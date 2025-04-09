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

<main style="padding: 20px;">

    <video id="localVideo" autoplay muted playsinline style="width: 45%; border: 2px solid #ccc;"></video>
    <video id="remoteVideo" autoplay playsinline style="width: 45%; border: 2px solid #ccc;"></video>

    <div style="margin-top: 20px;">
        <button onclick="start()">Iniciar</button>
        <button onclick="hangup()">Desligar</button>
    </div>
</main>

<script src="/assets/js/videochat.js"></script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
