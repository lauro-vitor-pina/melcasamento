<?php


function convidado_repositorio_existe_nome($dbc, $tx_nome_convidado, $codigo_convidado)
{

    $tx_nome_convidado = mysqli_real_escape_string($dbc, trim($tx_nome_convidado));

    $query = "SELECT COUNT(1) as total 
              FROM convidado 
              WHERE tx_nome_convidado = '$tx_nome_convidado' 
              AND dt_desativacao IS NULL";


    if ($codigo_convidado !== null && !empty($codigo_convidado)) {
        $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);
        $query .= " AND codigo_convidado != '$codigo_convidado'";
    }

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao verificar nome do convidado: " . mysqli_error($dbc));
    }

    $row = mysqli_fetch_assoc($result);
    $total = intval($row['total']);

    mysqli_free_result($result);

    return $total > 0;
}
