<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Ticket #<?= $ticket['id'] ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/tickets/view/' . $ticket['id']) ?>" class="btn btn-info me-2">
            <i class="fas fa-eye"></i> View Ticket
        </a>
        <a href="<?= site_url('admin/tickets') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= site_url('admin/tickets/update/' . $ticket['id']) ?>" method="post">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Ticket Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Ticket Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $ticket['title']) ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
                    <select class="form-select" id="organization_id" name="organization_id" required>
                        <option value="">Select Organization</option>
                        <?php foreach ($organizations as $org): ?>
                            <option value="<?= $org['id'] ?>" <?= old('organization_id', $ticket['organization_id']) == $org['id'] ? 'selected' : '' ?>>
                                <?= esc($org['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= old('description', $ticket['description']) ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="low" <?= old('priority', $ticket['priority']) == 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= old('priority', $ticket['priority']) == 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= old('priority', $ticket['priority']) == 'high' ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= old('priority', $ticket['priority']) == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="open" <?= old('status', $ticket['status']) == 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="assigned" <?= old('status', $ticket['status']) == 'assigned' ? 'selected' : '' ?>>Assigned</option>
                        <option value="in_progress" <?= old('status', $ticket['status']) == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="resolved" <?= old('status', $ticket['status']) == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                        <option value="closed" <?= old('status', $ticket['status']) == 'closed' ? 'selected' : '' ?>>Closed</option>
                        <option value="cancelled" <?= old('status', $ticket['status']) == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="technician_id" class="form-label">Assigned Technician</label>
                    <select class="form-select" id="technician_id" name="technician_id">
                        <option value="">Unassigned</option>
                        <?php foreach ($technicians as $tech): ?>
                            <option value="<?= $tech['id'] ?>" <?= old('technician_id', $ticket['technician_id']) == $tech['id'] ? 'selected' : '' ?>>
                                <?= esc($tech['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-end">
        <a href="<?= site_url('admin/tickets') ?>" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>
<?= $this->endSection() ?>