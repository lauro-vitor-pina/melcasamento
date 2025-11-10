<?php
// /src/services/autorizacao_service.php
require_once(__DIR__ . '../../../configuracao/usuarios.php');

/**
 * Verifica se o usuário está autenticado
 * Redireciona para login se não estiver
 */
function autorizacao_service_verificar_autenticacao() {
    // Verificar se o usuário está logado
    if (!autorizacao_service_esta_logado()) {
        autorizacao_service_redirecionar_para_login();
    }
    
    // Verificar se o cookie ainda é válido (1 hora)
    if (autorizacao_service_sessao_expirada()) {
        autorizacao_service_redirecionar_para_login('expired=1');
    }
    
    return [
        'usuario' => $_COOKIE['usuario_logado'],
        'nome' => $_COOKIE['usuario_nome']
    ];
}

/**
 * Verifica se o usuário está logado
 */
function autorizacao_service_esta_logado() {
    return isset($_COOKIE['usuario_logado']) && isset($_COOKIE['usuario_nome']);
}

/**
 * Verifica se a sessão expirou
 */
function autorizacao_service_sessao_expirada() {
    return !isset($_COOKIE['usuario_expira']) || time() > $_COOKIE['usuario_expira'];
}

/**
 * Realiza o login do usuário
 */
function autorizacao_service_fazer_login($usuario, $senha) {
    global $USUARIOS_AUTORIZADOS;
    
    // Verificar se o usuário existe e a senha está correta
    if (isset($USUARIOS_AUTORIZADOS[$usuario]) && $USUARIOS_AUTORIZADOS[$usuario] === $senha) {
        
        // Definir tempo de expiração (1 hora)
        $expira = time() + (60 * 60);
        
        // Definir cookies
        autorizacao_service_definir_cookies_autenticacao($usuario, $expira);
        
        return true;
    }
    
    return false;
}

/**
 * Realiza o logout do usuário
 */
function autorizacao_service_fazer_logout() {
    autorizacao_service_limpar_cookies_autenticacao();
    
    // Destruir a sessão também se estiver usando
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

/**
 * Define os cookies de autenticação
 */
function autorizacao_service_definir_cookies_autenticacao($usuario, $expira) {
    setcookie('usuario_logado', $usuario, $expira, '/');
    setcookie('usuario_nome', $usuario, $expira, '/');
    setcookie('usuario_expira', $expira, $expira, '/');
}

/**
 * Limpa os cookies de autenticação
 */
function autorizacao_service_limpar_cookies_autenticacao() {
    setcookie('usuario_logado', '', time() - 3600, '/');
    setcookie('usuario_nome', '', time() - 3600, '/');
    setcookie('usuario_expira', '', time() - 3600, '/');
}

/**
 * Redireciona para a página de login
 */
function autorizacao_service_redirecionar_para_login($parametros = '') {
    $url = '/admin/login.php';
    if (!empty($parametros)) {
        $url .= '?' . $parametros;
    }
    header('Location: ' . $url);
    exit();
}

/**
 * Obtém informações do usuário logado (sem redirecionar)
 */
function autorizacao_service_obter_usuario_logado() {
    if (autorizacao_service_esta_logado() && !autorizacao_service_sessao_expirada()) {
        return [
            'usuario' => $_COOKIE['usuario_logado'],
            'nome' => $_COOKIE['usuario_nome']
        ];
    }
    return null;
}

/**
 * Obtém o nome do usuário logado (apenas o nome)
 */
function autorizacao_service_obter_nome_usuario() {
    $usuario = autorizacao_service_obter_usuario_logado();
    return $usuario ? $usuario['nome'] : null;
}

/**
 * Verifica se um usuário específico está logado
 */
function autorizacao_service_usuario_esta_logado($usuario) {
    $usuarioLogado = autorizacao_service_obter_usuario_logado();
    return $usuarioLogado && $usuarioLogado['usuario'] === $usuario;
}
?>