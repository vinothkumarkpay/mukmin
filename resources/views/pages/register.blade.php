@extends('layouts.public')

@section('content')
@php
    $organizationCategories = ['NGO', 'Masjid', 'Surau', 'Madrasah', 'Education Institution', 'Others'];
    $profileTypes = ['Non-profit / NGO', 'Religious body', 'Educational body', 'Charity', 'Social', 'Professional Body', 'Foundation', 'Others'];
    $focusAreas = ['Education / Training', 'Welfare / Charity', 'Human Rights', 'Youth Development', 'Women Empowerment', 'Community Services', 'Others'];
@endphp
<style>
    .reg-page { padding: 1.5rem 0 3rem; }
    .reg-hero {
        text-align: center;
        padding: clamp(2rem, 5vw, 3rem) clamp(1.25rem, 4vw, 2.5rem);
        margin-bottom: 2rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #0f3d2c 0%, #1a6b4a 100%);
        box-shadow: 0 12px 32px rgba(15, 61, 44, 0.18);
    }
    .reg-hero h1 {
        font-family: 'Fraunces', serif;
        color: #fff;
        font-weight: 800;
        font-size: clamp(1.65rem, 4vw, 2.25rem);
        margin: 0 0 0.75rem;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .reg-hero p { color: rgba(255,255,255,0.9); margin: 0; font-size: 1.05rem; line-height: 1.6; max-width: 40rem; margin-inline: auto; }
    .reg-form-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        padding: clamp(1.5rem, 4vw, 2.5rem);
    }
    .reg-section { margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 1px solid #e2e8f0; }
    .reg-section:last-of-type { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .reg-section-title {
        font-family: 'Fraunces', serif;
        color: #0f3d2c;
        font-weight: 700;
        font-size: 1.15rem;
        margin: 0 0 0.35rem;
    }
    .reg-section-hint { color: #64748b; font-size: 0.9rem; margin: 0 0 1.25rem; line-height: 1.55; }
    .reg-section-title span { color: #d97706; font-weight: 800; margin-right: 0.35rem; }
    .reg-grid { display: grid; grid-template-columns: 1fr; gap: 0 1.25rem; }
    @media (min-width: 576px) {
        .reg-grid--2 { grid-template-columns: 1fr 1fr; }
        .reg-grid--3 { grid-template-columns: 1fr 1fr 1fr; }
    }
    .reg-field { display: flex; flex-direction: column; margin-bottom: 1.25rem; min-width: 0; }
    .reg-field--full { grid-column: 1 / -1; }
    .reg-label {
        font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;
        font-weight: 700; color: #64748b; margin-bottom: 0.5rem;
    }
    .reg-input, .reg-textarea, .reg-select {
        width: 100%; box-sizing: border-box; border-radius: 10px;
        background: #f8fafc; border: 2px solid #e2e8f0;
        padding: 0.85rem 1.15rem; font-size: 1rem; font-family: inherit; color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
    }
    .reg-textarea { min-height: 100px; resize: vertical; line-height: 1.55; }
    .reg-input:focus, .reg-textarea:focus, .reg-select:focus {
        background: #fff; border-color: #d97706;
        box-shadow: 0 0 0 4px rgba(16,185,129,0.12); outline: none;
    }
    .reg-input.is-invalid, .reg-textarea.is-invalid { border-color: #ef4444; background: #fef2f2; }
    .reg-check-grid {
        display: grid; grid-template-columns: 1fr; gap: 0.65rem;
    }
    @media (min-width: 480px) { .reg-check-grid { grid-template-columns: 1fr 1fr; } }
    @media (min-width: 768px) { .reg-check-grid--3 { grid-template-columns: repeat(3, 1fr); } }
    .reg-check {
        display: flex; align-items: flex-start; gap: 0.65rem;
        padding: 0.85rem 1rem; background: #f8fafc;
        border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer;
        transition: border-color 0.2s, background 0.2s; margin: 0;
    }
    .reg-check:hover { border-color: #cbd5e1; }
    .reg-check:has(input:checked) { border-color: #d97706; background: #fffbeb; }
    .reg-check input { width: 1.1rem; height: 1.1rem; margin-top: 0.15rem; flex-shrink: 0; accent-color: #0f3d2c; }
    .reg-check span { font-size: 0.95rem; color: #334155; font-weight: 500; line-height: 1.4; }
    .reg-radio-group { display: flex; flex-wrap: wrap; gap: 1rem; }
    .reg-radio {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 1.25rem; background: #f8fafc;
        border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; margin: 0;
    }
    .reg-radio:has(input:checked) { border-color: #d97706; background: #fffbeb; }
    .reg-radio input { accent-color: #0f3d2c; }
    .reg-other-wrap { margin-top: 1rem; }
    .reg-bearer-card {
        padding: 1.25rem; background: #f8fafc;
        border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 1.25rem;
    }
    .reg-bearer-title { font-weight: 700; color: #0f3d2c; margin: 0 0 1rem; font-size: 0.95rem; }
    .reg-bearer-title small { font-weight: 500; color: #94a3b8; }
    .reg-file {
        padding: 1.25rem; background: #f8fafc;
        border: 2px dashed #cbd5e1; border-radius: 12px; text-align: center;
        transition: border-color 0.2s, background 0.2s;
    }
    .reg-file:hover { border-color: #d97706; background: #fffbeb; }
    .reg-file p { margin: 0 0 0.75rem; color: #64748b; font-size: 0.9rem; }
    .reg-file input[type="file"] { font-size: 0.9rem; width: 100%; }
    .reg-declaration {
        padding: 1.25rem; background: #fffbeb;
        border: 1px solid #fde68a; border-radius: 12px;
    }
    .reg-declaration label { display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; margin: 0; }
    .reg-declaration input { width: 1.15rem; height: 1.15rem; margin-top: 0.2rem; flex-shrink: 0; accent-color: #0f3d2c; }
    .reg-declaration span { color: #78350f; font-size: 0.95rem; line-height: 1.55; }
    .reg-notes {
        margin-top: 2rem; padding: 1.25rem 1.5rem;
        background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;
    }
    .reg-notes h3 { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; color: #0f3d2c; margin: 0 0 0.75rem; }
    .reg-notes ul { margin: 0; padding-left: 1.25rem; color: #475569; font-size: 0.9rem; line-height: 1.6; }
    .reg-notes p { margin: 1rem 0 0; color: #475569; font-size: 0.9rem; line-height: 1.6; }
    .reg-alert { padding: 0.85rem 1.1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.95rem; }
    .reg-alert--success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .reg-alert--error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .reg-field-error { color: #dc2626; font-size: 0.85rem; margin-top: 0.35rem; }
    .reg-submit {
        display: block; width: 100%; margin-top: 1.5rem;
        background: linear-gradient(135deg, #0f3d2c, #1a6b4a);
        color: #fff; border: none; padding: 1rem 2rem; border-radius: 10px;
        font-weight: 600; font-size: 1.05rem; cursor: pointer;
        box-shadow: 0 8px 20px rgba(15,61,44,0.2);
        transition: transform 0.2s, box-shadow 0.2s; font-family: inherit;
    }
    .reg-submit:hover { transform: translateY(-1px); box-shadow: 0 12px 25px rgba(15,61,44,0.28); }
    .reg-signatory-num { font-size: 0.8rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 0.75rem; }
</style>

<div class="reg-page">
    <header class="reg-hero">
        <h1>Mukmin Organisation Membership Registration Form</h1>
        <p>Complete all sections below. Fields marked with * are required.</p>
    </header>

    <div class="reg-form-card">
        @if(session('success'))
            <div class="reg-alert reg-alert--success" role="status"><strong>Submitted!</strong> {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="reg-alert reg-alert--error" role="alert">
                <strong>Please correct the following:</strong>
                <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Section A --}}
            <section class="reg-section" aria-labelledby="reg-sec-a">
                <h2 class="reg-section-title" id="reg-sec-a"><span>A</span> Organisation Category</h2>
                <p class="reg-section-hint">Please check the box that applies to your organisation category. You may check more than one if your organisation falls into more than one category.</p>
                <div class="reg-check-grid">
                    @foreach($organizationCategories as $cat)
                        <label class="reg-check">
                            <input type="checkbox" name="organization_categories[]" value="{{ $cat }}"
                                {{ in_array($cat, old('organization_categories', [])) ? 'checked' : '' }}>
                            <span>{{ $cat === 'Others' ? 'Others (please specify)' : $cat }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="reg-other-wrap" id="category-other-wrap" style="{{ in_array('Others', old('organization_categories', [])) ? '' : 'display:none' }}">
                    <label class="reg-label" for="organization_category_other">Specify other category</label>
                    <input type="text" id="organization_category_other" name="organization_category_other"
                        class="reg-input @error('organization_category_other') is-invalid @enderror"
                        value="{{ old('organization_category_other') }}" placeholder="Please specify">
                    @error('organization_category_other')<span class="reg-field-error">{{ $message }}</span>@enderror
                </div>
            </section>

            {{-- Section B --}}
            <section class="reg-section" aria-labelledby="reg-sec-b">
                <h2 class="reg-section-title" id="reg-sec-b"><span>B</span> Organisation Details</h2>
                <div class="reg-grid">
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="registered_name">Registered Name *</label>
                        <input type="text" id="registered_name" name="registered_name" class="reg-input @error('registered_name') is-invalid @enderror" required value="{{ old('registered_name') }}">
                        @error('registered_name')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="address">Full Address of Organisation *</label>
                        <textarea id="address" name="address" class="reg-textarea @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                        @error('address')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="reg-grid reg-grid--2">
                    <div class="reg-field">
                        <label class="reg-label" for="postcode">Postcode *</label>
                        <input type="text" id="postcode" name="postcode" class="reg-input @error('postcode') is-invalid @enderror" required value="{{ old('postcode') }}">
                        @error('postcode')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="district">District / City *</label>
                        <input type="text" id="district" name="district" class="reg-input @error('district') is-invalid @enderror" required value="{{ old('district') }}">
                        @error('district')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="state">State / Province *</label>
                        <input type="text" id="state" name="state" class="reg-input @error('state') is-invalid @enderror" required value="{{ old('state') }}">
                        @error('state')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="registration_number">Registration Number / Recognition No. *</label>
                        <input type="text" id="registration_number" name="registration_number" class="reg-input @error('registration_number') is-invalid @enderror" required value="{{ old('registration_number') }}">
                        @error('registration_number')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="registration_date">Date of Registration *</label>
                        <input type="date" id="registration_date" name="registration_date" class="reg-input @error('registration_date') is-invalid @enderror" required value="{{ old('registration_date') }}">
                        @error('registration_date')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="email">Official Email Address *</label>
                        <input type="email" id="email" name="email" class="reg-input @error('email') is-invalid @enderror" required value="{{ old('email') }}" autocomplete="email">
                        @error('email')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="contact_number">Official Phone Number(s) *</label>
                        <input type="tel" id="contact_number" name="contact_number" class="reg-input @error('contact_number') is-invalid @enderror" required value="{{ old('contact_number') }}" autocomplete="tel">
                        @error('contact_number')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="website">Website / Social Media Link <span style="font-weight:500;text-transform:none">(if applicable)</span></label>
                        <input type="text" id="website" name="website" class="reg-input @error('website') is-invalid @enderror" placeholder="https:// or @handle" value="{{ old('website') }}">
                        @error('website')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            {{-- Section C --}}
            <section class="reg-section" aria-labelledby="reg-sec-c">
                <h2 class="reg-section-title" id="reg-sec-c"><span>C</span> Organisation Profile</h2>
                <p class="reg-section-hint">Type of organisation (select all that apply)</p>
                <div class="reg-check-grid reg-check-grid--3">
                    @foreach($profileTypes as $type)
                        <label class="reg-check">
                            <input type="checkbox" name="organization_profile_types[]" value="{{ $type }}"
                                {{ in_array($type, old('organization_profile_types', [])) ? 'checked' : '' }}>
                            <span>{{ $type === 'Others' ? 'Others' : $type }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="reg-other-wrap" id="profile-other-wrap" style="{{ in_array('Others', old('organization_profile_types', [])) ? '' : 'display:none' }}">
                    <label class="reg-label" for="organization_profile_other">Specify other type</label>
                    <input type="text" id="organization_profile_other" name="organization_profile_other" class="reg-input" value="{{ old('organization_profile_other') }}">
                </div>

                <p class="reg-section-hint" style="margin-top:1.5rem">Primary focus — please select the areas that apply</p>
                <div class="reg-check-grid reg-check-grid--3">
                    @foreach($focusAreas as $area)
                        <label class="reg-check">
                            <input type="checkbox" name="primary_focus_areas[]" value="{{ $area }}"
                                {{ in_array($area, old('primary_focus_areas', [])) ? 'checked' : '' }}>
                            <span>{{ $area === 'Others' ? 'Others' : $area }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="reg-other-wrap" id="focus-other-wrap" style="{{ in_array('Others', old('primary_focus_areas', [])) ? '' : 'display:none' }}">
                    <label class="reg-label" for="primary_focus_other">Specify other focus area</label>
                    <input type="text" id="primary_focus_other" name="primary_focus_other" class="reg-input" value="{{ old('primary_focus_other') }}">
                </div>
            </section>

            {{-- Section D --}}
            <section class="reg-section" aria-labelledby="reg-sec-d">
                <h2 class="reg-section-title" id="reg-sec-d"><span>D</span> Governance &amp; Legal</h2>
                <p class="reg-section-hint">Is your organisation registered with ROS? (tick where applicable)</p>
                <div class="reg-radio-group" role="radiogroup" aria-label="ROS registration">
                    <label class="reg-radio">
                        <input type="radio" name="is_registered_with_ros" value="1" required {{ old('is_registered_with_ros') === '1' || old('is_registered_with_ros') === 1 ? 'checked' : '' }}>
                        <span>Yes</span>
                    </label>
                    <label class="reg-radio">
                        <input type="radio" name="is_registered_with_ros" value="0" {{ old('is_registered_with_ros') === '0' || old('is_registered_with_ros') === 0 ? 'checked' : '' }}>
                        <span>No</span>
                    </label>
                </div>
                @error('is_registered_with_ros')<span class="reg-field-error">{{ $message }}</span>@enderror

                <p class="reg-section-hint" style="margin-top:1.25rem">Please attach the following documents (PDF, JPG, PNG — max 5MB each):</p>
                <div class="reg-grid reg-grid--2">
                    <div class="reg-file">
                        <p><strong>Registration Certificate</strong><br>ROS / SSM / any other body *</p>
                        <input type="file" name="registration_certificate" accept=".pdf,.jpg,.jpeg,.png" required>
                        @error('registration_certificate')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-file">
                        <p><strong>List of Executive Members</strong> *</p>
                        <input type="file" name="committee_members" accept=".pdf,.jpg,.jpeg,.png" required>
                        @error('committee_members')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            {{-- Section E --}}
            <section class="reg-section" aria-labelledby="reg-sec-e">
                <h2 class="reg-section-title" id="reg-sec-e"><span>E</span> Key Office Bearers</h2>

                <div class="reg-bearer-card">
                    <p class="reg-bearer-title">President / Chairman</p>
                    <div class="reg-grid reg-grid--3">
                        <div class="reg-field">
                            <label class="reg-label" for="president_name">Full Name *</label>
                            <input type="text" id="president_name" name="president_name" class="reg-input" required value="{{ old('president_name') }}">
                        </div>
                        <div class="reg-field">
                            <label class="reg-label" for="president_phone">Phone Number *</label>
                            <input type="tel" id="president_phone" name="president_phone" class="reg-input" required value="{{ old('president_phone') }}">
                        </div>
                        <div class="reg-field">
                            <label class="reg-label" for="president_email">Email Address *</label>
                            <input type="email" id="president_email" name="president_email" class="reg-input" required value="{{ old('president_email') }}">
                        </div>
                    </div>
                </div>

                <div class="reg-bearer-card">
                    <p class="reg-bearer-title">Secretary</p>
                    <div class="reg-grid reg-grid--3">
                        <div class="reg-field">
                            <label class="reg-label" for="secretary_name">Full Name *</label>
                            <input type="text" id="secretary_name" name="secretary_name" class="reg-input" required value="{{ old('secretary_name') }}">
                        </div>
                        <div class="reg-field">
                            <label class="reg-label" for="secretary_phone">Phone Number *</label>
                            <input type="tel" id="secretary_phone" name="secretary_phone" class="reg-input" required value="{{ old('secretary_phone') }}">
                        </div>
                        <div class="reg-field">
                            <label class="reg-label" for="secretary_email">Email Address *</label>
                            <input type="email" id="secretary_email" name="secretary_email" class="reg-input" required value="{{ old('secretary_email') }}">
                        </div>
                    </div>
                </div>

                <div class="reg-bearer-card">
                    <p class="reg-bearer-title">Treasurer <small>(optional)</small></p>
                    <div class="reg-grid reg-grid--3">
                        <div class="reg-field">
                            <label class="reg-label" for="treasurer_name">Full Name</label>
                            <input type="text" id="treasurer_name" name="treasurer_name" class="reg-input" value="{{ old('treasurer_name') }}">
                        </div>
                        <div class="reg-field">
                            <label class="reg-label" for="treasurer_phone">Phone Number</label>
                            <input type="tel" id="treasurer_phone" name="treasurer_phone" class="reg-input" value="{{ old('treasurer_phone') }}">
                        </div>
                        <div class="reg-field">
                            <label class="reg-label" for="treasurer_email">Email Address</label>
                            <input type="email" id="treasurer_email" name="treasurer_email" class="reg-input" value="{{ old('treasurer_email') }}">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Section F --}}
            <section class="reg-section" aria-labelledby="reg-sec-f">
                <h2 class="reg-section-title" id="reg-sec-f"><span>F</span> Declaration</h2>
                <div class="reg-declaration">
                    <label>
                        <input type="checkbox" name="agreed_to_declaration" value="1" required {{ old('agreed_to_declaration') ? 'checked' : '' }}>
                        <span>I declare that the information provided in this form is true and accurate. I agree to comply with the constitution and rules of the Mukmin Organisation. *</span>
                    </label>
                </div>
                @error('agreed_to_declaration')<span class="reg-field-error">{{ $message }}</span>@enderror
            </section>

            {{-- Section G --}}
            <section class="reg-section" aria-labelledby="reg-sec-g">
                <h2 class="reg-section-title" id="reg-sec-g"><span>G</span> Authorised Signatories <small style="font-weight:500;color:#94a3b8;font-size:0.85rem">(min. 2)</small></h2>
                @for($i = 0; $i < 2; $i++)
                    <div class="reg-bearer-card">
                        <p class="reg-signatory-num">Signatory {{ $i + 1 }}</p>
                        <div class="reg-grid reg-grid--3">
                            <div class="reg-field">
                                <label class="reg-label" for="signatory_{{ $i }}_name">Name *</label>
                                <input type="text" id="signatory_{{ $i }}_name" name="signatories[{{ $i }}][name]" class="reg-input" required value="{{ old('signatories.'.$i.'.name') }}">
                            </div>
                            <div class="reg-field">
                                <label class="reg-label" for="signatory_{{ $i }}_position">Position *</label>
                                <input type="text" id="signatory_{{ $i }}_position" name="signatories[{{ $i }}][position]" class="reg-input" required value="{{ old('signatories.'.$i.'.position') }}">
                            </div>
                            <div class="reg-field">
                                <label class="reg-label" for="signatory_{{ $i }}_date">Date *</label>
                                <input type="date" id="signatory_{{ $i }}_date" name="signatories[{{ $i }}][date]" class="reg-input" required value="{{ old('signatories.'.$i.'.date') }}">
                            </div>
                        </div>
                    </div>
                @endfor
            </section>

            <button type="submit" class="reg-submit">Submit Registration</button>
        </form>

        <aside class="reg-notes" aria-label="Important information">
            <h3>Important Notes</h3>
            <ul>
                <li>All applications are subject to approval by the Mukmin Organisation.</li>
                <li>Additional information or documents may be requested during the review process.</li>
                <li>The review process takes approximately 14 working days from the date of submission.</li>
                <li>All information submitted will be kept confidential.</li>
            </ul>
            <p><strong>Mukmin Membership Eligibility:</strong> Membership is open to organisations that share similar values and are committed to the upliftment of the Muslim community, operating with integrity, compassion, and excellence.</p>
        </aside>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function toggleOther(checkboxName, otherValue, wrapId) {
        const wrap = document.getElementById(wrapId);
        if (!wrap) return;
        const boxes = document.querySelectorAll('input[name="' + checkboxName + '[]"]');
        function update() {
            let show = false;
            boxes.forEach(function (cb) {
                if (cb.value === otherValue && cb.checked) show = true;
            });
            wrap.style.display = show ? '' : 'none';
        }
        boxes.forEach(function (cb) { cb.addEventListener('change', update); });
        update();
    }
    toggleOther('organization_categories', 'Others', 'category-other-wrap');
    toggleOther('organization_profile_types', 'Others', 'profile-other-wrap');
    toggleOther('primary_focus_areas', 'Others', 'focus-other-wrap');
});
</script>
@endsection
