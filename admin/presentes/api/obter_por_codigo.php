<?php
require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_obter_por_codigo.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    if (!isset($_GET['cd']) || empty($_GET['cd'])) {
        throw new Exception("Código do presente não informado");
    }

    $codigo_presente = $_GET['cd'];
    $dbc = reposito_obter_conexao();
    
    $presente = presente_repositorio_obter_por_codigo($dbc, $codigo_presente);
    
    repositorio_fechar_conexao($dbc);
    
    if ($presente) {
        $resposta = [
            'success' => true,
            'data' => $presente
        ];
    } else {
        $resposta = [
            'success' => false,
            'error' => 'Presente não encontrado'
        ];
    }

} catch (Exception $e) {
    http_response_code(500);
    $resposta = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

echo json_encode($resposta);
?>