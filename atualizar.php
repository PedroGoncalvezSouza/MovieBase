<?php

session_start();

require_once 'dadosFilmes.php';

if (!isset($_SESSION['filmes'])) {
    $_SESSION['filmes'] = $filmes;
}

function deletarFilme($id) {
    if (!isset($_SESSION['filmes']) || !is_array($_SESSION['filmes'])) {
        return false;
    }

    foreach ($_SESSION['filmes'] as $key => $filme) {
        if (isset($filme['id']) && (string) $filme['id'] === (string) $id) {
            unset($_SESSION['filmes'][$key]);
            $_SESSION['filmes'] = array_values($_SESSION['filmes']);
            return true;
        }
    }

    return false;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (deletarFilme($id)) {
        header("Location: filmes.php?msg=sucesso_excluir");
    } else {
        header("Location: filmes.php?msg=erro_id_nao_encontrado");
    }
    exit();
}