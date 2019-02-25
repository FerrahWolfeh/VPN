<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

$mensagem = empty($_POST['mensagem']) ? "" : $_POST['mensagem'];
$assunto = empty($_POST['assunto']) ? "" : $_POST['assunto'];
$anexo = empty($_FILES['anexo']) ? "" : $_FILES['anexo'];
$UserEmissor = $_SESSION['login'];
	
	if(empty($assunto)){
		echo MensagemAlerta('Erro', "Assunto é um campo obrigatório.", "danger");
	}
	elseif(empty($mensagem)){
		echo MensagemAlerta('Erro', "Mensagem é um campo obrigatório.", "danger");
	}
	elseif($mensagem == "<p><br></p>"){
		echo MensagemAlerta('Erro', "Mensagem é um campo obrigatório.", "danger");
	}
	else{

	if(!empty($_FILES) && $anexo['tmp_name']){ 
	
	$error       = false;
	$ds          = DIRECTORY_SEPARATOR; 
	$storeFolder = 'suporte'.$ds;
	$NomeAnexo = '';
	
	// check image type
	$tempFile = $anexo['tmp_name'];
    $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG);// list of allowed image types
    $detectedType = exif_imagetype($tempFile);
    $error = !in_array($detectedType, $allowedTypes);
    // end of check

	$ex = explode(".",$anexo['name']);
	$extensao = end($ex);
	
	$fileName = time().'_'.sha1($anexo['name'].time()).'.'.$extensao;
			
	if( ($extensao == "pdf") || ($extensao == "doc") || ($extensao == "docm") ) $error = false;
		if(!$error){
			
			$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;     
       		$targetFile =  $targetPath. $fileName;
			
			if(move_uploaded_file($tempFile,$targetFile)){
				$NomeAnexo = $fileName;
        	}
		 
		}
		else{
			echo MensagemAlerta("Erro", "Permitido anexo apenas nos formatos jpg, png, gif, pdf e doc.", "danger");
			exit;
		}
	}
	
	//Inserir Resposta
	$UserReceptor = $_SESSION['id_cad'];
	
	$SQLCad = "SELECT login FROM login WHERE id = :id";
	$SQLCad = $banco->prepare($SQLCad);
	$SQLCad->bindParam(':id', $UserReceptor, PDO::PARAM_STR);
	$SQLCad->execute();
	$LnSelectCad = $SQLCad->fetch();
	$UserReceptor = $LnSelectCad['login'];
	
	$data = time();
	$LidaEmissor = "S";
	$SQL = "INSERT INTO suporte (
			UserEmissor,
			UserReceptor,
            Assunto,
            data,
			anexo,
			Mensagem,
			LidaEmissor
			) VALUES (
            :UserEmissor,
			:UserReceptor,
            :Assunto,
            :data,
			:anexo,
			:Mensagem,
			:LidaEmissor
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':UserEmissor', $UserEmissor, PDO::PARAM_STR);
	$SQL->bindParam(':UserReceptor', $UserReceptor, PDO::PARAM_STR);
	$SQL->bindParam(':Assunto', $assunto, PDO::PARAM_STR);
	$SQL->bindParam(':data', $data, PDO::PARAM_STR);
	$SQL->bindParam(':anexo', $NomeAnexo, PDO::PARAM_STR);
	$SQL->bindParam(':Mensagem', $mensagem, PDO::PARAM_STR);
	$SQL->bindParam(':LidaEmissor', $LidaEmissor, PDO::PARAM_STR);
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta("Erro", "Ocorreu um erro ao processar a solicitação, por favor, tente mais tarde.", "danger");
	}
	else{
		echo MensagemAlerta("Sucesso", "Suporte aberto com sucesso.", "success", "index.php?p=suporte");
	}


	}
}else{
	echo Redirecionar('login.php');
}	
?>