<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
		
		
	$LeituraTxt = trim(LeituraTxt());
	$DataAtual = date('Y-m-d');
	$DataAtual = strtotime($DataAtual);
	
	$AcessoUser = 3;
	$AcessoTeste = 4;
	
	if( empty($LeituraTxt) || ($LeituraTxt != $DataAtual) ){
		
	$SQLUser = "SELECT login FROM login WHERE acesso = :AcessoUser OR acesso = :AcessoTeste";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':AcessoUser', $AcessoUser, PDO::PARAM_INT);
	$SQLUser->bindParam(':AcessoTeste', $AcessoTeste, PDO::PARAM_INT);
	$SQLUser->execute();
	
	while($LnUser = $SQLUser->fetch()){	
		$derrubado = 0;
		$SQL = "UPDATE login SET
					derrubado = :derrubado
           			WHERE login = :login";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':derrubado', $derrubado, PDO::PARAM_STR);
		$SQL->bindParam(':login', $LnUser['login'], PDO::PARAM_STR);
		$SQL->execute();	
	}
		
	CriarTxt($DataAtual);
	
	}
	
	
	}else{
		echo Redirecionar('login.php');
	}	

?>