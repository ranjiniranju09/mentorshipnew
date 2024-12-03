<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('module_id')->nullable(); // Foreign key to modules
            $table->unsignedBigInteger('chapter_id')->nullable(); // Foreign key to chapters
            $table->unsignedBigInteger('course_id')->nullable(); // Foreign key to courses
            $table->unsignedBigInteger('lesson_id')->nullable(); // Foreign key to lessons
            $table->string('title')->nullable(); // Test title
            $table->longText('description')->nullable(); // Test description
            $table->boolean('is_published')->default(0); // Publish status (0 = unpublished, 1 = published)
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); // deleted_at field for soft deletes
        
            // Define foreign key relationships
            $table->foreign('module_id')
                  ->references('id')
                  ->on('modules')
                  ->onDelete('set null'); // Set module_id to NULL if the module is deleted
        
            $table->foreign('chapter_id')
                  ->references('id')
                  ->on('chapters')
                  ->onDelete('set null'); // Set chapter_id to NULL if the chapter is deleted
        
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('set null'); // Set course_id to NULL if the course is deleted
        
            $table->foreign('lesson_id')
                  ->references('id')
                  ->on('lessons')
                  ->onDelete('set null'); // Set lesson_id to NULL if the lesson is deleted
        });
        
    }
}
