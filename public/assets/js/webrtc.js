const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');
const peer = new RTCPeerConnection();

peer.ontrack = event => {
  remoteVideo.srcObject = event.streams[0];
};

navigator.mediaDevices.getUserMedia({ video: true, audio: true })
.then(stream => {
  localVideo.srcObject = stream;
  stream.getTracks().forEach(track => peer.addTrack(track, stream));
  init();
});

let isCaller = location.search.includes("caller");

function init() {
  if (isCaller) {
    peer.createOffer().then(offer => {
      peer.setLocalDescription(offer);
      sendData("offer", offer);
      waitForAnswer();
    });
  } else {
    waitForOffer();
  }

  peer.onicecandidate = e => {
    if (e.candidate) sendData("candidate", e.candidate);
  };
}

function sendData(type, data) {
  fetch(`/signaling.php`, {
    method: 'POST',
    body: JSON.stringify({ type, data }),
    headers: { 'Content-Type': 'application/json' }
  });
}

function waitForOffer() {
  const interval = setInterval(() => {
    fetch(`/signaling.php?type=offer`)
      .then(res => res.json())
      .then(offer => {
        if (offer) {
          peer.setRemoteDescription(offer);
          peer.createAnswer().then(answer => {
            peer.setLocalDescription(answer);
            sendData("answer", answer);
          });
          clearInterval(interval);
          waitForCandidates();
        }
      });
  }, 1000);
}

function waitForAnswer() {
  const interval = setInterval(() => {
    fetch(`/signaling.php?type=answer`)
      .then(res => res.json())
      .then(answer => {
        if (answer) {
          peer.setRemoteDescription(answer);
          clearInterval(interval);
          waitForCandidates();
        }
      });
  }, 1000);
}

function waitForCandidates() {
  const interval = setInterval(() => {
    fetch(`/signaling.php?type=candidate`)
      .then(res => res.json())
      .then(candidate => {
        if (candidate) {
          peer.addIceCandidate(new RTCIceCandidate(candidate));
        }
      });
  }, 1000);
}
