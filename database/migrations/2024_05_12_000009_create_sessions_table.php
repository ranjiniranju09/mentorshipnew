<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('sessiondatetime');
            $table->string('sessionlink');
            $table->string('session_title');
            $table->string('session_duration_minutes')->nullable();
            $table->string('done');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
