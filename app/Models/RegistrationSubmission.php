<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_categories',
        'organization_category_other',
        'registered_name',
        'address',
        'postcode',
        'district',
        'state',
        'registration_number',
        'registration_date',
        'email',
        'contact_number',
        'website',
        'organization_profile_types',
        'organization_profile_other',
        'primary_focus_areas',
        'primary_focus_other',
        'is_registered_with_ros',
        'registration_certificate_path',
        'committee_members_path',
        'president_details',
        'secretary_details',
        'treasurer_details',
        'signatories',
        'agreed_to_declaration',
    ];

    protected $casts = [
        'organization_categories' => 'array',
        'organization_profile_types' => 'array',
        'primary_focus_areas' => 'array',
        'registration_date' => 'date',
        'is_registered_with_ros' => 'boolean',
        'agreed_to_declaration' => 'boolean',
        'president_details' => 'array',
        'secretary_details' => 'array',
        'treasurer_details' => 'array',
        'signatories' => 'array',
    ];
}
