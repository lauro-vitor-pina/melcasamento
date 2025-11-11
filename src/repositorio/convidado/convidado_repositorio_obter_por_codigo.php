<?php

//src/repositorio/convidado/convidado_repositorio_obter_por_codigo.php
function convidado_repositorio_obter_por_codigo($dbc, $codigo_convidado)
{
    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);

    $query = "SELECT * FROM convidado WHERE codigo_convidado = '$codigo_convidado'";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao buscar convidado: " . mysqli_error($dbc));
    }

    $convidado = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    return $convidado;
}
