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
	$Excluir = 'S';
	
	for($i = 0; $i < count($SelectBox); $i++){
		
		$SQL = "UPDATE suporte SET
			ExcluirEmissor = :ExcluirEmissor
            WHERE id = :id AND PastaEmissor = :PastaEmissor AND UserEmissor = :UserEmissor";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':ExcluirEmissor', $Excluir, PDO::PARAM_STR); 
		$SQL->bindParam(':id', $SelectBox[$i], PDO::PARAM_INT);
		$SQL->bindParam(':PastaEmissor', $Pasta, PDO::PARAM_INT);
		$SQL->bindParam(':UserEmissor', $user, PDO::PARAM_STR); 
		$SQL->execute(); 
		
		$SQL = "UPDATE suporte SET
			ExcluirReceptor = :ExcluirReceptor
            WHERE id = :id AND PastaReceptor = :PastaReceptor AND UserReceptor = :UserReceptor";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':ExcluirReceptor', $Excluir, PDO::PARAM_STR); 
		$SQL->bindParam(':id', $SelectBox[$i], PDO::PARAM_INT);
		$SQL->bindParam(':PastaReceptor', $Pasta, PDO::PARAM_INT);
		$SQL->bindParam(':UserReceptor', $user, PDO::PARAM_STR); 
		$SQL->execute(); 
		
		$SQLDel = "SELECT anexo FROM suporte WHERE id = :id AND PastaEmissor = :PastaEmissor AND PastaReceptor = :PastaReceptor AND ExcluirEmissor = :ExcluirEmissor AND ExcluirReceptor = :ExcluirReceptor AND UserEmissor = :UserEmissor OR id = :id AND PastaEmissor = :PastaEmissor AND PastaReceptor = :PastaReceptor AND ExcluirEmissor = :ExcluirEmissor AND ExcluirReceptor = :ExcluirReceptor AND UserReceptor = :UserReceptor";
		$SQLDel = $banco->prepare($SQLDel);
		$SQLDel->bindParam(':id', $SelectBox[$i], PDO::PARAM_INT);
		$SQLDel->bindParam(':PastaEmissor', $Pasta, PDO::PARAM_STR);
		$SQLDel->bindParam(':PastaReceptor', $Pasta, PDO::PARAM_STR);
		$SQLDel->bindParam(':ExcluirEmissor', $Excluir, PDO::PARAM_STR);
		$SQLDel->bindParam(':ExcluirReceptor', $Excluir, PDO::PARAM_STR);
		$SQLDel->bindParam(':UserEmissor', $user, PDO::PARAM_STR);
		$SQLDel->bindParam(':UserReceptor', $user, PDO::PARAM_STR);
		$SQLDel->execute();
		$TotalDel = count($SQLDel->fetchAll());
		
		if($TotalDel > 0){	
		
		$SQLSup = "SELECT anexo FROM suporte WHERE id = :id";	
		$SQLSup = $banco->prepare($SQLSup);
		$SQLSup->bindParam(':id', $SelectBox[$i], PDO::PARAM_INT);
		$SQLSup->execute();
		while($Ln = $SQLSup->fetch()){
			if(!empty($Ln['anexo'])){
				unlink('suporte/'.$Ln['anexo']);
			}
		}
		
		$SQL = "DELETE FROM suporte WHERE id = :id AND UserEmissor = :UserEmissor OR id = :id AND UserReceptor = :UserReceptor";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':id', $SelectBox[$i], PDO::PARAM_INT); 
		$SQL->bindParam(':UserEmissor', $user, PDO::PARAM_STR);
		$SQL->bindParam(':UserReceptor', $user, PDO::PARAM_STR);
		$SQL->execute(); 
		
		$SQLSup = "SELECT anexo FROM suporteresp WHERE id_suporte = :id_suporte";	
		$SQLSup = $banco->prepare($SQLSup);
		$SQLSup->bindParam(':id_suporte', $SelectBox[$i], PDO::PARAM_INT);
		$SQLSup->execute();
		while($Ln = $SQLSup->fetch()){
			if(!empty($Ln['anexo'])){
				unlink('suporte/'.$Ln['anexo']);
			}
		}
		
		$SQL = "DELETE FROM suporteresp WHERE id_suporte = :id_suporte";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':id_suporte', $SelectBox[$i], PDO::PARAM_INT); 
		$SQL->execute(); 
			
		}
	}
	
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