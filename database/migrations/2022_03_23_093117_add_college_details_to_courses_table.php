<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollegeDetailsToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->bigInteger('college_id')->unsigned()->index()->nullable()->after('category_id');
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
            
            $table->bigInteger('college_stream_id')->unsigned()->index()->nullable()->after('college_id');
            $table->foreign('college_stream_id')->references('id')->on('college_streams')->onDelete('cascade');

            $table->integer('semester')->nullable()->after('college_stream_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('college_id');
            // $table->dropColumn('college_stream_id');
            // $table->dropColumn('semester');
        });
    }
}
