<?php

class Produto
{
    private int $id;
    private string $nome;
    private float $preco;
    private int $estoque;

    public function __construct(int $id, string $nome, float $preco, int $estoque)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->estoque = $estoque;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function getEstoque(): int
    {
        return $this->estoque;
    }

    public function reduzirEstoque(int $quantidade): bool
    {
        if ($quantidade <= 0) {
            echo "Quantidade deve ser maior que zero<br>";
            return false;
        }

        if ($quantidade > $this->estoque) {
            echo "Estoque insuficiente<br>";
            return false;
        }

        $this->estoque -= $quantidade;
        return true;
    }

    public function adicionarEstoque(int $quantidade): bool
    {
        if ($quantidade <= 0) {
            echo "Quantidade deve ser maior que zero<br>";
            return false;
        }

        $this->estoque += $quantidade;
        return true;
    }

    public function temEstoque(int $quantidade): bool
    {
        return $this->estoque >= $quantidade;
    }
}
