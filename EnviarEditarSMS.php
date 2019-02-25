<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if($_SESSION['acesso'] == 1){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$Login = (isset($_POST['Login'])) ? $_POST['Login'] : '';
$Senha = (isset($_POST['Senha'])) ? $_POST['Senha'] : '';
	
	if(empty($id)){
		echo MensagemAlerta('Erro', 'ID é um campo obrigatório.', "danger");
	}
	elseif(empty($Login)){
		echo MensagemAlerta('Erro', 'Usuário é um campo obrigatório.', "danger");
	}
	elseif(empty($Senha)){
		echo MensagemAlerta('Erro', 'Senha é um campo obrigatório!', "danger");
	}
	else{
		
	$SQL = "UPDATE sms SET
			login = :login,
			senha = :senha
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':login', $Login, PDO::PARAM_STR);
	$SQL->bindParam(':senha', $Senha, PDO::PARAM_STR);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT);
	$SQL->execute();
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao editar a conta SMS!', "danger", "index.php?p=sms");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Conta SMS alterada com sucesso!', "success", "index.php?p=sms");
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