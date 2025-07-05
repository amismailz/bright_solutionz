<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MovementEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $movement;
    public  $statistics;
    public  $type;

    public function __construct($movement,$statistics,$type='movement')
    {
        $this->movement = $movement;
        $this->statistics = $statistics;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new Channel('statistics');
    }

    public function broadcastAs()
    {
        return 'movement';
    }

    public function broadcastWith()
    {
        return [
            'movement' => $this->movement,
            'statistics' => $this->statistics,
            'type' => $this->type,
        ];
    }

}
