<?php
// /admin/logout.php
require_once(__DIR__ . '../../src/services/autorizacao/autorizacao_service.php');

autorizacao_service_fazer_logout();

// Redirecionar para login com mensagem
header('Location: login.php?logout=1');
exit();
?>