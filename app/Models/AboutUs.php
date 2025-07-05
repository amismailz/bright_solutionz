<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AboutUs extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;
    protected $fillable = [
        'title',
        'vision',
        'description',
        'mission',
    ];

    public $translatable = [
        'title',
        'vision',
        'description',
        'mission',
    ];
}
