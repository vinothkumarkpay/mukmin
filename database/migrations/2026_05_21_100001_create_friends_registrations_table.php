<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends_registrations', function (Blueprint $table) {
            $table->id();
            
            $table->string('organization_category');
            $table->string('organization_category_other')->nullable();
            
            $table->string('org_name')->nullable();
            $table->string('org_registration_number')->nullable();
            $table->string('org_state')->nullable();
            $table->text('org_address')->nullable();
            $table->string('org_email')->nullable();
            $table->string('org_contact_number')->nullable();
            $table->string('org_website')->nullable();
            
            $table->string('individual_name')->nullable();
            $table->string('individual_nric')->nullable();
            $table->string('individual_state')->nullable();
            $table->text('individual_address')->nullable();
            $table->string('individual_email')->nullable();
            $table->string('individual_contact_number')->nullable();
            
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
        Schema::dropIfExists('friends_registrations');
    }
};
