<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> | Ticket Management System</title>
    <!-- Zephyr Bootswatch Theme -->
    <link rel="stylesheet" href="https://bootswatch.com/5/zephyr/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
            background: #f8f9fa;
        }
        
        .navbar {
            padding: 1rem;
            background-color: #506cd0;
        }
        
        .navbar-brand {
            font-weight: bold;
        }
        
        /* Sidebar */
        #sidebar-wrapper {
            min-height: calc(100vh - 56px);
            width: 250px;
            background-color: #506cd0;
            position: fixed;
            left: 0;
            top: 56px;
            z-index: 1000;
            color: white;
        }
        
        #sidebar-wrapper .list-group {
            width: 100%;
        }
        
        #sidebar-wrapper .list-group-item {
            border: none;
            padding: 12px 20px;
            background-color: transparent;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 0;
        }
        
        #sidebar-wrapper .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        #sidebar-wrapper .list-group-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        #sidebar-wrapper .list-group-item i {
            margin-right: 10px;
        }
        
        /* Page content */
        #page-content-wrapper {
            min-width: 100vw;
            padding-left: 250px; /* Width of the sidebar */
            padding-top: 20px;
        }
        
        /* Card styling */
        .card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -250px;
                transition: margin .25s ease-out;
            }
            
            #sidebar-wrapper.active {
                margin-left: 0;
            }
            
            #page-content-wrapper {
                min-width: 0;
                width: 100%;
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Top navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('admin/dashboard') ?>">Ticket System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="list-group list-group-flush">
                <a href="<?= site_url('admin/dashboard') ?>" class="list-group-item list-group-item-action <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= site_url('admin/organizations') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'admin/organizations') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-building"></i> Organizations
                </a>
                <a href="<?= site_url('admin/technicians') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'admin/technicians') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-users-cog"></i> Technicians
                </a>
                <a href="<?= site_url('admin/tickets') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'admin/tickets') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-ticket-alt"></i> Tickets
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        // Toggle the side navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.navbar-toggler');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('#sidebar-wrapper').classList.toggle('active');
                    document.querySelector('#page-content-wrapper').classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>