Nome: Ítalo Ricci RA:1993169

Nome: José Henrique Ribeiro dos Santos RA:1994042

------------------------------------------------------------------------

Instruções de Execução 

1.  Instale e abra o XAMPP.

2.  De Start no "apache".

3.  Verifique se a pasta do projeto está em:

        C:\xampp\htdocs\

4.  Abra no navegador:

        http://localhost/p1/

------------------------------------------------------------------------

Funcionalidades

-   Exibir produtos disponíveis (nome, preço, estoque).
-   Adicionar itens ao carrinho com validações:
    -   Produto existente.
    -   Quantidade maior que zero.
    -   Estoque suficiente.
-   Remover itens do carrinho.
-   Atualização automática de estoque.
-   Exibir itens do carrinho e cálculo do total.
-   Aplicação de cupom de desconto de 10% (DESCONTO10).

Regras de Negócio

-   Não é possível adicionar quantidade menor ou igual a zero.
-   Não é possível adicionar produtos além do estoque disponível.
-   O carrinho reflete a quantidade real de estoque.
-   Apenas cupons pré-definidos são aceitos.

Limitações

-   Estoque não é persistido em banco de dados (zerado ao reiniciar).
-   Não há interface gráfica (uso em navegador com **echo/HTML**).
-   O carrinho não mantém sessão entre diferentes usuários.

------------------------------------------------------------------------

Exemplos de Uso (Casos de Teste)

Caso 1: Adicionar produto válido

$carrinho->adicionarItem(1, 2);

Saída esperada:

    Sucesso: Produto adicionado ao carrinho
    Total: R$ 119,80

------------------------------------------------------------------------

Caso 2: Tentar adicionar além do estoque

$carrinho->adicionarItem(3, 10);

Saída esperada:

    Erro: Estoque insuficiente

------------------------------------------------------------------------

Caso 3: Remover produto

$carrinho->removerItem(2);

Saída esperada:

    Sucesso: Produto removido do carrinho, estoqu restaurado

------------------------------------------------------------------------

Caso 4: Aplicar cupom

$carrinho->aplicarCupom('DESCONTO10');

Saída esperada:

    Cupom aplicado: DESCONTO10 (10% de desconto)
