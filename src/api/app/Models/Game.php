<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *      schema="Game",
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Game name"
 *      ),
 *      @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Game uuid"
 *      ),
 *      @OA\Property(
 *          property="min_age",
 *          type="integer",
 *          description="Minimum player age"
 *      ),
 *      @OA\Property(
 *          property="open_time",
 *          type="string",
 *          description="Game open time"
 *      ),
 *      @OA\Property(
 *          property="close_time",
 *          type="string",
 *          description="Game close time"
 *      ),
 *      @OA\Property(
 *          property="max_hour_weekday",
 *          type="number",
 *          description="Maximum game hours in weekday"
 *      ),
 *      @OA\Property(
 *          property="max_hour_weekend",
 *          type="number",
 *          description="Maximum game hours in weekend"
 *      ),
 *      @OA\Property(
 *          property="max_money_daily",
 *          type="number",
 *          description="Maximum daily recharge amount"
 *      ),
 *      @OA\Property(
 *          property="max_money_monthly",
 *          type="number",
 *          description="Maximum monthly recharge amount"
 *      ),
 *      @OA\Property(
 *          property="start_prompt",
 *          type="string",
 *          description="start prompt"
 *      ),
 *      @OA\Property(
 *          property="out_of_time_prompt",
 *          type="string",
 *          description="out of time prompt"
 *      ),
 *      @OA\Property(
 *          property="time_limit_prompt",
 *          type="string",
 *          description="time limit prompt"
 *      ),
 *      @OA\Property(
 *          property="money_limit_prompt",
 *          type="string",
 *          description="money limit prompt"
 *      ),
 * )
 */

class Game extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'key',
        'created_at',
        'updated_at',
        'uuid',
    ];

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
