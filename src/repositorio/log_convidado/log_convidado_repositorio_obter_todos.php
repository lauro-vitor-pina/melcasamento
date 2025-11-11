<?php
// /src/repositorios/log_convidado/log_convidado_repositorio_obter_todos.php

function log_convidado_repositorio_obter_todos(
    $dbc,
    $nu_page_number,
    $nu_page_size,
    $codigo_convidado 
) {
    // Validação dos parâmetros de paginação
    $nu_page_number = max(1, intval($nu_page_number));
    $nu_page_size = max(1, intval($nu_page_size));
    $offset = ($nu_page_number - 1) * $nu_page_size;
    $where_clauslue = '';

    if($codigo_convidado != null && !empty($codigo_convidado)) {
        $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);
        $where_clauslue = "WHERE lc.codigo_convidado = '$codigo_convidado' ";
    }

    // Query para contar o total
    $query_count = "SELECT COUNT(*) as total FROM log_convidado lc $where_clauslue";
    
    // Query principal com ordenação por data mais recente
    $query_data = "SELECT 
                    lc.codigo_log_convidado,
                    lc.codigo_convidado,
                    lc.codigo_presente,
                    lc.tx_pagina,
                    lc.tx_acao,
                    lc.dt_registro,
                    c.tx_nome_convidado,
                    c.tx_telefone_convidado,
                    p.tx_nome_presente,
                    p.tx_descricao_presente
                   FROM log_convidado lc
                   INNER JOIN convidado c ON lc.codigo_convidado = c.codigo_convidado
                   LEFT JOIN presente p ON lc.codigo_presente = p.codigo_presente
                   $where_clauslue
                   ORDER BY lc.dt_registro DESC
                   LIMIT $nu_page_size OFFSET $offset";

    // Executa query de count
    $result_count = mysqli_query($dbc, $query_count);

    if (!$result_count) {
        throw new Exception("Erro ao contar logs de convidado: " . mysqli_error($dbc));
    }

    $row_count = mysqli_fetch_assoc($result_count);
    $total_count = intval($row_count['total']);
    mysqli_free_result($result_count);

    // Executa query dos dados
    $result_data = mysqli_query($dbc, $query_data);

    if (!$result_data) {
        throw new Exception("Erro ao buscar logs de convidado: " . mysqli_error($dbc));
    }

    $rows = [];

    while ($row = mysqli_fetch_assoc($result_data)) {
        $rows[] = $row;
    }

    mysqli_free_result($result_data);

    // Calcula se tem próxima página
    $has_next_page = ($total_count > ($offset + $nu_page_size));

    return [
        'rows' => $rows,
        'nu_count' => $total_count,
        'has_next_page' => $has_next_page,
        'nu_page_number' => $nu_page_number,
        'nu_page_size' => $nu_page_size
    ];
}
?>
