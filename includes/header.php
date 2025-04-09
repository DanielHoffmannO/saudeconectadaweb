<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saúde Conectada</title>
    <link rel="stylesheet" href="assets/css/styles.css"> 
    <link rel="stylesheet" href="/assets/css/styles.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <h1 class="header__title">Saúde Conectada</h1>
        <nav class="nav">
            <ul class="nav__list">
                <li class="nav__item"><a href="./index.php" class="nav__link">Início</a></li>
                <li class="nav__item"><a href="./consultas.php" class="nav__link">Consultas</a></li>
                <li class="nav__item"><a href="./chat.php" class="nav__link">Chat</a></li>
                <li class="nav__item"><a href="./notificacoes.php" class="nav__link">Notificações</a></li>
                <li class="nav__item"><a href="./exames.php" class="nav__link">Exames</a></li>
                <li class="nav__item"><a href="./prontuario.php" class="nav__link">Prontuário</a></li>

                <?php if (isset($_SESSION['usuario'])): ?>
                    <li class="nav__item">
                        <a href="./cadastro.php" class="nav__link">
                            <?= htmlspecialchars($_SESSION['usuario']) ?>
                        </a> |
                        <a href="./logout.php" class="nav__link">Sair</a>
                    </li>
                <?php else: ?>
                    <li class="nav__item"><a href="./login.php" class="nav__link">Login</a></li>
                    <li class="nav__item"><a href="./cadastro.php" class="nav__link">Cadastro</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
