<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$EditarEmail = (isset($_POST['EditarEmail'])) ? trim($_POST['EditarEmail']) : '';
$EditarUsuario = (isset($_POST['EditarUsuario'])) ? trim($_POST['EditarUsuario']) : '';
$EditarSenha = (isset($_POST['EditarSenha'])) ? $_POST['EditarSenha'] : '';
$EditarNome = (isset($_POST['EditarNome'])) ? $_POST['EditarNome'] : '';
$EditarOperadora = (isset($_POST['EditarOperadora'])) ? $_POST['EditarOperadora'] : '';
$CadUser = $_SESSION['id'];
$Data30Dias = time() + (3600 * 24 * 30);

$SQLUser = "SELECT login FROM login WHERE login = :login";
$SQLUser = $banco->prepare($SQLUser);
$SQLUser->bindParam(':login', $EditarUsuario, PDO::PARAM_STR);
$SQLUser->execute();
$TotalResul = count($SQLUser->fetchAll());

	$SQLDes = "SELECT cota FROM login WHERE id = :id";
	$SQLDes = $banco->prepare($SQLDes);
	$SQLDes->bindParam(':id', $CadUser, PDO::PARAM_STR);
	$SQLDes->execute();
	$LnDes = $SQLDes->fetch();

	if(empty($CadUser)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif($LnDes['cota'] < 1){
		echo MensagemAlerta('Erro', 'Você não tem saldo para adicionar novos usuários, compre um novo pacote!', "danger");
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
		
	$Operadora = implode($EditarOperadora);
	
	//Seleciona o servidor
	$SQLServer = "SELECT * FROM servidor WHERE nome = :nome";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->bindParam(':nome', $Operadora, PDO::PARAM_STR);
	$SQLServer->execute();
	$LnServer = $SQLServer->fetch();
		
	//Atualizar Cota
	$CotaAtualizar = $LnDes['cota'] - 1;
	$SQLa = "UPDATE login SET
			cota = :cota
            WHERE id = :id";
	$SQLa = $banco->prepare($SQLa);
	$SQLa->bindParam(':cota', $CotaAtualizar, PDO::PARAM_INT);
	$SQLa->bindParam(':id', $CadUser, PDO::PARAM_INT);
	$SQLa->execute(); 
		
	$DataExpirar = date('Y-m-d', $Data30Dias);
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, 'sudo useradd -e '.$DataExpirar.' -M -s /bin/false '.trim($EditarUsuario).'');
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
	
	$AlterarSenha = ssh2_exec($connection, 'sudo usermod -p $(openssl passwd -1 '.escapeshellarg(trim($EditarSenha)).') '.trim($EditarUsuario).'');
	stream_set_blocking($AlterarSenha, true);
	stream_set_timeout($AlterarSenha, 15);
	fclose($AlterarSenha);
	
	$DataAtual = date('Y-m-d');
	$DataAtual = strtotime($DataAtual);
		
	$SQL = "INSERT INTO login (
			id_cad,
			nome,
            login,
            senha,
			expiredate,
			operadora,
			data,
			email
            ) VALUES (
			:id_cad,
			:nome,
            :login,
            :senha,
			:expiredate,
			:operadora,
			:data,
			:email
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_INT); 
	$SQL->bindParam(':nome', $EditarNome, PDO::PARAM_STR); 
	$SQL->bindParam(':login', $EditarUsuario, PDO::PARAM_STR); 
	$SQL->bindParam(':senha', $EditarSenha, PDO::PARAM_STR); 
	$SQL->bindParam(':expiredate', $Data30Dias, PDO::PARAM_STR); 
	$SQL->bindParam(':operadora', $Operadora, PDO::PARAM_STR); 
	$SQL->bindParam(':data', $DataAtual, PDO::PARAM_STR); 
	$SQL->bindParam(':email', $EditarEmail, PDO::PARAM_STR); 
	$SQL->execute(); 
	
	//Salva no Relatório//
	$SQLUser = "SELECT id FROM login WHERE login = :login";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':login', $EditarUsuario, PDO::PARAM_STR);
	$SQLUser->execute();
	$LnUser = $SQLUser->fetch();
	
	$mes = date('n');
	$Ano = date('Y');
	$data = time();
	$SQL = "INSERT INTO relatorio (
			id_cad,
			mes,
            ano,
            usuario,
			data
            ) VALUES (
			:id_cad,
			:mes,
            :ano,
            :usuario,
			:data
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_INT); 
	$SQL->bindParam(':mes', $mes, PDO::PARAM_STR); 
	$SQL->bindParam(':ano', $Ano, PDO::PARAM_STR); 
	$SQL->bindParam(':usuario', $LnUser['id'], PDO::PARAM_STR); 
	$SQL->bindParam(':data', $data, PDO::PARAM_STR); 
	$SQL->execute(); 
	//Salva no Relatório//
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao cadastrar o usuário!', "danger", "index.php?p=usuario");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Usuário adicionado com sucesso!', "success", "index.php?p=usuario");
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