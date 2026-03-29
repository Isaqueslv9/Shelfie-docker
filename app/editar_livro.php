<?php
include 'templates/header.php';
require 'conexao.php';

$mensagem   = '';
$livro      = null;
$id_livro   = $_GET['id'] ?? null;
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_livro_post = $_POST['id_livro'] ?? null;
    $titulo        = trim($_POST['titulo']);
    $autor         = trim($_POST['autor']);
    $editora       = trim($_POST['editora']);
    $categoria     = trim($_POST['categoria']);
    $andamento     = $_POST['andamento'];
    $resenha       = trim($_POST['resenha']);
    $nota          = !empty($_POST['nota']) ? (int)$_POST['nota'] : null;

    if (!empty($titulo) && !empty($autor) && $id_livro_post) {
        try {
            mongoUpdateOne('livros',
                ['_id' => new MongoDB\BSON\ObjectId($id_livro_post), 'usuario_id' => $id_usuario],
                [
                    'titulo'    => $titulo,
                    'autor'     => $autor,
                    'editora'   => $editora,
                    'categoria' => $categoria,
                    'andamento' => $andamento,
                    'nota'      => $nota,
                    'resenha'   => $resenha,
                ]
            );
            header('Location: meus_livros.php?status=edit_success');
            exit();
        } catch (Exception $e) {
            $mensagem = '<div class="mensagem-erro">Ocorreu um erro ao atualizar o livro.</div>';
        }
    }
}

if ($id_livro) {
    $resultado = mongoFind('livros', [
        '_id'        => new MongoDB\BSON\ObjectId($id_livro),
        'usuario_id' => $id_usuario,
    ]);
    $livro = $resultado[0] ?? null;
}
?>

<div class="container page-container">
    <?php if ($livro): ?>
        <h1>Editar Livro: "<?= htmlspecialchars($livro->titulo) ?>"</h1>
        <?php echo $mensagem; ?>

        <form class="livro-form" action="editar_livro.php?id=<?= (string)$livro->_id ?>" method="POST">
            <input type="hidden" name="id_livro" value="<?= (string)$livro->_id ?>">

            <label for="titulo">Título do Livro:</label>
            <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($livro->titulo) ?>" required>

            <label for="autor">Autor:</label>
            <input type="text" name="autor" id="autor" value="<?= htmlspecialchars($livro->autor) ?>" required>

            <label for="editora">Editora:</label>
            <input type="text" name="editora" id="editora" value="<?= htmlspecialchars($livro->editora ?? '') ?>" placeholder="Ex: Companhia das Letras">

            <label for="categoria">Categoria:</label>
            <input type="text" name="categoria" id="categoria" value="<?= htmlspecialchars($livro->categoria ?? '') ?>" placeholder="Ex: Ficção Científica">

            <label for="andamento">Andamento da Leitura:</label>
            <select name="andamento" id="andamento" required>
                <option value="Quero Ler" <?= ($livro->andamento == 'Quero Ler') ? 'selected' : '' ?>>Quero Ler</option>
                <option value="Lendo"     <?= ($livro->andamento == 'Lendo')     ? 'selected' : '' ?>>Lendo</option>
                <option value="Lido"      <?= ($livro->andamento == 'Lido')      ? 'selected' : '' ?>>Lido</option>
                <option value="Abandonei" <?= ($livro->andamento == 'Abandonei') ? 'selected' : '' ?>>Abandonei</option>
            </select>

            <label for="nota">Nota (de 1 a 5):</label>
            <input type="number" name="nota" id="nota" min="1" max="5" value="<?= htmlspecialchars($livro->nota ?? '') ?>">

            <label for="resenha">Resenha:</label>
            <textarea name="resenha" id="resenha" rows="5"><?= htmlspecialchars($livro->resenha ?? '') ?></textarea>

            <button type="submit">Salvar Alterações</button>
        </form>
    <?php else: ?>
        <h1>Erro</h1>
        <p class="mensagem-erro">Livro não encontrado ou você não tem permissão para editá-lo.</p>
        <a href="meus_livros.php">Voltar para a lista de livros</a>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
