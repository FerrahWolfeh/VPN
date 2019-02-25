<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

$id = $_POST['id'];	
$titulo = $_POST['titulo'];
$texto = $_POST['texto'];
$tipo = $_POST['tipo'];
$url = $_POST['url'];
$fa = $_POST['fa'];
$bt1 = 'Sim';
$bt2 = 'Não';

echo MensagemConfirmarJS($id, $titulo, $texto, $tipo, $url, $fa, $bt1, $bt2);
            
}else{
	echo Redirecionar('login.php');
}
?>