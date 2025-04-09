let localStream;
let peerConnection;
const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');
const signalingUrl = "/signaling.php";

const config = {
    iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
};

async function start() {
    localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    localVideo.srcObject = localStream;

    peerConnection = new RTCPeerConnection(config);

    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });

    peerConnection.ontrack = (event) => {
        remoteVideo.srcObject = event.streams[0];
    };

    peerConnection.onicecandidate = event => {
        if (event.candidate) {
            sendSignal({ type: 'candidate', payload: event.candidate });
        }
    };

    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);
    sendSignal({ type: 'offer', payload: offer });

    pollSignals(); // inicia a escuta
}

async function pollSignals() {
    setInterval(async () => {
        const res = await fetch(signalingUrl);
        const messages = await res.json();

        for (let msg of messages) {
            if (msg.type === 'offer') {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(msg.payload));
                const answer = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answer);
                sendSignal({ type: 'answer', payload: answer });
            }

            if (msg.type === 'answer') {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(msg.payload));
            }

            if (msg.type === 'candidate') {
                try {
                    await peerConnection.addIceCandidate(new RTCIceCandidate(msg.payload));
                } catch (e) {
                    console.error('Erro ao adicionar candidate:', e);
                }
            }
        }
    }, 1000); // a cada segundo
}

async function sendSignal(message) {
    await fetch(signalingUrl, {
        method: 'POST',
        body: JSON.stringify(message)
    });
}

function hangup() {
    if (peerConnection) peerConnection.close();
    localVideo.srcObject = null;
    remoteVideo.srcObject = null;
}
