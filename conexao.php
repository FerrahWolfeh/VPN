<?php
include_once("functions.php");

// ======================================
//         ~ Informações MySQL ~
// ======================================
// Servidor MySQL
$_MDouglas['servidor'] = 'localhost';
// Usuário MySQL
$_MDouglas['usuario'] = 'root';
// Senha MySQL
$_MDouglas['senha'] = 'Pedr0123';

// ======================================
//          ~ Bancos de Dados ~
// ======================================
$_MDouglas['banco'] = 'net';

// ======================================
//    ~ Conexões com Bancos de Dados ~
// ======================================
	try {
   $banco = new PDO('mysql:host='.$_MDouglas['servidor'].';dbname='.$_MDouglas['banco'].';charset=utf8', $_MDouglas['usuario'], $_MDouglas['senha'], 
	array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
	PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8"
  ));
	} 
	catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
	}
	
// ======================================
//        ~ Inicializa a Sessão ~
// ======================================
if(empty($_SESSION)){ 
	session_start(); 
}
?>