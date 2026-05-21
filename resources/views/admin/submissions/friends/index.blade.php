@extends('layouts.admin', ['headerTitle' => 'Friends of MUKMIN Submissions'])

@section('title', 'Friends of MUKMIN Submissions')

@section('content')
<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Friends of MUKMIN Registrations</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Name (Org / Individual)</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                <tr>
                    <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        {{ $submission->organization_category }}
                        @if($submission->organization_category === 'Others' && $submission->organization_category_other)
                            <small class="text-muted">({{ $submission->organization_category_other }})</small>
                        @endif
                    </td>
                    <td>
                        @if($submission->org_name)
                            <span class="fw-bold">{{ $submission->org_name }}</span>
                            @if($submission->individual_name)
                                <br><small class="text-muted">Rep: {{ $submission->individual_name }}</small>
                            @endif
                        @elseif($submission->individual_name)
                            <span class="fw-bold">{{ $submission->individual_name }}</span>
                        @else
                            <span class="text-muted fst-italic">Not provided</span>
                        @endif
                    </td>
                    <td>{{ $submission->org_contact_number ?? $submission->individual_contact_number ?? '—' }}</td>
                    <td>
                        @php
                            $email = $submission->org_email ?? $submission->individual_email;
                        @endphp
                        @if($email)
                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('submissions.friends.show', $submission) }}" class="btn btn-sm btn-primary" style="background-color: #0f3d2c; border-color: #0f3d2c;">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No registrations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">
        {{ $submissions->links() }}
    </div>
</div>
@endsection
