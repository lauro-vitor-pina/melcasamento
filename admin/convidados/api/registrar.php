<?php
require_once(__DIR__ . '/../../../src/services/convidado/convidado_service_registrar.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

try {

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception("Dados inválidos");
    }


    $result = convidado_service_registrar(
        $input['tx_nome_convidado'] ?? '', 
        $input['tx_telefone_convidado'] ?? '',
        $input['nu_qtd_pessoas'] ?? 1, 
        $input['bl_mensagem_enviada'] ?? null,
        $input['bl_confirmacao'] ?? null,
        $input['codigo_convidado'] ?? null
    );

    $resposta = [
        'success' => true,
        'message' => 'Convidado inserido com sucesso',
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
