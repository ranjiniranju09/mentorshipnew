<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenteesTable extends Migration
{
    public function up()
    {
        Schema::create('mentees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('mobile');
            $table->date('dob')->nullable();
            $table->string('skilss')->nullable();
            $table->string('interestedskills');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
