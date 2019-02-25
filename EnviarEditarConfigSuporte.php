<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
	$ExibirPorPag = (isset($_POST['ExibirPorPag'])) ? $_POST['ExibirPorPag'] : '';

	if(empty($ExibirPorPag)){
		echo MensagemAlerta("Erro", "Exibir por Página é um campo obrigatório.", "danger");
	}
	else{
		
	
	$user = $_SESSION['login'];
	$SQLSup = "SELECT id FROM config_suporte WHERE id_cad = :id_cad";
	$SQLSup = $banco->prepare($SQLSup);
	$SQLSup->bindParam(':id_cad', $user, PDO::PARAM_STR); 
	$SQLSup->execute();
	$TotalSup = count($SQLSup->fetchAll());
	
	if($TotalSup > 0){
		$SQL = "UPDATE config_suporte SET
			SuportePaginacao = :SuportePaginacao
            WHERE id_cad = :id_cad";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':SuportePaginacao', $ExibirPorPag, PDO::PARAM_INT);
		$SQL->bindParam(':id_cad', $user, PDO::PARAM_STR); 
		$SQL->execute(); 
	}
	else{
		$SQL = "INSERT INTO config_suporte (
			id_cad,
			SuportePaginacao
            ) VALUES (
			:id_cad,
            :SuportePaginacao
			)";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':id_cad', $user, PDO::PARAM_STR);
		$SQL->bindParam(':SuportePaginacao', $ExibirPorPag, PDO::PARAM_INT);
		$SQL->execute();
	}
	
	$_SESSION['SuportePaginacao'] = $ExibirPorPag;
	
	if(empty($SQL)){
		echo MensagemAlerta("Erro", "Ocorreu um erro ao processar a solicitação, por favor, tente mais tarde.", "danger");
	}
	else{
		echo MensagemAlerta("Suporte", "Alterado com sucesso.", "success", "index.php?p=suporte");
	}
		
		
	}
}

}else{
	echo Redirecionar('login.php');
}

?>