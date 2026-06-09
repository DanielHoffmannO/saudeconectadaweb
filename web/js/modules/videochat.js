const VideoChat = (() => {
  let localStream = null;

  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Videochamada</h1></div>
      <div class="card">
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
          <button class="btn btn-primary" onclick="VideoChat.start()">Iniciar Câmera</button>
          <button class="btn btn-danger" onclick="VideoChat.stop()">Encerrar</button>
        </div>
      </div>
    `;
  }

  async function start() {
    try {
      localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
      document.getElementById('localVideo').srcObject = localStream;
    } catch (e) { alert('Erro ao acessar câmera: ' + e.message); }
  }

  function stop() {
    if (localStream) {
      localStream.getTracks().forEach(t => t.stop());
      localStream = null;
    }
    const lv = document.getElementById('localVideo');
    if (lv) lv.srcObject = null;
  }

  function load() {}

  return { page, load, start, stop };
})();
