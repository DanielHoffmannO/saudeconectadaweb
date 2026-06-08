const Pacientes = (() => {
  function page() {
    return `
      <div class="page-header">
        <h1 class="page-title">Pacientes</h1>
        <button class="btn btn-primary" onclick="Pacientes.showForm()">+ Novo Paciente</button>
      </div>
      <div id="pacientes-modal"></div>
      <div class="card">
        <div class="table-container">
          <table>
            <thead>
              <tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>Ações</th></tr>
            </thead>
            <tbody id="pacientes-list"><tr><td colspan="4">Carregando...</td></tr></tbody>
          </table>
        </div>
      </div>
    `;
  }

  async function load() {
    try {
      const data = await Api.get('/api/pacientes');
      const list = document.getElementById('pacientes-list');
      if (!list) return;
      if (!data.length) { list.innerHTML = '<tr><td colspan="4">Nenhum paciente cadastrado</td></tr>'; return; }
      list.innerHTML = data.map(p => `
        <tr>
          <td>${p.nome}</td>
          <td>${p.cpf}</td>
          <td>${p.telefone || '-'}</td>
          <td>
            <button class="btn btn-secondary btn-sm" onclick="Pacientes.showForm(${JSON.stringify(p).replace(/"/g, '&quot;')})">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="Pacientes.remove(${p.id})">Excluir</button>
          </td>
        </tr>
      `).join('');
    } catch (e) {
      const list = document.getElementById('pacientes-list');
      if (list) list.innerHTML = `<tr><td colspan="4">${e.message}</td></tr>`;
    }
  }

  function showForm(paciente) {
    const isEdit = !!paciente;
    document.getElementById('pacientes-modal').innerHTML = `
      <div class="modal-overlay" onclick="Pacientes.closeForm(event)">
        <div class="modal" onclick="event.stopPropagation()">
          <h2 class="modal-title">${isEdit ? 'Editar' : 'Novo'} Paciente</h2>
          <form id="paciente-form">
            <div class="form-group"><label>Nome</label><input type="text" id="pac-nome" required value="${isEdit ? paciente.nome : ''}"></div>
            <div class="form-group"><label>CPF</label><input type="text" id="pac-cpf" required value="${isEdit ? paciente.cpf : ''}"></div>
            <div class="form-group"><label>Telefone</label><input type="text" id="pac-tel" value="${isEdit ? (paciente.telefone || '') : ''}"></div>
            <input type="hidden" id="pac-id" value="${isEdit ? paciente.id : ''}">
            <div class="modal-actions">
              <button type="button" class="btn btn-secondary" onclick="Pacientes.closeForm()">Cancelar</button>
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    `;
  }

  function closeForm(e) {
    if (e && e.target !== e.currentTarget) return;
    document.getElementById('pacientes-modal').innerHTML = '';
  }

  async function save(e) {
    e.preventDefault();
    const id = document.getElementById('pac-id').value;
    const body = {
      nome: document.getElementById('pac-nome').value,
      cpf: document.getElementById('pac-cpf').value,
      telefone: document.getElementById('pac-tel').value
    };
    try {
      if (id) await Api.put(`/api/pacientes/${id}`, body);
      else await Api.post('/api/pacientes', body);
      closeForm();
      load();
    } catch (err) { alert(err.message); }
  }

  async function remove(id) {
    if (!confirm('Confirma exclusão?')) return;
    try { await Api.delete(`/api/pacientes/${id}`); load(); }
    catch (err) { alert(err.message); }
  }

  function bindEvents() {
    document.addEventListener('submit', (e) => {
      if (e.target.id === 'paciente-form') save(e);
    });
  }

  return { page, load, showForm, closeForm, remove, bindEvents };
})();
