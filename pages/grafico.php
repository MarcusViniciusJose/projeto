<?php
include_once '../classe/conexao.php';

// Consulta para as últimas 5 vendas realizadas
$sqlVendasRealizadas = "SELECT v.id AS venda_id, v.data_venda, c.nome AS cliente_nome, 
                        SUM(i.preco_venda * i.quantidade) AS total_venda, f.nome AS vendedor
                        FROM vendas v 
                        JOIN clientes c ON v.cliente_id = c.id
                        JOIN itens i ON v.id = i.venda_id
                        JOIN funcionarios f ON v.funcionario_id = f.id
                        GROUP BY v.id, v.data_venda, c.nome, f.nome
                        ORDER BY v.data_venda DESC LIMIT 5;";
$resultVendasRealizadas = mysqli_query($conexao, $sqlVendasRealizadas);

$datas_vendas = [];
$totais_vendas = [];
while ($row = mysqli_fetch_assoc($resultVendasRealizadas)) {
    $datas_vendas[] = $row['data_venda'];
    $totais_vendas[] = $row['total_venda'];
}

// Consulta para os 5 produtos mais vendidos
$sqlProdutosVendidos = "SELECT p.nome AS produto, p.cor, p.tamanho, p.genero,
                        SUM(i.quantidade) AS total_vendido,
                        SUM(i.quantidade * i.preco_venda) AS valor_total
                        FROM itens i
                        JOIN produtos p ON i.produto_id = p.id 
                        GROUP BY p.nome, p.cor, p.tamanho, p.genero
                        ORDER BY total_vendido DESC LIMIT 5;";
$resultProdutosVendidos = mysqli_query($conexao, $sqlProdutosVendidos);

$nomes_produtos = [];
$totais_vendidos = [];
while ($row = mysqli_fetch_assoc($resultProdutosVendidos)) {
    $nomes_produtos[] = $row['produto'];
    $totais_vendidos[] = $row['total_vendido'];
}

// Consulta para status de estoque (masculino vs feminino)
$sqlStatusEstoque = "SELECT p.nome AS produto, p.genero, p.tamanho, 
                     SUM(p.quantidade) AS quantidade_estoque
                     FROM produtos p
                     GROUP BY p.nome, p.genero, p.tamanho
                     ORDER BY quantidade_estoque ASC;";
$resultStatusEstoque = mysqli_query($conexao, $sqlStatusEstoque);

$estoque_genero = [0, 0]; // [Masculino, Feminino]
while ($row = mysqli_fetch_assoc($resultStatusEstoque)) {
    if ($row['genero'] == 'Masculino') {
        $estoque_genero[0] += $row['quantidade_estoque'];
    } else if ($row['genero'] == 'Feminino') {
        $estoque_genero[1] += $row['quantidade_estoque'];
    }
}

// Consulta para os funcionários com mais vendas
$sqlVendasFuncionarios = "SELECT f.nome AS funcionario, COUNT(v.id) AS quantidade_vendas,
                          SUM(i.preco_venda * i.quantidade) AS total_vendas
                          FROM vendas v
                          JOIN funcionarios f ON v.funcionario_id = f.id
                          JOIN itens i ON v.id = i.venda_id
                          GROUP BY f.nome
                          ORDER BY total_vendas DESC;";
$resultVendasFuncionarios = mysqli_query($conexao, $sqlVendasFuncionarios);

$nomes_vendedores = [];
$totais_vendas_vendedores = [];
while ($row = mysqli_fetch_assoc($resultVendasFuncionarios)) {
    $nomes_vendedores[] = $row['funcionario'];
    $totais_vendas_vendedores[] = $row['total_vendas'];
}

// Consulta para o valor das vendas mensais
$sqlVendasMensais = "SELECT YEAR(v.data_venda) AS ano, MONTH(v.data_venda) AS mes, 
                     SUM(i.preco_venda * i.quantidade) AS total_vendas
                     FROM vendas v 
                     JOIN itens i ON v.id = i.venda_id
                     GROUP BY YEAR(v.data_venda), MONTH(v.data_venda)
                     ORDER BY ano, mes;";
