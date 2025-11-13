<?php
require_once(__DIR__ . '/../../repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../repositorio/presente/presente_repositorio_desativar.php');
require_once(__DIR__ . '/../../repositorio/presente/presente_repositorio_existe_reserva.php');
require_once(__DIR__ . '/../autorizacao/autorizacao_service.php');

/**
 * Serviço para desativar presente com validações de negócio
 * 
 * @param string $codigo_presente Código do presente a ser desativado
 * @return array Resultado da operação
 * @throws Exception Em caso de erro
 */
function presente_service_desativar($codigo_presente)
{

    if ($codigo_presente == null || empty($codigo_presente)) {
        throw new Exception("Código do presente é obrigatório");
    }

    $usuario_logado = autorizacao_service_obter_usuario_logado();

    if (!$usuario_logado || empty($usuario_logado['nome'])) {
        throw new Exception("Usuário não autenticado");
    }

    $dbc = null;

    try {

        $dbc = reposito_obter_conexao();

        $possui_reserva = presente_repositorio_existe_reserva($dbc, $codigo_presente);

        if ($possui_reserva) {
            throw new Exception("Não é possível desativar um presente que possui reserva. Cancele a reserva primeiro.");
        }

        $result = presente_repositorio_desativar($dbc, $codigo_presente, $usuario_logado['nome']);

        return $result;

    } finally {
        repositorio_fechar_conexao($dbc);
    }
}
