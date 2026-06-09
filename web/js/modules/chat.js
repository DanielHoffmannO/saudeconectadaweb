const Chat = (() => {
  let contatoAtivo = null;
  let contatoNome = '';

  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Chat</h1></div>
      <div class="chat-layout">
        <div class="chat-sidebar card" id="chat-contatos">
          <div class="chat-sidebar-header">
            <input type="text" placeholder="Buscar contato..." class="chat-search-input" oninput="Chat.filtrar(this.value)">
          </div>
          <div id="chat-contatos-list"><p style="padding:1rem;color:var(--color-text-muted)">Carregando...</p></div>
        </div>
        <div class="chat-main card">
          <div id="chat-header" class="chat-hdr">
            <p style="color:var(--color-text-muted)">Selecione um contato para iniciar a conversa</p>
          </div>
          <div id="chat-messages" class="chat-msgs"></div>
          <div id="chat-input-area" class="chat-input-area" style="display:none">
            <input type="text" id="chat-msg-input" placeholder="Digite sua mensagem..." class="chat-input" onkeydown="if(event.key==='Enter')Chat.enviar()">
            <button class="btn btn-primary" onclick="Chat.enviar()">Enviar</button>
          </div>
        </div>
      </div>
    `;
  }

  let todosContatos = [];

  async function load() {
    try {
      todosContatos = await Api.get('/api/medicos');
      renderContatos(todosContatos);
    } catch (e) {
      document.getElementById('chat-contatos-list').innerHTML = `<p style="padding:1rem">${e.message}</p>`;
    }
  }

  function renderContatos(lista) {
    const container = document.getElementById('chat-contatos-list');
    if (!container) return;
    container.innerHTML = lista.map(m => `
      <div class="chat-contato ${contatoAtivo === m.id ? 'active' : ''}" onclick="Chat.selectContato(${m.id}, '${m.nome}', '${m.especialidade}')">
        <div class="chat-contato-avatar">${m.nome.charAt(0)}</div>
        <div class="chat-contato-info">
          <strong>${m.nome}</strong>
          <small>${m.especialidade}</small>
        </div>
      </div>
    `).join('');
  }

  function filtrar(q) {
    const filtered = todosContatos.filter(m =>
      m.nome.toLowerCase().includes(q.toLowerCase()) || m.especialidade.toLowerCase().includes(q.toLowerCase())
    );
    renderContatos(filtered);
  }

  async function selectContato(id, nome, esp) {
    contatoAtivo = id;
    contatoNome = nome;
    document.getElementById('chat-header').innerHTML = `
      <div class="chat-hdr-info">
        <div class="chat-contato-avatar">${nome.charAt(0)}</div>
        <div><strong>${nome}</strong><br><small style="color:var(--color-text-muted)">${esp || ''}</small></div>
      </div>
      <a href="#/videochat" class="btn btn-sm btn-secondary">📹 Videochamada</a>
    `;
    document.getElementById('chat-input-area').style.display = 'flex';
    renderContatos(todosContatos);
    await loadMessages();
  }

  async function loadMessages() {
    if (!contatoAtivo) return;
    const container = document.getElementById('chat-messages');
    try {
      const msgs = await Api.get(`/api/mensagens/conversa/${contatoAtivo}`);
      if (!msgs.length) {
        container.innerHTML = '<p style="text-align:center;color:var(--color-text-muted);padding:3rem">Nenhuma mensagem ainda. Diga olá! 👋</p>';
        return;
      }
      container.innerHTML = msgs.map(m => `
        <div class="message ${m.remetenteId === contatoAtivo ? 'received' : 'sent'}">
          <div class="message-bubble">
            <p>${m.conteudo}</p>
            <small>${new Date(m.enviadaEm).toLocaleTimeString('pt-BR', {hour:'2-digit',minute:'2-digit'})}</small>
          </div>
        </div>
      `).join('');
      container.scrollTop = container.scrollHeight;
    } catch (e) {
      container.innerHTML = `<p style="padding:1rem">${e.message}</p>`;
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

  return { page, load, selectContato, enviar, filtrar };
})();
