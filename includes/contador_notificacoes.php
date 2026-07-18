<?php
session_start();

include_once('config/config.php');

$id = $_SESSION['id'];

$sql = $conexao->prepare("
    SELECT COUNT(*) AS total
    FROM notificacoes n

    LEFT JOIN notificacoes_lidas nl
    ON nl.id_notificacao = n.id
    AND nl.id_usuario = ?

    WHERE 
    (
        (n.id_usuario = ? AND n.lida = 0)
        OR
        (n.id_usuario = 0 AND nl.id IS NULL)
    )
");

$sql->bind_param("ii", $id, $id);
$sql->execute();

$result = $sql->get_result();

$total = $result->fetch_assoc()['total'];

if ($total > 0):
?>

<span class="badge badge-danger position-absolute"
      id="contador-Noti"
      style="top:-6px; right:-6px;">
    <?= $total ?>
</span>

<?php endif; ?>