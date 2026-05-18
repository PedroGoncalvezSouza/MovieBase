<?php
session_start();
require 'db.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = ?");
$stmt->execute([$id]);
$filme = $stmt->fetch();

if (!$filme) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("DELETE FROM filmes WHERE id = ?")->execute([$id]);
    $_SESSION['flash'] = [
        'tipo' => 'sucesso',
        'msg'  => "Filme \"{$filme['titulo']}\" removido com sucesso.",
    ];
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieBase – Confirmar Exclusão</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <div class="header-inner">
    <div>
      <div class="logo">Movie<span>Base</span></div>
      <p class="subtitulo">Confirmar exclusão</p>
    </div>
    <a href="index.php" class="btn btn-voltar">← Voltar</a>
  </div>
</header>

<main class="form-page">
  <div class="form-card form-card--danger">
    <h1 class="form-titulo">🗑️ Remover Filme</h1>

    <div class="alert alert-aviso">
      <p>Você tem certeza que deseja remover este filme? Esta ação não pode ser desfeita.</p>
    </div>

    <div class="confirm-info">
      <p class="confirm-label">Título</p>
      <p class="confirm-value"><?= htmlspecialchars(trim($filme['titulo'])) ?></p>

      <p class="confirm-label">Diretor</p>
      <p class="confirm-value"><?= htmlspecialchars($filme['diretor']) ?></p>

      <p class="confirm-label">Ano</p>
      <p class="confirm-value"><?= (int)$filme['ano'] ?></p>

      <p class="confirm-label">Nota</p>
      <p class="confirm-value"><?= number_format((float)$filme['nota'], 1) ?></p>
    </div>

    <form method="POST" action="deletar.php?id=<?= $id ?>">
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="form-actions">
        <a href="index.php" class="btn btn-voltar">Cancelar</a>
        <button type="submit" class="btn btn-deletar btn-deletar--lg">Sim, Remover</button>
      </div>
    </form>
  </div>
</main>

<footer>MovieBase &copy; 2026</footer>

</body>
</html>