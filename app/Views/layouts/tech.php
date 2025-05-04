<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Technician Portal' ?> | Servispace</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom styles -->
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --info-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            min-height: 100vh;
            padding-top: 60px; /* Height of the header */
        }
        
        /* Header */
        .header {
            background-color: #fff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            height: 60px;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo-container img {
            height: 40px;
            margin-right: 10px;
        }
        
        .logo-text {
            font-weight: 600;
            font-size: 18px;
            color: var(--primary-color);
        }
        
        /* Sidebar */
        .sidebar {
            background-color: #fff;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            height: 100%;
            position: fixed;
            top: 60px; /* Start below header */
            left: 0;
            width: 250px;
            padding-top: 20px;
            transition: all 0.3s;
            z-index: 900;
            overflow-y: auto;
        }
        
        .sidebar-collapsed {
            left: -250px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #666;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-menu a.active {
            background-color: rgba(52, 152, 219, 0.15);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }
        
        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
            min-height: calc(100vh - 60px);
        }
        
        .main-content-expanded {
            margin-left: 0;
        }
        
        /* Content wrapper */
        .content-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        /* Page title */
        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark-color);
        }
        
        /* Cards */
        .stats-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stats-card-value {
            font-size: 28px;
            font-weight: 700;
        }
        
        .stats-card-title {
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stats-card-icon {
            font-size: 24px;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        
        .icon-primary {
            background-color: var(--primary-color);
        }
        
        .icon-success {
            background-color: var(--success-color);
        }
        
        .icon-warning {
            background-color: var(--warning-color);
        }
        
        .icon-info {
            background-color: var(--info-color);
        }
        
        /* Tables */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .custom-table th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .custom-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        
        .custom-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .custom-table tr:last-child td {
            border-bottom: none;
        }
        
        /* Toggle sidebar button */
        .toggle-sidebar {
            background-color: transparent;
            border: none;
            font-size: 20px;
            color: var(--primary-color);
            cursor: pointer;
            display: none;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -250px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .toggle-sidebar {
                display: block !important;
            }
            
            .page-title {
                font-size: 20px;
            }
        }
        
        /* Fix for mobile display */
        @media (max-width: 576px) {
            .stats-card-value {
                font-size: 24px;
            }
            
            .stats-card-title {
                font-size: 12px;
            }
            
            .stats-card-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <button class="toggle-sidebar" type="button" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="col">
                    <div class="logo-container">
                        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Servispace Logo">
                        <span class="logo-text d-none d-sm-inline">Technician Portal</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> 
                            <span class="d-none d-md-inline"><?= session()->get('tech_name') ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= site_url('tech/profile') ?>"><i class="fas fa-user-edit me-2"></i> My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('tech/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="<?= site_url('tech/dashboard') ?>" class="<?= uri_string() == 'tech/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= site_url('tech/tickets') ?>" class="<?= strpos(uri_string(), 'tech/tickets') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-ticket-alt"></i> My Tickets
                </a>
            </li>
            <li>
                <a href="<?= site_url('tech/profile') ?>" class="<?= strpos(uri_string(), 'tech/profile') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main content -->
    <main class="main-content" id="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar on mobile
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('main-content-expanded');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992 && 
                    !sidebar.contains(event.target) && 
                    !toggleBtn.contains(event.target) && 
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function(alert) {
                    alert.classList.add('fade');
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                });
            }, 5000);
            
            // Adjust sidebar and main content height
            function adjustHeights() {
                const windowHeight = window.innerHeight;
                const headerHeight = 60; // Height of the header
                sidebar.style.height = (windowHeight - headerHeight) + 'px';
            }
            
            // Run on load and resize
            adjustHeights();
            window.addEventListener('resize', adjustHeights);
        });
    </script>
</body>
</html>