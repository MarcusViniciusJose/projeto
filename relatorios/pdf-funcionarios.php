$sqlRankingFuncionarios = "SELECT usuarios.nome AS funcionario, usuarios.foto AS foto, SUM(itens.preco_venda * itens.quantidade) AS total_vendas
FROM vendas
INNER JOIN usuarios ON vendas.funcionario_id = usuarios.id
INNER JOIN itens ON vendas.id = itens.venda_id
GROUP BY usuarios.id ORDER BY total_vendas DESC LIMIT 3";
$resultRankingFuncionarios = mysqli_query($conexao, $sqlRankingFuncionarios);


<!-- Ranking Funcionarios -->
<div class="head">
    <h3>Ranking funcionários</h3>
</div>
<ul class="todo-list">
    <?php
    $posicao = 1;
    while ($row = mysqli_fetch_assoc($resultRankingFuncionarios)) { ?>
        <li>
            <img src="../src/uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['funcionario']; ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px; border-radius: 50%;">
            <p><?php echo $posicao . "º " . $row['funcionario']; ?> - R$ <?php echo number_format($row['total_vendas'], 2, ',', '.'); ?></p>
        </li>
    <?php
        $posicao++;
    } ?>
</ul>