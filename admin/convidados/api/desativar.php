<?php
// admin/api/convidados/desativar.php
require_once(__DIR__ . '/../../../src/services/convidado/convidado_service_desativar.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');

try {

    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception("Dados inválidos");
    }

    $result = convidado_service_desativar($input['codigo_convidado']);

    $resposta = [
        'success' => true,
        'message' => 'Convidado desativado com sucesso',
        'data' => $result
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