<?php $__env->startSection('title', __('nav_mesin_peralatan_khusus')); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <h1 class="visually-hidden" data-i18n="nav_mesin_peralatan_khusus"><?php echo e(__('nav_mesin_peralatan_khusus')); ?></h1>
    <div class="container">
        <?php
            $sections = [
                [
                    'id' => 'coffee',
                    'title_key' => 'naveq_1',
                    'desc_key' => 'naveq_coffee_desc',
                    'items' => [
                        ['title_key' => 'naveq_10', 'desc_key' => 'naveq_11', 'img' => 'eq-coffee-machine-full-auto.png'],
                        ['title_key' => 'naveq_12', 'desc_key' => 'naveq_13', 'img' => 'eq-coffee-machine-semi-auto.png'],
                        ['title_key' => 'naveq_14', 'desc_key' => 'naveq_15', 'img' => 'eq-seduh-kopi.png'],
                        ['title_key' => 'naveq_16', 'desc_key' => 'naveq_17', 'img' => 'eq-capsules-machine.png'],
                        ['title_key' => 'naveq_18', 'desc_key' => 'naveq_19', 'img' => 'eq-grinder.png'],
                    ]
                ],
                [
                    'id' => 'dispenser',
                    'title_key' => 'naveq_2',
                    'desc_key' => 'naveq_dispenser_desc',
                    'items' => [
                        ['title_key' => 'naveq_22', 'desc_key' => 'naveq_23', 'img' => 'eq-instant-drink-machine.png'],
                        ['title_key' => 'naveq_24', 'desc_key' => 'naveq_25', 'img' => 'eq-dispenser-cold.png'],
                    ]
                ],
                [
                    'id' => 'accessories',
                    'title_key' => 'naveq_3',
                    'desc_key' => 'naveq_accessories_desc',
                    'items' => [
                        ['title_key' => 'naveq_31', 'desc_key' => 'naveq_32', 'img' => 'eq-acc-milk-shake.png'],
                        ['title_key' => 'naveq_33', 'desc_key' => 'naveq_34', 'img' => 'eq-acc-electric-ketel.png'],
                        ['title_key' => 'naveq_35', 'desc_key' => 'naveq_36', 'img' => 'eq-acc-french-press.png'],
                        ['title_key' => 'naveq_37', 'desc_key' => 'naveq_38', 'img' => 'eq-acc-moka-pot.png'],
                        ['title_key' => 'naveq_39', 'desc_key' => 'naveq_40', 'img' => 'eq-acc-2glass.png'],
                    ]
                ],
            ];
        ?>

        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <section id="<?php echo e($section['id']); ?>" class="py-5">
            <div class="py-lg-5">
                <div class="mb-5">
                    <h2 class="display-4 fw-thin text-capitalize"><b class="fw-bold" data-i18n="<?php echo e($section['title_key']); ?>"><?php echo e(__($section['title_key'])); ?></b></h2>
                    <p class="lead" data-i18n="<?php echo e($section['desc_key']); ?>"><?php echo e(__($section['desc_key'])); ?></p>
                </div>
                <ul class="list-unstyled mb-0 row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-gap-5 gx-md-5">
                    <?php $__currentLoopData = $section['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="col">
                        <article>
                            <img src="<?php echo e(asset('images/' . $item['img'])); ?>" alt="" aria-hidden="true" loading="lazy" class="theme-image" style="aspect-ratio: 1/1; width: 30%; object-fit: contain;">
                            <h3 class="fw-bold fs-4 text-capitalize my-3" data-i18n="<?php echo e($item['title_key']); ?>"><?php echo e(__($item['title_key'])); ?></h3>
                            <p data-i18n="<?php echo e($item['desc_key']); ?>"><?php echo e(__($item['desc_key'])); ?></p>
                        </article>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </section>
        <?php if(!$loop->last): ?>
        <hr class="m-0">
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/pages/equipment.blade.php ENDPATH**/ ?>