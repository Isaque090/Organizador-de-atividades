<?php

session_start();
include_once('config.php');
if ((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    unset($_SESSION['id']);
    header('location:login.php');
}



$pesquisa = $conexao->prepare("SELECT cd_atividade, ds_atividade, dt_entrega, (SELECT nm_materia FROM materias WHERE cd_materia = atividades.id_materia ) AS nm_materia FROM atividades ORDER BY dt_entrega asc ");
$pesquisa->execute();
$resultado = $pesquisa->get_result();

$id=$_SESSION['id'];

if(isset($_POST['entrega'])){
$id_atividade=$_POST['id_atividade'];

$verifica = $conexao->prepare("SELECT * FROM atividades_usuarios WHERE id_usuario = ? AND id_atividade = ?");
$verifica->bind_param("ii", $id, $id_atividade);
$verifica->execute();
$result = $verifica->get_result();
if($result->num_rows == 0){
$entrega=$conexao->prepare("INSERT INTO `atividades_usuarios` ( `id_usuario`, `id_atividade`, `st_status`) VALUES (?, ?, 'feito')");
    $entrega->bind_param("ii", $id, $id_atividade);
    $entrega->execute();
      header("Location: " . $_SERVER['PHP_SELF']);

    exit;
}
else{
    //adicior uma mensagem melhor,esta esta so  para teste
    echo "<script>
 $(document).ready(function(){
    $('#myModal').modal('show');
});

</script>";
}
}

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

    <style>
        body {
            background-color: aliceblue;
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        .card {
            border-radius: 12px;
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .card:hover {
            transition: all 0.5s ease;
            transform: translateY(-12px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .atrasada {
            background-color: rgb(255, 235, 238) !important;
            color: #c62828 !important;
        }

        .para-fazer {
            background-color: rgb(235, 255, 235) !important;
            color: #28c62b !important;
        }

        .atencao {
            background-color: rgb(255, 248, 235) !important;
            color: #c69128 !important;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            font-weight: bold;
            
        }

        .card-footer {
            background-color: transparent !important;
            border: none !important;
            padding: 1.25rem 1.25rem 1.5rem !important;
            display: flex !important;
            gap: 12px !important;
            justify-content: flex-end !important;
        }

        .badge-atrasada {
            background-color: rgb(254, 172, 183) !important;
        }

        .badge-fazer {
            background-color: rgb(172, 254, 183) !important;
        }

        .badge-atencao {
            background-color: rgb(254, 227, 172) !important;
        }

        @media (max-width: 576px) {
            .card {
                margin: 0 auto;
                max-width: 95%;
            }
        }

        .btn{
            transition: all 0.4s ease;
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarConteudo"
                aria-controls="navbarConteudo" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarConteudo">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active"><a class="nav-link" href="#">Home <span
                                class="sr-only">(atual)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Serviços</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mais
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="logout.php">sair</a>
                            <a class="dropdown-item" href="#">Contato</a>
                    
                            <a class="dropdown-item" href="#">Algo mais</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container my-4">
        <div class="filtro "></div>
        <div class="row">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($teste = $resultado->fetch_assoc()): ?>

                    <?php
                    $hoje = date('Y-m-d');
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
                    ?>

                    <div class="col-12 col-sm-6 col-lg-4 mt-3" >
                        <div class="card">

                            <div class="card-header d-flex justify-content-between align-items-center <?php echo $heder; ?>">
                                <span><?php echo $teste['nm_materia']; ?></span>
                                <span class="badge  <?php echo $badge; ?> " style="margin-left:1px;"><?php echo $textoBadge; ?></span>
                            </div>

                            <div class="card-body">
                                <div class="mb-3">
                                    <small>Entrega em:</small>
                                    <h5 class="<?php echo $corData; ?> fw-bold mb-1">
                                        <?php echo date('d/m/Y', strtotime($teste['dt_entrega'])); ?>
                                    </h5>
                                    <small><?php
                                    $dias = (strtotime($teste['dt_entrega']) - strtotime($hoje)) / 86400;
                                    if ($dias < 0)
                                        echo '(' . abs($dias) . ' dias atrasada)';
                                    elseif ($dias == 0)
                                        echo '(Vence Hoje)';
                                    elseif ($dias == 1)
                                        echo '(Falta ' . (int) $dias . ' dia)';
                                    else
                                        echo '(Faltam ' . (int) $dias . ' dias)';
                                    ?></small>
                                </div>

                                <p class="card-text">
                                    <?php echo htmlspecialchars($teste['ds_atividade']); ?>
                                </p>
                            </div>

                            <div class="card-footer">
                             <form action="" method="post">
                              
                            <input type="hidden" class="form-control" value="<?= $teste['cd_atividade'] ?>" name="id_atividade" required>
                                <button class="btn btn-outline-success btn-sm" type="submit" name="entrega">
                                    Entregar
                                </button>
                                </form>
                            </div>

                        </div>
                    </div>

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
</body>

</html>