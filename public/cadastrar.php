<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cadastro_sucesso'] = true;
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Saúde Conectada</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="cadastro-container">
        <div class="cadastro-card">
            <h1>Crie sua conta</h1>
            <p>Preencha os campos abaixo para se cadastrar em nossa plataforma.</p>
            
            <form id="formCadastro" method="POST" action="cadastro.php">
                <div class="form-group">
                    <label for="nome">Nome Completo*</label>
                    <input type="text" id="nome" name="nome" required placeholder="Digite seu nome completo">
                    <span class="error-message" id="nome-error"></span>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cpf">CPF*</label>
                        <input type="text" id="cpf" name="cpf" required placeholder="000.000.000-00">
                        <span class="error-message" id="cpf-error"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento*</label>
                        <input type="date" id="nascimento" name="nascimento" required>
                        <span class="error-message" id="nascimento-error"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" id="email" name="email" required placeholder="seu@email.com">
                    <span class="error-message" id="email-error"></span>
                </div>
                
                <div class="form-group">
                    <label for="celular">Celular*</label>
                    <input type="tel" id="celular" name="celular" required placeholder="(00) 00000-0000">
                    <span class="error-message" id="celular-error"></span>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="senha">Senha*</label>
                        <div class="password-input">
                            <input type="password" id="senha" name="senha" required placeholder="Mínimo 8 caracteres">
                            <i class="fas fa-eye" id="toggleSenha"></i>
                        </div>
                        <span class="error-message" id="senha-error"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Senha*</label>
                        <div class="password-input">
                            <input type="password" id="confirmar_senha" name="confirmar_senha" required placeholder="Confirme sua senha">
                            <i class="fas fa-eye" id="toggleConfirmarSenha"></i>
                        </div>
                        <span class="error-message" id="confirmar_senha-error"></span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cep">CEP*</label>
                        <input type="text" id="cep" name="cep" required placeholder="00000-000">
                        <span class="error-message" id="cep-error"></span>
                    </div>
                    
                    <div class="form-group">
                        <button type="button" id="buscarCep" class="btn-buscar-cep">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="logradouro">Logradouro</label>
                    <input type="text" id="logradouro" name="logradouro" readonly>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="numero">Número*</label>
                        <input type="text" id="numero" name="numero" required placeholder="Nº">
                    </div>
                    
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento" placeholder="Apto, bloco, etc.">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="uf">UF</label>
                        <input type="text" id="uf" name="uf" readonly>
                    </div>
                </div>
                

                <button type="submit" class="btn-cadastrar">Cadastrar</button>
                
                <div class="login-link">
                    Já tem uma conta? <a href="login.php">Faça login</a>
                </div>
            </form>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/cadastro.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html> 

