<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('question_text')->nullable(); // Question text, nullable
            $table->integer('points')->nullable(); // Points for the question, nullable
            $table->enum('mcq', ['Yes', 'No'])->default('No'); // Indicates if it's a multiple-choice question
            $table->unsignedBigInteger('test_id')->nullable(); // Foreign key to tests table
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); // Soft delete column
        
            // Define the foreign key relationship
            $table->foreign('test_id')
                  ->references('id')
                  ->on('tests')
                  ->onDelete('set null'); // Sets test_id to NULL if the related test is deleted
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
