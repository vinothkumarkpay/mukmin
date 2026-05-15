@extends('layouts.admin', ['headerTitle' => 'Scholarship Submissions'])

@section('title', 'View Scholarship Application')

@section('content')
<style>
    .admin-detail-container {
        font-family: inherit;
        color: #333;
    }
    .back-btn {
        display: inline-block;
        margin-bottom: 1.5rem;
        color: #495057;
        text-decoration: none;
        font-weight: 600;
    }
    .back-btn:hover {
        color: #0f3d2c;
        text-decoration: underline;
    }
    .section-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #edf2f9;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .section-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #edf2f9;
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f3d2c;
    }
    .section-body {
        padding: 0;
    }
    .data-row {
        display: flex;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f3f5;
    }
    .data-row:last-child {
        border-bottom: none;
    }
    .data-label {
        width: 30%;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .data-value {
        width: 70%;
        color: #212529;
        font-size: 1rem;
        font-weight: 500;
    }
    .doc-action-btn {
        background: #0f3d2c;
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .doc-action-btn:hover {
        background: #1a6b4a;
        color: #fff;
        text-decoration: none;
    }
    .status-missing {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<div class="admin-detail-container">
    <a href="{{ route('admin.submissions.scholarship.index') }}" class="back-btn">← Back to Application List</a>

    <div class="section-card">
        <div class="section-header">Applicant Information</div>
        <div class="section-body">
            <div class="data-row">
                <div class="data-label">Full Name</div>
                <div class="data-value">{{ $submission->full_name }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">IC/Passport Number</div>
                <div class="data-value">{{ $submission->ic_passport }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Date of Birth</div>
                <div class="data-value">{{ $submission->dob->format('d M Y') }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Gender & Nationality</div>
                <div class="data-value">{{ $submission->gender }}, {{ $submission->nationality }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Contact Details</div>
                <div class="data-value">
                    {{ $submission->contact_number }} / <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">Educational Background</div>
        <div class="section-body">
            <div class="data-row">
                <div class="data-label">Level & Institution</div>
                <div class="data-value">{{ $submission->education_level }} at {{ $submission->institution_name }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Field of Study</div>
                <div class="data-value">{{ $submission->field_of_study }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Current CGPA / Result</div>
                <div class="data-value">{{ $submission->current_cgpa }}</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">Financial Information</div>
        <div class="section-body">
            <div class="data-row">
                <div class="data-label">Parent/Guardian Occupation</div>
                <div class="data-value">{{ $submission->parent_occupation }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Household Income</div>
                <div class="data-value">{{ $submission->household_income }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Dependents</div>
                <div class="data-value">{{ $submission->dependents }}</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">Supporting Documents</div>
        <div class="section-body">
            <div class="data-row" style="align-items: center;">
                <div class="data-label">IC / Passport</div>
                <div class="data-value">
                    @if($submission->ic_path)
                        <a href="{{ Storage::disk('public')->url($submission->ic_path) }}" target="_blank" class="doc-action-btn">View Document</a>
                    @else
                        <span class="status-missing">Not provided</span>
                    @endif
                </div>
            </div>
            <div class="data-row" style="align-items: center;">
                <div class="data-label">Academic Transcript</div>
                <div class="data-value">
                    @if($submission->transcript_path)
                        <a href="{{ Storage::disk('public')->url($submission->transcript_path) }}" target="_blank" class="doc-action-btn">View Document</a>
                    @else
                        <span class="status-missing">Not provided</span>
                    @endif
                </div>
            </div>
            <div class="data-row" style="align-items: center;">
                <div class="data-label">Income Proof</div>
                <div class="data-value">
                    @if($submission->income_proof_path)
                        <a href="{{ Storage::disk('public')->url($submission->income_proof_path) }}" target="_blank" class="doc-action-btn">View Document</a>
                    @else
                        <span class="status-missing">Not provided</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section-card" style="background: #e9f7ef; border-color: #c3e6cb;">
        <div class="section-body" style="padding: 1.5rem;">
            <div style="font-weight: 700; color: #155724; margin-bottom: 0.5rem;">
                ✓ Applicant declared that all provided information is true and accurate.
            </div>
            <div style="color: #495057; font-size: 0.9rem;">
                Submitted at: {{ $submission->created_at->format('d M Y, h:i:s A') }}
            </div>
        </div>
    </div>

</div>
@endsection
