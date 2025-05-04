<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Organization</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('admin/organizations') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
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

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="organizationTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Organization Details</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">Documents</button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="organizationTabContent">
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                <form action="<?= site_url('admin/organizations/update/' . $organization['id']) ?>" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $organization['name']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', $organization['is_active']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3 border-bottom pb-2">Contact Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_name" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?= old('contact_name', $organization['contact_name']) ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= old('contact_email', $organization['contact_email']) ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?= old('contact_phone', $organization['contact_phone']) ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= old('address', $organization['address']) ?></textarea>
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3 border-bottom pb-2">Support Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="support_email" class="form-label">Support Email</label>
                            <input type="email" class="form-control" id="support_email" name="support_email" value="<?= old('support_email', $organization['support_email']) ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="support_phone" class="form-label">Support Phone</label>
                            <input type="text" class="form-control" id="support_phone" name="support_phone" value="<?= old('support_phone', $organization['support_phone']) ?>">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= old('description', $organization['description']) ?></textarea>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Update Organization</button>
                    </div>
                </form>
            </div>
            
            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Upload New Document</h5>
                            </div>
                            <div class="card-body">
                                    <form action="<?= site_url('admin/organizations/upload-document/' . $organization['id']) ?>" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="document" class="form-label">Select Document</label>
                                            <input type="file" class="form-control" id="document" name="document" required>
                                            <small class="text-muted">Max file size: 5MB</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="description" class="form-label">Document Description</label>
                                            <input type="text" class="form-control" id="description" name="description" placeholder="Enter a description for this document">
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-primary">Upload Document</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Organization Documents</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Type</th>
                                                <th>Size</th>
                                                <th>Description</th>
                                                <th>Uploaded</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(empty($documents)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No documents uploaded yet</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach($documents as $document): ?>
                                                    <tr>
                                                        <td><?= $document['file_name'] ?></td>
                                                        <td><?= $document['file_type'] ?></td>
                                                        <td><?= formatBytes($document['file_size']) ?></td>
                                                        <td><?= $document['description'] ?? '-' ?></td>
                                                        <td><?= date('M d, Y', strtotime($document['created_at'])) ?></td>
                                                        <td>
                                                         <!-- For each document in your listing -->
                                                            <div class="btn-group w-100">
                                                                <a href="<?= site_url('admin/organizations/view-document/' . $organization['id'] . '/' . $document['id']) ?>" class="btn btn-primary" target="_blank">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                                <a href="<?= base_url(WRITEPATH . $document['file_path']) ?>" class="btn btn-outline-primary" download>
                                                                    <i class="fas fa-download"></i> Download
                                                                </a>
                                                                <a href="<?= site_url('admin/organizations/delete-document/' . $organization['id'] . '/' . $document['id']) ?>" class="btn btn-outline-danger delete-doc-btn">
                                                                    <i class="fas fa-trash"></i> Delete
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
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

