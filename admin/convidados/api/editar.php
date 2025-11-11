<?php
// admin/convidados/api/inserir.php
require_once(__DIR__ . '/../../../src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/../../../src/repositorio/convidado/convidado_repositorio_editar.php');
require_once(__DIR__. '/../../../src/services/autorizacao/autorizacao_service.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');

try {
    
    $usuario_logado = autorizacao_service_obter_usuario_logado();

    // Ler dados do POST
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception("Dados inválidos");
    }

    // Validar campos obrigatórios
    if (empty($input['codigo_convidado']) || empty($input['tx_nome_convidado'])) {
        throw new Exception("Código, nome e usuário são obrigatórios");
    }


    $dbc = reposito_obter_conexao();
    
    $resultado = convidado_repositorio_editar(
        $dbc,
        $input['codigo_convidado'],
        $usuario_logado['nome'],
        $input['tx_nome_convidado'],
        $input['tx_telefone_convidado'] ?? null,
        $input['nu_qtd_pessoas'] ?? 1,
        $input['bl_mensagem_enviada'] ?? false
    );
    
    repositorio_fechar_conexao($dbc);
    
    $resposta = [
        'success' => true,
        'message' => 'Convidado atualizado com sucesso',
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