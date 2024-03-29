<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\ApiController;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Modules\Reservation\Transformers\ReservationRoomTransformer;
use App\Modules\Room\Repositories\Interfaces\RoomInterface;
use App\Modules\Room\Requests\CreateRoomRequest;
use App\Modules\Room\Requests\GetListRoomRequest;
use App\Modules\Room\Requests\GetOneRoomRequest;
use App\Modules\Room\Requests\UpdateRoomRequest;
use App\Modules\Room\Transformers\RoomTransformer;
use App\Modules\RoomType\Repositories\Interfaces\RoomTypeInterface;
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
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $postData['code'] = 0;
            $room = $this->roomRepo->create($postData);
            $lastRoom = $this->roomRepo->getLastRoomOnFloor(floorNumber: $postData['floor'], hotelId: $postData['hotel_id']);
            $this->roomRepo->generateCodeRoom(lastRoom: $lastRoom, floorNumber: $postData['floor'], room: $room);
            $room->save();
            DB::commit();
            $data = fractal($room, new RoomTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function deleteRoom(GetOneRoomRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
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
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $room = $this->roomRepo->find($postData['room_id']);
            if (!$room) {
                return $this->respondError(__('messages.room_not_found'));
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
    public function getRoomDetail(GetOneRoomRequest $request): JsonResponse
    {
        $postData = $request->validated();
        $roomId = $postData['room_id'];
        $room = $this->roomRepo->find($roomId);
        if (!$room) {
            return $this->respondError(__('messages.room_not_found'));
        }
        $reservations = $this->roomRepo->getListCurrentReservationInRoom(roomId: $roomId);
        $dataReservations = fractal($reservations, new ReservationRoomTransformer())->toArray();
        $roomDetail = fractal($room, new RoomTransformer())->toArray();
        $roomDetail['reservations'] = $dataReservations;
        return $this->respondSuccess($roomDetail);
    }

}
