<?php
// admin/convidados/api/obter_todos.php
require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/convidado/convidado_repositorio_obter_todos.php');

/**
 * Endpoint API para obter convidados - Versão JSON Puro
 * Retorna apenas dados, sem HTML
 */

// Headers para API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {

    $parametros = [
        'bl_somente_mensagem_nao_enviada' => isset($_GET['msg_nao_enviada']) ? (bool)$_GET['msg_nao_enviada'] : false,
        'bl_somente_nao_confirmado' => isset($_GET['nao_confirmado']) ? (bool)$_GET['nao_confirmado'] : false,
        'bl_somente_nao_respondido' => isset($_GET['nao_respondido']) ? (bool)$_GET['nao_respondido'] : false,
        'bl_somente_nao_visualizado' => isset($_GET['nao_visualizado']) ? (bool)$_GET['nao_visualizado'] : false,
        'tx_consulta' => $_GET['q'] ?? '',
        'nu_page_number' => max(1, intval($_GET['page'] ?? 1)),
        'nu_page_size' => max(1, intval($_GET['limit'] ?? 12))
    ];
    
    $dbc = reposito_obter_conexao();
    
    // Buscar convidados
    $resultado = convidado_repositorio_obter_todos(
        $dbc,
        $parametros['bl_somente_mensagem_nao_enviada'],
        $parametros['bl_somente_nao_confirmado'],
        $parametros['bl_somente_nao_respondido'],
        $parametros['bl_somente_nao_visualizado'],
        $parametros['tx_consulta'],
        $parametros['nu_page_number'],
        $parametros['nu_page_size']
    );

    repositorio_fechar_conexao($dbc);
    
    // Preparar resposta (APENAS DADOS)
    $resposta = [
        'success' => true,
        'data' => $resultado
    ];

} catch (Exception $e) {
    http_response_code(500);
    
    $resposta = [
        'success' => false,
        'error' => $e->getMessage(),
        'data' => null
    ];
    
    error_log("Erro API Convidados: " . $e->getMessage());
}

// Retornar JSON
echo json_encode($resposta);