$resultVendasMensais = mysqli_query($conexao, $sqlVendasMensais);

$meses_ano = [];
$total_vendas_mensais = [];
while ($row = mysqli_fetch_assoc($resultVendasMensais)) {
    $meses_ano[] = $row['mes'] . '/' . $row['ano']; // Formato MM/AAAA
    $total_vendas_mensais[] = $row['total_vendas'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos de Vendas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .grafico-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .grafico-container canvas {
            margin: 10px;
            max-width: 45%;
            height: auto;
        }

        #vendasMensaisGrafico {
            width: 100% !important;
            height: 400px !important;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="my-4">Relatório de Vendas</h1>

        <!-- Gráficos lado a lado -->
        <div class="grafico-container">
            <!-- Gráfico 1: Últimas 5 vendas realizadas -->
            <div class="grafico-item">
                <h2>Últimas 5 Vendas Realizadas</h2>
                <canvas id="vendasGrafico" width="400" height="200"></canvas>
            </div>

            <!-- Gráfico 2: Top 5 Produtos Mais Vendidos -->
            <div class="grafico-item">
                <h2>Top 5 Produtos Mais Vendidos</h2>
                <canvas id="produtosGrafico" width="400" height="200"></canvas>
            </div>

            <!-- Gráfico 3: Estoque Masculino x Feminino -->
            <div class="grafico-item">
                <h2>Estoque Masculino vs Feminino</h2>
                <canvas id="estoqueGeneroGrafico" width="400" height="200"></canvas>
            </div>

            <!-- Gráfico 4: Funcionários com Mais Vendas -->
            <div class="grafico-item">
                <h2>Funcionários com Mais Vendas</h2>
                <canvas id="vendedoresGrafico" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Gráfico 5: Valor de Vendas Mensais (gráfico de linhas) -->
        <div class="my-4">
            <h2>Valor de Vendas por Mês</h2>
            <canvas id="vendasMensaisGrafico" width="400" height="200"></canvas>
        </div>

    </div>

    <script>
        // Gráfico 1: Últimas 5 Vendas Realizadas
        var ctx = document.getElementById('vendasGrafico').getContext('2d');
        var vendasGrafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($datas_vendas); ?>,
                datasets: [{
                    label: 'Total de Vendas',
                    data: <?php echo json_encode($totais_vendas); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: 'black',
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                legend: {
                    position: 'right'
                }
            }
        });

        // Gráfico 2: Top 5 Produtos Mais Vendidos
        var ctx = document.getElementById('produtosGrafico').getContext('2d');
        var produtosGrafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($nomes_produtos); ?>,
                datasets: [{
                    label: 'Total Vendido',
                    data: <?php echo json_encode($totais_vendidos); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: 'black',
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                legend: {
                    position: 'right'
                }
            }
        });

        // Gráfico 3: Estoque Masculino vs Feminino
        var ctx = document.getElementById('estoqueGeneroGrafico').getContext('2d');
        var estoqueGeneroGrafico = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Masculino', 'Feminino'],
                datasets: [{
                    label: 'Estoque por Gênero',
                    data: <?php echo json_encode($estoque_genero); ?>,
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    borderColor: ['#FFFFFF', '#FFFFFF'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        formatter: function(value) {
                            return value + '%';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 16
                        }
                    }
                },
                legend: {
                    position: 'right'
                }
            }
        });

        // Gráfico 4: Funcionários com Mais Vendas
        var ctx = document.getElementById('vendedoresGrafico').getContext('2d');
        var vendedoresGrafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($nomes_vendedores); ?>,
                datasets: [{
                    label: 'Total de Vendas',
                    data: <?php echo json_encode($totais_vendas_vendedores); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: 'black',
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                legend: {
                    position: 'right'
                }
            }
        });

        // Gráfico 5: Valor de Vendas por Mês
        var ctx = document.getElementById('vendasMensaisGrafico').getContext('2d');
        var vendasMensaisGrafico = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($meses_ano); ?>,
                datasets: [{
                    label: 'Valor das Vendas',
                    data: <?php echo json_encode($total_vendas_mensais); ?>,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        color: 'black',
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    }
                },
                legend: {
                    position: 'right'
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>