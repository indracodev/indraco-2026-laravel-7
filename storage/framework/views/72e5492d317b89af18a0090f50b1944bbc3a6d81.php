<?php $__env->startSection('title', $item->translated_title . ' – INDRACO'); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <div class="container pb-5">
        <div class="row row-gap-5 py-lg-5 justify-content-lg-between">
            <div class="col col-12 col-lg-8 col-xl-7">
                <div class="sticky-top">
                    <div id="news-content">
                        <h2 class="fs-1 fw-bold text-capitalize mb-0 text-start">
                            <?php echo e($item->translated_title); ?>

                        </h2>
                        <p class="small mb-4">
                            <?php echo e($item->translated_date); ?>

                        </p>
                        <div class="news-body">
                            <?php echo $item->translated_content; ?>

                        </div>
                        <hr>
                        <div class="ratio ratio-1x1 w-100 h-auto bg-light-subtle">
                            <img src="<?php echo e(asset($item->image_path)); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-cover">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col col-12 col-lg-4">
                <div id="menu-news">
                    <h3 class="text-uppercase fs-5 fw-bold" data-i18n="news_btn_calendar"><?php echo e(__('news_btn_calendar')); ?></h3>
                    <hr class="opacity-100 border-2">
                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = \App\Models\News::latest()->limit(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $other): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route('news.show', $other->slug)); ?>" class="text-reset text-decoration-none opacity-75-hover text-start <?php echo e($item->id == $other->id ? 'fw-bold' : ''); ?>">
                                <h3 class="fs-5 text-capitalize text-2-line">
                                    <?php echo e($other->translated_title); ?>

                                </h3>
                                <p class="small">
                                    <?php echo e($other->translated_date); ?>

                                </p>
                            </a>
                        </li>
                        <li><hr></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <a href="<?php echo e(route('news')); ?>" class="btn btn-outline-secondary w-100 mt-3" data-i18n="news_back"><?php echo e(__('news_back')); ?></a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\indraco-2026-laravel-7\resources\views/news/show.blade.php ENDPATH**/ ?>