<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Reservation\Repositories\Interfaces\ReservationInterface;
use App\Modules\Reservation\Requests\CreateReservationRequest;
use App\Modules\Reservation\Requests\FilterReservationRequest;
use App\Modules\Reservation\Requests\GetOneReservationRequest;
use App\Modules\Reservation\Requests\UpdateReservationRequest;
use App\Modules\Reservation\Transformers\ReservationTransformer;
use App\Modules\Room\Repositories\Interfaces\RoomInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends ApiController
{
    protected ReservationInterface $reservationRepo;
    protected RoomInterface $roomRepo;

    public function __construct(ReservationInterface $reservationRepo, RoomInterface $roomRepo)
    {
        $this->reservationRepo = $reservationRepo;
        $this->roomRepo = $roomRepo;
    }

    public function createReservation(CreateReservationRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $postData['user_id'] = Auth::id();
            $postData['code'] = 0;
            $postData['status'] = ReservationStatusEnum::PENDING->value;
            $reservation = $this->reservationRepo->create($postData);
            $reservation->code = 'RSV' . $reservation->id;
            $reservation->save();
            $data = fractal($reservation, new ReservationTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage(), 500);
        }
        return $response;
    }

    public function updateReservation(UpdateReservationRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $reservation = $this->reservationRepo->find($postData['reservation_id']);
            if (!$reservation) {
                return $this->respondError(__('messages.not_found'));
            }
            $reservation->fill($postData);
            $reservation->save();
            $data = fractal($reservation, new ReservationTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage(), 500);
        }
        return $response;

    }

    public function checkIn(UpdateReservationRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $reservation = $this->reservationRepo->find($postData['reservation_id']);
            if (!$reservation) {
                return $this->respondError(__('messages.not_found'));
            }
            if ($reservation->status === ReservationStatusEnum::REJECTED->value) {
                return $this->respondError(__('messages.reservation_rejected'));
            }
            $reservation->check_in = now();
            $reservation->status = ReservationStatusEnum::PROCESSING->value;
            $reservation->save();
            $data = fractal($reservation, new ReservationTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage(), 500);
        }
        return $response;
    }

    public function checkOut(UpdateReservationRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $reservation = $this->reservationRepo->find($postData['reservation_id']);
            if (!$reservation) {
                return $this->respondError(__('messages.not_found'));
            }
            if ($reservation->status !== ReservationStatusEnum::PROCESSING->value) {
                return $this->respondError(__('messages.reservation_not_processing'));
            }
            $reservation->check_out = now();
            $reservation->status = ReservationStatusEnum::COMPLETED->value;
            $reservation->save();
            $data = fractal($reservation, new ReservationTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage(), 500);
        }
        return $response;
    }

    public function getOneReservation(GetOneReservationRequest $request): JsonResponse
    {
        try {
            $reservation = $this->reservationRepo->find($request['reservation_id']);
            if (!$reservation) {
                return $this->respondError(__('messages.not_found'));
            }
            $data = fractal($reservation, new ReservationTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getListReservations(PaginationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated('per_page', 15);
            $hotels = $this->reservationRepo->getData(perPage: $postData);
            $data = fractal($hotels, new ReservationTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function filterReservation(FilterReservationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $reservations = $this->reservationRepo->filterReservation(filters: $postData);
            $data = fractal($reservations, new ReservationTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
