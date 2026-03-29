<?php
include 'templates/header.php';
require 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];

// Paginação
$limit  = 8;
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Filtros
$filter = ['usuario_id' => $id_usuario];

if (!empty($_GET['busca'])) {
    $busca = $_GET['busca'];
    $filter['$or'] = [
        ['titulo' => new MongoDB\BSON\Regex($busca, 'i')],
        ['autor'  => new MongoDB\BSON\Regex($busca, 'i')],
    ];
}
if (!empty($_GET['filtro_status'])) {
    $filter['andamento'] = $_GET['filtro_status'];
}

// Busca livros paginados
$livros = mongoFind('livros', $filter, [
    'sort'  => ['data_adicao' => -1],
    'limit' => $limit,
    'skip'  => $offset,
]);

// Total para paginação
$total_livros = mongoCount('livros', $filter);
$total_pages  = (int)ceil($total_livros / $limit);
?>

<div class="container page-container">
    <h1>Meus Livros</h1>

    <div class="search-filter-box">
        <form action="meus_livros.php" method="GET">
            <input type="text" name="busca" placeholder="Buscar por título ou autor..."
                   value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
            <select name="filtro_status">
                <option value="">Todos</option>
                <option value="Lido"      <?= ($_GET['filtro_status'] ?? '') == 'Lido'      ? 'selected' : '' ?>>Lido</option>
                <option value="Lendo"     <?= ($_GET['filtro_status'] ?? '') == 'Lendo'     ? 'selected' : '' ?>>Lendo</option>
                <option value="Quero Ler" <?= ($_GET['filtro_status'] ?? '') == 'Quero Ler' ? 'selected' : '' ?>>Quero Ler</option>
                <option value="Abandonei" <?= ($_GET['filtro_status'] ?? '') == 'Abandonei' ? 'selected' : '' ?>>Abandonei</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
    </div>

    <div class="livros-grid">
        <?php foreach ($livros as $livro): ?>
            <div class="livro-card">
                <div class="card-favorito">
                    <a href="processa_favorito.php?id=<?= (string)$livro->_id ?>">
                        <i class="<?= $livro->favorito ? 'fa-solid fa-star' : 'fa-regular fa-star' ?>"></i>
                    </a>
                </div>
                <h4><?= htmlspecialchars($livro->titulo) ?></h4>
                <p><strong>Autor:</strong> <?= htmlspecialchars($livro->autor) ?></p>

                <?php if (!empty($livro->editora)): ?>
                    <p><strong>Editora:</strong> <?= htmlspecialchars($livro->editora) ?></p>
                <?php endif; ?>

                <span class="status-tag"><?= htmlspecialchars($livro->andamento) ?></span>
                <div class="card-actions">
                    <a href="editar_livro.php?id=<?= (string)$livro->_id ?>" class="btn-edit">Editar</a>
                    <a href="processa_exclusao.php?id=<?= (string)$livro->_id ?>" class="btn-delete"
                       onclick="return confirm('Tem certeza que deseja excluir este livro?')">Excluir</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&busca=<?= htmlspecialchars($_GET['busca'] ?? '') ?>&filtro_status=<?= htmlspecialchars($_GET['filtro_status'] ?? '') ?>"
               class="<?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
