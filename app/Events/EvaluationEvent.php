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

class EvaluationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $evaluation;
    public  $type;
    public  $statistics;
    public function __construct($evaluation,$statistics)
    {
        $this->evaluation = $evaluation;
        $this->type = 'evaluation';
        $this->statistics = $statistics;
    }

    public function broadcastOn()
    {
        return new Channel('statistics');
    }

    public function broadcastAs()
    {
        return 'evaluation';
    }

    public function broadcastWith()
    {
        return [
            'evaluation' => $this->evaluation,
            'statistics' => $this->statistics,
            'type' => $this->type,
        ];
    }

}
