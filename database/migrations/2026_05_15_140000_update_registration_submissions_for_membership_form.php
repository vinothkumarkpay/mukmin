<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRegistrationSubmissionsForMembershipForm extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('registration_submissions')) {
            return;
        }

        if (Schema::hasColumn('registration_submissions', 'organization_categories')) {
            return;
        }

        Schema::table('registration_submissions', function (Blueprint $table) {
            $table->json('organization_categories')->nullable()->after('id');
            $table->string('organization_category_other')->nullable()->after('organization_categories');
            $table->string('postcode')->nullable()->after('address');
            $table->string('district')->nullable()->after('postcode');
            $table->string('state')->nullable()->after('district');
            $table->date('registration_date')->nullable()->after('registration_number');
            $table->json('organization_profile_types')->nullable()->after('website');
            $table->string('organization_profile_other')->nullable()->after('organization_profile_types');
            $table->json('primary_focus_areas')->nullable()->after('organization_profile_other');
            $table->string('primary_focus_other')->nullable()->after('primary_focus_areas');
            $table->boolean('is_registered_with_ros')->default(false)->after('primary_focus_other');
            $table->json('signatories')->nullable()->after('treasurer_details');
        });

        Schema::table('registration_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('registration_submissions', 'organization_type')) {
                $table->dropColumn('organization_type');
            }
            if (Schema::hasColumn('registration_submissions', 'profile_type')) {
                $table->dropColumn('profile_type');
            }
            if (Schema::hasColumn('registration_submissions', 'target_beneficiary')) {
                $table->dropColumn('target_beneficiary');
            }
            if (Schema::hasColumn('registration_submissions', 'has_mukmin_membership')) {
                $table->dropColumn('has_mukmin_membership');
            }
        });
    }

    public function down()
    {
        // Intentionally left minimal — reverting would lose data shape.
    }
}
