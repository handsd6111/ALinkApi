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
        Schema::create('expected_items', function (Blueprint $table) {
            $table->mediumInteger('MGI_sequence');
            $table->char('M_id', 12);
            $table->tinyInteger('EI_sequence');
            $table->string('EI_data');
            $table->primary(['M_id', 'MGI_sequence', 'EI_sequence']);
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
        Schema::dropIfExists('expected_items', function (Blueprint $table) {
            $table->dropForeign(['M_id', 'MGI_sequence']);
        });
    }
};
