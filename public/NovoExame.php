<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$medicos = json_decode(file_get_contents(__DIR__ . '/../data/medicos.json'), true);
$laboratorios = json_decode(file_get_contents(__DIR__ . '/../data/laboratorios.json'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['tipo_exame']) || empty($_POST['medico']) || empty($_POST['laboratorio'])) {
        die("Todos os campos obrigatórios devem ser preenchidos");
    }

    $arquivoExames = __DIR__ . '/../data/exames.json';
    $exames = file_exists($arquivoExames) ? json_decode(file_get_contents($arquivoExames), true) : [];
    
    $proximoId = 1;
    if (!empty($exames)) {
        $ids = array_column($exames, 'id');
        $proximoId = max($ids) + 1;
    }

    $novoExame = [
        'id' => $proximoId,
        'tipo' => $_POST['tipo_exame'],
        'data' => 'Aguardando agendamento',
        'status' => 'pendente',
        'laboratorio' => $_POST['laboratorio'],
        'medico' => $_POST['medico'],
        'resultado' => 'Aguardando realização do exame',
        'assets' => []
    ];

    if (!empty($_POST['observacoes'])) {
        $novoExame['observacoes'] = $_POST['observacoes'];
    }

    $exames[] = $novoExame;

    file_put_contents($arquivoExames, json_encode($exames, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header("Location: exames.php");
    exit();
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="novo-exame-container">
    <h1>Solicitar Novo Exame</h1>
    
    <form method="POST" class="form-exame">
        <div class="form-group">
            <label for="tipo_exame">Tipo de Exame:</label>
            <select name="tipo_exame" id="tipo_exame" required class="form-control">
                <option value="">Selecione o tipo de exame</option>
                <option value="Hemograma Completo">Hemograma Completo</option>
                <option value="Glicemia em Jejum">Glicemia em Jejum</option>
                <option value="Colesterol Total">Colesterol Total</option>
                <option value="Urina Tipo I">Urina Tipo I</option>
                <option value="TSH e T4 Livre">TSH e T4 Livre</option>
                <option value="Eletrocardiograma">Eletrocardiograma</option>
                <option value="Ultrassonografia Abdominal">Ultrassonografia Abdominal</option>
                <option value="COVID-19 RT-PCR">COVID-19 RT-PCR</option>
                <option value="Ressonância Magnética - Joelho">Ressonância Magnética - Joelho</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="medico">Médico Solicitante:</label>
            <select name="medico" id="medico" required class="form-control">
                <option value="">Selecione o médico</option>
                <?php foreach ($medicos as $medico): ?>
                    <option value="<?= htmlspecialchars($medico['nome']) ?>">
                        <?= htmlspecialchars($medico['nome']) ?> (<?= htmlspecialchars($medico['especialidade']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="laboratorio">Laboratório:</label>
            <select name="laboratorio" id="laboratorio" required class="form-control">
                <option value="">Selecione o laboratório</option>
                <?php foreach ($laboratorios as $lab): ?>
                    <option value="<?= htmlspecialchars($lab['nome']) ?>">
                        <?= htmlspecialchars($lab['nome']) ?> - <?= htmlspecialchars($lab['endereco']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="observacoes">Observações (opcional):</label>
            <textarea name="observacoes" id="observacoes" rows="4" class="form-control"></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Solicitar Exame</button>
            <a href="exames.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>