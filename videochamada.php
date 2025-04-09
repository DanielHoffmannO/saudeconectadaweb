<!DOCTYPE html>
<html>
<head>
  <title>Videochamada WebRTC</title>
</head>
<body>
  <h1>Videochamada</h1>
  <video id="localVideo" autoplay muted playsinline></video>
  <video id="remoteVideo" autoplay playsinline></video>

  <script>
    const localVideo = document.getElementById('localVideo');
    const remoteVideo = document.getElementById('remoteVideo');
    const peer = new RTCPeerConnection();

    peer.ontrack = e => {
      remoteVideo.srcObject = e.streams[0];
    };

    navigator.mediaDevices.getUserMedia({ video: true, audio: true }).then(stream => {
      localVideo.srcObject = stream;
      stream.getTracks().forEach(track => peer.addTrack(track, stream));
      iniciar();
    });

    const isCaller = location.search.includes("caller");

    function iniciar() {
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
      fetch("/signaling.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ type, data })
      });
    }

    function waitForOffer() {
      const interval = setInterval(() => {
        fetch("/signaling.php?type=offer")
          .then(res => res.json())
          .then(offer => {
            if (offer && offer.type === "offer") {
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
        fetch("/signaling.php?type=answer")
          .then(res => res.json())
          .then(answer => {
            if (answer && answer.type === "answer") {
              peer.setRemoteDescription(answer);
              clearInterval(interval);
              waitForCandidates();
            }
          });
      }, 1000);
    }

    function waitForCandidates() {
      setInterval(() => {
        fetch("/signaling.php?type=candidate")
          .then(res => res.json())
          .then(candidate => {
            if (candidate && candidate.candidate) {
              peer.addIceCandidate(new RTCIceCandidate(candidate));
            }
          });
      }, 1000);
    }
  </script>
</body>
</html>
