@extends('layouts.public')

@section('content')
<style>
    .premium-form-container {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        padding: 3.5rem;
    }
    .premium-input, .premium-select {
        border-radius: 12px;
        background-color: #f8fafc;
        border: 2px solid #e2e8f0;
        padding: 0.95rem 1.25rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        color: #1e293b;
    }
    .premium-input:focus, .premium-select:focus {
        background-color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12);
        outline: none;
        transform: translateY(-1px);
    }
    .premium-select {
        cursor: pointer;
    }
    .premium-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.5rem;
        display: block;
    }
    .premium-btn {
        background: linear-gradient(135deg, #0f3d2c, #1a6b4a);
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 1rem 2.5rem;
        border-radius: 10px;
        border: none;
        box-shadow: 0 8px 20px rgba(15, 61, 44, 0.2);
        transition: all 0.2s ease;
        font-size: 1.1rem;
    }
    .premium-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(15, 61, 44, 0.3);
        color: #fff;
    }
    .section-title {
        color: #0f3d2c;
        font-weight: 800;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        position: relative;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: #10b981;
    }
    .file-upload-wrapper {
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .file-upload-wrapper:hover {
        border-color: #10b981;
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1);
    }
    .file-upload-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }
    .file-upload-icon {
        width: 64px;
        height: 64px;
        background: #ecfdf5;
        color: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .file-upload-wrapper:hover .file-upload-icon {
        background: #10b981;
        color: #ffffff;
        transform: scale(1.05);
    }
    .file-upload-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    .file-upload-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        position: relative;
        z-index: 1;
        padding: 0 1rem;
    }
    .file-upload-btn-fake {
        margin-top: 1rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 0.4rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }
    .file-upload-wrapper:hover .file-upload-btn-fake {
        border-color: #10b981;
        color: #10b981;
        background: #ecfdf5;
    }
    
    .form-check-premium {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s ease;
    }
    .form-check-premium:hover {
        border-color: #10b981;
    }
    .sub-heading {
        color: #475569;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    .radio-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: inline-block;
        margin-right: 0.5rem;
        background: #f8fafc;
        transition: all 0.2s ease;
        cursor: pointer;
        flex: 1;
        text-align: center;
    }
    .radio-card:hover {
        border-color: #10b981;
        background: #f1fdf8;
    }
    .radio-card input[type="radio"]:checked + label {
        color: #0f3d2c;
        font-weight: bold;
    }
    .radio-card input[type="radio"]:checked {
        accent-color: #10b981;
    }
    
    .form-text-custom {
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.6;
    }
    
    .d-flex-radio-group {
        display: flex;
        gap: 1rem;
    }
</style>

