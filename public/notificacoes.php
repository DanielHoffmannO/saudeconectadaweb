<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$notificacoes = [
    'todas' => [
        [
            'id' => 1,
            'tipo' => 'consulta',
            'titulo' => 'Nova consulta agendada',
            'mensagem' => 'Sua consulta com Dr. Carlos foi marcada para 20/05 às 14:30',
            'hora' => 'há 5 minutos',
            'lida' => false,
            'icone' => 'calendar-check'
        ],
        [
            'id' => 2,
            'tipo' => 'exame',
            'titulo' => 'Resultado de exames disponível',
            'mensagem' => 'Seus exames de sangue já estão disponíveis no portal',
            'hora' => 'há 1 hora',
            'lida' => true,
            'icone' => 'flask'
        ],
        [
            'id' => 3,
            'tipo' => 'mensagem',
            'titulo' => 'Nova mensagem do Dr. Silva',
            'mensagem' => 'Você tem uma nova mensagem na conversa com Dr. Silva',
            'hora' => 'há 3 horas',
            'lida' => false,
            'icone' => 'comment-medical'
        ]
    ],
    'nao-lidas' => [
        [
            'id' => 1,
            'tipo' => 'consulta',
            'titulo' => 'Nova consulta agendada',
            'mensagem' => 'Sua consulta com Dr. Carlos foi marcada para 20/05 às 14:30',
            'hora' => 'há 5 minutos',
            'lida' => false,
            'icone' => 'calendar-check'
        ],
        [
            'id' => 3,
            'tipo' => 'mensagem',
            'titulo' => 'Nova mensagem do Dr. Silva',
            'mensagem' => 'Você tem uma nova mensagem na conversa com Dr. Silva',
            'hora' => 'há 3 horas',
            'lida' => false,
            'icone' => 'comment-medical'
        ]
    ]
];

$abaAtiva = isset($_GET['aba']) && in_array($_GET['aba'], ['todas', 'nao-lidas']) ? $_GET['aba'] : 'todas';

$pageTitle = "Notificações - Saúde Conectada";
$additionalCSS = [
    '/assets/css/notificacoes.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

require_once __DIR__ . '/../includes/header.php';
?>

<main class="notificacoes-container">
    <div class="notificacoes-header">
        <h1>Notificações</h1>
        <div class="notificacoes-acoes">
            <button class="btn-marcar-todas">
                <i class="fas fa-check-double"></i> Marcar todas como lidas
            </button>
        </div>
    </div>
    
    <div class="notificacoes-abas">
        <a href="notificacoes.php?aba=todas" class="aba <?= $abaAtiva == 'todas' ? 'active' : '' ?>">
            Todas <span class="contador"><?= count($notificacoes['todas']) ?></span>
        </a>
        <a href="notificacoes.php?aba=nao-lidas" class="aba <?= $abaAtiva == 'nao-lidas' ? 'active' : '' ?>">
            Não lidas <span class="contador"><?= count($notificacoes['nao-lidas']) ?></span>
        </a>
    </div>
    
    <div class="notificacoes-lista">
        <?php if (empty($notificacoes[$abaAtiva])): ?>
            <div class="notificacao-vazia">
                <i class="far fa-bell-slash"></i>
                <p>Nenhuma notificação <?= $abaAtiva == 'nao-lidas' ? 'não lida' : 'encontrada' ?></p>
            </div>
        <?php else: ?>
            <?php foreach ($notificacoes[$abaAtiva] as $notificacao): ?>
                <div class="notificacao <?= !$notificacao['lida'] ? 'nao-lida' : '' ?>" data-id="<?= $notificacao['id'] ?>">
                    <div class="notificacao-icone">
                        <i class="fas fa-<?= $notificacao['icone'] ?>"></i>
                    </div>
                    <div class="notificacao-conteudo">
                        <h3><?= htmlspecialchars($notificacao['titulo']) ?></h3>
                        <p><?= htmlspecialchars($notificacao['mensagem']) ?></p>
                        <span class="notificacao-hora"><?= $notificacao['hora'] ?></span>
                    </div>
                    <div class="notificacao-acoes">
                        <button class="btn-marcar-lida" title="Marcar como lida">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn-excluir" title="Excluir notificação">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

<script src="/assets/js/notificacoes.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-marcar-lida').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificacao = this.closest('.notificacao');
            notificacao.classList.remove('nao-lida');
        });
    });
    
    document.querySelectorAll('.btn-excluir').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Deseja realmente excluir esta notificação?')) {
                const notificacao = this.closest('.notificacao');
                notificacao.style.opacity = '0';
                setTimeout(() => notificacao.remove(), 300);
            }
        });
    });
    
    document.querySelector('.btn-marcar-todas').addEventListener('click', function() {
        document.querySelectorAll('.notificacao.nao-lida').forEach(notificacao => {
            notificacao.classList.remove('nao-lida');
        });
    });
});
</script>