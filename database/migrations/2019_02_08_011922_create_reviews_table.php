<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->unsignedInteger('reviewer_id');
            $table->unsignedInteger('paper_id');
            $table->integer('score');
            $table->text('comment')->nullable();
            $table->boolean('passed');
            $table->timestamps();
            $table->primary(array('reviewer_id', 'paper_id'));
            $table->foreign('reviewer_id')->references('user_id')->on('reviewers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
