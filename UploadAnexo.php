<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

$id_suporte = empty($_POST['id_suporte']) ? "" : $_POST['id_suporte'];
$mensagem = empty($_POST['mensagem']) ? "" : $_POST['mensagem'];
$pasta = empty($_POST['pasta']) ? "" : $_POST['pasta'];
$anexo = empty($_FILES['anexo']) ? "" : $_FILES['anexo'];
$UserEmissor = $_SESSION['login'];
	
	if(empty($id_suporte)){
		echo MensagemAlerta("Erro", "Como você fez isso?", "danger");
	}
	elseif(empty($pasta)){
		echo MensagemAlerta("Erro", "Como você fez isso?", "danger");
	}
	elseif(empty($mensagem)){
		echo MensagemAlerta("Erro", "Mensagem é um campo obrigatório.", "danger");
	}
	elseif($mensagem == "<p><br></p>"){
		echo MensagemAlerta("Erro", "Mensagem é um campo obrigatório.", "danger");
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
	
	//Atualiza Receptor
	$LidaReceptor = "N";
	$SQL = "UPDATE suporte SET
			LidaReceptor = :LidaReceptor
            WHERE id = :id AND UserEmissor = :UserEmissor";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':LidaReceptor', $LidaReceptor, PDO::PARAM_STR); 
	$SQL->bindParam(':id', $id_suporte, PDO::PARAM_INT);
	$SQL->bindParam(':UserEmissor', $UserEmissor, PDO::PARAM_STR); 
	$SQL->execute();
	
	//Atualiza Emissor
	$LidaEmissor = "N";
	$SQL = "UPDATE suporte SET
			LidaEmissor = :LidaEmissor
            WHERE id = :id AND UserReceptor = :UserReceptor";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':LidaEmissor', $LidaEmissor, PDO::PARAM_STR); 
	$SQL->bindParam(':id', $id_suporte, PDO::PARAM_INT);
	$SQL->bindParam(':UserReceptor', $UserEmissor, PDO::PARAM_STR); 
	$SQL->execute();
	
	//Inserir Resposta
	$data = time();
	$SQL = "INSERT INTO suporteresp (
			id_suporte,
			UserEmissor,
            mensagem,
            anexo,
			data
			) VALUES (
            :id_suporte,
			:UserEmissor,
            :mensagem,
            :anexo,
			:data
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_suporte', $id_suporte, PDO::PARAM_STR);
	$SQL->bindParam(':UserEmissor', $UserEmissor, PDO::PARAM_STR);
	$SQL->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);
	$SQL->bindParam(':anexo', $NomeAnexo, PDO::PARAM_STR);
	$SQL->bindParam(':data', $data, PDO::PARAM_STR);
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta("Erro", "Ocorreu um erro ao processar a solicitação, por favor, tente mais tarde.", "danger");
	}
	else{
		echo MensagemAlerta("Sucesso", "Mensagem enviada com sucesso.", "success", "index.php?p=suporte&a=".$pasta."&m=".$id_suporte."");
	}


	}
}else{
	echo Redirecionar('login.php');
}	
?>