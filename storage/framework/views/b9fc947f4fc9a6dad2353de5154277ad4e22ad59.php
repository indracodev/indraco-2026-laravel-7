<?php $__env->startSection('title', ($is_search ?? false ? 'Pencarian: ' . $search_query : $brand->nama_merek) . ' – INDRACO'); ?>

<?php $__env->startSection('content'); ?>
<main id="konten">
    <h1 class="visually-hidden">halaman produk</h1>

    <div class="container py-5">
        <div class="py-lg-5">
            <header aria-label="header brand" class="brand-header d-flex flex-column flex-lg-row align-items-lg-center row-gap-5 mb-5">
                <?php if($is_search ?? false): ?>
                    <h2 class="display-4 text-capitalize fw-thin order-lg-1 text-center text-lg-start">
                        <span>Hasil Pencarian</span>: <br> <b class="fw-bold">"<?php echo e($search_query); ?>"</b>
                    </h2>
                <?php else: ?>
                    <?php
                        $merek_slug = str_replace('consumer-', '', $brand->slug);
                        $logo_img = "images/logo-{$merek_slug}.png";
                    ?>
                    <img src="<?php echo e(asset($logo_img)); ?>" alt="" loading="lazy" aria-hidden="true" class="theme-image w-75 mx-auto order-lg-2 me-lg-0" style="max-width: 280px;" onerror="this.src='<?php echo e(asset('images/logo-tugu-buaya.png')); ?>'">
                    <h2 class="display-4 text-capitalize fw-thin order-lg-1 text-center text-lg-start">
                        <span>produk</span> <br>
                        <b class="fw-bold">
                            <?php echo str_replace('Kopi ', 'kopi <br> <b class="fw-bold">', $brand->nama_merek); ?></b>
                    </h2>
                <?php endif; ?>
            </header>

            <!-- product list -->
            <ol class="list-unstyled mb-0 row product-list row-cols-1 row-gap-5 row-cols-sm-2 row-cols-lg-3 gx-sm-4 gx-lg-5 pt-lg-5">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $img_path = !empty($prod->gambar_utama) ? $prod->gambar_utama : 'images/no-image.png';
                        $target_link = !empty($prod->link_web) ? $prod->link_web : '#';
                    ?>
                    <li class="product-item col">
                        <a href="<?php echo e($target_link); ?>" <?php echo e($target_link != '#' ? 'target="_blank"' : ''); ?> class="text-reset text-decoration-none">
                            <article class="card border-0 bg-transparent h-100 pointer-hover">
                                <div class="card-header p-0 bg-transparent border-0 rounded-0">
                                    <div class="card-img ratio ratio-1x1 bg-transparent overflow-hidden">
                                        <img src="<?php echo e(asset($img_path)); ?>" alt="<?php echo e($prod->nama_produk); ?>" loading="lazy" class="object-fit-contain w-100 h-100 drop-shadow">
                                    </div>
                                </div>
                                <div class="card-body text-center d-flex flex-column justify-content-center pt-4">
                                    <h4 class="card-title fs-6 fw-bold text-capitalize mb-2"><?php echo e($prod->nama_produk); ?></h4>
                                    <?php if(!empty($prod->tipe_packing)): ?>
                                        <p class="card-text"><?php echo e($prod->tipe_packing); ?> (<?php echo e($prod->inner_kemasan); ?>)</p>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted"><?php echo e($is_search ?? false ? 'Produk tidak ditemukan.' : 'Produk belum tersedia untuk kategori ini.'); ?></p>
                    </div>
                <?php endif; ?>
            </ol>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/products/brand_standard.blade.php ENDPATH**/ ?>