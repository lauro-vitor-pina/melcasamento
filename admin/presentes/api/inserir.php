<?php
// /admin/presentes/api/inserir.php

require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_inserir.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_upload_foto.php');
require_once(__DIR__ . '/../../../src/services/autorizacao/autorizacao_service.php');


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

try {
    $usuario_logado = autorizacao_service_obter_usuario_logado();

    // Ler dados do POST
    $input = $_POST;


    if (empty($input['tx_nome_presente'])) {
        throw new Exception("Nome do presente é obrigatório");
    }
    
    if(empty($_FILES['tx_foto_presente']) || $_FILES['tx_foto_presente']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Foto do presente é obrigatória");
    }

    $tx_foto_presente = presente_repositorio_upload_foto($_FILES['tx_foto_presente']);

    $dbc = reposito_obter_conexao();

    $resultado = presente_repositorio_inserir(
        $dbc,
        $input['tx_nome_presente'],
        $input['tx_descricao_presente'] ?? '',
        $tx_foto_presente,
        $usuario_logado['nome']
    );

    repositorio_fechar_conexao($dbc);

    $resposta = [
        'success' => true,
        'message' => 'Presente inserido com sucesso',
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



