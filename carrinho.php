<?php
require_once 'produto.php';

class Carrinho {
    private array $itens = [];
    private array $produtos;
    private float $desconto = 0;

    public function __construct(array $produtos){
        $this->produtos = $produtos;
    }

    public function adicionarItem(int $idProduto, int $quantidade): bool {
        $produto = null;
        foreach ($this->produtos as $p) {
            if ($p->getId() === $idProduto) {
                $produto = $p;
                break;
            }
        }

        if (!$produto) {
            echo "Produto não encontrado<br>";
            return false;
        }

        if ($quantidade <= 0) {
            echo "Quantidade inválida<br>";
            return false;
        }

        if ($produto->getEstoque() < $quantidade) {
            echo "Estoque insuficiente para {$produto->getNome()}<br>";
            return false;
        }

        $produto->reduzirEstoque($quantidade);

        foreach ($this->itens as &$item) {
            if ($item['produto']->getId() === $produto->getId()) {
                $item['quantidade'] += $quantidade;
                echo "+ {$quantidade}x {$produto->getNome()} adicionado ao carrinho<br>";
                return true;
            }
        }

        $this->itens[] = [
            'produto' => $produto,
            'quantidade' => $quantidade
        ];
        echo "+ {$quantidade}x {$produto->getNome()} adicionado ao carrinho<br>";
        return true;
    }

    public function removerItem(int $idProduto, ?int $quantidade = null): bool {
        foreach ($this->itens as $key => &$item) {
            if ($item['produto']->getId() === $idProduto) {
                $qtdNoCarrinho = $item['quantidade'];
                $nomeProduto = $item['produto']->getNome();


                if ($quantidade === null || $quantidade >= $qtdNoCarrinho) {
                    $item['produto']->adicionarEstoque($qtdNoCarrinho);
                    unset($this->itens[$key]);
                    echo "- Todas as unidades de {$nomeProduto} removidas do carrinho<br>";
                } else {
                    $item['produto']->adicionarEstoque($quantidade);
                    $item['quantidade'] -= $quantidade;
                    echo "- {$quantidade} unidade(s) de {$nomeProduto} removida(s) do carrinho<br>";
                }

                return true;
            }
        }

        echo "Produto não encontrado no carrinho<br>";
        return false;
    }

    public function exibirCarrinho(): void {
        if (empty($this->itens)) {
            echo "Carrinho vazio<br>";
            return;
        }

        echo "<h3>Itens no carrinho:</h3>";
        $total = 0;
        foreach ($this->itens as $item) {
            $subtotal = $item['quantidade'] * $item['produto']->getPreco();
            echo "- {$item['quantidade']}x {$item['produto']->getNome()} (R$ {$item['produto']->getPreco()} cada) = R$ " . number_format($subtotal, 2, ',', '.') . "<br>";
            $total += $subtotal;
        }

        if ($this->desconto > 0) {
            $valorDesconto = $total * $this->desconto;
            $totalComDesconto = $total - $valorDesconto;
            echo "<strong>Total: R$ " . number_format($total, 2, ',', '.') . "</strong><br>";
            echo "<strong>Desconto aplicado: -" . ($this->desconto * 100) . "% (R$ " . number_format($valorDesconto, 2, ',', '.') . ")</strong><br>";
            echo "<strong>Total com desconto: R$ " . number_format($totalComDesconto, 2, ',', '.') . "</strong><br>";
        } else {
            echo "<strong>Total: R$ " . number_format($total, 2, ',', '.') . "</strong><br>";
        }
    }

    public function exibirProdutos(): void {
        echo "<h3>Produtos disponíveis:</h3>";
        foreach ($this->produtos as $produto) {
            echo "- {$produto->getNome()} (ID: {$produto->getId()}) | Preço: R$ {$produto->getPreco()} | Estoque: {$produto->getEstoque()}<br>";
        }
    }

    public function exibirProdutosAposOperacoes(): void {
        echo "<h3>Produtos no estoque após operações:</h3>";
        foreach ($this->produtos as $produto) {
            echo "- {$produto->getNome()} | Estoque: {$produto->getEstoque()}<br>";
        }
    }

    public function aplicarCupom(string $codigo): bool {
        $cupons = [
            'DESCONTO10' => 0.10,
            'DESCONTO20' => 0.20
        ];

        if (isset($cupons[$codigo])) {
            $this->desconto = $cupons[$codigo];
            echo "Cupom {$codigo} aplicado com sucesso (" . ($this->desconto * 100) . "%)<br>";
            return true;
        }

        echo "Cupom inválido<br>";
        return false;
    }
}
