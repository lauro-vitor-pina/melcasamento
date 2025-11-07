<?php 


function convidado_repositorio_confirmar_presenca($dbc, $codigo_convidado, $bl_confirmacao){
    
    $query = "UPDATE convidado
              SET bl_confirmacao = ?
              WHERE codigo_convidado = ?";

    $stmt = mysqli_prepare($dbc, $query);

    if(!$stmt) {
        throw new Exception("Erro ao preparar query: " . mysqli_error($dbc));
    }

    mysqli_stmt_bind_param($stmt, "is",  $bl_confirmacao, $codigo_convidado);

    $result = mysqli_stmt_execute($stmt);
           
    mysqli_stmt_close($stmt);

    if (!$result) {
        throw new Exception("Erro ao confirmar prensença do convidado: " . mysqli_error($dbc));
    }

    return $result;
}