document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modalExame");
  const modalTitle = document.getElementById("modalTitulo");
  const modalBody = document.getElementById("modalBody");
  const closeModal = document.querySelector(".close-modal");

  closeModal.addEventListener("click", function () {
      modal.style.display = "none";
      modalTitle.textContent = "";
      modalBody.innerHTML = "";
  });

  window.addEventListener("click", function (e) {
      if (e.target === modal) {
          modal.style.display = "none";
          modalTitle.textContent = "";
          modalBody.innerHTML = "";
      }
  });

  document.querySelectorAll(".btn-compartilhar").forEach(button => {
      button.addEventListener("click", function () {
          const exameId = this.getAttribute("data-exame");

          modalTitle.textContent = "Compartilhar Exame";
          modalBody.innerHTML = `
              <p>Você pode copiar o link do exame ou enviar por e-mail.</p>
              <input type="text" value="https://seusite.com/exames/compartilhar.php?id=${exameId}" readonly style="width: 100%; padding: 8px;" />
              <button onclick="navigator.clipboard.writeText('https://seusite.com/exames/compartilhar.php?id=${exameId}')">Copiar Link</button>
          `;
          modal.style.display = "block";
      });
  });

  document.querySelectorAll(".btn-remarcar").forEach(button => {
      button.addEventListener("click", function () {
          const exameId = this.getAttribute("data-exame");

          modalTitle.textContent = "Remarcar Exame";
          modalBody.innerHTML = `
              <p>Escolha uma nova data para o exame:</p>
              <input type="date" id="novaData" />
              <button onclick="remarcarExame(${exameId})">Confirmar</button>
          `;
          modal.style.display = "block";
      });
  });

  document.querySelectorAll(".btn-anexo").forEach(button => {
      button.addEventListener("click", function () {
          const exameId = this.getAttribute("data-exame");
          const arquivo = this.getAttribute("data-arquivo");

          modalTitle.textContent = "Visualizar Anexo";
          modalBody.innerHTML = `
              <p>Abrindo o anexo: <strong>${arquivo}</strong></p>
              <iframe src="/assets/${arquivo}" width="100%" height="500px" style="border: none;"></iframe>
          `;
          modal.style.display = "block";
      });
  });
});

function remarcarExame(id) {
  const novaData = document.getElementById("novaData").value;
  if (!novaData) {
      alert("Por favor, selecione uma nova data.");
      return;
  }

  alert(`Exame ${id} remarcado para ${novaData} (simulação).`);
  document.getElementById("modalExame").style.display = "none";
}
