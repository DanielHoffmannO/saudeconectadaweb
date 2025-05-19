<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Minhas Consultas - Saúde Conectada";
$additionalCSS = ['consultas.css'];

require_once __DIR__ . '/../includes/header.php';

$consultasPath = __DIR__ . '/../data/consultas.json';
$consultas = [];

if (file_exists($consultasPath)) {
    $consultas = json_decode(file_get_contents($consultasPath), true);
}

$consultasUsuario = array_filter($consultas, function($consulta) {
    return $consulta['paciente'] === 'Daniel Hoffmann';
});

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agendar'])) {
    $novaConsulta = [
        'id' => count($consultas) + 1,
        'paciente' => 'Daniel Hoffmann', 
        'medico' => $_POST['medico'],
        'especialidade' => $_POST['especialidade'],
        'data' => $_POST['data'],
        'hora' => $_POST['hora'],
        'status' => 'Solicitada',
        'observacoes' => $_POST['observacoes']
    ];

    $consultas[] = $novaConsulta;
    file_put_contents($consultasPath, json_encode($consultas, JSON_PRETTY_PRINT));
    
    header("Location: consultas.php");
    exit();
}

$consultaProxima = null;
$hoje = date('Y-m-d');
foreach ($consultasUsuario as $consulta) {
    if ($consulta['data'] >= $hoje) {
        if (!$consultaProxima || $consulta['data'] < $consultaProxima['data']) {
            $consultaProxima = $consulta;
        }
    }
}

$eventosCalendario = [];
foreach ($consultasUsuario as $consulta) {
    $eventosCalendario[] = [
        'id' => $consulta['id'],
        'title' => $consulta['medico'] . ' - ' . $consulta['especialidade'],
        'start' => $consulta['data'] . 'T' . $consulta['hora'],
        'extendedProps' => [
            'paciente' => $consulta['paciente'],
            'medico' => $consulta['medico'],
            'especialidade' => $consulta['especialidade'],
            'status' => $consulta['status'],
            'observacoes' => $consulta['observacoes']
        ],
        'backgroundColor' => $consulta['status'] === 'confirmada' ? '#28a745' : 
                          ($consulta['status'] === 'pendente' ? '#ffc107' : '#dc3545')
    ];
}
?>

<main class="consultas-container">
    <div class="consultas-layout">
        <div class="consulta-detalhes">
            <h2>Detalhes da Consulta</h2>
            
            <div id="detalhes-consulta" class="detalhes-container">
                <?php if ($consultaProxima): ?>
                    <div class="consulta-info">
                        <h3><?= htmlspecialchars($consultaProxima['medico']) ?> - <?= htmlspecialchars($consultaProxima['especialidade']) ?></h3>
                        <p><strong>Paciente:</strong> <?= htmlspecialchars($consultaProxima['paciente']) ?></p>
                        <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($consultaProxima['data'])) ?></p>
                        <p><strong>Hora:</strong> <?= htmlspecialchars($consultaProxima['hora']) ?></p>
                        <p><strong>Status:</strong> <span class="status-badge <?= $consultaProxima['status'] ?>"><?= ucfirst($consultaProxima['status']) ?></span></p>
                        <p><strong>Observações:</strong> <?= htmlspecialchars($consultaProxima['observacoes']) ?></p>
                        
                        <div class="consulta-actions">
                            <button class="btn-primary" onclick="location.href='videochat.php?medico=<?= urlencode($consultaProxima['id']) ?>'">
                                <i class="fas fa-video"></i> Iniciar Videochamada
                            </button>
                            <button class="btn-danger" onclick="cancelarConsulta(<?= $consultaProxima['id'] ?>)">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="sem-consulta">
                        <p>Nenhuma consulta agendada</p>
                        <button class="btn-primary" id="btn-nova-consulta">
                            <i class="fas fa-plus"></i> Agendar Nova Consulta
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            
            <div id="form-nova-consulta" class="form-container" style="display: none;">
                <h3>Agendar Nova Consulta</h3>
                <form method="POST" action="consultas.php">
                    <input type="hidden" name="agendar" value="1">
                    
                    <div class="form-group">
                        <label for="medico">Médico:</label>
                        <select id="medico" name="medico" class="form-control" required>
                            <option value="">Selecione um médico</option>
                            <option value="Dr. Carlos Silva">Dr. Carlos Silva - Cardiologia</option>
                            <option value="Dra. Ana Oliveira">Dra. Ana Oliveira - Dermatologia</option>
                            <option value="Dr. Roberto Santos">Dr. Roberto Santos - Ortopedia</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="especialidade">Especialidade:</label>
                        <input type="text" id="especialidade" name="especialidade" class="form-control" required readonly
                               value="Cardiologia" id="especialidade-input">
                        <small>Definida automaticamente pelo médico selecionado</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="data">Data:</label>
                        <input type="date" id="data" name="data" class="form-control" required min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="hora">Hora:</label>
                        <input type="time" id="hora" name="hora" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="observacoes">Observações:</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Agendar</button>
                        <button type="button" id="btn-cancelar" class="btn-secondary">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="calendario-container">
            <div id='calendar'></div>
        </div>
    </div>
