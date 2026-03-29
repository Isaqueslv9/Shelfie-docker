<?php
include 'templates/header.php';
require 'conexao.php';

$mensagem   = '';
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_atual    = $_POST['senha_atual'];
    $nova_senha     = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    $resultado = mongoFind('usuarios', ['_id' => new MongoDB\BSON\ObjectId($id_usuario)]);
    $usuario   = $resultado[0] ?? null;

    if ($usuario && password_verify($senha_atual, $usuario->senha)) {
        if ($nova_senha === $confirma_senha) {
            mongoUpdateOne('usuarios',
                ['_id' => new MongoDB\BSON\ObjectId($id_usuario)],
                ['senha' => password_hash($nova_senha, PASSWORD_DEFAULT)]
            );
            $mensagem = '<div class="mensagem-sucesso">Senha alterada com sucesso!</div>';
        } else {
            $mensagem = '<div class="mensagem-erro">A nova senha e a confirmação não coincidem.</div>';
        }
    } else {
        $mensagem = '<div class="mensagem-erro">Senha atual incorreta.</div>';
    }
}
?>
<div class="container page-container">
    <h1>Meu Perfil</h1>
    <?php echo $mensagem; ?>
    <form class="livro-form" method="POST">
        <h2>Alterar Senha</h2>
        <label for="senha_atual">Senha Atual</label>
        <input type="password" name="senha_atual" required>
        <label for="nova_senha">Nova Senha</label>
        <input type="password" name="nova_senha" required>
        <label for="confirma_senha">Confirmar Nova Senha</label>
        <input type="password" name="confirma_senha" required>
        <button type="submit">Salvar Alterações</button>
    </form>
</div>
<?php include 'templates/footer.php'; ?>
