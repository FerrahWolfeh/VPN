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
	
	$SQLArq = "SELECT file FROM arquivo WHERE id = :id";
	$SQLArq = $banco->prepare($SQLArq);
	$SQLArq->bindParam(':id', $id, PDO::PARAM_STR);
	$SQLArq->execute();
	$LnArq = $SQLArq->fetch();
	@unlink('download/'.$LnArq['file'].'');
		
	$SQL = "DELETE FROM arquivo WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT); 
	$SQL->execute(); 
		
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao deletar o arquivo de perfil', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Arquivo de Perfil deletado com sucesso', "success", "index.php?p=arquivo-perfil");
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