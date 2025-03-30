<?php
require_once 'config.php';

// Configura o fuso horário para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Credenciais de acesso
$usuarioPadrao = 'admin';
$senhaPadrao = 'admin123';

// Inicia a sessão e verifica login
session_start();

// Redireciona para login se não estiver autenticado
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$mensagem = '';
$precoAtual = $precoCombustivel;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logout
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
    // Atualização de preço
    $novoPreco = floatval(str_replace(',', '.', $_POST['preco']));
    
    if ($novoPreco > 0) {
        salvarPreco($novoPreco);
        $precoAtual = $novoPreco;
        $mensagem = '<div class="alert alert-success">Preço atualizado com sucesso!</div>';
    } else {
        $mensagem = '<div class="alert alert-danger">O preço deve ser maior que zero!</div>';
    }
}

function dataModificacaoBrasilia() {
    if (file_exists('preco.txt')) {
        $timestamp = filemtime('preco.txt');
        return date('d/m/Y H:i:s', $timestamp);
    }
    return 'Nunca';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Configuração de Preço</title>
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
                        <h3 class="text-center mb-0">Área Administrativa</h3>
                        <div>
                            <form method="POST" class="d-inline">
                                <button type="submit" name="logout" class="btn btn-sm btn-outline-light me-2">Sair</button>
                            </form>
                            <span class="theme-switcher" onclick="toggleTheme()">
                                <i id="theme-icon" class="bi"></i>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= $mensagem ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço Atual do Combustível (R$/L):</label>
                                <input type="text" class="form-control" id="preco" name="preco" 
                                       value="<?= number_format($precoAtual, 2, '.', '') ?>" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">Atualizar Preço</button>
                                <a href="index.php" class="btn btn-secondary">Voltar para Home</a>
                            </div>
                        </form>
                        
                        <div class="mt-4 p-3 bg-light rounded">
                            <h5>Informações:</h5>
                            <p>Preço atual: <strong>R$ <?= number_format($precoAtual, 2) ?></strong> por litro</p>
                            <p>Última atualização: <?= dataModificacaoBrasilia() ?></p>
                        </div>
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