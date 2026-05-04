<?php $__env->startSection('title', 'Manajemen Kontak - Admin Panel'); ?>
<?php $__env->startSection('page_title', 'Manajemen Kontak'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
        <h5 class="mb-3 mb-md-0">Data Masuk dari Hubungi Kami</h5>
        <div class="d-flex flex-column flex-md-row gap-2">
            <form action="<?php echo e(url('admin/contacts')); ?>" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari pesan..." value="<?php echo e($search ?? ''); ?>">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 200px;">Pengirim</th>
                        <th style="width: 250px;">Subjek</th>
                        <th>Pesan</th>
                        <th style="width: 180px;">Tanggal</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold"><?php echo e($contact->nama); ?></div>
                            <small class="text-muted d-block"><?php echo e($contact->email); ?></small>
                            <small class="text-muted d-block"><?php echo e($contact->telepon); ?></small>
                        </td>
                        <td><?php echo e($contact->judul_pesan); ?></td>
                        <td>
                            <div class="text-truncate" style="max-width: 400px;" title="<?php echo e($contact->pesan); ?>">
                                <?php echo e($contact->pesan); ?>

                            </div>
                        </td>
                        <td><?php echo e($contact->tanggal_kirim); ?></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-info text-white me-1" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo e($contact->id); ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                            <form action="<?php echo e(url('admin/contacts/'.$contact->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal<?php echo e($contact->id); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Pesan dari <?php echo e($contact->nama); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Nama</div>
                                        <div class="col-md-8">: <?php echo e($contact->nama); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Email</div>
                                        <div class="col-md-8">: <?php echo e($contact->email); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Telepon</div>
                                        <div class="col-md-8">: <?php echo e($contact->telepon); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Subjek</div>
                                        <div class="col-md-8">: <?php echo e($contact->judul_pesan); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Tanggal Kirim</div>
                                        <div class="col-md-8">: <?php echo e($contact->tanggal_kirim); ?></div>
                                    </div>
                                    <hr>
                                    <div class="fw-bold mb-2">Pesan:</div>
                                    <div class="p-3 bg-light rounded" style="white-space: pre-wrap;"><?php echo e($contact->pesan); ?></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada pesan masuk.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/admin/contacts/index.blade.php ENDPATH**/ ?>