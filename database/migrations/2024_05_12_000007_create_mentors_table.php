<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentorsTable extends Migration
{
    public function up()
    {
        Schema::create('mentors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('mobile')->unique();
            $table->string('companyname');
            $table->string('skills');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
