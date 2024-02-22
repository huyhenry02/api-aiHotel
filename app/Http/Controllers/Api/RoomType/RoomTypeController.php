<?php

namespace App\Http\Controllers\Api\RoomType;

use App\Http\Controllers\ApiController;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Modules\Room\Repositories\Interfaces\RoomInterface;
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

    public function __construct( RoomTypeInterface $roomType, HotelInterface $hotel)
    {
        $this->roomTypeRepo = $roomType;
        $this->hotelRepo = $hotel;

    }

    public function createRoomType(CreateRoomTypeRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $roomType = $this->roomTypeRepo->create($data);
            DB::commit();
            $data = fractal($roomType, new RoomTypeTransformer())->toArray();
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
            $data = $request->validated();
            $roomType = $this->roomTypeRepo->find($data['room_type_id']);
            if (!$roomType) {
                return $this->respondError(__('messages.not_found'));

            }
            $data = fractal($roomType, new RoomTypeTransformer())->toArray();
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
            $roomType->save();
            $data = fractal($roomType, new RoomTypeTransformer())->toArray();
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
            $data = fractal($roomTypes, new RoomTypeTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
