<?php

function presente_repositorio_remover_reserva_do_convidado($dbc, $codigo_convidado, $usuario_atualizacao)
{
    if ($codigo_convidado == null || empty($codigo_convidado)) {
        throw new Exception("Código do convidado inválido.");
    }

    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);

    $query = "UPDATE presente
              SET 
                codigo_convidado = NULL, 
                dt_atualizacao = NOW(),
                tx_usuario_atualizacao = '$usuario_atualizacao'
              WHERE codigo_convidado = '$codigo_convidado'";

    $query_result = mysqli_query($dbc, $query);

    if (!$query_result) {
        throw new Exception("Erro ao remover presentes do convidado: " . mysqli_error($dbc));
    }

    return $query_result;
}
