<?php
// src/repositorio/presente/presente_repositorio_obter_todos.php

function presente_repositorio_obter_todos(
    $dbc,
    $bl_somente_nao_reservados = false,
    $bl_somente_reservados = false,
    $tx_consulta = null,
    $nu_page_number = 1,
    $nu_page_size = 12
) {
    // Validação dos parâmetros de paginação
    $nu_page_number = max(1, intval($nu_page_number));
    $nu_page_size = max(1, intval($nu_page_size));
    $offset = ($nu_page_number - 1) * $nu_page_size;

    // Query base
    $query_base = "FROM presente p
                   LEFT JOIN convidado c ON p.codigo_convidado = c.codigo_convidado
                   WHERE p.dt_desativacao IS NULL";

    // Aplica filtros
    $conditions = [];

    // Filtro por reservados/não reservados
    if ($bl_somente_nao_reservados) {
        $conditions[] = "p.codigo_convidado IS NULL";
    }

    if ($bl_somente_reservados) {
        $conditions[] = "p.codigo_convidado IS NOT NULL";
    }

    // Filtro por texto
    if ($tx_consulta !== null && trim($tx_consulta) !== '') {
        $tx_consulta_escaped = mysqli_real_escape_string($dbc, trim($tx_consulta));
        $conditions[] = "(p.tx_nome_presente LIKE '%$tx_consulta_escaped%' OR 
                         p.tx_descricao_presente LIKE '%$tx_consulta_escaped%')";
    }

    // Adiciona condições à query base
    if (!empty($conditions)) {
        $query_base .= " AND " . implode(" AND ", $conditions);
    }

    // Query para contar o total
    $query_count = "SELECT COUNT(*) as total " . $query_base;
    
    // Query principal
    $query_data = "SELECT p.*, 
                          c.tx_nome_convidado,
                          c.tx_telefone_convidado
                   $query_base
                   ORDER BY p.dt_registro DESC
                   LIMIT $nu_page_size OFFSET $offset";

    // Executa query de count
    $result_count = mysqli_query($dbc, $query_count);
    if (!$result_count) {
        throw new Exception("Erro ao contar presentes: " . mysqli_error($dbc));
    }
    $row_count = mysqli_fetch_assoc($result_count);
    $total_count = intval($row_count['total']);
    mysqli_free_result($result_count);

    // Executa query dos dados
    $result_data = mysqli_query($dbc, $query_data);
    if (!$result_data) {
        throw new Exception("Erro ao buscar presentes: " . mysqli_error($dbc));
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