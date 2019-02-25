<?php
	include("conexao.php");
	include_once("functions.php");

	if(ProtegePag() == true){
	
	if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
		
	$CadUserOnline = $_SESSION['id'];
	$ArvoreAdminOnline = ArvoreRev($CadUserOnline);
		
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$id = (isset($_POST['id'])) ? $_POST['id'] : '';
	
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(!in_array($id, $ArvoreAdminOnline)) {
		echo MensagemAlerta('Erro', 'Este usuário não pertence a você!', "danger");
	}
	else{	
	
	$ArvoreRev = ArvoreRev($id);
	
	if(!empty($ArvoreRev)){
		for($i = 0; $i < count($ArvoreRev); $i++){
		$ExcluirSSHUser = ExcluirSSHUser($ArvoreRev[$i]);
		$ExcluirPorUsuario = ExcluirPorUsuario($ArvoreRev[$i]);
		}
	}
	
	//Deletar Usuário
	$ExcluirPorUsuario = ExcluirPorUsuario($id);
	$SQL = "DELETE FROM login WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT); 
	$SQL->execute(); 
		
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao deletar o usuário', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Usuário deletado com sucesso', "success", "index.php?p=revendedor");
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