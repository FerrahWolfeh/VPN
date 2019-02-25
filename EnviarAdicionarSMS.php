<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if($_SESSION['acesso'] == 1){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
$Login = (isset($_POST['Login'])) ? $_POST['Login'] : '';
$Senha = (isset($_POST['Senha'])) ? $_POST['Senha'] : '';
	
	if(empty($Login)){
		echo MensagemAlerta('Erro', 'Usuário é um campo obrigatório.', "danger");
	}
	elseif(empty($Senha)){
		echo MensagemAlerta('Erro', 'Senha é um campo obrigatório.', "danger");
	}
	else{
		
	$SQL = "INSERT INTO sms (
            login,
			senha
            ) VALUES (
            :login,
			:senha
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':login', $Login, PDO::PARAM_STR); 
	$SQL->bindParam(':senha', $Senha, PDO::PARAM_STR); 
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao cadastrar a conta SMS!', "danger", "index.php?p=sms");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Conta SMS adicionada com sucesso!', "success", "index.php?p=sms");
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