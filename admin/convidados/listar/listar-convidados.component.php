<?php

/**
 * Componente de Listagem de Convidados
 * Lógica e funções de renderização
 */

/**
 * Processar parâmetros de request para o componente
 */
function obterParametrosHttpListarConvidados()
{
    $result = [
        'bl_somente_mensagem_nao_enviada' => false,
        'bl_somente_nao_confirmado' => false,
        'bl_somente_nao_respondido' => false,
        'bl_somente_nao_visualizado' => false,
        'tx_consulta' => '',
        'nu_page_number' => 1,
        'nu_page_size' => 12
    ];

    $result['bl_somente_mensagem_nao_enviada'] = isset($_GET['msg_nao_enviada']) ? (bool)$_GET['msg_nao_enviada'] : false;
    $result['bl_somente_nao_confirmado'] = isset($_GET['nao_confirmado']) ? (bool)$_GET['nao_confirmado'] : false;
    $result['bl_somente_nao_respondido'] = isset($_GET['nao_respondido']) ? (bool)$_GET['nao_respondido'] : false;
    $result['bl_somente_nao_visualizado'] = isset($_GET['nao_visualizado']) ? (bool)$_GET['nao_visualizado'] : false;
    $result['tx_consulta'] = $_GET['q'] ?? '';
    $result['nu_page_number'] = max(1, intval($_GET['page'] ?? 1));

    return $result;
}


function renderizarStatusMensagemEnviada($convidado)
{
    $bl_mensagem_enviada = $convidado['bl_mensagem_enviada'];
    $status = '';
    $cor = '';

    if ($bl_mensagem_enviada) {
        $status = 'Mensagem enviada';
        $cor = 'success';
    } else {
        $status = 'Mensagem não enviada';
        $cor = 'warning';
    }

    return (
        "<span class='badge bg-$cor' >
            <i class='bi bi-envelope'></i>
            $status
         </span>"
    );
}

function redenderizarStatusVisualizado($convidado)
{
    $bl_visualizado = $convidado['bl_visualizado'];
    $status = '';
    $cor = '';

    if ($bl_visualizado) {
        $status = 'Visualizado';
        $cor = 'success';
    } else {
        $status = 'Não visualizado';
        $cor = 'info';
    }

    return (
        "<span class='badge bg-$cor'>
            <i class='bi bi-eye-slash'></i>
            $status
         </span>"
    );
}

function renderizarStatusConfirmado($convidado)
{
    $bl_mensagem_enviada = $convidado['bl_mensagem_enviada'];
    $bl_confirmacao = $convidado['bl_confirmacao'];
    $status = '';
    $cor = '';
    $icone = '';

    if (!$bl_mensagem_enviada) {
        return '';
    }

    if ($bl_confirmacao === null) {
        $status = 'Não Respondido';
        $cor = 'secondary';
        $icone = 'clock';
    } else if ($bl_confirmacao == false) {
        $status = 'Não confirmado';
        $cor = 'danger';
        $icone = 'x-circle';
    } else {
        $status = 'Confirmado';
        $cor = 'success';
        $icone = 'check-circle';
    }

    return ("<span class='badge bg-$cor'>
            <i class='bi bi-$icone me-1' ></i>
            $status 
            </span>");
}

/**
 * Renderizar card individual de convidado
 */
function renderConvidadoCard($convidado)
{
    ob_start();
?>
    <div class="col-12 col-sm-6 col-lg-4 convidado-item">
        <div class="card convidado-card touch-feedback h-100">
            <div class="card-body">

                <!-- Header do Card -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="card-title mb-0 text-truncate pe-2">
                        <?php echo htmlspecialchars($convidado['tx_nome_convidado']); ?>
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item touch-feedback" 
                                    href="../registrar/registrar-convidados.html.php?cd=<?php echo $convidado['codigo_convidado']; ?>">
                                    <i class="bi bi-pencil me-2"></i>Editar
                                </a>
                            </li>


                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger touch-feedback" 
                                   href="../excluir/excluir-convidados.html.php?cd=<?php echo $convidado['codigo_convidado']; ?>" >
                                    <i class="bi bi-trash me-2"></i>Excluir
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Informações do Convidado -->
                <div class="mb-3">
                    <?php if ($convidado['tx_telefone_convidado']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted"><?php echo htmlspecialchars($convidado['tx_telefone_convidado']); ?></small>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-people text-muted me-2" style="width: 16px;"></i>
                        <small class="text-muted">
                            <?php echo $convidado['nu_qtd_pessoas'] ?? 1; ?>
                            <?php echo ($convidado['nu_qtd_pessoas'] ?? 1) == 1 ? 'pessoa' : 'pessoas'; ?>
                        </small>
                    </div>

                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar text-muted me-2" style="width: 16px;"></i>
                        <small class="text-muted">
                            <?php echo date('d/m/Y', strtotime($convidado['dt_registro'])); ?>
                        </small>
                    </div>
                </div>

                <!-- Status e Badges -->
                <div>
                    <?php echo renderizarStatusMensagemEnviada($convidado); ?>
                    <br />

                    <?php echo redenderizarStatusVisualizado($convidado); ?>
                    <br />

                    <?php echo renderizarStatusConfirmado($convidado); ?>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php return ob_get_clean();
}

/**
 * Renderizar grid de cards de convidados
 */
function renderConvidadosCards($convidados)
{

    if (empty($convidados)) {
        return '';
    }

    ob_start();

    foreach ($convidados as $convidado) {
        echo renderConvidadoCard($convidado);
    }

    return ob_get_clean();
}

?>