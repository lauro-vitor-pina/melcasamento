<?php

//src/repositorio/convidado/convidado_repositorio_obter_todos.php
function convidado_repositorio_obter_todos(
    $dbc,
    $bl_somente_mensagem_nao_enviada,
    $bl_somente_nao_confirmado,
    $bl_somente_nao_respondido,
    $bl_somente_nao_visualizado,
    $tx_consulta,
    $nu_page_number,
    $nu_page_size
) {
    $tx_filtro = _convidado_repositorio_obter_todos_filtrar(
        $dbc,
        $bl_somente_mensagem_nao_enviada,
        $bl_somente_nao_confirmado,
        $bl_somente_nao_respondido,
        $bl_somente_nao_visualizado,
        $tx_consulta
    );

    $nu_count = _convidado_repositorio_obter_todos_count($dbc, $tx_filtro);

    $rows = _convidado_repositorio_obter_todos_rows($dbc, $tx_filtro, $nu_page_number, $nu_page_size);

    $has_next_page = ($nu_count > ($nu_page_number * $nu_page_size));

    return [
        'nu_count' => $nu_count,
        'rows' => $rows,
        'nu_page_number' => $nu_page_number,
        'nu_page_size' => $nu_page_size,
        'has_next_page' => $has_next_page
    ];
}


function _convidado_repositorio_obter_todos_filtrar(
    $dbc,
    $bl_somente_mensagem_nao_enviada,
    $bl_somente_nao_confirmado,
    $bl_somente_nao_respondido,
    $bl_somente_nao_visualizado,
    $tx_consulta
) {
    $tx_filtro = '';

    if ($bl_somente_mensagem_nao_enviada) {
        $tx_filtro .= 'AND c.bl_mensagem_enviada = 0 ';
    }

    if ($bl_somente_nao_confirmado) {
        $tx_filtro .= 'AND c.bl_mensagem_enviada = 1 AND c.bl_confirmacao = 0 ';
    }

    if ($bl_somente_nao_respondido) {
        $tx_filtro .= 'AND c.bl_mensagem_enviada = 1 AND c.bl_confirmacao IS NULL ';
    }

    if ($bl_somente_nao_visualizado) {
        $tx_filtro .= 'AND c.bl_mensagem_enviada = 1
            AND NOT EXISTS (
            SELECT 1 FROM log_convidado lc 
            WHERE lc.codigo_convidado = c.codigo_convidado
        ) ';
    }

    if ($tx_consulta !== null && trim($tx_consulta) !== '') {

        $tx_consulta_escaped = mysqli_real_escape_string($dbc, trim($tx_consulta));

        $tx_filtro .= "AND (c.tx_nome_convidado LIKE '%$tx_consulta_escaped%' OR c.tx_telefone_convidado LIKE '%$tx_consulta_escaped%') ";
    }

    return $tx_filtro;
}

function _convidado_repositorio_obter_todos_count($dbc, $tx_filtro)
{
    $query = "SELECT COUNT(codigo_convidado) as count FROM convidado c  WHERE c.dt_desativacao IS NULL $tx_filtro";

    $query_result = mysqli_query($dbc, $query);

    if (!$query_result) {
        throw new Exception('Erro ao contar convidados: '. mysqli_error($dbc));
    }

    $row = mysqli_fetch_array($query_result);

    mysqli_free_result($query_result);

    $count = (int) $row['count'];

    return $count;
}

function _convidado_repositorio_obter_todos_rows($dbc, $tx_filtro, $nu_page_number, $nu_page_size)
{
    $offset = ($nu_page_number - 1) * $nu_page_size;
    
    $query = "SELECT 
                c.codigo_convidado,
                c.tx_nome_convidado,
                c.tx_telefone_convidado,
                c.nu_qtd_pessoas,
                c.dt_registro,
                c.bl_confirmacao,
                c.bl_mensagem_enviada,
                (
                    CASE 
                        WHEN ( (SELECT COUNT(lc.codigo_log_convidado)
                                    FROM log_convidado AS lc
                                    WHERE lc.codigo_convidado = c.codigo_convidado) >= 1 )
                                THEN 1
                            ELSE 0
                    END
                 ) AS bl_visualizado
                FROM convidado c
                WHERE dt_desativacao IS NULL $tx_filtro
                ORDER BY c.dt_registro DESC
                LIMIT $nu_page_size OFFSET $offset";

    $query_result = mysqli_query($dbc, $query);

    if (!$query_result) {
        throw new Exception('Erro ao buscar convidados: ' . mysqli_error($dbc));
    }

    $rows = [];

    while ($row = mysqli_fetch_assoc($query_result)) {
        $rows[] = $row;
    }

    mysqli_free_result($query_result);

    return $rows;
}

