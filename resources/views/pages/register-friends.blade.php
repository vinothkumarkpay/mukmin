@extends('layouts.public')

@section('content')
<style>
    .reg-page { padding: 1.25rem 0 3rem; }

    /* Membership hero — photo backdrop + bright standalone heading */
    .reg-hero {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        padding: clamp(3rem, 6.5vw, 4.75rem) clamp(1.25rem, 5vw, 3rem);
        margin: 0 0 2.5rem;
        text-align: center;
        background-image: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        isolation: isolate;
    }
    .reg-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 20% 30%, rgba(245, 158, 11, 0.55) 0%, rgba(245, 158, 11, 0) 55%),
            radial-gradient(ellipse at 80% 80%, rgba(13, 148, 136, 0.55) 0%, rgba(13, 148, 136, 0) 55%),
            linear-gradient(135deg, rgba(15, 23, 42, 0.55) 0%, rgba(15, 23, 42, 0.35) 50%, rgba(15, 23, 42, 0.55) 100%);
        z-index: 1;
    }
    .reg-hero__content {
        position: relative;
        z-index: 2;
        max-width: 920px;
        margin: 0 auto;
    }
    .reg-hero__eyebrow {
        display: inline-block;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #b45309;
        background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
        padding: 0.5rem 1.15rem;
        border-radius: 999px;
        margin-bottom: 1.2rem;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    }
    .reg-hero h1 {
        font-family: 'Fraunces', serif;
        font-size: clamp(2.15rem, 4.8vw, 3.4rem);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.01em;
        margin: 0 0 1rem;
        background: linear-gradient(95deg, #fde68a 0%, #fbbf24 35%, #fb923c 65%, #5eead4 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: #fbbf24;
        text-shadow: 0 2px 18px rgba(0, 0, 0, 0.35);
        filter: drop-shadow(0 2px 12px rgba(0, 0, 0, 0.35));
        text-transform: none;
    }
    .reg-hero__subtitle {
        display: inline-block;
        font-size: clamp(1.3rem, 2.6vw, 1.85rem);
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #ffffff;
        margin: 0 0 1.25rem;
        padding: 0.35rem 1.25rem;
        background: linear-gradient(90deg, rgba(234, 88, 12, 0.85) 0%, rgba(13, 148, 136, 0.85) 100%);
        border-radius: 999px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.25);
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.3);
    }
    .reg-hero__divider {
        width: 140px;
        height: 4px;
        margin: 0 auto 1rem;
        border-radius: 999px;
        background: linear-gradient(90deg, #facc15 0%, #fb923c 55%, #5eead4 100%);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
    }
    .reg-hero p {
        color: rgba(255, 255, 255, 0.95);
        margin: 0 auto;
        font-size: clamp(0.98rem, 1.6vw, 1.1rem);
        line-height: 1.6;
        max-width: 40rem;
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.35);
    }

    @media (max-width: 575px) {
        .reg-hero {
            border-radius: 18px;
            margin-bottom: 2rem;
        }
        .reg-hero__eyebrow {
            font-size: 0.7rem;
            padding: 0.4rem 0.95rem;
        }
        .reg-hero__subtitle {
            padding: 0.3rem 1rem;
        }
    }
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
</style>

