<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->renameColumn('max_money_daily', 'max_money_onetime');
            $table->decimal('max_money_onetime_l2');
            $table->decimal('max_money_monthly_l2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->renameColumn('max_money_onetime', 'max_money_daily');
            $table->dropColumn('max_money_onetime_l2');
            $table->dropColumn('max_money_monthly_l2');
        });
    }
}
