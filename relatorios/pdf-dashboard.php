<?php
// Importando arquivo de conexão
include_once '../classe/conexao.php';

// Total de clientes registrados
$sqlClientes = "SELECT COUNT(*) AS total_clientes FROM clientes";
$resultClientes = mysqli_query($conexao, $sqlClientes);
$total_clientes = $resultClientes ? mysqli_fetch_assoc($resultClientes)['total_clientes'] : 0;

// Total de produtos vendidos
$sqlProdutosVendidos = "SELECT SUM(quantidade) AS total_vendidos FROM itens";
$resultProdutosVendidos = mysqli_query($conexao, $sqlProdutosVendidos);
$total_produtos_vendidos = $resultProdutosVendidos ? mysqli_fetch_assoc($resultProdutosVendidos)['total_vendidos'] : 0;

// Total vendido em R$
$sqlTotalVendido = "SELECT SUM(preco_venda * quantidade) AS total_vendido FROM itens INNER JOIN vendas ON itens.venda_id = vendas.id";
$resultTotalVendido = mysqli_query($conexao, $sqlTotalVendido);
$total_vendido = $resultTotalVendido ? mysqli_fetch_assoc($resultTotalVendido)['total_vendido'] : 0;

// Últimas 15 compras
$sqlUltimasCompras = "SELECT clientes.nome AS cliente, vendas.data_venda, SUM(itens.preco_venda * itens.quantidade) AS total_compra 
                      FROM vendas
                      INNER JOIN clientes ON vendas.cliente_id = clientes.id
                      INNER JOIN itens ON vendas.id = itens.venda_id
                      GROUP BY vendas.id ORDER BY vendas.data_venda DESC LIMIT 15";
$resultUltimasCompras = mysqli_query($conexao, $sqlUltimasCompras);

// Ranking de funcionários
$sqlRankingFuncionarios = "SELECT usuarios.nome AS funcionario, usuarios.foto AS foto, SUM(itens.preco_venda * itens.quantidade) AS total_vendas
						   FROM vendas
						   INNER JOIN usuarios ON vendas.funcionario_id = usuarios.id
						   INNER JOIN itens ON vendas.id = itens.venda_id
						   GROUP BY usuarios.id ORDER BY total_vendas DESC LIMIT 3";
$resultRankingFuncionarios = mysqli_query($conexao, $sqlRankingFuncionarios);

// Ranking de produtos
$sqlRankingProdutos = "SELECT produtos.nome AS produto, produtos.imagem AS imagem, SUM(itens.preco_venda * itens.quantidade) AS total_vendas
					   FROM vendas
					   INNER JOIN itens ON vendas.id = itens.venda_id
					   INNER JOIN produtos ON itens.produto_id = produtos.id
					   GROUP BY produtos.id ORDER BY total_vendas DESC LIMIT 3";
$resultRankingProdutos = mysqli_query($conexao, $sqlRankingProdutos);

// Carregar o autoload do DOMPDF
require_once '../src/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;

// Instanciar o DOMPDF
$dompdf = new Dompdf();

// Montar o HTML do conteúdo principal
$html = '
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Relatório de Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section id="content">
        <main>
            <div class="head-title">
                <h1>Dashboard</h1>
            </div>
            <ul class="box-info">
                <li>
                    <i class="bx bx-store-alt"></i>
                    <span class="text">
                        <h3>' . $total_produtos_vendidos . '</h3>
                        <p>Produtos Vendidos</p>
                    </span>
                </li>
                <li>
                    <i class="bx bxs-group"></i>
                    <span class="text">
                        <h3>' . $total_clientes . '</h3>
                        <p>Clientes</p>
                    </span>
                </li>
                <li>
                    <i class="bx bxs-dollar-circle"></i>
                    <span class="text">
                        <h3>R$ ' . number_format($total_vendido, 2, ',', '.') . '</h3>
                        <p>Total Vendido</p>
                    </span>
                </li>
            </ul>
            <div class="table-data">
                <div class="order">
                    <h3>Vendas Recentes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Data da Compra</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>';

// Inserir as últimas compras no HTML
while ($row = mysqli_fetch_assoc($resultUltimasCompras)) {
    $html .= '<tr>
                                        <td>' . $row['cliente'] . '</td>
                                        <td>' . date('d/m/Y', strtotime($row['data_venda'])) . '</td>
                                        <td>R$ ' . number_format($row['total_compra'], 2, ',', '.') . '</td>
                                      </tr>';
}

$html .= '        </tbody>
                    </table>
                </div>
            </div>
            <div class="todo">
                <div class="head">
                    <h3>Ranking Funcionários</h3>
                </div>
                <ul class="todo-list">';

// Inserir o ranking de funcionários
$posicao = 1;
while ($row = mysqli_fetch_assoc($resultRankingFuncionarios)) {
    $html .= '<li>
                                <img src="../src/uploads/' . $row['foto'] . '" alt="' . $row['funcionario'] . '" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                <p>' . $posicao . 'º ' . $row['funcionario'] . ' - R$ ' . number_format($row['total_vendas'], 2, ',', '.') . '</p>
                              </li>';
    $posicao++;
}

$html .= '    </ul>
            </div>
            <div class="todo">
                <div class="head">
                    <h3>Ranking Produtos</h3>
                </div>
                <ul class="todo-list">';

// Inserir o ranking de produtos
$posicao = 1;
while ($row = mysqli_fetch_assoc($resultRankingProdutos)) {
    $html .= '<li>
                                <img src="../src/' . $row['imagem'] . '" alt="' . $row['produto'] . '" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                <p>' . $posicao . 'º ' . $row['produto'] . ' - R$ ' . number_format($row['total_vendas'], 2, ',', '.') . '</p>
                              </li>';
    $posicao++;
}

$html .= '    </ul>
            </div>
        </main>
    </section>
</body>
</html>
';

// Carregar o conteúdo HTML
$dompdf->loadHtml($html);

// (Opcional) Configurar o tamanho e a orientação do papel
$dompdf->setPaper('A4', 'portrait');

// Renderizar o HTML como PDF
$dompdf->render();

// Enviar o PDF para o navegador
$dompdf->stream("pdf-dashboard.pdf", array("Attachment" => false));
