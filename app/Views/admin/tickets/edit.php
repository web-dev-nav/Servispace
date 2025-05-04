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

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('admin/tickets/update/' . $ticket['id']) ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <!-- Ticket Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ticket Details</h5>
                </div>
                <div class="card-body">
                <div class="mb-3">
                        <label for="title" class="form-label">Ticket Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $ticket['title']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="6" required><?= old('description', $ticket['description']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="low" <?= old('priority', $ticket['priority']) == 'low' ? 'selected' : '' ?>>Low</option>
                                <option value="medium" <?= old('priority', $ticket['priority']) == 'medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="high" <?= old('priority', $ticket['priority']) == 'high' ? 'selected' : '' ?>>High</option>
                                <option value="urgent" <?= old('priority', $ticket['priority']) == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" class="form-control" value="<?= formatStatus($ticket['status']) ?>" readonly>
                            <small class="text-muted">Status can only be changed from the ticket view page</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Parts Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Parts</h5>
                    <button type="button" class="btn btn-sm btn-primary add-part-btn">
                        <i class="fas fa-plus"></i> Add Part
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Manage parts required for this service call.
                    </div>
                    
                    <!-- Existing Parts -->
                    <div id="existing-parts-container">
                        <h6 class="mb-3">Existing Parts</h6>
                        <?php if(empty($parts)): ?>
                            <div class="text-muted mb-3">No parts have been added to this ticket yet.</div>
                        <?php else: ?>
                            <?php foreach($parts as $index => $part): ?>
                                <div class="part-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="mb-0">Part Information</h6>
                                        <button type="button" class="btn btn-sm btn-danger delete-part-btn" data-part-id="<?= $part['id'] ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    
                                    <input type="hidden" name="existing_part_id[]" value="<?= $part['id'] ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label">Part Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="existing_part_number[]" value="<?= esc($part['part_number']) ?>" required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label">Description</label>
                                            <input type="text" class="form-control" name="existing_part_description[]" value="<?= esc($part['description']) ?>">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="existing_part_quantity[]" min="1" value="<?= $part['quantity'] ?>">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Status</label>
                                            <input type="text" class="form-control" value="<?= formatPartStatus($part['status']) ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- New Parts -->
                    <div id="new-parts-container" class="mt-4">
                        <h6 class="mb-3">Add New Parts</h6>
                        <div class="no-parts-message">No new parts added yet.</div>
                    </div>
                    
                    <!-- Part Template (hidden) -->
                    <template id="part-template">
                        <div class="part-item border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0">New Part</h6>
                                <button type="button" class="btn btn-sm btn-danger remove-part-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Part Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="part_number[]" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="part_description[]">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control" name="part_quantity[]" min="1" value="1">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Organization and Customer -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Organization & Customer</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
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
                    
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="form-select" id="customer_id" name="customer_id">
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>" <?= old('customer_id', $ticket['customer_id']) == $customer['id'] ? 'selected' : '' ?>>
                                    <?= esc($customer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="new_customer_check">
                            <label class="form-check-label" for="new_customer_check">
                                Add New Customer
                            </label>
                        </div>
                    </div>
                    
                    <!-- New Customer Form (initially hidden) -->
                    <div id="new-customer-form" style="display: none;">
                        <input type="hidden" name="new_customer" id="new_customer" value="0">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name">
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email">
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Address</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Technician Assignment -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Technician Assignment</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="technician_id" class="form-label">Assigned Technician</label>
                        <select class="form-select" id="technician_id" name="technician_id">
                            <option value="">Unassigned</option>
                            <?php foreach ($technicians as $tech): ?>
                                <option value="<?= $tech['id'] ?>" <?= old('technician_id', $ticket['technician_id']) == $tech['id'] ? 'selected' : '' ?>>
                                    <?= esc($tech['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text">
                            <?php if (!empty($ticket['technician_id'])): ?>
                                Reassigning will notify the new technician.
                            <?php else: ?>
                                Assigning a technician will change the ticket status to "Assigned".
                            <?php endif; ?>
                        </small>
                    </div>
                    
                    <?php if (!empty($ticket['technician_id']) && !empty($ticket['assigned_at'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Assigned On</label>
                            <input type="text" class="form-control" value="<?= date('F j, Y \a\t g:i a', strtotime($ticket['assigned_at'])) ?>" readonly>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($ticket['appointment_date'])): ?>
                        <div class="alert alert-info">
                            <h6 class="mb-1"><i class="fas fa-calendar-alt me-2"></i> Appointment Scheduled</h6>
                            <p class="mb-0">
                                <strong>Date:</strong> <?= date('F j, Y', strtotime($ticket['appointment_date'])) ?><br>
                                <strong>Time:</strong> <?= date('g:i a', strtotime($ticket['appointment_time'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i> Update Ticket
                </button>
                <a href="<?= site_url('admin/tickets/view/' . $ticket['id']) ?>" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Organization change handler - load customers
    const organizationSelect = document.getElementById('organization_id');
    const customerSelect = document.getElementById('customer_id');
    
    organizationSelect.addEventListener('change', function() {
        const organizationId = this.value;
        const currentCustomerId = '<?= $ticket['customer_id'] ?? '' ?>';
        
        customerSelect.innerHTML = '<option value="">Select Customer</option>';
        
        if (organizationId) {
            customerSelect.disabled = true;
            
            fetch(`<?= site_url('admin/tickets/get-customers-by-organization') ?>?organization_id=${organizationId}`)
                .then(response => response.json())
                .then(data => {
                    customerSelect.disabled = false;
                    
                    if (data.customers && data.customers.length > 0) {
                        data.customers.forEach(customer => {
                            const option = document.createElement('option');
                            option.value = customer.id;
                            option.textContent = customer.name;
                            
                            if (customer.id == currentCustomerId) {
                                option.selected = true;
                            }
                            
                            customerSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'No customers found for this organization';
                        customerSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error fetching customers:', error);
                    customerSelect.disabled = false;
                });
        } else {
            customerSelect.disabled = true;
        }
    });
    
    // Load initial customers if organization is selected
    if (organizationSelect.value) {
        // We don't need to trigger this if customers are already loaded in the PHP
        if (customerSelect.options.length <= 1) {
            const event = new Event('change');
            organizationSelect.dispatchEvent(event);
        }
    }
    
    // New customer checkbox handler
    const newCustomerCheck = document.getElementById('new_customer_check');
    const newCustomerForm = document.getElementById('new-customer-form');
    const newCustomerInput = document.getElementById('new_customer');
    const customerNameInput = document.getElementById('customer_name');
    
    newCustomerCheck.addEventListener('change', function() {
        if (this.checked) {
            newCustomerForm.style.display = 'block';
            customerSelect.disabled = true;
            newCustomerInput.value = '1';
            customerNameInput.setAttribute('required', 'required');
        } else {
            newCustomerForm.style.display = 'none';
            customerSelect.disabled = false;
            newCustomerInput.value = '0';
            customerNameInput.removeAttribute('required');
        }
    });
    
    // Parts management for new parts
    const newPartsContainer = document.getElementById('new-parts-container');
    const partTemplate = document.getElementById('part-template');
    const addPartBtn = document.querySelector('.add-part-btn');
    const noPartsMessage = document.querySelector('.no-parts-message');
    
    addPartBtn.addEventListener('click', function() {
        const newPart = document.importNode(partTemplate.content, true);
        
        // Set up the remove button functionality
        const removeBtn = newPart.querySelector('.remove-part-btn');
        removeBtn.addEventListener('click', function() {
            this.closest('.part-item').remove();
            
            // Show "no parts" message if no parts remain
            if (newPartsContainer.querySelectorAll('.part-item').length === 0) {
                noPartsMessage.style.display = 'block';
            }
        });
        
        newPartsContainer.appendChild(newPart);
        
        // Hide "no parts" message
        noPartsMessage.style.display = 'none';
    });
    
    // Delete existing part
    const deletePartBtns = document.querySelectorAll('.delete-part-btn');
    deletePartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const partItem = this.closest('.part-item');
            const partId = this.getAttribute('data-part-id');
            
            if (confirm('Are you sure you want to delete this part?')) {
                fetch(`<?= site_url('admin/tickets/delete-part/' . $ticket['id']) ?>/${partId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        partItem.remove();
                        
                        // Show message if no parts left
                        if (document.querySelectorAll('#existing-parts-container .part-item').length === 0) {
                            const noPartsMsg = document.createElement('div');
                            noPartsMsg.className = 'text-muted mb-3';
                            noPartsMsg.textContent = 'No parts have been added to this ticket yet.';
                            document.getElementById('existing-parts-container').appendChild(noPartsMsg);
                        }
                    } else {
                        alert('Error deleting part: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the part.');
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>