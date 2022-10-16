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
        Schema::create('share_machine_with_user', function (Blueprint $table) {
            $table->char('U_id', 13);
            $table->char('M_id', 12);
            $table->primary(['U_id', 'M_id']);
            $table->foreign('M_id')->references('M_id')->on('machines');
            $table->foreign('U_id')->references('U_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_machine_with_user', function (Blueprint $table) {
            $table->dropForeign('U_id');
            $table->dropForeign('M_id');
        });
    }
};
