let localStream;
let peerConnection;
const config = {
    iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
};

const localVideo = document.getElementById("localVideo");
const remoteVideo = document.getElementById("remoteVideo");

async function start() {
    localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    localVideo.srcObject = localStream;

    peerConnection = new RTCPeerConnection(config);

    localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

    peerConnection.ontrack = event => {
        remoteVideo.srcObject = event.streams[0];
    };

    peerConnection.onicecandidate = event => {
        if (event.candidate) {
            console.log("New ICE Candidate:", JSON.stringify(event.candidate));
        }
    };

    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);

    console.log("Oferta (cole no outro PC):", JSON.stringify(offer));
}

async function answer(offerStr) {
    const offer = JSON.parse(offerStr);
    await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));

    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);

    console.log("Resposta (cole no primeiro PC):", JSON.stringify(answer));
}

async function setAnswer(answerStr) {
    const answer = JSON.parse(answerStr);
    await peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
}

function hangup() {
    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
    }
}
