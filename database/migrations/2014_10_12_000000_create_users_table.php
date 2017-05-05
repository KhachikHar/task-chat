<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->length(20);
            $table->string('email')->unique()->length(50);
            $table->string('password')->length(100);
            $table->boolean('activate')->default(0);
            $table->boolean('online')->default(0);
            $table->string('token')->nullable()->lngth(100);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
