const Layout = (() => {
  function render(authenticated) {
    const app = document.getElementById('app');

    if (!authenticated) {
      app.innerHTML = `<main class="main" id="main-content"></main>`;
      return;
    }

    const currentPath = Router.getCurrentPath();

    app.innerHTML = `
      <header class="header">
        <div class="header-logo">${CONFIG.APP_NAME}</div>
        <nav class="header-nav">
          <a href="#/consultas" class="${currentPath === '/consultas' ? 'active' : ''}">Consultas</a>
          <a href="#/medicos" class="${currentPath === '/medicos' ? 'active' : ''}">Médicos</a>
          <a href="#/pacientes" class="${currentPath === '/pacientes' ? 'active' : ''}">Pacientes</a>
          <a href="#/exames" class="${currentPath === '/exames' ? 'active' : ''}">Exames</a>
          <a href="#/prontuario" class="${currentPath === '/prontuario' ? 'active' : ''}">Prontuário</a>
          <a href="#/chat" class="${currentPath === '/chat' ? 'active' : ''}">Chat</a>
          <a href="#/videochat" class="${currentPath === '/videochat' ? 'active' : ''}">Videochamada</a>
          <a href="#/notificacoes" class="${currentPath === '/notificacoes' ? 'active' : ''}">Notificações</a>
          <a href="#/perfil" class="${currentPath === '/perfil' ? 'active' : ''}">Perfil</a>
          <a href="#/sobre" class="${currentPath === '/sobre' ? 'active' : ''}">Sobre</a>
        </nav>
        <button class="btn-logout" onclick="Auth.logout()">Sair</button>
      </header>
      <main class="main" id="main-content"></main>
      <footer class="footer">© 2026 ${CONFIG.APP_NAME} — Sistema de Gestão de Saúde</footer>
    `;
  }

  return { render };
})();
