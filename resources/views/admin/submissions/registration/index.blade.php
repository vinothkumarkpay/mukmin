@extends('layouts.admin', ['headerTitle' => 'Registration Submissions'])

@section('title', 'Registration Submissions')

@section('content')
<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Organization Registrations</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Org. Name</th>
                    <th>Type</th>
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
                        <span class="fw-bold">{{ $submission->registered_name }}</span><br>
                        <small class="text-muted">{{ $submission->registration_number }}</small>
                    </td>
                    <td>{{ implode(', ', $submission->organization_categories ?? ['—']) }}</td>
                    <td>{{ $submission->contact_number }}</td>
                    <td><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></td>
                    <td>
                        <a href="{{ route('admin.submissions.registration.show', $submission) }}" class="btn btn-sm btn-primary" style="background-color: #0f3d2c; border-color: #0f3d2c;">View</a>
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
