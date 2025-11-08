<?php

// Incluir dependências

require_once(__DIR__ . '../../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '../../../../src/repositorio/convidado/convidado_repositorio_obter_todos.php');
require_once 'listar-convidados.component.php';

/**
 * Endpoint API para obter convidados
 * Retorna JSON para requisições AJAX
 */

// Headers para API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');


try {

    // Processar parâmetros da requisição
    $resultParametrosHttp = obterParametrosHttpListarConvidados();

    $dbc = reposito_obter_conexao();
    // Buscar convidados
    $resultado = convidado_repositorio_obter_todos(
        $dbc,
        $resultParametrosHttp['bl_somente_mensagem_nao_enviada'],
        $resultParametrosHttp['bl_somente_nao_confirmado'],
        $resultParametrosHttp['bl_somente_nao_respondido'],
        $resultParametrosHttp['bl_somente_nao_visualizado'],
        $resultParametrosHttp['tx_consulta'],
        $resultParametrosHttp['nu_page_number'],
        $resultParametrosHttp['nu_page_size']
    );

    repositorio_fechar_conexao($dbc);
    
    // Preparar resposta
    $resposta = [
        'success' => true,
        'data' => $resultado,
        'html' => renderConvidadosCards($resultado['rows'])
    ];

    // Log para debug (remover em produção)
    error_log("API Convidados: " . $resultado['nu_count'] . " encontrados");
} catch (Exception $e) {

    http_response_code(500);

    $resposta = [
        'success' => false,
        'error' => $e->getMessage(),
        'data' => null,
        'html' => ''
    ];

    error_log("Erro API Convidados: " . $e->getMessage());
}

// Retornar JSON
echo json_encode($resposta, JSON_PRETTY_PRINT);
