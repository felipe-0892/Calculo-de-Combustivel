<?php
require_once 'config.php';

// Configura o fuso horário para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Variáveis para cálculo de valor/litros
$valorTotal = 0;
$litrosCalculados = 0;
$valorCalculado = 0;
$mostrarResultadoCombustivel = false;
$tipoCalculo = 'litros'; // Padrão

// Variáveis para cálculo de autonomia
$autonomia = 0;
$consumoMedio = 0;
$litrosAutonomia = 0;
$mostrarResultadoAutonomia = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cálculo de valor/litros do combustível
    if (isset($_POST['calcular_combustivel'])) {
        $tipoCalculo = $_POST['tipo_calculo'];
        
        if ($tipoCalculo === 'litros') {
            $litros = floatval($_POST['litros']);
            $valorTotal = $litros * $precoCombustivel;
            $mostrarResultadoCombustivel = true;
        } else {
            $valor = floatval(str_replace(',', '.', $_POST['valor']));
            if ($precoCombustivel > 0) {
                $litrosCalculados = $valor / $precoCombustivel;
                $mostrarResultadoCombustivel = true;
            }
        }
    }
    
    // Cálculo de autonomia
    if (isset($_POST['calcular_autonomia'])) {
        $litrosAutonomia = floatval($_POST['litros_autonomia']);
        $consumoMedio = floatval($_POST['consumo_medio']);
        
        if ($consumoMedio > 0) {
            $autonomia = $litrosAutonomia * $consumoMedio;
            $mostrarResultadoAutonomia = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cálculo de Combustível e Autonomia</title>
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="text-center mb-0">Calculadora de Combustível</h3>
                        <span class="theme-switcher" onclick="toggleTheme()">
                            <i id="theme-icon" class="bi"></i>
                        </span>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="combustivel-tab" data-bs-toggle="tab" data-bs-target="#combustivel" type="button" role="tab">Cálculo de Combustível</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="autonomia-tab" data-bs-toggle="tab" data-bs-target="#autonomia" type="button" role="tab">Cálculo de Autonomia</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="myTabContent">
                            <!-- Aba de Cálculo de Combustível -->
                            <div class="tab-pane fade show active" id="combustivel" role="tabpanel">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Cálculo:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_calculo" id="litros" value="litros" <?= $tipoCalculo === 'litros' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="litros">
                                                Calcular valor por litros
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_calculo" id="valor" value="valor" <?= $tipoCalculo === 'valor' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="valor">
                                                Calcular litros por valor
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3" id="litros-container">
                                        <label for="litros" class="form-label">Quantidade de Litros:</label>
                                        <input type="number" step="0.01" class="form-control" id="litros" name="litros" value="<?= isset($_POST['litros']) ? $_POST['litros'] : '' ?>">
                                    </div>
                                    
                                    <div class="mb-3 d-none" id="valor-container">
                                        <label for="valor" class="form-label">Valor em Reais (R$):</label>
                                        <input type="text" class="form-control" id="valor" name="valor" value="<?= isset($_POST['valor']) ? $_POST['valor'] : '' ?>">
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="calcular_combustivel" class="btn btn-primary">Calcular</button>
                                    </div>
                                </form>

                                <?php if ($mostrarResultadoCombustivel): ?>
                                <div class="alert alert-success mt-3">
                                    <h5>Resultado:</h5>
                                    <?php if ($tipoCalculo === 'litros'): ?>
                                        <p>Litros: <?= number_format($_POST['litros'], 2) ?> L</p>
                                        <p>Preço por litro: R$ <?= number_format($precoCombustivel, 2) ?></p>
                                        <p><strong>Valor Total: R$ <?= number_format($valorTotal, 2) ?></strong></p>
                                    <?php else: ?>
                                        <p>Valor: R$ <?= number_format($_POST['valor'], 2) ?></p>
                                        <p>Preço por litro: R$ <?= number_format($precoCombustivel, 2) ?></p>
                                        <p><strong>Quantidade de Litros: <?= number_format($litrosCalculados, 2) ?> L</strong></p>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Aba de Cálculo de Autonomia -->
                            <div class="tab-pane fade" id="autonomia" role="tabpanel">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="litros_autonomia" class="form-label">Quantidade de Litros no Tanque:</label>
                                        <input type="number" step="0.01" class="form-control" id="litros_autonomia" name="litros_autonomia" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="consumo_medio" class="form-label">Consumo Médio do Veículo (km/l):</label>
                                        <input type="number" step="0.01" class="form-control" id="consumo_medio" name="consumo_medio" required>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="calcular_autonomia" class="btn btn-primary">Calcular Autonomia</button>
                                    </div>
                                </form>

                                <?php if ($mostrarResultadoAutonomia): ?>
                                <div class="alert alert-info mt-3">
                                    <h5>Resultado - Autonomia:</h5>
                                    <p>Litros no tanque: <?= number_format($litrosAutonomia, 2) ?> L</p>
                                    <p>Consumo médio: <?= number_format($consumoMedio, 2) ?> km/l</p>
                                    <p><strong>Autonomia estimada: <?= number_format($autonomia, 2) ?> km</strong></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="admin.php" class="btn btn-secondary">Área Admin</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5 py-3 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <span class="text-muted">Desenvolvido por Felipe Silva &copy 2025 - Todos os direitos reservados</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostra/oculta campos conforme o tipo de cálculo selecionado
        document.querySelectorAll('input[name="tipo_calculo"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const litrosContainer = document.getElementById('litros-container');
                const valorContainer = document.getElementById('valor-container');
                
                if (this.value === 'litros') {
                    litrosContainer.classList.remove('d-none');
                    valorContainer.classList.add('d-none');
                } else {
                    litrosContainer.classList.add('d-none');
                    valorContainer.classList.remove('d-none');
                }
            });
        });
        
        // Inicializa os campos conforme o tipo de cálculo selecionado
        document.addEventListener('DOMContentLoaded', function() {
            const tipoCalculo = document.querySelector('input[name="tipo_calculo"]:checked').value;
            if (tipoCalculo === 'valor') {
                document.getElementById('litros-container').classList.add('d-none');
                document.getElementById('valor-container').classList.remove('d-none');
            }
        });
            
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