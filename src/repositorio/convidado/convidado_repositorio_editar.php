<?php

//src/repositorio/convidado/convidado_repositorio_editar.php
function convidado_repositorio_editar(
    $dbc,
    $tx_usuario_atualizacao,
    $tx_nome_convidado,
    $tx_telefone_convidado,
    $nu_qtd_pessoas,
    $bl_mensagem_enviada,
    $bl_confirmacao,
    $codigo_convidado,
) {
    $codigo_convidado = mysqli_real_escape_string($dbc, $codigo_convidado);
    $tx_usuario_atualizacao = mysqli_real_escape_string($dbc, $tx_usuario_atualizacao);
    $tx_nome_convidado = mysqli_real_escape_string($dbc, $tx_nome_convidado);
    $tx_telefone_convidado = mysqli_real_escape_string($dbc, $tx_telefone_convidado);
    $nu_qtd_pessoas = intval($nu_qtd_pessoas);
    $bl_mensagem_enviada = $bl_mensagem_enviada ? 1 : 0;

    $bl_confirmacao_sql = '';

    if ($bl_confirmacao === 'null' || $bl_confirmacao === null)
        $bl_confirmacao_sql = 'NULL';
    else
        $bl_confirmacao_sql = $bl_confirmacao;



    $query = "UPDATE convidado
               SET dt_atualizacao = NOW(),
                   tx_usuario_atualizacao = '$tx_usuario_atualizacao',
                   tx_nome_convidado = '$tx_nome_convidado',
                   tx_telefone_convidado = '$tx_telefone_convidado',
                   bl_mensagem_enviada = $bl_mensagem_enviada,
                   nu_qtd_pessoas = $nu_qtd_pessoas,
                   bl_confirmacao = $bl_confirmacao_sql 
              WHERE codigo_convidado = '$codigo_convidado'";

    $result = mysqli_query($dbc, $query);

    if (!$result) {
        throw new Exception("Erro ao atualizar convidado: " . mysqli_error($dbc));
    }

    return $result;
}
