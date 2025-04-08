<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /saudeconectada/pages/login.php");

    exit();
}

$pageTitle = "Prontuário Médico - Saúde Conectada";

$paciente = [
    'nome' => 'Carlos Alberto Mendonça',
    'cpf' => '987.654.321-00',
    'nascimento' => '1978-11-23',
    'sexo' => 'Masculino',
    'tipo_sanguineo' => 'A+',
    'altura' => '1,75m',
    'peso' => '78kg',
    'alergias' => 'Penicilina, Dipirona'
];

$historico = [
    [
        'data' => '2023-05-15',
        'medico' => 'Dra. Ana Claudia Souza',
        'especialidade' => 'Cardiologia',
        'diagnostico' => 'Hipertensão arterial estágio 1',
        'prescricao' => 'Losartana 50mg 1x/dia, Medição diária de PA'
    ],
    [
        'data' => '2023-03-10',
        'medico' => 'Dr. Marco Antônio Silva',
        'especialidade' => 'Ortopedia',
        'diagnostico' => 'Lombalgia mecânica',
        'prescricao' => 'Fisioterapia 2x/semana, Dipirona SOS'
    ]
];

$exames = [
    [
        'tipo' => 'Hemograma completo',
        'data' => '2023-05-10',
        'resultado' => 'Dentro dos parâmetros normais'
    ],
    [
        'tipo' => 'Eletrocardiograma',
        'data' => '2023-05-12',
        'resultado' => 'Ritmo sinusal, sem alterações isquêmicas'
    ]
];

require_once __DIR__ . '/../includes/header.php';
?>

<body class="prontuario-page">
    <main class="prontuario-container">
        <div class="prontuario-header">
            <h1>Prontuário Médico</h1>
            <div class="paciente-info">
                <h2><?php echo htmlspecialchars($paciente['nome']); ?></h2>
                <div class="dados-vitais">
                    <span><strong>Idade:</strong> 45 anos</span>
                    <span><strong>Sexo:</strong> <?php echo htmlspecialchars($paciente['sexo']); ?></span>
                    <span><strong>Tipo Sanguíneo:</strong> <?php echo htmlspecialchars($paciente['tipo_sanguineo']); ?></span>
                    <span><strong>Altura/Peso:</strong> <?php echo htmlspecialchars($paciente['altura']); ?> / <?php echo htmlspecialchars($paciente['peso']); ?></span>
                </div>
            </div>
        </div>

        <section class="prontuario-section">
            <h3><i class="fas fa-heartbeat"></i> Informações Vitais</h3>
            <div class="vital-cards">
                <div class="vital-card">
                    <h4>Última Aferição</h4>
                    <div class="vital-values">
                        <span><strong>PA:</strong> 128/82 mmHg</span>
                        <span><strong>FC:</strong> 72 bpm</span>
                        <span><strong>Sat.O2:</strong> 98%</span>
                        <span><strong>Temp:</strong> 36,5°C</span>
                    </div>
                    <span class="vital-date">15/05/2023 14:30</span>
                </div>
                
                <div class="vital-card">
                    <h4>Alergias</h4>
                    <p><?php echo htmlspecialchars($paciente['alergias']); ?></p>
                </div>
                
                <div class="vital-card">
                    <h4>Medicações Atuais</h4>
                    <ul>
                        <li>Losartana 50mg - 1x/dia (desde 05/2023)</li>
                        <li>Omeprazol 20mg - 1x/dia (desde 01/2023)</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="prontuario-section">
            <h3><i class="fas fa-file-medical"></i> Histórico Clínico</h3>
            <div class="historico-list">
                <?php foreach ($historico as $consulta): ?>
                <div class="consulta-card">
                    <div class="consulta-header">
                        <span class="consulta-date"><?php echo date('d/m/Y', strtotime($consulta['data'])); ?></span>
                        <span class="consulta-medico"><?php echo htmlspecialchars($consulta['medico']); ?> - <?php echo htmlspecialchars($consulta['especialidade']); ?></span>
                    </div>
                    <div class="consulta-body">
                        <p><strong>Diagnóstico:</strong> <?php echo htmlspecialchars($consulta['diagnostico']); ?></p>
                        <p><strong>Prescrição:</strong> <?php echo htmlspecialchars($consulta['prescricao']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="prontuario-section">
            <h3><i class="fas fa-flask"></i> Exames Recentes</h3>
            <div class="exames-list">
                <?php foreach ($exames as $exame): ?>
                <div class="exame-card">
                    <div class="exame-header">
                        <span class="exame-type"><?php echo htmlspecialchars($exame['tipo']); ?></span>
                        <span class="exame-date"><?php echo date('d/m/Y', strtotime($exame['data'])); ?></span>
                    </div>
                    <div class="exame-result">
                        <p><strong>Resultado:</strong> <?php echo htmlspecialchars($exame['resultado']); ?></p>
                        <button class="btn-exame">Visualizar Laudo</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="prontuario-section">
            <h3><i class="fas fa-comment-medical"></i> Anotações Médicas</h3>
            <div class="anotacoes-box">
                <div class="anotacao">
                    <div class="anotacao-header">
                        <span class="anotacao-medico">Dr. Rodrigo Lima - Clínico Geral</span>
                        <span class="anotacao-date">10/04/2023</span>
                    </div>
                    <p>Paciente relata melhora da lombalgia com fisioterapia. Manter acompanhamento a cada 15 dias.</p>
                </div>
                
                <div class="anotacao">
                    <div class="anotacao-header">
                        <span class="anotacao-medico">Dra. Fernanda Costa - Nutricionista</span>
                        <span class="anotacao-date">28/03/2023</span>
                    </div>
                    <p>Orientações nutricionais fornecidas. Dieta hipossódica e controle de gorduras.</p>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="../js/prontuario.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>