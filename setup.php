<?php
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die('Não foi possível conectar ao MySQL: ' . $e->getMessage());
}

$pdo->exec("CREATE DATABASE IF NOT EXISTS moviebase
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE moviebase");
$pdo->exec("
    CREATE TABLE IF NOT EXISTS filmes (
        id      INT AUTO_INCREMENT PRIMARY KEY,
        titulo  VARCHAR(255) NOT NULL,
        ano     INT          NOT NULL,
        genero  VARCHAR(100) NOT NULL,
        diretor VARCHAR(255) NOT NULL,
        nota    DECIMAL(3,1) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

$count     = (int) $pdo->query("SELECT COUNT(*) FROM filmes")->fetchColumn();
$inseridos = 0;

if ($count === 0) {
    include __DIR__ . '/dadosFilmes.php';
    $stmt = $pdo->prepare(
        "INSERT INTO filmes (titulo, ano, genero, diretor, nota) VALUES (?, ?, ?, ?, ?)"
    );
    foreach ($filmes as $f) {
        $stmt->execute([
            trim($f['titulo']),
            (int)   $f['ano'],
            trim($f['genero']),
            trim($f['diretor']),
            (float) $f['nota'],
        ]);
        $inseridos++;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieBase – Setup</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .setup-wrap { display:flex; align-items:center; justify-content:center; min-height:70vh; }
    .setup-card { background:var(--surface); border:1px solid var(--border);
                  border-radius:var(--radius); padding:2.5rem; max-width:460px;
                  width:90%; text-align:center; }
    .setup-card h1 { color:var(--accent); font-size:1.6rem; margin-bottom:1rem; }
    .setup-card p  { color:var(--muted); margin:.4rem 0; }
    .setup-card strong { color:var(--text); }
  </style>
</head>
<body>
<header>
  <div class="header-inner">
    <div>
      <div class="logo">Movie<span>Base</span></div>
      <p class="subtitulo">Configuração do banco de dados</p>
    </div>
  </div>
</header>

<div class="setup-wrap">
  <div class="setup-card">
    <h1>✅ Setup Concluído</h1>
    <p>Banco de dados: <strong>moviebase</strong></p>
    <p>Tabela: <strong>filmes</strong></p>
    <?php if ($inseridos > 0): ?>
      <p><strong><?= $inseridos ?></strong> filmes inseridos como dados iniciais.</p>
    <?php else: ?>
      <p>Tabela já possuía <strong><?= $count ?></strong> registros. Seeding ignorado.</p>
    <?php endif; ?>
    <a href="index.php" class="btn btn-add" style="display:inline-block;margin-top:1.5rem;text-decoration:none">
      Ir para o MovieBase →
    </a>
  </div>
</div>
</body>
</html>