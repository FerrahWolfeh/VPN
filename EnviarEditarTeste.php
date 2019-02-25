<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
$id = (isset($_POST['id'])) ? trim($_POST['id']) : '';
$EditarUsuario = (isset($_POST['EditarUsuario'])) ? trim($_POST['EditarUsuario']) : '';
$EditarSenha = (isset($_POST['EditarSenha'])) ? $_POST['EditarSenha'] : '';
$EditarNome = (isset($_POST['EditarNome'])) ? $_POST['EditarNome'] : '';
$EditarOperadora = (isset($_POST['EditarOperadora'])) ? $_POST['EditarOperadora'] : '';
$CadUser = $_SESSION['id'];

$SQLUser = "SELECT login FROM login WHERE login = :login AND id != :id";
$SQLUser = $banco->prepare($SQLUser);
$SQLUser->bindParam(':login', $EditarUsuario, PDO::PARAM_STR);
$SQLUser->bindParam(':id', $id, PDO::PARAM_INT);
$SQLUser->execute();
$TotalResul = count($SQLUser->fetchAll());
	
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(empty($CadUser)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(empty($EditarUsuario)){
		echo MensagemAlerta('Erro', 'Usuário é um campo obrigatório!', "danger");
	}
	elseif( substr_count($EditarUsuario, " ") > 0 ) {
		echo MensagemAlerta('Erro', 'Usuário não pode conter espaço.', "danger");
	}
	elseif(preg_match ('/[^a-zA-Z0-9]/i', $EditarUsuario)) {
		echo MensagemAlerta('Erro', 'Usuário não pode conter caracteres especiais.', 'danger');
	}
	elseif(empty($EditarOperadora)){
		echo MensagemAlerta('Erro', 'Operadora é um campo obrigatório!', "danger");
	}
	elseif(count($EditarOperadora) > 1){
		echo MensagemAlerta('Erro', 'Permitido selecionar apenas uma operadora!', "danger");
	}
	elseif($TotalResul > 0){
		echo MensagemAlerta('Erro', 'Usuário já encontra-se em uso!', "danger");
	}
	elseif(empty($EditarSenha)){
		echo MensagemAlerta('Erro', 'Senha é um campo obrigatório!', "danger");
	}
	elseif(empty($EditarNome)){
		echo MensagemAlerta('Erro', 'Nome é um campo obrigatório!', "danger");
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
			nome = :nome,
			login = :login,
			senha = :senha,
			operadora = :operadora,
			bloqueado = :bloqueado
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':nome', $EditarNome, PDO::PARAM_STR);
	$SQL->bindParam(':login', $EditarUsuario, PDO::PARAM_STR);
	$SQL->bindParam(':senha', $EditarSenha, PDO::PARAM_STR);
	$SQL->bindParam(':operadora', $Operadora, PDO::PARAM_STR);
	$SQL->bindParam(':bloqueado', $bloqueado, PDO::PARAM_STR);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT);
	$SQL->execute();
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao editar o teste!', "danger", "index.php?p=criar-teste");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Teste editado com sucesso!', "success", "index.php?p=criar-teste");
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