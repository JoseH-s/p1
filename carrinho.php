<?php
require_once 'produto.php';
class Carrinho {
    private $itens = array();
    private $produtos = [];

    public function __construct(array $produtos = []) {
        $this->produtos = $produtos;
        $this->itens = [];
    }
    
    public function getProdutos(): array {
        return $this->produtos;
    }
    
    public function getItens():array {
        return $this->itens;
    }

    public function setItens(array $itens) {
        $this->itens = $itens;
    }
    
    public function adicionarItem(int $idProduto, int $quantidade): ?Produto {
        // 1. Busca produto por ID
        $produto = $this->findById($idProduto);
        
        // 2. Valida se produto existe
        if (!$produto) {
            echo "Produto não encontrado<br>";
            return null;
        }
        
        // 3. Valida quantidade
        if ($quantidade <= 0) {
            echo "Quantidade deve ser maior que zero<br>";
            return null;
        }
        
        // 4. Valida estoque
        if ($produto->getEstoque() < $quantidade) {
            echo "Estoque insuficiente<br>";
            return null;
        }
        
        // 5. Reduz estoque do produto
        if (!$produto->reduzirEstoque($quantidade)) {
            return null;
        }
        
        // 6. Adiciona produto no carrinho
        $this->itens[] = $produto;
        echo "Produto adicionado com sucesso<br>";
        return $produto;
    }
    
    public function findById(int $idProduto): ?Produto {
        foreach ($this->produtos as $produto) {
            if ($produto->getId() == $idProduto) {
                return $produto;
            }
        }
        return null;
    }

    
    
    public function exibirCarrinho(): void {
        echo "+ Itens do Carrinho";
        
        if (empty($this->itens)) {
            echo "<p>Carrinho vazio</p>";
            return;
        }
        
        foreach ($this->itens as $item) {
            echo "<p>ID: {$item->getId()} | Nome: {$item->getNome()} | Preço: R$ " . number_format($item->getPreco(), 2, ',', '.') . " | Estoque: {$item->getEstoque()}</p>";
        }
        
        // Calcular e exibir total
        $total = $this->calcularTotal();
        echo "+ Total: R$ " . number_format($total, 2, ',', '.') . "";
        
        if ($this->cupomAplicado) {
            echo "<p><strong>Cupom aplicado:</strong> {$this->cupomAplicado} ({$this->percentualDesconto}% de desconto)</p>";
        }
    }
    
    
    public function exibirProdutos(): void {
        echo "+ Produtos Disponíveis";
        foreach ($this->produtos as $produto) {
            echo "<p>ID: {$produto->getId()} | {$produto->getNome()} | R$ " . number_format($produto->getPreco(), 2, ',', '.') . " | Estoque: {$produto->getEstoque()}</p>";
        }
    }

    public function executarCasosTeste(): void {
        echo "+ Casos de Teste";
        
        // Caso 1: Adicionar produto válido
        echo "+ Caso 1: Adicionar produto válido (ID=1, Qtd=2)";
        $resultado = $this->adicionarItem(1, 2);
        if ($resultado) {
            echo "+ Sucesso: Produto adicionado ao carrinho<br>";
            $this->exibirCarrinho();
        } else {
            echo "- Erro: Não foi possível adicionar o produto<br>";
        }
        
        // Caso 2: Tentar adicionar além do estoque
        echo "+ Caso 2: Tentar adicionar além do estoque (ID=3, Qtd=10)";
        $resultado = $this->adicionarItem(3, 10);
        if ($resultado) {
            echo "+ Sucesso: Produto adicionado ao carrinho<br>";
            $this->exibirCarrinho();
        } else {
            echo "- Erro: Não foi possível adicionar o produto<br>";
        }
        
        // Caso 3: Adicionar mais um produto
        echo "+ Caso 3: Adicionar outro produto (ID=2, Qtd=1)";
        $resultado = $this->adicionarItem(2, 1);
        if ($resultado) {
            echo "+ Sucesso: Produto adicionado ao carrinho<br>";
            $this->exibirCarrinho();
        } else {
            echo "- Erro: Não foi possível adicionar o produto<br>";
        }
    }

    public function exibirProdutosAposOperacoes(): void {
        echo "+ Produtos Após Operações";
        foreach ($this->produtos as $produto) {
            echo "<p>ID: {$produto->getId()} | {$produto->getNome()} | R$ " . number_format($produto->getPreco(), 2, ',', '.') . " | Estoque: {$produto->getEstoque()}</p>";
        }
    }

    public function removerProdutoCompletoComExibicao(int $idProduto): void {
        echo "+ Remover produto do carrinho (ID={$idProduto})";
        $resultado = $this->removerItem($idProduto);
        if ($resultado) {
            echo "+ Sucesso: Produto removido do carrinho<br>";
            $this->exibirCarrinho();
        } else {
            echo "- Erro: Não foi possível remover o produto<br>";
        }
    }

    private $cupomAplicado = null;
    private $percentualDesconto = 0.0;

    public function aplicarCupom(string $cupom): void {
        $cupons = [
            'DESCONTO10' => 10.0,
            'DESCONTO20' => 20.0,
            'DESCONTO5' => 5.0
        ];

        if (isset($cupons[$cupom])) {
            $this->cupomAplicado = $cupom;
            $this->percentualDesconto = $cupons[$cupom];
            echo "+ Cupom aplicado: {$cupom} ({$this->percentualDesconto}% de desconto)";
        } else {
            echo "- Cupom inválido: {$cupom}";
        }
    }

    public function calcularTotal(): float {
        $total = 0;
        foreach ($this->itens as $item) {
            $total += $item->getPreco();
        }
        
        if ($this->cupomAplicado) {
            $desconto = $total * ($this->percentualDesconto / 100);
            $total -= $desconto;
        }
        
        return $total;
    }

    public function findItemByIdCarrinho(int $idProduto): ?Produto {
        foreach ($this->itens as $item) {
            if ($item->getId() == $idProduto) {
                return $item;
            }
        }
        return null;
    }
    
    public function removerItem(int $idProduto): ?Produto {
        $item = $this->findItemByIdCarrinho($idProduto);
        if (!$item) {
            echo "Item não encontrado<br>";
            return null;
        }
        
        // Remove o item do carrinho
        $novosItens = [];
        foreach ($this->itens as $itemCarrinho) {
            if ($itemCarrinho->getId() != $idProduto) {
                $novosItens[] = $itemCarrinho;
            }
        }
        $this->itens = $novosItens;
        
        echo "+ Item removido: {$idProduto}";
        return $item;
    }
    
    
}