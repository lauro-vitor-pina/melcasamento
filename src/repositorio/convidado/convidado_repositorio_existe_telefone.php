<?php


function convidado_repositorio_existe_telefone($dbc, $tx_telefone_convidado, $codigo_convidado)
{
    $tx_telefone_convidado = mysqli_real_escape_string($dbc, $tx_telefone_convidado);

    $query = "SELECT COUNT(1) as total 
              FROM convidado 
              WHERE tx_telefone_convidado = '$tx_telefone_convidado'
              AND dt_desativacao IS NULL ";

    if ($codigo_convidado !== null && !empty($codigo_convidado)) {
        $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);
        $query .= "AND codigo_convidado != '$codigo_convidado' ";
    }

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao verificar telefone do convidado: " . mysqli_error($dbc));
    }

    $row = mysqli_fetch_assoc($result);
    
    $total = intval($row['total']);

    mysqli_free_result($result);

    return $total > 0;
}
