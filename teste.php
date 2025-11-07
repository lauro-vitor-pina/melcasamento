<?php
// vendo erros do apache no rhel = sudo cat /var/log/php-fpm/www-error.log

require_once(__DIR__ . '/src/repositorio/repositorio_conexao.php');
require_once(__DIR__ . '/src/repositorio/convidado/convidado_repositorio_inserir.php');
require_once(__DIR__ . '/src/repositorio/convidado/convidado_repositorio_editar.php');
require_once(__DIR__ . '/src/repositorio/convidado/convidado_repositorio_obter_por_codigo.php');
require_once(__DIR__ . '/src/repositorio/convidado/convidado_repositorio_obter_todos.php');

$dbc  = reposito_obter_conexao();


try {
  
    $result = convidado_repositorio_obter_todos($dbc, false, false, false, false, null, 2, 5);

    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $ex) {
    echo $ex->getMessage() . '<br>';
    var_dump($ex->getTrace());
    echo '<br>';
}


repositorio_fechar_conexao($dbc);


//testado
function testar_editar_convidado($dbc)
{
    convidado_repositorio_editar(
        $dbc,
        'acf124a2-bb56-11f0-88f0-d8778bcd5d3e',
        'lauro.vitor.edicao',
        'lauro vitor pereira pina editado',
        '027999581086',
        5,
        true
    );

    echo 'convidado editado com sucesso!';
}

//testado
function testar_inserir_convidado($conexao)
{

    convidado_repositorio_inserir($conexao, 'lauro.vitor', 'lauro vitor pereira pina', '027998142395', 1); // eu passei

    echo 'inserido com sucesso';
}
