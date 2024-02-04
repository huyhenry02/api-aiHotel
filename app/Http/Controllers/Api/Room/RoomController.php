<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Modules\Hotel\Requests\GetOneHotelRequest;
use App\Modules\Room\Repositories\Interfaces\RoomInterface;
use App\Modules\Room\Repositories\Interfaces\RoomTypeInterface;
use App\Modules\Room\Requests\CreateRoomRequest;
use App\Modules\Room\Requests\CreateRoomTypeRequest;
use App\Modules\Room\Requests\GetListRoomRequest;
use App\Modules\Room\Requests\GetOneRoomRequest;
use App\Modules\Room\Requests\GetOneRoomTypeRequest;
use App\Modules\Room\Requests\UpdateRoomRequest;
use App\Modules\Room\Requests\UpdateRoomTypeRequest;
use App\Modules\Room\Transformers\RoomTransformer;
use App\Modules\Room\Transformers\RoomTypeTransformer;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RoomController extends ApiController
{
    use AuthorizesRequests, ValidatesRequests;

    protected RoomInterface $roomRepo;
    protected RoomTypeInterface $roomTypeRepo;
    protected HotelInterface $hotelRepo;

    public function __construct(RoomInterface $room, RoomTypeInterface $roomType, HotelInterface $hotel)
    {
        $this->roomRepo = $room;
        $this->roomTypeRepo = $roomType;
        $this->hotelRepo = $hotel;

    }

    public function createRoom(CreateRoomRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $postData = $request->validated();
            $postData['code'] = 0;
            $room = $this->roomRepo->create($postData);
            $floorNumber = $postData['floor'];
            $lastRoom = $this->roomRepo->getLastRoomOnFloor($floorNumber);
            if (!$lastRoom) {
                $room->code = $floorNumber . '01';
                $room->save();
            } else {
                $lastRoomCode = $lastRoom->code;
                $lastRoomNumber = (int)substr($lastRoomCode, -2);
                $newRoomNumber = $lastRoomNumber + 1;
                $newRoomNumber = str_pad($newRoomNumber, 2, '0', STR_PAD_LEFT);
                $room->code = $floorNumber . $newRoomNumber;
                $room->save();
            }
            DB::commit();
            $data = fractal($room, new RoomTransformer())->toArray();
            return $this->respondSuccess($data);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function deleteRoom(GetOneRoomRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $postData = $request->validated();
            $room = $this->roomRepo->find($postData['room_id']);
            if (!$room) {
                return $this->respondError(__('messages.room_not_found'));
            }
            $room->delete();
            $response = $this->respondSuccess(__('messages.delete_successfully'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function updateRoom(UpdateRoomRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $postData = $request->validated();
            $room = $this->roomRepo->find($postData['room_id']);
            if (!$room) {
                return $this->respondError(__('messages.room_not_found'));
            }
            if (isset($postData['floor']) && $postData['floor'] != $room->floor) {
                $room->floor = $postData['floor'];
                $lastRoom = $this->roomRepo->getLastRoomOnFloor($room->floor);
                if (!$lastRoom) {
                    $room->code = $room->floor . '01';
                } else {
                    $lastRoomCode = $lastRoom->code;
                    $lastRoomNumber = (int)substr($lastRoomCode, -2);
                    $newRoomNumber = $lastRoomNumber + 1;
                    $newRoomNumber = str_pad($newRoomNumber, 2, '0', STR_PAD_LEFT);
                    $room->code = $room->floor . $newRoomNumber;
                }
                $room->save();
            }
            $room->update($postData);
            $data = fractal($room, new RoomTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getListRoom(GetListRoomRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $conditions = [];
            if (isset($postData['hotel_id'])) {
                $conditions['hotel_id'] = $postData['hotel_id'];
            }
            if (isset($postData['room_type_id'])) {
                $conditions['room_type_id'] = $postData['room_type_id'];
            }
            if (isset($postData['floor'])) {
                $conditions['floor'] = $postData['floor'];
            }
            $rooms = $this->roomRepo->getByParams(params: $conditions);
            $data = fractal($rooms, new RoomTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;

    }

}
