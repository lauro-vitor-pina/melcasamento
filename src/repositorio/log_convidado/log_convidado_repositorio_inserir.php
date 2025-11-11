<?php
// /src/repositorios/log_convidado/log_convidado_repositorio_inserir.php

function log_convidado_repositorio_inserir(
    $dbc,
    $codigo_convidado,
    $codigo_presente,
    $tx_pagina,
    $tx_acao
) {
    $codigo_presente_sql = 'NULL';

    if (empty($codigo_convidado) || empty($tx_pagina) || empty($tx_acao)) {
        throw new Exception("Código do convidado, página e ação são obrigatórios");
    }

    if ($codigo_presente != null && !empty($codigo_presente)) {
        $codigo_presente = mysqli_real_escape_string($dbc, $codigo_presente);
        $codigo_presente_sql = "'$codigo_presente'";
    }

    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);
    $tx_pagina = mysqli_real_escape_string($dbc, $tx_pagina);
    $tx_acao = mysqli_real_escape_string($dbc, $tx_acao);


    $query = "INSERT INTO log_convidado (
        codigo_log_convidado,
        codigo_convidado,
        codigo_presente,
        tx_pagina,
        tx_acao,
        dt_registro
    ) VALUES (
        UUID(),
        '$codigo_convidado',
        $codigo_presente_sql,
        '$tx_pagina',
        '$tx_acao',
        NOW()
    )";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao inserir log do convidado: " . mysqli_error($dbc));
    }

    return $result;
}
