<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" @hasSection('html_theme')
data-bs-theme="@yield('html_theme')"
@endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        // ── Per-page SEO resolution ──────────────────────────────────────────
        $routePageMap = [
            'home'          => 'home',
            'about'         => 'about',
            'products'      => 'products',
            'products.show' => 'products',
            'news'          => 'news',
            'news.show'     => 'news',
            'businesses'    => 'businesses',
            'stores'        => 'stores',
            'career'        => 'career',
            'contact'       => 'contact',
            'equipment'     => 'equipment',
            'foodservice'   => 'foodservice',
            'download'      => 'download',
            'privacy'       => 'privacy',
            'terms'         => 'terms',
        ];
        $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
        $currentPage  = $routePageMap[$currentRoute] ?? null;

        // Determine SEO Prefix and Key
        // Priority: Dynamic Object (Product/News) -> Static Page -> Global Default
        $seoPrefix = 'page';
        $seoKey    = $currentPage;

        if ($currentRoute === 'products.show' && isset($brand)) {
            $seoPrefix = 'product'; // In this project brands are often treated as product listings
            $seoKey    = "product_{$brand->id}";
        } elseif ($currentRoute === 'news.show' && isset($item)) {
            $seoPrefix = 'news';
            $seoKey    = "news_{$item->id}";
        }

        $pageSeoTitle     = $seoKey ? ($settings["seo_{$seoPrefix}_{$seoKey}_title"]       ?? null) : null;
        $pageSeoDesc      = $seoKey ? ($settings["seo_{$seoPrefix}_{$seoKey}_description"]  ?? null) : null;
        $pageSeoKeywords  = $seoKey ? ($settings["seo_{$seoPrefix}_{$seoKey}_keywords"]     ?? null) : null;
        $pageSeoOgTitle   = $seoKey ? ($settings["seo_{$seoPrefix}_{$seoKey}_og_title"]     ?? null) : null;
        $pageSeoOgDesc    = $seoKey ? ($settings["seo_{$seoPrefix}_{$seoKey}_og_description"] ?? null) : null;
        $pageSeoOgImage   = $seoKey ? ($settings["seo_{$seoPrefix}_{$seoKey}_og_image"]     ?? null) : null;
        $pageSeoCanonical = $seoKey ? ($settings["seo_{$seoPrefix}_{$seoKey}_canonical"]    ?? null) : null;

        // Fallback chain: specific → global setting → hardcoded default
        $defaultTitle    = $pageSeoTitle   ?? $settings['seo_title']       ?? 'Perusahaan FMCG Terkemuka di Indonesia Sejak 1971 – INDRACO';
        $defaultDesc     = $pageSeoDesc    ?? $settings['seo_description'] ?? 'INDRACO adalah perusahaan FMCG terkemuka di Indonesia sejak 1971, menghadirkan berbagai produk berkualitas.';
        $defaultKeywords = $pageSeoKeywords ?? $settings['seo_keywords']   ?? 'indraco, fmcg indonesia, kopi indonesia';
        $defaultOgTitle  = $pageSeoOgTitle ?? $pageSeoTitle ?? $settings['seo_title'] ?? 'INDRACO – Indonesia Leading FMCG Company Since 1971';
        $defaultOgDesc   = $pageSeoOgDesc  ?? $pageSeoDesc  ?? $settings['seo_description'] ?? 'Perusahaan kopi dan produk konsumen Indonesia sejak 1971.';
        $defaultOgImage  = $pageSeoOgImage ? (str_starts_with($pageSeoOgImage, 'http') ? $pageSeoOgImage : asset($pageSeoOgImage)) : asset($settings['seo_og_image'] ?? 'images/og-image.jpg');
    @endphp

    {{-- SEO Settings --}}
    <meta name="description" content="@yield('meta_description', $defaultDesc)">
    <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">
    @if (!empty($settings['google_site_verification']))
        <meta name="google-site-verification" content="{{ $settings['google_site_verification'] }}" />
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', $defaultOgTitle)">
    <meta property="og:description" content="@yield('og_description', $defaultOgDesc)">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">

    {{-- Canonical URL --}}
    @if($pageSeoCanonical || !\Illuminate\Support\Str::contains($currentRoute ?? '', '.show'))
    <link rel="canonical" href="@yield('canonical', $pageSeoCanonical ?? url()->current())">
    @else
    @hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif
    @endif

    {{-- Google Analytics --}}
    @if (!empty($settings['google_analytics_id']))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZKW7TJ40DB"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-ZKW7TJ40DB');
        </script>
    @endif

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-E71YZSYCS8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-E71YZSYCS8');
    </script>



    <link rel="shortcut icon" href="{{ asset('images/icon-indraco.ico') }}" type="image/x-icon">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap.min.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    @yield('styles')

    <title>@yield('title', $defaultTitle)</title>
