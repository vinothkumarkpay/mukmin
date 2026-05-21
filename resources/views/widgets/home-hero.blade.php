@php
    $s = $widget->settings ?? [];
    $g1 = $s['hero_gradient_1'] ?? '#dcd4cb';
    $g2 = $s['hero_gradient_2'] ?? '#6f5f52';
    $g3 = $s['hero_gradient_3'] ?? '#555c52';
    $overlay = isset($s['hero_overlay']) ? (float) $s['hero_overlay'] : 0.46;
    $overlay = max(0, min(0.92, $overlay));
    $bgUrl = $widget->heroBackgroundPublicUrl();
    $defaults = \App\Models\Widget::defaultHeroSlides();
    $slideRows = $s['hero_slides'] ?? [];
    $slides = [];
    for ($hi = 0; $hi < \App\Models\Widget::HERO_SLIDE_SLOTS; $hi++) {
        $row = isset($slideRows[$hi]) && is_array($slideRows[$hi]) ? $slideRows[$hi] : [];
        $base = $defaults[$hi] ?? ['title' => '', 'description' => '', 'cta_label' => '', 'link_url' => '', 'image_url' => '', 'image_path' => null, 'new_tab' => false];
        $slides[] = array_merge($base, $row);
    }
    $slidesWithMedia = [];
    foreach ($slides as $sl) {
        if (\App\Models\Widget::heroSlideImageUrl($sl)) {
            $slidesWithMedia[] = $sl;
        }
    }
    $slideInterval = max(3, min(120, (int) ($s['hero_slide_interval'] ?? 6)));
    $slideAutoplay = array_key_exists('hero_slides_autoplay', $s) ? (bool) $s['hero_slides_autoplay'] : true;

    // Combine main landing page as Slide 0, and others as Slide 1..N
    $allSlides = [];
    $htmlContent = $widget->content;
    $headlineHtml = '';
    $subHtml = '';
    if (preg_match('/(<h1[^>]*>.*?<\/h1>)/is', $htmlContent, $matches)) {
        $headlineHtml = $matches[1];
    }
    if (preg_match('/(<div class="mukmin-hero__sub"[^>]*>.*?<\/div>)/is', $htmlContent, $matches)) {
        $subHtml = $matches[1];
    }

    $allSlides[] = [
        'is_html_content' => true,
        'html_headline' => $headlineHtml ?: $htmlContent,
        'html_sub' => $subHtml,
        'image_url' => $bgUrl ?: \App\Models\Widget::heroSidePanelDefaultImageUrl(),
        'cta_label' => __('Register Now'),
        'link_url' => \App\Support\FormUrls::register(),
        'cta_subtext' => __('Be part of a national effort to connect communities, unlock opportunities, and build a more inclusive future.'),
        'new_tab' => false,
    ];

    foreach ($slidesWithMedia as $slide) {
        $img = \App\Models\Widget::heroSlideImageUrl($slide);
        $href = \App\Support\FormUrls::resolveCtaUrl(
            trim((string) ($slide['cta_label'] ?? $slide['title'] ?? '')),
            (string) ($slide['link_url'] ?? '')
        );
        $allSlides[] = [
            'is_html_content' => false,
            'title' => trim((string) ($slide['title'] ?? '')) ?: __('Banner'),
            'description' => trim((string) ($slide['description'] ?? '')),
            'image_url' => $img,
            'cta_label' => trim((string) ($slide['cta_label'] ?? '')) ?: __('More details'),
            'link_url' => $href,
            'cta_subtext' => null,
            'new_tab' => !empty($slide['new_tab']),
        ];
    }
@endphp

<section
    class="mukmin-hero"
    style="--mukmin-hero-g1: {{ $g1 }}; --mukmin-hero-g2: {{ $g2 }}; --mukmin-hero-g3: {{ $g3 }}; --mukmin-hero-overlay: {{ $overlay }};"
    aria-labelledby="mukmin-hero-heading"
