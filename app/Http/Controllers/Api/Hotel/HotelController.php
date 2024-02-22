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
            $postData = $request->validated('per_page', 15);
            $hotels = $this->hotelRepo->getData(perPage: $postData);
            $data = fractal($hotels, new HotelTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getOneHotel(GetOneHotelRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $hotel = $this->fileRepo->findWithFile(modelType: Hotel::class, modelId: $postData['hotel_id']);
            if (!$hotel) {
                throw new Exception(__('messages.not_found'));
            }
            $data = fractal($hotel, new HotelTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function createHotel(CreateHotelRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $room_types = $postData['room_types'];
            $hotel = $this->hotelRepo->create($postData);
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filePath = $this->fileRepo->uploadFile($file, Hotel::class, $hotel->id, 'banner');
                $postData['banner'] = $filePath;
            }
            $hotel->roomTypes()->sync($room_types);
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
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $hotel = $this->hotelRepo->find($postData['hotel_id']);
            if (!$hotel) {
                return $this->respondError(__('messages.not_found'));
            }
            $hotel->fill($postData);
            if (isset($postData['room_types'])) {
                $hotel->roomTypes()->sync($postData['room_types']);
            }
            if (isset($postData['banner'])) {
                $hotel->files()->delete();
                $file = $request->file('banner');
                $filePath = $this->fileRepo->uploadFile($file, Hotel::class, $hotel->id, 'banner');
                $postData['banner'] = $filePath;
            }
            $hotel->save();
            $data = fractal($hotel, new HotelTransformer())->parseIncludes(['files'])->toArray();
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
            $hotel->files()->delete();
            $response = $this->respondSuccess(__('messages.delete_successfully'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
