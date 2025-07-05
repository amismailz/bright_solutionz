<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscriber extends Command
{
    protected $signature = 'redis:subscribe';

    protected $description = 'Subscribe to Redis item-events channel';

    public function handle()
    {
        $this->info('Listening to Redis channel item-events...');

        Redis::subscribe(['item-events'], function ($message) {
            $this->info('Received message: ' . $message);

            // Here you can:
            // - Save message to DB or cache
            // - Broadcast via WebSocket
            // - Push to some queue
        });
    }
}