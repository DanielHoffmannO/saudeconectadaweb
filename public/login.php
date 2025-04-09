<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST['tipo'];
    $identificador = $_POST['identificador'];
    $senha = $_POST['senha'];

    $usuarios = [
        'medico' => ['123456' => 'senha123'], 
        'paciente' => ['11122233344' => 'senha456']
    ];

    if ((($tipo == "medico" && strlen($identificador) == 6) || 
         ($tipo == "paciente" && strlen($identificador) == 11)) 
         && isset($usuarios[$tipo][$identificador]) && $usuarios[$tipo][$identificador] == $senha) {
        
        $_SESSION['usuario'] = ucfirst($tipo) . " - " . $identificador;
        header("Location: index.php");
        exit();
    } else {
        $erro = "CRM/CPF ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2 class="login-title">Login</h2>
        <?php if (isset($erro)) echo "<p class='login-error'>$erro</p>"; ?>
        
        <form method="POST" class="login-form">
            <label for="tipoUsuario" class="login-label">Tipo de Usuário:</label>
            <select name="tipo" id="tipoUsuario" required class="login-select" onchange="trocarLabel()">
                <option value="medico">Médico</option>
                <option value="paciente">Paciente</option>
            </select>
            
            <label id="labelIdentificador" class="login-label">CRM:</label>
            <input type="text" name="identificador" id="campoIdentificador" placeholder="Digite seu CRM" required class="login-input">
            
            <label for="senha" class="login-label">Senha:</label>
            <input type="password" name="senha" id="senha" required class="login-input">
            
            <button type="submit" class="login-button">Entrar</button>
            
            <div class="cadastro-link">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </form>
    </div>

    <script>
        function trocarLabel() {
            let tipoUsuario = document.getElementById("tipoUsuario").value;
            let labelIdentificador = document.getElementById("labelIdentificador");
            let campoIdentificador = document.getElementById("campoIdentificador");

            if (tipoUsuario === "medico") {
                labelIdentificador.innerText = "CRM:";
                campoIdentificador.placeholder = "Digite seu CRM";
                campoIdentificador.maxLength = 6;
            } else {
                labelIdentificador.innerText = "CPF:";
                campoIdentificador.placeholder = "Digite seu CPF";
                campoIdentificador.maxLength = 11;
            }
        }
    </script>
</body>
</html>