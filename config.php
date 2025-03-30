<?php
// Função para ler o preço atual do arquivo
function lerPreco() {
    if (file_exists('preco.txt')) {
        return floatval(file_get_contents('preco.txt'));
    } else {
        // Preço padrão se o arquivo não existir
        file_put_contents('preco.txt', '5.50');
        return 5.50;
    }
}

// Função para salvar o novo preço
function salvarPreco($novoPreco) {
    file_put_contents('preco.txt', $novoPreco);
}

// Preço atual do combustível
$precoCombustivel = lerPreco();
?>