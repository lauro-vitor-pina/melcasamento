<?php
// /admin/presentes/registrar/registrar-presente.html.php

$page_title = 'Registrar Presente';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="registrar-presente.css">

<main class="main-content-desktop">
    <div class="container-fluid touch-improve">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../index.php"><i class="bi bi-house-door me-2"></i>Início</a></li>
                <li class="breadcrumb-item"><a href="../listar/listar-presentes.html.php"><i class="bi bi-gift me-2"></i>Presentes</a></li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-<?php echo isset($_GET['cd']) ? 'pencil' : 'plus-circle'; ?> me-2"></i>
                    <?php echo isset($_GET['cd']) ? 'Editar Presente' : 'Novo Presente'; ?>
                </li>
            </ol>
        </nav>

        <!-- Título da Página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-<?php echo isset($_GET['cd']) ? 'pencil' : 'gift-plus'; ?> me-2"></i>
                <?php echo isset($_GET['cd']) ? 'Editar Presente' : 'Cadastrar Novo Presente'; ?>
            </h4>
            <a href="../listar/listar-presentes.html.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <!-- Formulário -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form id="formPresente" novalidate enctype="multipart/form-data">
                            <!-- Código (hidden para edição) -->
                            <input type="hidden" id="codigo_presente" name="codigo_presente">
                            <!-- Foto atual (hidden para edição) -->
                            <input type="hidden" id="tx_foto_presente_atual" name="tx_foto_presente_atual">

                            <!-- Nome do Presente -->
                            <div class="mb-3">
                                <label for="tx_nome_presente" class="form-label">Nome do Presente *</label>
                                <input type="text" class="form-control" id="tx_nome_presente" name="tx_nome_presente" required>
                                <div class="invalid-feedback">Por favor, informe o nome do presente.</div>
                            </div>

                            <!-- Descrição do Presente -->
                            <div class="mb-3">
                                <label for="tx_descricao_presente" class="form-label">Descrição do Presente</label>
                                <textarea class="form-control" id="tx_descricao_presente" name="tx_descricao_presente" rows="3"></textarea>
                            </div>

                            <!-- Foto Atual (apenas edição) -->
                            <div class="mb-3" id="foto-atual-container" style="display: none;">
                                <label class="form-label">Foto Atual</label>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img id="foto-atual-img" src="" alt="Foto atual" class="img-fluid rounded mb-2" style="max-height: 200px;">
                                        <div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" id="btn-remover-foto">
                                                <i class="bi bi-trash me-1"></i>Remover Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nova Foto do Presente -->
                            <div class="mb-3">
                                <label for="tx_foto_presente" class="form-label">
                                    <?php echo isset($_GET['cd']) ? 'Nova Foto do Presente' : 'Foto do Presente'; ?>
                                </label>
                                <input type="file" class="form-control" id="tx_foto_presente" name="tx_foto_presente" accept="image/jpeg,image/png,image/gif,image/webp">
                                <small class="form-text text-muted">Apenas arquivos de imagem (JPG, PNG, GIF, WEBP). Máximo 5MB.</small>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="mb-3">
                                <div class="d-grid gap-2">
                                    <a href="../listar/listar-presentes.html.php" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" id="btnSalvar">
                                        <i class="bi bi-check-lg me-2"></i>
                                        <span id="btnText">Cadastrar Presente</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include '../../includes/footer.php'; ?>

<!-- Script específico da página -->
<script src="registrar-presente.js"></script>