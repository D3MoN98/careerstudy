<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('college_id')->unsigned()->index()->nullable();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
            
            $table->bigInteger('college_stream_id')->unsigned()->index()->nullable();
            $table->foreign('college_stream_id')->references('id')->on('college_streams')->onDelete('cascade');


            // $table->integer('college_id')->unsigned()->nullable();

            // $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');

            // $table->integer('college_stream_id')->unsigned()->nullable();
            // $table->foreign('college_stream_id')->references('id')->on('college_streams')->onDelete('cascade');

            $table->integer('semester')->nullable();


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
        Schema::dropIfExists('students');
    }
}
