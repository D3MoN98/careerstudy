<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollegeTypeClassAndManyToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('college_type', ['college', 'university'])->nullable()->after('category_id');
            
            $table->bigInteger('programme_class_id')->unsigned()->index()->nullable()->after('semester');
            $table->foreign('programme_class_id')->references('id')->on('programme_classes')->onDelete('cascade');
            
            $table->enum('honour_passcourse', ['honours', 'pass_course'])->nullable()->after('programme_class_id');

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
            $table->dropColumn('college_type');
            $table->dropColumn('programme_class_id');
            $table->dropColumn('honour_passcourse');
        });
    }
}
