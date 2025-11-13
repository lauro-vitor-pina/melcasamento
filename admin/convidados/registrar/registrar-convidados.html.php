<?php
// admin/convidados/registrar/registrar-convidados.html.php
$page_title = 'Registrar Convidado';
include '../../includes/header.php';
include '../../includes/sidebar.php';
$modo_edicao = isset($_GET['cd']);
?>

<link rel="stylesheet" href="registrar-convidado.css">

<main class="main-content-desktop">
    <div class="container-fluid touch-improve">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../index.php"><i class="bi bi-house-door me-2"></i>Início</a></li>
                <li class="breadcrumb-item"><a href="../listar/listar-convidados.html.php"><i class="bi bi-people me-2"></i>Convidados</a></li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-<?php echo $modo_edicao ? 'pencil' : 'plus-circle'; ?> me-2"></i>
                    <?php echo $modo_edicao ? 'Editar Convidado' : 'Novo Convidado'; ?>
                </li>
            </ol>
        </nav>

        <!-- Título da Página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-<?php echo $modo_edicao ? 'pencil' : 'person-plus'; ?> me-2"></i>
                <?php echo $modo_edicao ? 'Editar Convidado' : 'Novo Convidado'; ?>
            </h4>
            <a href="../listar/listar-convidados.html.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <!-- Formulário -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form id="formConvidado" novalidate>
                            <!-- Código (hidden para edição) -->
                            <input type="hidden" id="codigo_convidado" name="codigo_convidado">

                            <!-- Nome -->
                            <div class="mb-3">
                                <label for="tx_nome_convidado" class="form-label">Nome do Convidado *</label>
                                <input type="text" class="form-control" id="tx_nome_convidado" name="tx_nome_convidado" required>
                                <span id="tx_nome_convidado_msg_erro" class="invalid-feedback"></span>
                            </div>

                            <!-- Quantidade de Pessoas -->
                            <div class="mb-3">
                                <label for="nu_qtd_pessoas" class="form-label">Quantidade de Pessoas</label>
                                <select class="form-select" id="nu_qtd_pessoas" name="nu_qtd_pessoas">
                                    <option value="1">1 pessoa</option>
                                    <option value="2" selected>2 pessoas</option>
                                    <option value="3">3 pessoas</option>
                                    <option value="4">4 pessoas</option>
                                    <option value="5">5 pessoas</option>
                                    <option value="6">6 pessoas</option>
                                    <option value="7">7 pessoas</option>
                                </select>
                            </div>

                            <!-- Telefone -->
                            <div class="mb-3">
                                <label for="tx_telefone_convidado" class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="tx_telefone_convidado" name="tx_telefone_convidado" placeholder="(11) 99999-9999">
                                <span id="tx_telefone_convidado_msg_erro" class="invalid-feedback"></span>
                            </div>



                            <?php if ($modo_edicao): ?>
                                <div class="mb-3">
                                    <label for="bl_confirmacao" class="form-label">Confirmação de Presença</label>
                                    <select class="form-select" id="bl_confirmacao" name="bl_confirmacao" required>
                                        <option value="null" selected>Não Respondido</option>
                                        <option value="1">Confirmado</option>
                                        <option value="0">Não Confirmado</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="campo-mensagem-enviada">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="bl_mensagem_enviada" name="bl_mensagem_enviada">
                                        <label class="form-check-label" for="bl_mensagem_enviada">Mensagem enviada para o convidado</label>
                                    </div>
                                </div>

                                <div class="mt-2 mb-3" id="botoesWhatsAppContainer">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-primary mb-3" id="btnCopiarMensagem">
                                            <i class="bi bi-clipboard me-2"></i>
                                            Copiar Mensagem
                                        </button>
                                        <button type="button" class="btn btn-success" id="btnWhatsApp">
                                            <i class="bi bi-whatsapp me-2"></i>
                                            WhatsApp
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Botões de Ação -->
                            <div class="mb-3">
                                <div class="d-grid gap-2">
                                    <a href="../listar/listar-convidados.html.php" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" id="btnSalvar">
                                        <i class="bi bi-check-lg me-2"></i>
                                        <span id="btnText">Cadastrar Convidado</span>
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

<script src="registrar-convidado.js?v=<?= rand(1, 100); ?>"></script>