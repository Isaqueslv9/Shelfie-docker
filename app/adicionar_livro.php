<?php
include 'templates/header.php';
require 'conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo     = trim($_POST['titulo']);
    $autor      = trim($_POST['autor']);
    $editora    = trim($_POST['editora']);
    $categoria  = trim($_POST['categoria']);
    $andamento  = $_POST['andamento'];
    $resenha    = trim($_POST['resenha']);
    $nota       = !empty($_POST['nota']) ? (int)$_POST['nota'] : null;
    $id_usuario = $_SESSION['id_usuario'];

    if (empty($titulo) || empty($autor)) {
        $mensagem = '<div class="mensagem-erro">Título e Autor são obrigatórios.</div>';
    } else {
        try {
            mongoInsertOne('livros', [
                'usuario_id'  => $id_usuario,
                'titulo'      => $titulo,
                'autor'       => $autor,
                'editora'     => $editora,
                'categoria'   => $categoria,
                'andamento'   => $andamento,
                'nota'        => $nota,
                'resenha'     => $resenha,
                'favorito'    => false,
                'data_adicao' => new MongoDB\BSON\UTCDateTime(),
            ]);
            header('Location: meus_livros.php?status=add_success');
            exit();
        } catch (Exception $e) {
            $mensagem = '<div class="mensagem-erro">Ocorreu um erro ao salvar o livro.</div>';
        }
    }
}
?>

<div class="container page-container">
    <h1>Adicionar Novo Livro</h1>
    <?php echo $mensagem; ?>

    <form class="livro-form" action="adicionar_livro.php" method="POST">
        <label for="titulo">Título do Livro:</label>
        <input type="text" name="titulo" id="titulo" required>

        <label for="autor">Autor:</label>
        <input type="text" name="autor" id="autor" required>

        <label for="editora">Editora:</label>
        <input type="text" name="editora" id="editora" placeholder="Ex: Companhia das Letras">

        <label for="categoria">Categoria:</label>
        <input type="text" name="categoria" id="categoria" placeholder="Ex: Ficção Científica">

        <label for="andamento">Andamento da Leitura:</label>
        <select name="andamento" id="andamento" required>
            <option value="Quero Ler">Quero Ler</option>
            <option value="Lendo">Lendo</option>
            <option value="Lido">Lido</option>
            <option value="Abandonei">Abandonei</option>
        </select>

        <label for="nota">Nota (de 1 a 5):</label>
        <input type="number" name="nota" id="nota" min="1" max="5">

        <label for="resenha">Resenha:</label>
        <textarea name="resenha" id="resenha" rows="5"></textarea>

        <button type="submit">Adicionar à Estante</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
