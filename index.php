<?php

require_once 'produto.php';
require_once 'carrinho.php';

$produtos = [
    new Produto(1, 'Camiseta', 59.90, 10),
    new Produto(2, 'Calça Jeans', 129.90, 5),
    new Produto(3, 'Tênis', 199.90, 3)
];

// Exibir produtos disponíveis
$carrinhoBase = new Carrinho($produtos);
$carrinhoBase->exibirProdutos();

echo "<hr>";

// CASO 1: Usuário adiciona um produto válido
echo "<h2>+ CASO 1: Adicionar produto válido (ID=1, Qtd=2)</h2>";
$carrinho1 = new Carrinho($produtos);
$resultado1 = $carrinho1->adicionarItem(1, 2);
if ($resultado1) {
    echo "+ Sucesso: Produto adicionado ao carrinho, estoque atualizado<br>";
    $carrinho1->exibirCarrinho();
} else {
    echo "- Erro: Não foi possível adicionar o produto<br>";
}
echo "<br><strong>Verificação do estoque após Caso 1:</strong><br>";
$carrinho1->exibirProdutosAposOperacoes();

echo "<hr>";

// CASO 2: Usuário tenta adicionar além do estoque
echo "<h2>+ CASO 2: Tentar adicionar além do estoque (ID=3, Qtd=10)</h2>";
$carrinho2 = new Carrinho($produtos);
$resultado2 = $carrinho2->adicionarItem(3, 10);
if ($resultado2) {
    echo "+ Sucesso: Produto adicionado ao carrinho<br>";
    $carrinho2->exibirCarrinho();
} else {
    echo "- Erro: Estoque insuficiente<br>";
}
echo "<br><strong>Verificação do estoque após Caso 2:</strong><br>";
$carrinho2->exibirProdutosAposOperacoes();

echo "<hr>";

// CASO 3: Usuário remove produto do carrinho
echo "<h2>+ CASO 3: Remover produto do carrinho (ID=2)</h2>";
$carrinho3 = new Carrinho($produtos);
// Primeiro adiciona o produto 2 para depois remover
echo "Adicionando produto 2 ao carrinho:<br>";
$carrinho3->adicionarItem(2, 1);
$carrinho3->exibirCarrinho();
echo "<br>Estoque após adicionar produto 2:<br>";
$carrinho3->exibirProdutosAposOperacoes();
echo "<br>Removendo produto 2:<br>";
$resultado3 = $carrinho3->removerItem(2);
if ($resultado3) {
    echo "+ Sucesso: Produto removido, estoque restaurado<br>";
    $carrinho3->exibirCarrinho();
} else {
    echo "- Erro: Não foi possível remover o produto<br>";
}
echo "<br><strong>Verificação do estoque após Caso 3 (remoção):</strong><br>";
$carrinho3->exibirProdutosAposOperacoes();

echo "<hr>";

// CASO 4: Aplicação de cupom de desconto
echo "<h2>+ CASO 4: Aplicar cupom DESCONTO10</h2>";
$carrinho4 = new Carrinho($produtos);
// Adiciona alguns produtos para testar o desconto
$carrinho4->adicionarItem(1, 2);
$carrinho4->adicionarItem(2, 1);
echo "Carrinho antes do cupom:<br>";
$carrinho4->exibirCarrinho();
echo "<br>Estoque antes do cupom:<br>";
$carrinho4->exibirProdutosAposOperacoes();
echo "<br>Aplicando cupom DESCONTO10:<br>";
$carrinho4->aplicarCupom('DESCONTO10');
$carrinho4->exibirCarrinho();
echo "<br><strong>Verificação do estoque após Caso 4:</strong><br>";
$carrinho4->exibirProdutosAposOperacoes();

echo "<hr>";

// Exibir produtos após operações (usando carrinho base)
echo "<h2>+ Produtos Após Operações</h2>";
$carrinhoBase->exibirProdutosAposOperacoes();

