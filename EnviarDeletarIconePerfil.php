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
	
	$SQLServer = "SELECT imagem FROM icone_perfil WHERE id = :id";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->bindParam(':id', $id, PDO::PARAM_INT); 
	$SQLServer->execute();
	$LnServer = $SQLServer->fetch();
	
	@unlink('img/icone/'.$LnServer['imagem'].'');
		
	$SQL = "DELETE FROM icone_perfil WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT); 
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao deletar o ícone de perfil!', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Ícone de Perfil deletado com sucesso', "success", "index.php?p=icone-perfil");
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