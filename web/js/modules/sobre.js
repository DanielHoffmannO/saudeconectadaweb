const Sobre = (() => {
  function page() {
    return `
      <div class="page-header"><h1 class="page-title">Sobre Nós</h1></div>
      <div class="card">
        <h2>Transformando o acesso à saúde com tecnologia</h2>
        <p><strong>Saúde Conectada</strong> centraliza agendamentos de consultas, notificações, mensagens, exames e chamadas de vídeo, tornando o acesso mais rápido, fácil e eficiente para pacientes e profissionais da saúde.</p>
        <br>
        <p>Nossa plataforma foi criada para resolver as dificuldades de acesso a informações médicas essenciais. Com a <strong>Saúde Conectada</strong>, eliminamos a necessidade de deslocamentos, permitindo que você gerencie consultas, exames e atendimentos diretamente do seu dispositivo.</p>
        <br>
        <p>Nosso objetivo é proporcionar um controle mais eficiente da sua saúde, tornando a rotina de pacientes e profissionais mais simples através de uma solução digital integrada.</p>
      </div>
    `;
  }

  function load() {}

  return { page, load };
})();
