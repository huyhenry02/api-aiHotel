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
            $roomType = $this->roomTypeRepo->find($data['id']);
            if (!$roomType) {
                throw new Exception('Room type not found');
            }
            $data = fractal($roomType, new RoomTypeTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
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
