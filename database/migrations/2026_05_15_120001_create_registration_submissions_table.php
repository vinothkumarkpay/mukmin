<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('registration_submissions', function (Blueprint $table) {
            $table->id();

            // Section A: Organisation Category (multi-select)
            $table->json('organization_categories');
            $table->string('organization_category_other')->nullable();

            // Section B: Organisation Details
            $table->string('registered_name');
            $table->text('address');
            $table->string('postcode');
            $table->string('district');
            $table->string('state');
            $table->string('registration_number');
            $table->date('registration_date');
            $table->string('email');
            $table->string('contact_number');
            $table->string('website')->nullable();

            // Section C: Organisation Profile
            $table->json('organization_profile_types');
            $table->string('organization_profile_other')->nullable();
            $table->json('primary_focus_areas');
            $table->string('primary_focus_other')->nullable();

            // Section D: Governance & Legal
            $table->boolean('is_registered_with_ros')->default(false);
            $table->string('registration_certificate_path')->nullable();
            $table->string('committee_members_path')->nullable();

            // Section E: Key Office Bearers
            $table->json('president_details');
            $table->json('secretary_details');
            $table->json('treasurer_details')->nullable();

            // Section G: Authorised Signatories
            $table->json('signatories');

            // Section F: Declaration
            $table->boolean('agreed_to_declaration')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registration_submissions');
    }
}
