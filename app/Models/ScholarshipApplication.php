<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'ic_passport',
        'dob',
        'gender',
        'nationality',
        'contact_number',
        'email',
        'education_level',
        'institution_name',
        'field_of_study',
        'current_cgpa',
        'parent_occupation',
        'household_income',
        'dependents',
        'ic_path',
        'transcript_path',
        'income_proof_path',
        'agreed_to_declaration',
    ];

    protected $casts = [
        'dob' => 'date',
        'dependents' => 'integer',
        'agreed_to_declaration' => 'boolean',
    ];
}
