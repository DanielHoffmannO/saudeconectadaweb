<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$exames = json_decode(file_get_contents(__DIR__ . '/../data/exames.json'), true);

$filtroStatus = $_GET['status'] ?? 'todos';

require_once __DIR__ . '/../includes/header.php';
?>

<main class="exames-container">
    <div class="exames-header">
        <h1>Meus Exames</h1>
        <div class="exames-acoes">
            <a href="#" class="btn-novo-exame">
                <i class="fas fa-plus"></i> Solicitar Novo Exame
            </a>
        </div>
    </div>
    
    <div class="exames-filtros">
        <a href="exames.php?status=todos" class="filtro <?= $filtroStatus == 'todos' ? 'active' : '' ?>">Todos</a>
        <a href="exames.php?status=disponivel" class="filtro <?= $filtroStatus == 'disponivel' ? 'active' : '' ?>">Disponíveis</a>
        <a href="exames.php?status=agendado" class="filtro <?= $filtroStatus == 'agendado' ? 'active' : '' ?>">Agendados</a>
        <a href="exames.php?status=pendente" class="filtro <?= $filtroStatus == 'pendente' ? 'active' : '' ?>">Pendentes</a>
    </div>
    
    <div class="exames-lista">
        <?php foreach ($exames as $exame): ?>
            <?php if ($filtroStatus == 'todos' || $exame['status'] == $filtroStatus): ?>
                <div class="exame-card <?= $exame['status'] ?>">
                    <div class="exame-info">
                        <div class="exame-tipo">
                            <i class="fas fa-<?= $exame['status'] == 'disponivel' ? 'file-medical' : ($exame['status'] == 'agendado' ? 'calendar-check' : 'clock') ?>"></i>
                            <h3><?= htmlspecialchars($exame['tipo']) ?></h3>
                        </div>
                        <div class="exame-detalhes">
                            <p><strong>Data:</strong> <?= htmlspecialchars($exame['data']) ?></p>
                            <p><strong>Laboratório:</strong> <?= htmlspecialchars($exame['laboratorio']) ?></p>
                            <p><strong>Médico solicitante:</strong> <?= htmlspecialchars($exame['medico']) ?></p>
                        </div>
                        <div class="exame-resultado">
                            <p><strong>Resultado:</strong> <?= htmlspecialchars($exame['resultado']) ?></p>
                        </div>
                    </div>
                    
                    <div class="exame-acoes">
                        <?php if (!empty($exame['assets'])): ?>
                            <div class="exame-assets">
                                <p><strong>assets:</strong></p>
                                <?php foreach ($exame['assets'] as $anexo): ?>
                                    <a href="#" class="btn-anexo" data-exame="<?= $exame['id'] ?>" data-arquivo="<?= htmlspecialchars($anexo) ?>">
                                        <i class="fas fa-file-pdf"></i> <?= htmlspecialchars($anexo) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="exame-botoes">
                            <?php if ($exame['status'] == 'disponivel'): ?>
                                <button class="btn-compartilhar" data-exame="<?= $exame['id'] ?>">
                                    <i class="fas fa-share-alt"></i> Compartilhar
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($exame['status'] == 'agendado'): ?>
                                <button class="btn-remarcar" data-exame="<?= $exame['id'] ?>">
                                    <i class="fas fa-calendar-alt"></i> Remarcar
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <?php
            $examesFiltrados = array_filter($exames, function ($exame) use ($filtroStatus) {
                return $filtroStatus == 'todos' || $exame['status'] == $filtroStatus;
            });

if (empty($examesFiltrados)):
    ?>
            <div class="exame-vazio">
                <i class="fas fa-microscope"></i>
                <p>Nenhum exame <?= $filtroStatus == 'todos' ? 'encontrado' : ($filtroStatus == 'disponivel' ? 'disponível' : ($filtroStatus == 'agendado' ? 'agendado' : 'pendente')) ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<div class="modal-exame" id="modalExame">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2 id="modalTitulo"></h2>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

<script src="/assets/js/exames.js"></script>