</main>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const medicosEspecialidades = {
        'Dr. Carlos Silva': 'Cardiologia',
        'Dra. Ana Oliveira': 'Dermatologia',
        'Dr. Roberto Santos': 'Ortopedia'
    };
    
    document.getElementById('medico').addEventListener('change', function() {
        const medico = this.value;
        document.getElementById('especialidade-input').value = medicosEspecialidades[medico] || '';
    });
    
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?= json_encode($eventosCalendario) ?>,
        eventClick: function(info) {
            const consulta = info.event;
            const props = consulta.extendedProps;
            
            document.getElementById('detalhes-consulta').innerHTML = `
                <div class="consulta-info">
                    <h3>${props.medico} - ${props.especialidade}</h3>
                    <p><strong>Paciente:</strong> ${props.paciente}</p>
                    <p><strong>Data:</strong> ${consulta.start.toLocaleDateString('pt-BR')}</p>
                    <p><strong>Hora:</strong> ${consulta.start.toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'})}</p>
                    <p><strong>Status:</strong> <span class="status-badge ${props.status}">${props.status.charAt(0).toUpperCase() + props.status.slice(1)}</span></p>
                    <p><strong>Observações:</strong> ${props.observacoes || 'Nenhuma'}</p>
                    
                    <div class="consulta-actions">
                        <button class="btn-primary" onclick="location.href='videochat.php?medico=${consulta.id}'">
                            <i class="fas fa-video"></i> Iniciar Videochamada
                        </button>
                        <button class="btn-danger" onclick="cancelarConsulta(${consulta.id})">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('form-nova-consulta').style.display = 'none';
            document.getElementById('detalhes-consulta').style.display = 'block';
        },
        dateClick: function(info) {
            document.getElementById('form-nova-consulta').style.display = 'block';
            document.getElementById('detalhes-consulta').style.display = 'none';
            document.getElementById('data').value = info.dateStr;
        }
    });
    
    calendar.render();
    
    document.getElementById('btn-nova-consulta').addEventListener('click', function() {
        document.getElementById('form-nova-consulta').style.display = 'block';
        document.getElementById('detalhes-consulta').style.display = 'none';
    });
    
    document.getElementById('btn-cancelar').addEventListener('click', function() {
        document.getElementById('form-nova-consulta').style.display = 'none';
        document.getElementById('detalhes-consulta').style.display = 'block';
    });
});

function cancelarConsulta(idConsulta) {
    Swal.fire({
        title: 'Cancelar Consulta',
        text: 'Tem certeza que deseja cancelar esta consulta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`cancelar_consulta.php?id=${idConsulta}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', 'Consulta cancelada com sucesso.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Erro!', data.message || 'Erro ao cancelar consulta.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Erro!', 'Ocorreu um erro ao tentar cancelar a consulta.', 'error');
            });
        }
    });
}
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>