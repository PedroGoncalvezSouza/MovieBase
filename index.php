<?php
session_start();
require 'db.php';

$flash   = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$busca   = trim($_GET['busca']   ?? '');
$genero  = $_GET['genero']  ?? '';
$ordenar = in_array($_GET['ordenar'] ?? '', ['nota', 'ano', 'titulo'])
         ? $_GET['ordenar'] : 'nota';

// ── Query ──────────────────────────────────────────────
$where  = [];
$params = [];

if ($busca !== '') {
    $where[]  = "(titulo LIKE ? OR diretor LIKE ?)";
    $params[] = "%{$busca}%";
    $params[] = "%{$busca}%";
}

if ($genero !== '') {
    $where[]  = "genero = ?";
    $params[] = $genero;
}

$orderMap = ['nota' => 'nota DESC', 'ano' => 'ano DESC', 'titulo' => 'titulo ASC'];
$sql = "SELECT * FROM filmes"
     . ($where ? " WHERE " . implode(" AND ", $where) : "")
     . " ORDER BY " . $orderMap[$ordenar];

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$lista = $stmt->fetchAll();

$todosGeneros = $pdo->query(
    "SELECT DISTINCT genero FROM filmes ORDER BY genero"
)->fetchAll(PDO::FETCH_COLUMN);

// ── Helpers ────────────────────────────────────────────
$generoIcons = [
    'Drama'             => '🎭', 'Drama musical'     => '🎵',
    'Crime'             => '🔫', 'Ação'              => '💥',
    'Ficção Científica' => '🚀', 'Sci-Fi'            => '🚀',
    'Terror'            => '💀', 'terror'            => '💀',
    'Guerra'            => '⚔️', 'Animação'          => '🎨',
    'Mistério'          => '🔍', 'Western'           => '🤠',
    'Aventura'          => '🗺️', 'Comédia dramatica' => '😄',
];

function notaCor(float $nota): string {
    if ($nota >= 9.0) return 'nota-ouro';
    if ($nota >= 8.7) return 'nota-verde';
    return 'nota-azul';
}

function genreUrl(string $g, string $busca, string $ordenar): string {
    $p = ['ordenar' => $ordenar];
    if ($busca !== '') $p['busca']  = $busca;
    if ($g     !== '') $p['genero'] = $g;
    return 'index.php?' . http_build_query($p);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieBase</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <div class="header-inner">
    <div>
      <div class="logo">Movie<span>Base</span></div>
      <p class="subtitulo">Os melhores filmes de todos os tempos</p>
    </div>
    <a href="criar.php" class="btn btn-add">+ Adicionar Filme</a>
  </div>
</header>

<?php if ($flash): ?>
  <div class="flash flash-<?= $flash['tipo'] ?>">
    <?= htmlspecialchars($flash['msg']) ?>
  </div>
<?php endif; ?>

<div class="controles">
  <form method="GET" action="index.php" class="controles-form">
    <div class="busca-wrap">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input id="busca" name="busca" type="search"
             placeholder="Buscar por título ou diretor…"
             value="<?= htmlspecialchars($busca) ?>">
    </div>
    <select id="ordenar" name="ordenar" onchange="this.form.submit()">
      <option value="nota"   <?= $ordenar === 'nota'   ? 'selected' : '' ?>>Ordenar: Nota</option>
      <option value="ano"    <?= $ordenar === 'ano'    ? 'selected' : '' ?>>Ordenar: Ano</option>
      <option value="titulo" <?= $ordenar === 'titulo' ? 'selected' : '' ?>>Ordenar: Título</option>
    </select>
    <?php if ($genero !== ''): ?>
      <input type="hidden" name="genero" value="<?= htmlspecialchars($genero) ?>">
    <?php endif; ?>
    <button type="submit" class="btn btn-buscar">Buscar</button>
  </form>
</div>

<main>
  <div class="filtros-topo">
    <div id="generos">
      <a href="<?= genreUrl('', $busca, $ordenar) ?>"
         class="pill<?= $genero === '' ? ' ativa' : '' ?>">Todos</a>
      <?php foreach ($todosGeneros as $g): ?>
        <a href="<?= genreUrl($g, $busca, $ordenar) ?>"
           class="pill<?= $genero === $g ? ' ativa' : '' ?>">
          <?= htmlspecialchars($g) ?>
        </a>
      <?php endforeach; ?>
    </div>
    <span id="contador">
      <?= count($lista) ?> filme<?= count($lista) !== 1 ? 's' : '' ?>
    </span>
  </div>

  <?php if (empty($lista)): ?>
    <div class="vazio">
      <span>🎬</span>
      <p>Nenhum filme encontrado.</p>
    </div>
  <?php else: ?>
    <div id="grid">
      <?php foreach ($lista as $f): ?>
        <article class="card">
          <div class="card-topo">
            <span class="icone-genero"><?= $generoIcons[$f['genero']] ?? '🎬' ?></span>
            <span class="badge-genero"><?= htmlspecialchars($f['genero']) ?></span>
          </div>
          <h2 class="card-titulo"><?= htmlspecialchars(trim($f['titulo'])) ?></h2>
          <p class="card-meta">🎬 <?= htmlspecialchars($f['diretor']) ?></p>
          <p class="card-meta">📅 <?= (int)$f['ano'] ?></p>
          <div class="card-rodape">
            <span class="nota <?= notaCor((float)$f['nota']) ?>">
              <?= number_format((float)$f['nota'], 1) ?>
            </span>
            <span class="label-nota">IMDb</span>
          </div>
          <div class="card-acoes">
            <a href="editar.php?id=<?= $f['id'] ?>" class="btn btn-editar">Editar</a>
            <a href="deletar.php?id=<?= $f['id'] ?>" class="btn btn-deletar">Deletar</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<footer>
  MovieBase &copy; 2026 &mdash;
  <?= count($lista) ?> filme<?= count($lista) !== 1 ? 's' : '' ?> exibido<?= count($lista) !== 1 ? 's' : '' ?>
</footer>

</body>
</html>