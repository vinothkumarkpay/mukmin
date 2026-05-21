@extends('layouts.admin', ['headerTitle' => 'Friends of MUKMIN Submissions'])

@section('title', 'View Friends of MUKMIN Registration')

@section('content')
<div class="mb-3">
    <a href="{{ route('submissions.friends.index') }}" class="text-decoration-none">← Back to List</a>
</div>

<div class="card mb-4">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Registration: {{ $submission->org_name ?? $submission->individual_name ?? 'Friends of MUKMIN' }}</h5>
    </div>
    <div class="card-body">

        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Section A — Organisation Category</h6>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Category</div>
            <div class="col-md-9">
                {{ $submission->organization_category }}
                @if($submission->organization_category === 'Others' && $submission->organization_category_other)
                    <br><small class="text-muted">Specified: {{ $submission->organization_category_other }}</small>
                @endif
            </div>
        </div>

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section B — Organisation Details</h6>
        @if($submission->org_name || $submission->org_registration_number || $submission->org_address)
            @foreach([
                'Organisation Name' => $submission->org_name,
                'Registration Number' => $submission->org_registration_number,
                'State' => $submission->org_state,
                'Full Address' => $submission->org_address,
                'Official Email' => $submission->org_email,
                'Contact Number' => $submission->org_contact_number,
                'Website / Social' => $submission->org_website,
            ] as $label => $value)
                @if($value)
                <div class="row mb-3">
                    <div class="col-md-3 text-muted fw-bold">{{ $label }}</div>
                    <div class="col-md-9">{{ $value }}</div>
                </div>
                @endif
            @endforeach
        @else
            <div class="alert alert-light text-muted">No organisation details provided.</div>
        @endif

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section C — Individual Details</h6>
        @if($submission->individual_name || $submission->individual_nric || $submission->individual_address)
            @foreach([
                'Full Name' => $submission->individual_name,
                'NRIC Number' => $submission->individual_nric,
                'State of Residency' => $submission->individual_state,
                'Full Address' => $submission->individual_address,
                'Email Address' => $submission->individual_email,
                'Contact Number' => $submission->individual_contact_number,
            ] as $label => $value)
                @if($value)
                <div class="row mb-3">
                    <div class="col-md-3 text-muted fw-bold">{{ $label }}</div>
                    <div class="col-md-9">{{ $value }}</div>
                </div>
                @endif
            @endforeach
        @else
            <div class="alert alert-light text-muted">No individual details provided.</div>
        @endif

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section G — Declaration</h6>
        <div class="alert alert-info mt-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" checked disabled>
                <label class="form-check-label fw-bold">
                    Applicant agreed to comply with the constitution, policies, and guidelines of MUKMIN and confirmed information is true and accurate.
                </label>
            </div>
            <small class="text-muted d-block mt-2">Submitted at: {{ $submission->created_at->format('d M Y, h:i:s A') }}</small>
        </div>

    </div>
</div>
@endsection
