<style>
    /* Responsive Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        overflow-y: auto;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            height: 100vh;
            transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            width: 280px;
            z-index: 1050;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar.show {
            left: 0;
        }

        /* Overlay ketika sidebar terbuka di mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
    }

    .nav-link {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .nav-link:hover {
        background-color: #f8f9fa !important;
    }

    .nav-link.active {
        background-color: #e7f1ff !important;
        color: #4e73df !important;
    }

    /* Tombol toggle untuk mobile */
    .navbar-toggler {
        display: none;
    }

    @media (max-width: 767.98px) {
        .navbar-toggler {
            display: block;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1060;
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 0.5rem 0.7rem;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            color: #667eea;
            cursor: pointer;
        }

        .navbar-toggler:hover {
            background: #667eea;
            color: white;
            transform: scale(1.05);
        }

        .navbar-toggler:active {
            transform: scale(0.95);
        }

        .navbar-toggler i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .navbar-toggler.active {
            background: #667eea;
            color: white;
        }

        .navbar-toggler.active i {
            transform: rotate(90deg);
        }
    }

    @media (min-width: 768px) {
        .navbar-toggler {
            display: none;
        }
    }
</style>

<!-- Mobile Toggle Button -->
<button class="navbar-toggler d-md-none" type="button" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar">
    <div class="position-sticky pt-3">
        
        <div class="d-flex align-items-center px-3 mb-4 mt-2 pb-3 border-bottom">
            <div class="me-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="fas fa-user-tie fa-lg"></i>
                </div>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-dark">Executive User</h6>
                <small class="text-muted">Administrator</small>
            </div>
        </div>

        <ul class="nav flex-column px-2">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='sales')?'active':'text-secondary'; ?>" href="index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard Sales
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='finance')?'active':'text-secondary'; ?>" href="finance.php">
                    <i class="fas fa-box"></i>
                    Tax
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='geo')?'active':'text-secondary'; ?>" href="geo.php">
                    <i class="fas fa-globe-asia"></i>
                    Geo Analysis
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='olap')?'active':'text-secondary'; ?>" href="olap.php">
                    <i class="fas fa-th"></i>
                    Mondrian OLAP
                </a>
            </li>
            
            <li class="nav-item mt-4 border-top pt-3">
                <a class="nav-link d-flex align-items-center gap-2 text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<script>
    // Toggle sidebar untuk mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                sidebarToggle.classList.toggle('active');
                
                // Prevent body scroll when sidebar is open
                if (sidebar.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                sidebarToggle.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Auto close sidebar saat klik link di mobile
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                        sidebarToggle.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            });

            // Handle resize - close sidebar if window becomes larger
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    sidebarToggle.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
    });
</script>