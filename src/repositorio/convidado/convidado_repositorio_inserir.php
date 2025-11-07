<?php

//ok
function convidado_repositorio_inserir(
    $dbc,
    $tx_usuario_registro,
    $tx_nome_convidado,
    $tx_telefone_convidado,
    $nu_qtd_pessoas
) {

    $tx_usuario_registro = mysqli_real_escape_string($dbc, $tx_usuario_registro);
    $tx_nome_convidado = mysqli_real_escape_string($dbc, $tx_nome_convidado);
    $tx_telefone_convidado = mysqli_real_escape_string($dbc, $tx_telefone_convidado);
    $nu_qtd_pessoas = intval($nu_qtd_pessoas);

    $query = "INSERT INTO convidado (
        codigo_convidado, 
        dt_registro, 
        tx_usuario_registro,
        tx_nome_convidado, 
        tx_telefone_convidado,
        nu_qtd_pessoas
    ) VALUES (UUID(), NOW(), '$tx_usuario_registro', '$tx_nome_convidado', '$tx_telefone_convidado', $nu_qtd_pessoas)";


    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao inserir convidado: " . mysqli_error($dbc));
    }


    return $result;
}
