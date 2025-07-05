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

class ReviewEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $review;
    public  $type;
    public  $statistics;

    public function __construct($review,$statistics)
    {
        $this->review = $review;
        $this->statistics = $statistics;
        $this->type = 'review';
    }

    public function broadcastOn()
    {
        return new Channel('statistics');
    }

    public function broadcastAs()
    {
        return 'review';
    }

    public function broadcastWith()
    {
        return [
            'review' => $this->review,
            'statistics' => $this->statistics,
            'type' => $this->type,
        ];
    }

}
