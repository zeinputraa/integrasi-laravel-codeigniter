<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Library Management System' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
        }
        
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link i {
            width: 25px;
            text-align: center;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 1px solid #eaeaea;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .stat-card.primary { border-left: 4px solid var(--primary-color); }
        .stat-card.success { border-left: 4px solid var(--success-color); }
        .stat-card.warning { border-left: 4px solid var(--warning-color); }
        .stat-card.info { border-left: 4px solid #7209b7; }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table-custom thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .table-custom th {
            border: none;
            padding: 15px;
            font-weight: 500;
        }
        
        .table-custom td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f1f3f4;
        }
        
        .badge-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-available {
            background: #e7f7ef;
            color: #28a745;
        }
        
        .badge-borrowed {
            background: #ffeaea;
            color: #dc3545;
        }
        
        .form-custom {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .form-control-custom {
            border: 2px solid #eaeaea;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        
        .search-box {
            max-width: 400px;
        }
        
        .main-content {
            background: #f5f7fb;
            min-height: 100vh;
        }
        
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .logo-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
        }
        
        .action-btn {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 2px;
            transition: all 0.3s ease;
        }
        
        .theme-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="position-sticky pt-4">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <a href="<?= base_url('/') ?>" class="logo-brand">
                            <i class="fas fa-book-reader me-2"></i>
                            LibManager
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= current_url() == base_url('/dashboard') ? 'active' : '' ?>" href="<?= base_url('/dashboard') ?>">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(current_url(), '/books') !== false && current_url() != base_url('/books/create') ? 'active' : '' ?>" href="<?= base_url('/books') ?>">
                                <i class="fas fa-book me-2"></i>
                                Daftar Buku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= current_url() == base_url('/books/create') ? 'active' : '' ?>" href="<?= base_url('/books/create') ?>">
                                <i class="fas fa-plus-circle me-2"></i>
                                Tambah Buku
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Statistics Shortcut -->
                    <div class="mt-5 px-3">
                        <small class="text-white-50">STATISTIK CEPAT</small>
                        <div class="mt-2 text-white small">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Buku:</span>
                                <span class="fw-bold" id="quick-total">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tersedia:</span>
                                <span class="fw-bold" id="quick-available">-</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Dipinjam:</span>
                                <span class="fw-bold" id="quick-borrowed">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content p-0">
                <!-- Top Navigation -->
                <nav class="navbar navbar-custom navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 text-dark"><?= $title ?? 'Library Management' ?></h4>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <span class="theme-toggle me-3" onclick="toggleTheme()">
                                <i class="fas fa-moon"></i>
                            </span>
                            <span class="text-muted">
                                <i class="fas fa-user-circle me-2"></i>
                                Administrator
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Content Area -->
                <div class="container-fluid mt-4">
                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-1">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Main Content -->
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.querySelector('.theme-toggle i');
            
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                themeIcon.className = 'fas fa-moon';
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                themeIcon.className = 'fas fa-sun';
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Load quick statistics
        async function loadQuickStats() {
            try {
                const response = await fetch('<?= base_url('/books/statistics') ?>');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('quick-total').textContent = data.data.total_books;
                    document.getElementById('quick-available').textContent = data.data.available_books;
                    document.getElementById('quick-borrowed').textContent = data.data.borrowed_books;
                }
            } catch (error) {
                console.error('Error loading quick stats:', error);
            }
        }

        // Load stats when page loads
        document.addEventListener('DOMContentLoaded', loadQuickStats);
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>