<?php
// /src/repositorios/presente/presente_repositorio_editar.php

function presente_repositorio_editar(
    $dbc,
    $codigo_presente,
    $tx_nome_presente,
    $tx_descricao_presente,
    $tx_foto_presente,
    $tx_usuario_atualizacao
) {
    $codigo_presente = mysqli_real_escape_string($dbc, $codigo_presente);
    $tx_nome_presente = mysqli_real_escape_string($dbc, $tx_nome_presente);
    $tx_descricao_presente = mysqli_real_escape_string($dbc, $tx_descricao_presente);
    $tx_foto_presente = mysqli_real_escape_string($dbc, $tx_foto_presente);
    $tx_usuario_atualizacao = mysqli_real_escape_string($dbc, $tx_usuario_atualizacao); 

    $query = "UPDATE presente
               SET dt_atualizacao = NOW(),
                   tx_usuario_atualizacao = '$tx_usuario_atualizacao',
                   tx_nome_presente = '$tx_nome_presente',
                   tx_descricao_presente = '$tx_descricao_presente',
                   tx_foto_presente = '$tx_foto_presente'
              WHERE codigo_presente = '$codigo_presente'";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao atualizar presente: " . mysqli_error($dbc));
    }

    return $result;
}
?>