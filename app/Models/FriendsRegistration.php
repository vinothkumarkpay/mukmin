<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendsRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_category',
        'organization_category_other',
        'org_name',
        'org_registration_number',
        'org_state',
        'org_address',
        'org_email',
        'org_contact_number',
        'org_website',
        'individual_name',
        'individual_nric',
        'individual_state',
        'individual_address',
        'individual_email',
        'individual_contact_number',
        'agreed_to_declaration',
    ];

    protected $casts = [
        'agreed_to_declaration' => 'boolean',
    ];
}
