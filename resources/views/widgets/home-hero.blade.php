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
    $sidePanelUrl = $widget->slug === \App\Models\Widget::HOME_HERO_SLUG ? $widget->heroSidePanelPublicUrl() : null;
@endphp
<section
    class="mukmin-hero"
    style="--mukmin-hero-g1: {{ $g1 }}; --mukmin-hero-g2: {{ $g2 }}; --mukmin-hero-g3: {{ $g3 }}; --mukmin-hero-overlay: {{ $overlay }};"
    aria-labelledby="mukmin-hero-heading"
>
    @if ($bgUrl)
        <div class="mukmin-hero__photo" style="background-image: url({{ json_encode($bgUrl) }});" role="presentation"></div>
    @endif
    <div class="mukmin-hero__gradient" aria-hidden="true"></div>
    <div class="mukmin-hero__vignette" aria-hidden="true"></div>
    @if ($sidePanelUrl)
        <figure class="mukmin-hero__side-panel" aria-hidden="true">
            <span class="mukmin-hero__side-panel__img" style="background-image: url({{ json_encode($sidePanelUrl) }});"></span>
            <span class="mukmin-hero__side-panel__scrim" aria-hidden="true"></span>
        </figure>
    @endif
    <div class="mukmin-hero__inner">
        {!! $widget->content !!}
    </div>

    <div class="mukmin-hero__cta" style="position: relative; z-index: 3; text-align: center; margin: clamp(1.5rem, 4vw, 2.5rem) auto 0; max-width: 40rem;">
        <a href="#register" class="btn" style="
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: linear-gradient(135deg, #10b981, #0d9488);
            color: #fff; font-weight: 700; font-size: 1.1rem;
            padding: 0.85rem 2.25rem; border-radius: 999px; border: none;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
        " onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(16,185,129,0.45)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(16,185,129,0.35)'">
            {{ __('Join the Movement') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
        <p style="margin: 0.85rem auto 0; font-size: 0.92rem; color: rgba(255,255,255,0.7); max-width: 34rem; line-height: 1.55; font-weight: 400;">
            {{ __('Be part of a national effort to connect communities, unlock opportunities, and build a more inclusive future.') }}
        </p>
    </div>

    @if (count($slidesWithMedia) > 0)
        <div
            id="mukmin-hero-carousel-{{ $widget->getKey() ?? 'new' }}"
            class="mukmin-hero-carousel mukmin-hero__banners"
            role="region"
            aria-roledescription="{{ __('carousel') }}"
            aria-label="{{ __('Initiative banners') }}"
            data-interval="{{ $slideInterval }}"
            data-autoplay="{{ $slideAutoplay ? '1' : '0' }}"
            data-slide-count="{{ count($slidesWithMedia) }}"
        >
            @php($slideCount = count($slidesWithMedia))
            <div class="mukmin-hero-carousel__viewport">
                <div class="mukmin-hero-carousel__track">
                    @foreach (array_merge($slidesWithMedia, $slidesWithMedia) as $si => $slide)
                        @php($isClone = $si >= $slideCount)
                        @php($img = \App\Models\Widget::heroSlideImageUrl($slide))
                        @php($href = trim((string) ($slide['link_url'] ?? '')))
                        @php($title = trim((string) ($slide['title'] ?? '')) ?: __('Banner'))
                        @php($desc = trim((string) ($slide['description'] ?? '')))
                        @php($ctaCustom = trim((string) ($slide['cta_label'] ?? '')))
                        @php($cta = $ctaCustom !== '' ? $ctaCustom : __('More details'))
                        @php($newTab = !empty($slide['new_tab']))
                        @php($a11yLabel = $desc !== '' ? $title.' — '.$desc : $title)
                        <div class="mukmin-hero-carousel__card" @if ($isClone) aria-hidden="true" @endif>
                            @if ($href === '')
                                <div class="mukmin-hero-carousel__slide mukmin-hero-carousel__slide--nolink" tabindex="0" @if (! $isClone) aria-label="{{ e(\Illuminate\Support\Str::limit($a11yLabel, 240)) }}" @endif>
                                    <img class="mukmin-hero-carousel__img" src="{{ $img }}" alt="{{ $title }}" loading="lazy" decoding="async" width="1200" height="675">
                                    <header class="mukmin-hero-carousel__slide-head"><span class="mukmin-hero-carousel__slide-title">{{ $title }}</span></header>
                                    @if ($desc !== '')
                                        <div class="mukmin-hero-carousel__slide-hover">
                                            <p class="mukmin-hero-carousel__slide-desc">{{ $desc }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <a
                                    class="mukmin-hero-carousel__slide"
                                    href="{{ $href }}"
                                    @if ($newTab) target="_blank" rel="noopener noreferrer" @endif
                                    @if ($isClone) tabindex="-1" @else tabindex="0" aria-label="{{ e(\Illuminate\Support\Str::limit($a11yLabel, 240)) }}" @endif
                                >
                                    <img class="mukmin-hero-carousel__img" src="{{ $img }}" alt="" role="presentation" loading="lazy" decoding="async" width="1200" height="675">
                                    <header class="mukmin-hero-carousel__slide-head" aria-hidden="true"><span class="mukmin-hero-carousel__slide-title">{{ $title }}</span></header>
                                    <div class="mukmin-hero-carousel__slide-hover" aria-hidden="true">
                                        @if ($desc !== '')
                                            <p class="mukmin-hero-carousel__slide-desc">{{ $desc }}</p>
                                        @endif
                                        <span class="mukmin-hero-carousel__slide-cta"><span class="mukmin-hero-carousel__slide-cta-inner">{{ e($cta) }}</span></span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
        (function () {
            var root = document.getElementById('mukmin-hero-carousel-{{ $widget->getKey() ?? "new" }}');
            if (!root) return;
            var viewport = root.querySelector('.mukmin-hero-carousel__viewport');
            var track = root.querySelector('.mukmin-hero-carousel__track');
            var slides = track ? track.querySelectorAll('.mukmin-hero-carousel__card') : [];
            var realN = parseInt(root.getAttribute('data-slide-count'), 10) || 0;
            if (!viewport || !track || slides.length < 2 || realN < 1) return;

            var intervalSec = parseInt(root.getAttribute('data-interval'), 10);
            if (isNaN(intervalSec) || intervalSec < 3) intervalSec = 6;
            var autoplay = root.getAttribute('data-autoplay') === '1';
            var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var idx = 0;
            var timer = null;
            var loopLock = false;
            var peek = 3.25;
            var visibleGaps = 3;

            function updateResponsivePeek() {
                var w = window.innerWidth || document.documentElement.clientWidth || 0;
                if (w <= 420) {
                    peek = 1.08;
                } else if (w <= 520) {
                    peek = 1.12;
                } else if (w <= 640) {
                    peek = 1.22;
                } else if (w <= 768) {
                    peek = 1.45;
                } else if (w <= 900) {
                    peek = 2.05;
                } else if (w <= 1100) {
                    peek = 2.55;
                } else {
                    peek = 3.25;
                }
                visibleGaps = Math.max(0, Math.ceil(peek) - 1);
            }

            function gapPx() {
                var st = window.getComputedStyle(track);
                var n = parseFloat(st.columnGap || st.gap || '0', 10);
                return isNaN(n) ? 0 : n;
            }

            function syncSlideMetrics() {
                updateResponsivePeek();
                var vw = viewport.clientWidth;
                var g = gapPx();
                var slideW = Math.max(0, (vw - visibleGaps * g) / peek);
                viewport.style.setProperty('--hero-slide-w', slideW + 'px');
                viewport.style.setProperty('--hero-gap', g + 'px');
                return { slideW: slideW, g: g };
            }

            function stepPx() {
                var m = syncSlideMetrics();
                return m.slideW + m.g;
            }

            function setTransformPx(x, disableTransition) {
                if (disableTransition) {
                    track.classList.add('mukmin-hero-carousel__track--no-trans');
                } else {
                    track.classList.remove('mukmin-hero-carousel__track--no-trans');
                }
                track.style.transform = 'translate3d(' + (-x) + 'px,0,0)';
            }

            function applyTransform(disableTransition) {
                setTransformPx(idx * stepPx(), disableTransition);
            }

            function jumpToStart() {
                track.classList.add('mukmin-hero-carousel__track--no-trans');
                idx = 0;
                track.style.transform = 'translate3d(0,0,0)';
                void track.offsetWidth;
                track.classList.remove('mukmin-hero-carousel__track--no-trans');
            }

            function finishLoop() {
                jumpToStart();
                loopLock = false;
            }

            function advance() {
                if (loopLock) return;
                if (idx === realN - 1) {
                    loopLock = true;
                    idx += 1;
                    applyTransform(false);
                    var done = false;
                    function cleanup() {
                        if (done) return;
                        done = true;
                        track.removeEventListener('transitionend', onLoopEnd);
                        clearTimeout(safetyTimer);
                        finishLoop();
                    }
                    function onLoopEnd(e) {
                        if (e.target !== track) return;
                        if (e.propertyName && e.propertyName !== 'transform') return;
                        cleanup();
                    }
                    var safetyTimer = setTimeout(cleanup, 900);
                    track.addEventListener('transitionend', onLoopEnd);
                } else {
                    idx += 1;
                    applyTransform(false);
                }
            }

            function start() {
                if (timer || !autoplay || reduceMotion) return;
                timer = window.setInterval(advance, intervalSec * 1000);
            }

            function stop() {
                if (timer) {
                    window.clearInterval(timer);
                    timer = null;
                }
            }

            var ro = typeof ResizeObserver !== 'undefined'
                ? new ResizeObserver(function () {
                    syncSlideMetrics();
                    applyTransform(true);
                })
                : null;
            if (ro) ro.observe(viewport);

            var resizeT;
            window.addEventListener('resize', function () {
                clearTimeout(resizeT);
                resizeT = setTimeout(function () {
                    syncSlideMetrics();
                    applyTransform(true);
                }, 50);
            });

            root.addEventListener('mouseenter', stop);
            root.addEventListener('mouseleave', start);
            root.addEventListener('focusin', stop);
            root.addEventListener('focusout', function (e) {
                if (!root.contains(e.relatedTarget)) start();
            });

            syncSlideMetrics();
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    syncSlideMetrics();
                    applyTransform(true);
                });
            });
            start();
        })();
        </script>
    @endif

    @if ($sidePanelUrl)
        <script>
        (function () {
            var roots = document.querySelectorAll('section.mukmin-hero');
            roots.forEach(function (root) {
                var origin = root.querySelector('.mukmin-hero__side-panel-origin');
                if (!origin) return;
                function sync() {
                    var hr = root.getBoundingClientRect();
                    var r = origin.getBoundingClientRect();
                    var gap = 10;
                    var minPanelW = 200;
                    var rightGutter = Math.max(10, Math.min(28, hr.width * 0.018 + 8));
                    var candidate = r.right - hr.left + gap;
                    var maxLeft = Math.max(minPanelW + 24, hr.width - rightGutter - minPanelW);
                    var leftPx = Math.max(8, Math.min(maxLeft, candidate));
                    root.style.setProperty('--mukmin-side-panel-left', leftPx + 'px');
                }
                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(sync);
                } else {
                    sync();
                }
                window.addEventListener('resize', sync);
                if (typeof ResizeObserver !== 'undefined') {
                    var ro = new ResizeObserver(sync);
                    ro.observe(root);
                    var inner = root.querySelector('.mukmin-hero__inner');
                    if (inner) ro.observe(inner);
                }
            });
        })();
        </script>
    @endif
</section>
