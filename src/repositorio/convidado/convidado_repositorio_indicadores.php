<?php

require_once(__DIR__ . '/convidado_repositorio_obter_todos.php');

function convidado_repositorio_indicadores($dbc)
{

    $result = [
        'total' => _convidado_repositorio_obter_todos_count($dbc, ''),
        'confirmados' => _convidado_repositorio_obter_todos_count($dbc, _convidado_repositorio_obter_filtro_confirmados()),
        'nao_confirmados' => _convidado_repositorio_obter_todos_count($dbc, _convidado_repositorio_obter_filtro_nao_confirmados()),
        'mensagem_nao_enviada' => _convidado_repositorio_obter_todos_count($dbc, _convidado_repositorio_obter_filtro_mensagem_nao_enviada() )
    ];

    return $result;
}
