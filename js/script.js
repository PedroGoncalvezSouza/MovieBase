const filmes = [
  {
    id: 1,
    titulo: "The Shawshank Redemption",
    ano: 1994,
    genero: "Drama",
    diretor: "Frank Darabont",
    nota: 9.3,
  },
  {
    id: 2,
    titulo: "The Godfather",
    ano: 1972,
    genero: "Crime",
    diretor: "Francis Ford Coppola",
    nota: 9.2,
  },
  {
    id: 3,
    titulo: "The Dark Knight",
    ano: 2008,
    genero: "Ação",
    diretor: "Christopher Nolan",
    nota: 9.0,
  },
  {
    id: 4,
    titulo: "Pulp Fiction",
    ano: 1994,
    genero: "Crime",
    diretor: "Quentin Tarantino",
    nota: 8.9,
  },
  {
    id: 5,
    titulo: "Schindler's List",
    ano: 1993,
    genero: "Drama",
    diretor: "Steven Spielberg",
    nota: 8.9,
  },
  {
    id: 6,
    titulo: "Interstellar",
    ano: 2014,
    genero: "Ficção Científica",
    diretor: "Christopher Nolan",
    nota: 8.7,
  },
  {
    id: 7,
    titulo: "Fight Club",
    ano: 1999,
    genero: "Drama",
    diretor: "David Fincher",
    nota: 8.8,
  },
  {
    id: 8,
    titulo: "Forrest Gump",
    ano: 1994,
    genero: "Drama",
    diretor: "Robert Zemeckis",
    nota: 8.8,
  },
  {
    id: 9,
    titulo: "Inception",
    ano: 2010,
    genero: "Ficção Científica",
    diretor: "Christopher Nolan",
    nota: 8.8,
  },
  {
    id: 10,
    titulo: "The Matrix",
    ano: 1999,
    genero: "Ficção Científica",
    diretor: "The Wachowskis",
    nota: 8.7,
  },
  {
    id: 11,
    titulo: "Goodfellas",
    ano: 1990,
    genero: "Crime",
    diretor: "Martin Scorsese",
    nota: 8.7,
  },
  {
    id: 12,
    titulo: "The Silence of the Lambs",
    ano: 1991,
    genero: "Terror",
    diretor: "Jonathan Demme",
    nota: 8.6,
  },
  {
    id: 13,
    titulo: "Saving Private Ryan",
    ano: 1998,
    genero: "Guerra",
    diretor: "Steven Spielberg",
    nota: 8.6,
  },
  {
    id: 14,
    titulo: "The Lion King",
    ano: 1994,
    genero: "Animação",
    diretor: "Roger Allers",
    nota: 8.5,
  },
  {
    id: 15,
    titulo: "Parasite",
    ano: 2019,
    genero: "Drama",
    diretor: "Bong Joon-ho",
    nota: 8.5,
  },
  {
    id: 16,
    titulo: "Whiplash",
    ano: 2014,
    genero: "Drama",
    diretor: "Damien Chazelle",
    nota: 8.5,
  },
  {
    id: 17,
    titulo: "Gladiator",
    ano: 2000,
    genero: "Ação",
    diretor: "Ridley Scott",
    nota: 8.5,
  },
  {
    id: 18,
    titulo: "The Prestige",
    ano: 2006,
    genero: "Mistério",
    diretor: "Christopher Nolan",
    nota: 8.5,
  },
  {
    id: 19,
    titulo: "Joker",
    ano: 2019,
    genero: "Drama",
    diretor: "Todd Phillips",
    nota: 8.4,
  },
  {
    id: 20,
    titulo: "Django Unchained",
    ano: 2012,
    genero: "Western",
    diretor: "Quentin Tarantino",
    nota: 8.4,
  },
];

const generoIcons = {
  Drama: "🎭",
  Crime: "🔫",
  Ação: "💥",
  "Ficção Científica": "🚀",
  Terror: "💀",
  Guerra: "⚔️",
  Animação: "🎨",
  Mistério: "🔍",
  Western: "🤠",
};

let filtroAtivo = "Todos";
let ordenacao = "nota";
let termoBusca = "";

function notaCor(nota) {
  if (nota >= 9.0) return "nota-ouro";
  if (nota >= 8.7) return "nota-verde";
  return "nota-azul";
}

function renderGeneros() {
  const generos = ["Todos", ...new Set(filmes.map((f) => f.genero))].sort(
    (a, b) =>
      a === "Todos" ? -1 : b === "Todos" ? 1 : a.localeCompare(b, "pt"),
  );
  const container = document.getElementById("generos");
  container.innerHTML = generos
    .map(
      (g) =>
        `<button class="pill${g === filtroAtivo ? " ativa" : ""}" data-genero="${g}">${g}</button>`,
    )
    .join("");
  container.querySelectorAll(".pill").forEach((btn) =>
    btn.addEventListener("click", () => {
      filtroAtivo = btn.dataset.genero;
      renderTudo();
    }),
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
        f.diretor.toLowerCase().includes(t),
    );
  }

  lista.sort((a, b) => {
    if (ordenacao === "nota") return b.nota - a.nota;
    if (ordenacao === "ano") return b.ano - a.ano;
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
      <h2 class="card-titulo">${f.titulo}</h2>
      <p class="card-meta">🎬 ${f.diretor}</p>
      <p class="card-meta">📅 ${f.ano}</p>
      <div class="card-rodape">
        <span class="nota ${notaCor(f.nota)}">${f.nota.toFixed(1)}</span>
        <span class="label-nota">IMDb</span>
      </div>
    </article>
  `,
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
