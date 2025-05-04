<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Organizations</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/organizations/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Organization
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('message')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact Person</th>
                        <th>Contact Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($organizations)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No organizations found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($organizations as $organization): ?>
                            <tr>
                                <td><?= $organization['id'] ?></td>
                                <td><?= $organization['name'] ?></td>
                                <td><?= $organization['contact_name'] ?? '-' ?></td>
                                <td><?= $organization['contact_email'] ?? '-' ?></td>
                                <td>
                                    <?php if($organization['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= site_url('admin/organizations/edit/' . $organization['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= site_url('admin/organizations/delete/' . $organization['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this organization?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>