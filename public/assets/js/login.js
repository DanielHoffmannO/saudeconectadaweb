document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formEditarPerfil');

  form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      try {
          const response = await fetch('editar_perfil_backend.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(data)
          });

          const result = await response.json();

          if (result.success) {
              alert('Perfil atualizado com sucesso!');
              window.location.href = 'perfil.php';
          } else {
              alert('Erro ao atualizar perfil: ' + result.message);
          }

      } catch (error) {
          console.error('Erro na requisição:', error);
          alert('Erro na requisição.');
      }
  });
});
