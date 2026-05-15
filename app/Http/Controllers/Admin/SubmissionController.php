<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\RegistrationSubmission;
use App\Models\ScholarshipApplication;

class SubmissionController extends Controller
{
    public function contactIndex()
    {
        $submissions = ContactSubmission::latest()->paginate(20);
        return view('admin.submissions.contact.index', compact('submissions'));
    }

    public function contactShow(ContactSubmission $submission)
    {
        return view('admin.submissions.contact.show', compact('submission'));
    }

    public function registrationIndex()
    {
        $submissions = RegistrationSubmission::latest()->paginate(20);
        return view('admin.submissions.registration.index', compact('submissions'));
    }

    public function registrationShow(RegistrationSubmission $submission)
    {
        return view('admin.submissions.registration.show', compact('submission'));
    }

    public function scholarshipIndex()
    {
        $submissions = ScholarshipApplication::latest()->paginate(20);
        return view('admin.submissions.scholarship.index', compact('submissions'));
    }

    public function scholarshipShow(ScholarshipApplication $submission)
    {
        return view('admin.submissions.scholarship.show', compact('submission'));
    }
}