>
    <!-- Background Slides -->
    <div class="mukmin-hero__bg-slides" aria-hidden="true">
        @foreach ($allSlides as $si => $slide)
            <div 
                class="mukmin-hero__photo js-hero-bg-slide @if($si === 0) active @endif" 
                style="background-image: url('{{ e($slide['image_url']) }}');" 
                data-index="{{ $si }}" 
                role="presentation"
            ></div>
        @endforeach
    </div>

    <!-- Overlays -->
    <div class="mukmin-hero__gradient" aria-hidden="true"></div>
    <div class="mukmin-hero__vignette" aria-hidden="true"></div>

    <!-- Content Slider -->
    <div class="mukmin-hero__content-container">
        @foreach ($allSlides as $si => $slide)
            <div class="mukmin-hero__content-slide js-hero-content-slide @if($si === 0) active @endif" data-index="{{ $si }}">
                <div class="mukmin-hero__layout-grid">
                    <div class="mukmin-hero__layout-left">
                        @if ($slide['is_html_content'])
                            {!! $slide['html_headline'] !!}
                        @else
                            <h2 class="mukmin-hero__headline">
                                {{ $slide['title'] }}
                            </h2>
                        @endif
                    </div>
                    <div class="mukmin-hero__layout-right">
                        @if ($slide['is_html_content'])
                            {!! $slide['html_sub'] !!}
                        @else
                            @if ($slide['description'])
                                <div class="mukmin-hero__sub">
                                    <p class="mukmin-hero__subline">{{ $slide['description'] }}</p>
                                </div>
                            @endif
                        @endif

                        @if ($slide['link_url'] !== '')
                            <div class="mukmin-hero__cta">
                                <a href="{{ $slide['link_url'] }}" 
                                   @if ($slide['new_tab']) target="_blank" rel="noopener noreferrer" @endif
                                   class="btn hero-slider-cta-btn"
                                >
                                    {{ $slide['cta_label'] }}
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                                @if (!empty($slide['cta_subtext']))
                                    <p class="hero-slider-cta-subtext">
                                        {{ $slide['cta_subtext'] }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Navigation Arrows -->
    @if (count($allSlides) > 1)
        <button type="button" class="hero-nav-btn hero-nav-btn--left js-hero-prev" aria-label="{{ __('Previous slide') }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <button type="button" class="hero-nav-btn hero-nav-btn--right js-hero-next" aria-label="{{ __('Next slide') }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>

        <!-- Bullet Indicators -->
        <div class="hero-indicators">
            @foreach ($allSlides as $si => $slide)
                <button type="button" class="hero-indicator-dot js-hero-indicator @if($si === 0) active @endif" data-index="{{ $si }}" aria-label="{{ __('Go to slide :num', ['num' => $si + 1]) }}"></button>
            @endforeach
        </div>
    @endif

    <script>
    (function () {
        var hero = document.querySelector('.mukmin-hero');
        if (!hero) return;

        var bgSlides = hero.querySelectorAll('.js-hero-bg-slide');
        var contentSlides = hero.querySelectorAll('.js-hero-content-slide');
        var dots = hero.querySelectorAll('.js-hero-indicator');
        var prevBtn = hero.querySelector('.js-hero-prev');
        var nextBtn = hero.querySelector('.js-hero-next');

        var currentIndex = 0;
        var totalSlides = bgSlides.length;
        if (totalSlides <= 1) return;

        var slideInterval = {{ $slideInterval * 1000 }};
        var autoplay = {{ $slideAutoplay ? 'true' : 'false' }};
        var timer = null;

        function showSlide(index, direction) {
            if (index < 0) {
                index = totalSlides - 1;
            } else if (index >= totalSlides) {
                index = 0;
            }

            if (index === currentIndex) {
                return;
            }

            direction = (direction === 'prev') ? 'prev' : 'next';
            currentIndex = index;

            // Update background slides — apply direction class so CSS picks the right slide-in keyframe
            bgSlides.forEach(function (slide, i) {
                slide.classList.remove('slide-prev', 'slide-next');
                if (i === currentIndex) {
                    slide.classList.add(direction === 'prev' ? 'slide-prev' : 'slide-next');
                    // Force reflow so the slide-in animation restarts cleanly
                    void slide.offsetWidth;
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });

            // Update content slides
            contentSlides.forEach(function (slide, i) {
                if (i === currentIndex) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });

            // Update dots
            dots.forEach(function (dot, i) {
                if (i === currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            showSlide(currentIndex + 1, 'next');
        }

        function prevSlide() {
            showSlide(currentIndex - 1, 'prev');
        }

        function startTimer() {
            if (autoplay && !timer) {
                timer = setInterval(nextSlide, slideInterval);
            }
        }

        function stopTimer() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                prevSlide();
                stopTimer();
                startTimer();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                nextSlide();
                stopTimer();
                startTimer();
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var idx = parseInt(dot.getAttribute('data-index'), 10);
                var dir = (idx < currentIndex) ? 'prev' : 'next';
                showSlide(idx, dir);
                stopTimer();
                startTimer();
            });
        });

        // Display states initialized by CSS grid active class

        // Autoplay mouse events
        hero.addEventListener('mouseenter', stopTimer);
        hero.addEventListener('mouseleave', startTimer);
        hero.addEventListener('focusin', stopTimer);
        hero.addEventListener('focusout', startTimer);

        startTimer();
    })();
    </script>
</section>
