<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if($_SESSION['acesso'] == 1){

include("image_resizing.php");
$imgr = new imageResizing();

if($_POST['cp_img_path']){    
		
	$ds          = DIRECTORY_SEPARATOR; 
	
    $image = $_POST['cp_img_path'];
    $imgr->load($image);
    
    $imgX = intval($_POST['ic_x']);
    $imgY = intval($_POST['ic_y']);
    $imgW = intval($_POST['ic_w']);
    $imgH = intval($_POST['ic_h']);
	    
    $imgr->resize($imgW,$imgH,$imgX,$imgY);    
    $imgr->ResizePerfil(326,107); 
    $imgr->save($image);
	
	$filename = basename($_POST['cp_img_path']);
	
	//Insere a imagem no banco de dados
	$SQL = "INSERT INTO imagem_perfil (
            imagem
            ) VALUES (
            :imagem
			)";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':imagem', $filename, PDO::PARAM_STR);
	$SQL->execute();
	
	if(empty($SQL)){
		echo MensagemAlerta('Erro', 'Ocorreu um erro ao processar a solicitação, por favor, tente mais tarde.', "danger");
	}
	else{
		echo MensagemAlerta('Sucesso', 'Imagem de Perfil adicionada com sucesso!', "success", "index.php?p=imagem-perfil");
	}

	}

}else{
	echo Redirecionar('index.php');
}	
}else{
	echo Redirecionar('login.php');
}	
?>     
