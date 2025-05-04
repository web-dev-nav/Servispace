<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= $title ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/tickets/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Ticket
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

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link <?= empty($filter) && empty($organizationId) && empty($technicianId) ? 'active' : '' ?>" href="<?= site_url('admin/tickets') ?>">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $filter === 'unassigned' ? 'active' : '' ?>" href="<?= site_url('admin/tickets?filter=unassigned') ?>">Unassigned</a>
                    </li>       
                    <li class="nav-item">
                        <a class="nav-link <?= $filter === 'open' ? 'active' : '' ?>" href="<?= site_url('admin/tickets?filter=open') ?>">Open</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $filter === 'assigned' ? 'active' : '' ?>" href="<?= site_url('admin/tickets?filter=assigned') ?>">Assigned</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $filter === 'in_progress' ? 'active' : '' ?>" href="<?= site_url('admin/tickets?filter=in_progress') ?>">In Progress</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $filter === 'resolved' ? 'active' : '' ?>" href="<?= site_url('admin/tickets?filter=resolved') ?>">Resolved</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $filter === 'closed' ? 'active' : '' ?>" href="<?= site_url('admin/tickets?filter=closed') ?>">Closed</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Organization</th>
                                <th>Technician</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($tickets)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No tickets found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($tickets as $ticket): ?>
                                    <tr>
                                        <td><?= $ticket['id'] ?></td>
                                        <td>
                                            <a href="<?= site_url('admin/tickets/view/' . $ticket['id']) ?>" class="text-decoration-none">
                                                <?= esc($ticket['title']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('admin/tickets?organization=' . $ticket['organization_id']) ?>" class="badge bg-secondary text-decoration-none">
                                                <?= esc($ticket['organization_name']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if(!empty($ticket['technician_id']) && !empty($ticket['technician_name'])): ?>
                                                <a href="<?= site_url('admin/tickets?technician=' . $ticket['technician_id']) ?>" class="badge bg-info text-decoration-none">
                                                    <?= esc($ticket['technician_name']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Unassigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= getStatusBadgeClass($ticket['status']) ?>">
                                                <?= formatStatus($ticket['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= getPriorityBadgeClass($ticket['priority']) ?>">
                                                <?= ucfirst($ticket['priority']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('admin/tickets/view/' . $ticket['id']) ?>" class="btn btn-sm btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= site_url('admin/tickets/edit/' . $ticket['id']) ?>" class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if(empty($ticket['technician_id']) && $ticket['status'] === 'open'): ?>
                                                    <button type="button" class="btn btn-sm btn-success quick-assign-btn" data-ticket-id="<?= $ticket['id'] ?>" data-bs-toggle="modal" data-bs-target="#assignModal" title="Assign">
                                                        <i class="fas fa-user-plus"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?= site_url('admin/tickets/delete/' . $ticket['id']) ?>" class="btn btn-sm btn-danger delete-btn" title="Delete">
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
    </div>
</div>

<!-- Quick Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Assign Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" action="" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="technician_id" class="form-label">Technician</label>
                        <select class="form-select" id="technician_id" name="technician_id" required>
                            <option value="">Select Technician</option>
                            <?php 
                            $technicians = (new \App\Models\TechnicianModel())->getActiveTechnicians();
                            foreach ($technicians as $tech): 
                            ?>
                                <option value="<?= $tech['id'] ?>"><?= esc($tech['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Ticket</button>
                </div>
            </form>
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
                Are you sure you want to delete this ticket? This action cannot be undone.
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
    // Quick assign ticket
    const quickAssignBtns = document.querySelectorAll('.quick-assign-btn');
    quickAssignBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const ticketId = this.getAttribute('data-ticket-id');
            const assignForm = document.getElementById('assignForm');
            assignForm.action = '<?= site_url('admin/tickets/assign/') ?>' + ticketId;
        });
    });
    
    // Delete confirmation
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