const Prontuario = (() => {
  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Prontuário Médico</h1></div>
      <div id="prontuario-content">
        <div class="card"><p>Carregando prontuário...</p></div>
      </div>
    `;
  }

  async function load() {
    const content = document.getElementById('prontuario-content');
    if (!content) return;
    try {
      const perfil = await Api.get('/api/perfil');
      let consultas = [], exames = [];
      try { consultas = await Api.get('/api/consultas'); } catch {}
      try { exames = await Api.get('/api/exames'); } catch {}

      content.innerHTML = `
        <div class="card" style="margin-bottom:1.5rem">
          <h2 style="margin-bottom:1rem;color:var(--color-primary)">👤 Dados do Paciente</h2>
          <div class="prontuario-grid">
            <div class="prontuario-field"><span class="prontuario-label">Nome</span><span>${perfil.nome}</span></div>
            <div class="prontuario-field"><span class="prontuario-label">Email</span><span>${perfil.email}</span></div>
            <div class="prontuario-field"><span class="prontuario-label">Tipo</span><span class="btn btn-sm btn-secondary">${perfil.role}</span></div>
            <div class="prontuario-field"><span class="prontuario-label">Cadastro</span><span>${new Date(perfil.criadoEm).toLocaleDateString('pt-BR')}</span></div>
          </div>
        </div>

        <div class="card" style="margin-bottom:1.5rem">
          <h2 style="margin-bottom:1rem;color:var(--color-primary)">📋 Histórico de Consultas (${consultas.length})</h2>
          ${consultas.length ? `<div class="table-container"><table>
            <thead><tr><th>Data</th><th>Médico</th><th>Status</th><th>Observações</th></tr></thead>
            <tbody>${consultas.map(c => `<tr>
              <td>${new Date(c.dataHora).toLocaleDateString('pt-BR')}</td>
              <td>${c.medicoNome}</td>
              <td><span class="btn btn-sm ${c.status === 'Realizada' ? 'btn-primary' : 'btn-secondary'}">${c.status}</span></td>
              <td>${c.observacoes || '-'}</td>
            </tr>`).join('')}</tbody>
          </table></div>` : '<p style="color:var(--color-text-muted)">Nenhuma consulta registrada.</p>'}
        </div>

        <div class="card">
          <h2 style="margin-bottom:1rem;color:var(--color-primary)">🔬 Exames Realizados (${exames.length})</h2>
          ${exames.length ? `<div class="table-container"><table>
            <thead><tr><th>Tipo</th><th>Data</th><th>Laboratório</th><th>Status</th><th>Resultado</th></tr></thead>
            <tbody>${exames.map(e => `<tr>
              <td>${e.tipo}</td>
              <td>${new Date(e.dataSolicitacao).toLocaleDateString('pt-BR')}</td>
              <td>${e.laboratorio}</td>
              <td><span class="btn btn-sm ${e.status === 'Disponivel' ? 'btn-primary' : 'btn-secondary'}">${e.status}</span></td>
              <td>${e.resultado || 'Aguardando'}</td>
            </tr>`).join('')}</tbody>
          </table></div>` : '<p style="color:var(--color-text-muted)">Nenhum exame registrado.</p>'}
        </div>
      `;
    } catch (e) { content.innerHTML = `<div class="card"><p>${e.message}</p></div>`; }
  }

  return { page, load };
})();
