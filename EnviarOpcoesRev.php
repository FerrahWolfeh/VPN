<?php
	include("conexao.php");
	include_once("functions.php");
	if(ProtegePag() == true){
	
	if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

	$CadUser = $_SESSION['id'];
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$rev = (isset($_POST['rev'])) ? $_POST['rev'] : '';
	$camposMarcados = (isset($_POST['camposMarcados'])) ? $_POST['camposMarcados'] : '';
	$status = (isset($_POST['status'])) ? $_POST['status'] : '';
	
	if(empty($camposMarcados)){
		echo MensagemAlerta('Erro', 'Como você fez isso? Ocorreu um erro, por favor, tente mais tarde!', "danger");
	}
	elseif(empty($status)){
		echo MensagemAlerta('Erro', 'Qual o Status? Ocorreu um problema, por favor, tente mais tarde!', "danger");
	}
	else{	
	
		for($i = 0; $i < count($camposMarcados); $i++){
				
				if( $status == "alterarrev" ){
					
						$SQL = "UPDATE login SET
								id_cad = :id_cad
            					WHERE id = :id";
						$SQL = $banco->prepare($SQL);
						$SQL->bindParam(':id_cad', $rev, PDO::PARAM_INT);
						$SQL->bindParam(':id', $camposMarcados[$i], PDO::PARAM_INT);
						$SQL->execute();
					
				}
				elseif( $status == "excluirall" ){
						
						$ArvoreRev = ArvoreRev($camposMarcados[$i]);
						if(!empty($ArvoreRev)){
							for($is = 0; $is < count($ArvoreRev); $is++){
								$ExcluirSSHUser = ExcluirSSHUser($ArvoreRev[$is]);
								$ExcluirPorUsuario = ExcluirPorUsuario($ArvoreRev[$is]);
							}
						}
	
						//Deletar Usuário
						$ExcluirPorUsuario = ExcluirPorUsuario($camposMarcados[$i]);
						$SQL = "DELETE FROM login WHERE id = :id";
						$SQL = $banco->prepare($SQL);
						$SQL->bindParam(':id', $camposMarcados[$i], PDO::PARAM_INT); 
						$SQL->execute(); 
						
				}
								
		}
	
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro, por favor, procure o administrador!', "danger", "index.php?p=revendedor");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Realizado com sucesso', "success", "index.php?p=revendedor");
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