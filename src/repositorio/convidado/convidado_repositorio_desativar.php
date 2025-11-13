<?php
//src/repositorio/convidado/convidado_repositorio_desativar.php
function convidado_repositorio_desativar($dbc, $codigo_convidado, $usuario_desativacao)
{
    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);
    $usuario_desativacao = mysqli_real_escape_string($dbc, $usuario_desativacao);

    $query = "UPDATE convidado 
              SET dt_desativacao = NOW(), 
                  tx_usuario_desativacao = '$usuario_desativacao',
                  bl_confirmacao = NULL
              WHERE codigo_convidado = '$codigo_convidado'";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao desativar convidado: " . mysqli_error($dbc));
    }

    return $result;
}