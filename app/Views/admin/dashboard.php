<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-calendar-day"></i> Today
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-calendar-week"></i> This Week
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Tickets</h6>
                        <h2 class="card-text"><?= $total_tickets ?></h2>
                    </div>
                    <i class="fas fa-ticket-alt fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Open Tickets</h6>
                        <h2 class="card-text"><?= $open_tickets ?></h2>
                    </div>
                    <i class="fas fa-door-open fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Assigned Tickets</h6>
                        <h2 class="card-text"><?= $assigned_tickets ?></h2>
                    </div>
                    <i class="fas fa-tasks fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Completed Tickets</h6>
                        <h2 class="card-text"><?= $completed_tickets ?></h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building mr-1"></i> Organizations
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0"><?= $total_organizations ?></h3>
                        <p class="text-muted">Total Organizations</p>
                    </div>
                    <a href="<?= site_url('admin/organizations') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users-cog mr-1"></i> Technicians
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0"><?= $total_technicians ?></h3>
                        <p class="text-muted">Total Technicians</p>
                    </div>
                    <a href="<?= site_url('admin/technicians') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-clipboard-list mr-1"></i> Recent Tickets
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_tickets)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No tickets found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_tickets as $ticket): ?>
                            <tr>
                                <td><?= $ticket['id'] ?></td>
                                <td><?= $ticket['title'] ?></td>
                                <td><?= $ticket['organization_name'] ?? $ticket['organization_id'] ?></td>
                                <td>
                                    <span class="badge badge-<?= getStatusBadgeClass($ticket['status']) ?>">
                                        <?= ucfirst($ticket['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
                                <td>
                                    <a href="<?= site_url('admin/tickets/view/' . $ticket['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="<?= site_url('admin/tickets') ?>" class="btn btn-outline-primary">View All Tickets</a>
    </div>
</div>
<?= $this->endSection() ?>

