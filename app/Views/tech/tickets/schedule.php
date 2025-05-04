<?= $this->extend('layouts/tech') ?>

<?= $this->section('content') ?>
<div class="page-title d-flex justify-content-between align-items-center mb-3">
    <h1>Schedule Appointment</h1>
    <a href="<?= site_url('tech/tickets/view/' . $ticket['id']) ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Ticket
    </a>
</div>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-8">
            <form action="<?= site_url('tech/tickets/save-schedule/' . $ticket['id']) ?>" method="post">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Appointment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                value="<?= !empty($ticket['appointment_date']) ? date('Y-m-d', strtotime($ticket['appointment_date'])) : date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="appointment_time" class="form-label">Appointment Time</label>
                            <input type="time" class="form-control" id="appointment_time" name="appointment_time" 
                                value="<?= !empty($ticket['appointment_time']) ? date('H:i', strtotime($ticket['appointment_time'])) : '09:00' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estimated_duration" class="form-label">Estimated Duration</label>
                            <select class="form-select" id="estimated_duration" name="estimated_duration">
                                <option value="30" <?= (!empty($ticket['estimated_duration']) && $ticket['estimated_duration'] == 30) ? 'selected' : '' ?>>30 minutes</option>
                                <option value="60" <?= (empty($ticket['estimated_duration']) || $ticket['estimated_duration'] == 60) ? 'selected' : '' ?>>1 hour</option>
                                <option value="90" <?= (!empty($ticket['estimated_duration']) && $ticket['estimated_duration'] == 90) ? 'selected' : '' ?>>1.5 hours</option>
                                <option value="120" <?= (!empty($ticket['estimated_duration']) && $ticket['estimated_duration'] == 120) ? 'selected' : '' ?>>2 hours</option>
                                <option value="180" <?= (!empty($ticket['estimated_duration']) && $ticket['estimated_duration'] == 180) ? 'selected' : '' ?>>3 hours</option>
                                <option value="240" <?= (!empty($ticket['estimated_duration']) && $ticket['estimated_duration'] == 240) ? 'selected' : '' ?>>4 hours</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="appointment_notes" class="form-label">Notes for Customer</label>
                            <textarea class="form-control" id="appointment_notes" name="appointment_notes" rows="4"><?= !empty($ticket['appointment_notes']) ? esc($ticket['appointment_notes']) : '' ?></textarea>
                            <small class="text-muted">Include any instructions for the customer (parking information, required access, etc.)</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_customer" name="notify_customer" value="1" checked>
                            <label class="form-check-label" for="notify_customer">
                                Notify customer about this appointment
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= site_url('tech/tickets/view/' . $ticket['id']) ?>" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                </div>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ticket Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Ticket ID:</strong> #<?= $ticket['id'] ?></p>
                    <p><strong>Title:</strong> <?= esc($ticket['title']) ?></p>
                 
                    <p><strong>Status:</strong> 
                        <span class="badge bg-<?= function_exists('getStatusBadgeClass') ? getStatusBadgeClass($ticket['status']) : 'secondary' ?>">
                            <?= function_exists('formatStatus') ? formatStatus($ticket['status']) : ucfirst($ticket['status']) ?>
                        </span>
                    </p>
                    <p><strong>Priority:</strong> 
                        <span class="badge bg-<?= function_exists('getPriorityBadgeClass') ? getPriorityBadgeClass($ticket['priority']) : 'secondary' ?>">
                            <?= ucfirst($ticket['priority']) ?>
                        </span>
                    </p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?= esc($customer['name']) ?></p>
                    <p><strong>Phone:</strong> <?= esc($customer['phone']) ?></p>
                    <p><strong>Email:</strong> <?= esc($customer['email']) ?></p>
                    <p><strong>Address:</strong><br><?= nl2br(esc($customer['address'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>