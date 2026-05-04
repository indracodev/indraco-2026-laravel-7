<?php $__env->startSection('title', 'Produk Kami – INDRACO'); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <h1 class="visually-hidden">halaman produk</h1>

    <?php if($banners->count() > 0): ?>
    <section>
        <div id="carouselBanner" class="carousel carousel-fade slide" data-bs-ride="carousel" data-bs-theme="light">
            <div class="carousel-indicators">
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="<?php echo e($index); ?>" class="<?php echo e($index === 0 ? 'active' : ''); ?>" aria-current="<?php echo e($index === 0 ? 'true' : 'false'); ?>" aria-label="Slide <?php echo e($index + 1); ?>"></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="carousel-inner">
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                    <div class="carousel-caption position-static d-lg-flex align-items-lg-center column-gap-lg-5 justify-content-lg-around">
                        <img src="<?php echo e(asset($banner->image_path)); ?>" alt="" loading="lazy" aria-hidden="true" class="carousel-img w-100 h-auto order-lg-2">
                        <div class="caption-text text-start order-lg-1">
                            <h2 class="fw-bold fs-1 text-capitalize"><?php echo app()->getLocale() == 'en' ? $banner->title_en : $banner->title_id; ?></h2>
                            <hr>
                            <p class="fs-4 fw-bold mb-4"><?php echo app()->getLocale() == 'en' ? $banner->subtitle_en : $banner->subtitle_id; ?></p>
                            <a href="<?php echo e($banner->link); ?>" target="_blank" class="btn btn-outline-invert text-capitalize"><?php echo e(app()->getLocale() == 'en' ? $banner->button_text_en : $banner->button_text_id); ?></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <?php endif; ?>

    <section class="py-5 text-center" aria-labelledby="brands">
        <div class="container py-lg-5">
            <h2 id="brands" class="fs-3 fw-bold text-capitalize mb-5" data-i18n="product_brands_title"><?php echo e(__('product_brands_title')); ?></h2>

            <div class="daftar-kategori-produk text-start text-capitalize row row-cols-1 row-gap-5">
                <!-- Coffee Category -->
                <?php if($brands['coffee']->count() > 0): ?>
                <div class="col">
                    <h3 class="fs-4 fw-bold mb-4" data-i18n="coffee"><?php echo e(__('coffee')); ?></h3>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 g-sm-4 g-md-5">
                        <?php $__currentLoopData = $brands['coffee']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <a href="<?php echo e(route($brand->slug == 'supresso' ? 'product.supresso' : 'product.indraco', $brand->slug)); ?>" class="text-reset text-decoration-none opacity-100">
                                <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                    <img src="<?php echo e(asset($brand->logo_path)); ?>" class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle" alt="<?php echo e($brand->name); ?>" loading="lazy">
                                </article>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Ginger, Coconut Milk, Cocoa Categories -->
                <div class="col">
                    <h3 class="fs-4 fw-bold mb-4">
                        <span data-i18n="ginger"><?php echo e(__('ginger')); ?></span> | 
                        <span data-i18n="choconutmilk"><?php echo e(__('choconutmilk')); ?></span> | 
                        <span data-i18n="chocolate"><?php echo e(__('chocolate')); ?></span>
                    </h3>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 g-sm-4 g-md-5">
                        <?php
                            $others = $brands['ginger']->concat($brands['coconut_milk'])->concat($brands['cocoa']);
                        ?>
                        <?php $__currentLoopData = $others; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <a href="<?php echo e(route('product.indraco', $brand->slug)); ?>" class="text-reset text-decoration-none opacity-100">
                                <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                    <img src="<?php echo e(asset($brand->logo_path)); ?>" class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle" alt="<?php echo e($brand->name); ?>" loading="lazy">
                                </article>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 text-bg-primary text-center" aria-labelledby="marketplace">
        <div class="container py-lg-5">
            <h2 id="marketplace" class="fs-3 fw-bold text-capitalize mb-5" data-i18n="product_order_title"><?php echo e(__('product_order_title')); ?></h2>
            <div class="mb-5">
                <p class="small">Website INDRACO Store</p>
                <a href="https://indracostore.com/" target="_blank" class="text-reset text-decoration-none">
                    <img src="<?php echo e(asset('images/logo-indracostore-invert.png')); ?>" alt="Logo INDRACO Store" loading="lazy" class="w-100 h-auto" style="max-width: 18rem;">
                </a>
            </div>
            <div>
                <p class="small" data-i18n="product_available_at"><?php echo e(__('product_available_at')); ?> :</p>
                <nav aria-label="online store" class="d-flex flex-wrap justify-content-center align-items-center" style="gap: 3rem 5rem;">
                    <a href="https://www.tokopedia.com/indracoofficial" target="_blank" class="text-reset text-decoration-none">
                        <img src="<?php echo e(asset('images/logo-tokopedia.png')); ?>" alt="Logo Tokopedia" loading="lazy" class="w-100 h-auto" style="max-width: 10rem;">
                    </a>
                    <a href="https://shopee.co.id/indracoofficial" target="_blank" class="text-reset text-decoration-none">
                        <img src="<?php echo e(asset('images/logo-shopee.png')); ?>" alt="Logo Shopee" loading="lazy" class="w-100 h-auto" style="max-width: 3rem;">
                    </a>
                    <a href="https://www.blibli.com/merchant/indraco/INT-60044" target="_blank" class="text-reset text-decoration-none">
                        <img src="<?php echo e(asset('images/logo-blibli.png')); ?>" alt="Logo Blibli" loading="lazy" class="w-100 h-auto" style="max-width: 8rem;">
                    </a>
                    <a href="https://www.lazada.co.id/shop/indraco/" target="_blank" class="text-reset text-decoration-none">
                        <img src="<?php echo e(asset('images/logo-lazada.png')); ?>" alt="Logo Lazada" loading="lazy" class="w-100 h-auto" style="max-width: 4rem;">
                    </a>
                    <a href="https://www.tiktok.com/@indracostore" target="_blank" class="text-reset text-decoration-none">
                        <img src="<?php echo e(asset('images/logo-tiktokshop.png')); ?>" alt="Logo TikTok Shop" loading="lazy" class="w-100 h-auto" style="max-width: 10rem;">
                    </a>
                </nav>
            </div>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    #carouselBanner, #carouselBanner .carousel-item { background-color: #1a1a1a; }
    #carouselBanner .carousel-caption { padding: 0 15% 15% 15%; }
    #carouselBanner .carousel-img { aspect-ratio: 1/1; object-fit: contain; }
    @media (min-width: 992px) {
        #carouselBanner .carousel-caption { padding: 2% 15%; }
        #carouselBanner .carousel-caption > * { max-width: 26rem; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/products/index.blade.php ENDPATH**/ ?>