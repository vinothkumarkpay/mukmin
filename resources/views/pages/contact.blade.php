@extends('layouts.public')

@section('content')
<style>
    .contact-page {
        padding: 2rem 0 3rem;
    }

    .contact-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    @media (min-width: 992px) {
        .contact-layout {
            grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
            gap: 3.5rem;
        }
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .contact-header-title {
        font-family: 'Fraunces', serif;
        font-weight: 800;
        color: #0f3d2c;
        font-size: clamp(2rem, 5vw, 2.75rem);
        line-height: 1.15;
        margin: 0 0 1rem;
    }

    .contact-intro {
        color: #64748b;
        font-size: 1.05rem;
        line-height: 1.65;
        margin: 0 0 2rem;
        max-width: 28rem;
    }

    .contact-info-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .contact-info-item {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        padding: 1.15rem 1.25rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .contact-info-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 16px rgba(15, 61, 44, 0.06);
    }

    .contact-info-icon {
        flex-shrink: 0;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ecfdf5;
        color: #0f3d2c;
        border-radius: 10px;
    }

    .contact-info-icon svg {
        width: 1.15rem;
        height: 1.15rem;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .contact-info-body {
        min-width: 0;
    }

    .contact-label {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1.2px;
        color: #94a3b8;
        margin: 0 0 0.25rem;
        display: block;
    }

    .contact-detail {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 500;
        line-height: 1.5;
        margin: 0;
        word-break: break-word;
    }

    .contact-detail a {
        color: #10b981;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .contact-detail a:hover {
        color: #0f3d2c;
    }

    .contact-form-card {
        background: #ffffff;
        padding: clamp(1.5rem, 4vw, 2.5rem);
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
    }

    .contact-form-heading {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        color: #0f3d2c;
        font-size: 1.35rem;
        margin: 0 0 0.35rem;
    }

    .contact-form-subheading {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0 0 1.75rem;
        line-height: 1.5;
    }

    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .contact-form-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0 1.25rem;
    }

    @media (min-width: 576px) {
        .contact-form-row {
            grid-template-columns: 1fr 1fr;
        }
    }

    .contact-field {
        display: flex;
        flex-direction: column;
        margin-bottom: 1.25rem;
        min-width: 0;
    }

    .contact-field--full {
        grid-column: 1 / -1;
    }

    .contact-form-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .contact-form-input,
    .contact-form-textarea {
        width: 100%;
        box-sizing: border-box;
        border-radius: 10px;
        background-color: #f8fafc;
        border: 2px solid #e2e8f0;
        padding: 0.85rem 1.15rem;
        font-size: 1rem;
        font-family: inherit;
        color: #1e293b;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .contact-form-input::placeholder,
    .contact-form-textarea::placeholder {
        color: #94a3b8;
    }

    .contact-form-input:focus,
    .contact-form-textarea:focus {
        background-color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
        outline: none;
    }

    .contact-form-input.is-invalid,
    .contact-form-textarea.is-invalid {
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    .contact-form-textarea {
        min-height: 140px;
        resize: vertical;
        line-height: 1.55;
    }

    .contact-field-error {
        color: #dc2626;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 0.35rem;
    }

    .contact-alert {
        padding: 0.85rem 1.1rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .contact-alert--success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .contact-alert--error {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .contact-submit {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
        background: linear-gradient(135deg, #0f3d2c, #1a6b4a);
        color: #ffffff;
        border: none;
        padding: 1rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.05rem;
        letter-spacing: 0.3px;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(15, 61, 44, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        font-family: inherit;
    }

    .contact-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 25px rgba(15, 61, 44, 0.28);
    }

    .contact-submit:active {
        transform: translateY(0);
    }
</style>

<div class="contact-page">
    <div class="contact-layout">
        <aside class="contact-info" aria-label="{{ __('Contact information') }}">
            <h1 class="contact-header-title">Get in touch</h1>
            <p class="contact-intro">Whether you're looking for more information or want to collaborate, our team is ready to help you.</p>

            <ul class="contact-info-list">
                <li class="contact-info-item">
                    <span class="contact-info-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-4.5 7-10a7 7 0 1 0-14 0c0 5.5 7 10 7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                    </span>
                    <div class="contact-info-body">
                        <span class="contact-label">Our Office</span>
                        <p class="contact-detail">Kuala Lumpur, Malaysia</p>
                    </div>
                </li>
                <li class="contact-info-item">
                    <span class="contact-info-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                    </span>
                    <div class="contact-info-body">
                        <span class="contact-label">Email Address</span>
                        <p class="contact-detail">
                            <a href="mailto:info@fikrah.org">info@fikrah.org</a>
                        </p>
                    </div>
                </li>
                <li class="contact-info-item">
                    <span class="contact-info-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </span>
                    <div class="contact-info-body">
                        <span class="contact-label">Phone Support</span>
                        <p class="contact-detail">
                            <a href="tel:+601255555555">+6012 5555 5555</a>
                        </p>
                    </div>
                </li>
            </ul>
        </aside>

        <div class="contact-form-card">
            <h2 class="contact-form-heading">Send us a message</h2>
            <p class="contact-form-subheading">Fill in the form below and we'll get back to you as soon as possible.</p>

            @if(session('success'))
                <div class="contact-alert contact-alert--success" role="status">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="contact-alert contact-alert--error" role="alert">
                    <strong>Please correct the following:</strong>
                    <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="contact-form" action="{{ route('contact.submit') }}" method="POST">
                @csrf

                <div class="contact-form-row">
                    <div class="contact-field">
                        <label class="contact-form-label" for="contact-name">Full Name</label>
                        <input
                            type="text"
                            id="contact-name"
                            name="name"
                            class="contact-form-input @error('name') is-invalid @enderror"
                            placeholder="Your full name"
                            required
                            value="{{ old('name') }}"
                            autocomplete="name"
                        >
                        @error('name')
                            <span class="contact-field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="contact-field">
                        <label class="contact-form-label" for="contact-email">Email Address</label>
                        <input
                            type="email"
                            id="contact-email"
                            name="email"
                            class="contact-form-input @error('email') is-invalid @enderror"
                            placeholder="name@email.com"
                            required
                            value="{{ old('email') }}"
                            autocomplete="email"
                        >
                        @error('email')
                            <span class="contact-field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="contact-field contact-field--full">
                    <label class="contact-form-label" for="contact-phone">Phone Number</label>
                    <input
                        type="tel"
                        id="contact-phone"
                        name="phone"
                        class="contact-form-input @error('phone') is-invalid @enderror"
                        placeholder="+6012 345 6789"
                        required
                        value="{{ old('phone') }}"
                        autocomplete="tel"
                    >
                    @error('phone')
                        <span class="contact-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="contact-field contact-field--full">
                    <label class="contact-form-label" for="contact-message">How can we help?</label>
                    <textarea
                        id="contact-message"
                        name="message"
                        class="contact-form-textarea @error('message') is-invalid @enderror"
                        placeholder="Tell us more about your inquiry..."
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <span class="contact-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="contact-submit">Send Message</button>
            </form>
        </div>
    </div>
</div>
@endsection
