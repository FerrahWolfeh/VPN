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
		
	$SQL = "DELETE FROM sms WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT); 
	$SQL->execute(); 
		
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao deletar a conta SMS', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Conta SMS deletada com sucesso', "success", "index.php?p=sms");
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