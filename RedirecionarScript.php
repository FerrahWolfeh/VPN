<?php
include("conexao.php");
include_once("functions.php");

$url = $_POST['url'];	
echo Redirecionar($url);
?>