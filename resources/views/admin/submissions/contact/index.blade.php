@extends('layouts.admin', ['headerTitle' => 'Contact Submissions'])

@section('title', 'Contact Submissions')

@section('content')
<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Contact Us Submissions</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                <tr>
                    <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                    <td>{{ $submission->name }}</td>
                    <td><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></td>
                    <td>{{ $submission->phone }}</td>
                    <td>
                        <a href="{{ route('admin.submissions.contact.show', $submission) }}" class="btn btn-sm btn-primary" style="background-color: #0f3d2c; border-color: #0f3d2c;">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No submissions found.</td>
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
