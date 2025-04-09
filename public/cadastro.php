<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Editar Perfil - Saúde Conectada";

$dadosUsuario = [
    'nome' => 'João da Silva',
    'cpf' => '123.456.789-00',
    'nascimento' => '1985-05-15',
    'email' => 'joao@email.com',
    'celular' => '(11) 98765-4321',
    'cep' => '01001-000',
    'logradouro' => 'Praça da Sé',
    'numero' => '100',
    'complemento' => 'Apto 101',
    'bairro' => 'Sé',
    'cidade' => 'São Paulo',
    'uf' => 'SP'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['atualizacao_sucesso'] = true;
    header('Location: perfil.php');
    exit();
}
require_once __DIR__ . '/../includes/header.php';
?>

<body>
    
    <main class="cadastro-container">
        <div class="cadastro-card">
            <h1>Editar Perfil</h1>
            <p>Atualize seus dados abaixo.</p>
            
            <form id="formEditarPerfil" method="POST" action="editar_perfil.php">
                <div class="form-group">
                    <label for="nome">Nome Completo*</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?php echo htmlspecialchars($dadosUsuario['nome']); ?>">
                    <span class="error-message" id="nome-error"></span>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cpf">CPF*</label>
                        <input type="text" id="cpf" name="cpf" required readonly
                               value="<?php echo htmlspecialchars($dadosUsuario['cpf']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento*</label>
                        <input type="date" id="nascimento" name="nascimento" required
                               value="<?php echo htmlspecialchars($dadosUsuario['nascimento']); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" id="email" name="email" required
                           value="<?php echo htmlspecialchars($dadosUsuario['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="celular">Celular*</label>
                    <input type="tel" id="celular" name="celular" required
                           value="<?php echo htmlspecialchars($dadosUsuario['celular']); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cep">CEP*</label>
                        <input type="text" id="cep" name="cep" required
                               value="<?php echo htmlspecialchars($dadosUsuario['cep']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <button type="button" id="buscarCep" class="btn-buscar-cep">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="logradouro">Logradouro</label>
                    <input type="text" id="logradouro" name="logradouro"
                           value="<?php echo htmlspecialchars($dadosUsuario['logradouro']); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="numero">Número*</label>
                        <input type="text" id="numero" name="numero" required
                               value="<?php echo htmlspecialchars($dadosUsuario['numero']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento"
                               value="<?php echo htmlspecialchars($dadosUsuario['complemento']); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro"
                               value="<?php echo htmlspecialchars($dadosUsuario['bairro']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade"
                               value="<?php echo htmlspecialchars($dadosUsuario['cidade']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="uf">UF</label>
                        <input type="text" id="uf" name="uf"
                               value="<?php echo htmlspecialchars($dadosUsuario['uf']); ?>">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-cadastrar">Salvar Alterações</button>
                    <a href="perfil.php" class="btn-cadastrar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

    <?php 
require_once __DIR__ . '/../includes/footer.php';
?>

<script src="../js/editar_perfil.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>