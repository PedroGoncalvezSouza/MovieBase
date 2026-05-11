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
    <div class="logo">Movie<span>Base</span></div>
    <p class="subtitulo">Os melhores filmes de todos os tempos</p>
  </header>

  <div class="controles">
    <div class="busca-wrap">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input id="busca" type="search" placeholder="Buscar por título ou diretor…">
    </div>
    <select id="ordenar">
      <option value="nota">Ordenar: Nota</option>
      <option value="ano">Ordenar: Ano</option>
      <option value="titulo">Ordenar: Título</option>
    </select>
  </div>

  <main>
    <div class="filtros-topo">
      <div id="generos"></div>
      <span id="contador"></span>
    </div>
    <div id="grid"></div>
  </main>

  <footer>
    MovieBase &copy; 2026 &mdash; Dados baseados no IMDb
  </footer>

  <script src="js/script.js"></script>
</body>
</html>