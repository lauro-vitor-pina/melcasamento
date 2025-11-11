<?php
require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/convidado/convidado_repositorio_obter_por_codigo.php');
require_once(__DIR__ . '/../../../src/repositorio/log_convidado/log_convidado_repositorio_obter_todos.php');

$dbc = null;
$cd = '';
$convidado = null;
$erro = '';
$pagina   = 0;
$tamanho  = 0;
$totalPaginas = 0;
$logsData = [];
$page_title = '';


try {

    $cd = $_GET['cd'];

    if (!preg_match('/^[0-9a-f\-]{36}$/i', $cd)) {
        http_response_code(400);
        exit('Código inválido');
    }

    $dbc = reposito_obter_conexao();


    // dados do convidado
    $convidado = convidado_repositorio_obter_por_codigo($dbc, $cd);

    if (!$convidado) {
        http_response_code(404);
        exit('Convidado não encontrado');
    }

    $page_title = 'Logs de ' . htmlspecialchars($convidado['tx_nome_convidado']);

    // paginação
    $pagina   = max(1, intval($_GET['page'] ?? 1));
    $tamanho  = 5;
    $logsData = log_convidado_repositorio_obter_todos($dbc, $pagina, $tamanho, $cd);
    $totalPaginas = max(1, ceil($logsData['nu_count'] / $tamanho));
} catch (Exception $ex) {
    $erro = 'Erro ao obter logs: ' . htmlspecialchars($ex->getMessage());
} finally {
    repositorio_fechar_conexao($dbc);
}


?>

<?php
include(__DIR__ . '/../../includes/header.php');
include(__DIR__ . '/../../includes/sidebar.php');
?>

<main class="main-content-desktop">
    <div class="container-fluid touch-improve">

        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../index.php"><i class="bi bi-house-door me-2"></i>Início</a></li>
                <li class="breadcrumb-item"><a href="../listar/listar-convidados.html.php"><i class="bi bi-people me-2"></i>Convidados</a></li>
                <li class="breadcrumb-item active"> <i class="bi bi-journal-text me-2"></i>Logs</li>
            </ol>
        </nav>

        <!-- CABEÇALHO DO CONVIDADO -->
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><?= htmlspecialchars($convidado['tx_nome_convidado']) ?></h5>
                    <small class="text-muted"><?= htmlspecialchars($convidado['tx_telefone_convidado']) ?></small>
                </div>
                <a href="../listar/listar-convidados.html.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                <div>
                    <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
                    <br>
                    <a href="../listar/listar-convidados.html.php" class="alert-link">Voltar para lista de convidados</a>
                </div>
            </div>
        <?php else: ?>
            <!-- TABELA DE LOGS -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Data / Hora</th>
                                    <th>Página</th>
                                    <th>Ação</th>
                                    <th>Presente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logsData['rows'])): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Nenhum log registrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logsData['rows'] as $log): ?>
                                        <tr>
                                            <td><?= (new DateTime($log['dt_registro']))->format('d/m/Y H:i:s') ?></td>
                                            <td><?= htmlspecialchars($log['tx_pagina']) ?></td>
                                            <td><?= htmlspecialchars($log['tx_acao']) ?></td>
                                            <td><?= htmlspecialchars($log['tx_nome_presente'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PAGINAÇÃO PHP -->
            <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Paginação" class="mt-3">
                    <ul class="pagination justify-content-center">
                        <!-- Anterior -->
                        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?cd=<?= urlencode($cd) ?>&page=<?= $pagina - 1 ?>">
                                Anterior
                            </a>
                        </li>

                        <!-- Números -->
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                                <a class="page-link"
                                    href="?cd=<?= urlencode($cd) ?>&page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Próxima -->
                        <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?cd=<?= urlencode($cd) ?>&page=<?= $pagina + 1 ?>">
                                Próxima
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</main>

<?php include(__DIR__ . '/../../includes/footer.php'); ?>