<?php
// src/repositorio/presente/presente_repositorio_obter_por_codigo.php

function presente_repositorio_obter_por_codigo($dbc, $codigo_presente)
{
    $codigo_presente = mysqli_real_escape_string($dbc, $codigo_presente);

    $query = "SELECT p.*, 
                     c.tx_nome_convidado,
                     c.tx_telefone_convidado
              FROM presente p
              LEFT JOIN convidado c ON p.codigo_convidado = c.codigo_convidado
              WHERE p.codigo_presente = '$codigo_presente' 
              AND p.dt_desativacao IS NULL";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao buscar presente: " . mysqli_error($dbc));
    }

    $presente = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    return $presente;
}
?>