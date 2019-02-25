<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
		
	if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

	$CadUserOnline = $_SESSION['id'];
	$ArvoreAdminOnline = ArvoreUser($CadUserOnline);
		
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$id = (isset($_POST['id'])) ? $_POST['id'] : '';
		
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif( (!in_array($id, $ArvoreAdminOnline)) && ($_SESSION['acesso'] != 1) ) {
		echo MensagemAlerta('Erro', 'Este teste não pertence a você!', "danger");
	}
	else{	
	
	$SQLUser = "SELECT login, senha, operadora, expiredate FROM login WHERE id = :id";
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
		
	$DataExpirar = date('Y-m-d', $Ln['expiredate']);
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, 'useradd -e '.$DataExpirar.' -M -s /bin/false '.trim($Ln['login']).'');
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
	
	$AlterarSenha = ssh2_exec($connection, 'sudo usermod -p $(openssl passwd -1 '.escapeshellarg(trim($Ln['senha'])).') '.trim($Ln['login']).'');
	stream_set_blocking($AlterarSenha, true);
	stream_set_timeout($AlterarSenha, 15);
	fclose($AlterarSenha);
	
	$bloqueado = "N";
	$SQL = "UPDATE login SET
			bloqueado = :bloqueado
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':bloqueado', $bloqueado, PDO::PARAM_STR);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT);
	$SQL->execute();
		
	if(empty($SQL)){
		echo "<span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Erro\">Erro</span>";
	}
	else{
		echo "<a class=\"bloquear label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Bloquear\" Onclick=\"BloquearUser('".$id."', 'StatusBloDes".$id."')\"><i class=\"fa fa-lock\"></i></a>";
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