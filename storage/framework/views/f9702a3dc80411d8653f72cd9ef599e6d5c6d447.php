<?php $__env->startSection('title', __('Berita & Acara') . ' – INDRACO'); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <h1 class="visually-hidden"><?php echo e(__('news_h1') ?? 'halaman berita & acara'); ?></h1>
    <div class="container pb-5">
        <div class="row row-gap-5 py-lg-5 justify-content-lg-between">
            <div class="col col-12 col-lg-8 col-xl-7">
                <?php 
                    $current = $news->first(); 
                ?>
                <?php if($current): ?>
                <div class="sticky-top">
                    <div id="news-content">
                        <h2 class="fs-1 fw-bold text-capitalize mb-0 text-start">
                            <?php echo e($current->translated_title); ?>

                        </h2>
                        <p class="small mb-4">
                            <?php echo e($current->translated_date); ?>

                        </p>
                        <div class="news-body">
                            <?php echo $current->translated_content; ?>

                        </div>
                        <hr>
                        <div class="ratio ratio-1x1 w-100 h-auto bg-light-subtle">
                            <img src="<?php echo e(asset($current->image_path)); ?>" alt="" loading="lazy" aria-hidden="true" class="object-fit-cover">
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <p><?php echo e(__('Belum ada berita.')); ?></p>
                <?php endif; ?>
            </div>

            <div class="col col-12 col-lg-4">
                <div id="menu-news" class="order-lg-2">
                    <h3 class="text-uppercase fs-5 fw-bold" data-i18n="news_btn_calendar"><?php echo e(__('kalender acara')); ?></h3>
                    <hr class="opacity-100 border-2">
                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="text-reset text-decoration-none opacity-75-hover text-start <?php echo e(isset($current) && $current->id == $item->id ? 'fw-bold' : ''); ?>">
                                <h3 class="fs-5 text-capitalize text-2-line">
                                    <?php echo e($item->translated_title); ?>

                                </h3>
                                <p class="small">
                                    <?php echo e($item->translated_date); ?>

                                </p>
                            </a>
                        </li>
                        <li><hr></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <div class="mt-4">
                        <?php echo e($news->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/news/index.blade.php ENDPATH**/ ?>