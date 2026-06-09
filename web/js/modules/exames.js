const Exames = (() => {
  function page() {
    return `
      <div class="page-header">
        <h1 class="page-title">Exames</h1>
        <button class="btn btn-primary" onclick="Exames.showForm()">+ Solicitar Exame</button>
      </div>
      <div id="exames-modal"></div>
      <div class="card">
        <div class="filter-bar">
          <button class="btn btn-sm btn-secondary" onclick="Exames.filter('')">Todos</button>
          <button class="btn btn-sm btn-secondary" onclick="Exames.filter('Disponivel')">Disponíveis</button>
          <button class="btn btn-sm btn-secondary" onclick="Exames.filter('Agendado')">Agendados</button>
          <button class="btn btn-sm btn-secondary" onclick="Exames.filter('Pendente')">Pendentes</button>
        </div>
        <div class="table-container">
          <table>
            <thead><tr><th>Tipo</th><th>Laboratório</th><th>Data</th><th>Status</th><th>Resultado</th></tr></thead>
            <tbody id="exames-list"><tr><td colspan="5">Carregando...</td></tr></tbody>
          </table>
        </div>
      </div>
    `;
  }

  let allExames = [];

  async function load() {
    try {
      allExames = await Api.get('/api/exames');
      render(allExames);
    } catch (e) {
      document.getElementById('exames-list').innerHTML = `<tr><td colspan="5">${e.message}</td></tr>`;
    }
  }

  function filter(status) {
    const filtered = status ? allExames.filter(e => e.status === status) : allExames;
    render(filtered);
  }

  function render(exames) {
    const list = document.getElementById('exames-list');
    if (!list) return;
    if (!exames.length) { list.innerHTML = '<tr><td colspan="5">Nenhum exame encontrado</td></tr>'; return; }
    list.innerHTML = exames.map(e => `
      <tr>
        <td>${e.tipo}</td>
        <td>${e.laboratorio}</td>
        <td>${new Date(e.dataSolicitacao).toLocaleDateString('pt-BR')}</td>
        <td><span class="btn btn-sm ${e.status === 'Disponivel' ? 'btn-primary' : 'btn-secondary'}">${e.status}</span></td>
        <td>${e.resultado || 'Aguardando'}</td>
      </tr>
    `).join('');
  }

  async function showForm() {
    let medicos = [];
    try { medicos = await Api.get('/api/medicos'); } catch {}

    document.getElementById('exames-modal').innerHTML = `
      <div class="modal-overlay" onclick="Exames.closeForm(event)">
        <div class="modal" onclick="event.stopPropagation()">
          <h2 class="modal-title">Solicitar Exame</h2>
          <form id="exame-form">
            <div class="form-group"><label>Tipo do Exame</label><input type="text" id="ex-tipo" required placeholder="Ex: Hemograma"></div>
            <div class="form-group"><label>Laboratório</label><input type="text" id="ex-lab" required placeholder="Nome do laboratório"></div>
            <div class="form-group"><label>Médico Solicitante</label>
              <select id="ex-medico" required><option value="">Selecione...</option>${medicos.map(m => `<option value="${m.id}">${m.nome}</option>`).join('')}</select>
            </div>
            <div class="form-group"><label>Observações</label><textarea id="ex-obs" rows="3"></textarea></div>
            <div class="modal-actions">
              <button type="button" class="btn btn-secondary" onclick="Exames.closeForm()">Cancelar</button>
              <button type="submit" class="btn btn-primary">Solicitar</button>
            </div>
          </form>
        </div>
      </div>
    `;
  }

  function closeForm(e) {
    if (e && e.target !== e.currentTarget) return;
    document.getElementById('exames-modal').innerHTML = '';
  }

  async function save(e) {
    e.preventDefault();
    const body = {
      pacienteId: 1,
      medicoId: parseInt(document.getElementById('ex-medico').value),
      tipo: document.getElementById('ex-tipo').value,
      laboratorio: document.getElementById('ex-lab').value,
      observacoes: document.getElementById('ex-obs').value
    };
    try { await Api.post('/api/exames', body); closeForm(); load(); }
    catch (err) { alert(err.message); }
  }

  function bindEvents() {
    document.addEventListener('submit', (e) => { if (e.target.id === 'exame-form') save(e); });
  }

  return { page, load, showForm, closeForm, filter, bindEvents };
})();
