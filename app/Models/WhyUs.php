<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
class WhyUs extends Model
{
     use HasFactory, HasTranslations, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'image'

    ];

    public $translatable = [
        'title',
        'description',
    ];
}
