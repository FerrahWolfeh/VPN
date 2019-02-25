<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
	
	if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

	$CadUser = $_SESSION['id'];
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$rev = (isset($_POST['rev'])) ? $_POST['rev'] : '';
	$camposMarcados = (isset($_POST['camposMarcados'])) ? $_POST['camposMarcados'] : '';
	$status = (isset($_POST['status'])) ? $_POST['status'] : '';
	
	if(empty($camposMarcados)){
		echo MensagemAlerta('Erro', 'Como você fez isso? Ocorreu um erro, por favor, tente mais tarde!', "danger");
	}
	elseif(empty($status)){
		echo MensagemAlerta('Erro', 'Qual o Status? Ocorreu um problema, por favor, tente mais tarde!', "danger");
	}
	else{	
	
		for($i = 0; $i < count($camposMarcados); $i++){
				
				if( $status == "alterarrev" ){
					
						$SQL = "UPDATE login SET
								id_cad = :id_cad
            					WHERE id = :id";
						$SQL = $banco->prepare($SQL);
						$SQL->bindParam(':id_cad', $rev, PDO::PARAM_INT);
						$SQL->bindParam(':id', $camposMarcados[$i], PDO::PARAM_INT);
						$SQL->execute();
					
				}
				elseif( $status == "renovarall" ){
					
						$SQLDes = "SELECT cota FROM login WHERE id = :id";
						$SQLDes = $banco->prepare($SQLDes);
						$SQLDes->bindParam(':id', $CadUser, PDO::PARAM_STR);
						$SQLDes->execute();
						$LnDes = $SQLDes->fetch();
						
						if($LnDes['cota'] < 1){
						echo MensagemAlerta('Erro', 'Você não tem saldo para renovar o usuário, compre um novo pacote!', "danger");
						exit;
						}
						else{
						
						$SQLUser = "SELECT login, expiredate, operadora FROM login WHERE id = :id";
						$SQLUser = $banco->prepare($SQLUser);
						$SQLUser->bindParam(':id', $camposMarcados[$i], PDO::PARAM_INT);
						$SQLUser->execute();
						$Ln = $SQLUser->fetch();
	
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
						$stream = ssh2_exec($connection, 'sudo chage -E '.$DataExpirar.' '.trim($Ln['login']).'');
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
						$SQLU->bindParam(':id', $camposMarcados[$i], PDO::PARAM_INT);
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
						$SQL->bindParam(':usuario', $camposMarcados[$i], PDO::PARAM_STR); 
						$SQL->bindParam(':data', $data, PDO::PARAM_STR); 
						$SQL->execute(); 
						//Salva no Relatório//
						
						}
					
				}
				elseif($status == "excluirall"){
						
						$SQLUser = "SELECT login, operadora FROM login WHERE id = :id";
						$SQLUser = $banco->prepare($SQLUser);
						$SQLUser->bindParam(':id', $camposMarcados[$i], PDO::PARAM_INT);
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
						$SQL->bindParam(':id', $camposMarcados[$i], PDO::PARAM_INT); 
						$SQL->execute(); 
				}
								
		}
	
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro, por favor, procure o administrador!', "danger", "index.php?p=usuario");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Realizado com sucesso', "success", "index.php?p=usuario");
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