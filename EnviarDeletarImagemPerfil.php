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
	
	$SQLServer = "SELECT imagem FROM imagem_perfil WHERE id = :id";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->bindParam(':id', $id, PDO::PARAM_INT); 
	$SQLServer->execute();
	$LnServer = $SQLServer->fetch();
	
	@unlink('img/perfil/'.$LnServer['imagem'].'');
		
	$SQL = "DELETE FROM imagem_perfil WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT); 
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao deletar a imagem de perfil!', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Imagem de Perfil deletada com sucesso', "success", "index.php?p=imagem-perfil");
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