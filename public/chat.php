<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

define('IA_ID', 4);

$medicos = json_decode(file_get_contents(__DIR__ . '/../data/medicos.json'), true);

$chatAtivo = isset($_GET['medico']) && is_numeric($_GET['medico']) ? (int)$_GET['medico'] : IA_ID;

$medicoAtivo = array_filter($medicos, fn($m) => $m['id'] === $chatAtivo);
$medicoAtivo = reset($medicoAtivo) ?: $medicos[array_key_last($medicos)];

$pageTitle = "Chat - Saúde Conectada";
$additionalCSS = [
    '/assets/css/chat.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

require_once __DIR__ . '/../includes/header.php';
?>

<main class="chat-container">
    <section class="chat-sidebar">
        <div class="chat-search">
            <input type="text" placeholder="Buscar conversas...">
            <i class="fas fa-search"></i>
        </div>
        
        <div class="chat-contatos">
            <?php foreach ($medicos as $medico): ?>
                <a href="chat.php?medico=<?= $medico['id'] ?>" 
                   class="chat-contato <?= $medico['id'] === $chatAtivo ? 'active' : '' ?>"
                   data-medico-id="<?= $medico['id'] ?>">
                    <img src="<?= $medico['avatar'] ?>" alt="<?= htmlspecialchars($medico['nome']) ?>" class="chat-avatar">
                    <div class="chat-info">
                        <h3><?= htmlspecialchars($medico['nome']) ?></h3>
                        <p><?= htmlspecialchars($medico['especialidade']) ?></p>
                        <span class="chat-preview">
                            <?= htmlspecialchars(end($medico['mensagens'])['texto']) ?>
                        </span>
                    </div>
                    <div class="chat-status">
                        <span class="chat-hora"><?= end($medico['mensagens'])['hora'] ?></span>
                        <?php if ($medico['online']): ?>
                            <span class="online"><i class="fas fa-circle"></i></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    
    <section class="chat-main">
        <div class="chat-header">
            <img src="<?= $medicoAtivo['avatar'] ?>" alt="<?= htmlspecialchars($medicoAtivo['nome']) ?>" class="chat-avatar">
            <div class="chat-header-info">
                <h2><?= htmlspecialchars($medicoAtivo['nome']) ?></h2>
                <p><?= htmlspecialchars($medicoAtivo['especialidade']) ?></p>
                <span class="<?= $medicoAtivo['online'] ? 'online' : 'offline' ?>">
                    <?= $medicoAtivo['online'] ? 'Online' : 'Offline' ?>
                </span>
            </div>
            <div class="chat-actions">
            <li class="nav__item"><a href="/videochat.php" class="nav__link">Ligar</a></li>
</div>

        </div>
        
        <div class="chat-messages">
            <?php foreach ($medicoAtivo['mensagens'] as $mensagem): ?>
                <div class="message <?= $mensagem['remetente'] ?>">
                    <div class="message-content">
                        <p><?= htmlspecialchars($mensagem['texto']) ?></p>
                        <span class="message-time"><?= $mensagem['hora'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="chat-input">
            <button class="emoji-btn" aria-label="Emojis"><i class="far fa-smile"></i></button>
            <input type="text" placeholder="Digite uma mensagem..." id="mensagemInput">
            <button class="send-btn" aria-label="Enviar"><i class="fas fa-paper-plane"></i></button>
        </div>
    </section>
</main>

<?php 
require_once __DIR__ . '/../includes/footer.php';
?>

<script src="/assets/js/chat.js"></script>
