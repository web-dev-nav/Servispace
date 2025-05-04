<?= $this->extend('layouts/tech') ?>

<?= $this->section('content') ?>

<?php if(session()->getFlashdata('message')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="page-title d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Dashboard</h1>
    <a href="<?= site_url('tech/tickets') ?>" class="btn btn-primary">
        <i class="fas fa-ticket-alt me-2"></i> View All Tickets
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-card-header">
                <div>
                    <div class="stats-card-value"><?= $total_tickets ?></div>
                    <div class="stats-card-title">Total Tickets</div>
                </div>
                <div class="stats-card-icon icon-primary">
                    <i class="fas fa-ticket-alt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-card-header">
                <div>
                    <div class="stats-card-value"><?= $open_tickets ?></div>
                    <div class="stats-card-title">Open Tickets</div>
                </div>
                <div class="stats-card-icon icon-warning">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-card-header">
                <div>
                    <div class="stats-card-value"><?= $in_progress_tickets ?></div>
                    <div class="stats-card-title">In Progress</div>
                </div>
                <div class="stats-card-icon icon-info">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-card-header">
                <div>
                    <div class="stats-card-value"><?= $resolved_tickets ?></div>
                    <div class="stats-card-title">Resolved</div>
                </div>
                <div class="stats-card-icon icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tickets -->
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Recent Tickets</h2>
        <a href="<?= site_url('tech/tickets') ?>" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    
    <?php if(empty($recent_tickets)): ?>
        <div class="text-center py-5">
            <div class="mb-3"><i class="fas fa-ticket-alt fa-3x text-muted"></i></div>
            <h4>No Tickets Assigned</h4>
            <p class="text-muted">You don't have any tickets assigned to you yet.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_tickets as $ticket): ?>
                        <tr>
                            <td>#<?= $ticket['id'] ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($ticket['title']) ?></div>
                            </td>
                            <td><?= esc($ticket['organization_name']) ?></td>
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
                            <td><?= date('M d, Y', strtotime($ticket['updated_at'])) ?></td>
                            <td>
                                <a href="<?= site_url('tech/tickets/view/' . $ticket['id']) ?>" class="btn btn-sm btn-primary">
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



<?= $this->endSection() ?>