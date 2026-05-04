<?php $__env->startSection('title', 'Manajemen Banner - Admin Panel'); ?>
<?php $__env->startSection('page_title', 'Manajemen Banner'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
        <h5 class="mb-3 mb-md-0">Daftar Banner</h5>
        <div class="d-flex flex-column flex-md-row gap-2">
            <form action="<?php echo e(url('admin/banners')); ?>" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari banner..." value="<?php echo e($search ?? ''); ?>">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            </form>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Tambah Banner
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Judul Banner (ID)</th>
                        <th>Subjudul (ID)</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 fw-medium"><?php echo e($banner->title_id); ?></td>
                        <td><?php echo e($banner->subtitle_id); ?></td>
                        <td>
                            <span class="badge <?php echo e($banner->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                <?php echo e($banner->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($banner->id); ?>">Edit</button>
                            <form action="<?php echo e(url('admin/banners/'.$banner->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo e($banner->id); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="<?php echo e(url('admin/banners/'.$banner->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Banner</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label">Judul (ID)</label>
                                            <textarea name="title_id" class="form-control summernote" required><?php echo e($banner->title_id); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Subjudul (ID)</label>
                                            <textarea name="subtitle_id" class="form-control summernote" required><?php echo e($banner->subtitle_id); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Judul (EN)</label>
                                            <textarea name="title_en" class="form-control summernote" required><?php echo e($banner->title_en); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Subjudul (EN)</label>
                                            <textarea name="subtitle_en" class="form-control summernote" required><?php echo e($banner->subtitle_en); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tautan (Link)</label>
                                            <input type="url" name="link" class="form-control" value="<?php echo e($banner->link); ?>">
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" name="is_active" class="form-check-input" value="1" id="isActiveEdit<?php echo e($banner->id); ?>" <?php echo e($banner->is_active ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="isActiveEdit<?php echo e($banner->id); ?>">Banner Aktif</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data banner.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(url('admin/banners')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Banner Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul (ID)</label>
                        <textarea name="title_id" class="form-control summernote" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subjudul (ID)</label>
                        <textarea name="subtitle_id" class="form-control summernote" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul (EN)</label>
                        <textarea name="title_en" class="form-control summernote" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subjudul (EN)</label>
                        <textarea name="subtitle_en" class="form-control summernote" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tautan (Link)</label>
                        <input type="url" name="link" class="form-control" value="https://indracostore.com/">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" id="isActiveAdd" checked>
                        <label class="form-check-label" for="isActiveAdd">Banner Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 150,
            dialogsInBody: true,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u1313327/public_html/beta.indracocoffee.com/resources/views/admin/banners/index.blade.php ENDPATH**/ ?>