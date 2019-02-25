<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
	$SelectBox = (isset($_POST['SelectBox'])) ? $_POST['SelectBox'] : '';
	
	if(empty($SelectBox)){
		echo MensagemAlerta("Erro", "Como você fez isso?", "danger");
	}
	else{
	$user = $_SESSION['login'];
	$Pasta = 4;
	
	$SQL = "UPDATE suporte SET
			PastaReceptor = :PastaReceptor
            WHERE id = :id AND UserReceptor = :UserReceptor";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':PastaReceptor', $Pasta, PDO::PARAM_INT);
	$SQL->bindParam(':id', $SelectBox, PDO::PARAM_INT);
	$SQL->bindParam(':UserReceptor', $user, PDO::PARAM_STR); 
	$SQL->execute(); 
	
	$SQL = "UPDATE suporte SET
			PastaEmissor = :PastaEmissor
            WHERE id = :id AND UserEmissor = :UserEmissor";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':PastaEmissor', $Pasta, PDO::PARAM_INT);
	$SQL->bindParam(':id', $SelectBox, PDO::PARAM_INT);
	$SQL->bindParam(':UserEmissor', $user, PDO::PARAM_STR); 
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta("Erro", "Ocorreu um erro ao processar a solicitação, por favor, tente mais tarde.", "danger");
	}
	else{
		echo MensagemAlerta("Sucesso", "Excluído com sucesso!", "success", "index.php?p=suporte");
	}
	
	}
		
}

}

?>