<?php
include("conexao.php");
include_once("functions.php");

if(ProtegePag() == true){
	
$titulo = $_POST['titulo'];
$texto = $_POST['texto'];
$tipo = $_POST['tipo'];
$link = $_POST['link'];
$fa = $_POST['fa'];
$bt1 = 'Sim';
$bt2 = 'Não';

echo MensagemConfirmar($titulo, $texto, $tipo, $link, $fa, $bt1, $bt2);
            
}else{
	echo Redirecionar('login.php');
}
?>