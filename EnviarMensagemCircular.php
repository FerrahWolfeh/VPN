<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$Grupo = (isset($_POST['Grupo'])) ? $_POST['Grupo'] : '';
	$Todos = (isset($_POST['Todos'])) ? $_POST['Todos'] : '';
	$Status = (isset($_POST['Status'])) ? $_POST['Status'] : '';
	$Mensagem = (isset($_POST['Mensagem'])) ? $_POST['Mensagem'] : '';
	$EntreDatas = (isset($_POST['EntreDatas'])) ? $_POST['EntreDatas'] : '';
	$DataInicio = (isset($_POST['DataInicio'])) ? $_POST['DataInicio'] : '';
	$DataFinal = (isset($_POST['DataFinal'])) ? $_POST['DataFinal'] : '';
	$DataAtual = strtotime(date('Y-m-d'));
		
	$DataInicio = ConverterData($DataInicio);
	$DataFinal = ConverterData($DataFinal);
	
	if(empty($EntreDatas)){
		echo MensagemAlerta("Erro", "Entre Datas é um campo obrigatório!", "danger");
	}
	elseif( ($EntreDatas == "S") && (empty($DataInicio)) ){
		echo MensagemAlerta("Erro", "Data Inicial é um campo obrigatório!", "danger");
	}
	elseif( ($EntreDatas == "S") && (empty($DataFinal)) ){
		echo MensagemAlerta("Erro", "Data Final é um campo obrigatório!", "danger");
	}
	elseif(empty($Grupo)){
		echo MensagemAlerta("Erro", "Enviar Para é um campo obrigatório!", "danger");
	}
	elseif(empty($Todos)){
		echo MensagemAlerta("Erro", "Todos é um campo obrigatório!", "danger");
	}
	elseif(empty($Status)){
		echo MensagemAlerta("Erro", "Status é um campo obrigatório!", "danger");
	}
	elseif(empty($Mensagem)){
		echo MensagemAlerta("Erro", "Mensagem é um campo obrigatório!", "danger");
	}
	else{
		
	if($Grupo == "Todos"){
		$SQLGrupos = "";
	}
	else{
		$SQLGrupos = " AND acesso = '".$Grupo."'";
	}
	
	if($Todos == "S"){
		$CadUser = $_SESSION['id'];
		$ArvoreAdminOnline = ArvoreRev($CadUser);
		$ArvoreAdminOnline[] = $CadUser;	
		$Emissor = implode(',', $ArvoreAdminOnline);
		$SQLTodos = "FIND_IN_SET(id,:id)";
	}
	else{
		$Emissor = $_SESSION['id'];
		$SQLTodos = "FIND_IN_SET(id_cad,:id)";
	}
	
	if($EntreDatas == "S"){
		$SqlEntreDatas = " AND expiredate >= '".$DataInicio."' AND expiredate <= '".$DataFinal."'";
	}
	else{
		$SqlEntreDatas = "";
	}
	
	if($Status == "Ativos"){
		$PesStatus = " AND bloqueado = 'N' AND expiredate >= '".$DataAtual."'";
	}
	elseif($Status == "Bloqueados"){
		$PesStatus = " AND bloqueado = 'S'";
	}
	elseif($Status == "Esgotados"){
		$PesStatus = " AND expiredate < '".$DataAtual."'";
	}
	else{
		$PesStatus = "";
	}

	//Cadastrar Circular
	$SQL = "UPDATE login SET
			msginterna = :msginterna
            WHERE ".$SQLTodos.$SQLGrupos.$SqlEntreDatas.$PesStatus;
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':msginterna', $Mensagem, PDO::PARAM_STR); 
	$SQL->bindParam(':id', $Emissor, PDO::PARAM_STR); 
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao deletar o teste', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Mensagem Interna enviada com sucesso', "success", "index.php?p=inicio");
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