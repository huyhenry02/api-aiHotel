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
use App\Modules\Room\Requests\GetOneRoomTypeRequest;
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
        try{
            DB::beginTransaction();
            $postData = $request->validated();
            $postData['code'] = 0;
            $room = $this->roomRepo->create($postData);
            $floorNumber = $postData['floor'];
            $lastRoom = $this->roomRepo->getLastRoomOnFloor($floorNumber);
            if (!$lastRoom) {
                $room->code = $floorNumber . '01';
                $room->save();
            }else{
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

}
