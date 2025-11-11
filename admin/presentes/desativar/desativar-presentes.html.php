<?php 
$page_title = 'Desativar Presente';
include '../../includes/header.php'; 
include '../../includes/sidebar.php';
?>

<main class="main-content-desktop">
    <div class="container-fluid touch-improve">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../index.php"><i class="bi bi-house-door me-2"></i>Dashboard</a></li>
                <li class="breadcrumb-item"><a href="../listar/listar-presentes.html.php"><i class="bi bi-gift me-2"></i>Presentes</a></li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-trash me-2"></i>
                    Desativar Presente
                </li>
            </ol>
        </nav>

        <!-- Título da Página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-trash me-2"></i>
                Desativar Presente
            </h4>
            <a href="../listar/listar-presentes.html.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <!-- Card de Confirmação -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Confirmação de Desativação
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="bi bi-gift display-1 text-danger mb-3"></i>
                            <h5 class="text-danger">Tem certeza que deseja desativar este presente?</h5>
                            <p class="text-muted">
                                O presente será marcado como desativado e não aparecerá mais na lista de presentes.
                            </p>
                        </div>

                        <!-- Informações do Presente -->
                        <div class="card mb-4">
                            <div class="card-body text-start">
                                <h6 class="card-title">Informações do Presente</h6>
                                <div id="infoPresente">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Carregando...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulário de Desativação -->
                        <form id="formDesativar" novalidate>
                            <input type="hidden" id="codigo_presente" name="codigo_presente">
                            
                            <!-- Botões de Confirmação -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg" id="btnConfirmar">
                                    <i class="bi bi-check-lg me-2"></i>
                                    Confirmar Desativação
                                </button>
                                <a href="../listar/listar-presentes.html.php" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-x-lg me-2"></i>
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="spinner-border text-danger" role="status">
        <span class="visually-hidden">Processando...</span>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

<!-- Script específico da página -->
<script src="desativar-presentes.js"></script>