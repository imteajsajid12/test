<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steadfasts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_url')->nullable();
            $table->string('api_key')->unique();
            $table->string('api_secret')->nullable();
            $table->string('status')->default(0);
            $table->string('is_active')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
