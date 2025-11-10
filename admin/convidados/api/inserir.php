<?php
// api/convidados/inserir.php
require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/convidado/convidado_repositorio_inserir.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

try {
    // Ler dados do POST
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception("Dados inválidos");
    }

    // Validar campos obrigatórios
    if (empty($input['tx_nome_convidado'])) {
        throw new Exception("Nome do convidado é obrigatório");
    }

    if (empty($input['tx_telefone_convidado'])) {
        throw new Exception("Nome do convidado é obrigatório");
    }

    $dbc = reposito_obter_conexao();
    
    $resultado = convidado_repositorio_inserir(
        $dbc,
        'usuario.registro.teste',
        $input['tx_nome_convidado'],
        $input['tx_telefone_convidado'],
        $input['nu_qtd_pessoas'] ?? 1
    );
    
    repositorio_fechar_conexao($dbc);
    
    $resposta = [
        'success' => true,
        'message' => 'Convidado inserido com sucesso',
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