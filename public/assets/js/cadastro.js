document.addEventListener('DOMContentLoaded', function() {
    // Verifica se jQuery e jQuery Mask estão disponíveis
    if (typeof $ !== 'undefined' && $.fn.mask) {
        // Máscaras de entrada
        $('#cpf').mask('000.000.000-00');
        $('#celular').mask('(00) 00000-0000');
        $('#cep').mask('00000-000');
    } else {
        console.warn('jQuery Mask não está disponível');
    }

    // Mostrar/ocultar senha
    document.getElementById('toggleSenha').addEventListener('click', function() {
        togglePasswordVisibility('senha', this);
    });

    document.getElementById('toggleConfirmarSenha').addEventListener('click', function() {
        togglePasswordVisibility('confirmar_senha', this); // Corrigido o ID
    });

    function togglePasswordVisibility(fieldId, icon) {
        const field = document.getElementById(fieldId);
        if (!field) {
            console.error('Campo não encontrado:', fieldId);
            return;
        }
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Elementos do formulário para busca de CEP
    const cepInput = document.getElementById('cep');
    const buscarCepBtn = document.getElementById('buscarCep');
    const logradouroInput = document.getElementById('logradouro');
    const bairroInput = document.getElementById('bairro');
    const cidadeInput = document.getElementById('cidade');
    const ufInput = document.getElementById('uf');
    
    // Buscar CEP
    if (buscarCepBtn) {
        buscarCepBtn.addEventListener('click', buscarCep);
    }
    if (cepInput) {
        cepInput.addEventListener('blur', buscarCep);
    }
    
    async function buscarCep() {
        const cep = cepInput.value.replace(/\D/g, '');
        
        if (cep.length !== 8) {
            alert('CEP inválido. Digite um CEP com 8 dígitos.');
            return;
        }
        
        try {
            limparCamposEndereco();
            
            const response = await fetch('./data/cep.json');
            const ceps = await response.json();
            
            if (ceps[cep]) {
                const endereco = ceps[cep];
                logradouroInput.value = endereco.logradouro;
                bairroInput.value = endereco.bairro;
                cidadeInput.value = endereco.cidade;
                ufInput.value = endereco.uf;
            } else {
                await buscarViaCEP(cep);
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
            await buscarViaCEP(cep);
        }
    }
    
    async function buscarViaCEP(cep) {
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();
            
            if (data.erro) {
                alert('CEP não encontrado. Por favor, verifique o número digitado.');
                return;
            }
            
            logradouroInput.value = data.logradouro || '';
            bairroInput.value = data.bairro || '';
            cidadeInput.value = data.localidade || '';
            ufInput.value = data.uf || '';
            
        } catch (error) {
            console.error('Erro ao buscar CEP na API ViaCEP:', error);
            alert('Não foi possível buscar o CEP. Por favor, preencha os campos manualmente.');
        }
    }
    
    function limparCamposEndereco() {
        logradouroInput.value = '';
        bairroInput.value = '';
        cidadeInput.value = '';
        ufInput.value = '';
    }

    // Validação do formulário
    const formCadastro = document.getElementById('formCadastro');
    if (formCadastro) {
        formCadastro.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validarFormulario()) {
                this.submit();
            }
        });
    }

    function validarFormulario() {
        let isValid = true;
        
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmar_senha').value;
        
        if (senha !== confirmarSenha) {
            document.getElementById('confirmar_senha-error').textContent = 'As senhas não coincidem';
            isValid = false;
        } else {
            document.getElementById('confirmar_senha-error').textContent = '';
        }
        
        if (senha.length < 8) {
            document.getElementById('senha-error').textContent = 'A senha deve ter pelo menos 8 caracteres';
            isValid = false;
        } else {
            document.getElementById('senha-error').textContent = '';
        }
        
        const cpf = document.getElementById('cpf').value.replace(/\D/g, '');
        if (cpf.length !== 11) {
            document.getElementById('cpf-error').textContent = 'CPF inválido';
            isValid = false;
        } else {
            document.getElementById('cpf-error').textContent = '';
        }
        
        return isValid;
    }
});