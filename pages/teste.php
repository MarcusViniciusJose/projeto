<?php
include_once '../classe/conexao.php';

// Consulta SQL para obter os dados
$query = "SELECT DATE_FORMAT(v.data_venda, '%M') AS mes, 
                 SUM(i.preco_venda * i.quantidade) AS total_vendas
          FROM vendas v
          JOIN itens i ON v.id = i.venda_id
          GROUP BY DATE_FORMAT(v.data_venda, '%Y-%m')
          ORDER BY total_vendas DESC";

$result = $conexao->query($query);

// Preparar os dados para o gráfico
$chartData = [["Mês", "Vendas"]];
while ($row = $result->fetch_assoc()) {
    $chartData[] = [$row['mes'], (float)$row['total_vendas']];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Gráfico de Vendas por Mês</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });

        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);

            var options = {
                title: 'Desempenho de Vendas por Mês',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <div id="curve_chart" style="width: 1300px; height: 500px"></div>
</body>

</html>