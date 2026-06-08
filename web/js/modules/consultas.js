const Consultas = (() => {
  function page() {
    return `
      <div class="page-header">
        <h1 class="page-title">Consultas</h1>
        <button class="btn btn-primary" onclick="Consultas.showForm()">+ Agendar Consulta</button>
      </div>
      <div id="consultas-modal"></div>
      <div class="card">
        <div class="table-container">
          <table>
            <thead>
              <tr><th>Data/Hora</th><th>Paciente</th><th>Médico</th><th>Status</th><th>Ações</th></tr>
            </thead>
            <tbody id="consultas-list"><tr><td colspan="5">Carregando...</td></tr></tbody>
          </table>
        </div>
      </div>
    `;
  }

  async function load() {
    try {
      const data = await Api.get('/api/consultas');
      const list = document.getElementById('consultas-list');
      if (!list) return;
      if (!data.length) { list.innerHTML = '<tr><td colspan="5">Nenhuma consulta agendada</td></tr>'; return; }
      list.innerHTML = data.map(c => `
        <tr>
          <td>${new Date(c.dataHora).toLocaleString('pt-BR')}</td>
          <td>${c.pacienteNome || c.pacienteId}</td>
          <td>${c.medicoNome || c.medicoId}</td>
          <td><span class="btn btn-sm ${c.status === 'Confirmada' ? 'btn-primary' : 'btn-secondary'}">${c.status || 'Agendada'}</span></td>
          <td>
            <button class="btn btn-danger btn-sm" onclick="Consultas.remove(${c.id})">Cancelar</button>
          </td>
        </tr>
      `).join('');
    } catch (e) {
      const list = document.getElementById('consultas-list');
      if (list) list.innerHTML = `<tr><td colspan="5">${e.message}</td></tr>`;
    }
  }

  async function showForm() {
    let medicos = [], pacientes = [];
    try { medicos = await Api.get('/api/medicos'); } catch {}
    try { pacientes = await Api.get('/api/pacientes'); } catch {}

    document.getElementById('consultas-modal').innerHTML = `
      <div class="modal-overlay" onclick="Consultas.closeForm(event)">
        <div class="modal" onclick="event.stopPropagation()">
          <h2 class="modal-title">Agendar Consulta</h2>
          <form id="consulta-form">
            <div class="form-group">
              <label>Paciente</label>
              <select id="con-paciente" required>
                <option value="">Selecione...</option>
                ${pacientes.map(p => `<option value="${p.id}">${p.nome}</option>`).join('')}
              </select>
            </div>
            <div class="form-group">
              <label>Médico</label>
              <select id="con-medico" required>
                <option value="">Selecione...</option>
                ${medicos.map(m => `<option value="${m.id}">${m.nome} - ${m.especialidade}</option>`).join('')}
              </select>
            </div>
            <div class="form-group">
              <label>Data e Hora</label>
              <input type="datetime-local" id="con-data" required>
            </div>
            <div class="form-group">
              <label>Observações</label>
              <textarea id="con-obs" rows="3"></textarea>
            </div>
            <div class="modal-actions">
              <button type="button" class="btn btn-secondary" onclick="Consultas.closeForm()">Cancelar</button>
              <button type="submit" class="btn btn-primary">Agendar</button>
            </div>
          </form>
        </div>
      </div>
    `;
  }

  function closeForm(e) {
    if (e && e.target !== e.currentTarget) return;
    document.getElementById('consultas-modal').innerHTML = '';
  }

  async function save(e) {
    e.preventDefault();
    const body = {
      pacienteId: parseInt(document.getElementById('con-paciente').value),
      medicoId: parseInt(document.getElementById('con-medico').value),
      dataHora: document.getElementById('con-data').value,
      observacoes: document.getElementById('con-obs').value
    };
    try {
      await Api.post('/api/consultas', body);
      closeForm();
      load();
    } catch (err) { alert(err.message); }
  }

  async function remove(id) {
    if (!confirm('Cancelar esta consulta?')) return;
    try { await Api.delete(`/api/consultas/${id}`); load(); }
    catch (err) { alert(err.message); }
  }

  function bindEvents() {
    document.addEventListener('submit', (e) => {
      if (e.target.id === 'consulta-form') save(e);
    });
  }

  return { page, load, showForm, closeForm, remove, bindEvents };
})();
