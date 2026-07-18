<?php


$dbhost='localhost';
$dbUsername='root';
$dbPassword='';
$dbName='atividades';

$conexao=new mysqli($dbhost,$dbUsername,$dbPassword,$dbName,);

//if($conexao->connect_error){
  //echo"Erro";
//}
//else{
//echo"certo";
//}

$conexao->set_charset("utf8mb4");


?>

