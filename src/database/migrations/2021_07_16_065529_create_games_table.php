<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uuid')->unique();
            $table->string('key');
            $table->integer('min_age');
            $table->datetime('open_time');
            $table->dateTime('close_time');
            $table->decimal('max_hour_weekday');
            $table->decimal('max_hour_weekend');
            $table->decimal('max_money_daily');
            $table->decimal('max_money_monthly');
            $table->longText('start_prompt');
            $table->longText('out_of_time_prompt');
            $table->longText('time_limit_prompt');
            $table->longText('money_limit_prompt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
