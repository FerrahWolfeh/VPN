<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$DataPremium = (isset($_POST['DataPremium'])) ? $_POST['DataPremium'] : '';
$LimiteTeste = (isset($_POST['LimiteTeste'])) ? $_POST['LimiteTeste'] : '';
$EditarID = (isset($_POST['EditarID'])) ? $_POST['EditarID'] : '';
$EditarNome = (isset($_POST['EditarNome'])) ? $_POST['EditarNome'] : '';
$EditarUsuario = (isset($_POST['EditarUsuario'])) ? $_POST['EditarUsuario'] : '';
$EditarSenha = (isset($_POST['EditarSenha'])) ? $_POST['EditarSenha'] : '';
$EditarCota = (isset($_POST['EditarCota'])) ? $_POST['EditarCota'] : '';
$ValorCobrado = (isset($_POST['ValorCobrado'])) ? ConverterDinheiro($_POST['ValorCobrado']) : '';
$UsuarioOnline = $_SESSION['id'];

	$ArvoreAdminOnline = ArvoreRev($UsuarioOnline);
	$ArvoreAdminOnline[] = $UsuarioOnline;
	
	$SQLCotaUser = "SELECT cota FROM login WHERE id = :id";
	$SQLCotaUser = $banco->prepare($SQLCotaUser);
	$SQLCotaUser->bindParam(':id', $EditarID, PDO::PARAM_STR);
	$SQLCotaUser->execute();
	$LnCotaUser = $SQLCotaUser->fetch();
	$CotaUser = $LnCotaUser['cota'];
	$CotaEditarSalvar = $EditarCota - $CotaUser;
	
	$SQLDes = "SELECT cota FROM login WHERE id = :id";
	$SQLDes = $banco->prepare($SQLDes);
	$SQLDes->bindParam(':id', $UsuarioOnline, PDO::PARAM_STR);
	$SQLDes->execute();
	$LnDes = $SQLDes->fetch();
	$VerCota = $LnDes['cota'] - $CotaEditarSalvar;
	
	$SQLUser = "SELECT login FROM login WHERE login = :login AND id != :id";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':login', $EditarUsuario, PDO::PARAM_STR);
	$SQLUser->bindParam(':id', $EditarID, PDO::PARAM_INT);
	$SQLUser->execute();
	$TotalResul = count($SQLUser->fetchAll());
	
	$CadUser = $_SESSION['id'];
	$VerificarLimiteTeste = VerificarLimiteTeste($CadUser);	

	if(empty($EditarID)){
		echo MensagemAlerta('Erro', 'Como você fez isso?', "danger");
	}
	elseif(is_numeric($EditarCota) == false){
		echo MensagemAlerta('Erro', 'Cota deve ser apenas número inteiro!', "danger");
	}
	elseif(!in_array($EditarID, $ArvoreAdminOnline)) {
		echo MensagemAlerta('Erro', 'Este usuário não pertence a você!', "danger");
	}
	elseif($VerCota < 0){
		echo MensagemAlerta('Erro', 'Você não tem saldo suficiente para editar a cota do revendedor, compre um novo pacote!', "danger");
	}
	elseif($EditarCota < 0){
		echo MensagemAlerta('Erro', 'Cota não pode ser menor do que 0 (Zero).', "danger");
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
	$SQLa->bindParam(':id', $UsuarioOnline, PDO::PARAM_INT);
	$SQLa->execute(); 

	$SQL = "UPDATE login SET
		nome = :nome,
		login = :login,
		senha = :senha,
		cota = :cota,
		LimiteTeste = :LimiteTeste,
		ValorCobrado = :ValorCobrado,
		expiredate = :expiredate
        WHERE id = :id";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':nome', $EditarNome, PDO::PARAM_STR); 
	$SQL->bindParam(':login', $EditarUsuario, PDO::PARAM_STR); 
	$SQL->bindParam(':senha', $EditarSenha, PDO::PARAM_STR);
	$SQL->bindParam(':cota', $EditarCota, PDO::PARAM_STR);
	$SQL->bindParam(':LimiteTeste', $LimiteTeste, PDO::PARAM_STR);
	$SQL->bindParam(':ValorCobrado', $ValorCobrado, PDO::PARAM_STR);
	$SQL->bindParam(':expiredate', $DataPremium, PDO::PARAM_STR);
	$SQL->bindParam(':id', $EditarID, PDO::PARAM_INT);
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao editar o revendedor.', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Revendedor editado com sucesso', "success", "index.php?p=revendedor");
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