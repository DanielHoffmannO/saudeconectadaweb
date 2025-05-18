<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$medicoId = 1;
$pageTitle = "Chamada de Vídeo";
$additionalCSS = [];

require_once __DIR__ . '/../includes/header.php';
?>

<main class="videochamada-container">

    <div class="video-header">
        <div class="medico-info">
            <img src="/assets/img/ana.jpeg" alt="Médico" class="medico-avatar">
            <div>
                <strong>Dra. Ana</strong><br>
                <span id="call-status">Conectando...</span>
            </div>
        </div>
        <div class="call-info">
            <span id="call-timer">00:00</span>
        </div>
    </div>

    <div class="video-grid">
        <div class="video-container">
            <video id="localVideo" autoplay muted playsinline></video>
            <span class="video-label">Você</span>
        </div>
        <div class="video-container">
            <video id="remoteVideo" autoplay playsinline></video>
            <span class="video-label">Médico</span>
        </div>
    </div>

    <div class="call-controls">
        <button class="control-btn active" onclick="start()" title="Iniciar Chamada">
            <i class="fas fa-video"></i>
        </button>
        <button class="control-btn end-call" onclick="hangup()" title="Desligar">
            <i class="fas fa-phone-slash"></i>
        </button>
    </div>

</main>

<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<script src="/assets/js/videochat.js"></script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
