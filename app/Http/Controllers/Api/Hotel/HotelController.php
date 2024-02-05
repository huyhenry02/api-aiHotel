<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Modules\Hotel\Requests\CreateHotelRequest;
use App\Modules\Hotel\Requests\GetOneHotelRequest;
use App\Modules\Hotel\Requests\UpdateHotelRequest;
use App\Modules\Hotel\Transformers\HotelTransformer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HotelController extends ApiController
{
    protected HotelInterface $hotelRepo;

    public function __construct(HotelInterface $hotel)
    {
        $this->hotelRepo = $hotel;
    }

    public function getListHotels(PaginationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated('per_page', 15);
            $hotels = $this->hotelRepo->getData(perPage: $postData);
            $data = fractal($hotels, new HotelTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getOneHotel(GetOneHotelRequest $request): JsonResponse
    {
        try {
            $hotel = $this->hotelRepo->find($request['hotel_id']);
            if (!$hotel) {
                throw new Exception(__('messages.not_found'));
            }
            $data = fractal($hotel, new HotelTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function createHotel(CreateHotelRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $postData = $request->validated();
            $room_types = $postData['room_types'];
            $hotel = $this->hotelRepo->create($postData);
            $hotel->roomTypes()->sync($room_types);
            $data = fractal($hotel, new HotelTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function updateHotel(UpdateHotelRequest $request): JsonResponse
    {

        try {
            DB::beginTransaction();
            $postData = $request->validated();
            $hotel = $this->hotelRepo->find($postData['hotel_id']);
            if (!$hotel) {
                return $this->respondError(__('messages.not_found'));
            }
            $hotel->fill($postData);
            $hotel->roomTypes()->sync($postData['room_types']);
            $hotel->save();
            $data = fractal($hotel, new HotelTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function deleteHotel(GetOneHotelRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $hotel = $this->hotelRepo->find($request['hotel_id']);
            $hotel->delete();
            $hotel->roomTypes()->detach();
            $response = $this->respondSuccess(__('messages.delete_successfully'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
