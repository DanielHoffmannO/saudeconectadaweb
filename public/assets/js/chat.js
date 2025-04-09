document.addEventListener('DOMContentLoaded', function() {
    // Rolagem automática para a última mensagem
    const chatMessages = document.querySelector('.chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Envio de mensagens
    const sendBtn = document.querySelector('.send-btn');
    const messageInput = document.getElementById('mensagemInput');
    
    if (sendBtn && messageInput) {
        sendBtn.addEventListener('click', function() {
            sendMessage();
        });

        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Alternar entre médicos (já funciona via PHP, mas podemos melhorar)
    const chatContacts = document.querySelectorAll('.chat-contato');
    chatContacts.forEach(contact => {
        contact.addEventListener('click', function() {
            // Remove a classe 'active' de todos os contatos
            chatContacts.forEach(c => c.classList.remove('active'));
            // Adiciona apenas ao clicado
            this.classList.add('active');
        });
    });

    // Função para enviar mensagem (simulação)
    function sendMessage() {
        const messageText = messageInput.value.trim();
        if (messageText !== '') {
            // Aqui você pode adicionar AJAX para enviar para o servidor
            console.log('Mensagem enviada:', messageText);
            
            // Simulação: adiciona a mensagem ao chat
            const messagesContainer = document.querySelector('.chat-messages');
            if (messagesContainer) {
                const newMessage = document.createElement('div');
                newMessage.className = 'message voce';
                newMessage.innerHTML = `
                    <div class="message-content">
                        <p>${messageText}</p>
                        <span class="message-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    </div>
                `;
                messagesContainer.appendChild(newMessage);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
            
            // Limpa o input
            messageInput.value = '';
        }
    }

    // Botão de videochamada
    const videoCallBtn = document.querySelector('.fa-video');
    if (videoCallBtn) {
        videoCallBtn.closest('button').addEventListener('click', function(e) {
            e.preventDefault();
            const medicoId = this.closest('.chat-contato').getAttribute('href').split('=')[1];
            window.location.href = `videochamada.php?medico=${medicoId}`;
        });
    }
});