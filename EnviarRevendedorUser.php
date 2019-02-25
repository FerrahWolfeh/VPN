<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
		
	if($_SESSION['acesso'] == 1){
	
	$CadUser = $_SESSION['id'];
	$ArvoreAdminOnline = ArvoreUser($CadUser);
		
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$id = (isset($_POST['id'])) ? $_POST['id'] : '';
	
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif( (!in_array($id, $ArvoreAdminOnline)) ) {
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
	
	$acesso = 2;
	$SQLU = "UPDATE login SET
			acesso = :acesso
            WHERE id = :id";
	$SQLU = $banco->prepare($SQLU);
	$SQLU->bindParam(':acesso', $acesso, PDO::PARAM_STR);
	$SQLU->bindParam(':id', $id, PDO::PARAM_INT);
	$SQLU->execute();
		
	if(empty($SQLU)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao tornar o usuário revendedor.', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Usuário convertido revendedor com sucesso!', "success", "index.php?p=usuario");
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