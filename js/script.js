const generoIcons = {
  "Drama":              "🎭",
  "Drama musical":      "🎵",
  "Crime":              "🔫",
  "Ação":               "💥",
  "Ficção Científica":  "🚀",
  "Sci-Fi":             "🚀",
  "Terror":             "💀",
  "terror":             "💀",
  "Guerra":             "⚔️",
  "Animação":           "🎨",
  "Mistério":           "🔍",
  "Western":            "🤠",
  "Aventura":           "🗺️",
  "Comédia dramatica":  "😄",
};

let filtroAtivo = "Todos";
let ordenacao   = "nota";
let termoBusca  = "";

function notaCor(nota) {
  if (nota >= 9.0) return "nota-ouro";
  if (nota >= 8.7) return "nota-verde";
  return "nota-azul";
}

function renderGeneros() {
  const generos = ["Todos", ...new Set(filmes.map((f) => f.genero))].sort(
    (a, b) => a === "Todos" ? -1 : b === "Todos" ? 1 : a.localeCompare(b, "pt")
  );
  const container = document.getElementById("generos");
  container.innerHTML = generos
    .map((g) => `<button class="pill${g === filtroAtivo ? " ativa" : ""}" data-genero="${g}">${g}</button>`)
    .join("");
  container.querySelectorAll(".pill").forEach((btn) =>
    btn.addEventListener("click", () => {
      filtroAtivo = btn.dataset.genero;
      renderTudo();
    })
  );
}

function renderFilmes() {
  let lista = [...filmes];

  if (filtroAtivo !== "Todos")
    lista = lista.filter((f) => f.genero === filtroAtivo);

  if (termoBusca) {
    const t = termoBusca.toLowerCase();
    lista = lista.filter(
      (f) =>
        f.titulo.toLowerCase().includes(t) ||
        f.diretor.toLowerCase().includes(t)
    );
  }

  lista.sort((a, b) => {
    if (ordenacao === "nota")   return b.nota - a.nota;
    if (ordenacao === "ano")    return b.ano - a.ano;
    if (ordenacao === "titulo") return a.titulo.localeCompare(b.titulo);
  });

  document.getElementById("contador").textContent =
    `${lista.length} filme${lista.length !== 1 ? "s" : ""}`;

  const grid = document.getElementById("grid");

  if (lista.length === 0) {
    grid.innerHTML = `<div class="vazio"><span>🎬</span><p>Nenhum filme encontrado.</p></div>`;
    return;
  }

  grid.innerHTML = lista
    .map(
      (f, i) => `
        <article class="card" style="animation-delay:${i * 50}ms">
          <div class="card-topo">
            <span class="icone-genero">${generoIcons[f.genero] ?? "🎬"}</span>
            <span class="badge-genero">${f.genero}</span>
          </div>
          <h2 class="card-titulo">${f.titulo.trim()}</h2>
          <p class="card-meta">🎬 ${f.diretor}</p>
          <p class="card-meta">📅 ${f.ano}</p>
          <div class="card-rodape">
            <span class="nota ${notaCor(f.nota)}">${Number(f.nota).toFixed(1)}</span>
            <span class="label-nota">IMDb</span>
          </div>
        </article>
      `
    )
    .join("");
}

function renderTudo() {
  renderGeneros();
  renderFilmes();
}

document.addEventListener("DOMContentLoaded", () => {
  renderTudo();

  document.getElementById("busca").addEventListener("input", (e) => {
    termoBusca = e.target.value.trim();
    renderFilmes();
  });

  document.getElementById("ordenar").addEventListener("change", (e) => {
    ordenacao = e.target.value;
    renderFilmes();
  });
});