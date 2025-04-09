<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$medicoId = $_GET['medico'] ?? null;

$medicos = [
    1 => ['nome' => 'Dra. Ana', 'especialidade' => 'Cardiologia', 'avatar' => '/assets/img/ana.jpeg'],
    2 => ['nome' => 'Dra. Camily', 'especialidade' => 'Ortopedia', 'avatar' => '/assets/img/camily.jpeg'],
    3 => ['nome' => 'Dr. João', 'especialidade' => 'Pediatria', 'avatar' => '/assets/img/joao.jpeg']
];

if (!$medicoId || !isset($medicos[$medicoId])) {
    header("Location: chat.php");
    exit();
}

$medico = $medicos[$medicoId];
$pageTitle = "Videochamada - " . $medico['nome'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="videochat-container">
    <div class="videochat-header">
        <h2>Videochamada com <?= htmlspecialchars($medico['nome']) ?> (<?= htmlspecialchars($medico['especialidade']) ?>)</h2>
    </div>
    
    <div class="videochat-content">
        <div class="remote-video">
            <video id="remoteVideo" autoplay playsinline></video>
            <div class="video-overlay">
                <img src="<?= $medico['avatar'] ?>" alt="<?= $medico['nome'] ?>">
                <p>Conectando com <?= $medico['nome'] ?>...</p>
            </div>
        </div>
        
        <div class="local-video">
            <video id="localVideo" autoplay playsinline muted></video>
        </div>
    </div>
    
    <div class="videochat-controls">
        <button id="toggleAudio" class="control-btn" title="Microfone">
            <i class="fas fa-microphone"></i>
        </button>
        <button id="toggleVideo" class="control-btn" title="Câmera">
            <i class="fas fa-video"></i>
        </button>
        <button id="endCall" class="control-btn end-call" title="Encerrar chamada">
            <i class="fas fa-phone-slash"></i>
        </button>
    </div>
</div>

<script src="/assets/js/videochat.js"></script>
<script>
document.getElementById('endCall').addEventListener('click', function() {
    window.location.href = 'chat.php?medico=<?= $medicoId ?>';
});
</script>

<?php 
require_once __DIR__ . '/../includes/footer.php';
?>