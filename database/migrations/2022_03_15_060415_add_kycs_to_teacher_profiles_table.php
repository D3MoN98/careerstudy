<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKycsToTeacherProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->string('subject_specialist', 191)->nullable()->after('description');
            $table->string('qualification_details', 191)->nullable()->after('subject_specialist');
            $table->string('teaching_details', 191)->nullable()->after('qualification_details');
            $table->text('profile_photo')->nullable()->after('teaching_details');
            $table->text('signature')->nullable()->after('profile_photo');
            $table->text('qualification_certificate')->nullable()->after('signature');
            $table->text('bank_passbook')->nullable()->after('qualification_certificate');
            $table->text('resume')->nullable()->after('bank_passbook');
            $table->boolean('kyc_submitted')->default(false)->after('resume');
            $table->boolean('approved')->default(false)->after('kyc_submitted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn('subject_specialist');
            $table->dropColumn('qualification_details');
            $table->dropColumn('teaching_details');
            $table->dropColumn('profile_photo');
            $table->dropColumn('signature');
            $table->dropColumn('qualification_certificate');
            $table->dropColumn('bank_passbook');
            $table->dropColumn('resume');
            $table->dropColumn('approved');
        });
    }
}
