<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario']) || empty($_GET['id'])) {
    header('Location: login.php');
    exit();
}

$id_livro   = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// Busca o estado atual do favorito
$resultado = mongoFind('livros', [
    '_id'        => new MongoDB\BSON\ObjectId($id_livro),
    'usuario_id' => $id_usuario,
], ['projection' => ['favorito' => 1]]);

if (!empty($resultado)) {
    $favorito_atual = $resultado[0]->favorito ?? false;

    // Inverte o estado
    mongoUpdateOne('livros',
        ['_id' => new MongoDB\BSON\ObjectId($id_livro), 'usuario_id' => $id_usuario],
        ['favorito' => !$favorito_atual]
    );
}

header('Location: meus_livros.php');
exit();
?>
