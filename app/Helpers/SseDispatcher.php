<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class SseDispatcher
{
    public static function dispatch(array $payload): void
    {
        $data = [
            'timestamp' => now()->timestamp,
            'payload'   => $payload,
        ];

        // 🔁 Choose method here:
        // self::writeToFile($data);
        self::publishToRedis($data);
    }

    protected static function writeToFile(array $data): void
    {
        Storage::put('sse-trigger.json', json_encode($data));
    }

    protected static function publishToRedis(array $data): void
    {
        Redis::publish('item-events', json_encode($data));
    }
}
