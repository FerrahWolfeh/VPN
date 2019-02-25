<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
		
	if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2) ){

	$CadUserOnline = $_SESSION['id'];
	$ArvoreAdminOnline = ArvoreUser($CadUserOnline);
		
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$id = (isset($_POST['id'])) ? $_POST['id'] : '';
		
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif( (!in_array($id, $ArvoreAdminOnline)) && ($_SESSION['acesso'] != 1) ) {
		echo MensagemAlerta('Erro', 'Este usuário não pertence a você!', "danger");
	}
	else{	
	
	$SQLUser = "SELECT login, operadora FROM login WHERE id = :id";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':id', $id, PDO::PARAM_INT);
	$SQLUser->execute();
	$Ln = $SQLUser->fetch();
	
	//Seleciona o servidor
	$SQLServer = "SELECT * FROM servidor WHERE nome = :nome";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->bindParam(':nome', $Ln['operadora'], PDO::PARAM_STR);
	$SQLServer->execute();
	$LnServer = $SQLServer->fetch();
		
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, 'sudo pkill -KILL -u '.$Ln['login'].'');
	$stream = ssh2_exec($connection, 'sudo userdel '.$Ln['login'].'');
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
	
	//Deletar Usuário
	$SQL = "DELETE FROM login WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT); 
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