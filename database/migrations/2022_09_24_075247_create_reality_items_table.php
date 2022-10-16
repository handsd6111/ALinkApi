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
        Schema::create('reality_items', function (Blueprint $table) {
            $table->mediumInteger('MGI_sequence');
            $table->char('M_id', 12);
            $table->tinyInteger('RI_sequence');
            $table->string('RI_data')->nullable();
            $table->primary(['M_id', 'MGI_sequence', 'RI_sequence']);
            $table->foreign(['M_id', 'MGI_sequence'])->references(['M_id', 'MGI_sequence'])->on('machine_game_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reality_items', function (Blueprint $table) {
            $table->dropForeign(['M_id', 'MGI_sequence']);
        });
    }
};
