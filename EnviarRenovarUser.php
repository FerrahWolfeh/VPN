<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
		
	if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

	$CadUserOnline = $_SESSION['id'];
	$ArvoreAdminOnline = ArvoreUser($CadUserOnline);
		
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$id = (isset($_POST['id'])) ? $_POST['id'] : '';
	$CadUser = $_SESSION['id'];
	
	$SQLDes = "SELECT cota FROM login WHERE id = :id";
	$SQLDes = $banco->prepare($SQLDes);
	$SQLDes->bindParam(':id', $CadUser, PDO::PARAM_STR);
	$SQLDes->execute();
	$LnDes = $SQLDes->fetch();
		
	if(empty($id)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif( (!in_array($id, $ArvoreAdminOnline)) && ($_SESSION['acesso'] != 1) ) {
		echo MensagemAlerta('Erro', 'Este usuário não pertence a você!', "danger");
	}
	elseif($LnDes['cota'] < 1){
		echo MensagemAlerta('Erro', 'Você não tem saldo para renovar o usuário, compre um novo pacote!', "danger");
	}
	else{	
	
	$SQLUser = "SELECT login, senha, expiredate, operadora FROM login WHERE id = :id";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':id', $id, PDO::PARAM_INT);
	$SQLUser->execute();
	$Ln = $SQLUser->fetch();
	$login = $Ln['login'];
	$senha = $Ln['senha'];
	
	//Seleciona o servidor
	$SQLServer = "SELECT * FROM servidor WHERE nome = :nome";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->bindParam(':nome', $Ln['operadora'], PDO::PARAM_STR);
	$SQLServer->execute();
	$LnServer = $SQLServer->fetch();
	
	$TimeAtual = time();
	$TempoAtual = $Ln['expiredate'] > $TimeAtual ? $Ln['expiredate'] : $TimeAtual;
	$TempoRenovar = $TempoAtual + (3600 * 24 * 30);
	$DataExpirar = date('Y-m-d', $TempoRenovar);
	$DataExibir = date('d/m/Y', $TempoRenovar);
	
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, 'useradd -e '.$DataExpirar.' -M -s /bin/false '.trim($login).'');
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
	
	$AlterarSenha = ssh2_exec($connection, 'sudo usermod -p $(openssl passwd -1 '.escapeshellarg(trim($senha)).') '.trim($login).'');
	stream_set_blocking($AlterarSenha, true);
	stream_set_timeout($AlterarSenha, 15);
	fclose($AlterarSenha);
	
	$comando = trim("sudo chage -E ".$DataExpirar." $login");
	$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
	ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
	$stream = ssh2_exec($connection, $comando);
	stream_set_blocking($stream, true);
	stream_set_timeout($stream, 15);
	fclose($stream);
		
	//Atualizar Cota
	$CotaAtualizar = $LnDes['cota'] - 1;
	$SQL = "UPDATE login SET
			cota = :cota
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':cota', $CotaAtualizar, PDO::PARAM_INT);
	$SQL->bindParam(':id', $CadUser, PDO::PARAM_INT);
	$SQL->execute();
	
	$SQLU = "UPDATE login SET
			expiredate = :expiredate
            WHERE id = :id";
	$SQLU = $banco->prepare($SQLU);
	$SQLU->bindParam(':expiredate', $TempoRenovar, PDO::PARAM_STR);
	$SQLU->bindParam(':id', $id, PDO::PARAM_INT);
	$SQLU->execute();
	
	//Salva no Relatório//
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
	$SQL->bindParam(':usuario', $id, PDO::PARAM_STR); 
	$SQL->bindParam(':data', $data, PDO::PARAM_STR); 
	$SQL->execute(); 
	//Salva no Relatório//
		
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao renovar o usuário.', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Renovado com sucesso.<br>Usuário válido até '.$DataExibir.'', "success", "index.php?p=usuario");
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