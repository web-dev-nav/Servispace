<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Technician Profile</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/technicians') ?>" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="<?= site_url('admin/technicians/edit/' . $technician['id']) ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <?php if(!empty($technician['photo']) && file_exists(WRITEPATH . $technician['photo'])): ?>
                    <img src="<?= base_url(WRITEPATH . $technician['photo']) ?>" alt="<?= $technician['name'] ?>" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                <?php else: ?>
                    <img src="<?= base_url('assets/img/default-user.png') ?>" alt="Default" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                <?php endif; ?>
                
                <h3 class="card-title"><?= $technician['name'] ?></h3>
                <p class="card-text">
                    <span class="badge bg-<?= $technician['is_active'] ? 'success' : 'danger' ?>">
                        <?= $technician['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </p>
                
                <?php if(!empty($technician['tech_id'])): ?>
                    <div class="mt-3">
                        <h5>Tech ID</h5>
                        <p class="mb-0"><?= $technician['tech_id'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $technician['email'] ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?= $technician['phone'] ?? 'Not provided' ?></td>
                    </tr>
                    <tr>
                        <th>Added On:</th>
                        <td><?= date('F d, Y', strtotime($technician['created_at'])) ?></td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td><?= date('F d, Y', strtotime($technician['updated_at'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Assigned Tickets</h5>
            </div>
            <div class="card-body">
                <?php
                $ticketModel = new \App\Models\TicketModel();
                $assignedTickets = $ticketModel->getTicketsByTechnician($technician['id']);
                ?>
                
                <?php if (empty($assignedTickets)): ?>
                    <p class="text-muted">No tickets assigned yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Created</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignedTickets as $ticket): ?>
                                    <tr>
                                        <td><?= $ticket['id'] ?></td>
                                        <td><?= esc($ticket['title']) ?></td>
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
                                            <a href="<?= site_url('admin/tickets/view/' . $ticket['id']) ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>