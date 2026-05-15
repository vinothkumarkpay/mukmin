@extends('layouts.admin', ['headerTitle' => 'Contact Submissions'])

@section('title', 'View Contact Submission')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.submissions.contact.index') }}" class="text-decoration-none">← Back to List</a>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Submission from {{ $submission->name }}</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Date Submitted</div>
            <div class="col-md-9">{{ $submission->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Name</div>
            <div class="col-md-9">{{ $submission->name }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Email</div>
            <div class="col-md-9"><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Phone Number</div>
            <div class="col-md-9">{{ $submission->phone }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Message</div>
            <div class="col-md-9">
                <div class="p-3 bg-light rounded border">
                    {!! nl2br(e($submission->message)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
