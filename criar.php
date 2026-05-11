<?php

$dataFile = __DIR__ . '/filmes.json';
$errors = [];
$success = '';

function loadMovies()
{
    global $dataFile;

    if (file_exists($dataFile)) {
        $json = file_get_contents($dataFile);
        $movies = json_decode($json, true);
        if (is_array($movies)) {
            return $movies;
        }
    }

    require_once __DIR__ . '/dadosFilmes.php';
    return $filmes ?? [];
}

function saveMovies(array $movies)
{
    global $dataFile;
    return file_put_contents($dataFile, json_encode($movies, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$movies = loadMovies();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $ano = trim($_POST['ano'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $diretor = trim($_POST['diretor'] ?? '');
    $nota = trim($_POST['nota'] ?? '');

    if ($titulo === '') {
        $errors[] = 'O título é obrigatório.';
    }

    if ($ano === '' || !filter_var($ano, FILTER_VALIDATE_INT)) {
        $errors[] = 'O ano deve ser um número inteiro válido.';
    } else {
        $ano = (int) $ano;
        if ($ano < 1800 || $ano > (int) date('Y') + 1) {
            $errors[] = 'Informe um ano válido para o filme.';
        }
    }

    if ($genero === '') {
        $errors[] = 'O gênero é obrigatório.';
    }

    if ($diretor === '') {
        $errors[] = 'O diretor é obrigatório.';
    }

    if ($nota === '' || !is_numeric($nota)) {
        $errors[] = 'A nota deve ser um número entre 0 e 10.';
    } else {
        $nota = (float) str_replace(',', '.', $nota);
        if ($nota < 0 || $nota > 10) {
            $errors[] = 'A nota deve estar entre 0 e 10.';
        }
    }

    if (empty($errors)) {
        $newId = 1;
        $ids = array_column($movies, 'id');
        if (!empty($ids)) {
            $newId = max($ids) + 1;
        }

        $newMovie = [
            'id' => $newId,
            'titulo' => $titulo,
            'ano' => $ano,
            'genero' => $genero,
            'diretor' => $diretor,
            'nota' => $nota,
        ];

        $movies[] = $newMovie;

        if (saveMovies($movies) !== false) {
            $success = 'Filme criado com sucesso!';
            $titulo = $ano = $genero = $diretor = $nota = '';
        } else {
            $errors[] = 'Não foi possível salvar o filme. Verifique as permissões de gravação.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Filme</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; color: #333; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 18px rgba(0,0,0,.08); }
        h1 { margin-top: 0; }
        label { display: block; margin: 12px 0 4px; }
        input[type="text"], input[type="number"], input[type="submit"] { width: 100%; padding: 10px; border: 1px solid #bbb; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background: #0078d7; color: white; border: none; cursor: pointer; margin-top: 16px; }
        input[type="submit"]:hover { background: #005fa3; }
        .message { padding: 12px; border-radius: 4px; margin: 12px 0; }
        .error { background: #ffe6e6; border: 1px solid #ffb3b3; }
        .success { background: #e6ffe6; border: 1px solid #b3ffb3; }
        .small { font-size: 0.9rem; color: #555; }
    </style>
</head>
<body>
<div class="container">
    <h1>Cadastrar novo filme</h1>

    <?php if (!empty($errors)): ?>
        <div class="message error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="ano">Ano</label>
        <input type="number" id="ano" name="ano" value="<?php echo htmlspecialchars($ano ?? '', ENT_QUOTES, 'UTF-8'); ?>" min="1800" max="<?php echo date('Y') + 1; ?>" required>

        <label for="genero">Gênero</label>
        <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($genero ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="diretor">Diretor</label>
        <input type="text" id="diretor" name="diretor" value="<?php echo htmlspecialchars($diretor ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="nota">Nota (0 a 10)</label>
        <input type="text" id="nota" name="nota" value="<?php echo htmlspecialchars($nota ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <input type="submit" value="Salvar filme">
    </form>

    <p class="small">Quantidade de filmes atualmente salvos: <?php echo count($movies); ?>.</p>
</div>
</body>
</html>
