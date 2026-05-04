<?php $__env->startSection('title', __('download_title_page')); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <h1 class="visually-hidden"><?php echo e(__('download_h1')); ?></h1>
    <div class="py-5">
        <div class="container py-lg-5">
            <ul class="list-unstyled row row-cols-1 row-cols-sm-2 g-3 g-sm-4 g-lg-5">
                <li class="col">
                    <article class="d-flex align-items-center">
                        <div class="w-50">
                            <div class="ratio ratio-1x1 w-100 bg-secondary-subtle">
                                <img src="<?php echo e(asset('images/brochure-cp.jpg')); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-cover">
                            </div>
                        </div>
                        <div class="w-50 p-4">
                            <h2 class="fs-4 text-capitalize lh-1">INDRACO</h2>
                            <p class="text-capitalize"><?php echo e(__('download_company_profile')); ?></p>
                            <a href="https://indraco.com/brosur/Company Profile Indraco.pdf" target="_blank" class="btn btn-outline-invert rounded-0 text-capitalize"><?php echo e(__('download_btn')); ?></a>
                        </div>
                    </article>
                </li>
                <li class="col d-sm-none"><hr></li>
                <li class="col">
                    <article class="d-flex align-items-center">
                        <div class="w-50">
                            <div class="ratio ratio-1x1 w-100 bg-secondary-subtle">
                                <img src="<?php echo e(asset('images/brochure-supresso.jpg')); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-cover">
                            </div>
                        </div>
                        <div class="w-50 p-4">
                            <h2 class="fs-4 text-capitalize lh-1">supresso</h2>
                            <p class="text-capitalize"><?php echo e(__('download_product_spec')); ?></p>
                            <a href="https://indraco.com/brosur/BROCHURE CATALOG SPECIFICATION SUPRESSO NEW.pdf" target="_blank" class="btn btn-outline-invert rounded-0 text-capitalize"><?php echo e(__('download_btn')); ?></a>
                        </div>
                    </article>
                </li>
                <li class="col d-sm-none"><hr></li>
                <li class="col">
                    <article class="d-flex align-items-center">
                        <div class="w-50">
                            <div class="ratio ratio-1x1 w-100 bg-secondary-subtle">
                                <img src="<?php echo e(asset('images/brochure-products.jpg')); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-cover">
                            </div>
                        </div>
                        <div class="w-50 p-4">
                            <h2 class="fs-4 text-capitalize lh-1">UCAFÉ</h2>
                            <p class="text-capitalize"><?php echo e(__('download_product_spec')); ?></p>
                            <a href="https://indraco.com/brosur/Catalog Supresso UCAFE.pdf" target="_blank" class="btn btn-outline-invert rounded-0 text-capitalize"><?php echo e(__('download_btn')); ?></a>
                        </div>
                    </article>
                </li>
                <li class="col d-sm-none"><hr></li>
                <li class="col">
                    <article class="d-flex align-items-center">
                        <div class="w-50">
                            <div class="ratio ratio-1x1 w-100 bg-secondary-subtle">
                                <img src="<?php echo e(asset('images/brochure-products.jpg')); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-cover">
                            </div>
                        </div>
                        <div class="w-50 p-4">
                            <h2 class="fs-4 text-capitalize lh-1">BROCHOCO</h2>
                            <p class="text-capitalize"><?php echo e(__('download_product_spec')); ?></p>
                            <a href="https://indraco.com/brosur/Catalog BROCHOCO.pdf" target="_blank" class="btn btn-outline-invert rounded-0 text-capitalize"><?php echo e(__('download_btn')); ?></a>
                        </div>
                    </article>
                </li>
                <li class="col d-sm-none"><hr></li>
                <li class="col">
                    <article class="d-flex align-items-center">
                        <div class="w-50">
                            <div class="ratio ratio-1x1 w-100 bg-secondary-subtle">
                                <img src="<?php echo e(asset('images/brochure-products.jpg')); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-cover">
                            </div>
                        </div>
                        <div class="w-50 p-4">
                            <h2 class="fs-4 text-capitalize lh-1">jaheku</h2>
                            <p class="text-capitalize"><?php echo e(__('download_product_spec')); ?></p>
                            <a href="https://indraco.com/brosur/Catalog Jaheku.pdf" target="_blank" class="btn btn-outline-invert rounded-0 text-capitalize"><?php echo e(__('download_btn')); ?></a>
                        </div>
                    </article>
                </li>
            </ul>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/pages/download.blade.php ENDPATH**/ ?>