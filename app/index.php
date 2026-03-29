<?php
include 'templates/header.php';
require 'conexao.php';

$id_usuario   = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];

// Total de livros
$total_livros = mongoCount('livros', ['usuario_id' => $id_usuario]);

// Livros sendo lidos
$lendo_agora = mongoCount('livros', ['usuario_id' => $id_usuario, 'andamento' => 'Lendo']);

// Continue Lendo (limite 3)
$lista_continue_lendo = mongoFind('livros',
    ['usuario_id' => $id_usuario, 'andamento' => 'Lendo'],
    ['limit' => 3, 'projection' => ['_id' => 1, 'titulo' => 1, 'autor' => 1]]
);

// Favoritos (limite 3)
$lista_favoritos = mongoFind('livros',
    ['usuario_id' => $id_usuario, 'favorito' => true],
    ['limit' => 3, 'projection' => ['_id' => 1, 'titulo' => 1, 'autor' => 1]]
);
?>

<div class="container page-container">
    <h1 class="dashboard-greeting">Olá, <?= htmlspecialchars($nome_usuario) ?>!</h1>
    <p class="dashboard-subtitle">Aqui está um resumo da sua estante virtual.</p>

    <div class="dashboard-grid">

        <div class="dashboard-widget">
            <h2 class="widget-title">Resumo</h2>
            <div class="stat-boxes">
                <div class="stat-box">
                    <span class="stat-number"><?= $total_livros ?></span>
                    <span class="stat-label">Livros na Estante</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number"><?= $lendo_agora ?></span>
                    <span class="stat-label">Lendo Atualmente</span>
                </div>
            </div>
        </div>

        <div class="dashboard-widget">
            <h2 class="widget-title">Acesso Rápido</h2>
            <div class="action-boxes">
                <a href="adicionar_livro.php" class="action-box">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Adicionar Novo Livro</span>
                </a>
                <a href="meus_livros.php" class="action-box">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Ver Estante Completa</span>
                </a>
            </div>
        </div>

        <div class="dashboard-widget full-width">
            <h2 class="widget-title">Continue Lendo</h2>
            <div class="book-list-widget">
                <?php if (count($lista_continue_lendo) > 0): ?>
                    <ul>
                        <?php foreach ($lista_continue_lendo as $livro): ?>
                            <li>
                                <span class="book-title"><?= htmlspecialchars($livro->titulo) ?></span>
                                <span class="book-author">por <?= htmlspecialchars($livro->autor) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-list-message">Você não está lendo nenhum livro no momento.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-widget full-width">
            <h2 class="widget-title"><i class="fa-solid fa-star" style="color: var(--cor-favorito);"></i> Seus Favoritos</h2>
            <div class="book-list-widget">
                <?php if (count($lista_favoritos) > 0): ?>
                    <ul>
                        <?php foreach ($lista_favoritos as $livro): ?>
                            <li>
                                <span class="book-title"><?= htmlspecialchars($livro->titulo) ?></span>
                                <span class="book-author">por <?= htmlspecialchars($livro->autor) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-list-message">Você ainda não marcou nenhum livro como favorito.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include 'templates/footer.php'; ?>
