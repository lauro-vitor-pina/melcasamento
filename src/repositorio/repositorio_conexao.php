<?php 

require_once(__DIR__ . '../../configuracao/ambiente.local.php');

function reposito_obter_conexao() : mysqli
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT) or die('Faield to connect Database');

    return $dbc;
}

function repositorio_fechar_conexao(mysqli $dbc)
{
    if ($dbc != null) {
        mysqli_close($dbc);
    }
}