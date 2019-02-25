<?php
include_once("functions.php");
if(ProtegePag() == true){

Sair();
    
}else{
	echo Redirecionar('login.php');
}	
?>