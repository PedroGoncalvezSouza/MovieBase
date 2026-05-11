<?php 
include 'dadosFilmes.php';

function listarFilmes($filmes) { 

    foreach ($filmes as $filme) {
        echo "ID: " . $filme['id'] . "<br>";
        echo "Título: " . $filme['titulo'] . "<br>";
        echo "Ano: " . $filme['ano'] . "<br>";
        echo "Gênero: " . $filme['genero'] . "<br>";
        echo "Diretor: " . $filme['diretor'] . "<br>";
        echo "Nota: " . $filme['nota'] . "<br><br>";
    }
};
listarFilmes($filmes);
?>