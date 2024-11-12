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
    <link rel="stylesheet" href="../src/css/relatorio.css">
</head>

<body>
    <?php include('../src/template/sidebar.php'); ?>
    <section class="home-section">
        <section class="main-container container">
            <h2 class="text-center mt-4 mb-5">Painel de Relatórios e Desempenho</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Produtos Mais Vendidos</label>
                        <a href="#" class="btn-download">
                            <span class="text">Download PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Vendas Realizadas</label>
                        <a href="#" class="btn-download">
                            <span class="text">Download PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Status do Estoque</label>
                        <a href="#" class="btn-download">
                            <span class="text">Download PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Vendas por Funcionário</label>
                        <a href="#" class="btn-download">
                            <span class="text">Download PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Clientes Cadastrados</label>
                        <a href="#" class="btn-download">
                            <span class="text">Download PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>