<div class="reg-page">
    <header class="reg-hero">
        <div class="reg-hero__content">
            <span class="reg-hero__eyebrow">Membership</span>
            <h1>Friends of MUKMIN</h1>
            <p class="reg-hero__subtitle">Ahli Bersekutu Registration</p>
            <div class="reg-hero__divider" aria-hidden="true"></div>
            <p>Complete all applicable sections below.</p>
        </div>
    </header>

    <div style="margin-bottom: 1.25rem;">
        <a href="{{ route('register.show') }}" style="display:inline-flex;align-items:center;gap:0.4rem;color:#64748b;font-size:0.92rem;font-weight:600;text-decoration:none;transition:color 0.2s;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back to membership selection
        </a>
    </div>

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

        <form action="{{ route('register.friends.submit') }}" method="POST">
            @csrf

            {{-- Section A --}}
            <section class="reg-section" aria-labelledby="reg-sec-a">
                <h2 class="reg-section-title" id="reg-sec-a"><span>A</span> Organisation Category</h2>
                <p class="reg-section-hint">Please tick the relevant category:</p>
                <div class="reg-radio-group">
                    <label class="reg-radio">
                        <input type="radio" name="organization_category" value="Surau" required {{ old('organization_category') === 'Surau' ? 'checked' : '' }}>
                        <span>Surau</span>
                    </label>
                    <label class="reg-radio">
                        <input type="radio" name="organization_category" value="Madrasah" required {{ old('organization_category') === 'Madrasah' ? 'checked' : '' }}>
                        <span>Madrasah</span>
                    </label>
                    <label class="reg-radio">
                        <input type="radio" name="organization_category" value="Others" required {{ old('organization_category') === 'Others' ? 'checked' : '' }}>
                        <span>Others</span>
                    </label>
                </div>
                @error('organization_category')<span class="reg-field-error">{{ $message }}</span>@enderror

                <div class="reg-other-wrap" id="category-other-wrap" style="{{ old('organization_category') === 'Others' ? '' : 'display:none' }}">
                    <label class="reg-label" for="organization_category_other">Specify other category *</label>
                    <input type="text" id="organization_category_other" name="organization_category_other"
                        class="reg-input @error('organization_category_other') is-invalid @enderror"
                        value="{{ old('organization_category_other') }}" placeholder="Please specify">
                    @error('organization_category_other')<span class="reg-field-error">{{ $message }}</span>@enderror
                </div>
            </section>

            {{-- Section B --}}
            <section class="reg-section" aria-labelledby="reg-sec-b">
                <h2 class="reg-section-title" id="reg-sec-b"><span>B</span> Organisation Details (If Applicable)</h2>
                <div class="reg-grid">
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="org_name">Name of Organisation</label>
                        <input type="text" id="org_name" name="org_name" class="reg-input @error('org_name') is-invalid @enderror" value="{{ old('org_name') }}">
                        @error('org_name')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="reg-grid reg-grid--2">
                    <div class="reg-field">
                        <label class="reg-label" for="org_registration_number">Organisation Registration Number</label>
                        <input type="text" id="org_registration_number" name="org_registration_number" class="reg-input @error('org_registration_number') is-invalid @enderror" value="{{ old('org_registration_number') }}">
                        @error('org_registration_number')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="org_state">State</label>
                        <select id="org_state" name="org_state" class="reg-select @error('org_state') is-invalid @enderror">
                            <option value="">Select State</option>
                            @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'W.P. Kuala Lumpur', 'W.P. Labuan', 'W.P. Putrajaya'] as $state)
                                <option value="{{ $state }}" {{ old('org_state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                        @error('org_state')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="org_address">Full Address of Organisation</label>
                        <textarea id="org_address" name="org_address" class="reg-textarea @error('org_address') is-invalid @enderror">{{ old('org_address') }}</textarea>
                        @error('org_address')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="org_email">Official Organisation Email Address</label>
                        <input type="email" id="org_email" name="org_email" class="reg-input @error('org_email') is-invalid @enderror" value="{{ old('org_email') }}">
                        @error('org_email')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="org_contact_number">Official Contact Number (WhatsApp preferred)</label>
                        <input type="tel" id="org_contact_number" name="org_contact_number" class="reg-input @error('org_contact_number') is-invalid @enderror" value="{{ old('org_contact_number') }}">
                        @error('org_contact_number')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="org_website">Website / Social Media (if any)</label>
                        <input type="text" id="org_website" name="org_website" class="reg-input @error('org_website') is-invalid @enderror" placeholder="https:// or @handle" value="{{ old('org_website') }}">
                        @error('org_website')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            {{-- Section C --}}
            <section class="reg-section" aria-labelledby="reg-sec-c">
                <h2 class="reg-section-title" id="reg-sec-c"><span>C</span> Individual Details (If Applicable)</h2>
                <div class="reg-grid">
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="individual_name">Full Name</label>
                        <input type="text" id="individual_name" name="individual_name" class="reg-input @error('individual_name') is-invalid @enderror" value="{{ old('individual_name') }}">
                        @error('individual_name')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="reg-grid reg-grid--2">
                    <div class="reg-field">
                        <label class="reg-label" for="individual_nric">NRIC Number</label>
                        <input type="text" id="individual_nric" name="individual_nric" class="reg-input @error('individual_nric') is-invalid @enderror" value="{{ old('individual_nric') }}">
                        @error('individual_nric')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="individual_state">State of Residency</label>
                        <select id="individual_state" name="individual_state" class="reg-select @error('individual_state') is-invalid @enderror">
                            <option value="">Select State</option>
                            @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'W.P. Kuala Lumpur', 'W.P. Labuan', 'W.P. Putrajaya'] as $state)
                                <option value="{{ $state }}" {{ old('individual_state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                        @error('individual_state')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field reg-field--full">
                        <label class="reg-label" for="individual_address">Full Address</label>
                        <textarea id="individual_address" name="individual_address" class="reg-textarea @error('individual_address') is-invalid @enderror">{{ old('individual_address') }}</textarea>
                        @error('individual_address')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="individual_email">Email Address</label>
                        <input type="email" id="individual_email" name="individual_email" class="reg-input @error('individual_email') is-invalid @enderror" value="{{ old('individual_email') }}">
                        @error('individual_email')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="reg-field">
                        <label class="reg-label" for="individual_contact_number">Contact Number</label>
                        <input type="tel" id="individual_contact_number" name="individual_contact_number" class="reg-input @error('individual_contact_number') is-invalid @enderror" value="{{ old('individual_contact_number') }}">
                        @error('individual_contact_number')<span class="reg-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            <section class="reg-section" aria-labelledby="reg-sec-d">
                <h2 class="reg-section-title" id="reg-sec-d"><span>G</span> Declaration</h2>
                <div class="reg-declaration">
                    <label style="margin-bottom: 1rem;">
                        <input type="checkbox" name="agreed_to_accuracy" value="1" required {{ old('agreed_to_accuracy') ? 'checked' : '' }}>
                        <span>I hereby confirm that all information provided in this form is true and accurate. *</span>
                    </label>
                    <label>
                        <input type="checkbox" name="agreed_to_policies" value="1" required {{ old('agreed_to_policies') ? 'checked' : '' }}>
                        <span>I agree to comply with the constitution, policies, and guidelines of MUKMIN. *</span>
                    </label>
                </div>
                @error('agreed_to_accuracy')<div class="reg-field-error" style="margin-top: 0.5rem;">{{ $message }}</div>@enderror
                @error('agreed_to_policies')<div class="reg-field-error" style="margin-top: 0.5rem;">{{ $message }}</div>@enderror
            </section>

            <button type="submit" class="reg-submit">Submit Registration</button>
        </form>

        <aside class="reg-notes" aria-label="Important information">
            <h3>Important Notes</h3>
            <ul>
                <li>Incomplete forms may not be processed</li>
                <li>MUKMIN reserves the right to approve or reject any application</li>
                <li>Additional supporting documents may be requested if necessary</li>
            </ul>
            <p><strong>MUKMIN Membership Eligibility:</strong> Membership is open to organisations including NGOs, masjid, surau, and madrasah that align with the mission, vision, and values of MUKMIN. All members must demonstrate active involvement in community, educational, or welfare initiatives and commit to collaborative participation in MUKMIN programmes. The MUKMIN committee reserves the right to approve or reject applications.</p>
        </aside>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="organization_category"]');
    const wrap = document.getElementById('category-other-wrap');
    
    function update() {
        let show = false;
        radios.forEach(function (radio) {
            if (radio.value === 'Others' && radio.checked) {
                show = true;
            }
        });
        wrap.style.display = show ? '' : 'none';
    }
    
    radios.forEach(function (radio) {
        radio.addEventListener('change', update);
    });
    
    update();
});
</script>
@endsection
