(() => {
  // Register routes
  Router.register('/login', () => { setTimeout(() => {}, 0); return Auth.loginPage(); });
  Router.register('/register', () => Auth.registerPage());
  Router.register('/medicos', () => { setTimeout(Medicos.load, 50); return Medicos.page(); });
  Router.register('/pacientes', () => { setTimeout(Pacientes.load, 50); return Pacientes.page(); });
  Router.register('/consultas', () => { setTimeout(Consultas.load, 50); return Consultas.page(); });

  // Bind module events
  Auth.bindEvents();
  Medicos.bindEvents();
  Pacientes.bindEvents();
  Consultas.bindEvents();

  // Start router
  Router.init();
})();
