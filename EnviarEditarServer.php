<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if($_SESSION['acesso'] == 1){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$IconePerfil = (isset($_POST['IconePerfil'])) ? $_POST['IconePerfil'] : '';
$Nome = (isset($_POST['Nome'])) ? $_POST['Nome'] : '';
$Servidor = (isset($_POST['Servidor'])) ? $_POST['Servidor'] : '';
$Porta = (isset($_POST['Porta'])) ? $_POST['Porta'] : '';
$Usuario = (isset($_POST['Usuario'])) ? $_POST['Usuario'] : '';
$Senha = (isset($_POST['Senha'])) ? $_POST['Senha'] : '';
	
	if(empty($IconePerfil)){
		echo MensagemAlerta('Erro', 'Ícone de Perfil é um campo obrigatório.', "danger");
	}
	elseif(empty($Nome)){
		echo MensagemAlerta('Erro', 'Nome é um campo obrigatório.', "danger");
	}
	elseif(empty($Servidor)){
		echo MensagemAlerta('Erro', 'Servidor é um campo obrigatório!', "danger");
	}
	elseif(empty($Porta)){
		echo MensagemAlerta('Erro', 'Porta é um campo obrigatório!', "danger");
	}
	elseif(is_numeric($Porta) == FALSE){
		echo MensagemAlerta('Erro', 'Porta deve conter apenas números!', "danger");
	}
	elseif(empty($Usuario)){
		echo MensagemAlerta('Erro', 'Usuário é um campo obrigatório!', "danger");
	}
	elseif(empty($Senha)){
		echo MensagemAlerta('Erro', 'Senha é um campo obrigatório!', "danger");
	}
	else{
		
	$SQL = "UPDATE servidor SET
			icone = :icone,
			nome = :nome,
			server = :server,
			porta = :porta,
			user = :user,
			senha = :senha
            WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':icone', $IconePerfil, PDO::PARAM_STR);
	$SQL->bindParam(':nome', $Nome, PDO::PARAM_STR);
	$SQL->bindParam(':server', $Servidor, PDO::PARAM_STR);
	$SQL->bindParam(':porta', $Porta, PDO::PARAM_STR);
	$SQL->bindParam(':user', $Usuario, PDO::PARAM_STR);
	$SQL->bindParam(':senha', $Senha, PDO::PARAM_STR);
	$SQL->bindParam(':id', $id, PDO::PARAM_INT);
	$SQL->execute();
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao cadastrar o servidor!', "danger", "index.php?p=servidor");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Servidor alterado com sucesso!', "success", "index.php?p=servidor");
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