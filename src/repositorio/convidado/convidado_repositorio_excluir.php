<?php

function convidado_repositorio_excluir($dbc, $codigo_convidado)
{
    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);

    $query = "DELETE FROM convidado WHERE codigo_convidado = '$codigo_convidado'";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao excluir convidado: " . mysqli_error($dbc));
    }

    return $result;
}
