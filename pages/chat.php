<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /saudeconectada/pages/login.php");
    exit();
}

$medicos = [
    [
        'id' => 1,
        'nome' => 'Dra. Ana',
        'especialidade' => 'Cardiologia',
        'avatar' => '/web/assets/img/ana.jpeg',
        'online' => true,
        'mensagens' => [
            ['remetente' => 'medico', 'texto' => 'Olá, como posso ajudar?', 'hora' => '10:30'],
            ['remetente' => 'voce', 'texto' => 'Estou com dor no peito', 'hora' => '10:32'],
            ['remetente' => 'medico', 'texto' => 'Há quanto tempo sente isso?', 'hora' => '10:33']
        ]
    ],
    [
        'id' => 2,
        'nome' => 'Dra. camily',
        'especialidade' => 'Ortopedia',
        'avatar' => '/web/assets/img/camily.jpeg',
        'online' => false,
        'mensagens' => [
            ['remetente' => 'medico', 'texto' => 'Bom dia, seu exame está pronto', 'hora' => '09:15'],
            ['remetente' => 'voce', 'texto' => 'Obrigado, vou buscar hoje', 'hora' => '09:20']
        ]
    ],
    [
        'id' => 3,
        'nome' => 'Dr. João',
        'especialidade' => 'Pediatria',
        'avatar' => '/web/assets/img/joao.jpeg',
        'online' => true,
        'mensagens' => [
            ['remetente' => 'voce', 'texto' => 'Boa tarde, minha filha está com febre', 'hora' => '14:45'],
            ['remetente' => 'medico', 'texto' => 'Qual a temperatura?', 'hora' => '14:47']
        ]
    ],
    [
        'id' => 4,
        'nome' => 'Assistente Virtual',
        'especialidade' => 'IA de Saúde',
        'avatar' => '/web/assets/img/ia.jpeg',
        'online' => true,
        'mensagens' => [
            ['remetente' => 'ia', 'texto' => 'Olá! Sou a assistente virtual da Saúde Conectada. Como posso ajudar?', 'hora' => '11:00'],
            ['remetente' => 'voce', 'texto' => 'Preciso marcar uma consulta', 'hora' => '11:02'],
            ['remetente' => 'ia', 'texto' => 'Posso te ajudar com isso. Qual especialidade você precisa?', 'hora' => '11:02']
        ]
    ]
];

$chatAtivo = $_GET['medico'] ?? 4;
$medicoAtivo = array_filter($medicos, fn($m) => $m['id'] == $chatAtivo);
$medicoAtivo = reset($medicoAtivo);

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
                   class="chat-contato <?= $medico['id'] == $chatAtivo ? 'active' : '' ?>">
                    <img src="<?= $medico['avatar'] ?>" alt="<?= $medico['nome'] ?>" class="chat-avatar">
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
            <img src="<?= $medicoAtivo['avatar'] ?>" alt="<?= $medicoAtivo['nome'] ?>" class="chat-avatar">
            <div class="chat-header-info">
                <h2><?= htmlspecialchars($medicoAtivo['nome']) ?></h2>
                <p><?= htmlspecialchars($medicoAtivo['especialidade']) ?></p>
                <?php if ($medicoAtivo['online']): ?>
                    <span class="online">Online</span>
                <?php else: ?>
                    <span class="offline">Offline</span>
                <?php endif; ?>
            </div>
            <div class="chat-actions">
                <button aria-label="Ligar"><i class="fas fa-phone"></i></button>
                <button aria-label="Vídeo chamada" onclick="window.location.href='videochamada.php'">
    <i class="fas fa-video"></i>
</button>
                <button aria-label="Mais opções"><i class="fas fa-ellipsis-v"></i></button>
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

<script src="/web/assets/js/chat.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.querySelector('.chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    document.querySelector('.send-btn').addEventListener('click', function() {
        const input = document.getElementById('mensagemInput');
        if (input.value.trim() !== '') {
            console.log('Mensagem enviada:', input.value);
            input.value = '';
        }
    });
});
</script>