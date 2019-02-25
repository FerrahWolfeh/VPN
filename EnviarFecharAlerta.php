<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;
	
	$msginterna = "";
	$IDuser = $_SESSION['id'];
	
	$SQL = "UPDATE login SET
			msginterna = :msginterna
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':msginterna', $msginterna, PDO::PARAM_STR);
	$SQL->bindParam(':id', $IDuser, PDO::PARAM_INT);
	$SQL->execute();
	
	$_SESSION['msginterna'] = "";
		
}else{
	echo Redirecionar('login.php');
}	
?>