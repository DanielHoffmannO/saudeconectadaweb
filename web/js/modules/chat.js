const Chat = (() => {
  let contatoAtivo = null;

  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Chat</h1></div>
      <div class="chat-layout">
        <div class="chat-sidebar card" id="chat-contatos"><p>Carregando contatos...</p></div>
        <div class="chat-main card">
          <div id="chat-header" class="chat-hdr"></div>
          <div id="chat-messages" class="chat-msgs"><p class="text-center">Selecione um contato</p></div>
          <div id="chat-input-area" class="chat-input-area" style="display:none">
            <input type="text" id="chat-msg-input" placeholder="Digite uma mensagem..." class="chat-input">
            <button class="btn btn-primary" onclick="Chat.enviar()">Enviar</button>
          </div>
        </div>
      </div>
    `;
  }

  async function load() {
    try {
      const medicos = await Api.get('/api/medicos');
      const sidebar = document.getElementById('chat-contatos');
      if (!sidebar) return;
      sidebar.innerHTML = medicos.map(m => `
        <div class="chat-contato" onclick="Chat.selectContato(${m.id}, '${m.nome}')">
          <strong>${m.nome}</strong><br><small>${m.especialidade}</small>
        </div>
      `).join('');
    } catch (e) {
      document.getElementById('chat-contatos').innerHTML = `<p>${e.message}</p>`;
    }
  }

  async function selectContato(id, nome) {
    contatoAtivo = id;
    document.getElementById('chat-header').innerHTML = `<strong>${nome}</strong>`;
    document.getElementById('chat-input-area').style.display = 'flex';
    await loadMessages();
  }

  async function loadMessages() {
    if (!contatoAtivo) return;
    try {
      const msgs = await Api.get(`/api/mensagens/conversa/${contatoAtivo}`);
      const container = document.getElementById('chat-messages');
      if (!msgs.length) { container.innerHTML = '<p class="text-center">Nenhuma mensagem ainda</p>'; return; }
      container.innerHTML = msgs.map(m => `
        <div class="message ${m.remetenteId === contatoAtivo ? 'received' : 'sent'}">
          <p>${m.conteudo}</p>
          <small>${new Date(m.enviadaEm).toLocaleTimeString('pt-BR')}</small>
        </div>
      `).join('');
      container.scrollTop = container.scrollHeight;
    } catch (e) {
      document.getElementById('chat-messages').innerHTML = `<p>${e.message}</p>`;
    }
  }

  async function enviar() {
    const input = document.getElementById('chat-msg-input');
    if (!input.value.trim() || !contatoAtivo) return;
    try {
      await Api.post('/api/mensagens', { destinatarioId: contatoAtivo, conteudo: input.value });
      input.value = '';
      await loadMessages();
    } catch (e) { alert(e.message); }
  }

  return { page, load, selectContato, enviar };
})();
