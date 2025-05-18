document.getElementById("formCadastro").addEventListener("submit", async function(e) {
  e.preventDefault();

  const dados = Object.fromEntries(new FormData(this));
  
  const response = await fetch("salvar_usuario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(dados)
  });

  const resultado = await response.json();
  if (resultado.sucesso) {
      alert("Dados salvos com sucesso!");
      window.location.href = "?editar=1";
  } else {
      alert("Erro ao salvar dados.");
  }
});

document.getElementById("buscarCep").addEventListener("click", async function () {
  const cep = document.getElementById("cep").value.replace(/\D/g, '');
  if (cep.length !== 8) return alert("CEP inválido!");

  const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
  const dados = await response.json();

  if (dados.erro) return alert("CEP não encontrado!");

  document.getElementById("logradouro").value = dados.logradouro || "";
  document.getElementById("bairro").value = dados.bairro || "";
  document.getElementById("cidade").value = dados.localidade || "";
  document.getElementById("uf").value = dados.uf || "";
});
