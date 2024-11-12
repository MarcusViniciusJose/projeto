<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPM Wear | Vendas</title>
    <!--  BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--  ICONES BOOTSTRAP -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <!--  CSS -->
    <link rel="stylesheet" href="../src/css/venda.css">
</head>

<body>
    <!--  Importação da Sidebar -->
    <?php include('../src/template/sidebar.php'); ?>

    <!-- Header com logo e título -->
    <header class="p-3 text-white text-center">
        <img src="../src/imagens/logo2.png" alt="Logo RPM Wear" class="header-logo">
        <h1>Gerenciamento de Vendas</h1>
    </header>

    <section class="home-section">

        <!-- Containers principais -->
        <div id="main-container">
            <!-- Container de carrinho -->
            <div id="carrinho-container">
                <h2>Carrinho de Compras</h2>
                <div id="carrinho-itens"></div>
                <p>Total: R$<span id="total">0,00</span></p>
                <button id="finalizar-venda" type="button">Finalizar Venda</button>
            </div>

            <!-- Barra de pesquisa -->
            <div id="search-container">
                <input type="text" class="form-control search-bar" name="search" id="searchInput" placeholder="Pesquisar produtos..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
            </div>

            <!-- Container de produtos -->
            <div id="produtos-container">
                <h2>Produtos</h2>
                <!-- Barra de pesquisa dentro do container de produtos -->
                <div id="search-container">
                    <input type="text" class="form-control search-bar" name="search" id="searchInput" placeholder="Pesquisar produtos..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
                </div>
                <div id="produtos-list">
                    <!-- Lista de produtos será injetada aqui -->
                </div>
            </div>
        </div>
    </section>

    <!-- Painel lateral de inputs -->
    <div id="inputs-panel">
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
                <option value="" disabled selected>Forma de pagamento</option>
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

        <!-- Botão de visualizar vendas -->
        <a href="#"><button id="panel-button">Visualizar Vendas</button></a>

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
    </script>
</body>

</html>