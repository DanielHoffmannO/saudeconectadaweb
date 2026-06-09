const Prontuario = (() => {
  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Prontuário Médico</h1></div>
      <div class="card" id="prontuario-content"><p>Carregando...</p></div>
    `;
  }

  async function load() {
    const content = document.getElementById('prontuario-content');
    if (!content) return;
    try {
      const perfil = await Api.get('/api/perfil');
      const consultas = await Api.get('/api/consultas');
      const exames = await Api.get('/api/exames');

      content.innerHTML = `
        <h2>Dados do Paciente</h2>
        <p><strong>Nome:</strong> ${perfil.nome}</p>
        <p><strong>Email:</strong> ${perfil.email}</p>

        <h2 style="margin-top:1.5rem">Histórico de Consultas</h2>
        ${consultas.length ? consultas.map(c => `
          <div class="prontuario-item">
            <p><strong>${new Date(c.dataHora).toLocaleDateString('pt-BR')}</strong> - ${c.medicoNome}</p>
            <p>Status: ${c.status} ${c.observacoes ? '| ' + c.observacoes : ''}</p>
          </div>
        `).join('') : '<p>Nenhuma consulta registrada.</p>'}

        <h2 style="margin-top:1.5rem">Exames</h2>
        ${exames.length ? exames.map(e => `
          <div class="prontuario-item">
            <p><strong>${e.tipo}</strong> - ${new Date(e.dataSolicitacao).toLocaleDateString('pt-BR')}</p>
            <p>Resultado: ${e.resultado || 'Aguardando'}</p>
          </div>
        `).join('') : '<p>Nenhum exame registrado.</p>'}
      `;
    } catch (e) { content.innerHTML = `<p>${e.message}</p>`; }
  }

  return { page, load };
})();
