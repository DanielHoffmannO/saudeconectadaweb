<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarios = json_decode(file_get_contents(__DIR__ . '/../data/usuarios.json'), true);
$cpf = $_SESSION['usuario'];

foreach ($usuarios as $usuario) {
    if ($usuario['cpf'] === $cpf) {
        break;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="cadastro-container">
    <div class="cadastro-card">
        <h1>Editar Perfil</h1>
        <p>Atualize seus dados abaixo.</p>

        <form id="formEditarPerfil">
            <input type="hidden" id="cpf" name="cpf" value="<?= htmlspecialchars($usuario['cpf']) ?>">

            <div class="form-group">
                <label for="nome">Nome Completo*</label>
                <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($usuario['nome']) ?>">
            </div>

            <div class="form-group">
                <label for="nascimento">Data de Nascimento*</label>
                <input type="date" id="nascimento" name="nascimento" required value="<?= htmlspecialchars($usuario['nascimento']) ?>">
            </div>

            <div class="form-group">
                <label for="email">E-mail*</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($usuario['email']) ?>">
            </div>

            <div class="form-group">
                <label for="celular">Celular*</label>
                <input type="tel" id="celular" name="celular" required value="<?= htmlspecialchars($usuario['celular']) ?>">
            </div>

            <!-- Endereço -->
            <div class="form-group">
                <label for="cep">CEP*</label>
                <input type="text" id="cep" name="cep" required value="<?= htmlspecialchars($usuario['cep']) ?>">
            </div>

            <div class="form-group">
                <label for="logradouro">Logradouro</label>
                <input type="text" id="logradouro" name="logradouro" value="<?= htmlspecialchars($usuario['logradouro']) ?>">
            </div>

            <div class="form-group">
                <label for="numero">Número*</label>
                <input type="text" id="numero" name="numero" required value="<?= htmlspecialchars($usuario['numero']) ?>">
            </div>

            <div class="form-group">
                <label for="complemento">Complemento</label>
                <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($usuario['complemento']) ?>">
            </div>

            <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($usuario['bairro']) ?>">
            </div>

            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($usuario['cidade']) ?>">
            </div>

            <div class="form-group">
                <label for="uf">UF</label>
                <input type="text" id="uf" name="uf" value="<?= htmlspecialchars($usuario['uf']) ?>">
            </div>

            <div class="form-actions">
                <button type="submit">Salvar Alterações</button>
                <a href="perfil.php">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<script src="../js/login.js"></script>
