<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPM WEAR | Vendas</title>
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
    <section class="home-section">
        <!-- Header de Gerenciamento de Vendas -->
        <header class="p-3 text-white text-center">
            <img src="../src/imagens/logo2.png" alt="Logo RPM Wear" class="header-logo">
            <h1>RPM Wear | Gerenciamento de Vendas</h1>
            <!-- Linha com os campos -->
            <div class="d-flex justify-content-center mt-3 align-items-center gap-3">
                <div class="input-group-container">
                    <div class="input-row">
                        <label for="cpfCliente" class="header-title">CPF:</label>
                        <input type="text" class="form-control" placeholder="Digite o CPF" id="cpfCliente">
                    </div>
                </div>
                <div class="input-group-container">
                    <div class="input-row">
                        <label for="nomeCliente" class="header-title">Cliente:</label>
                        <input type="text" class="form-control" placeholder="Nome do Cliente" id="nomeCliente" readonly>
                    </div>
                </div>
                <div class="input-group-container">
                    <div class="input-row">
                        <label for="dataAtual" class="header-title">Data:</label>
                        <input type="text" class="form-control" id="dataAtual" readonly>
                    </div>
                </div>
            </div>
            <!-- Linha com os campos -->
            <div class="d-flex justify-content-center mt-3 align-items-center gap-3">
                <div class="input-group-container">
                    <div class="input-row">
                        <label for="formaPagamento" class="header-title">Forma Pagamento:</label>
                        <select id="formaPagamento" name="formaPagamento" class="form-control" required>
                            <option value="" disabled selected>Escolha uma forma de pagamento</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Débito">Débito</option>
                            <option value="Crédito">Crédito</option>
                            <option value="Pix">Pix</option>
                        </select>
                    </div>
                </div>
                <div class="input-group-container">
                    <div class="input-row">
                        <label for="usuarioLogado" class="header-title">Funcionário:</label>
                        <input type="text" class="form-control" id="usuarioLogado" value="<?php echo $_SESSION['user']->nome ?>" readonly>
                    </div>
                </div>
        </header>
        <!-- Barra de pesquisa -->
        <input type="text" class="form-control" name="search" id="searchInput" placeholder="Pesquisar produtos..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">

        <!-- Containers de produtos e carrinho -->
        <div id="main-container">
            <!-- Container de produtos -->
            <div id="produtos-container">
                <form method="GET" class="mt-3" id="searchForm">
                    <div class="input-group">
                        <button class="search-button" type="submit"><i class='bx bx-search-alt-2'></i></button>
                        <button type="button" class="clean-button" onclick="clearSearch()"><i class='bx bx-x-circle'></i></button>
                        <input type="hidden" name="page" value="<?= htmlspecialchars($page) ?>">
                    </div>
                </form>
            </div>

            <!-- Container de carrinho -->
            <div id="carrinho-container">
                <h2>Carrinho de Compras</h2>
                <div id="carrinho-itens"></div>
                <p>Total: R$<span id="total">0,00</span></p>
                <button id="finalizar-venda" type="button">Finalizar Venda</button>
            </div>
        </div>
    </section>

    <script src="../src/javascript/venda.js"></script>

    <script>
        // Coloca a data atual
        document.getElementById("dataAtual").value = new Date().toLocaleDateString("pt-BR");

        // Função para tratar o input do CPF
        document.getElementById("cpfCliente").addEventListener("input", function() {
            const cpf = this.value.replace(/\D/g, ''); // Remove qualquer caractere não numérico
            if (cpf.length === 11) {
                fetchCliente(cpf);
            } else {
                document.getElementById("nomeCliente").value = ''; // Limpa o nome do cliente
            }
        });

        // Função para fazer a requisição ao back-end
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
                        // Se o cliente estiver cadastrado, preenche o nome
                        document.getElementById("nomeCliente").value = data.nome;
                    } else {
                        // Se não estiver cadastrado, mostra o aviso e a opção de cadastrar
                        alert("Cliente não encontrado. Você precisa cadastrar o cliente.");
                        //window.location.href = "forms/cliente-create.php"; // Redireciona para a página de cadastro
                    }
                })
                .catch(error => {
                    console.error("Erro ao verificar CPF:", error);
                });
        }
    </script>
</body>

</html>