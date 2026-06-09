const Perfil = (() => {
  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Meu Perfil</h1></div>
      <div class="card" id="perfil-content"><p>Carregando...</p></div>
    `;
  }

  async function load() {
    const content = document.getElementById('perfil-content');
    if (!content) return;
    try {
      const user = await Api.get('/api/perfil');
      content.innerHTML = `
        <form id="perfil-form">
          <div class="form-group"><label>Nome</label><input type="text" id="perfil-nome" value="${user.nome}" required></div>
          <div class="form-group"><label>Email</label><input type="email" id="perfil-email" value="${user.email}" required></div>
          <div class="form-group"><label>Tipo</label><input type="text" value="${user.role}" disabled></div>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
      `;
    } catch (e) { content.innerHTML = `<p>${e.message}</p>`; }
  }

  async function save(e) {
    e.preventDefault();
    const body = {
      nome: document.getElementById('perfil-nome').value,
      email: document.getElementById('perfil-email').value
    };
    try { await Api.put('/api/perfil', body); alert('Perfil atualizado!'); }
    catch (err) { alert(err.message); }
  }

  function bindEvents() {
    document.addEventListener('submit', (e) => { if (e.target.id === 'perfil-form') save(e); });
  }

  return { page, load, bindEvents };
})();
