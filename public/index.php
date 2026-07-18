<?php

session_start();
if (isset($_POST['filtro'])) {
    $_SESSION['filtro'] = $_GET['filtro'];
}
$id = $_SESSION['id'];
include_once('../includes/config/config.php');
if ((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    unset($_SESSION['id']);
    header('location:login.php');
    exit;
}
$contador = false;
$filtro_todos = "btn-outline-primary";
$filtro_pendente = "btn-outline-warning";

$filtro_atrasadas = "btn-outline-danger";

$filtro_entregues = "btn-outline-success";
$entregue = "btn-outline-success";
$entreguetexto = "Entregar";
if (!isset($_SESSION['filtro'])) {
    $_SESSION['filtro'] = "todos";
}
$filtroAtual = $_GET['filtro'] ?? $_SESSION['filtro'];
$hoje = date('Y-m-d');

include_once('../includes/filtro.php');
if (isset($_POST['entrega'])) {
    $id_atividade = $_POST['id_atividade'];

    $verifica = $conexao->prepare("SELECT * FROM atividades_usuarios WHERE id_usuario = ? AND id_atividade = ?  ");
    $verifica->bind_param("ii", $id, $id_atividade);
    $verifica->execute();
    $result = $verifica->get_result();
    if ($result->num_rows == 0) {
        $hoje = date('Y-m-d');
        $entrega = $conexao->prepare("INSERT INTO `atividades_usuarios` ( `id_usuario`, `id_atividade`, `st_status`,dt_entrega) VALUES (?, ?, 'feito',?)");
        $entrega->bind_param("iis", $id, $id_atividade, $hoje);
        $entrega->execute();
        header("Location: " . $_SERVER['PHP_SELF']);
        $_SESSION['filtro'] = $filtroAtual;
        exit;
    } else {
        header("Location: " . $_SERVER['PHP_SELF']);
        $entrega = $conexao->prepare("DELETE FROM atividades_usuarios WHERE id_usuario = ? AND id_atividade = ?");
        $entrega->bind_param("ii", $id, $id_atividade);
        $entrega->execute();
        $_SESSION['filtro'] = $filtroAtual;

    }
}


$tema = $_COOKIE['tema'] ?? 'light';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lição</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link id="tema" rel="stylesheet" href="../css/<?php echo $tema ?>.css">


</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#">📚 Organizador de Atividades</a>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-light mr-3">
                        Olá, <?= $_SESSION['nome'] ?? 'Aluno' ?>!
                    </span>

                    <div onclick="trocarTema()" class="quadro">
                        <div class="bolaa bola-ani bola">

                        </div>
                    </div>

                    <div class="dropdown mr-3">

                        <button class="btn btn-outline-light position-relative" type="button" id="dropdownNotificacoes"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="bi bi-bell-fill"></i>
                            <div id="contador"></div>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="dropdownNotificacoes"
                            style="width:360px; max-height:420px; overflow-y:auto;">

                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>🔔 Notificações</span>

                            </h6>

                            <div class="dropdown-divider"></div>



                            <div id="noti"></div>

                            <div class="dropdown-divider"></div>

                            <a href="#" class="dropdown-item text-center text-primary font-weight-bold">
                                Ver todas as notificações
                            </a>

                        </div>

                    </div>
                    <a href="../includes/logout.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Sair</span>
                    </a>
                </div>
            </div>

        </nav>
    </header>

    <main class="container my-4">
        <form method="get" class="filtros">

            <a href="?filtro=todos" class="btn btn-filtro filtro <?= $filtro_todos ?>" name="filtro" value="todos">
                <i class="bi bi-list"></i> Todos
            </a>

            <a href="?filtro=pendentes" class="btn btn-filtro filtro <?= $filtro_pendente ?>" name="filtro"
                value="pendentes">
                <i class="bi bi-clock"></i> Pendentes
            </a>

            <a href="?filtro=atrasadas" class="btn btn-filtro filtro <?= $filtro_atrasadas ?>" name="filtro"
                value="atrasadas">
                <i class="bi bi-exclamation-circle"></i> Atrasadas
            </a>

            <a href="?filtro=entregues" class="btn btn-filtro filtro <?= $filtro_entregues ?>" name="filtro"
                value="entregues">
                <i class="bi bi-check-circle"></i> Entregues
            </a>

        </form>
        <div class="row">

            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($teste = $resultado->fetch_assoc()): ?>

                    <?php

                    if ($contador == true) {
                        $status = $teste['st_status'];
                    }
                    if (isset($cor_card) && $cor_card == true) {

                        $badge = 'badge-entregue-cinza';
                        $textoBadge = 'Entregue';
                        $heder = 'card-entregue-cinza';
                        $corData = 'text-black';



                    } else {

                        $dt_entrega = $teste['dt_entrega'];

                        if ($dt_entrega < $hoje) {
                            $badge = 'badge-atrasada';
                            $textoBadge = 'Atrasada!';
                            $heder = 'atrasada';
                            $corData = 'text-danger';
                        } else if ($dt_entrega == $hoje) {
                            $badge = 'badge-atencao';
                            $textoBadge = 'Hoje!';
                            $heder = 'atencao';
                            $corData = 'text-warning';
                        } else {
                            $badge = 'badge-fazer';
                            $textoBadge = 'No prazo!';
                            $heder = 'para-fazer';
                            $corData = 'text-success';
                        }
                    }


                    if (isset($status) && $status == "feito") {
                        $entregue = "btn-success";
                        $entreguetexto = "Entregue <i class='bi bi-check-lg'></i>";
                        $entregue_estilo = true;
                        $badge = 'badge-entregue-cinza';
                        $textoBadge = 'Entregue';
                        $heder = 'card-entregue-cinza';
                        $corData = 'text-black';
                        $texto_obs = true;

                    }
                    ?>


                    <div class="col-12 col-sm-6 col-lg-4 mt-3">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between align-items-center <?php echo $heder; ?>">
                                <span><?php echo $teste['nm_materia']; ?></span>
                                <span class="badge  <?php echo $badge; ?> "
                                    style="margin-left:1px;"><?php echo $textoBadge; ?></span>
                            </div>

                            <div class="card-body teste">
                                <div class="mb-3">
                                    <small>Entrega em:</small>
                                    <h5 class="<?php echo $corData; ?> fw-bold mb-1">
                                        <?php echo date('d/m/Y', strtotime($teste['dt_entrega'])); ?>
                                    </h5>
                                    <small><?php
                                    if (isset($cor_card) && $cor_card == true) {

                                        echo '(Atividade Já Entregue)';




                                    } else if (isset($texto_obs) && $texto_obs == true) {


                                        echo '(Atividade Já Entregue)';


                                        $texto_obs = false;
                                    } else {
                                        $dias = (strtotime($teste['dt_entrega']) - strtotime($hoje)) / 86400;
                                        if ($dias < 0)
                                            echo '(' . abs($dias) . ' dias atrasada)';
                                        elseif ($dias == 0)
                                            echo '(Vence Hoje)';
                                        elseif ($dias == 1)
                                            echo '(Falta ' . (int) $dias . ' dia)';
                                        else
                                            echo '(Faltam ' . (int) $dias . ' dias)';
                                    }

                                    ?></small>
                                </div>

                                <p class="card-text">
                                    <?php echo htmlspecialchars($teste['ds_atividade']); ?>
                                </p>
                            </div>

                            <div class="card-footer">
                                <form action="" method="post">

                                    <input type="hidden" class="form-control" value="<?= $teste['cd_atividade'] ?>"
                                        name="id_atividade" required>
                                    <button class="btn <?= $entregue ?> btn-block" type="submit" name="entrega">
                                        <?= $entreguetexto ?>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                    <?php
                    if (isset($entregue_estilo) && $entregue_estilo == true) {


                        $entregue = "btn-outline-success";
                        $entreguetexto = "Entregar";
                        $badge = 'badge-entregue-cinza';
                        $textoBadge = 'Entregue';
                        $heder = 'card-entregue-cinza';
                        $corData = 'text-black';
                    } ?>
                <?php endwhile; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="lead">Nenhuma atividade encontrada.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>



    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="EmBreveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="EmBreveLabel">Função em breve</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-clock-history" style="font-size: 3.5rem; color: #ffc107;"></i>
                    <h4 class="mt-3 mb-3">Em desenvolvimento...</h4>
                    <p class="text-muted">
                        A funcionalidade de entregas de atividades ainda não está disponível.<br>
                        Estamos trabalhando para liberar em breve!
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">Entendi</button>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>


        function trocarTema() {
            const css = document.getElementById("tema");

            if (css.href.includes("light.css")) {
                css.href = "../css/dark.css";
                document.cookie = "tema=dark; path=/; max-age=31536000";
            } else {
                css.href = "../css/light.css";
                document.cookie = "tema=light; path=/; max-age=31536000";
            }
        }

        const sino = document.getElementById("dropdownNotificacoes");

        sino.addEventListener("click", function () {

            fetch("../includes/marcar_notificacoes.php", {
                method: "POST"
            });
            document.getElementById("contador-Noti").style.display = "none";


        });

        function carregarNotificacoes() {

            fetch("../includes/busca_notificacoes.php")

                .then(response => response.text())

                .then(html => {

                    document.getElementById("noti").innerHTML = html;

                });

        }
        function carregarcontador() {

            fetch("../includes/contador_notificacoes.php")

                .then(response => response.text())

                .then(html => {

                    document.getElementById("contador").innerHTML = html;

                });

        }

        setInterval(() => {
            carregarcontador()
            carregarNotificacoes();

        }, 3000);
        carregarcontador()
        carregarNotificacoes();

    </script>
</body>

</html>