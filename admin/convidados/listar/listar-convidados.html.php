<?php

$page_title = 'Convidados';
include '../../includes/header.php';
include '../../includes/sidebar.php';
require_once 'listar-convidados.component.php'
?>

<main>
    <div class="container-fluid touch-area">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="../../index.php">
                        <i class="bi bi-house-door me-1"></i> Início
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-people me-1"></i> Convidados
                </li>
                <li class="breadcrumb-item active">
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>">
                        Listar
                    </a>
                </li>
            </ol>
        </nav>

        <!-- Header da Página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-people me-2 text-primary"></i>
                    Meus Convidados
                </h4>
                <small class="text-muted" id="total-convidados">
                    Carregando...
                </small>
            </div>
            <a href="../registrar/registrar-convidados.html.php" class="btn btn-primary btn-mobile">
                <i class="bi bi-person-plus me-1"></i>
                Novo
            </a>
        </div>

        <!-- Card de Filtros -->
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-funnel me-2 text-info"></i>
                    Filtros
                    <span class="badge bg-primary ms-2 d-none" id="filtros-ativo">Ativo</span>
                </h6>
            </div>
            <div class="card-body">
                <form id="filtrosForm" class="row g-3">

                    <!-- Busca por Texto -->
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control border-start-0"
                                name="q"
                                placeholder="Buscar por nome ou telefone..."
                                id="searchInput">
                            <button
                                type="button"
                                class="btn btn-outline-secondary d-none"
                                id="clearSearchBtn">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filtros Booleanos - Checkboxes -->
                    <div class="col-12">
                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input touch-feedback"
                                        type="checkbox"
                                        name="msg_nao_enviada"
                                        id="msg_nao_enviada"
                                        value="1">
                                    <label class="form-check-label small" for="msg_nao_enviada">
                                        <i class="bi bi-envelope-x me-1 text-warning"></i>
                                        Msg não enviada
                                    </label>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input touch-feedback"
                                        type="checkbox"
                                        name="nao_confirmado"
                                        id="nao_confirmado"
                                        value="1">
                                    <label class="form-check-label small" for="nao_confirmado">
                                        <i class="bi bi-x-circle me-1 text-danger"></i>
                                        Não confirmados
                                    </label>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input touch-feedback"
                                        type="checkbox"
                                        name="nao_respondido"
                                        id="nao_respondido"
                                        value="1">
                                    <label class="form-check-label small" for="nao_respondido">
                                        <i class="bi bi-clock me-1 text-secondary"></i>
                                        Não respondido
                                    </label>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input touch-feedback"
                                        type="checkbox"
                                        name="nao_visualizado"
                                        id="nao_visualizado"
                                        value="1">
                                    <label class="form-check-label small" for="nao_visualizado">
                                        <i class="bi bi-eye-slash me-1 text-info"></i>
                                        Não visualizado
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação dos Filtros -->
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="bi bi-funnel me-1"></i>
                                Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limparFiltros()">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpar Filtros
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Área de Conteúdo -->
        <div id="conteudo-principal">
            <!-- Loading Inicial -->
            <div class="card text-center py-5" id="loading-inicial">
                <div class="card-body">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <h5 class="text-muted">Carregando convidados...</h5>
                </div>
            </div>

            <!-- Grid de Convidados -->
            <div id="convidados-container" class="d-none">
                <div class="row g-3 mb-4" id="convidados-grid"></div>

                <!-- Botão Carregar Mais -->
                <div class="text-center mt-4 mb-5" id="carregar-mais-container">
                    <button id="carregar-mais-btn" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-arrow-down-circle me-2"></i>
                        Carregar Mais Convidados
                    </button>
                    <div id="carregando-mais" class="mt-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="text-muted mt-2">Carregando mais convidados...</p>
                    </div>
                </div>
            </div>

            <!-- Estado Vazio -->
            <div class="card text-center py-5 d-none" id="estado-vazio">
                <div class="card-body">
                    <i class="bi bi-people display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum convidado encontrado</h5>
                    <p class="text-muted mb-4" id="mensagem-vazio"></p>
                    <a href="../adicionar.php" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>
                        Adicionar Primeiro Convidado
                    </a>
                </div>
            </div>

            <!-- Erro -->
            <div class="alert alert-danger d-none" id="erro-container">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span id="mensagem-erro"></span>
            </div>
        </div>

    </div>
</main>

<!-- Incluir JavaScript do Componente -->
<script src="listar-convidados.js"></script>

<?php include '../../includes/footer.php'; ?>