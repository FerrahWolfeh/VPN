<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$id = (isset($_SESSION['id'])) ? $_SESSION['id'] : '';
$EditarOperadora = (isset($_POST['EditarOperadora'])) ? $_POST['EditarOperadora'] : '';
	
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(empty($EditarOperadora)){
		echo MensagemAlerta('Erro', 'Operadora é um campo obrigatório!', "danger");
	}
	elseif(count($EditarOperadora) > 1){
		echo MensagemAlerta('Erro', 'Permitido selecionar apenas uma operadora!', "danger");
	}
	else{
		
	$Operadora = trim(implode($EditarOperadora));
	
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
	
	//Remove o usuário
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, 'sudo pkill -KILL -u '.$Ln['login'].'');
	$stream = ssh2_exec($connection, 'sudo userdel '.$Ln['login'].'');
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
	
	//Seleciona o servidor
	$SQLServer = "SELECT * FROM servidor WHERE nome = :nome";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->bindParam(':nome', $Operadora, PDO::PARAM_STR);
	$SQLServer->execute();
	$LnServer = $SQLServer->fetch();
	
	//Adicionar no SSH
	$DataExpirar = date('Y-m-d', $Ln['expiredate']);	
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, 'useradd -e '.$DataExpirar.' -M -s /bin/false '.trim($EditarUsuario).'');
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
	
	$AlterarSenha = ssh2_exec($connection, 'sudo usermod -p $(openssl passwd -1 '.escapeshellarg(trim($EditarSenha)).') '.trim($EditarUsuario).'');
	stream_set_blocking($AlterarSenha, true);
	stream_set_timeout($AlterarSenha, 15);
	fclose($AlterarSenha);
	
	$bloqueado = "N";
	$SQL = "UPDATE login SET
			operadora = :operadora
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':operadora', $Operadora, PDO::PARAM_STR);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT);
	$SQL->execute();
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao editar a operadora!', "danger", "index.php?p=inicio");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Operadora alterada com sucesso!', "success", "index.php?p=inicio");
	}
		
		
	}
}

}else{
	echo Redirecionar('login.php');
}	

?>