</head>

<body>
    <a href="#konten" class="visually-hidden-focusable">{{ __('skip_to_content') }}</a>

    @include('partials.header')
    @include('partials.menu_mobile')

    <main id="konten">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.modal_search')

    <!-- Vendor JS -->
    <script src="{{ asset('assets/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/ScrollTrigger.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/frontend.js') }}"></script>
    <script src="{{ asset('js/traffic-tracker.js') }}"></script>

    @yield('scripts')

    {{-- Geolocation Detection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Geolocation & Localization Logger
            function logGeo(type, message) {
                @if (config('app.debug'))
                    const colors = {
                        'info': '#0d6efd',
                        'success': '#198754',
                        'warn': '#ffc107'
                    };
                    console.log(`%c[Geolocation] %c${message}`, `color: ${colors[type]}; font-weight: bold;`,
                        'color: inherit;');
                @endif
            }

            async function detectLocation() {
                const cached = localStorage.getItem('lang_detected');
                const cachedLocation = localStorage.getItem('lang_location');
                const currentLang = "{{ app()->getLocale() }}";

                // If we have cached location info, log it immediately
                if (cachedLocation) {
                    logGeo('info', `Visitor from: ${cachedLocation} (Cached)`);
                    if (cached) return;
                }

                try {
                    const response = await fetch('https://ipapi.co/json/');
                    const data = await response.json();
                    const country = data.country_code;
                    const locationName = `${data.country_name} (${country})`;
                    let targetLang = country === 'ID' ? 'id' : 'en';

                    localStorage.setItem('lang_location', locationName);
                    logGeo('success', `Visitor from: ${locationName} -> Target: ${targetLang.toUpperCase()}`);

                    if (currentLang !== targetLang) {
                        localStorage.setItem('lang_detected', 'true');
                        window.location.href = `/lang/${targetLang}`;
                    } else {
                        localStorage.setItem('lang_detected', 'true');
                    }
                } catch (error) {
                    logGeo('warn', 'Detection service unavailable, using defaults.');
                    localStorage.setItem('lang_detected', 'true');
                }
            }
            detectLocation();
        });
    </script>

    @if (request()->has('preview'))
        <style>
            [data-i18n] {
                position: relative !important;
                outline: 1px dashed rgba(13, 110, 253, 0.3) !important;
            }

            [data-i18n]:hover {
                outline: 2px solid #0d6efd !important;
                outline-offset: 2px !important;
                z-index: 9999 !important;
            }

            [data-i18n]::after {
                content: attr(data-i18n);
                position: absolute;
                top: -18px;
                left: 0;
                background: #0d6efd;
                color: white;
                font-size: 10px;
                padding: 1px 4px;
                border-radius: 3px;
                white-space: nowrap;
                pointer-events: none;
                z-index: 10000;
                font-family: monospace;
                line-height: 1.2;
                opacity: 0.8;
                display: none;
                /* Hidden by default, toggled via parent class */
            }

            body.show-keys [data-i18n]::after {
                display: block;
            }

            body.show-keys [data-i18n] {
                outline: 1px solid rgba(13, 110, 253, 0.5) !important;
            }
        </style>
        <script>
            window.addEventListener('message', function(event) {
                if (event.data.type === 'translationUpdate') {
                    const key = event.data.key;
                    const value = event.data.value;
                    const elements = document.querySelectorAll(`[data-i18n="${key}"]`);
                    elements.forEach(el => {
                        el.innerHTML = value;
                    });
                } else if (event.data.type === 'toggleKeys') {
                    if (event.data.show) {
                        document.body.classList.add('show-keys');
                    } else {
                        document.body.classList.remove('show-keys');
                    }
                }
            });
        </script>
    @endif
</body>

</html>
