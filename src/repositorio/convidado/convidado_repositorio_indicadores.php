<?php

require_once(__DIR__ . '/convidado_repositorio_obter_todos.php');

function convidado_repositorio_indicadores($dbc)
{
    $result = [
        'total' => _convidado_repositorio_indicadores_total($dbc),
        'confirmados' => _convidado_repositorio_indicadores_confirmados($dbc),
        'nao_confirmados' => _convidado_repositorio_indicadores_nao_confirmados($dbc),
        'mensagem_nao_enviada' => _convidado_repositorio_indicadores_mensagem_nao_enviada($dbc)
    ];

    return $result;
}


function _convidado_repositorio_indicadores_total($dbc): int
{
    return _convidado_repositorio_obter_todos_count($dbc, '');
}

function _convidado_repositorio_indicadores_confirmados($dbc): int
{
    $tx_filtro = 'AND c.bl_mensagem_enviada = 1 AND c.bl_confirmacao = 1 ';

    return _convidado_repositorio_obter_todos_count($dbc, $tx_filtro);
}

function _convidado_repositorio_indicadores_nao_confirmados($dbc): int
{
    $tx_filtro = 'AND c.bl_mensagem_enviada = 1 AND c.bl_confirmacao = 0';

    return _convidado_repositorio_obter_todos_count($dbc, $tx_filtro);
}

function _convidado_repositorio_indicadores_mensagem_nao_enviada($dbc): int
{
    $tx_filtro = 'AND c.bl_mensagem_enviada = 0 ';

    return _convidado_repositorio_obter_todos_count($dbc, $tx_filtro);
}
