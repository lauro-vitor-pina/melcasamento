<?php

require_once(__DIR__ . '/../../repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../repositorio/presente/presente_repositorio_existe_reserva.php');
require_once(__DIR__ . '/../../repositorio/presente/presente_repositorio_reservar.php');
require_once(__DIR__ . '/../../repositorio/convidado/convidado_repositorio_obter_por_codigo.php');

function presente_service_reservar($codigo_convidado, $codigo_presente)
{
    $dbc = null;

    if ($codigo_convidado == null || empty($codigo_convidado)) {
        throw new Exception('Código do convidado é obrigatório.');
    }

    if ($codigo_presente == null || empty($codigo_presente)) {
        throw new Exception('Código do presente é obrigatório.');
    }

    try {

        $dbc = reposito_obter_conexao();

        $convidado_result = convidado_repositorio_obter_por_codigo($dbc, $codigo_convidado);

        if ($convidado_result == null) {
            throw new Exception('Convidado informado é inválido.');
        }

        $existe_reserva_result = presente_repositorio_existe_reserva($dbc, $codigo_presente);

        if ($existe_reserva_result) {
            throw new Exception('O presente já está reservado, por favor reserve outro presente.');
        }

        $reservar_result = presente_repositorio_reservar($dbc, $codigo_convidado, $codigo_presente, $convidado_result['tx_nome_convidado']);

        return $reservar_result;
    } finally {
        repositorio_fechar_conexao($dbc);
    }
}
