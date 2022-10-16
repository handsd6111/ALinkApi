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
        Schema::create('users', function (Blueprint $table) {
            $table->char('U_id', 13)->primary();
            $table->string('U_name', 20);
            $table->string('U_email', 32);
            $table->string('U_phone', 10);
            $table->string('U_password', 128);
            $table->string('identity', 12);
            $table->foreign('identity')->references('I_id')->on('identities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users', function (Blueprint $table) {
            $table->dropForeign('identity');
        });
    }
};
