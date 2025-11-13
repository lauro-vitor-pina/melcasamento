<?php

$page_title = 'Admin';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/sidebar.php');
include(__DIR__ . '/../src/repositorio/repositorio_conexao.php');
include(__DIR__ . '/../src/repositorio/convidado/convidado_repositorio_indicadores.php');
include(__DIR__ . '/../src/repositorio/log_convidado/log_convidado_repositorio_obter_todos.php');
include(__DIR__ . '/../src/repositorio/presente/presente_repositorio_indicadores.php');

$dbc = null;
$total_convidados = 0;
$total_confirmados = 0;
$total_nao_confirmados = 0;
$total_mensagem_nao_enviada = 0;
$total_presentes_reservados = 0;
$total_presentes_nao_reservados = 0;

$logs_convidado_result = null;

try {
    $dbc = reposito_obter_conexao();
    
    $logs_convidado_result = log_convidado_repositorio_obter_todos($dbc, 1, 5, null);

    $total_presentes_reservados = presente_repositorio_indicadores_reservados($dbc);
    $total_presentes_nao_reservados = presente_repositorio_indicadores_nao_reservados($dbc);

    $indicadores_convidado_result = convidado_repositorio_indicadores($dbc);
    $total_convidados = $indicadores_convidado_result['total'];
    $total_confirmados = $indicadores_convidado_result['confirmados'];
    $total_nao_confirmados = $indicadores_convidado_result['nao_confirmados'];
    $total_mensagem_nao_enviada = $indicadores_convidado_result['mensagem_nao_enviada'];
} catch (Exception $e) {
    echo '<div class="alert alert-danger" role="alert">
            Erro ao obter indicadores: ' . htmlspecialchars($e->getMessage()) . '
          </div>';
    exit;
} finally {
    repositorio_fechar_conexao($dbc);
}

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
        <div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card stat-card touch-feedback bg-primary text-white">
                        <div class="card-body p-3 text-center">
                            <h3 class="mb-1"><?php echo $total_convidados; ?></h3>
                            <small>Total</small>
                            <div class="mt-2">
                                <i class="bi bi-people-fill" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card stat-card touch-feedback bg-success text-white">
                        <div class="card-body p-3 text-center">
                            <h3 class="mb-1"><?php echo $total_confirmados; ?></h3>
                            <small>Confirmados</small>
                            <div class="mt-2">
                                <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card stat-card touch-feedback bg-danger text-white">
                        <div class="card-body p-3 text-center">
                            <h3 class="mb-1"><?php echo $total_nao_confirmados; ?></h3>
                            <small>Não Confirmados</small>
                            <div class="mt-2">
                                <i class="bi bi-x-circle-fill me-1" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="card stat-card touch-feedback bg-warning text-white">
                        <div class="card-body p-3 text-center">
                            <h3 class="mb-1"><?php echo $total_mensagem_nao_enviada; ?></h3>
                            <small>Mensagem não enviada</small>
                            <div class="mt-2">
                                <i class="bi bi-clock-fill" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card stat-card touch-feedback bg-info text-white">
                        <div class="card-body p-3 text-center">
                            <h3 class="mb-1"><?php echo $total_presentes_reservados; ?></h3>
                            <small>Presentes reservados</small>
                            <div class="mt-2">
                                <i class="bi bi-gift-fill" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card stat-card touch-feedback bg-secondary text-white">
                        <div class="card-body p-3 text-center">
                            <h3 class="mb-1"><?php echo $total_presentes_nao_reservados; ?></h3>
                            <small>Presentes não reservados</small>
                            <div class="mt-2">
                                <i class="bi bi-gift-fill" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
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
                        <a href="presentes/registrar/registrar-presente.html.php" class="btn btn-outline-warning btn-mobile touch-feedback w-100">
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
                        <a href="presentes/listar/listar-presentes.html.php" class="btn btn-outline-info btn-mobile touch-feedback w-100">
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
                    <?php if ($logs_convidado_result ==  null || sizeof($logs_convidado_result['rows']) == 0):  ?>
                        <div class="list-group-item text-muted small">
                            Nenhuma atividade registrada ainda.
                        </div>
                    <?php else: ?>

                        <?php foreach ($logs_convidado_result['rows'] as $log_item): ?>

                            <div class="list-group-item touch-feedback">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        <i class=""></i>
                                        <div>
                                            <small class="fw-bold d-block mb-2">
                                                <?php echo $log_item['tx_nome_convidado'];  ?>

                                            </small>
                                            <small class="text-muted">
                                                <b>Ação:</b> <?= $log_item['tx_acao'] ?> &nbsp;
                                                <b>Página:</b> <?= $log_item['tx_pagina'] ?>
                                            </small>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= (new DateTime($log_item['dt_registro']))->format('d/m/Y H:i:s') ?>
                                    </small>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include(__DIR__ . '/includes/footer.php'); ?>