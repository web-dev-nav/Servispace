<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Technicians</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/technicians/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Technician
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
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Tech ID</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($technicians)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No technicians found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($technicians as $tech): ?>
                            <tr>
                                <td><?= $tech['id'] ?></td>
                                <td>
                                    <?php if(!empty($tech['photo']) && file_exists(WRITEPATH . $tech['photo'])): ?>
                                        <img src="<?= base_url(WRITEPATH . $tech['photo']) ?>" alt="<?= $tech['name'] ?>" class="img-thumbnail" width="50">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/img/default-user.png') ?>" alt="Default" class="img-thumbnail" width="50">
                                    <?php endif; ?>
                                </td>
                                <td><?= $tech['name'] ?></td>
                                <td><?= $tech['email'] ?></td>
                                <td><?= $tech['phone'] ?? '-' ?></td>
                                <td><?= $tech['tech_id'] ?? '-' ?></td>
                                <td>
                                    <?php if($tech['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= site_url('admin/technicians/profile/' . $tech['id']) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        <a href="<?= site_url('admin/technicians/edit/' . $tech['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= site_url('admin/technicians/delete/' . $tech['id']) ?>" class="btn btn-sm btn-danger delete-btn" data-id="<?= $tech['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this technician? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up deletion confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const deleteUrl = this.getAttribute('href');
            confirmDeleteButton.setAttribute('href', deleteUrl);
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>