<main class="page-content" style="padding-top: 100px; padding-bottom: 80px; background-color: #fcfcfc;">
    <div class="container">
        <div class="cmsw-lead mb-5 mt-4 position-relative" style="border-radius: 20px; padding: 6rem 3rem; text-align: center; box-shadow: 0 20px 40px rgba(15, 61, 44, 0.15); overflow: hidden; background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center;">
            <div class="position-absolute w-100 h-100 top-0 start-0" style="background: linear-gradient(135deg, rgba(15, 61, 44, 0.85) 0%, rgba(26, 107, 74, 0.75) 100%);"></div>
            <div class="position-relative" style="z-index: 1;">
                <h1 style="color: white; font-family: var(--font-hero-display, var(--font)); font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 800; margin-bottom: 1rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">MUKMIN Future Leaders Scholarship</h1>
                <p style="color: rgba(255,255,255,0.95); font-size: 1.25rem; max-width: 700px; margin: 0 auto; line-height: 1.6; font-weight: 600; text-shadow: 0 1px 5px rgba(0,0,0,0.2);">
                    Application Form
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="premium-form-container">
                    <p class="form-text-custom mb-3">The MUKMIN Future Leaders Scholarship (MFLS) is a national talent development initiative by MUKMIN, designed to unlock the full potential of the Indian Muslim community through a dual pathway model—integrating TVET (skills and technical pathways) and academic education.</p>
                    <p class="form-text-custom mb-3">The programme provides access to TVET, Foundation, Diploma, Degree and Master programmes in collaboration with leading universities and institutions—ensuring multiple pathways for talents to progress, excel and succeed.</p>
                    <p class="form-text-custom mb-4">Facilitated by FIKRAH, MUKMIN’s strategic think tank, MFLS goes beyond financial support by building a future-ready talent pipeline—developing individuals who are not only qualified, but skilled, adaptable and driven to contribute meaningfully to society and the nation.</p>
                    <p class="form-text-custom fw-bold mb-2" style="color: #0f3d2c;">Apply Now. Lead the Future.</p>
                    <p class="form-text-custom fw-bold mb-5 text-danger">Applications close on 30th May 2026.</p>

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center mb-5" style="border-radius: 12px; border: none; background-color: #ecfdf5; color: #065f46; padding: 1.5rem;">
                            <i class="bi bi-check-circle-fill me-3 fs-3"></i>
                            <div>
                                <h5 class="mb-1 fw-bold">Application Submitted!</h5>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger mb-5" style="border-radius: 12px; border: none; background-color: #fef2f2; color: #991b1b; padding: 1.5rem;">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <h6 class="mb-0 fw-bold">Please correct the following errors:</h6>
                            </div>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('scholarship.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <h4 class="section-title">Section 1: Applicant Information</h4>
                        <div class="row mb-4">
                            <div class="col-12 mb-4">
                                <label class="premium-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control premium-input" required value="{{ old('full_name') }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Gender <span class="text-danger">*</span></label>
                                <div class="d-flex-radio-group">
                                    <div class="radio-card">
                                        <div class="form-check m-0 d-flex align-items-center justify-content-center">
                                            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" required {{ old('gender') == 'Male' ? 'checked' : '' }}>
                                            <label class="form-check-label ms-2" for="genderMale">Male</label>
                                        </div>
                                    </div>
                                    <div class="radio-card">
                                        <div class="form-check m-0 d-flex align-items-center justify-content-center">
                                            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" required {{ old('gender') == 'Female' ? 'checked' : '' }}>
                                            <label class="form-check-label ms-2" for="genderFemale">Female</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" name="contact_number" class="form-control premium-input" placeholder="+6012..." required value="{{ old('contact_number') }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control premium-input" required value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">IC / Passport Number <span class="text-danger">*</span></label>
                                <input type="text" name="ic_passport" class="form-control premium-input" required value="{{ old('ic_passport') }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" class="form-control premium-input" required value="{{ old('dob') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Nationality <span class="text-danger">*</span></label>
                                <input type="text" name="nationality" class="form-control premium-input" placeholder="e.g. Malaysian" required value="{{ old('nationality') }}">
                            </div>
                        </div>
                        
                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="premium-label">Mailing Address <span class="text-danger">*</span></label>
                                <textarea name="mailing_address" class="form-control premium-input" rows="3" required>{{ old('mailing_address') }}</textarea>
                            </div>
                        </div>

                        <h4 class="section-title">Section 2: Educational Background</h4>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Education Level / Level of Study <span class="text-danger">*</span></label>
                                <select name="education_level" class="form-select premium-select" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="SPM/O-Level" {{ old('education_level') == 'SPM/O-Level' ? 'selected' : '' }}>SPM / O-Level</option>
                                    <option value="STPM/A-Level/Foundation" {{ old('education_level') == 'STPM/A-Level/Foundation' ? 'selected' : '' }}>STPM / A-Level / Foundation</option>
                                    <option value="Diploma" {{ old('education_level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="Bachelor's Degree" {{ old('education_level') == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                    <option value="Master's Degree" {{ old('education_level') == "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">University / Institution Name <span class="text-danger">*</span></label>
                                <input type="text" name="institution_name" class="form-control premium-input" required value="{{ old('institution_name') }}">
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Mode of Study <span class="text-danger">*</span></label>
                                <select name="mode_of_study" class="form-select premium-select" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Full-time" {{ old('mode_of_study') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="Part-time" {{ old('mode_of_study') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="Distance Learning" {{ old('mode_of_study') == 'Distance Learning' ? 'selected' : '' }}>Distance Learning</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Current Year of Study <span class="text-danger">*</span></label>
                                <select name="year_of_study" class="form-select premium-select" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Year 1" {{ old('year_of_study') == 'Year 1' ? 'selected' : '' }}>Year 1</option>
                                    <option value="Year 2" {{ old('year_of_study') == 'Year 2' ? 'selected' : '' }}>Year 2</option>
                                    <option value="Year 3" {{ old('year_of_study') == 'Year 3' ? 'selected' : '' }}>Year 3</option>
                                    <option value="Year 4" {{ old('year_of_study') == 'Year 4' ? 'selected' : '' }}>Year 4</option>
                                    <option value="Other" {{ old('year_of_study') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Current CGPA <span class="text-danger">*</span></label>
                                <input type="text" name="current_cgpa" class="form-control premium-input" placeholder="e.g. 3.85 / 4.00 or 8As" required value="{{ old('current_cgpa') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Field of Study <span class="text-danger">*</span></label>
                                <select name="field_of_study" class="form-select premium-select" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Arts" {{ old('field_of_study') == 'Arts' ? 'selected' : '' }}>Arts</option>
                                    <option value="Science" {{ old('field_of_study') == 'Science' ? 'selected' : '' }}>Science</option>
                                    <option value="Engineering" {{ old('field_of_study') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="Other" {{ old('field_of_study') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <h4 class="section-title">Section 3: Financial & Income Information</h4>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Are you receiving any other sponsorship/scholarship? <span class="text-danger">*</span></label>
                                <div class="d-flex-radio-group">
                                    <div class="radio-card">
                                        <div class="form-check m-0 d-flex align-items-center justify-content-center">
                                            <input class="form-check-input" type="radio" name="has_other_sponsorship" id="sponsorYes" value="Yes" required {{ old('has_other_sponsorship') == 'Yes' ? 'checked' : '' }}>
                                            <label class="form-check-label ms-2" for="sponsorYes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="radio-card">
                                        <div class="form-check m-0 d-flex align-items-center justify-content-center">
                                            <input class="form-check-input" type="radio" name="has_other_sponsorship" id="sponsorNo" value="No" required {{ old('has_other_sponsorship') == 'No' ? 'checked' : '' }}>
                                            <label class="form-check-label ms-2" for="sponsorNo">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">If Yes, please state the sponsor's name</label>
                                <input type="text" name="sponsor_name" class="form-control premium-input" value="{{ old('sponsor_name') }}">
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Household Monthly Income <span class="text-danger">*</span></label>
                                <select name="household_income" class="form-select premium-select" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="< RM3,000" {{ old('household_income') == '< RM3,000' ? 'selected' : '' }}>< RM3,000 (B40)</option>
                                    <option value="RM3,001 - RM4,850" {{ old('household_income') == 'RM3,001 - RM4,850' ? 'selected' : '' }}>RM3,001 - RM4,850 (B40)</option>
                                    <option value="RM4,851 - RM10,959" {{ old('household_income') == 'RM4,851 - RM10,959' ? 'selected' : '' }}>RM4,851 - RM10,959 (M40)</option>
                                    <option value="> RM10,960" {{ old('household_income') == '> RM10,960' ? 'selected' : '' }}>> RM10,960 (T20)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Applicant's Employment Status <span class="text-danger">*</span></label>
                                <select name="employment_status" class="form-select premium-select" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Employed" {{ old('employment_status') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                    <option value="Unemployed" {{ old('employment_status') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">If employed, please state occupation and monthly income:</label>
                                <input type="text" name="occupation_income" class="form-control premium-input" value="{{ old('occupation_income') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Number of Dependents <span class="text-danger">*</span></label>
                                <input type="number" name="dependents" class="form-control premium-input" min="0" required value="{{ old('dependents') }}">
                            </div>
                        </div>

                        <h4 class="section-title">Section 4: Parent / Guardian Information</h4>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Parent / Guardian Name <span class="text-danger">*</span></label>
                                <input type="text" name="parent_name" class="form-control premium-input" required value="{{ old('parent_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Relationship <span class="text-danger">*</span></label>
                                <input type="text" name="parent_relationship" class="form-control premium-input" required value="{{ old('parent_relationship') }}">
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <label class="premium-label">Parent / Guardian Contact Number <span class="text-danger">*</span></label>
                                <input type="text" name="parent_contact" class="form-control premium-input" required value="{{ old('parent_contact') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="premium-label">Parent / Guardian Occupation <span class="text-danger">*</span></label>
                                <input type="text" name="parent_occupation" class="form-control premium-input" required value="{{ old('parent_occupation') }}">
                            </div>
                        </div>

                        <h4 class="section-title">Section 5: Co-curricular & Leadership Involvement</h4>
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="premium-label">Please list your active involvement in clubs / associations / NGOs (Organization Name & Position) <span class="text-danger">*</span></label>
                                <textarea name="cocurricular_involvement" class="form-control premium-input" rows="3" required>{{ old('cocurricular_involvement') }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="premium-label">Please state your highest achievement (Sports / Academic / Leadership etc.)</label>
                                <textarea name="highest_achievement" class="form-control premium-input" rows="3">{{ old('highest_achievement') }}</textarea>
                            </div>
                        </div>

                        <h4 class="section-title">Section 6: Short Essay</h4>
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="premium-label">Why do you deserve to receive this Scholarship? (Maximum 300 words) <span class="text-danger">*</span></label>
                                <textarea name="essay_deserve" class="form-control premium-input" rows="5" required>{{ old('essay_deserve') }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="premium-label">What are your future plans to contribute back to society upon graduation? (Maximum 300 words) <span class="text-danger">*</span></label>
                                <textarea name="essay_contribute" class="form-control premium-input" rows="5" required>{{ old('essay_contribute') }}</textarea>
                            </div>
                        </div>

                        <h4 class="section-title">Section 7: Supporting Documents</h4>
                        <p class="text-muted small mb-4">Please upload the required documents. Accepted formats: PDF, JPG, JPEG, PNG. Max size: 5MB per file.</p>
                        
                        <div class="row gx-4 mb-4">
                            <div class="col-md-6 mb-4">
                                <div class="file-upload-wrapper h-100">
                                    <div class="file-upload-icon">
                                        <i class="bi bi-person-vcard"></i>
                                    </div>
                                    <div class="file-upload-title">1. Identity Card <span class="text-danger">*</span></div>
                                    <div class="file-upload-subtitle mb-2">(Front & Back)</div>
                                    <div class="file-upload-btn-fake">Choose File</div>
                                    <input type="file" name="ic_document" required accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="file-upload-wrapper h-100">
                                    <div class="file-upload-icon">
                                        <i class="bi bi-bank"></i>
                                    </div>
                                    <div class="file-upload-title">2. Offer Letter <span class="text-danger">*</span></div>
                                    <div class="file-upload-subtitle mb-2">(University / Institution)</div>
                                    <div class="file-upload-btn-fake">Choose File</div>
                                    <input type="file" name="offer_letter_document" required accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="file-upload-wrapper h-100">
                                    <div class="file-upload-icon">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div class="file-upload-title">3. Academic Transcript <span class="text-danger">*</span></div>
                                    <div class="file-upload-subtitle mb-2">(Latest)</div>
                                    <div class="file-upload-btn-fake">Choose File</div>
                                    <input type="file" name="transcript_document" required accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="file-upload-wrapper h-100">
                                    <div class="file-upload-icon">
                                        <i class="bi bi-file-earmark-bar-graph"></i>
                                    </div>
                                    <div class="file-upload-title">4. Income Proof <span class="text-danger">*</span></div>
                                    <div class="file-upload-subtitle mb-2">(Parent / Guardian)</div>
                                    <div class="file-upload-btn-fake">Choose File</div>
                                    <input type="file" name="income_proof_document" required accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>

                        <h4 class="section-title">Section 8: Declaration</h4>
                        <div class="form-check form-check-premium d-flex align-items-center mb-5" style="background: #f8fafc; border-color: #e2e8f0;">
                            <input type="checkbox" name="agreed_to_declaration" value="1" class="form-check-input mt-0 me-3" id="declaration" required {{ old('agreed_to_declaration') ? 'checked' : '' }} style="width: 1.5em; height: 1.5em; border-color: #94a3b8; cursor: pointer;">
                            <label class="form-check-label fw-bold" for="declaration" style="color: #475569; cursor: pointer; line-height: 1.6;">
                                I confirm that all information provided in this form is true and accurate. I understand that MUKMIN reserves the right to cancel this application if false information is found.
                            </label>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="premium-btn">Submit Application <i class="bi bi-arrow-right ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('.file-upload-wrapper input[type="file"]');
        fileInputs.forEach(input => {
            // Save original icon class for reset if needed
            const icon = input.closest('.file-upload-wrapper').querySelector('.file-upload-icon i');
            if(icon) {
                input.dataset.originalIcon = icon.className;
            }

            input.addEventListener('change', function(e) {
                const wrapper = this.closest('.file-upload-wrapper');
                const fakeBtn = wrapper.querySelector('.file-upload-btn-fake');
                const iconElement = wrapper.querySelector('.file-upload-icon i');
                const iconContainer = wrapper.querySelector('.file-upload-icon');
                
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    fakeBtn.textContent = fileName;
                    fakeBtn.style.backgroundColor = '#ecfdf5';
                    fakeBtn.style.color = '#10b981';
                    fakeBtn.style.borderColor = '#10b981';
                    
                    // Change icon to a checkmark indicating success
                    iconElement.className = 'bi bi-check-circle-fill';
                    iconContainer.style.backgroundColor = '#10b981';
                    iconContainer.style.color = '#ffffff';
                    
                    wrapper.style.borderColor = '#10b981';
                    wrapper.style.backgroundColor = '#f1fdf8';
                } else {
                    fakeBtn.textContent = 'Choose File';
                    fakeBtn.style.backgroundColor = '';
                    fakeBtn.style.color = '';
                    fakeBtn.style.borderColor = '';
                    
                    iconElement.className = this.dataset.originalIcon || 'bi bi-file-earmark-text';
                    iconContainer.style.backgroundColor = '';
                    iconContainer.style.color = '';
                    
                    wrapper.style.borderColor = '';
                    wrapper.style.backgroundColor = '';
                }
            });
        });
    });
</script>
@endsection
