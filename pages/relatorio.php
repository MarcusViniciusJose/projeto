<?php
include_once '../classe/conexao.php';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Relatórios</title>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ICONES BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include('../src/template/sidebar.php'); ?>
    <section class="home-section">
        <section class="main-container">
            <div>
                <label>10 Produtos mais vendidos</label>
                <a href="#" class="btn-download">
                    <span class="text">Download PDF</span>
                </a>
            </div>
            <div>
                <label>Vendas realizadas</label>
                <a href="#" class="btn-download">
                    <span class="text">Download PDF</span>
                </a>
            </div>
            <div>
                <label>Estoque</label>
                <a href="#" class="btn-download">
                    <span class="text">Download PDF</span>
                </a>
            </div>
            <div>
                <label>Funcionários que mais venderam</label>
                <a href="#" class="btn-download">
                    <span class="text">Download PDF</span>
                </a>
            </div>
            <div>
                <label>Clientes cadastrados</label>
                <a href="#" class="btn-download">
                    <span class="text">Download PDF</span>
                </a>
            </div>
        </section>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>