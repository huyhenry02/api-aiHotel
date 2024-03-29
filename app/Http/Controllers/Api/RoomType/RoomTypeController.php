<?php

namespace App\Http\Controllers\Api\RoomType;

use App\Http\Controllers\ApiController;
use App\Modules\File\Repositories\FileRepository;
use App\Modules\File\Repositories\Interfaces\FileInterface;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Modules\Room\Repositories\Interfaces\RoomInterface;
use App\Modules\RoomType\Models\RoomType;
use App\Modules\RoomType\Repositories\Interfaces\RoomTypeInterface;
use App\Modules\RoomType\Requests\CreateRoomTypeRequest;
use App\Modules\RoomType\Requests\GetListRoomTypeRequest;
use App\Modules\RoomType\Requests\GetOneRoomTypeRequest;
use App\Modules\RoomType\Requests\UpdateRoomTypeRequest;
use App\Modules\RoomType\Transformers\RoomTypeTransformer;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends ApiController
{
    use AuthorizesRequests, ValidatesRequests;

    protected RoomTypeInterface $roomTypeRepo;
    protected HotelInterface $hotelRepo;
    protected FileInterface $fileRepo;

    public function __construct(RoomTypeInterface $roomType, HotelInterface $hotel, FileInterface $fileRepo)
    {
        $this->roomTypeRepo = $roomType;
        $this->hotelRepo = $hotel;
        $this->fileRepo = $fileRepo;

    }

    public function createRoomType(CreateRoomTypeRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $roomType = $this->roomTypeRepo->create($postData);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filePath = $this->fileRepo->uploadFile($file, RoomType::class, $roomType->id, 'image');
                $postData['image'] = $filePath;
            }
            DB::commit();
            $data = fractal($roomType, new RoomTypeTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getOneRoomType(GetOneRoomTypeRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $roomType = $this->fileRepo->findWithFile(modelType: RoomType::class, modelId: $postData['room_type_id']);
            if (!$roomType) {
                return $this->respondError(__('messages.not_found'));

            }
            $data = fractal($roomType, new RoomTypeTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function updateRoomType(UpdateRoomTypeRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $roomType = $this->roomTypeRepo->find($postData['room_type_id']);
            if (!$roomType) {
                return $this->respondError(__('messages.not_found'));
            }
            $roomType->fill($postData);
            if (isset($postData['image'])) {
                $roomType->files()->delete();
                $file = $request->file('image');
                $filePath = $this->fileRepo->uploadFile($file, RoomType::class, $roomType->id, 'image');
                $postData['image'] = $filePath;
            }
            $roomType->save();
            $data = fractal($roomType, new RoomTypeTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function deleteRoomType(GetOneRoomTypeRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $roomType = $this->roomTypeRepo->find($request['room_type_id']);
            if (!$roomType) {
                throw new Exception('Room type not found');
            }
            $roomType->delete();
            $roomType->hotels()->detach();
            $roomType->files()->delete();
            DB::commit();
            $response = $this->respondSuccess('Room type deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getRoomTypes(GetListRoomTypeRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $perPage = $postData['per_page'] ?? 15;
            if (isset($postData['hotel_id'])) {
                $hotel = $this->hotelRepo->find($postData['hotel_id']);
                $roomTypes = $hotel->roomTypes()->paginate($perPage);
            } else {
                $roomTypes = $this->roomTypeRepo->paginate($perPage);
            }
            $data = fractal($roomTypes, new RoomTypeTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

}
