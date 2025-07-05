<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPoint extends Model
{
    protected $fillable =
        [
            'user_id',
            'point_id',
        ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class,'point_id');
    }

}
