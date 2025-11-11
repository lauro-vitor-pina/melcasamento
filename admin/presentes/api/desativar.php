<?php
// /admin/presentes/api/desativar.php

require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_desativar.php');
require_once(__DIR__ . '/../../../src/services/autorizacao/autorizacao_service.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');

try {
    $usuario_logado = autorizacao_service_obter_usuario_logado();

    // Ler dados do POST
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception("Dados inválidos");
    }

    // Validar campos obrigatórios
    if (empty($input['codigo_presente'])) {
        throw new Exception("Código do presente é obrigatório");
    }

    $dbc = reposito_obter_conexao();
    
    $resultado = presente_repositorio_desativar(
        $dbc,
        $input['codigo_presente'],
        $usuario_logado['nome']
    );
    
    repositorio_fechar_conexao($dbc);
    
    $resposta = [
        'success' => true,
        'message' => 'Presente desativado com sucesso',
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