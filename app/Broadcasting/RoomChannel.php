<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomChannel implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $roomId;
    private $startDate;
    private $endDate;

    /**
     * Create a new channel instance.
     */
    public function __construct($roomId, $startDate, $endDate)
    {
        $this->roomId = $roomId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(): array|bool
    {
        return true;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return ['room_id' => $this->roomId, 'start_date' => $this->startDate, 'end_date' => $this->endDate];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'booked.event.' . $this->roomId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('booked-channel');
    }
}
