<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Ticket</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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

<form action="<?= site_url('admin/tickets/store') ?>" method="post" enctype="multipart/form-data">
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
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
                        <small class="text-muted">Provide a clear, descriptive title for the ticket</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="6" required><?= old('description') ?></textarea>
                        <small class="text-muted">Describe the issue in detail</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low" <?= old('priority') == 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= old('priority') == 'medium' || empty(old('priority')) ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= old('priority') == 'high' ? 'selected' : '' ?>>High</option>
                            <option value="urgent" <?= old('priority') == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <small class="text-muted">You can upload multiple files (max 5MB each)</small>
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
                        <i class="fas fa-info-circle me-2"></i> Add parts that are required for this service call.
                    </div>
                    
                    <div id="parts-container">
                        <!-- Parts will be added dynamically here -->
                        <div class="no-parts-message">No parts added yet.</div>
                    </div>
                    
                    <!-- Part Template (hidden) -->
                    <template id="part-template">
                        <div class="part-item border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0">Part Information</h6>
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
                                <option value="<?= $org['id'] ?>" <?= old('organization_id') == $org['id'] ? 'selected' : '' ?>>
                                    <?= esc($org['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="form-select" id="customer_id" name="customer_id" disabled>
                            <option value="">Select Customer</option>
                        </select>
                        <div class="form-text">First select an organization to see its customers</div>
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
                        <label for="technician_id" class="form-label">Assign Technician (Optional)</label>
                        <select class="form-select" id="technician_id" name="technician_id">
                            <option value="">Unassigned</option>
                            <?php foreach ($technicians as $tech): ?>
                                <option value="<?= $tech['id'] ?>" <?= old('technician_id') == $tech['id'] ? 'selected' : '' ?>>
                                    <?= esc($tech['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Leave unassigned if no technician is available yet</small>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i> Create Ticket
                </button>
                <a href="<?= site_url('admin/tickets') ?>" class="btn btn-secondary">
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
        customerSelect.innerHTML = '<option value="">Select Customer</option>';
        
        if (organizationId) {
            customerSelect.disabled = true;
            
            fetch(`<?= site_url('admin/tickets/get-customers-by-organization') ?>?organization_id=${organizationId}`)
                .then(response => response.json())
                .then(data => {
                    customerSelect.disabled = false;
                    customerSelect.removeAttribute('disabled');
                    
                    if (data.customers && data.customers.length > 0) {
                        data.customers.forEach(customer => {
                            const option = document.createElement('option');
                            option.value = customer.id;
                            option.textContent = customer.name;
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
            customerSelect.disabled = organizationSelect.value ? false : true;
            newCustomerInput.value = '0';
            customerNameInput.removeAttribute('required');
        }
    });
    
    // Parts management
    const partsContainer = document.getElementById('parts-container');
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
            if (partsContainer.querySelectorAll('.part-item').length === 0) {
                noPartsMessage.style.display = 'block';
            }
        });
        
        partsContainer.appendChild(newPart);
        
        // Hide "no parts" message
        noPartsMessage.style.display = 'none';
    });
});
</script>
<?= $this->endSection() ?>