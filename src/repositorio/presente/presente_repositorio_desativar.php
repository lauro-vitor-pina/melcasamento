<?php
// src/repositorio/presente/presente_repositorio_desativar.php

function presente_repositorio_desativar($dbc, $codigo_presente, $usuario_desativacao)
{
    $codigo_presente = mysqli_real_escape_string($dbc, $codigo_presente);
    $usuario_desativacao = mysqli_real_escape_string($dbc, $usuario_desativacao);

    $query = "UPDATE presente 
              SET dt_desativacao = NOW(), 
                  tx_usuario_desativacao = '$usuario_desativacao'
              WHERE codigo_presente = '$codigo_presente'";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao desativar presente: " . mysqli_error($dbc));
    }

    return $result;
}
?>