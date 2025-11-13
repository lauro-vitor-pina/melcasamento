<?php

function presente_repositorio_indicadores_reservados($dbc)
{
    $query = 'SELECT COUNT(1) AS result
              FROM presente 
              WHERE dt_desativacao IS NULL 
              AND codigo_convidado IS NOT NULL';

    $query_result = mysqli_query($dbc, $query);

    $result = mysqli_fetch_array($query_result)['result'];

    return $result;
}

function presente_repositorio_indicadores_nao_reservados($dbc)
{

    $query = 'SELECT COUNT(1)  AS result
              FROM presente 
              WHERE dt_desativacao IS NULL 
              AND codigo_convidado IS NULL';


    $query_result = mysqli_query($dbc, $query);

    $result = mysqli_fetch_array($query_result)['result'];

    return $result;
}
