<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
		
	if($_SESSION['acesso'] == 1){
		
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$id = (isset($_POST['id'])) ? $_POST['id'] : '';
		
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	else{	
	
	//Deletar Usuário
	$SQL = "DELETE FROM relatorio WHERE usuario = :usuario";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':usuario', $id, PDO::PARAM_INT); 
	$SQL->execute(); 
		
	if(empty($SQL)){
		echo "<span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Erro\">Erro</span>";
	}
	else{
		echo "<span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Sucesso\">Sucesso</span>";
	}
		
		
	}
	}
	
	}else{
		echo Redirecionar('index.php');
	}

	}else{
		echo Redirecionar('login.php');
	}	

?>