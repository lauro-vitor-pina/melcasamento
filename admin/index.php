<?php

$page_title = 'Admin';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/sidebar.php');
?>


<?php
// Dados de exemplo
$total_convidados = 150;
$total_confirmados = 120;
$total_presentes = 45;
$nao_confirmados = 30;
?>

<!-- Conteúdo Principal -->
<main>
    <div class="container-fluid touch-area">

        <!-- Saudação por Horário -->
        <div class="card welcome-card">
            <div class="card-body">
                <?php
                $hora = date('H');
                if ($hora < 12) {
                    $saudacao = "Bom dia! ☀️";
                } elseif ($hora < 18) {
                    $saudacao = "Boa tarde! 🌤️";
                } else {
                    $saudacao = "Boa noite! 🌙";
                }
                ?>
                <h5 class="card-title mb-3">
                    <?php echo $saudacao; ?>
                </h5>
                <p class="card-text text-muted mb-0">
                    Gerencie seus convidados de forma simples e rápida.
                </p>
            </div>
        </div>

        <!-- Grid de Estatísticas -->
        <div class="mobile-grid">
            <div class="card stat-card touch-feedback bg-primary text-white">
                <div class="card-body p-3 text-center">
                    <h3 class="mb-1"><?php echo $total_convidados; ?></h3>
                    <small>Total</small>
                    <div class="mt-2">
                        <i class="bi bi-people-fill" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="card stat-card touch-feedback bg-success text-white">
                <div class="card-body p-3 text-center">
                    <h3 class="mb-1"><?php echo $total_confirmados; ?></h3>
                    <small>Confirmados</small>
                    <div class="mt-2">
                        <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="card stat-card touch-feedback bg-warning text-dark">
                <div class="card-body p-3 text-center">
                    <h3 class="mb-1"><?php echo $total_presentes; ?></h3>
                    <small>Presentes</small>
                    <div class="mt-2">
                        <i class="bi bi-gift-fill" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="card stat-card touch-feedback bg-info text-white">
                <div class="card-body p-3 text-center">
                    <h3 class="mb-1"><?php echo $nao_confirmados; ?></h3>
                    <small>Pendentes</small>
                    <div class="mt-2">
                        <i class="bi bi-clock-fill" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-lightning-fill me-2 text-warning"></i>
                    Ações Rápidas
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="convidados/registrar/registrar-convidados.html.php" class="btn btn-outline-primary btn-mobile touch-feedback w-100">
                            <i class="bi bi-person-plus me-2"></i>
                            Novo Convidado
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="presentes/registrar.php" class="btn btn-outline-warning btn-mobile touch-feedback w-100">
                            <i class="bi bi-gift me-2"></i>
                            Novo Presente
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="convidados/listar/listar-convidados.html.php" class="btn btn-outline-success btn-mobile touch-feedback w-100">
                            <i class="bi bi-person-plus me-2"></i>
                            Listar Convidados
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="presentes/" class="btn btn-outline-info btn-mobile touch-feedback w-100">
                            <i class="bi bi-gift me-2"></i>
                            Listar presentes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividade Recente -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>
                    Atividade Recente
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item touch-feedback">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-plus text-success me-3" style="font-size: 1.2rem;"></i>
                                <div>
                                    <small class="fw-bold d-block">Novo convidado</small>
                                    <small class="text-muted">João Silva</small>
                                </div>
                            </div>
                            <small class="text-muted">5min</small>
                        </div>
                    </div>

                    <div class="list-group-item touch-feedback">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-primary me-3" style="font-size: 1.2rem;"></i>
                                <div>
                                    <small class="fw-bold d-block">Confirmação</small>
                                    <small class="text-muted">Maria Santos</small>
                                </div>
                            </div>
                            <small class="text-muted">15min</small>
                        </div>
                    </div>

                    <div class="list-group-item touch-feedback">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-gift text-warning me-3" style="font-size: 1.2rem;"></i>
                                <div>
                                    <small class="fw-bold d-block">Presente reservado</small>
                                    <small class="text-muted">Jogo de Panelas</small>
                                </div>
                            </div>
                            <small class="text-muted">1h</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Botão Flutuante -->
<button class="fab touch-feedback" type="button" data-bs-toggle="dropdown">
    <i class="bi bi-plus"></i>
</button>

<ul class="dropdown-menu dropdown-menu-end" style="margin-bottom: 80px;">
    <li><a class="dropdown-item touch-feedback" href="convidados/"><i class="bi bi-person-plus me-2"></i>Novo Convidado</a></li>
    <li><a class="dropdown-item touch-feedback" href="presentes/"><i class="bi bi-gift me-2"></i>Novo Presente</a></li>
    <li><a class="dropdown-item touch-feedback" href="#"><i class="bi bi-envelope me-2"></i>Nova Mensagem</a></li>
</ul>

<?php include(__DIR__ . '/includes/footer.php'); ?>