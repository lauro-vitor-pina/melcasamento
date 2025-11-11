<?php
// /src/repositorios/presente/presente_repositorio_inserir.php

function presente_repositorio_inserir(
    $dbc,
    $tx_nome_presente,
    $tx_descricao_presente,
    $tx_foto_presente,
    $tx_usuario_registro
) {
    $tx_nome_presente = mysqli_real_escape_string($dbc, $tx_nome_presente);
    $tx_descricao_presente = mysqli_real_escape_string($dbc, $tx_descricao_presente);
    $tx_foto_presente = mysqli_real_escape_string($dbc, $tx_foto_presente);
    $tx_usuario_registro = mysqli_real_escape_string($dbc, $tx_usuario_registro);

    $query = "INSERT INTO presente (
        codigo_presente,
        tx_nome_presente, 
        tx_descricao_presente, 
        tx_foto_presente, 
        dt_registro, 
        tx_usuario_registro
    ) VALUES (UUID(),'$tx_nome_presente', '$tx_descricao_presente', '$tx_foto_presente', NOW(), '$tx_usuario_registro')";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao inserir presente: " . mysqli_error($dbc));
    }

    return $result;
}