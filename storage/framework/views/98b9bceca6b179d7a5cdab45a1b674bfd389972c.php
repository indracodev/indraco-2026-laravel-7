<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="ltr" <?php if (! empty(trim($__env->yieldContent('html_theme')))): ?> data-bs-theme="<?php echo $__env->yieldContent('html_theme'); ?>" <?php endif; ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'INDRACO adalah perusahaan FMCG terkemuka di Indonesia sejak 1971, menghadirkan berbagai produk berkualitas seperti kopi, teh, jahe, dan cokelat.'); ?>">
    
    <meta property="og:title" content="<?php echo $__env->yieldContent('og_title', 'INDRACO – Indonesia Leading FMCG Company Since 1971'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og_description', 'Perusahaan kopi dan produk konsumen Indonesia sejak 1971.'); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', asset('images/og-image.jpg')); ?>">
    <meta property="og:type" content="website">
    
    <link rel="shortcut icon" href="<?php echo e(asset('images/icon-indraco.ico')); ?>" type="image/x-icon">
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap.min.css')); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/theme.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/frontend.css')); ?>">
    
    <?php echo $__env->yieldContent('styles'); ?>
    
    <title><?php echo $__env->yieldContent('title', 'Perusahaan FMCG Terkemuka di Indonesia Sejak 1971 – INDRACO'); ?></title>
</head>

<body>
    <a href="#konten" class="visually-hidden-focusable"><?php echo e(__('skip_to_content')); ?></a>
    
    <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.menu_mobile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main id="konten">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.modal_search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Vendor JS -->
    <script src="<?php echo e(asset('assets/vendor/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/gsap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/ScrollTrigger.min.js')); ?>"></script>

    <!-- Custom JS -->
    <script src="<?php echo e(asset('js/theme.js')); ?>"></script>
    <script src="<?php echo e(asset('js/frontend.js')); ?>"></script>
    
    <?php echo $__env->yieldContent('scripts'); ?>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Geolocation & Localization Logger
        function logGeo(type, message) {
            <?php if(config('app.debug')): ?>
                const colors = {
                    'info': '#0d6efd',
                    'success': '#198754',
                    'warn': '#ffc107'
                };
                console.log(`%c[Geolocation] %c${message}`, `color: ${colors[type]}; font-weight: bold;`, 'color: inherit;');
            <?php endif; ?>
        }

        async function detectLocation() {
            const cached = localStorage.getItem('lang_detected');
            const cachedLocation = localStorage.getItem('lang_location');
            const currentLang = "<?php echo e(app()->getLocale()); ?>";

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

    <?php if(request()->has('preview')): ?>
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
            display: none; /* Hidden by default, toggled via parent class */
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
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\#indraco\indraco-2026\indraco-2026-laravel-7\resources\views/layouts/app.blade.php ENDPATH**/ ?>