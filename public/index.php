<?php
session_start();

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$medicosPath = __DIR__ . '/../data/medicos.json';

$medicos = [];
if (file_exists($medicosPath)) {
    $medicos = json_decode(file_get_contents($medicosPath), true);
}

$filtered = [];
if ($search !== '') {
    foreach ($medicos as $m) {
        if (
            stripos($m['nome'], $search) !== false ||
            stripos($m['especialidade'], $search) !== false
        ) {
            $filtered[] = $m;
        }
    }
} else {
    $filtered = $medicos;
}

$pageTitle = 'Início - Saúde Conectada';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="search">
    <h2 class="search__title">Encontre um Médico</h2>
    <form class="search__form" action="index.php" method="get">
        <input
            type="text"
            name="q"
            class="search__input"
            placeholder="Digite uma especialidade ou nome..."
            value="<?= htmlspecialchars($search) ?>"
        >
        <button type="submit" class="search__button">Buscar</button>
    </form>
</section>

<?php if ($search !== ''): ?>
<section class="results">
    <h3 class="results__title">
        <?= count($filtered) ?> resultado(s) para "<?= htmlspecialchars($search) ?>"
    </h3>
    <?php if (count($filtered) === 0): ?>
        <p>Nenhum médico encontrado.</p>
    <?php endif; ?>
</section>
<?php endif; ?>

<section class="doctors">
    <?php foreach ($filtered as $medico): ?>
        <div class="doctor-card">
            <img
                src="<?= htmlspecialchars($medico['avatar']) ?>"
                alt="<?= htmlspecialchars($medico['nome']) ?>"
                class="doctor-card__avatar"
            >
            <div class="doctor-card__info">
                <h4 class="doctor-card__name"><?= htmlspecialchars($medico['nome']) ?></h4>
                <p class="doctor-card__specialty"><?= htmlspecialchars($medico['especialidade']) ?></p>
                <p class="doctor-card__status">
                    Status:
                    <span class="<?= $medico['status'] === 'online' ? 'online' : 'offline' ?>">
                        <?= ucfirst($medico['status']) ?>
                    </span>
                </p>
                <a
                    href="chat.php?medico=<?= urlencode($medico['id']) ?>"
                    class="doctor-card__chat-btn"
                >
                    Iniciar Chat
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
