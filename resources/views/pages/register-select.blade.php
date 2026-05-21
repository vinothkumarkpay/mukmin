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
        max-width: 800px;
        margin: 0 auto;
    }
    
    .category-selection-title {
        font-family: 'Fraunces', serif;
        color: #0f3d2c;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0 0 0.5rem;
        text-align: center;
    }
    
    .category-selection-subtitle {
        text-align: center;
        color: #64748b;
        margin-bottom: 2rem;
        font-size: 1.05rem;
    }

    .category-card {
        display: block;
        padding: 1.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        background: #f8fafc;
        text-decoration: none;
        color: inherit;
    }

    .category-card:hover {
        border-color: #d97706;
        background: #fffbeb;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(217, 119, 6, 0.1);
        text-decoration: none !important;
    }

    .category-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f3d2c;
        margin: 0 0 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .category-card p {
        margin: 0;
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .category-icon {
        color: #d97706;
    }
    
    .arrow-icon {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        transition: transform 0.2s ease, color 0.2s ease;
    }
    
    .category-card:hover .arrow-icon {
        color: #d97706;
        transform: translate(4px, -50%);
    }

</style>

<div class="reg-page">
    <header class="reg-hero">
        <div class="reg-hero__content">
            <span class="reg-hero__eyebrow">Membership</span>
            <h1>MUKMIN Organisation Membership</h1>
            <div class="reg-hero__divider" aria-hidden="true"></div>
            <p>Join a national platform advancing inclusive community development.</p>
        </div>
    </header>

    <div class="reg-form-card">
        <h2 class="category-selection-title">Select Membership Category</h2>
        <p class="category-selection-subtitle">Please select the membership category that best represents your organisation.</p>

        <a href="{{ route('register.ordinary.show') }}" class="category-card">
            <h3>
                <svg class="category-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Ordinary Member (Ahli Biasa)
            </h3>
            <p>Organisations registered with the Registrar of Societies Malaysia or recognised by the Majlis Agama Islam. Entitled to full rights, including voting and holding office.</p>
            <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>

        <a href="{{ route('register.friends.show') }}" class="category-card">
            <h3>
                <svg class="category-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Friends of MUKMIN (Ahli Bersekutu)
            </h3>
            <p>Organisations that are not registered with the Registrar of Societies Malaysia and/or not recognised by the Majlis Agama Islam.</p>
            <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
    </div>
</div>
@endsection
