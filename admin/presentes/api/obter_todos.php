<?php
// api/presentes/obter_todos.php
require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/presente/presente_repositorio_obter_todos.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    $parametros = [
        'bl_somente_nao_reservados' => isset($_GET['nao_reservados']) ? (bool)$_GET['nao_reservados'] : false,
        'bl_somente_reservados' => isset($_GET['reservados']) ? (bool)$_GET['reservados'] : false,
        'tx_consulta' => $_GET['q'] ?? '',
        'nu_page_number' => max(1, intval($_GET['page'] ?? 1)),
        'nu_page_size' => max(1, intval($_GET['limit'] ?? 12))
    ];
    
    $dbc = reposito_obter_conexao();
    
    $resultado = presente_repositorio_obter_todos(
        $dbc,
        $parametros['bl_somente_nao_reservados'],
        $parametros['bl_somente_reservados'],
        $parametros['tx_consulta'],
        $parametros['nu_page_number'],
        $parametros['nu_page_size']
    );

    repositorio_fechar_conexao($dbc);
    
    $resposta = [
        'success' => true,
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