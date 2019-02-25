<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
$id = isset($_POST['id']) ? $_POST['id'] : '';

if($id == "S"){
	
echo "
	<div class=\"form-group\">
    		<label class=\"col-md-3 control-label\">E-mail</label>
        	<div class=\"col-md-9\">
           		<input id=\"EditarEmail\" name=\"EditarEmail\" type=\"text\" class=\"validate[custom[email]] form-control\">
            </div>
    </div>
";

}

}
}else{
	echo Redirecionar('login.php');
}	
?>