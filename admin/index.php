
<?php 

$page_title = 'Admin';
include ( __DIR__ . '/includes/header.php'); 
include (__DIR__ . '/includes/sidebar.php');
?>


<?php
// Dados de exemplo
$total_convidados = 150;
$total_confirmados = 120;
$total_presentes = 45;
$nao_confirmados = 30;
?>

<!-- Conteúdo Principal -->
<main class="main-content-desktop">
    <div class="container-fluid touch-improve">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">
                    <i class="bi bi-house-door me-2"></i>
                    Dashboard
                </li>
            </ol>
        </nav>

        <!-- Mensagem de Boas-Vindas Mobile -->
        <div class="card welcome-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-2">
                            <i class="bi bi-emoji-smile text-primary me-2"></i>
                            Olá, Admin!
                        </h5>
                        <p class="card-text text-muted mb-0">
                            Bem-vindo ao painel de controle. Tudo sob controle hoje?
                        </p>
                    </div>
                    <div class="ms-3">
                        <i class="bi bi-speedometer2 text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Estatísticas Mobile -->
        <div class="mobile-grid">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-1"><?php echo $total_convidados; ?></h4>
                    <small>Convidados</small>
                    <div class="mt-2">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>

            <div class="card stat-card bg-success text-white">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-1"><?php echo $total_confirmados; ?></h4>
                    <small>Confirmados</small>
                    <div class="mt-2">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="card stat-card bg-warning text-dark">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-1"><?php echo $total_presentes; ?></h4>
                    <small>Presentes</small>
                    <div class="mt-2">
                        <i class="bi bi-gift"></i>
                    </div>
                </div>
            </div>

            <div class="card stat-card bg-info text-white">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-1"><?php echo $nao_confirmados; ?></h4>
                    <small>Pendentes</small>
                    <div class="mt-2">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas Mobile -->
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Ações Rápidas
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="convidados/" class="btn btn-outline-primary btn-mobile w-100">
                            <i class="bi bi-person-plus me-1"></i>
                            Add Convidado
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="convidados/" class="btn btn-outline-success btn-mobile w-100">
                            <i class="bi bi-envelope me-1"></i>
                            Lembretes
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="presentes/" class="btn btn-outline-warning btn-mobile w-100">
                            <i class="bi bi-gift me-1"></i>
                            Add Presente
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-info btn-mobile w-100">
                            <i class="bi bi-download me-1"></i>
                            Exportar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividade Recente Mobile -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Atividade Recente
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-plus text-success me-3"></i>
                                <div>
                                    <small class="fw-bold">Novo convidado</small>
                                    <div class="text-muted">João Silva</div>
                                </div>
                            </div>
                            <small class="text-muted">5min</small>
                        </div>
                    </div>
                    
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-primary me-3"></i>
                                <div>
                                    <small class="fw-bold">Confirmação</small>
                                    <div class="text-muted">Maria Santos</div>
                                </div>
                            </div>
                            <small class="text-muted">15min</small>
                        </div>
                    </div>
                    
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-gift text-warning me-3"></i>
                                <div>
                                    <small class="fw-bold">Presente reservado</small>
                                    <div class="text-muted">Jogo de Panelas</div>
                                </div>
                            </div>
                            <small class="text-muted">1h</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botão Flutuante para Ações -->
        <div class="position-fixed bottom-0 end-0 m-3">
            <div class="dropdown">
                <button class="btn btn-primary btn-lg rounded-circle shadow" type="button" data-bs-toggle="dropdown" style="width: 60px; height: 60px;">
                    <i class="bi bi-plus"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="convidados/"><i class="bi bi-person-plus me-2"></i>Novo Convidado</a></li>
                    <li><a class="dropdown-item" href="presentes/"><i class="bi bi-gift me-2"></i>Novo Presente</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-envelope me-2"></i>Nova Mensagem</a></li>
                </ul>
            </div>
        </div>

    </div>
</main>


<?php include(__DIR__ . '/includes/footer.php'); ?>