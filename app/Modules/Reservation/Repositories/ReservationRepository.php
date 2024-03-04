<?php

namespace App\Modules\Reservation\Repositories;

use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Repositories\Interfaces\ReservationInterface;
use App\Modules\Room\Models\Room;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;


class ReservationRepository extends BaseRepository implements ReservationInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Reservation::class;
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function filterReservation(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->_model->query();
        $query->select('reservations.*', 'rooms.room_type_id', 'rooms.hotel_id');
        if (isset($filters['hotel_id']) || isset($filters['room_type_id'])) {
            $query->join('rooms', 'reservations.room_id', '=', 'rooms.id');

            if (isset($filters['hotel_id'])) {
                $query->where('rooms.hotel_id', $filters['hotel_id']);
            }

            if (isset($filters['room_type_id'])) {
                $query->where('rooms.room_type_id', $filters['room_type_id']);
            }
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }

        if (isset($filters['phone'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('phone', $filters['phone']);
            });
        }
        return $query->paginate($perPage);
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return mixed
     */
    public function getReservationStatistics(DateTime $startDate, DateTime $endDate): mixed
    {
        return $this->_model->whereBetween('created_at', [$startDate, $endDate])->get();
    }

    /**
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getMyReservations(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->_model->query();
        $query->where('user_id', $userId);
        return $query->paginate($perPage);
    }
}
