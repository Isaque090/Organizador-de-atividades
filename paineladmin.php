<?php
include_once('config.php');


$pesquisa = $conexao->prepare("SELECT  cd_atividade, id_materia, ds_atividade, dt_entrega, (SELECT nm_materia  FROM materias WHERE cd_materia = atividades.id_materia ) AS nm_materia FROM atividades ORDER BY dt_entrega asc ");
$pesquisa->execute();
$resultado = $pesquisa->get_result();



$atrasadas = 0;
$vencemHoje = 0;

$hoje = date('Y-m-d');

if ($resultado->num_rows > 0) {


    while ($teste = $resultado->fetch_assoc()) {
        $dt_entrega = $teste['dt_entrega'];
        if ($dt_entrega < $hoje) {
            $atrasadas++;
        }

        if ($dt_entrega == $hoje) {
            $vencemHoje++;
        }

    }
    $resultado->data_seek(0);


}



$pesquisamateria = $conexao->prepare("SELECT * FROM materias ");
$pesquisamateria->execute();
$resultadomateria = $pesquisamateria->get_result();

$pesquisamateriaa = $conexao->prepare("SELECT * FROM materias ");
$pesquisamateriaa->execute();
$resultadomateriaa = $pesquisamateriaa->get_result();




if (isset($_POST['adicionar_tarefa'])) {
    //teste para saber se esta recebendo o valor certo
    $materia = $_POST['categoria'];
    $atividade = $_POST['des_atividade'];
    $dataEntrega = $_POST['data'];

    $inserir = $conexao->prepare("  INSERT INTO `atividades` ( `id_materia`, `ds_atividade`, `dt_entrega`) VALUES ( ?, ?, ?) LIMIT 1");
    $inserir->bind_param("iss", $materia, $atividade, $dataEntrega);
    $inserir->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


if (isset($_POST['atualizar_tarefa'])){
 $materia = $_POST['categoria'];
    $atividade = $_POST['des_atividade'];
    $dataEntrega = $_POST['data'];
    $cd_atividade=$_POST['id'];

    $inserir = $conexao->prepare("     UPDATE `atividades` SET `id_materia`= ? ,`ds_atividade`= ?,`dt_entrega`= ? WHERE `cd_atividade`= ? LIMIT 1");
    $inserir->bind_param("issi", $materia, $atividade, $dataEntrega,$cd_atividade);
    $inserir->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


if (isset($_POST['confirmar_exclusao'])){

    $cd_atividade=$_POST['atividade_id'];

    $inserir = $conexao->prepare("DELETE FROM atividades WHERE `atividades`.`cd_atividade` =? LIMIT 1" );
    $inserir->bind_param("i", $cd_atividade);
    $inserir->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$usuarios = $conexao->prepare("SELECT * FROM `usuarios`");
$usuarios->execute();
$resultadousuarios = $usuarios->get_result();








?><!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            background-color: rgb(255, 255, 250);
        }



        .sidebar h2 {
            text-align: center;
            margin: 0 0 20px 0;
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            color: white;
            display: block;
            cursor: pointer;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #575757;
            color: rgb(217, 17, 17);
        }

        .content {
            margin-left: 220px;
            padding: 30px;
            flex: 1;
            background: #f8f9fa;
            min-height: 100vh;
        }


        .secao {
            display: none;
        }


        .secao.ativa {
            display: block;
        }

        .licao {

            background-color: rgb(13, 110, 253);

        }



        .total h5 {
            margin-top: 10px;
        }

        .total h3 {
            margin-top: 70px;

        }

        .total:hover {
            transform: translateY(-10px);
        }

        .total {


            border-radius: 10px;
            text-align: center;
            color: white;
            transition: all 0.3s ease-in-out;
            width: 100% !important;
            height: 120px;
            margin-left: 0 !important;
            margin-top: 0 !important;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }


        .entregue {
            background-color: rgb(25, 135, 84);
        }

        .teste {
            width: 100%;
            height: 50px;
            background-color: rgb(255, 255, 255);
            border-radius: 10px;
            text-align: left;

        }

        .teste h4 {
            padding-top: 10px;
            padding-left: 10px;
            font-weight: bold;
        }

        .geral {
            margin-top: 30px;
        }

        .adicionar {
            height: 50px;
            width: 230px;
            background-color: rgb(0, 51, 255);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 4px;

            margin-left: 80% !important;
        }

        .texto {
            padding-top: 8px;

            margin-left: auto;
            margin-right: auto;

        }

        .texto i {
            font-size: 1.4rem;
            padding-left: 20px;
            margin-top: -3px !important;


        }

        .adicionar:hover {
            background-color: rgba(1, 40, 194, 0.72);
        }

        .sidebar {
            min-height: 100vh;
            width: 220px;
            background-color: #333;
            color: white;
            position: fixed;

            left: 0;
            transition: transform 0.3s ease;
            z-index: 1000;
            padding-top: 20px;
        }



        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0 !important;
                padding: 60px 15px 15px 15px;
            }


            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 900;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;

            }

            .textoVisao {
                width: 240px !important;
            }

            .adicionar {

                margin-left: 40% !important;
            }
        }

        .textoVisao {
            width: 240px !important;
        }


        .criar-usuario {
            border-radius: 10px;
        }

        .usuarios {

            justify-content: space-between !important;
        }

        hr {
            background-color: #cdcdcd8a;
        }


        th {
            background-color: #6c5ce7;
            color: white;

        }

        tr:nth-child(even) {
            background-color: #f5f5ff;
        }

        tr {
            background-color: #ffffff;
            padding: 1px !important;

        }

        tr:hover {
            background-color: #f0f0ff;
            cursor: default;
        }

        th,
        td {
            padding: 6px 4px;
            text-align: left;
            border-bottom: 1px solid #ddd;

            text-align: center;
            overflow: hidden;
            width: 100%;
            border: 1px solid #e9ecef;

        }

        table {
            margin-left: auto;
            margin-right: auto;
            width: 100% !important;
            table-layout: fixed !important;
            margin-top: 40px;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
    </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
         <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
         
</head>

<body>

    <button class="btn btn-dark d-lg-none" type="button" id="sidebarToggle"
        style="position: fixed; top: 10px; right: 10px; z-index: 1050;">
        <i class="bi bi-list" style="font-size: 1.8rem;"></i>
    </button>
    <div class="sidebar-overlay d-lg-none"></div>
    <div class="sidebar" id="sidebar">
        <h2>Menu</h2>
        <a data-target="home" class="active">Geral</a>
        <a data-target="sobre">Usuarios</a>
        <a data-target="servicos">teste 2</a>
        <a data-target="contato">teste 3</a>
    </div>

    <div class="content">

        <div id="home" class="secao ativa">
            <div class="teste  bg-white shadow-lg">
                <h4>Painel Admin</h4>

            </div>

            <div class="geral">
                <div class="textoVisao">
                    <h1>Visão geral</h1>
                    <hr>
                </div>
                <div class="row ml-2">
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="total licao  bg-primary">
                            <h5>Total de Atividades</h5>
                            <h2>
                                <?php echo $resultado->num_rows;
                                ?>
                            </h2>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="total bg-danger ">
                            <h5>Atrasadas</h5>
                            <h2>


                                <?= $atrasadas ?>

                            </h2>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="total bg-warning text-dark ">
                            <h5>Vence Hoje</h5>
                            <h2>
                                <?= $vencemHoje ?>
                            </h2>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="total bg-success   ">

                            <!-- Sera feito a implementação depois de colocar  a função de entregar -->
                            <h5>Entregas Hoje</h5>
                            <h2>10</h2>
                        </div>
                    </div>
                </div>
                <!-- adicionar tabela para mostrar a  as tividades existentes, tambem colocar para editar  algum campo da atividade ou excluir a atividade-->
                <h2 style="margin-top:30px;">Atividades</h2>
                <button class="adicionar row" type="button" data-toggle="modal" data-target="#adicionartarefa">
                    <div class="texto  row">
                        <h5>Adicionar tarefa</h5> <i class="bi bi-plus-lg"></i>
                    </div>
                </button>

                <table>
                    <thed class="theder">
                        <tr>
                            <th>Materia</th>
                            <th>Descrição da atvidade</th>
                            <th>data de entrega</th>
                            <th>Ações</th>
                        </tr>
                    </thed>
                    <tbody>
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php



                            while ($row = $resultado->fetch_assoc()): ?>

                                <tr>

                                    <td style="<?= $css; ?>"><?= $row['nm_materia']; ?></td>
                                    <td style="max-width: 280px;" data-toggle="tooltip" data-placement="top"
                                        title="<?= htmlspecialchars($row['ds_atividade']) ?>">
                                        <div class="text-truncate">
                                            <?= htmlspecialchars($row['ds_atividade']) ?>
                                        </div>
                                    </td>
                                    <td class="ponto"><strong><?= date('d/m/Y', strtotime($row['dt_entrega'])); ?></strong></td>
                                    <td>
                                        <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deletar_atividade"
                                            data-id="<?= htmlspecialchars($row['cd_atividade']) ?>"
                                          >
                                            <i class="material-icons" style="font-size:1rem;">delete</i> Excluir
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#editaratividade"
                                            data-id="<?= $row['cd_atividade'] ?>"
                                            data-materia="<?= htmlspecialchars($row['id_materia']) ?>"
                                            data-entrega="<?= htmlspecialchars($row['dt_entrega']) ?>"
                                            data-atividade="<?= htmlspecialchars($row['ds_atividade']) ?>">
                                            <i class="material-icons" style="font-size:1rem;">edit</i> Editar
                                        </button>

                                    </td>
                                </tr>



                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>


            </div>
        </div>

        <div id="sobre" class="secao">

            <div class="usuarios row">
                <h1>Gerenciamento de Usuários</h1>

                <button class="criar-usuario btn btn-primary">Criar Usuario</button>
            </div>

            <hr>

            <div class="row ml-2 mt-4">
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="total bg-info ">
                        <h5>Total de Usuarios</h5>
                        <h2>


                        
                              <?php echo  $resultadousuarios->num_rows;
                                ?>
                        </h2>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="total bg-warning text-dark ">
                        <h5>Com Atrasos</h5>
                        <h2>
                            <?= $vencemHoje ?>
                        </h2>
                    </div>
                </div>



                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="total bg-success   ">


                        <h5>Admins</h5>
                        <h2>10</h2>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="total licao  bg-primary">
                        <h5>Novos</h5>
                        <h2>
                            <?php echo $resultado->num_rows;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div id="servicos" class="secao">

        </div>

        <div id="contato" class="secao">

        </div>
    </div>


    <div class="modal fade" id="adicionartarefa">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Nova Tarefa</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">

                        <div class="form-group">
                            <label>Data de Entrega</label>
                            <input class="form-control" name="data" type="date" min="2026-01-01" required>
                        </div>

                        <div class="form-group">
                            <label>Descrição da atividade</label>

                            <textarea name="des_atividade" class="form-control" rows="10" cols="38" required></textarea>

                        </div>

                        <div class="form-group">
                            <label>Materia</label>
                            <select class="form-control" name="categoria" required>
                                <option value="">Selecione</option>
                                <?php




                                while ($materia = $resultadomateria->fetch_assoc()): ?>

                                    <option value="<?= $materia['cd_materia'] ?>"> <?= $materia['nm_materia'] ?></option>
                                <?php endwhile; ?>


                            </select>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="adicionar_tarefa" class="btn btn-success">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editaratividade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar atividade</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">

                        <div class="form-group">
                            <label>Data de Entrega</label>
                            <input class="form-control" id="entrega-modal" name="data" type="date" min="2026-01-01"
                                required>
                        </div>


                        <div class="form-group">
                            <label>Descrição da atividade</label>

                            <textarea name="des_atividade" id="atividade-modal" class="form-control" rows="10" cols="38"
                                required></textarea>

                        </div>


                        <div class="form-group">
                            <label>Materia</label>
                            <select class="form-control" name="categoria" id="materia-modal" required>
                                <option value="">Selecione</option>
                                <?php




                                while ($materia = $resultadomateriaa->fetch_assoc()): ?>

                                    <option value="<?= $materia['cd_materia'] ?>"> <?= $materia['nm_materia'] ?></option>
                                <?php endwhile; ?>


                            </select>
                        </div>



                        <div class="form-group" style="display:none;">
                            <label>id</label>
                            <input type="text" class="form-control" id="atividade-id-modal" name="id" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="atualizar_tarefa" class="btn btn-success">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="deletar_atividade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Excluir Atividade</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir essa atividade?</p>
                    <p>Esta ação não pode ser desfeita.</p>
                    <form method="post" action="">
                        <input type="hidden" name="atividade_id" id="id-modal" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="confirmar_exclusao" class="btn btn-danger">Excluir
                                Permanentemente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>


        $('#editaratividade').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var materia = button.data('materia');
            var entrega = button.data('entrega');
            var atividade = button.data('atividade');

            var modal = $(this);
            modal.find('#materia-modal').val(materia);
            modal.find('#atividade-id-modal').val(id);
            modal.find('#entrega-modal').val(entrega);
            modal.find('#atividade-modal').val(atividade);

        });

      $('#deletar_atividade').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var titulo = button.data('titulo');

            var modal = $(this);
           
            modal.find('#id-modal').val(id);
        });

        // Toggle sidebar no mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        const mainContent = document.getElementById('main-content');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
        }


        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }


        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
        });
        const menuLinks = document.querySelectorAll('.sidebar a');

        menuLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                menuLinks.forEach(l => l.classList.remove('active'));

                this.classList.add('active');


                const targetId = this.getAttribute('data-target');


                document.querySelectorAll('.secao').forEach(secao => {
                    secao.classList.remove('ativa');
                });


                const secaoAlvo = document.getElementById(targetId);
                if (secaoAlvo) {
                    secaoAlvo.classList.add('ativa');
                }
            });
        });
    </script>

</body>


</html>