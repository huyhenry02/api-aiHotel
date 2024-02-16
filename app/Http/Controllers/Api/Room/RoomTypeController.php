<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Modules\Hotel\Requests\GetOneHotelRequest;
use App\Modules\Room\Repositories\Interfaces\RoomInterface;
use App\Modules\Room\Repositories\Interfaces\RoomTypeInterface;
use App\Modules\Room\Requests\CreateRoomTypeRequest;
use App\Modules\Room\Requests\GetOneRoomTypeRequest;
use App\Modules\Room\Requests\UpdateRoomTypeRequest;
use App\Modules\Room\Transformers\RoomTypeTransformer;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends ApiController
{
    use AuthorizesRequests, ValidatesRequests;

    protected RoomTypeInterface $roomTypeRepo;

    public function __construct(RoomInterface $room, RoomTypeInterface $roomType, HotelInterface $hotel)
    {
        $this->roomTypeRepo = $roomType;

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

    public function getRoomTypes(PaginationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated('per_page', '15');
            $roomTypes = $this->roomTypeRepo->getData(perPage: $postData);
            $data = fractal($roomTypes, new RoomTypeTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
