<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$DataPremium = (isset($_POST['DataPremium'])) ? $_POST['DataPremium'] : '';
$LimiteTeste = (isset($_POST['LimiteTeste'])) ? $_POST['LimiteTeste'] : '';
$EditarUsuario = (isset($_POST['EditarUsuario'])) ? $_POST['EditarUsuario'] : '';
$EditarSenha = (isset($_POST['EditarSenha'])) ? $_POST['EditarSenha'] : '';
$EditarNome = (isset($_POST['EditarNome'])) ? $_POST['EditarNome'] : '';
$EditarCota = (isset($_POST['EditarCota'])) ? $_POST['EditarCota'] : '';
$ValorCobrado = (isset($_POST['ValorCobrado'])) ? ConverterDinheiro($_POST['ValorCobrado']) : '';
$CadUser = $_SESSION['id'];

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
	$VerCota = $LnDes['cota'] - $EditarCota;
	
	$VerificarLimiteTeste = VerificarLimiteTeste($CadUser);	
	
	if(empty($CadUser)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(is_numeric($EditarCota) == false){
		echo MensagemAlerta('Erro', 'Cota deve ser apenas número inteiro!', "danger");
	}
	elseif($VerCota < 0){
		echo MensagemAlerta('Erro', 'Você não tem saldo suficiente para adicionar o revendedor, compre um novo pacote!', "danger");
	}
	elseif($EditarCota < 1){
		echo MensagemAlerta('Erro', 'Você não tem saldo suficiente para adicionar o revendedor, compre um novo pacote!', "danger");
	}
	elseif(empty($EditarUsuario)){
		echo MensagemAlerta('Erro', 'Usuário é um campo obrigatório!', "danger");
	}
	elseif($TotalResul > 0){
		echo MensagemAlerta('Erro', 'Usuário já encontra-se em uso!', "danger");
	}
	elseif(empty($EditarSenha)){
		echo MensagemAlerta('Erro', 'Senha é um campo obrigatório!', "danger");
	}
	elseif(empty($DataPremium)){
		echo MensagemAlerta('Erro', 'Data Premium é um campo obrigatório!', "danger");
	}
	elseif(empty($EditarNome)){
		echo MensagemAlerta('Erro', 'Nome é um campo obrigatório!', "danger");
	}
	elseif(is_numeric($LimiteTeste) == false){
		echo MensagemAlerta('Erro', 'Limite Teste deve ser apenas número inteiro!', "danger");
	}
	elseif( ($LimiteTeste > $VerificarLimiteTeste) && ($VerificarLimiteTeste != 0)){
		echo MensagemAlerta('Erro', 'Não permitido, este limite é maior do que você possui atualmente.', "danger");
	}
	else{
		
	$DataPremium = ConverterData($DataPremium);
		
	//Atualizar Cota
	$SQLa = "UPDATE login SET
			cota = :cota
            WHERE id = :id";
	$SQLa = $banco->prepare($SQLa);
	$SQLa->bindParam(':cota', $VerCota, PDO::PARAM_INT);
	$SQLa->bindParam(':id', $CadUser, PDO::PARAM_INT);
	$SQLa->execute(); 
	
	$DataAtual = date('Y-m-d');
	$DataAtual = strtotime($DataAtual);
	
	$acesso = 2;
	$SQL = "INSERT INTO login (
			id_cad,
			acesso,
			nome,
            login,
            senha,
			cota,
			LimiteTeste,
			data,
			ValorCobrado,
			expiredate
            ) VALUES (
			:id_cad,
			:acesso,
			:nome,
            :login,
            :senha,
			:cota,
			:LimiteTeste,
			:data,
			:ValorCobrado,
			:expiredate
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_INT); 
	$SQL->bindParam(':acesso', $acesso, PDO::PARAM_INT); 
	$SQL->bindParam(':nome', $EditarNome, PDO::PARAM_STR); 
	$SQL->bindParam(':login', $EditarUsuario, PDO::PARAM_STR); 
	$SQL->bindParam(':senha', $EditarSenha, PDO::PARAM_STR); 
	$SQL->bindParam(':cota', $EditarCota, PDO::PARAM_INT);  
	$SQL->bindParam(':LimiteTeste', $LimiteTeste, PDO::PARAM_INT); 
	$SQL->bindParam(':data', $DataAtual, PDO::PARAM_INT); 
	$SQL->bindParam(':ValorCobrado', $ValorCobrado, PDO::PARAM_STR);
	$SQL->bindParam(':expiredate', $DataPremium, PDO::PARAM_STR); 
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um problema ao cadastrar o usuário!', "danger", "index.php?p=revendedor");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Usuário adicionado com sucesso!', "success", "index.php?p=revendedor");
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