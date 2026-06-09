const Notificacoes = (() => {
  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Notificações</h1></div>
      <div class="card">
        <div id="notificacoes-list"><p>Carregando...</p></div>
      </div>
    `;
  }

  async function load() {
    const container = document.getElementById('notificacoes-list');
    if (!container) return;
    try {
      const notificacoes = await Api.get('/api/notificacoes');
      if (!notificacoes.length) { container.innerHTML = '<p>Nenhuma notificação.</p>'; return; }
      container.innerHTML = notificacoes.map(n => `
        <div class="notificacao-item ${n.lida ? 'lida' : 'nao-lida'}" onclick="Notificacoes.marcarLida(${n.id})">
          <strong>${n.titulo}</strong>
          <p>${n.mensagem}</p>
          <small>${new Date(n.criadaEm).toLocaleString('pt-BR')}</small>
        </div>
      `).join('');
    } catch (e) { container.innerHTML = `<p>${e.message}</p>`; }
  }

  async function marcarLida(id) {
    try { await Api.put(`/api/notificacoes/${id}/lida`); load(); } catch {}
  }

  return { page, load, marcarLida };
})();
