<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

$EditarStatus = (isset($_POST['EditarStatus'])) ? $_POST['EditarStatus'] : 'N';
$EditarTempo = (isset($_POST['EditarTempo'])) ? $_POST['EditarTempo'] : '';
$EditarCopia = (isset($_POST['EditarCopia'])) ? $_POST['EditarCopia'] : 'N';
$EditarEmail = (isset($_POST['EditarEmail'])) ? $_POST['EditarEmail'] : '';
$CadUser = $_SESSION['login'];

	if(empty($CadUser)){
		echo MensagemAlerta("Erro", "Como você fez isso?", "danger");
	}
	elseif(empty($EditarTempo)){
		echo MensagemAlerta("Erro", "Tempo é um campo obrigatório.", "danger");
	}
	elseif( ($EditarCopia == "S") && substr_count($EditarEmail, "@") == 0 || ($EditarCopia == "S") && substr_count($EditarEmail, ".") == 0) {
		echo MensagemAlerta("Erro", "Formato de e-mail inválido.", "danger");
	}
	else{	
	
	$SQLUrlT = "SELECT id FROM urlteste WHERE CadUser = :CadUser";
	$SQLUrlT = $banco->prepare($SQLUrlT);
	$SQLUrlT->bindParam(':CadUser', $CadUser, PDO::PARAM_STR);
	$SQLUrlT->execute();
	$TotalUrlT = count($SQLUrlT->fetchAll());	
	
	if($TotalUrlT > 0){
	$SQL = "UPDATE urlteste SET
			status = :status,
			tempo = :tempo,
			cemail = :cemail,
			email = :email
       	 	WHERE CadUser = :CadUser";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':status', $EditarStatus, PDO::PARAM_STR); 
	$SQL->bindParam(':tempo', $EditarTempo, PDO::PARAM_STR); 
	$SQL->bindParam(':cemail', $EditarCopia, PDO::PARAM_STR); 
	$SQL->bindParam(':email', $EditarEmail, PDO::PARAM_STR); 
	$SQL->bindParam(':CadUser', $CadUser, PDO::PARAM_STR); 
	$SQL->execute();
	}
	else{	
	$SQL = "INSERT INTO urlteste (
			CadUser,
			status,
            tempo,
            cemail,
			email
            ) VALUES (
			:CadUser,
			:status,
            :tempo,
            :cemail,
			:email
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':CadUser', $CadUser, PDO::PARAM_STR); 
	$SQL->bindParam(':status', $EditarStatus, PDO::PARAM_STR); 
	$SQL->bindParam(':tempo', $EditarTempo, PDO::PARAM_STR); 
	$SQL->bindParam(':cemail', $EditarCopia, PDO::PARAM_STR); 
	$SQL->bindParam(':email', $EditarEmail, PDO::PARAM_STR); 
	$SQL->execute(); 
	}
	
	if(empty($SQL)){
		echo MensagemAlerta("Erro", "Ocorreu um erro ao processar a solicitação, por favor, tente mais tarde.", "danger");
	}
	else{
		echo MensagemAlerta("Sucesso", "Configurado com sucesso!", "success", "index.php?p=inicio");
	}
		
		
		}

			}

}else{
	echo Redirecionar('login.php');
}	

?>