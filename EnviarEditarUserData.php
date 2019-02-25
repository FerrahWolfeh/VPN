<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2) ){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
$id = (isset($_POST['id'])) ? trim($_POST['id']) : '';
$DataPremium = (isset($_POST['DataPremium'])) ? trim($_POST['DataPremium']) : '';
$CadUser = $_SESSION['id'];
	
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(empty($CadUser)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(empty($DataPremium)){
		echo MensagemAlerta('Erro', 'Data Premium é um campo obrigatório!', "danger");
	}
	else{
		
	$DataPremium = ConverterData($DataPremium);
			
	$SQLUser = "SELECT login, senha, operadora FROM login WHERE id = :id";
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
	
	//Remove o usuário
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, 'sudo pkill -KILL -u '.$Ln['login'].'');
	$stream = ssh2_exec($connection, 'sudo userdel '.$Ln['login'].'');
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
	
	//Adicionar no SSH
	$DataExpirar = date('Y-m-d', $DataPremium);	
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
			expiredate = :expiredate
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':expiredate', $DataPremium, PDO::PARAM_STR);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT);
	$SQL->execute();
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao alterar a data premium do usuário!', "danger", "index.php?p=usuario");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Data Premium do Usuário alterada com sucesso!', "success", "index.php?p=usuario");
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