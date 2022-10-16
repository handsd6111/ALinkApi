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
        Schema::create('machines', function (Blueprint $table) {
            $table->char('M_id', 12)->primary();
            $table->string('M_name', 20);
            $table->string('M_description', 50)->nullable();
            $table->string('M_image', 100)->nullable();
            $table->boolean('M_online_status')->default(false);
            $table->char('owner', 13)->nullable();
            $table->string('machine_type', 5);
            $table->timestamps();
            $table->foreign('owner')->references('U_id')->on('users');
            $table->foreign('machine_type')->references('MT_id')->on('machine_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machines', function (Blueprint $table) {
            $table->dropForeign('owner');
            $table->dropForeign('machine_type');
        });
    }
};
