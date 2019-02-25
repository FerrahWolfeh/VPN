<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

$camposMarcados = $_POST['camposMarcados'];	
$status = $_POST['status'];	
$titulo = $_POST['titulo'];
$texto = $_POST['texto'];
$tipo = $_POST['tipo'];
$url = $_POST['url'];
$fa = $_POST['fa'];
$bt1 = 'Sim';
$bt2 = 'Não';

echo MensagemConfirmarJS2($camposMarcados, $status, $titulo, $texto, $tipo, $url, $fa, $bt1, $bt2);
            
}else{
	echo Redirecionar('login.php');
}
?>