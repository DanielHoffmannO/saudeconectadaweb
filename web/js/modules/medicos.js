const Medicos = (() => {
  function page() {
    return `
      <div class="page-header">
        <h1 class="page-title">Médicos</h1>
        <button class="btn btn-primary" onclick="Medicos.showForm()">+ Novo Médico</button>
      </div>
      <div id="medicos-alert"></div>
      <div id="medicos-modal"></div>
      <div class="card">
        <div class="table-container">
          <table>
            <thead>
              <tr><th>Nome</th><th>CRM</th><th>Especialidade</th><th>Ações</th></tr>
            </thead>
            <tbody id="medicos-list"><tr><td colspan="4">Carregando...</td></tr></tbody>
          </table>
        </div>
      </div>
    `;
  }

  async function load() {
    try {
      const data = await Api.get('/api/medicos');
      const list = document.getElementById('medicos-list');
      if (!list) return;
      if (!data.length) { list.innerHTML = '<tr><td colspan="4">Nenhum médico cadastrado</td></tr>'; return; }
      list.innerHTML = data.map(m => `
        <tr>
          <td>${m.nome}</td>
          <td>${m.crm}</td>
          <td>${m.especialidade}</td>
          <td>
            <button class="btn btn-secondary btn-sm" onclick="Medicos.showForm(${JSON.stringify(m).replace(/"/g, '&quot;')})">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="Medicos.remove(${m.id})">Excluir</button>
          </td>
        </tr>
      `).join('');
    } catch (e) {
      const list = document.getElementById('medicos-list');
      if (list) list.innerHTML = `<tr><td colspan="4">${e.message}</td></tr>`;
    }
  }

  function showForm(medico) {
    const isEdit = !!medico;
    document.getElementById('medicos-modal').innerHTML = `
      <div class="modal-overlay" onclick="Medicos.closeForm(event)">
        <div class="modal" onclick="event.stopPropagation()">
          <h2 class="modal-title">${isEdit ? 'Editar' : 'Novo'} Médico</h2>
          <form id="medico-form">
            <div class="form-group"><label>Nome</label><input type="text" id="med-nome" required value="${isEdit ? medico.nome : ''}"></div>
            <div class="form-group"><label>CRM</label><input type="text" id="med-crm" required value="${isEdit ? medico.crm : ''}"></div>
            <div class="form-group"><label>Especialidade</label><input type="text" id="med-espec" required value="${isEdit ? medico.especialidade : ''}"></div>
            <input type="hidden" id="med-id" value="${isEdit ? medico.id : ''}">
            <div class="modal-actions">
              <button type="button" class="btn btn-secondary" onclick="Medicos.closeForm()">Cancelar</button>
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    `;
  }

  function closeForm(e) {
    if (e && e.target !== e.currentTarget) return;
    document.getElementById('medicos-modal').innerHTML = '';
  }

  async function save(e) {
    e.preventDefault();
    const id = document.getElementById('med-id').value;
    const body = {
      nome: document.getElementById('med-nome').value,
      crm: document.getElementById('med-crm').value,
      especialidade: document.getElementById('med-espec').value
    };
    try {
      if (id) await Api.put(`/api/medicos/${id}`, body);
      else await Api.post('/api/medicos', body);
      closeForm();
      load();
    } catch (err) {
      alert(err.message);
    }
  }

  async function remove(id) {
    if (!confirm('Confirma exclusão?')) return;
    try {
      await Api.delete(`/api/medicos/${id}`);
      load();
    } catch (err) { alert(err.message); }
  }

  function bindEvents() {
    document.addEventListener('submit', (e) => {
      if (e.target.id === 'medico-form') save(e);
    });
  }

  return { page, load, showForm, closeForm, remove, bindEvents };
})();
