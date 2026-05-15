<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            
            // Section 1: Applicant Information
            $table->string('full_name');
            $table->string('ic_passport');
            $table->date('dob');
            $table->string('gender');
            $table->string('nationality');
            $table->string('contact_number');
            $table->string('email');
            
            // Section 2: Educational Background
            $table->string('education_level');
            $table->string('institution_name');
            $table->string('field_of_study');
            $table->string('current_cgpa');
            
            // Section 3: Financial Information
            $table->string('parent_occupation');
            $table->string('household_income');
            $table->integer('dependents');
            
            // Section 4: Supporting Documents
            $table->string('ic_path')->nullable();
            $table->string('transcript_path')->nullable();
            $table->string('income_proof_path')->nullable();
            
            // Section 5: Declaration
            $table->boolean('agreed_to_declaration')->default(false);

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
        Schema::dropIfExists('scholarship_applications');
    }
}
