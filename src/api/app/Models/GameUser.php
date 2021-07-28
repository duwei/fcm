<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      schema="GameUser",
 *      required={"CharacterUID", "CharacterName", "Class", "Lev", "CombatPoint", "StageIdx"},
 *      @OA\Property(
 *          property="account",
 *          type="string",
 *          description="Game user account"
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Game user name"
 *      ),
 *      @OA\Property(
 *          property="id_card",
 *          type="string",
 *          description="Game user id card"
 *      ),
 * )
 */

/**
 * @OA\Schema(
 *      schema="UserStatus",
 *      @OA\Property(
 *          property="account",
 *          type="string",
 *          description="Game user account"
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Game user name"
 *      ),
 *      @OA\Property(
 *          property="age",
 *          type="integer",
 *          description="Game user age"
 *      ),
 *      @OA\Property(
 *          property="id_card",
 *          type="string",
 *          description="Game user id card"
 *      ),
 * )
 */

class GameUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'account', 'name', 'password', 'id_card', 'game_id', 'age'
    ];

    protected $hidden = [
        'password', 'created_at', 'updated_at', 'id', 'game_id'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = app('hash')->make($value);
    }

    public function game() {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
