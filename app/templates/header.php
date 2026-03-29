<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}
$nome_usuario = $_SESSION['nome_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelfie - Sua Estante Virtual</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">
                <img src="img/logo.png" alt="Shelfie Logo">
            </a>
            <nav>
                <a href="meus_livros.php">Meus Livros</a>
                <a href="adicionar_livro.php">Adicionar Livro</a>
                <a href="estatisticas.php">Estatísticas</a>
            </nav>
            <div class="user-profile">
                <a href="perfil.php" class="profile-link">
                    <i class="fa-solid fa-user"></i>
                    <span><?php echo htmlspecialchars($nome_usuario); ?></span>
                </a>
                <a href="logout.php" class="logout-btn">Sair</a>
            </div>
        </div>
    </header>
    <main>