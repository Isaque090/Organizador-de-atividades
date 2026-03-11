<?php
session_start();
$elemento = "";
include_once('config.php');
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $pesquisa = $conexao->prepare("SELECT * FROM usuarios WHERE ds_email = ? and ds_senha= ? ");
    $pesquisa->bind_param("ss", $email, $senha);
    $pesquisa->execute();

    $resultado = $pesquisa->get_result();
    $nivel = $resultado->fetch_assoc();
    if ($resultado->num_rows > 0) {
     $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;  
      $_SESSION['id'] =$nivel['id'];
            header('Location: index.php');
          
     
    
 
    } else {

        $elemento = "<div class='errado'>Dados invalidos</div>";

    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;

            justify-content: center;
        }

        .a {
            align-items: center;

            justify-content: center;
        }

        .botao {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="a">
        <form action="" method="post">
            <label>Email</label>
            <input class="form-control" name="email" type="text" id="iiinput" maxlength="20" required>
            <label for="senha">Senha</label>

            <input class="form-control" name="senha" type="password" id="iiinput" maxlength="20" required>
            <button name="login" id="botao" class="botao btn-dark" type="submit">Entrar</button>
        </form>

        <?php echo $elemento ?>
    </div>

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