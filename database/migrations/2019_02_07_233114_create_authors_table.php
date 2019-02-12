<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('organization_name');
            $table->integer('organization_type');
            $table->string('organization_country');
            $table->string('organization_address');
            $table->string('organization_email')->nullable();
            $table->string('organization_telephone')->nullable();
            $table->timestamps();
            $table->primary('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
}
