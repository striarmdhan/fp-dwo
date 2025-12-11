<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse border-end">
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

        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='sales')?'active bg-light text-primary fw-bold':'text-secondary'; ?>" href="index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard Sales
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='finance')?'active bg-light text-primary fw-bold':'text-secondary'; ?>" href="finance.php">
                    <i class="fas fa-box"></i>
                    Tax
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='geo')?'active bg-light text-primary fw-bold':'text-secondary'; ?>" href="geo.php">
                    <i class="fas fa-globe-asia"></i>
                    Geo Analysis
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($page=='olap')?'active bg-light text-primary fw-bold':'text-secondary'; ?>" href="olap.php">
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