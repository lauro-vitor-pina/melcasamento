<?php
require_once(__DIR__ . '/../autorizacao/autorizacao_service.php');
require_once(__DIR__ . '/../../repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../repositorio/convidado/convidado_repositorio_inserir.php');
require_once(__DIR__ . '/../../repositorio/convidado/convidado_repositorio_editar.php');
require_once(__DIR__ . '/../../repositorio/convidado/convidado_repositorio_existe_nome.php');
require_once(__DIR__ . '/../../repositorio/convidado/convidado_repositorio_existe_telefone.php');


function convidado_service_registrar($tx_nome_convidado,$tx_telefone_convidado, $nu_qtd_pessoas, $bl_mensagem_enviada, $bl_confirmado,$codigo_convidado): bool {

    $dbc = null;

    $result = false;

    try {

        $usuario_logado = autorizacao_service_obter_usuario_logado();

        $dbc = reposito_obter_conexao();

        convidado_service_validar_registro(
            $dbc,
            $tx_nome_convidado,
            $tx_telefone_convidado,
            $codigo_convidado
        );

        $tx_usuario_registro = $usuario_logado['nome'];

        if ($codigo_convidado == null || empty($codigo_convidado)) {

            $result = convidado_repositorio_inserir(
                $dbc,
                $tx_usuario_registro,
                $tx_nome_convidado,
                $tx_telefone_convidado,
                $nu_qtd_pessoas
            );
        } else {

            $result = convidado_repositorio_editar(
                $dbc,
                $tx_usuario_registro,
                $tx_nome_convidado,
                $tx_telefone_convidado,
                $nu_qtd_pessoas,
                $bl_mensagem_enviada,
                $bl_confirmado,
                $codigo_convidado
            );
        }

        return $result;
        
    } catch (Exception $e) {
        throw new Exception("Erro ao registrar convidado: " . $e->getMessage());
    } finally {
        repositorio_fechar_conexao($dbc);
    }
}

function convidado_service_validar_registro($dbc, $tx_nome_convidado, $tx_telefone_convidado, $codigo_convidado)
{
    if (empty($tx_nome_convidado)) {
        throw new Exception("Nome do convidado é obrigatório");
    }

    if (empty($tx_telefone_convidado)) {
        throw new Exception("Telefone do convidado é obrigatório");
    }

    $telefone_numeros = preg_replace('/\D/', '', $tx_telefone_convidado);

    if (strlen($telefone_numeros) != 11) {
        throw new Exception("Telefone do convidado é inválido");
    }

    if (convidado_repositorio_existe_nome($dbc, $tx_nome_convidado, $codigo_convidado)) {
        throw new Exception("Nome do convidado já está cadastrado");
    }

    if (convidado_repositorio_existe_telefone($dbc, $tx_telefone_convidado, $codigo_convidado)) {
        throw new Exception("Telefone do convidado já está cadastrado");
    }
}
