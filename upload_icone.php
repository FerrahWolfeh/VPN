<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if($_SESSION['acesso'] == 1){

$error       = false;
$ds          = DIRECTORY_SEPARATOR; 
$storeFolder = 'img'.$ds.'icone';
 
if (!empty($_FILES) && $_FILES['file']['tmp_name']) {    
    
    $tempFile = $_FILES['file']['tmp_name'];
	
	$ex = explode(".",$_FILES['file']['name']);
	$extensao = end($ex);
    $fileName = time().'_'.sha1($_FILES['file']['name'].time()).'.'.$extensao;
    
    // check image type
    $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG);// list of allowed image types
    $detectedType = exif_imagetype($tempFile);
    $error = !in_array($detectedType, $allowedTypes);
    // end of check
    
    if(!$error){
        
        $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;     
        $targetFile =  $targetPath. $fileName;
        
        if(move_uploaded_file($tempFile,$targetFile)){

        echo '<div class="cropping-image-wrap"><img src="'.$storeFolder.$ds.$fileName.'" class="img-thumbnail" id="crop_image" value="'.$fileName.'"/></div>';
        }
        
    }else{
        echo '<div class="alert alert-danger">Este formato de imagem não é suportado.</div>';
    }
}else{
    echo '<div class="alert alert-danger">Como você fez isso? O_o</div>';
}



}else{
	echo Redirecionar('index.php');
}	
}else{
	echo Redirecionar('login.php');
}
?>     
