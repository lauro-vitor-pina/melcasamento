<?php
// api/convidados/obter_por_codigo.php
require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/convidado/convidado_repositorio_obter_por_codigo.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    if (!isset($_GET['cd']) || empty($_GET['cd'])) {
        throw new Exception("Código do convidado não informado");
    }

    $codigo_convidado = $_GET['cd'];
    $dbc = reposito_obter_conexao();
    
    $convidado = convidado_repositorio_obter_por_codigo($dbc, $codigo_convidado);
    
    repositorio_fechar_conexao($dbc);
    
    if ($convidado) {
        
        $resposta = [
            'success' => true,
            'data' => $convidado
        ];

    } else {
        $resposta = [
            'success' => false,
            'error' => 'Convidado não encontrado'
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