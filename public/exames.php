<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$exames = [
    [
        'id' => 1,
        'tipo' => 'Hemograma Completo',
        'data' => '15/05/2023',
        'status' => 'disponivel',
        'laboratorio' => 'LabMed Saúde',
        'medico' => 'Dra. Carla Silva',
        'resultado' => 'Todos os parâmetros dentro dos limites normais',
        'anexos' => ['hemograma_20230515.pdf']
    ],
    [
        'id' => 2,
        'tipo' => 'Glicemia em Jejum',
        'data' => '10/05/2023',
        'status' => 'disponivel',
        'laboratorio' => 'Diagnóstico Preciso',
        'medico' => 'Dr. Marcos Lima',
        'resultado' => '92 mg/dL (Valor de referência: 70-99 mg/dL)',
        'anexos' => ['glicemia_20230510.pdf']
    ],
    [
        'id' => 3,
        'tipo' => 'Colesterol Total',
        'data' => '10/05/2023',
        'status' => 'disponivel',
        'laboratorio' => 'Diagnóstico Preciso',
        'medico' => 'Dr. Marcos Lima',
        'resultado' => '185 mg/dL (Valor desejável: < 200 mg/dL)',
        'anexos' => ['colesterol_20230510.pdf']
    ],
    [
        'id' => 4,
        'tipo' => 'Ultrassom Abdominal',
        'data' => '05/05/2023',
        'status' => 'disponivel',
        'laboratorio' => 'Imagem Diagnóstica',
        'medico' => 'Dr. André Rocha',
        'resultado' => 'Exame sem alterações significativas',
        'anexos' => ['usg_abdominal_20230505.pdf', 'laudo_usg_20230505.pdf']
    ],
    [
        'id' => 5,
        'tipo' => 'COVID-19 RT-PCR',
        'data' => '20/04/2023',
        'status' => 'disponivel',
        'laboratorio' => 'LabVirus',
        'medico' => 'Dra. Carla Silva',
        'resultado' => 'Negativo para SARS-CoV-2',
        'anexos' => ['covid_pcr_20230420.pdf']
    ],
    [
        'id' => 6,
        'tipo' => 'Ressonância Magnética - Joelho',
        'data' => 'Agendado para 25/05/2023',
        'status' => 'agendado',
        'laboratorio' => 'Imagem Avançada',
        'medico' => 'Dr. Marcos Lima',
        'resultado' => 'Aguardando realização do exame',
        'anexos' => []
    ],
    [
        'id' => 7,
        'tipo' => 'TSH e T4 Livre',
        'data' => 'Aguardando coleta',
        'status' => 'pendente',
        'laboratorio' => 'LabMed Saúde',
        'medico' => 'Dra. Carla Silva',
        'resultado' => 'Aguardando realização do exame',
        'anexos' => []
    ]
];
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
                        <?php if (!empty($exame['anexos'])): ?>
                            <div class="exame-anexos">
                                <p><strong>Anexos:</strong></p>
                                <?php foreach ($exame['anexos'] as $anexo): ?>
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
            $examesFiltrados = array_filter($exames, function($exame) use ($filtroStatus) {
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
// Inclui o rodapé
require_once __DIR__ . '/../includes/footer.php';
?>

<script src="/assets/js/exames.js"></script>