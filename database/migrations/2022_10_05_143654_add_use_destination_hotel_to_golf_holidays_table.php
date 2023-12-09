<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUseDestinationHotelToGolfHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('golf_holidays', function (Blueprint $table) {
            $table->boolean('use_destination_hotel')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('golf_holidays', function (Blueprint $table) {
            $table->dropColumn('use_destination_hotel');
        });
    }
}
