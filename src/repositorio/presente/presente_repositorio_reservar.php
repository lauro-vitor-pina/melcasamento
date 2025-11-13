<?php

function presente_repositorio_reservar($dbc, $codigo_convidado, $codigo_presente, $usuario_atualizacao)
{
    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);
    $codigo_presente = mysqli_real_escape_string($dbc, $codigo_presente);
    $usuario_atualizacao = mysqli_real_escape_string($dbc, $usuario_atualizacao);

    $query = "UPDATE presente 
              SET codigo_convidado = '$codigo_convidado',
                  dt_atualizacao = NOW(),
                  tx_usuario_atualizacao = '$usuario_atualizacao'
              WHERE codigo_presente = '$codigo_presente' ";

    $query_result = mysqli_query($dbc, $query);

    if (!$query_result) {
        throw new Exception('Erro ao fazer reserva do presente : ' . mysqli_error($dbc));
    }

    return $query_result;
}
