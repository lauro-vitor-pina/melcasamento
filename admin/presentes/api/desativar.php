<?php
require_once(__DIR__ . '/../../../src/services/presente/presente_service_desativar.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');

try {

    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception("Dados inválidos");
    }

    $result = presente_service_desativar($input['codigo_presente']);
    
    $response = [
        'success' => true,
        'message' => 'Presente desativado com sucesso',
        'data' => $result
    ];

} catch (Exception $e) {
    http_response_code(500);

    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

echo json_encode($response);
?>