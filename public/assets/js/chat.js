class ChatApp {
    constructor() {
        this.initElements();
        this.initEvents();
        this.scrollToBottom();
    }

    initElements() {
        this.chatMessages = document.querySelector('.chat-messages');
        this.sendBtn = document.querySelector('.send-btn');
        this.messageInput = document.getElementById('mensagemInput');
        this.chatContacts = document.querySelectorAll('.chat-contato');
        this.videoCallBtns = document.querySelectorAll('.video-call-btn');
    }

    initEvents() {
        if (this.sendBtn && this.messageInput) {
            this.sendBtn.addEventListener('click', () => this.sendMessage());
            this.messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.sendMessage();
            });
        }

        this.videoCallBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const medicoId = btn.getAttribute('data-medico-id');
                this.startVideoCall(medicoId);
            });
        });

        this.chatContacts.forEach(contact => {
            contact.addEventListener('click', () => {
                this.chatContacts.forEach(c => c.classList.remove('active'));
                contact.classList.add('active');
            });
        });
    }

    scrollToBottom() {
        if (this.chatMessages) {
            this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
        }
    }

    sendMessage() {
        const messageText = this.messageInput.value.trim();
        if (messageText === '') return;

        if (this.chatMessages) {
            const newMessage = document.createElement('div');
            newMessage.className = 'message voce';
            newMessage.innerHTML = `
                <div class="message-content">
                    <p>${messageText}</p>
                    <span class="message-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                </div>
            `;
            this.chatMessages.appendChild(newMessage);
            this.scrollToBottom();
        }
        
        this.messageInput.value = '';
    }

    startVideoCall(medicoId) {
        if (!medicoId) return;
        
        const isOnline = document.querySelector(`.chat-contato[data-medico-id="${medicoId}"] .online`);
        if (!isOnline) {
            alert('O médico está offline no momento. Por favor, tente novamente mais tarde.');
            return;
        }
        
        window.location.href = `videochat.php?medico=${medicoId}`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ChatApp();
});