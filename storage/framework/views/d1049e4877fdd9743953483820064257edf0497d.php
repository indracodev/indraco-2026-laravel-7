<?php $__env->startSection('html_theme', 'dark'); ?>
<?php $__env->startSection('title', $brand->name . ' Collection – INDRACO'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .menu-variant-item .figure { width: 6.5rem; }
    .menu-variant-item .figure-img { overflow: visible !important; position: relative; border: 2px solid transparent; transition: all .3s ease; }
    .menu-variant-item .figure-img img { 
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        object-fit: contain;
        transform: translate(-50%, -50%) scale(1.1);
        transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        filter: drop-shadow(0 5px 10px rgba(0,0,0,0.2));
    }
    .menu-variant-item > a:hover img, 
    .menu-variant-item > a.active img { 
        transform: translate(-50%, -60%) scale(1.6); 
        filter: drop-shadow(0 15px 25px rgba(0,0,0,0.5));
    }
    .menu-variant-item figcaption { transition: all .3s ease; opacity: .8; }
    .menu-variant-item > a:hover figcaption,
    .menu-variant-item > a.active figcaption { opacity: 1; font-weight: bold; }

    @media (min-width: 992px) {
        .map-img { max-width: 60vw; margin-top: 2vw; opacity: .7; pointer-events: none; }
        .variant-header { padding-top: 15vw; padding-bottom: 6rem; }
        .variant-body { margin-top: -6rem; }
        .header-tab-pane { z-index: 1020; }
    }
    .variant-item { padding-top: 100px; }
    .variant-item:first-child { padding-top: 0; }
    .card-img img { transition: transform 0.5s ease; }
    .card:hover .card-img img { transform: scale(1.1); }

    .product-acidity, .product-viscosity {
        font-size: 0.875rem;
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    .rating-stars-container {
        display: inline-flex;
        gap: 4px;
        align-items: center;
        vertical-align: middle;
    }
    .dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1.5px solid currentColor;
    }
    .dot.full { background-color: currentColor; }
    .dot.half { background: linear-gradient(to right, currentColor 50%, transparent 50%); }
    .dot.empty { background-color: transparent; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
if (!function_exists('renderRatingStars')) {
    function renderRatingStars($score) {
        $score = (float)$score;
        $full  = floor($score);
        $half  = ($score - $full) >= 0.5 ? 1 : 0;
        $empty = 5 - $full - $half;
        $out   = '<div class="rating-stars-container">';
        for ($i = 0; $i < $full;  $i++) $out .= '<span class="dot full"></span>';
        if ($half)                        $out .= '<span class="dot half"></span>';
        for ($i = 0; $i < $empty; $i++) $out .= '<span class="dot empty"></span>';
        return $out . '</div>';
    }
}
?>
<main id="konten" data-bs-spy="scroll" data-bs-target="#nav-variant-container" data-bs-offset="200">
    <h1 class="visually-hidden" data-i18n="brand_h1"><?php echo e(__('brand_h1')); ?></h1>

    
    <nav aria-label="breadcrumb" class="container py-3 mb-5 small">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('products')); ?>" class="text-reset opacity-75-hover text-decoration-none" data-i18n="brand_breadcrumb"><?php echo e(__('brand_breadcrumb')); ?></a>
            </li>
            <li class="breadcrumb-item active text-capitalize" aria-current="page"><?php echo e(str_replace(' Collection', '', $brand->name)); ?></li>
        </ol>
    </nav>

    
    <div class="text-center container mb-5" style="max-width: 860px;">
        <img src="<?php echo e(asset($brand->logo_path)); ?>" alt="Logo <?php echo e($brand->name); ?>" loading="lazy" class="img-fluid mb-3" style="max-width: 9rem;">
        <p class="lead"><?php echo e($brand->desc); ?></p>
    </div>

    
    <div class="overflow-auto">
        <div class="d-flex">
            <div class="menu-category nav nav-tabs flex-nowrap text-nowrap mx-auto border-0">
                <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="?col=<?php echo e($col->id); ?>"
                   class="menu-category-item nav-link text-capitalize text-reset opacity-75-hover border-0 <?php echo e($col->id == $collectionId ? 'active' : ''); ?>">
                    <?php echo e($col->collection_name); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="category">
        <?php if($variants->isNotEmpty()): ?>
            
            <header id="nav-variant-container" class="header-tab-pane sticky-top bg-light-subtle shadow-sm" style="overflow-x: auto; overflow-y: visible;">
                <ol class="menu-variant list-unstyled d-flex gap-4 px-4 py-5 mb-0 justify-content-lg-center">
                    <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Mirror legacy PHP: icon_path first, then fallback to first active product image
                        if (!empty($v->icon_path)) {
                            $v_icon = $v->icon_path;
                        } else {
                            $firstProd = $v->products->first();
                            $v_icon = $firstProd && !empty($firstProd->gambar_utama)
                                ? $firstProd->gambar_utama
                                : 'images/no-image.png';
                        }
                    ?>
                    <li class="menu-variant-item">
                        <a href="#variant-<?php echo e($v->slug); ?>" class="text-reset text-decoration-none">
                            <figure class="figure mb-0 text-center">
                                <div class="figure-img ratio ratio-1x1 rounded-circle mx-auto" style="background-color: <?php echo e($v->bg_color); ?>;">
                                    <img src="<?php echo e(asset($v_icon)); ?>" alt="" class="object-fit-contain">
                                </div>
                                <figcaption class="figure-caption text-reset text-capitalize mt-2">
                                    <small><?php echo e($v->name); ?></small>
                                </figcaption>
                            </figure>
                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>
            </header>

            
            <div class="variant-list">
                <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <section class="variant-item" id="variant-<?php echo e($v->slug); ?>">
                    
                    <header class="variant-header overflow-hidden position-relative"
                            style="background-color: <?php echo e($v->bg_color); ?>; color: <?php echo e($v->text_color); ?>;">
                        <div class="container">
                            <div class="row align-items-lg-end">
                                
                                <div class="col col-12 z-1">
                                    <?php if(!empty($v->map_image)): ?>
                                    <img src="<?php echo e(asset($v->map_image)); ?>"
                                         alt=""
                                         class="map-img w-100 h-auto position-lg-absolute top-0 end-0"
                                         style="opacity: <?php echo e($v->map_opacity ?? 0.70); ?>;">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col col-12 col-lg-5 z-2">
                                    <h3 class="product-title display-5 fw-bold text-capitalize"><?php echo e($v->name); ?></h3>
                                    <p class="product-description"><?php echo nl2br(e($v->desc)); ?></p>
                                    <?php if(!empty($v->taste_translated)): ?>
                                    <p class="product-taste text-uppercase fw-bold"><?php echo e($v->taste_translated); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col col-12 col-lg-auto ms-lg-auto z-2">
                                    <?php if(!$isKraton): ?>
                                    <p class="product-acidity text-capitalize">acidity <br> <?php echo renderRatingStars($v->acidity); ?></p>
                                    <p class="product-viscosity text-capitalize">body <br> <?php echo renderRatingStars($v->body); ?></p>
                                    <?php endif; ?>
                                    <?php if(!empty($v->roast_translated)): ?>
                                    <p class="product-process text-capitalize small opacity-75"><?php echo e($v->roast_translated); ?></p>
                                    <?php endif; ?>
                                    <?php if(!empty($v->ingredient_translated)): ?>
                                    <p class="product-ingredient text-capitalize small opacity-75"><?php echo e($v->ingredient_translated); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </header>

                    
                    <div class="variant-body container">
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 py-5 g-4">
                            <?php $__empty_1 = true; $__currentLoopData = $v->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $img_p  = !empty($prod->gambar_utama) ? $prod->gambar_utama : 'images/no-image.png';
                                $link_p = !empty($prod->link_web) ? $prod->link_web : '#';
                            ?>
                            <div class="col">
                                <a href="<?php echo e($link_p); ?>" <?php echo e($link_p !== '#' ? 'target="_blank"' : ''); ?> class="text-reset text-decoration-none">
                                    <article class="card border-0 bg-light-subtle h-100">
                                        <div class="card-header p-0 bg-transparent border-0 rounded-0">
                                            <div class="card-img ratio ratio-1x1">
                                                <img src="<?php echo e(asset($img_p)); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-contain">
                                            </div>
                                        </div>
                                        <div class="card-body text-center d-flex flex-column p-3">
                                            <h4 class="card-title fs-6 fw-bold text-capitalize mb-0"><?php echo e($prod->name); ?></h4>
                                            <?php if(!empty($prod->packing)): ?>
                                            <p class="card-text small mt-1"><?php echo e($prod->packing); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                </a>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12 text-center py-5 text-muted" data-i18n="brand_empty_desc"><?php echo e(__('brand_empty_desc')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="container text-center py-5 my-5">
                <p class="text-muted lead" data-i18n="brand_empty_desc"><?php echo e(__('brand_empty_desc')); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\#indraco\indraco-2026\indraco-2026-laravel-7\resources\views/products/brand_supresso.blade.php ENDPATH**/ ?>