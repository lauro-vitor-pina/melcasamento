<?php

function  presente_repositorio_obter_presentes_por_codigo_convidado($dbc, $codigo_convidado) 
{
    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);

    $query = "SELECT codigo_presente,
                     codigo_convidado,
                     tx_nome_presente,
                     tx_descricao_presente,
                     tx_foto_presente      
               FROM presente
               WHERE codigo_convidado = '$codigo_convidado'
               AND dt_desativacao IS NULL";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao obter presentes por código do convidado: " . mysqli_error($dbc));
    }

    $presentes = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $presentes[] = $row;
    }

    return $presentes;
}
