<?php
// /admin/login.php
require_once(__DIR__ . '../../src/services/autorizacao/autorizacao_service.php');

// Se já estiver logado, redirecionar para dashboard
if (autorizacao_service_esta_logado() && !autorizacao_service_sessao_expirada()) {
    header('Location: index.php');
    exit();
}

$erro = '';
$usuario = '';

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (empty($usuario) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        if (autorizacao_service_fazer_login($usuario, $senha)) {
            header('Location: index.php');
            exit();
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }
}
?>

<!-- O HTML permanece exatamente igual -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEL - Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 15px 0 0;
            padding: 2rem;
            text-align: center;
            color: white;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="login-header">
                        <i class="bi bi-heart-fill display-4 mb-3"></i>
                        <h4 class="card-title mb-0">MEL Casamento</h4>
                        <p class="mb-0 opacity-75">Faça login para continuar</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if (isset($_GET['expired'])): ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Sua sessão expirou. Faça login novamente.
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($erro): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-x-circle me-2"></i>
                                <?php echo htmlspecialchars($erro); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="formLogin">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuário</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="usuario" 
                                           name="usuario" 
                                           value="<?php echo htmlspecialchars($usuario); ?>"
                                           required 
                                           autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="senha" 
                                           name="senha" 
                                           required>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-login text-white btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Entrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formLogin');
            const firstInvalid = form.querySelector('.is-invalid');
            
            if (firstInvalid) {
                firstInvalid.focus();
            } else {
                document.getElementById('usuario').focus();
            }
        });
    </script>
</body>
</html>