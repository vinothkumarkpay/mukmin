<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ContactSubmission;
use App\Models\RegistrationSubmission;
use App\Models\ScholarshipApplication;

class FormSubmissionController extends Controller
{
    public function showContactForm()
    {
        return view('pages.contact');
    }

    public function submitContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        ContactSubmission::create($validated);

        return redirect()->back()->with('success', 'Thank you for getting in touch. We will respond shortly.');
    }

    public function showRegistrationForm()
    {
        return view('pages.register');
    }

    public function submitRegistrationForm(Request $request)
    {
        $categoryOptions = ['NGO', 'Masjid', 'Surau', 'Madrasah', 'Education Institution', 'Others'];
        $profileOptions = ['Non-profit / NGO', 'Religious body', 'Educational body', 'Charity', 'Social', 'Professional Body', 'Foundation', 'Others'];
        $focusOptions = ['Education / Training', 'Welfare / Charity', 'Human Rights', 'Youth Development', 'Women Empowerment', 'Community Services', 'Others'];

        $validated = $request->validate([
            'organization_categories' => ['required', 'array', 'min:1'],
            'organization_categories.*' => ['string', Rule::in($categoryOptions)],
            'organization_category_other' => [
                Rule::requiredIf(fn () => in_array('Others', $request->input('organization_categories', []), true)),
                'nullable', 'string', 'max:255',
            ],

            'registered_name' => 'required|string|max:255',
            'address' => 'required|string',
            'postcode' => 'required|string|max:20',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:255',
            'website' => 'nullable|string|max:500',

            'organization_profile_types' => ['required', 'array', 'min:1'],
            'organization_profile_types.*' => ['string', Rule::in($profileOptions)],
            'organization_profile_other' => [
                Rule::requiredIf(fn () => in_array('Others', $request->input('organization_profile_types', []), true)),
                'nullable', 'string', 'max:255',
            ],
            'primary_focus_areas' => ['required', 'array', 'min:1'],
            'primary_focus_areas.*' => ['string', Rule::in($focusOptions)],
            'primary_focus_other' => [
                Rule::requiredIf(fn () => in_array('Others', $request->input('primary_focus_areas', []), true)),
                'nullable', 'string', 'max:255',
            ],

            'is_registered_with_ros' => 'required|boolean',
            'registration_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'committee_members' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            'president_name' => 'required|string|max:255',
            'president_phone' => 'required|string|max:255',
            'president_email' => 'required|email|max:255',

            'secretary_name' => 'required|string|max:255',
            'secretary_phone' => 'required|string|max:255',
            'secretary_email' => 'required|email|max:255',

            'treasurer_name' => 'nullable|string|max:255',
            'treasurer_phone' => 'nullable|string|max:255',
            'treasurer_email' => 'nullable|email|max:255',

            'signatories' => 'required|array|size:2',
            'signatories.0.name' => 'required|string|max:255',
            'signatories.0.position' => 'required|string|max:255',
            'signatories.0.date' => 'required|date',
            'signatories.1.name' => 'required|string|max:255',
            'signatories.1.position' => 'required|string|max:255',
            'signatories.1.date' => 'required|date',

            'agreed_to_declaration' => 'accepted',
        ]);

        $regCertPath = $request->file('registration_certificate')->store('submissions/registrations', 'public');
        $committeePath = $request->file('committee_members')->store('submissions/registrations', 'public');

        $treasurerDetails = null;
        if (! empty($validated['treasurer_name'])) {
            $treasurerDetails = [
                'name' => $validated['treasurer_name'],
                'phone' => $validated['treasurer_phone'] ?? '',
                'email' => $validated['treasurer_email'] ?? '',
            ];
        }

        RegistrationSubmission::create([
            'organization_categories' => $validated['organization_categories'],
            'organization_category_other' => $validated['organization_category_other'] ?? null,
            'registered_name' => $validated['registered_name'],
            'address' => $validated['address'],
            'postcode' => $validated['postcode'],
            'district' => $validated['district'],
            'state' => $validated['state'],
            'registration_number' => $validated['registration_number'],
            'registration_date' => $validated['registration_date'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'website' => $validated['website'] ?? null,
            'organization_profile_types' => $validated['organization_profile_types'],
            'organization_profile_other' => $validated['organization_profile_other'] ?? null,
            'primary_focus_areas' => $validated['primary_focus_areas'],
            'primary_focus_other' => $validated['primary_focus_other'] ?? null,
            'is_registered_with_ros' => (bool) $validated['is_registered_with_ros'],
            'registration_certificate_path' => $regCertPath,
            'committee_members_path' => $committeePath,
            'president_details' => [
                'name' => $validated['president_name'],
                'phone' => $validated['president_phone'],
                'email' => $validated['president_email'],
            ],
            'secretary_details' => [
                'name' => $validated['secretary_name'],
                'phone' => $validated['secretary_phone'],
                'email' => $validated['secretary_email'],
            ],
            'treasurer_details' => $treasurerDetails,
            'signatories' => $validated['signatories'],
            'agreed_to_declaration' => true,
        ]);

        return redirect()->back()->with('success', 'Registration submitted successfully. We will review your application within approximately 14 working days.');
    }

    public function showScholarshipForm()
    {
        return view('pages.scholarship');
    }

    public function submitScholarshipForm(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'ic_passport' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'nationality' => 'required|string',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            
            'education_level' => 'required|string',
            'institution_name' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'current_cgpa' => 'required|string|max:255',
            
            'parent_occupation' => 'required|string|max:255',
            'household_income' => 'required|string',
            'dependents' => 'required|integer|min:0',
            
            'ic_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transcript_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'income_proof_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            'agreed_to_declaration' => 'accepted',
        ]);

        $icPath = $request->file('ic_document')->store('submissions/scholarships', 'public');
        $transcriptPath = $request->file('transcript_document')->store('submissions/scholarships', 'public');
        $incomePath = $request->file('income_proof_document')->store('submissions/scholarships', 'public');

        ScholarshipApplication::create([
            'full_name' => $validated['full_name'],
            'ic_passport' => $validated['ic_passport'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'nationality' => $validated['nationality'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'education_level' => $validated['education_level'],
            'institution_name' => $validated['institution_name'],
            'field_of_study' => $validated['field_of_study'],
            'current_cgpa' => $validated['current_cgpa'],
            'parent_occupation' => $validated['parent_occupation'],
            'household_income' => $validated['household_income'],
            'dependents' => $validated['dependents'],
            'ic_path' => $icPath,
            'transcript_path' => $transcriptPath,
            'income_proof_path' => $incomePath,
            'agreed_to_declaration' => true,
        ]);

        return redirect()->back()->with('success', 'Scholarship application submitted successfully.');
    }
}
