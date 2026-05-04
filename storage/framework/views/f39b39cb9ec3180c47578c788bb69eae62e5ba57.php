<?php $__env->startSection('title', __('fs_page_title')); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <h1 class="visually-hidden" data-i18n="fs_heading"><?php echo e(__('fs_heading')); ?></h1>
    <div class="container">
        <?php
            $services = [
                ['key' => 'coffee', 'title_key' => 'fs_coffee_title', 'desc_key' => 'navdesc_coffee'],
                ['key' => 'creamer', 'title_key' => 'fs_creamer_title', 'desc_key' => 'navdesc_creamer'],
                ['key' => 'tea', 'title_key' => 'fs_tea_title', 'desc_key' => 'navdesc_tea'],
                ['key' => 'ginger', 'title_key' => 'fs_ginger_title', 'desc_key' => 'navdesc_ginger'],
                ['key' => 'chocolate', 'title_key' => 'fs_chocolate_title', 'desc_key' => 'navdesc_chocolate'],
                ['key' => 'sugar', 'title_key' => 'fs_sugar_title', 'desc_key' => 'navdesc_sugar'],
            ];
        ?>

        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <section class="py-5">
            <div class="py-lg-5">
                <h2 class="display-4 fw-thin text-capitalize"><b class="fw-bold" data-i18n="<?php echo e($service['title_key']); ?>"><?php echo e(__($service['title_key'])); ?></b></h2>
                <p class="lead mb-5" data-i18n="<?php echo e($service['desc_key']); ?>"><?php echo e(__($service['desc_key'])); ?></p>
            </div>
        </section>
        <?php if(!$loop->last): ?>
        <hr class="m-0">
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/pages/foodservice.blade.php ENDPATH**/ ?>