@extends('layouts.admin', ['headerTitle' => 'Scholarship Submissions'])

@section('title', 'Scholarship Submissions')

@section('content')
<style>
    .admin-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .admin-card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #f8fafc;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table tbody tr:hover {
        background-color: #f8fafc;
    }
    .badge-soft-primary {
        background-color: #ecfdf5;
        color: #10b981;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 600;
    }
    .btn-view {
        background-color: #0f3d2c;
        color: #fff;
        border-radius: 6px;
        padding: 0.4rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-view:hover {
        background-color: #1a6b4a;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(15, 61, 44, 0.15);
    }
</style>

<div class="card admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold" style="color: #0f3d2c;">MFLS Scholarship Applications</h5>
        <span class="badge badge-soft-primary">{{ $submissions->total() }} Total</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date Applied</th>
                    <th>Applicant Name</th>
                    <th>IC / Passport</th>
                    <th>Education Level</th>
                    <th>Contact Info</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                <tr>
                    <td>
                        <div class="fw-bold" style="color: #475569;">{{ $submission->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $submission->created_at->format('h:i A') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold" style="color: #1e293b;">{{ $submission->full_name }}</div>
                        <small class="text-muted">{{ $submission->email }}</small>
                    </td>
                    <td><span class="text-secondary">{{ $submission->ic_passport }}</span></td>
                    <td>
                        <span class="badge badge-soft-primary">{{ $submission->education_level }}</span>
                        <div class="small text-muted mt-1">{{ Str::limit($submission->institution_name, 25) }}</div>
                    </td>
                    <td><span class="text-secondary">{{ $submission->contact_number }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.submissions.scholarship.show', $submission) }}" class="btn btn-view">
                            <i class="bi bi-eye me-1"></i> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-3"></i>
                        No scholarship applications found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($submissions->hasPages())
    <div class="p-4 border-top">
        {{ $submissions->links() }}
    </div>
    @endif
</div>
@endsection
