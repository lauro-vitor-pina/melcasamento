<!-- Sidebar Mobile (Offcanvas) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title text-white">
            <i class="bi bi-heart-fill me-2"></i>
            Menu
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body p-0">
        <!-- Menu de Navegação Mobile -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="../index.php">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'convidados') !== false ? 'active' : ''; ?>" href="convidados/">
                    <i class="bi bi-people"></i>
                    Convidados
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'presentes') !== false ? 'active' : ''; ?>" href="presentes/">
                    <i class="bi bi-gift"></i>
                    Presentes
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Sidebar Desktop -->
<div class="sidebar-desktop" style="display: none;">
    <div class="position-sticky pt-4">
        <!-- Logo Desktop -->
        <div class="text-center mb-5 px-3">
            <h4 class="text-white mb-2">
                <i class="bi bi-heart-fill me-2"></i>
                Sistema
            </h4>
            <small class="text-white-50">Painel Administrativo</small>
        </div>

        <!-- Menu Desktop -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="../index.php">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'convidados') !== false ? 'active' : ''; ?>" href="convidados/">
                    <i class="bi bi-people"></i>
                    Convidados
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'presentes') !== false ? 'active' : ''; ?>" href="presentes/">
                    <i class="bi bi-gift"></i>
                    Lista de Presentes
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link text-warning" href="../logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    Sair do Sistema
                </a>
            </li>
        </ul>

        <!-- Status do Sistema Desktop -->
        <div class="mt-5 p-3 mx-3 bg-dark bg-opacity-25 rounded">
            <small class="text-white-50 d-block">Status do Sistema</small>
            <div class="d-flex align-items-center mt-2">
                <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                <small class="text-white">Online</small>
            </div>
        </div>
    </div>
</div>