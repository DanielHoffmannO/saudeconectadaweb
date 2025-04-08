<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /saudeconectada/pages/login.php");
    exit();
}

$pageTitle = "Videochamada - Saúde Conectada";
$additionalCSS = [
    '/saudeconectada/assets/css/videochamada.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

require_once __DIR__ . '/../includes/header.php';

// Obter informações do médico
$medicos = [
    1 => ['nome' => 'Dra. Ana', 'especialidade' => 'Cardiologia'],
    2 => ['nome' => 'Dra. Camily', 'especialidade' => 'Ortopedia'],
    3 => ['nome' => 'Dr. João', 'especialidade' => 'Pediatria'],
    4 => ['nome' => 'Assistente Virtual', 'especialidade' => 'IA de Saúde']
];

$medicoId = $_GET['medico'] ?? 4;
$medico = $medicos[$medicoId] ?? $medicos[4];

// Gerar um ID único para cada participante
$userId = 'user_' . bin2hex(random_bytes(5));
?>

<!-- O HTML permanece exatamente igual -->

<script src="https://unpkg.com/peerjs@1.4.7/dist/peerjs.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos da interface (mantido igual)
    
    // Variáveis de estado
    let localStream;
    let peer;
    let currentCall;
    let activeConnection;
    let isVideoOn = true;
    let isMicOn = true;

    // Configuração do PeerJS - CORRIGIDA
    const peerOptions = {
        host: '10.0.0.180', // IP do servidor PeerJS
        port: 9000,
        path: '/myapp',
        debug: 3
    };

    // Inicializar PeerJS - CORRIGIDO
    function initializePeer() {
        try {
            peer = new Peer('<?= $userId ?>', peerOptions);
            
            peer.on('open', function(id) {
                console.log('Meu peer ID:', id);
                yourPeerId.textContent = id;
                peerIdContainer.style.display = 'block';
                connectionStatus.textContent = 'Pronto para conectar';
                connectionStatus.style.color = 'green';
                
                // Conexão automática se houver parâmetro na URL
                const urlParams = new URLSearchParams(window.location.search);
                const connectTo = urlParams.get('connect');
                if(connectTo) {
                    peerIdInput.value = connectTo;
                    connectToPeer(connectTo);
                }
            });
            
            peer.on('call', function(call) {
                call.answer(localStream);
                currentCall = call;
                
                call.on('stream', function(remoteStream) {
                    remoteVideo.srcObject = remoteStream;
                    connectionStatus.textContent = 'Conectado com ' + call.peer;
                    connectionStatus.style.color = 'green';
                });
                
                call.on('close', endCall);
                call.on('error', endCall);
            });
            
            peer.on('error', function(err) {
                console.error('Erro no Peer:', err);
                connectionStatus.textContent = 'Erro: ' + err.message;
                connectionStatus.style.color = 'red';
            });
            
        } catch (error) {
            console.error('Erro na inicialização:', error);
            connectionStatus.textContent = 'Falha ao iniciar conexão P2P';
            connectionStatus.style.color = 'red';
        }
    }

    // Função para conectar a outro peer - CORRIGIDA
    function connectToPeer(peerId) {
        if (!peerId) return;
        
        connectionStatus.textContent = 'Conectando...';
        connectionStatus.style.color = 'orange';
        
        // Conexão para chat
        activeConnection = peer.connect(peerId);
        
        activeConnection.on('open', function() {
            // Iniciar chamada de vídeo
            const call = peer.call(peerId, localStream);
            currentCall = call;
            
            call.on('stream', function(remoteStream) {
                remoteVideo.srcObject = remoteStream;
                connectionStatus.textContent = 'Conectado com ' + peerId;
                connectionStatus.style.color = 'green';
            });
            
            call.on('close', endCall);
            call.on('error', endCall);
        });
        
        activeConnection.on('data', function(data) {
            addMessage(data.sender + ': ' + data.message);
        });
        
        activeConnection.on('error', function(err) {
            console.error('Erro na conexão:', err);
            connectionStatus.textContent = 'Erro na conexão: ' + err.message;
            connectionStatus.style.color = 'red';
        });
    }

    // Função para encerrar chamada - CORRIGIDA
    function endCall() {
        if (currentCall) currentCall.close();
        if (activeConnection) activeConnection.close();
        connectionStatus.textContent = 'Chamada encerrada';
        connectionStatus.style.color = 'black';
        remoteVideo.srcObject = null;
    }

    // Event listener para o botão conectar - CORRIGIDO
    connectButton.addEventListener('click', function() {
        const peerId = peerIdInput.value.trim();
        if (!peerId) {
            alert('Por favor, insira um ID válido');
            return;
        }
        connectToPeer(peerId);
    });

    // Restante do código (startVideo, controles, chat) permanece igual
    // ... (mantenha todas as outras funções como estão)
    
    // Iniciar
    startVideo().then(initializePeer);
    
    async function startVideo() {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });
            localVideo.srcObject = localStream;
        } catch (err) {
            console.error('Erro ao acessar dispositivos:', err);
            connectionStatus.textContent = 'Erro ao acessar câmera/microfone';
            connectionStatus.style.color = 'red';
        }
    }
});
</script>

<?php 
require_once __DIR__ . '/../includes/footer.php';
?>