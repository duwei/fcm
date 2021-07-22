<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
                $model->key = Str::uuid();
            }
        });
    }
}
