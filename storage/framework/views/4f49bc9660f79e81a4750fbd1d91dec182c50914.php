<?php $__env->startSection('title', __('nav_stores') . ' – INDRACO'); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <h1 class="visually-hidden"><?php echo e(__('nav_stores')); ?></h1>
    <div class="container">
        <!-- Section: Official Store -->
        <section class="py-5">
            <div class="py-lg-5">
                <h2 class="fs-1 fw-thin text-capitalize mb-4"><b class="fw-bold" data-i18n="nav_official"><?php echo e(__('nav_official')); ?></b></h2>
                <ul class="list-unstyled mb-0 d-grid gap-3 gap-lg-4" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
                    <li>
                        <a href="https://supresso.com/" target="_blank" class="text-reset text-decoration-none">
                            <article class="card p-5 bg-light-subtle rounded-4 border-0">
                                <div class="ratio ratio-16x9">
                                    <img src="<?php echo e(asset('images/logo-supresso-typograph-flat.png')); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-contain top-50 start-50 translate-middle">
                                </div>
                            </article>
                        </a>
                    </li>
                    <li>
                        <a href="https://indracostore.com/" target="_blank" class="text-reset text-decoration-none">
                            <article class="card p-5 bg-light-subtle rounded-4 border-0">
                                <div class="ratio ratio-16x9">
                                    <img src="<?php echo e(asset('images/logo-indracostore-flat.png')); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-contain top-50 start-50 translate-middle">
                                </div>
                            </article>
                        </a>
                    </li>
                </ul>
            </div>
        </section>

        <hr class="m-0">

        <!-- Section: Marketplace -->
        <section class="py-5">
            <div class="py-lg-5">
                <h2 class="fs-1 fw-thin text-capitalize mb-4"><b class="fw-bold"><?php echo e(__('nav_marketplace')); ?></b></h2>
                <ul class="list-unstyled mb-0 d-grid gap-3 gap-lg-4" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
                    <?php
                        $marketplaces = [
                            ['name' => 'Tokopedia', 'url' => 'https://www.tokopedia.com/indracoofficial', 'img' => 'logo-tokopedia-flat.png', 'width' => '75%'],
                            ['name' => 'Shopee', 'url' => 'https://shopee.co.id/indracoofficial', 'img' => 'logo-shopee-flat.png', 'width' => '75%'],
                            ['name' => 'Lazada', 'url' => 'https://www.lazada.co.id/shop/indraco/', 'img' => 'logo-lazada-flat.png', 'width' => '75%'],
                            ['name' => 'Blibli', 'url' => 'https://www.blibli.com/merchant/indraco/INT-60044?pickupPointCode=PP-3056342&fbbActivated=false', 'img' => 'logo-blibli-flat.png', 'width' => '60%'],
                            ['name' => 'TikTok Shop', 'url' => 'https://www.tiktok.com/@indracostore', 'img' => 'logo-tiktok-shop-flat.png', 'width' => '75%'],
                        ];
                    ?>
                    <?php $__currentLoopData = $marketplaces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $market): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e($market['url']); ?>" target="_blank" class="text-reset text-decoration-none">
                            <article class="card p-5 bg-light-subtle rounded-4 border-0">
                                <div class="ratio ratio-16x9">
                                    <img src="<?php echo e(asset('images/' . $market['img'])); ?>" alt="<?php echo e($market['name']); ?>" loading="lazy" class="object-fit-contain top-50 start-50 translate-middle" style="width: <?php echo e($market['width']); ?>;">
                                </div>
                            </article>
                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </section>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/pages/stores.blade.php ENDPATH**/ ?>