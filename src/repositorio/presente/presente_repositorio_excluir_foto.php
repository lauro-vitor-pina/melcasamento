<?php
// /src/repositorios/presente/presente_repositorio_excluir_foto.php

function presente_repositorio_excluir_foto($tx_foto_presente)
{
    if (empty($tx_foto_presente)) {
        return true; // Não há foto para excluir
    }

    // Definir caminho base de uploads
    if (!defined('UPLOAD_PATH_PRESENTES')) {
        throw new Exception("Constante UPLOAD_PATH_PRESENTES não definida em parametros.php");
    }

    $caminho_completo = __DIR__ . '/../../../' . UPLOAD_PATH_PRESENTES . $tx_foto_presente;

    // Verificar se o arquivo existe
    if (file_exists($caminho_completo)) {
        // Tentar excluir o arquivo
        if (!unlink($caminho_completo)) {
            throw new Exception("Erro ao excluir arquivo de foto: " . $caminho_completo);
        }
    }

    return true;
}
?>