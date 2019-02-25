<?php
include("conexao.php");
include_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
$senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';

	if(empty($usuario)){
		echo MensagemAlerta('Erro', 'Usuário é um campo obrigatório', "danger");
	}
	elseif(empty($senha)){
		echo MensagemAlerta('Erro', 'Senha é um campo obrigatório', "danger");
	}
	elseif(ValidarUsuario($usuario, $senha) == true){
		echo Redirecionar('index.php?p=inicio');
	} 
	else{
		echo MensagemAlerta('Erro', 'Usuário ou Senha não confere!', "danger");
	}
}

?>