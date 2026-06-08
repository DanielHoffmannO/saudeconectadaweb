const Auth = (() => {
  function loginPage() {
    return `
      <div class="auth-container">
        <div class="card auth-card">
          <h1 class="auth-title">Login</h1>
          <p class="auth-subtitle">Acesse o sistema de gestão de saúde</p>
          <div id="auth-alert"></div>
          <form id="login-form">
            <div class="form-group">
              <label>Email</label>
              <input type="email" id="login-email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
              <label>Senha</label>
              <input type="password" id="login-password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
          </form>
          <p class="toggle-link" onclick="Router.navigate('/register')">Não tem conta? Cadastre-se</p>
        </div>
      </div>
    `;
  }

  function registerPage() {
    return `
      <div class="auth-container">
        <div class="card auth-card">
          <h1 class="auth-title">Cadastro</h1>
          <p class="auth-subtitle">Crie sua conta no sistema</p>
          <div id="auth-alert"></div>
          <form id="register-form">
            <div class="form-group">
              <label>Nome</label>
              <input type="text" id="reg-name" required placeholder="Seu nome completo">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" id="reg-email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
              <label>Senha</label>
              <input type="password" id="reg-password" required placeholder="Mínimo 6 caracteres">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
          </form>
          <p class="toggle-link" onclick="Router.navigate('/login')">Já tem conta? Faça login</p>
        </div>
      </div>
    `;
  }

  function bindEvents() {
    document.addEventListener('submit', async (e) => {
      if (e.target.id === 'login-form') {
        e.preventDefault();
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        try {
          const data = await Api.post('/api/auth/login', { email, password });
          localStorage.setItem(CONFIG.TOKEN_KEY, data.token);
          Router.navigate('/consultas');
        } catch (err) {
          showAlert(err.message);
        }
      }

      if (e.target.id === 'register-form') {
        e.preventDefault();
        const nome = document.getElementById('reg-name').value;
        const email = document.getElementById('reg-email').value;
        const password = document.getElementById('reg-password').value;
        try {
          await Api.post('/api/auth/register', { nome, email, password });
          Router.navigate('/login');
        } catch (err) {
          showAlert(err.message);
        }
      }
    });
  }

  function showAlert(msg) {
    const el = document.getElementById('auth-alert');
    if (el) el.innerHTML = `<div class="alert alert-error">${msg}</div>`;
  }

  function logout() {
    localStorage.removeItem(CONFIG.TOKEN_KEY);
    Router.navigate('/login');
  }

  return { loginPage, registerPage, bindEvents, logout };
})();
