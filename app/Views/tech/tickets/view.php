<?= $this->extend('layouts/tech') ?>

<?= $this->section('content') ?>
<div class="page-title d-flex justify-content-between align-items-center mb-3">
    <h1>Ticket #<?= $ticket['id'] ?></h1>
    <div class="d-flex">
        <a href="<?= site_url('tech/tickets') ?>" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Tickets
        </a>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog"></i> Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown">
                <?php if(empty($ticket['appointment_date'])): ?>
                    <li><a class="dropdown-item" href="#scheduleModal" data-bs-toggle="modal" data-bs-target="#scheduleModal">Schedule Appointment</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="#scheduleModal" data-bs-toggle="modal" data-bs-target="#scheduleModal">Reschedule Appointment</a></li>
                <?php endif; ?>
                
                <?php if($ticket['status'] == 'assigned' || $ticket['status'] == 'scheduled'): ?>
                    <li><a class="dropdown-item" href="#startServiceModal" data-bs-toggle="modal" data-bs-target="#startServiceModal">Start Service</a></li>
                <?php endif; ?>
                
                <?php if($ticket['status'] == 'in_progress'): ?>
                    <li><a class="dropdown-item" href="#completeServiceModal" data-bs-toggle="modal" data-bs-target="#completeServiceModal">Complete Service</a></li>
                <?php endif; ?>
                
                <li><a class="dropdown-item" href="#updateModal" data-bs-toggle="modal" data-bs-target="#updateModal">Add Update</a></li>
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
    <!-- Left column - Ticket details -->
    <div class="col-lg-8">
        <!-- Main ticket info -->
        <div class="content-wrapper mb-4">
            <div class="d-flex justify-content-between mb-3">
                <h2 class="h4 mb-0"><?= esc($ticket['title']) ?></h2>
                <span class="badge bg-<?= getStatusBadgeClass($ticket['status']) ?> fs-6">
                    <?= formatStatus($ticket['status']) ?>
                </span>
            </div>
            
            <div class="ticket-description mb-3">
                <?= nl2br(esc($ticket['description'])) ?>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Priority:</strong> <span class="badge bg-<?= getPriorityBadgeClass($ticket['priority']) ?>"><?= ucfirst($ticket['priority']) ?></span></p>
                    <p class="mb-1"><strong>Created:</strong> <?= date('M d, Y', strtotime($ticket['created_at'])) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Organization:</strong> <?= esc($ticket['organization_name']) ?></p>
                    <p class="mb-1"><strong>Assigned:</strong> 
                        <?= !empty($ticket['assigned_at']) ? date('M d, Y', strtotime($ticket['assigned_at'])) : 'Not yet assigned' ?>
                    </p>
                </div>
            </div>
            
            <?php if(!empty($ticket['appointment_date'])): ?>
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Appointment Scheduled</h5>
                            <p class="mb-0">
                                <strong>Date:</strong> <?= date('F d, Y', strtotime($ticket['appointment_date'])) ?><br>
                                <strong>Time:</strong> <?= date('h:i A', strtotime($ticket['appointment_time'])) ?><br>
                                <?php if(!empty($ticket['appointment_notes'])): ?>
                                    <strong>Notes:</strong> <?= esc($ticket['appointment_notes']) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Parts Information -->
        <div class="content-wrapper mb-4">
            <h2 class="h5 mb-3">Parts Information</h2>
            
            <?php if(empty($parts)): ?>
                <div class="alert alert-info">No parts associated with this ticket.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($parts as $part): ?>
                                <tr>
                                    <td><?= esc($part['part_number']) ?></td>
                                    <td><?= esc($part['description']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= getPartStatusClass($part['status']) ?>">
                                            <?= formatPartStatus($part['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary update-part-btn" 
                                            data-part-id="<?= $part['id'] ?>"
                                            data-part-number="<?= esc($part['part_number']) ?>"
                                            data-part-description="<?= esc($part['description']) ?>"
                                            data-part-status="<?= $part['status'] ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updatePartModal">
                                            Update Status
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Updates and Comments -->
        <div class="content-wrapper">
            <h2 class="h5 mb-3">Updates & Comments</h2>
            
            <div class="ticket-updates">
                <?php if(empty($updates)): ?>
                    <div class="alert alert-info">No updates yet.</div>
                <?php else: ?>
                    <?php foreach($updates as $update): ?>
                        <div class="card mb-3">
                            <div class="card-header bg-light d-flex justify-content-between">
                                <div>
                                    <strong>
                                        <?php if($update['user_type'] == 'technician' && $update['user_id'] == session()->get('tech_id')): ?>
                                            You
                                        <?php else: ?>
                                            <?= $update['user_type'] == 'admin' ? 'Admin' : ($update['user_type'] == 'technician' ? 'Technician' : 'Customer') ?>
                                        <?php endif; ?>
                                    </strong>
                                    <span class="text-muted ms-2"><?= date('M d, Y \a\t h:i A', strtotime($update['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="update-comment">
                                    <?= nl2br(esc($update['comment'])) ?>
                                </div>
                                
                                <?php if(!empty($update['attachments'])): ?>
                                    <div class="mt-3 border-top pt-3">
                                        <h6><i class="fas fa-paperclip"></i> Attachments</h6>
                                        <div class="d-flex flex-wrap">
                                            <?php foreach($update['attachments'] as $attachment): ?>
                                                <a href="<?= site_url('tech/tickets/attachment/' . $attachment['id']) ?>" class="me-3 mb-2 btn btn-sm btn-outline-secondary" target="_blank">
                                                    <i class="<?= getFileIcon($attachment['file_type']) ?> me-1"></i>
                                                    <?= esc($attachment['file_name']) ?>
                                                </a>
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
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal">
                    <i class="fas fa-plus-circle"></i> Add Update
                </button>
            </div>
        </div>
    </div>
    
    <!-- Right column - Customer info and quick actions -->
    <div class="col-lg-4">
        <!-- Customer Information -->
        <div class="content-wrapper mb-4">
            <h2 class="h5 mb-3">Customer Information</h2>
            <div class="customer-details">
                <p><strong>Name:</strong> <?= esc($customer['name'] ?? 'Not assigned') ?></p>
                <p><strong>Phone:</strong> <?php if(!empty($customer['phone'])): ?><a href="tel:<?= esc($customer['phone']) ?>"><?= esc($customer['phone']) ?></a><?php else: ?>Not available<?php endif; ?></p>
                <p><strong>Email:</strong> <?php if(!empty($customer['email'])): ?><a href="mailto:<?= esc($customer['email']) ?>"><?= esc($customer['email']) ?></a><?php else: ?>Not available<?php endif; ?></p>
                <p><strong>Address:</strong><br>
                <?= !empty($customer['address']) ? nl2br(esc($customer['address'])) : 'Not available' ?></p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="content-wrapper mb-4">
            <h2 class="h5 mb-3">Quick Actions</h2>
            <div class="d-grid gap-2">
                <a href="tel:<?= esc($customer['phone']) ?>" class="btn btn-outline-primary">
                    <i class="fas fa-phone-alt me-2"></i> Call Customer
                </a>
                <a href="mailto:<?= esc($customer['email']) ?>" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2"></i> Email Customer
                </a>
                <a href="https://maps.google.com/?q=<?= urlencode($customer['address']) ?>" class="btn btn-outline-primary" target="_blank">
                    <i class="fas fa-map-marker-alt me-2"></i> Get Directions
                </a>
                
                <?php if(empty($ticket['appointment_date'])): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                        <i class="fas fa-calendar-alt me-2"></i> Schedule Appointment
                    </button>
                <?php else: ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                        <i class="fas fa-calendar-alt me-2"></i> Reschedule Appointment
                    </button>
                <?php endif; ?>
                
                <?php if($ticket['status'] == 'assigned' || $ticket['status'] == 'scheduled' || $ticket['status'] == 'in_progress' || $ticket['status'] == 'onsite'): ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#startServiceModal">
                        <i class="fas fa-clipboard-check me-2"></i> Update Status
                    </button>
                <?php endif; ?>
                
                <?php if($ticket['status'] == 'in_progress' || $ticket['status'] == 'onsite'): ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#completeServiceModal">
                        <i class="fas fa-check-circle me-2"></i> Complete Service
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Appointment Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">
                    <?= empty($ticket['appointment_date']) ? 'Schedule Appointment' : 'Reschedule Appointment' ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('tech/tickets/save-schedule/' . $ticket['id']) ?>" method="post">
                <div class="modal-body">
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
                        <label for="appointment_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="appointment_notes" name="appointment_notes" rows="3"><?= !empty($ticket['appointment_notes']) ? esc($ticket['appointment_notes']) : '' ?></textarea>
                        <small class="text-muted">Include any special instructions or information for the customer.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <?= empty($ticket['appointment_date']) ? 'Schedule Appointment' : 'Update Appointment' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Start Service Modal -->
<div class="modal fade" id="startServiceModal" tabindex="-1" aria-labelledby="startServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startServiceModalLabel">Update Service Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('tech/tickets/start/' . $ticket['id']) ?>" method="post">
                <div class="modal-body">
                    <p>Select the current status of this service ticket:</p>
                    <div class="mb-3">
                        <label for="service_status" class="form-label">Status Code</label>
                        <select class="form-select" id="service_status" name="service_status" required>
                            <option value="">Select Status Code</option>
                            <option value="enroute">Enroute to customer location</option>
                            <option value="onsite">Onsite</option>
                            <option value="completed">Successfully completed the job</option>
                            <option value="parts_required">Additional parts required</option>
                            <option value="not_resolved">Issue not resolved</option>
                            <option value="deport">Deport to repair center</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="start_notes" name="start_notes" rows="3"></textarea>
                        <small class="text-muted">Additional details about the current status.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Service Modal -->
<div class="modal fade" id="completeServiceModal" tabindex="-1" aria-labelledby="completeServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeServiceModalLabel">Complete Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('tech/tickets/complete/' . $ticket['id']) ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Please provide details about the service completion.
                    </div>
                    
                    <div class="mb-3">
                        <label for="completion_code" class="form-label">Completion Code</label>
                        <select class="form-select" id="completion_code" name="completion_code" required>
                            <option value="">Select Completion Code</option>
                            <option value="COMPLETED">COMPLETED - Service completed successfully</option>
                            <option value="PARTIAL">PARTIAL - Service partially completed</option>
                            <option value="PARTS_MISSING">PARTS_MISSING - Required parts were missing</option>
                            <option value="PARTS_DEFECTIVE">PARTS_DEFECTIVE - Parts were defective</option>
                            <option value="WRONG_PARTS">WRONG_PARTS - Wrong parts received</option>
                            <option value="NO_ACCESS">NO_ACCESS - Could not access service location</option>
                            <option value="CUSTOMER_CANCEL">CUSTOMER_CANCEL - Customer cancelled on site</option>
                            <option value="OTHER">OTHER - Other reason (specify in notes)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parts_update" class="form-label">Parts Status Update</label>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Part Number</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($parts as $index => $part): ?>
                                        <input type="hidden" name="part_ids[]" value="<?= $part['id'] ?>">
                                        <tr>
                                            <td><?= esc($part['part_number']) ?> - <?= esc($part['description']) ?></td>
                                            <td>
                                                <select class="form-select" name="part_status[]" required>
                                                    <option value="unused" <?= $part['status'] == 'unused' ? 'selected' : '' ?>>Unused</option>
                                                    <option value="installed" <?= $part['status'] == 'installed' ? 'selected' : '' ?>>Installed</option>
                                                    <option value="defective" <?= $part['status'] == 'defective' ? 'selected' : '' ?>>Defective</option>
                                                    <option value="wrong_part" <?= $part['status'] == 'wrong_part' ? 'selected' : '' ?>>Wrong Part</option>
                                                    <option value="returned" <?= $part['status'] == 'returned' ? 'selected' : '' ?>>Returned</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="part_notes[]" placeholder="Optional notes">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="completion_notes" class="form-label">Completion Notes</label>
                        <textarea class="form-control" id="completion_notes" name="completion_notes" rows="4" required></textarea>
                        <small class="text-muted">Provide details about the service performed and outcomes.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments (Optional)</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <small class="text-muted">Upload photos or documents related to the service completion.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Customer Signature (Required)</label>
                        <div class="border rounded p-3 mb-2">
                            <div id="signature-pad" class="signature-pad">
                                <canvas width="100%" height="200px" style="border: 1px solid #ddd; width: 100%;"></canvas>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-secondary" id="clear-signature">Clear Signature</button>
                            </div>
                        </div>
                        <input type="hidden" name="customer_signature" id="customer_signature" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Complete Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Part Modal -->
<div class="modal fade" id="updatePartModal" tabindex="-1" aria-labelledby="updatePartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePartModalLabel">Update Part Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('tech/tickets/update-part/' . $ticket['id']) ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" id="part_id" name="part_id" value="">
                    <div class="mb-3">
                        <label class="form-label">Part Details</label>
                        <div id="part_details" class="form-control-plaintext"></div>
                    </div>
                    <div class="mb-3">
                        <label for="part_status" class="form-label">Part Status</label>
                        <select class="form-select" id="part_status" name="part_status" required>
                            <option value="unused">Unused</option>
                            <option value="installed">Installed</option>
                            <option value="defective">Defective</option>
                            <option value="wrong_part">Wrong Part</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="part_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="part_notes" name="part_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Part</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Add Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('tech/tickets/add-update/' . $ticket['id']) ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments (Optional)</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize signature pad
    const canvas = document.querySelector("#signature-pad canvas");
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)'
    });
    
    // Clear signature button
    document.getElementById('clear-signature').addEventListener('click', function() {
        signaturePad.clear();
    });
    
    // Save signature data to hidden input when submitting form
    document.querySelector('#completeServiceModal form').addEventListener('submit', function(event) {
        if (signaturePad.isEmpty()) {
            alert('Please provide customer signature before completing the service.');
            event.preventDefault();
            return false;
        }
        
        const signatureData = signaturePad.toDataURL();
        document.getElementById('customer_signature').value = signatureData;
    });
    
    // Update part modal
    const updatePartBtns = document.querySelectorAll('.update-part-btn');
    updatePartBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const partId = this.getAttribute('data-part-id');
            const partNumber = this.getAttribute('data-part-number');
            const partDescription = this.getAttribute('data-part-description');
            const partStatus = this.getAttribute('data-part-status');
            
            document.getElementById('part_id').value = partId;
            document.getElementById('part_details').textContent = partNumber + ' - ' + partDescription;
            document.getElementById('part_status').value = partStatus;
        });
    });
});
</script>
<?= $this->endSection() ?>