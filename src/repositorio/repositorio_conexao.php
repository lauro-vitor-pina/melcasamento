<?php


function is_local_environment()
{

    $host = $_SERVER['SERVER_NAME'] ?? throw new Exception('Cannot determine server environment');


    $local_environments = [
        'localhost',
        '127.0.01'
    ];

    return in_array($host, $local_environments);
}



if (is_local_environment()) {
    require_once(__DIR__  . '../../configuracao/ambiente.local.php');
} else {
    require_once(__DIR__ . '../../configuracao/ambiente.dev.php');
}


function reposito_obter_conexao(): mysqli
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
