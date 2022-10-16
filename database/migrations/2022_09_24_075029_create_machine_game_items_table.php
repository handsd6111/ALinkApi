<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_game_items', function (Blueprint $table) {
            $table->mediumInteger('MGI_sequence');
            $table->char('M_id', 12);
            $table->dateTime('MGI_start_game_time');
            $table->dateTime('MGI_end_game_time');
            $table->primary(['M_id', 'MGI_sequence']);
            $table->foreign('M_id')->references('M_id')->on('machines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_game_items', function (Blueprint $table) {
            $table->dropForeign('M_id');
        });
    }
};
