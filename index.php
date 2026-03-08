<?php
include_once('config.php');

$pesquisa = $conexao->prepare("SELECT * FROM atividades ORDER BY dt_entrega asc ");

$pesquisa->execute();
$resultado = $pesquisa->get_result();


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lição</title>
    <style>
        body {
            align-items: center;
            min-height: 100vh;

        }

        main {
            display: flex;
        }

        .card {
            width: 370px;
            min-height: 320px;
            border-radius: 12px;
            border: none;
            margin: 2%;
        }

        .card:hover {

            transition: all 0.5s ease;
            transform: translateY(-20px);
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

            border-radius: 12px 12px 0 0;
            font-weight: bold;
        }

        .card-footer {
            background-color: transparent !important;
            border: none !important;
            padding-bottom: 20px !important;
            display: flex !important;
            gap: 15px !important;
            justify-content: end !important;
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
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body style="background-color: aliceblue;">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarConteudo"
                aria-controls="navbarConteudo" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarConteudo">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(atual)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Serviços</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mais
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Blog</a>
                            <a class="dropdown-item" href="#">Contato</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Algo mais</a>
                        </div>
                    </li>
                </ul>

            </div>
        </nav>
    </header>
    <main>




  <div class="row">
        <?php if ($resultado->num_rows > 0): ?>
       
                <?php


                $posicao = 0;



                while ($teste = $resultado->fetch_assoc()): ?>

                    <?php $hoje = date('Y-m-d');
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
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center <?php echo $heder; ?>">
                            <span> <?php echo $teste['ds_materia']; ?></span>
                            <span class="badge  <?php echo $badge; ?>"> <?php echo $textoBadge; ?></span>
                        </div>


                        <div class="card-body ">

                            <div class="mb-3">
                                <small class="">Entrega em:</small>
                                <h5 class=" <?php echo $corData; ?> fw-bold mb-1">
                                    <?php echo date('d/m/Y', strtotime($teste['dt_entrega'])); ?>
                                </h5>
                                <small class=""><?php
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
                                <?php echo $teste['ds_atividade']; ?>
                            </p>

                       
                        </div>


                        <div class="card-footer  ">
                            <button class="btn btn-outline-danger">
                                <i class="bi bi-envelope"></i> Enviar e-mail
                            </button>

                            <button class="btn btn-outline-success">
                                Entregar
                            </button>
                        </div>
                    </div>
           
                <?php $posicao++; ?>

            <?php endwhile; ?>

        <?php endif; ?>
     </div>
    </main>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>

</html>