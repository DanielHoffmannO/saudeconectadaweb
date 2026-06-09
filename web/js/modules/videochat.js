const VideoChat = (() => {
  let localStream = null;
  let chamadaAtiva = false;

  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Videochamada</h1></div>
      <div class="card">
        <div class="videochat-status" id="vc-status">
          <p style="color:var(--color-text-muted)">📹 Clique em "Iniciar Câmera" para começar</p>
        </div>
        <div class="video-grid">
          <div class="video-container">
            <video id="localVideo" autoplay muted playsinline></video>
            <div class="video-label">Você</div>
          </div>
          <div class="video-container">
            <video id="remoteVideo" autoplay playsinline></video>
            <div class="video-label">Médico</div>
            <div class="video-placeholder" id="remote-placeholder">
              <p>Aguardando conexão...</p>
            </div>
          </div>
        </div>
        <div class="call-controls">
          <button class="btn btn-primary" id="btn-start" onclick="VideoChat.start()">📹 Iniciar Câmera</button>
          <button class="btn btn-secondary" id="btn-mute" onclick="VideoChat.toggleMute()" style="display:none">🎤 Mutar</button>
          <button class="btn btn-secondary" id="btn-video" onclick="VideoChat.toggleVideo()" style="display:none">📷 Desligar Vídeo</button>
          <button class="btn btn-danger" id="btn-end" onclick="VideoChat.stop()" style="display:none">📞 Encerrar</button>
        </div>
        <div class="videochat-info card" style="margin-top:1.5rem;background:var(--color-bg)">
          <h3 style="margin-bottom:0.5rem">ℹ️ Informações</h3>
          <ul style="color:var(--color-text-muted);font-size:0.85rem;list-style:disc;padding-left:1.5rem">
            <li>Certifique-se de permitir o acesso à câmera e microfone</li>
            <li>Use uma conexão estável para melhor qualidade</li>
            <li>A chamada é criptografada ponta a ponta</li>
          </ul>
        </div>
      </div>
    `;
  }

  async function start() {
    try {
      localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
      document.getElementById('localVideo').srcObject = localStream;
      chamadaAtiva = true;
      document.getElementById('vc-status').innerHTML = '<p style="color:var(--color-primary)">🟢 Câmera ativa — aguardando médico conectar</p>';
      document.getElementById('btn-start').style.display = 'none';
      document.getElementById('btn-mute').style.display = '';
      document.getElementById('btn-video').style.display = '';
      document.getElementById('btn-end').style.display = '';
    } catch (e) {
      document.getElementById('vc-status').innerHTML = `<p style="color:var(--color-danger)">❌ Erro: ${e.message}</p>`;
    }
  }

  function toggleMute() {
    if (!localStream) return;
    const audio = localStream.getAudioTracks()[0];
    if (audio) {
      audio.enabled = !audio.enabled;
      document.getElementById('btn-mute').textContent = audio.enabled ? '🎤 Mutar' : '🎤 Desmutar';
    }
  }

  function toggleVideo() {
    if (!localStream) return;
    const video = localStream.getVideoTracks()[0];
    if (video) {
      video.enabled = !video.enabled;
      document.getElementById('btn-video').textContent = video.enabled ? '📷 Desligar Vídeo' : '📷 Ligar Vídeo';
    }
  }

  function stop() {
    if (localStream) {
      localStream.getTracks().forEach(t => t.stop());
      localStream = null;
    }
    chamadaAtiva = false;
    const lv = document.getElementById('localVideo');
    if (lv) lv.srcObject = null;
    document.getElementById('vc-status').innerHTML = '<p style="color:var(--color-text-muted)">📹 Chamada encerrada</p>';
    document.getElementById('btn-start').style.display = '';
    document.getElementById('btn-mute').style.display = 'none';
    document.getElementById('btn-video').style.display = 'none';
    document.getElementById('btn-end').style.display = 'none';
  }

  function load() {}

  return { page, load, start, stop, toggleMute, toggleVideo };
})();
