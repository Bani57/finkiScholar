<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorships', function (Blueprint $table) {
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('paper_id');
            $table->boolean('is_lead_author');
            $table->string('part')->nullable();
            $table->timestamps();
            $table->primary(array('author_id', 'paper_id'));
            $table->foreign('author_id')->references('user_id')->on('authors')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('authorships');
    }
}
