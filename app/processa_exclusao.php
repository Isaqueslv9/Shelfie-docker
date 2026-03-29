<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario']) || empty($_GET['id'])) {
    header('Location: login.php');
    exit();
}

$id_livro   = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

mongoDeleteOne('livros', [
    '_id'        => new MongoDB\BSON\ObjectId($id_livro),
    'usuario_id' => $id_usuario,
]);

header('Location: meus_livros.php');
exit();
?>
