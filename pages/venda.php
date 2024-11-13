<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPM Wear | Vendas</title>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- ICONES BOOTSTRAP -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" href="../src/css/venda.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Importação da Sidebar -->
    <?php include('../src/template/sidebar.php'); ?>

    <!-- Header com logo e título -->
    <header class="p-3 text-white text-center">
        <img src="../src/imagens/logo2.png" alt="Logo RPM Wear" class="header-logo">
        <h1>Gerenciamento de Vendas</h1>
    </header>

    <section class="home-section">
        <div id="main-container">
            <!-- Barra de pesquisa -->
            <div id="search-container">
                <input type="text" class="form-control search-bar" name="search" id="searchInput" placeholder="Pesquisar produtos..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">

                <!-- Ícone de Carrinho com Notificação -->
                <div id="cart-icon-container" class="mt-3" style="position: relative; display: inline-block;">
                    <i class="bx bx-cart" id="cart-icon" style="font-size: 30px; cursor: pointer;"></i>
                    <!-- Notificação de Carrinho -->
                    <span id="cart-notification" class="badge bg-danger"
                        style="position: absolute; top: -8px; right: -8px; font-size: 14px; display: none;">
                        0
                    </span>
                </div>
            </div>

            <!-- Container de produtos -->
            <div id="produtos-container">
                <div id="produtos-list">
                    <!-- Lista de produtos será injetada aqui -->
                </div>
            </div>
        </div>
    </section>

    <!-- Modal do Carrinho -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Carrinho de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Primeira parte: Formulario da compra-->
                    <div class="mb-4">
                        <h2 class="panel-title">Dados da Compra</h2>
                        <div class="input-group-container">
                            <label for="cpfCliente" class="header-title">CPF:</label>
                            <input type="text" class="form-control" placeholder="Digite o CPF" id="cpfCliente">
                        </div>
                        <div class="input-group-container">
                            <label for="nomeCliente" class="header-title">Cliente:</label>
                            <input type="text" class="form-control" placeholder="Nome do Cliente" id="nomeCliente" readonly>
                        </div>
                        <div class="input-group-container">
                            <label for="dataAtual" class="header-title">Data:</label>
                            <input type="text" class="form-control" id="dataAtual" readonly>
                        </div>
                        <div class="input-group-container">
                            <label for="formaPagamento" class="header-title">Forma de Pagamento:</label>
                            <select id="formaPagamento" name="formaPagamento" class="form-control" required>
                                <option value="" disabled selected>Selecione a forma de pagamento</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Débito">Débito</option>
                                <option value="Crédito">Crédito</option>
                                <option value="Pix">Pix</option>
                            </select>
                        </div>
                        <div class="input-group-container">
                            <label for="usuarioLogado" class="header-title">Funcionário:</label>
                            <input type="text" class="form-control" id="usuarioLogado" value="<?php echo $_SESSION['user']->nome ?>" readonly>
                        </div>
                    </div>

                    <!-- Segunda Parte: Lista de Produtos Selecionados -->
                    <div class="mb-4">
                        <h2 class="panel-title">Produtos Selecionados</h2>
                        <div id="carrinho-itens">
                            <!-- Itens do carrinho serão inseridos aqui -->
                        </div>
                        <p>Total: R$<span id="total">0,00</span></p>
                    </div>

                    <!-- Terceira Parte: Botões para Finalizar e Limpar -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" id="limpar-carrinho">Limpar Carrinho</button>
                        <button type="button" class="btn btn-primary" id="finalizar-venda">Finalizar Venda</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../src/javascript/venda.js"></script>

    <script>
        document.getElementById("dataAtual").value = new Date().toLocaleDateString("pt-BR");

        document.getElementById("cpfCliente").addEventListener("input", function() {
            const cpf = this.value.replace(/\D/g, '');
            if (cpf.length === 11) {
                fetchCliente(cpf);
            } else {
                document.getElementById("nomeCliente").value = '';
            }
        });

        function fetchCliente(cpf) {
            fetch('../venda/verificar-cliente.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cpf: cpf
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.cadastrado) {
                        document.getElementById("nomeCliente").value = data.nome;
                    } else {
                        alert("Cliente não encontrado. Você precisa cadastrar o cliente.");
                    }
                })
                .catch(error => {
                    console.error("Erro ao verificar CPF:", error);
                });
        }

        // Abrir a Modal do Carrinho
        document.getElementById("cart-icon").addEventListener("click", function() {
            const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            cartModal.show();
        });

        // Função para limpar o carrinho e os inputs
        document.getElementById("limpar-carrinho").addEventListener("click", function() {
            // Limpar os itens do carrinho
            document.getElementById("carrinho-itens").innerHTML = '';
            // Limpar os inputs do formulário
            document.getElementById("cpfCliente").value = '';
            document.getElementById("nomeCliente").value = '';
            document.getElementById("formaPagamento").value = '';
            document.getElementById("total").textContent = '0,00';
        });
    </script>
</body>

</html>