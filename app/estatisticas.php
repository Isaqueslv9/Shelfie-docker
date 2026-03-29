<?php
include 'templates/header.php';
require 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];

// Livros lidos por ano — aggregation pipeline
$lidos_por_ano = mongoAggregate('livros', [
    ['$match'  => ['usuario_id' => $id_usuario, 'andamento' => 'Lido']],
    ['$group'  => ['_id' => ['$year' => '$data_adicao'], 'total' => ['$sum' => 1]]],
    ['$sort'   => ['_id' => 1]],
    ['$project'=> ['_id' => 0, 'ano' => '$_id', 'total' => 1]],
]);

// Top 3 autores
$top_autores = mongoAggregate('livros', [
    ['$match'  => ['usuario_id' => $id_usuario]],
    ['$group'  => ['_id' => '$autor', 'total' => ['$sum' => 1]]],
    ['$sort'   => ['total' => -1]],
    ['$limit'  => 3],
    ['$project'=> ['_id' => 0, 'autor' => '$_id', 'total' => 1]],
]);

// Média de notas
$media_resultado = mongoAggregate('livros', [
    ['$match'  => ['usuario_id' => $id_usuario, 'nota' => ['$gt' => 0]]],
    ['$group'  => ['_id' => null, 'media' => ['$avg' => '$nota']]],
]);
$media_notas = $media_resultado[0]->media ?? 0;

// Prepara dados para o Chart.js
$chart_lidos_labels = json_encode(array_map(fn($r) => $r->ano, $lidos_por_ano));
$chart_lidos_data   = json_encode(array_map(fn($r) => $r->total, $lidos_por_ano));

$chart_autores_labels = json_encode(array_map(fn($r) => $r->autor, $top_autores));
$chart_autores_data   = json_encode(array_map(fn($r) => $r->total, $top_autores));
?>

<div class="container page-container">
    <h1>Estatísticas de Leitura</h1>
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Livros Lidos por Ano</h3>
            <canvas id="lidosPorAnoChart"></canvas>
        </div>
        <div class="stat-card">
            <h3>Top 3 Autores</h3>
            <canvas id="topAutoresChart"></canvas>
        </div>
        <div class="stat-card">
            <h3>Média das Suas Notas</h3>
            <div class="media-nota">
                <?= number_format($media_notas, 1, ',') ?> <i class="fa-solid fa-star"></i>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctxLidos = document.getElementById('lidosPorAnoChart');
    new Chart(ctxLidos, {
        type: 'bar',
        data: {
            labels: <?= $chart_lidos_labels ?>,
            datasets: [{
                label: 'Livros Lidos',
                data: <?= $chart_lidos_data ?>,
                backgroundColor: '#004d4d'
            }]
        }
    });

    const ctxAutores = document.getElementById('topAutoresChart');
    new Chart(ctxAutores, {
        type: 'pie',
        data: {
            labels: <?= $chart_autores_labels ?>,
            datasets: [{
                label: 'Livros',
                data: <?= $chart_autores_data ?>,
                backgroundColor: ['#004d4d', '#E07A5F', '#3D405B']
            }]
        }
    });
});
</script>

<?php include 'templates/footer.php'; ?>
