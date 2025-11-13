<?php

require_once(__DIR__ . '/../autorizacao/autorizacao_service.php');
require_once(__DIR__ . '/../../repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../repositorio/convidado/convidado_repositorio_desativar.php');
require_once(__DIR__ . '/../../repositorio/presente/presente_repositorio_obter_por_codigo_convidado.php');
require_once(__DIR__ . '/../../repositorio/presente/presente_repositorio_remover_reserva_do_convidado.php');

function convidado_service_desativar($codigo_convidado)
{
    $dbc = null;
    $result = false;

    if (empty($codigo_convidado)) {
        throw new Exception("Código do convidado é obrigatório");
    }

    try {

        $usuario_logado = autorizacao_service_obter_usuario_logado();

        $dbc = reposito_obter_conexao();

        $presentes_do_convidado_result = presente_repositorio_obter_presentes_por_codigo_convidado($dbc, $codigo_convidado);

        if (sizeof($presentes_do_convidado_result) > 0) {
            presente_repositorio_remover_reserva_do_convidado($dbc, $codigo_convidado, $usuario_logado['nome']);
        }

        $result = convidado_repositorio_desativar($dbc, $codigo_convidado, $usuario_logado['nome']);

    } finally {
        repositorio_fechar_conexao($dbc);
    }

    return $result;
}
