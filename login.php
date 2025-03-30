<?php
require_once 'config.php';

// Configura o fuso horário para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Credenciais de acesso
$usuarioPadrao = 'admin';
$senhaPadrao = '12345';

// Verifica o login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if ($usuario === $usuarioPadrao && $senha === $senhaPadrao) {
        session_start();
        $_SESSION['logado'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $erro = 'Usuário ou senha incorretos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Área Administrativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="css/style.css" rel="stylesheet">
    <script>
    // Verifica o tema salvo ou preferência do sistema
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 
                         (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.body.setAttribute('data-bs-theme', savedTheme);
    });
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <h3 class="text-center mb-0">Acesso Restrito</h3>
                        <span class="theme-switcher" onclick="toggleTheme()">
                            <i id="theme-icon" class="bi"></i>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger"><?= $erro ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuário:</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">Entrar</button>
                                <a href="index.php" class="btn btn-secondary">Voltar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-5 py-3 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <span class="text-muted">Desenvolvido por Felipe Silva &copy; 2025 - Todos os direitos reservados</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para alternar tema
        function toggleTheme() {
            const currentTheme = document.body.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.body.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }
        
        // Atualiza o ícone conforme o tema
        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        }
        
        // Inicializa o ícone
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.body.getAttribute('data-bs-theme') || 'light';
            updateThemeIcon(currentTheme);
        });
    </script>
</body>
</html>