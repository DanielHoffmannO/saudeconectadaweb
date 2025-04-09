<?php
session_start();

// Redireciona para login se o usuário não estiver logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Constante para identificar a assistente virtual
define('IA_ID', 4);

// Lista de médicos fictícios (normalmente viria do banco de dados)
$medicos = [
    [
        'id' => 1,
        'nome' => 'Dra. Ana',
        'especialidade' => 'Cardiologia',
        'avatar' => '/assets/img/ana.jpeg',
        'online' => true,
        'mensagens' => [
            ['remetente' => 'medico', 'texto' => 'Olá, como posso ajudar?', 'hora' => '10:30'],
            ['remetente' => 'voce', 'texto' => 'Estou com dor no peito', 'hora' => '10:32'],
            ['remetente' => 'medico', 'texto' => 'Há quanto tempo sente isso?', 'hora' => '10:33']
        ]
    ],
    [
        'id' => 2,
        'nome' => 'Dra. Camily',
        'especialidade' => 'Ortopedia',
        'avatar' => '/assets/img/camily.jpeg',
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
        'avatar' => '/assets/img/joao.jpeg',
        'online' => true,
        'mensagens' => [
            ['remetente' => 'voce', 'texto' => 'Boa tarde, minha filha está com febre', 'hora' => '14:45'],
            ['remetente' => 'medico', 'texto' => 'Qual a temperatura?', 'hora' => '14:47']
        ]
    ],
    [
        'id' => IA_ID,
        'nome' => 'Assistente Virtual',
        'especialidade' => 'IA de Saúde',
        'avatar' => '/assets/img/ia.jpeg',
        'online' => true,
        'mensagens' => [
            ['remetente' => 'ia', 'texto' => 'Olá! Sou a assistente virtual da Saúde Conectada. Como posso ajudar?', 'hora' => '11:00'],
            ['remetente' => 'voce', 'texto' => 'Preciso marcar uma consulta', 'hora' => '11:02'],
            ['remetente' => 'ia', 'texto' => 'Posso te ajudar com isso. Qual especialidade você precisa?', 'hora' => '11:02']
        ]
    ]
];

// Valida o ID do médico
$chatAtivo = isset($_GET['medico']) && is_numeric($_GET['medico']) ? (int)$_GET['medico'] : IA_ID;

// Busca o médico ativo
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
                <?php if ($medicoAtivo['id'] !== IA_ID): ?>
                    <form action="videochat.php" method="get" style="display: inline;">
                        <input type="hidden" name="medico_id" value="<?= $medicoAtivo['id'] ?>">
                        <button class="video-call-btn" aria-label="Vídeo chamada" <?= !$medicoAtivo['online'] ? 'disabled' : '' ?>>
                            <i class="fas fa-video"></i> Vídeo Chamada
                        </button>
                    </form>
                <?php endif; ?>
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
