<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
	$id = (isset($_POST['id'])) ? $_POST['id'] : '';
	
	if(!empty($id)){
	$user = $_SESSION['login'];
	
	$SQLEstrela = "SELECT Estrela FROM suporte WHERE id = :id AND UserReceptor = :UserReceptor";
	$SQLEstrela = $banco->prepare($SQLEstrela);
	$SQLEstrela->bindParam(':id', $id, PDO::PARAM_INT);
	$SQLEstrela->bindParam(':UserReceptor', $user, PDO::PARAM_STR); 
	$SQLEstrela->execute();
	$LnEstrela = $SQLEstrela->fetch();
	
	$Estrela = $LnEstrela['Estrela'] == "S" ? "N" : "S";
	
	$SQL = "UPDATE suporte SET
			Estrela = :Estrela
            WHERE id = :id AND UserReceptor = :UserReceptor";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':Estrela', $Estrela, PDO::PARAM_STR); 
	$SQL->bindParam(':id', $id, PDO::PARAM_INT);
	$SQL->bindParam(':UserReceptor', $user, PDO::PARAM_STR); 
	$SQL->execute(); 
	
	//Estrela
	$PastaEstrela = 4;
	$Estrela = "S";
	$SQLEstrela = "SELECT id FROM suporte WHERE UserReceptor = :UserReceptor AND Estrela = :Estrela AND PastaReceptor != :PastaReceptor";
	$SQLEstrela = $banco->prepare($SQLEstrela);
	$SQLEstrela->bindParam(':UserReceptor', $user, PDO::PARAM_STR);
	$SQLEstrela->bindParam(':Estrela', $Estrela, PDO::PARAM_STR);
	$SQLEstrela->bindParam(':PastaReceptor', $PastaEstrela, PDO::PARAM_STR);
	$SQLEstrela->execute();
	$TotalEstrela = count($SQLEstrela->fetchAll());
	echo $TotalEstrela;
	}
		
}

}

?>