@extends('layouts.admin', ['headerTitle' => 'Registration Submissions'])

@section('title', 'View Registration')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.submissions.registration.index') }}" class="text-decoration-none">← Back to List</a>
</div>

<div class="card mb-4">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Registration: {{ $submission->registered_name }}</h5>
    </div>
    <div class="card-body">

        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Section A — Organisation Category</h6>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Categories</div>
            <div class="col-md-9">
                {{ implode(', ', $submission->organization_categories ?? []) }}
                @if($submission->organization_category_other)
                    <br><small class="text-muted">Other: {{ $submission->organization_category_other }}</small>
                @endif
            </div>
        </div>

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section B — Organisation Details</h6>
        @foreach([
            'Registered Name' => $submission->registered_name,
            'Full Address' => $submission->address,
            'Postcode' => $submission->postcode,
            'District / City' => $submission->district,
            'State / Province' => $submission->state,
            'Registration Number' => $submission->registration_number,
            'Date of Registration' => $submission->registration_date?->format('d/m/Y'),
            'Official Email' => $submission->email,
            'Official Phone' => $submission->contact_number,
        ] as $label => $value)
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">{{ $label }}</div>
            <div class="col-md-9">{{ $value }}</div>
        </div>
        @endforeach
        @if($submission->website)
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Website / Social</div>
            <div class="col-md-9">{{ $submission->website }}</div>
        </div>
        @endif

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section C — Organisation Profile</h6>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Type</div>
            <div class="col-md-9">
                {{ implode(', ', $submission->organization_profile_types ?? []) }}
                @if($submission->organization_profile_other)
                    <br><small class="text-muted">Other: {{ $submission->organization_profile_other }}</small>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Primary Focus</div>
            <div class="col-md-9">
                {{ implode(', ', $submission->primary_focus_areas ?? []) }}
                @if($submission->primary_focus_other)
                    <br><small class="text-muted">Other: {{ $submission->primary_focus_other }}</small>
                @endif
            </div>
        </div>

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section D — Governance &amp; Legal</h6>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Registered with ROS</div>
            <div class="col-md-9">
                @if($submission->is_registered_with_ros)
                    <span class="badge bg-success">Yes</span>
                @else
                    <span class="badge bg-secondary">No</span>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Registration Cert.</div>
            <div class="col-md-9">
                @if($submission->registration_certificate_path)
                    <a href="{{ Storage::disk('public')->url($submission->registration_certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Document</a>
                @else
                    <span class="text-muted">Not provided</span>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Executive Members</div>
            <div class="col-md-9">
                @if($submission->committee_members_path)
                    <a href="{{ Storage::disk('public')->url($submission->committee_members_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Document</a>
                @else
                    <span class="text-muted">Not provided</span>
                @endif
            </div>
        </div>

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section E — Key Office Bearers</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr><th>Role</th><th>Name</th><th>Phone</th><th>Email</th></tr>
                </thead>
                <tbody>
                    @php
                        $roles = [
                            'President / Chairman' => $submission->president_details ?? [],
                            'Secretary' => $submission->secretary_details ?? [],
                            'Treasurer' => $submission->treasurer_details ?? [],
                        ];
                    @endphp
                    @foreach($roles as $role => $details)
                        @if($role === 'Treasurer' && empty(data_get($details, 'name')))
                            @continue
                        @endif
                        <tr>
                            <td class="fw-bold text-muted">{{ $role }}</td>
                            <td>{{ data_get($details, 'name', '—') }}</td>
                            <td>{{ data_get($details, 'phone', '—') }}</td>
                            <td>
                                @if(data_get($details, 'email'))
                                    <a href="mailto:{{ data_get($details, 'email') }}">{{ data_get($details, 'email') }}</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h6 class="fw-bold mb-3 mt-4 text-primary border-bottom pb-2">Section G — Authorised Signatories</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Position</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @foreach($submission->signatories ?? [] as $index => $signatory)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ data_get($signatory, 'name') }}</td>
                            <td>{{ data_get($signatory, 'position') }}</td>
                            <td>{{ data_get($signatory, 'date') ? \Carbon\Carbon::parse(data_get($signatory, 'date'))->format('d/m/Y') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="alert alert-info mt-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" checked disabled>
                <label class="form-check-label fw-bold">
                    Applicant agreed to the declaration of true and accurate information.
                </label>
            </div>
            <small class="text-muted d-block mt-2">Submitted at: {{ $submission->created_at->format('d M Y, h:i:s A') }}</small>
        </div>

    </div>
</div>
@endsection
