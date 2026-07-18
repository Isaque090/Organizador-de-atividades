<?php
session_start();
include_once('config/config.php');

$id = $_SESSION['id'];


$update = $conexao->prepare("UPDATE notificacoes SET lida = 1 WHERE id_usuario = ?
");

$update->bind_param("i", $id);
$update->execute();


$gerais = $conexao->prepare("INSERT IGNORE INTO notificacoes_lidas (id_notificacao, id_usuario) SELECT id, ? FROM notificacoes WHERE id_usuario = 0");

$gerais->bind_param("i", $id);
$gerais->execute();