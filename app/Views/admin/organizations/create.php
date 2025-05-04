<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Organization</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/organizations') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= site_url('admin/organizations/store') ?>" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active') ? 'checked' : '' ?> checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            
            <h5 class="mt-4 mb-3 border-bottom pb-2">Contact Information</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact_name" class="form-label">Contact Person</label>
                    <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?= old('contact_name') ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= old('contact_email') ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="contact_phone" class="form-label">Contact Phone</label>
                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?= old('contact_phone') ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
                </div>
            </div>
            
            <h5 class="mt-4 mb-3 border-bottom pb-2">Support Information</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="support_email" class="form-label">Support Email</label>
                    <input type="email" class="form-control" id="support_email" name="support_email" value="<?= old('support_email') ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="support_phone" class="form-label">Support Phone</label>
                    <input type="text" class="form-control" id="support_phone" name="support_phone" value="<?= old('support_phone') ?>">
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                <button type="submit" class="btn btn-primary">Create Organization</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>