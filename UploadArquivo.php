<?php
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '500M');

include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if($_SESSION['acesso'] == 1){

$Operadora = empty($_POST['Operadora']) ? "" : $_POST['Operadora'];
$ImagemPerfil = empty($_POST['ImagemPerfil']) ? "" : $_POST['ImagemPerfil'];
$Nome = empty($_POST['Nome']) ? "" : $_POST['Nome'];
$Titulo = empty($_POST['Titulo']) ? "" : $_POST['Titulo'];
$Descricao = empty($_POST['Descricao']) ? "" : $_POST['Descricao'];
$Botao = empty($_POST['Botao']) ? "" : $_POST['Botao'];
$apn = empty($_POST['apn']) ? "" : $_POST['apn'];
$Tipo = empty($_POST['Tipo']) ? "" : $_POST['Tipo'];
$Url = empty($_POST['Url']) ? "" : $_POST['Url'];
$anexo = empty($_FILES['anexo']) ? "" : $_FILES['anexo'];
	
	if(empty($ImagemPerfil)){
		echo MensagemAlerta('Erro', 'Imagem de Perfil é um campo obrigatório!', "danger");
	}
	elseif(empty($Operadora)){
		echo MensagemAlerta('Erro', 'Operadora é um campo obrigatório!', "danger");
	}
	elseif(empty($Nome)){
		echo MensagemAlerta('Erro', 'Nome é um campo obrigatório!', "danger");
	}
	elseif(empty($Titulo)){
		echo MensagemAlerta('Erro', 'Título é um campo obrigatório!', "danger");
	}
	elseif(empty($Descricao)){
		echo MensagemAlerta('Erro', 'Descrição é um campo obrigatório!', "danger");
	}
	elseif(empty($Botao)){
		echo MensagemAlerta('Erro', 'Nome do Botão é um campo obrigatório!', "danger");
	}
	elseif(empty($Tipo)){
		echo MensagemAlerta('Erro', 'Tipo é um campo obrigatório!', "danger");
	}
	else{
		
	$NomeAnexo = '';
	
	if(!empty($_FILES) && $anexo['tmp_name']){ 
		$tempFile = $anexo['tmp_name'];
		$error       = false;
		$ds          = DIRECTORY_SEPARATOR; 
		$storeFolder = 'download'.$ds;

		$ex = explode(".",$anexo['name']);
		$extensao = end($ex);
	
		$fileName = sha1($anexo['name'].time()).'.'.$extensao;
			
		$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;     
        $targetFile =  $targetPath. $fileName;
			
		if(move_uploaded_file($tempFile,$targetFile)){
			$NomeAnexo = $fileName;
    	}
	}
	
	//Salvar em banco de dados
	$SQL = "INSERT INTO arquivo (
			nome,
			titulo,
            descricao,
            botao,
			apn,
			imagem,
			url,
			file,
			tipo,
			operadora
			) VALUES (
            :nome,
			:titulo,
            :descricao,
            :botao,
			:apn,
			:imagem,
			:url,
			:file,
			:tipo,
			:operadora
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':nome', $Nome, PDO::PARAM_STR);
	$SQL->bindParam(':titulo', $Titulo, PDO::PARAM_STR);
	$SQL->bindParam(':descricao', $Descricao, PDO::PARAM_STR);
	$SQL->bindParam(':botao', $Botao, PDO::PARAM_STR);
	$SQL->bindParam(':apn', $apn, PDO::PARAM_STR);
	$SQL->bindParam(':imagem', $ImagemPerfil, PDO::PARAM_STR);
	$SQL->bindParam(':url', $Url, PDO::PARAM_STR);
	$SQL->bindParam(':file', $NomeAnexo, PDO::PARAM_STR);
	$SQL->bindParam(':tipo', $Tipo, PDO::PARAM_STR);
	$SQL->bindParam(':operadora', $Operadora, PDO::PARAM_STR);
	$SQL->execute(); 
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao salvar o arquivo de perfil!', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', "Arquivo de Perfil salvo com sucesso!", "success", "index.php?p=arquivo-perfil");
	}

}

}else{
	echo Redirecionar('index.php');
}	
}else{
	echo Redirecionar('login.php');
}	
?>