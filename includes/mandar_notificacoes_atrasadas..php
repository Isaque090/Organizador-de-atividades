<?php
session_start();

include_once('config/config.php');
$hoje = date('Y-m-d');
$id = $_SESSION['id'];
$sql = $conexao->prepare("
SELECT
    a.cd_atividade,
    a.ds_atividade,
    a.dt_entrega,
    m.nm_materia
FROM atividades a

JOIN materias m
    ON m.cd_materia = a.id_materia

LEFT JOIN atividades_usuarios au
    ON au.id_atividade = a.cd_atividade
    AND au.id_usuario = ?

LEFT JOIN notificacoes n
    ON n.id_atividade = a.cd_atividade
    AND n.tipo = 'atrasada'
    AND n.id_usuario = ?

WHERE
    a.dt_entrega < CURDATE()
    AND au.id_atividade IS NULL
    AND n.id IS NULL
");
$sql->bind_param("ii", $id,$id);
$sql->execute();

$result = $sql->get_result();

while ($atividade = $result->fetch_assoc()) {

    $titulo = "Atividade atrasada";
    $mensagem = "A atividade de {$atividade['nm_materia']} está atrasada.";

    $insert = $conexao->prepare("
        INSERT INTO notificacoes
        (id_usuario, id_atividade, titulo, mensagem, tipo)
        VALUES (?, ?, ?, ?, 'atrasada')
    ");

    $insert->bind_param(
        "iiss",
        $id,
        $atividade['cd_atividade'],
        $titulo,
        $mensagem
    );

    $insert->execute();
}
?>