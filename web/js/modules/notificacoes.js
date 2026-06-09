const Notificacoes = (() => {
  let todasNotificacoes = [];

  function page() {
    return `
      <div class="page-header">
        <h1 class="page-title">Notificações</h1>
        <span class="btn btn-sm btn-secondary" id="notif-badge">0 não lidas</span>
      </div>
      <div class="notif-filters" style="margin-bottom:1rem;display:flex;gap:0.5rem">
        <button class="btn btn-sm btn-primary" onclick="Notificacoes.filtrar('todas')">Todas</button>
        <button class="btn btn-sm btn-secondary" onclick="Notificacoes.filtrar('nao-lidas')">Não Lidas</button>
        <button class="btn btn-sm btn-secondary" onclick="Notificacoes.filtrar('consulta')">Consultas</button>
        <button class="btn btn-sm btn-secondary" onclick="Notificacoes.filtrar('exame')">Exames</button>
        <button class="btn btn-sm btn-secondary" onclick="Notificacoes.filtrar('mensagem')">Mensagens</button>
      </div>
      <div id="notificacoes-list"></div>
    `;
  }

  async function load() {
    const container = document.getElementById('notificacoes-list');
    if (!container) return;
    try {
      todasNotificacoes = await Api.get('/api/notificacoes');
      const naoLidas = todasNotificacoes.filter(n => !n.lida).length;
      const badge = document.getElementById('notif-badge');
      if (badge) badge.textContent = `${naoLidas} não lida${naoLidas !== 1 ? 's' : ''}`;
      render(todasNotificacoes);
    } catch (e) { container.innerHTML = `<div class="card"><p>${e.message}</p></div>`; }
  }

  function filtrar(tipo) {
    let filtered = todasNotificacoes;
    if (tipo === 'nao-lidas') filtered = todasNotificacoes.filter(n => !n.lida);
    else if (tipo === 'consulta') filtered = todasNotificacoes.filter(n => n.tipo === 'Consulta');
    else if (tipo === 'exame') filtered = todasNotificacoes.filter(n => n.tipo === 'Exame');
    else if (tipo === 'mensagem') filtered = todasNotificacoes.filter(n => n.tipo === 'Mensagem');
    render(filtered);
  }

  function render(notificacoes) {
    const container = document.getElementById('notificacoes-list');
    if (!container) return;
    if (!notificacoes.length) {
      container.innerHTML = '<div class="card"><p style="color:var(--color-text-muted)">Nenhuma notificação encontrada.</p></div>';
      return;
    }
    container.innerHTML = notificacoes.map(n => `
      <div class="notif-card card ${n.lida ? 'notif-lida' : 'notif-nao-lida'}" style="margin-bottom:0.75rem;cursor:pointer" onclick="Notificacoes.marcarLida(${n.id})">
        <div class="notif-row">
          <span class="notif-icon">${getIcon(n.tipo)}</span>
          <div class="notif-body">
            <strong>${n.titulo}</strong>
            <p style="color:var(--color-text-muted);font-size:0.85rem;margin-top:0.25rem">${n.mensagem}</p>
          </div>
          <div class="notif-meta">
            <small style="color:var(--color-text-muted)">${timeAgo(n.criadaEm)}</small>
            ${!n.lida ? '<span class="notif-dot"></span>' : ''}
          </div>
        </div>
      </div>
    `).join('');
  }

  function getIcon(tipo) {
    if (tipo === 'Consulta') return '📅';
    if (tipo === 'Exame') return '🔬';
    if (tipo === 'Mensagem') return '💬';
    return '🔔';
  }

  function timeAgo(date) {
    const diff = Date.now() - new Date(date).getTime();
    const mins = Math.floor(diff / 60000);
    if (mins < 60) return `há ${mins}min`;
    const hrs = Math.floor(mins / 60);
    if (hrs < 24) return `há ${hrs}h`;
    return `há ${Math.floor(hrs / 24)}d`;
  }

  async function marcarLida(id) {
    try { await Api.put(`/api/notificacoes/${id}/lida`); load(); } catch {}
  }

  return { page, load, marcarLida, filtrar };
})();
