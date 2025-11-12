<!-- Sidebar Mobile -->
<?php


define('URL_ADMIN', '/admin/');

define('URL_CONVIDADO_LISTAR', '/admin/convidados/listar/listar-convidados.html.php');

?>
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile">
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title text-white mb-1">
                <i class="bi bi-heart-fill me-2"></i>
                Menu
            </h5>
        </div>
        <button type="button" class="btn-close btn-close-white touch-feedback" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body p-0">
        <!-- Menu Principal -->
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a  href="<?php echo URL_ADMIN; ?>"
                    class="nav-link touch-feedback <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="bi bi-house-door"></i>
                    Início
                </a>
            </li>
            <li class="nav-item">
                <a  href="<?php echo URL_CONVIDADO_LISTAR; ?>"
                    class="nav-link touch-feedback <?php echo strpos($_SERVER['REQUEST_URI'], 'convidados') !== false ? 'active' : ''; ?>" >
                    <i class="bi bi-people"></i>
                    Convidados
                    <span class="badge bg-primary float-end mt-1">150</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/presentes/listar/listar-presentes.html.php" class="nav-link touch-feedback <?php echo strpos($_SERVER['REQUEST_URI'], 'presentes') !== false ? 'active' : ''; ?>" ">
                    <i class="bi bi-gift"></i>
                    Presentes
                    <span class="badge bg-warning float-end mt-1">45</span>
                </a>
            </li>
        </ul>

        <!-- Status Rápido -->
        <div class="mt-4 p-3 mx-3 bg-dark bg-opacity-25 rounded">
            <small class="text-white-50 d-block">Status Rápido</small>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                    <small class="text-white">120 confirmados</small>
                </div>
                <small class="text-white-50">80%</small>
            </div>
        </div>

        <!-- Logout -->
        <div class="mt-4 p-3">
            <a href="/admin/logout.php" class="btn btn-outline-warning btn-sm w-100 touch-feedback">
                <i class="bi bi-box-arrow-right me-2"></i>
                Sair do App
            </a>
        </div>
    </div>
</div>