<style>
    /* ===== CSS VARIABLES FOR DARK MODE ===== */
    :root {
        --bg-primary: #f3f4f6;
        --bg-secondary: #ffffff;
        --bg-card: #ffffff;
        --text-primary: #1a202c;
        --text-secondary: #4b5563;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        --sidebar-bg: #ffffff;
        --nav-hover: #f8f9fa;
        --nav-active-bg: #e7f1ff;
        --nav-active-text: #4e73df;
    }

    body.dark-mode {
        --bg-primary: #0f172a;
        --bg-secondary: #1e293b;
        --bg-card: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #cbd5e1;
        --text-muted: #94a3b8;
        --border-color: #334155;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        --sidebar-bg: #1e293b;
        --nav-hover: #334155;
        --nav-active-bg: #3b82f6;
        --nav-active-text: #ffffff;
    }

    /* Smooth transition untuk semua color changes */
    body, .sidebar, .nav-link, main, .card, .chart-container {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
    }

    /* Responsive Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 0;
        background-color: var(--sidebar-bg);
        box-shadow: inset -1px 0 0 var(--border-color);
        overflow-y: auto;
    }

    /* Dark mode sidebar improvements */
    body.dark-mode .sidebar {
        border-right: 1px solid var(--border-color);
    }

    body.dark-mode .sidebar.bg-white {
        background-color: var(--sidebar-bg) !important;
    }

    body.dark-mode .border-bottom {
        border-color: var(--border-color) !important;
    }

    body.dark-mode .text-dark {
        color: var(--text-primary) !important;
    }

    body.dark-mode .text-muted, body.dark-mode small {
        color: var(--text-muted) !important;
    }

    body.dark-mode .text-secondary {
        color: var(--text-secondary) !important;
    }

    body.dark-mode .bg-primary {
        background-color: #3b82f6 !important;
    }

    body.dark-mode .nav-link.text-secondary {
        color: var(--text-secondary) !important;
    }

    body.dark-mode .nav-link.text-secondary:hover {
        color: var(--text-primary) !important;
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
        color: var(--text-secondary);
    }

    .nav-link:hover {
        background-color: var(--nav-hover) !important;
        color: var(--text-primary);
    }

    .nav-link.active {
        background-color: var(--nav-active-bg) !important;
        color: var(--nav-active-text) !important;
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
            background: var(--bg-secondary);
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

    /* ===== DARK MODE TOGGLE BUTTON (Floating Bottom Right) ===== */
    .dark-mode-toggle {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: white;
        font-size: 1.5rem;
    }

    .dark-mode-toggle:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    .dark-mode-toggle:active {
        transform: translateY(-1px) scale(1);
    }

    body.dark-mode .dark-mode-toggle {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    @media (max-width: 767.98px) {
        .dark-mode-toggle {
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
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

<!-- Dark Mode Toggle Button (Floating Bottom Right) -->
<button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
    <i class="fas fa-moon" id="darkModeIcon"></i>
</button>

<script>
    // ===== DARK MODE FUNCTIONALITY =====
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        
        // Check localStorage untuk dark mode preference
        const darkMode = localStorage.getItem('darkMode');
        
        // Apply dark mode jika sudah enabled sebelumnya
        if (darkMode === 'enabled') {
            document.body.classList.add('dark-mode');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
        }
        
        // Toggle dark mode saat button diklik
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            
            // Update icon
            if (document.body.classList.contains('dark-mode')) {
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                darkModeIcon.classList.remove('fa-sun');
                darkModeIcon.classList.add('fa-moon');
                localStorage.setItem('darkMode', 'disabled');
            }
        });
        
        // ===== SIDEBAR TOGGLE FOR MOBILE =====
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