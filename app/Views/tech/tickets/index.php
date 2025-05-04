<?= $this->extend('layouts/tech') ?>

<?= $this->section('content') ?>
<div class="page-title d-flex justify-content-between align-items-center">
    <h1 class="mb-0">My Tickets</h1>
    <div class="d-flex">
        <div class="dropdown me-2">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Filter: <?= ucfirst($filter ?? 'All') ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                <li><a class="dropdown-item" href="<?= site_url('tech/tickets') ?>">All</a></li>
                <li><a class="dropdown-item" href="<?= site_url('tech/tickets?filter=pending') ?>">Pending Appointment</a></li>
                <li><a class="dropdown-item" href="<?= site_url('tech/tickets?filter=scheduled') ?>">Scheduled</a></li>
                <li><a class="dropdown-item" href="<?= site_url('tech/tickets?filter=in_progress') ?>">In Progress</a></li>
                <li><a class="dropdown-item" href="<?= site_url('tech/tickets?filter=completed') ?>">Completed</a></li>
            </ul>
        </div>
    </div>
</div>
<?php print_r($tickets);?>
<div class="content-wrapper">
    <?php if(empty($tickets)): ?>
        <div class="text-center py-5">
            <div class="mb-3"><i class="fas fa-ticket-alt fa-3x text-muted"></i></div>
            <h4>No Tickets Found</h4>
            <p class="text-muted">No tickets match your current filter criteria.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Customer</th>
                        <th>Issue</th>
                        <th>Parts</th>
                        <th>Appointment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tickets as $ticket): ?>
                        <tr>
                            <td>#<?= $ticket['id'] ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($ticket['customer_name']) ?></div>
                                <small class="text-muted"><?= esc($ticket['organization_name']) ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($ticket['title']) ?></div>
                                <span class="badge bg-<?= getPriorityBadgeClass($ticket['priority']) ?>"><?= ucfirst($ticket['priority']) ?></span>
                            </td>
                            <td>
                                <?php if(!empty($ticket['part_details'])): ?>
                                    <span class="badge bg-info"><?= count(json_decode($ticket['part_details'], true)) ?> parts</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No parts</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!empty($ticket['appointment_date'])): ?>
                                    <div class="fw-semibold"><?= date('M d, Y', strtotime($ticket['appointment_date'])) ?></div>
                                    <small class="text-muted"><?= date('h:i A', strtotime($ticket['appointment_time'])) ?></small>
                                <?php else: ?>
                                    <span class="badge bg-warning">Not Scheduled</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= getStatusBadgeClass($ticket['status']) ?>">
                                    <?= formatStatus($ticket['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= site_url('tech/tickets/view/' . $ticket['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php if(empty($ticket['appointment_date'])): ?>
                                            <li><a class="dropdown-item" href="<?= site_url('tech/tickets/schedule/' . $ticket['id']) ?>">Schedule Appointment</a></li>
                                        <?php else: ?>
                                            <li><a class="dropdown-item" href="<?= site_url('tech/tickets/reschedule/' . $ticket['id']) ?>">Reschedule</a></li>
                                        <?php endif; ?>
                                        
                                        <?php if($ticket['status'] == 'assigned' || $ticket['status'] == 'scheduled'): ?>
                                            <li><a class="dropdown-item" href="<?= site_url('tech/tickets/start/' . $ticket['id']) ?>">Start Service</a></li>
                                        <?php endif; ?>
                                        
                                        <?php if($ticket['status'] == 'in_progress'): ?>
                                            <li><a class="dropdown-item" href="<?= site_url('tech/tickets/complete/' . $ticket['id']) ?>">Complete Service</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>


<?= $this->endSection() ?>