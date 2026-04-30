<aside id="menu" class="offcanvas offcanvas-start" tabindex="-1" aria-labelledby="menu-title">
   <div class="p-3 pb-0">
      <h5 id="menu-title" class="visually-hidden">Menu Navigasi</h5>
      <button class="btn-close rounded-0 border-0 shadow-none float-end" type="button" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
   </div>
   <div class="p-3 pt-0 flex-grow-1 overflow-y-auto">
      <!-- navigasi mobile -->
      <nav aria-label="navigasi mobile">
         <ul class="nav d-block">
            <li class="nav-item">
               <a href="<?php echo e(route('about')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_about"><?php echo e(__('nav_about')); ?></a>
            </li>
            <li class="nav-item collapse-item">
               <a data-bs-toggle="collapse" href="#collapse-prduk" class="nav-link collapse-toggler text-reset opacity-75-hover px-0 collapsed" data-i18n="nav_product"><?php echo e(__('nav_product')); ?></a>
               <div class="collapse" id="collapse-prduk">
                  <ul class="nav d-block ps-4">
                     <li class="nav-item collapse-item">
                        <a data-bs-toggle="collapse" href="#collapse-produk-konsummen" class="nav-link collapse-toggler text-reset opacity-75-hover px-0 collapsed" data-i18n="nav_produk_konsumen"><?php echo e(__('nav_produk_konsumen')); ?></a>
                        <div class="collapse" id="collapse-produk-konsummen">
                           <ul id="brand" class="nav d-block ps-4">
                              <?php
                                 $consumer_cat = $main_categories->where('slug', 'produk-konsumen')->first();
                              ?>
                              <?php if($consumer_cat && isset($sub_categories_map[$consumer_cat->id])): ?>
                                 <?php $__currentLoopData = $sub_categories_map[$consumer_cat->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item">
                                       <?php
                                          $brand_slug = str_replace('consumer-', '', $sub->slug);
                                          $brand_route = (str_contains($sub->slug, 'supresso')) ? 'product.supresso' : 'product.indraco';
                                          $lk = ['consumer-supresso'=>'nav_supresso','consumer-tugu-buaya'=>'nav_tugu_buaya','consumer-rasa-sayang'=>'nav_rasa_sayang','consumer-jaheku'=>'nav_jaheku','consumer-brochoco'=>'nav_brochoco','consumer-intirasa'=>'nav_intirasa','consumer-hao-cafe'=>'nav_hao_cafe','consumer-ucafe'=>'nav_ucafe','consumer-balicafe'=>'nav_balicafe','consumer-uang-emas'=>'nav_uang_emas'][$sub->slug] ?? ('nav_' . str_replace('-','_',$sub->slug));
                                       ?>
                                       <a href="<?php echo e(route($brand_route, $brand_slug)); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="<?php echo e($lk); ?>"><?php echo e(__($lk)); ?></a>
                                    </li>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                           </ul>
                        </div>
                     </li>
                     <li class="nav-item">
                        <a href="<?php echo e(route('foodservice')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_food_service"><?php echo e(__('nav_food_service')); ?></a>
                     </li>
                     <li class="nav-item">
                        <a href="<?php echo e(route('equipment')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_mesin_peralatan_khusus"><?php echo e(__('nav_mesin_peralatan_khusus')); ?></a>
                     </li>
                  </ul>
               </div>
            </li>
            <li class="nav-item">
               <a href="<?php echo e(route('businesses')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_business"><?php echo e(__('nav_business')); ?></a>
            </li>
            <li class="nav-item">
               <a href="<?php echo e(route('stores')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_stores"><?php echo e(__('nav_stores')); ?></a>
            </li>
            <li class="nav-item">
               <a href="<?php echo e(route('news')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_news"><?php echo e(__('nav_news')); ?></a>
            </li>
            <li class="nav-item">
               <a href="<?php echo e(route('download')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_download"><?php echo e(__('nav_download')); ?></a>
            </li>
            <li class="nav-item">
               <a href="<?php echo e(route('career')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_career"><?php echo e(__('nav_career')); ?></a>
            </li>
            <li class="nav-item">
               <a href="<?php echo e(route('contact')); ?>" class="nav-link text-reset opacity-75-hover px-0" data-i18n="nav_contact"><?php echo e(__('nav_contact')); ?></a>
            </li>
         </ul>
      </nav>
   </div>
   <div class="px-3"><hr class="m-0"></div>
   <div class="p-3">
      <ul class="nav flex-column">
         <li class="nav-item">
            <a href="https://indracostore.com/" target="_blank" class="nav-link text-reset opacity-75-hover px-0">INDRACO Store</a>
         </li>
         <li class="nav-item">
            <a href="<?php echo e(route('lang.switch', 'en')); ?>" class="nav-link text-reset opacity-75-hover px-0 <?php echo e(app()->getLocale() == 'en' ? 'fw-bold' : ''); ?>">&#x1f1fa;&#x1f1f8; English</a>
         </li>
         <li class="nav-item">
            <a href="<?php echo e(route('lang.switch', 'id')); ?>" class="nav-link text-reset opacity-75-hover px-0 <?php echo e(app()->getLocale() == 'id' ? 'fw-bold' : ''); ?>">&#x1F1EE;&#x1F1E9; Indonesia</a>
         </li>
      </ul>
   </div>
</aside>
<?php /**PATH C:\laragon\www\#indraco\indraco-2026\indraco-2026-laravel-7\resources\views/partials/menu_mobile.blade.php ENDPATH**/ ?>