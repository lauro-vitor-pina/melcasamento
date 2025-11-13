<?php

function presente_repositorio_existe_reserva($dbc, $codigo_presente): bool
{
    if ($codigo_presente == null || empty($codigo_presente)) {
        throw new Exception('Código do presente é obrigatório');
    } else {
        $codigo_presente = mysqli_real_escape_string($dbc, $codigo_presente);
    }

    $query = "SELECT 1 as existe
              FROM presente 
              WHERE codigo_presente = '$codigo_presente'
              AND dt_desativacao IS NULL
              AND codigo_convidado IS NOT NULL";

    $query_result = mysqli_query($dbc, $query);

    if (!$query_result) {
        throw new Exception('Erro ao verificar reserva de presente: ' . mysqli_error($dbc));
    }

    $result = mysqli_fetch_array($query_result);

    if (isset($result['existe']) && $result['existe'] == 1) {
        return true;
    }

    return false;
}
