<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\File\Repositories\Interfaces\FileInterface;
use App\Modules\Hotel\Models\Hotel;
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
    protected FileInterface $fileRepo;

    public function __construct(HotelInterface $hotel, FileInterface $file)
    {
        $this->hotelRepo = $hotel;
        $this->fileRepo = $file;
    }
    public function getListHotels(PaginationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated('per_page',15);
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
            $hotel = $this->hotelRepo->findWithBanner($request['hotel_id']);
            $data = fractal($hotel, new HotelTransformer())->parseIncludes(['files'])->toArray();
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
            $hotel = $this->hotelRepo->create($postData);
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filePath = $this->fileRepo->uploadFile($file, Hotel::class, $hotel->id, 'banner');
                $postData['banner'] = $filePath;
            }
            $data = fractal($hotel, new HotelTransformer())->parseIncludes(['files'])->toArray();
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
            $hotel = $this->hotelRepo->find($request['hotel_id']);
            $postData = $request->validated();
            $hotel->fill($postData);
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
            $response = $this->respondSuccess(__('messages.delete_successfully'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
