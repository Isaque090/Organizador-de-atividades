
<?php if ($filtroAtual == "todos") {
    $pesquisa = $conexao->prepare("SELECT a.cd_atividade,
a.ds_atividade,a.dt_entrega,m.nm_materia,au.st_status FROM atividades a JOIN materias m ON m.cd_materia = a.id_materia LEFT JOIN atividades_usuarios au   ON au.id_atividade = a.cd_atividade    AND au.id_usuario = ? ORDER BY a.dt_entrega DESC");

    $pesquisa->bind_param("i", $id);
    $pesquisa->execute();
    $resultado = $pesquisa->get_result();
    $filtro_todos = "btn-primary";

    $contador = true;
    $_SESSION['filtro'] = "todos";


} else if ($filtroAtual == "atrasadas") {
    $pesquisa = $conexao->prepare("SELECT cd_atividade, ds_atividade,  dt_entrega, (SELECT nm_materia FROM materias WHERE cd_materia = atividades.id_materia) AS nm_materia FROM atividades WHERE dt_entrega < CURDATE() AND NOT EXISTS ( SELECT 1 FROM atividades_usuarios  WHERE atividades_usuarios.id_atividade = atividades.cd_atividade AND atividades_usuarios.id_usuario = ? ) ORDER BY dt_entrega ASC");
    $pesquisa->bind_param("i", $id);
    $pesquisa->execute();
    $resultado = $pesquisa->get_result();
    $filtro_atrasadas = "btn-danger";
    $_SESSION['filtro'] = "atrasadas";
} else if ($filtroAtual == "entregues") {
    $pesquisa = $conexao->prepare("SELECT a.cd_atividade, a.ds_atividade, a.dt_entrega, m.nm_materia FROM atividades a JOIN atividades_usuarios au ON au.id_atividade = a.cd_atividade JOIN materias m  ON m.cd_materia = a.id_materia WHERE au.id_usuario = ? ORDER BY a.dt_entrega ASC");

    $pesquisa->bind_param("i", $id);
    $pesquisa->execute();
    $resultado = $pesquisa->get_result();
    $filtro_entregues = "btn-success";
    $entregue = "btn-success";
    $entreguetexto = "Entregue <i class='bi bi-check-lg'></i>";
    $cor_card = true;
    $_SESSION['filtro'] = "entregues";
} else {


    $pesquisa = $conexao->prepare("SELECT    cd_atividade,    ds_atividade,    dt_entrega,  (SELECT nm_materia FROM materias WHERE cd_materia = atividades.id_materia) AS nm_materia FROM atividades WHERE NOT EXISTS (   SELECT 1  FROM atividades_usuarios    WHERE atividades_usuarios.id_atividade = atividades.cd_atividade AND atividades_usuarios.id_usuario = ? )ORDER BY dt_entrega ASC");
    $pesquisa->bind_param("i", $id);
    $pesquisa->execute();
    $resultado = $pesquisa->get_result();
    $filtro_pendente = "btn-warning";

}

?>