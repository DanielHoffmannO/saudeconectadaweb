<?php
session_start();

function carregarUsuarios($arquivo) {
    if (!file_exists($arquivo)) return [];
    $conteudo = file_get_contents($arquivo);
    return json_decode($conteudo, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST['tipo'];
    $identificador = $_POST['identificador'];
    $senha = $_POST['senha'];

    if ($tipo === "medico") {
        $medicos = carregarUsuarios("../data/medicos.json");

        foreach ($medicos as $medico) {
            if ($medico["crm"] === $identificador && $medico["senha"] === $senha) {
                $_SESSION['usuario'] = $medico["nome"];
                $_SESSION['tipo'] = "medico";
                $_SESSION['id'] = $medico["id"];
                header("Location: index.php");
                exit();
            }
        }
    } elseif ($tipo === "paciente") {
        $pacientes = carregarUsuarios("../data/usuarios.json");

        foreach ($pacientes as $paciente) {
            if ($paciente["cpf"] === $identificador && $paciente["senha"] === $senha) {
                $_SESSION['usuario'] = $paciente["nome"];
                $_SESSION['tipo'] = "paciente";
                $_SESSION['cpf'] = $paciente["cpf"];
                header("Location: index.php");
                exit();
            }
        }
    }

    $erro = "CRM/CPF ou senha inválidos.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2 class="login-title">Login</h2>
        <?php if (isset($erro)) echo "<p class='login-error'>$erro</p>"; ?>

        <form method="POST" class="login-form" id="formLogin">
            <label for="tipoUsuario" class="login-label">Tipo de Usuário:</label>
            <select name="tipo" id="tipoUsuario" required class="login-select">
                <option value="medico">Médico</option>
                <option value="paciente">Paciente</option>
            </select>

            <label id="labelIdentificador" for="campoIdentificador" class="login-label">CRM:</label>
            <input type="text" name="identificador" id="campoIdentificador" required class="login-input" placeholder="Digite seu CRM">

            <label for="senha" class="login-label">Senha:</label>
            <input type="password" name="senha" id="senha" required class="login-input">

            <button type="submit" class="login-button">Entrar</button>

            <div class="cadastro-link">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </form>
    </div>

    <script src="../assets/js/login.js"></script>
</body>
</html>
