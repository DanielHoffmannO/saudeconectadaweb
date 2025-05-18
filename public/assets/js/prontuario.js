document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.vital-card, .consulta-card, .exame-card, .anotacao').forEach(card => {
      card.addEventListener('mouseenter', () => {
          card.classList.add('hovered');
      });
      card.addEventListener('mouseleave', () => {
          card.classList.remove('hovered');
      });
  });

  document.querySelectorAll('.btn-exame').forEach(button => {
      button.addEventListener('click', (e) => {
          const exameCard = e.target.closest('.exame-card');
          const tipo = exameCard.querySelector('.exame-type').innerText;
          const resultado = exameCard.querySelector('.exame-result p').innerText;

          alert(`Laudo de ${tipo}:\n\n${resultado}`);
      });
  });

  const toggleSections = (selector, sectionName) => {
      const section = document.querySelector(selector);
      if (section) {
          const header = section.querySelector('h3');
          header.style.cursor = 'pointer';
          header.addEventListener('click', () => {
              section.classList.toggle('collapsed');
              console.log(`${sectionName} ${section.classList.contains('collapsed') ? 'escondido' : 'mostrado'}`);
          });
      }
  };

  toggleSections('.prontuario-section:nth-of-type(2)', 'Histórico Clínico');
  toggleSections('.prontuario-section:nth-of-type(4)', 'Anotações Médicas');
});
