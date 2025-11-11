<?php
// /admin/presentes/api/editar.php

require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_editar.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_upload_foto.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_excluir_foto.php');
require_once(__DIR__ . '/../../../src/services/autorizacao/autorizacao_service.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');

try {
    $usuario_logado = autorizacao_service_obter_usuario_logado();

    // Ler dados do POST (agora usando FormData)
    $input = $_POST;

    if (empty($input['codigo_presente']) || empty($input['tx_nome_presente'])) {
        throw new Exception("Código e nome do presente são obrigatórios");
    }

    $dbc = reposito_obter_conexao();

    // Gerenciamento da foto
    $fotoPresente = $input['tx_foto_presente_atual'] ?? '';

    // Se o usuário quer remover a foto atual
    if (isset($input['remover_foto']) && $input['remover_foto'] === '1' && !empty($fotoPresente)) {
        presente_repositorio_excluir_foto($fotoPresente);
        $fotoPresente = '';
    }

    // Se o usuário enviou uma nova foto
    if (isset($_FILES['tx_foto_presente']) && $_FILES['tx_foto_presente']['error'] === UPLOAD_ERR_OK) {
        // Se havia uma foto anterior, exclui
        if (!empty($input['tx_foto_presente_atual'])) {
            presente_repositorio_excluir_foto($input['tx_foto_presente_atual']);
        }
        
        // Faz upload da nova foto
        $fotoPresente = presente_repositorio_upload_foto($_FILES['tx_foto_presente']);
    }

    $resultado = presente_repositorio_editar(
        $dbc,
        $input['codigo_presente'],
        $input['tx_nome_presente'],
        $input['tx_descricao_presente'] ?? '',
        $fotoPresente,
        $usuario_logado['nome']
    );

    repositorio_fechar_conexao($dbc);

    $resposta = [
        'success' => true,
        'message' => 'Presente atualizado com sucesso',
        'data' => $resultado
    ];
} catch (Exception $e) {
    http_response_code(500);
    $resposta = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

echo json_encode($resposta);
?>