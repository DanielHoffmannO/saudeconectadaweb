(() => {
  // Register routes
  Router.register('/login', () => { setTimeout(() => {}, 0); return Auth.loginPage(); });
  Router.register('/register', () => Auth.registerPage());
  Router.register('/medicos', () => { setTimeout(Medicos.load, 50); return Medicos.page(); });
  Router.register('/pacientes', () => { setTimeout(Pacientes.load, 50); return Pacientes.page(); });
  Router.register('/consultas', () => { setTimeout(Consultas.load, 50); return Consultas.page(); });
  Router.register('/exames', () => { setTimeout(Exames.load, 50); return Exames.page(); });
  Router.register('/prontuario', () => { setTimeout(Prontuario.load, 50); return Prontuario.page(); });
  Router.register('/chat', () => { setTimeout(Chat.load, 50); return Chat.page(); });
  Router.register('/videochat', () => { setTimeout(VideoChat.load, 50); return VideoChat.page(); });
  Router.register('/notificacoes', () => { setTimeout(Notificacoes.load, 50); return Notificacoes.page(); });
  Router.register('/perfil', () => { setTimeout(Perfil.load, 50); return Perfil.page(); });
  Router.register('/sobre', () => { setTimeout(Sobre.load, 50); return Sobre.page(); });

  // Bind module events
  Auth.bindEvents();
  Medicos.bindEvents();
  Pacientes.bindEvents();
  Consultas.bindEvents();
  Exames.bindEvents();
  Perfil.bindEvents();

  // Start router
  Router.init();
})();
