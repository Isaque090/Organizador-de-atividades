<?php
session_start();

include_once('config/config.php');
$hoje = date('Y-m-d');
$id = $_SESSION['id'];

$sql = $conexao->prepare("SELECT *FROM notificacoes WHERE id_usuario = ? OR id_usuario = 0 ORDER BY data_criacao DESC LIMIT 10");

$sql->bind_param("i", $id);
$sql->execute();

$result = $sql->get_result();


while($notifi = $result->fetch_assoc()):

?> <a href="#" class="dropdown-item">

                                        <div class="d-flex">
                                            <?php if ($notifi['tipo'] == "atividade") {
                                                $classe = "bi bi-calendar-event-fill text-warning";
                                            } else if ($notifi['tipo'] == "entrega") {
                                                $classe = "bi bi-check-circle-fill text-success";
                                            } else if ($notifi['tipo'] == "atrasada") {
                                                $classe = "bi bi-exclamation-circle-fill text-danger";
                                            } else if ($notifi['tipo'] == "prazo") {
                                                $classe = "bi-clock-fill text-warning";
                                            } else if ($notifi['tipo'] == "sistema") {
                                                $classe = "bi-bell-fill text-secondary";
                                            }





                                            ?>
                                            <div class="mr-3">
                                                <i class="<?= $classe ?>" style="font-size:22px;"></i>
                                            </div>

                                            <div>

                                                <strong><?= htmlspecialchars($notifi['titulo']) ?></strong>

                                                <div class="text-muted small">
                                                    <?= htmlspecialchars($notifi['mensagem']) ?>
                                                </div>

                                                <small class="text-secondary">
                                                    <?php $dias = (strtotime($notifi['data_criacao']) - strtotime($hoje)) / 86400;
                                                    if ($dias >= 0)
                                                        $diaNoti = 'Hoje';
                                                    else if ($dias >= -1)
                                                        $diaNoti = 'Ontem';
                                                    else if ($dias >= -2)
                                                        $diaNoti = 'Anteontem';
                                                    else
                                                        $diaNoti = "Há um tempo";

                                                    ?>
                                                    <?=
                                                        htmlspecialchars($diaNoti . " • " . date('H:i', strtotime($notifi['data_criacao'])));
                                                    ?>
                                                </small>

                                            </div>

                                        </div>

                                    </a>

                                    <div class="dropdown-divider"></div>






<?php endwhile; ?>