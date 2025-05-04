<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        Ticket #<?= $ticket['id'] ?>
        <span class="badge bg-<?= getStatusBadgeClass($ticket['status']) ?> ms-2">
            <?= formatStatus($ticket['status']) ?>
        </span>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/tickets/edit/' . $ticket['id']) ?>" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Edit Ticket
        </a>
        <a href="<?= site_url('admin/tickets') ?>" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog"></i> Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php if (empty($ticket['technician_id']) && $ticket['status'] === 'open'): ?>
                    <li>
                        <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#assignModal">
                            <i class="fas fa-user-plus"></i> Assign Technician
                        </button>
                    </li>
                <?php elseif (!empty($ticket['technician_id'])): ?>
                    <li>
                        <a class="dropdown-item" href="<?= site_url('admin/tickets/unassign/' . $ticket['id']) ?>" onclick="return confirm('Are you sure you want to unassign this ticket?')">
                            <i class="fas fa-user-minus"></i> Unassign Technician
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-exchange-alt"></i> Change Status
                    </button>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="<?= site_url('admin/tickets/delete/' . $ticket['id']) ?>" onclick="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete Ticket
                    </a>
                </li>
            </ul>
        </div>
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

<div class="row">
    <div class="col-md-8">
        <!-- Ticket Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?= esc($ticket['title']) ?></h5>
            </div>
            <div class="card-body">
                <div class="ticket-description mb-4">
                    <?= nl2br(esc($ticket['description'])) ?>
                </div>
                
                <?php if (!empty($attachments)): ?>
                    <div class="ticket-attachments">
                        <h6><i class="fas fa-paperclip"></i> Attachments</h6>
                        <div class="list-group">
                            <?php foreach ($attachments as $attachment): ?>
                                <?php if (empty($attachment['update_id'])): // Only show main ticket attachments ?>
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <a href="<?= site_url('admin/tickets/attachment/' . $attachment['id']) ?>" target="_blank" class="text-decoration-none">
                                            <i class="<?= getFileIcon($attachment['file_type']) ?> me-2"></i>
                                            <?= esc($attachment['file_name']) ?>
                                            <span class="text-muted ms-2">(<?= formatBytes($attachment['file_size']) ?>)</span>
                                        </a>
                                        <a href="<?= site_url('admin/tickets/delete-attachment/' . $attachment['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this attachment?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer text-muted">
                Created on <?= date('F d, Y \a\t h:i A', strtotime($ticket['created_at'])) ?>
            </div>
        </div>
        
        <!-- Parts Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Parts</h5>
                <a href="<?= site_url('admin/tickets/edit/' . $ticket['id']) ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Manage Parts
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($parts)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No parts have been added to this ticket yet.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Part Number</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($parts as $part): ?>
                                    <tr>
                                        <td><?= esc($part['part_number']) ?></td>
                                        <td><?= esc($part['description']) ?></td>
                                        <td><?= $part['quantity'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= getPartStatusBadgeClass($part['status']) ?>">
                                                <?= formatPartStatus($part['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Ticket Updates/Comments -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Updates & Comments</h5>
            </div>
            <div class="card-body">
                <div class="ticket-updates">
                    <?php if (empty($updates)): ?>
                        <div class="alert alert-info">
                            No updates or comments yet.
                        </div>
                    <?php else: ?>
                        <?php foreach ($updates as $update): ?>
                            <div class="card mb-3 <?= $update['is_private'] ? 'border-warning' : '' ?>">
                                <div class="card-header bg-<?= $update['is_private'] ? 'warning bg-opacity-25' : 'light' ?> d-flex justify-content-between">
                                    <div>
                                        <strong>
                                            <?php
                                            // Display the name based on user_type and user_id
                                            if ($update['user_type'] === 'admin') {
                                                echo 'Admin';
                                            } elseif ($update['user_type'] === 'technician') {
                                                $tech = (new \App\Models\TechnicianModel())->find($update['user_id']);
                                                echo $tech ? $tech['name'] : 'Technician';
                                            } else {
                                                echo 'Customer';
                                            }
                                            ?>
                                        </strong>
                                        <span class="text-muted ms-2"><?= date('M d, Y \a\t h:i A', strtotime($update['created_at'])) ?></span>
                                        <?php if ($update['is_private']): ?>
                                            <span class="badge bg-warning text-dark ms-2">Private Note</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="update-comment">
                                        <?= nl2br(esc($update['comment'])) ?>
                                    </div>
                                    
                                    <?php
                                    // Find attachments for this update
                                    $updateAttachments = array_filter($attachments, function($a) use ($update) {
                                        return $a['update_id'] == $update['id'];
                                    });
                                    
                                    if (!empty($updateAttachments)):
                                    ?>
                                        <div class="update-attachments mt-3">
                                            <h6 class="text-muted"><i class="fas fa-paperclip"></i> Attachments</h6>
                                            <div class="list-group">
                                                <?php foreach ($updateAttachments as $attachment): ?>
                                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <a href="<?= site_url('admin/tickets/attachment/' . $attachment['id']) ?>" target="_blank" class="text-decoration-none">
                                                            <i class="<?= getFileIcon($attachment['file_type']) ?> me-2"></i>
                                                            <?= esc($attachment['file_name']) ?>
                                                            <span class="text-muted ms-2">(<?= formatBytes($attachment['file_size']) ?>)</span>
                                                        </a>
                                                        <a href="<?= site_url('admin/tickets/delete-attachment/' . $attachment['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this attachment?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Add Comment Form -->
                <div class="add-comment mt-4">
                    <h5>Add Update</h5>
                    <form action="<?= site_url('admin/tickets/update/' . $ticket['id']) ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="4" placeholder="Type your update or comment here..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                            <small class="text-muted">You can upload multiple files (max 5MB each)</small>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_private" name="is_private" value="1">
                            <label class="form-check-label" for="is_private">Mark as private note (only visible to staff)</label>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Change Status (Optional)</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Keep Current Status</option>
                                        <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                                        <option value="assigned" <?= $ticket['status'] === 'assigned' ? 'selected' : '' ?>>Assigned</option>
                                        <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                        <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                                        <option value="cancelled" <?= $ticket['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="technician_id" class="form-label">Reassign Technician (Optional)</label>
                                    <select class="form-select" id="technician_id" name="technician_id">
                                        <option value="">Keep Current Assignment</option>
                                        <option value="0" <?= empty($ticket['technician_id']) ? 'selected' : '' ?>>Unassigned</option>
                                        <?php foreach ($technicians as $tech): ?>
                                            <option value="<?= $tech['id'] ?>" <?= $ticket['technician_id'] == $tech['id'] ? 'selected' : '' ?>>
                                                <?= esc($tech['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Ticket Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Ticket Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-<?= getStatusBadgeClass($ticket['status']) ?>">
                                <?= formatStatus($ticket['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Priority</th>
                        <td>
                            <span class="badge bg-<?= getPriorityBadgeClass($ticket['priority']) ?>">
                                <?= ucfirst($ticket['priority']) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= date('M d, Y \a\t h:i A', strtotime($ticket['created_at'])) ?></td>
                    </tr>
                    <?php if (!empty($ticket['updated_at']) && $ticket['updated_at'] != $ticket['created_at']): ?>
                        <tr>
                            <th>Last Updated</th>
                            <td><?= date('M d, Y \a\t h:i A', strtotime($ticket['updated_at'])) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($ticket['resolved_at'])): ?>
                        <tr>
                            <th>Resolved</th>
                            <td><?= date('M d, Y \a\t h:i A', strtotime($ticket['resolved_at'])) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($ticket['closed_at'])): ?>
                        <tr>
                            <th>Closed</th>
                            <td><?= date('M d, Y \a\t h:i A', strtotime($ticket['closed_at'])) ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <!-- Organization Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Organization</h5>
            </div>
            <div class="card-body">
            <?php 
                $organization = null;
                foreach ($organizations as $org) {
                    if ($org['id'] == $ticket['organization_id']) {
                        $organization = $org;
                        break;
                    }
                }
                ?>
                
                <?php if ($organization): ?>
                    <h5><?= esc($organization['name']) ?></h5>
                    
                    <?php if (!empty($organization['contact_name']) || !empty($organization['contact_email']) || !empty($organization['contact_phone'])): ?>
                        <div class="mt-3">
                            <h6>Contact Information</h6>
                            <ul class="list-unstyled">
                                <?php if (!empty($organization['contact_name'])): ?>
                                    <li><i class="fas fa-user me-2"></i> <?= esc($organization['contact_name']) ?></li>
                                <?php endif; ?>
                                
                                <?php if (!empty($organization['contact_email'])): ?>
                                    <li><i class="fas fa-envelope me-2"></i> <a href="mailto:<?= esc($organization['contact_email']) ?>"><?= esc($organization['contact_email']) ?></a></li>
                                <?php endif; ?>
                                
                                <?php if (!empty($organization['contact_phone'])): ?>
                                    <li><i class="fas fa-phone me-2"></i> <?= esc($organization['contact_phone']) ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="<?= site_url('admin/organizations/edit/' . $organization['id']) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-building me-1"></i> View Organization
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Organization not found or deleted.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Customer</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($ticket['customer_id']) && !empty($customer)): ?>
                    <h5><?= esc($customer['name']) ?></h5>
                    
                    <ul class="list-unstyled mt-3">
                        <?php if (!empty($customer['email'])): ?>
                            <li><i class="fas fa-envelope me-2"></i> <a href="mailto:<?= esc($customer['email']) ?>"><?= esc($customer['email']) ?></a></li>
                        <?php endif; ?>
                        
                        <?php if (!empty($customer['phone'])): ?>
                            <li><i class="fas fa-phone me-2"></i> <?= esc($customer['phone']) ?></li>
                        <?php endif; ?>
                        
                        <?php if (!empty($customer['address'])): ?>
                            <li><i class="fas fa-map-marker-alt me-2"></i> <?= esc($customer['address']) ?></li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="mt-3">
                        <a href="<?= site_url('admin/customers/edit/' . $customer['id']) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user me-1"></i> View Customer
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No customer assigned to this ticket.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Technician Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Assigned Technician</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($ticket['technician_id'])): ?>
                    <?php 
                    $technician = null;
                    foreach ($technicians as $tech) {
                        if ($tech['id'] == $ticket['technician_id']) {
                            $technician = $tech;
                            break;
                        }
                    }
                    ?>
                    
                    <?php if ($technician): ?>
                        <div class="d-flex align-items-center mb-3">
                            <?php if(!empty($technician['photo']) && file_exists(WRITEPATH . $technician['photo'])): ?>
                                <img src="<?= base_url(WRITEPATH . $technician['photo']) ?>" alt="<?= $technician['name'] ?>" class="rounded-circle me-3" width="48">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <h5 class="mb-0"><?= esc($technician['name']) ?></h5>
                                <?php if (!empty($technician['tech_id'])): ?>
                                    <small class="text-muted">ID: <?= esc($technician['tech_id']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <ul class="list-unstyled">
                            <?php if (!empty($technician['email'])): ?>
                                <li><i class="fas fa-envelope me-2"></i> <a href="mailto:<?= esc($technician['email']) ?>"><?= esc($technician['email']) ?></a></li>
                            <?php endif; ?>
                            
                            <?php if (!empty($technician['phone'])): ?>
                                <li><i class="fas fa-phone me-2"></i> <?= esc($technician['phone']) ?></li>
                            <?php endif; ?>
                        </ul>
                        
                        <div class="mt-3">
                            <a href="<?= site_url('admin/technicians/profile/' . $technician['id']) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-user me-1"></i> View Profile
                            </a>
                            <a href="<?= site_url('admin/tickets/unassign/' . $ticket['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to unassign this ticket?')">
                                <i class="fas fa-user-minus me-1"></i> Unassign
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Assigned technician not found or deleted.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <p><i class="fas fa-exclamation-circle me-2"></i> No technician assigned</p>
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#assignModal">
                            <i class="fas fa-user-plus me-1"></i> Assign Technician
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Assign Technician Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Assign Technician</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/tickets/assign/' . $ticket['id']) ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="technician_id_modal" class="form-label">Select Technician</label>
                        <select class="form-select" id="technician_id_modal" name="technician_id" required>
                            <option value="">Select Technician</option>
                            <?php foreach ($technicians as $tech): ?>
                                <option value="<?= $tech['id'] ?>" <?= $ticket['technician_id'] == $tech['id'] ? 'selected' : '' ?>>
                                    <?= esc($tech['name']) ?>
                                </option>
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

<!-- Change Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Change Ticket Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/tickets/update/' . $ticket['id']) ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status_modal" class="form-label">Select New Status</label>
                        <select class="form-select" id="status_modal" name="status" required>
                            <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                            <option value="assigned" <?= $ticket['status'] === 'assigned' ? 'selected' : '' ?>>Assigned</option>
                            <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                            <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                            <option value="cancelled" <?= $ticket['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment_modal" class="form-label">Add Comment</label>
                        <textarea class="form-control" id="comment_modal" name="comment" rows="3" required>Status changed to </textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_private_modal" name="is_private" value="1" checked>
                        <label class="form-check-label" for="is_private_modal">
                            Mark as private note
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Status change handler for the status modal
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status_modal');
    const commentTextarea = document.getElementById('comment_modal');
    
    statusSelect.addEventListener('change', function() {
        const statusText = this.options[this.selectedIndex].text;
        commentTextarea.value = `Status changed to ${statusText}`;
    });
});
</script>

<?= $this->endSection() ?>