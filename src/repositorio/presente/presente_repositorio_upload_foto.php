<?php 

require_once(__DIR__ . '/../../configuracao/parametros.php');

function presente_repositorio_upload_foto(array $arquivo_upload): string
{

    // Verificar se o arquivo foi enviado sem erros
    if (!isset($arquivo_upload['error']) || $arquivo_upload['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Erro no upload do arquivo: " . ($arquivo_upload['error'] ?? 'desconhecido'));
    }

    // Verificar se é uma imagem válida
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    $tipo_arquivo = mime_content_type($arquivo_upload['tmp_name']);
    $extensao = strtolower(pathinfo($arquivo_upload['name'], PATHINFO_EXTENSION));

    if (!in_array($tipo_arquivo, $tipos_permitidos) || !in_array($extensao, $extensoes_permitidas)) {
        throw new Exception("Tipo de arquivo não permitido. Apenas imagens JPG, PNG, GIF e WEBP são aceitas.");
    }

    // Verificar tamanho do arquivo (máximo 5MB)
    if ($arquivo_upload['size'] > 5 * 1024 * 1024) {
        throw new Exception("Arquivo muito grande. Máximo permitido: 5MB");
    }

    // Definir caminho base de uploads
    if (!defined('UPLOAD_PATH_PRESENTES')) {
        throw new Exception("Constante UPLOAD_PATH não definida em parametros.php");
    }

    //$caminho_base = UPLOAD_PATH;
    $caminho_completo = __DIR__ . '/../../../'. UPLOAD_PATH_PRESENTES;

    // Criar pasta se não existir
    if (!is_dir($caminho_completo)) {
        if (!mkdir($caminho_completo, 0777, true)) {
            throw new Exception("Erro ao criar pasta de upload: " . $caminho_completo);
        }
    }

    // Gerar nome único com hash
    $nome_hash = hash('sha256', uniqid(mt_rand(), true) . $arquivo_upload['name']);
    $nome_arquivo = $nome_hash . '.' . $extensao;
    $caminho_arquivo = $caminho_completo . $nome_arquivo;

    // Mover arquivo para pasta de uploads
    if (!move_uploaded_file($arquivo_upload['tmp_name'], $caminho_arquivo)) {
        throw new Exception("Erro ao mover arquivo para pasta de upload");
    }

    // Retornar apenas o nome do arquivo (sem o caminho completo)
    return $nome_arquivo;
}