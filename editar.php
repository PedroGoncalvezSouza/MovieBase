<?php
session_start();
require 'db.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch();

if (!$dados) {
    header('Location: index.php');
    exit;
}

$erros   = [];
$generos = $pdo->query("SELECT DISTINCT genero FROM filmes ORDER BY genero")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'id'      => $id,
        'titulo'  => trim($_POST['titulo']  ?? ''),
        'ano'     => (int)($_POST['ano']    ?? 0),
        'genero'  => trim($_POST['genero']  ?? ''),
        'diretor' => trim($_POST['diretor'] ?? ''),
        'nota'    => trim(str_replace(',', '.', $_POST['nota'] ?? '')),
    ];

    if ($dados['titulo'] === '')
        $erros[] = 'Título é obrigatório.';
    if ($dados['ano'] < 1888 || $dados['ano'] > 2100)
        $erros[] = 'Ano deve estar entre 1888 e 2100.';
    if ($dados['genero'] === '')
        $erros[] = 'Gênero é obrigatório.';
    if ($dados['diretor'] === '')
        $erros[] = 'Diretor é obrigatório.';

    $nota = is_numeric($dados['nota']) ? (float)$dados['nota'] : -1;
    if ($nota < 0 || $nota > 10)
        $erros[] = 'Nota deve ser um número entre 0 e 10.';

    if (empty($erros)) {
        $pdo->prepare(
            "UPDATE filmes SET titulo=?, ano=?, genero=?, diretor=?, nota=? WHERE id=?"
        )->execute([$dados['titulo'], $dados['ano'], $dados['genero'], $dados['diretor'], $nota, $id]);

        $_SESSION['flash'] = [
            'tipo' => 'sucesso',
            'msg'  => "Filme \"{$dados['titulo']}\" atualizado com sucesso!",
        ];
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieBase – Editar Filme</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <div class="header-inner">
    <div>
      <div class="logo">Movie<span>Base</span></div>
      <p class="subtitulo">Editar filme</p>
    </div>
    <a href="index.php" class="btn btn-voltar">← Voltar</a>
  </div>
</header>

<main class="form-page">
  <div class="form-card">
    <h1 class="form-titulo">✏️ Editar Filme</h1>

    <?php if ($erros): ?>
      <div class="alert alert-erro">
        <?php foreach ($erros as $e): ?>
          <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="editar.php?id=<?= $id ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="form-group">
        <label for="titulo">Título</label>
        <input class="form-input" type="text" id="titulo" name="titulo"
               value="<?= htmlspecialchars(trim($dados['titulo'])) ?>"
               required autofocus>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="ano">Ano</label>
          <input class="form-input" type="number" id="ano" name="ano"
                 value="<?= (int)$dados['ano'] ?>"
                 min="1888" max="2100" required>
        </div>
        <div class="form-group">
          <label for="nota">Nota (0–10)</label>
          <input class="form-input" type="number" id="nota" name="nota"
                 value="<?= number_format((float)$dados['nota'], 1) ?>"
                 min="0" max="10" step="0.1" required>
        </div>
      </div>

      <div class="form-group">
        <label for="genero">Gênero</label>
        <input class="form-input" type="text" id="genero" name="genero"
               value="<?= htmlspecialchars($dados['genero']) ?>"
               list="generos-list" required>
        <datalist id="generos-list">
          <?php foreach ($generos as $g): ?>
            <option value="<?= htmlspecialchars($g) ?>">
          <?php endforeach; ?>
        </datalist>
      </div>

      <div class="form-group">
        <label for="diretor">Diretor</label>
        <input class="form-input" type="text" id="diretor" name="diretor"
               value="<?= htmlspecialchars($dados['diretor']) ?>"
               required>
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn btn-voltar">Cancelar</a>
        <button type="submit" class="btn btn-add">Salvar Alterações</button>
      </div>

    </form>
  </div>
</main>

<footer>MovieBase &copy; 2026</footer>

</body>
</html>