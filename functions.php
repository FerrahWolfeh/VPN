<?php
function ValidarUsuario($usuario, $senha){
	include("conexao.php");

	$SQLAdn = "SELECT `id`, `id_cad`, `acesso`, `nome`, `login`, `senha`, `expiredate`, `operadora`, `msginterna`  FROM `login` WHERE `login` = :login AND `senha` = :senha OR `login` = :login AND `senha2` = :senha2 LIMIT 1";
	$SQLAdn = $banco->prepare($SQLAdn);
	$SQLAdn->bindParam(':login', $usuario, PDO::PARAM_STR);
	$SQLAdn->bindParam(':senha', $senha, PDO::PARAM_STR);
	$SQLAdn->bindParam(':senha2', $senha, PDO::PARAM_STR);
	$SQLAdn->execute();
  	$LnAdm = $SQLAdn->fetch();
	
	if(!empty($LnAdm)) {
		$_SESSION['id'] = $LnAdm['id'];
		$_SESSION['id_cad'] = $LnAdm['id_cad'];
		$_SESSION['acesso'] = $LnAdm['acesso'];
		$_SESSION['nome'] = $LnAdm['nome'];
		$_SESSION['login'] = $LnAdm['login'];
		$_SESSION['expiredate'] = $LnAdm['expiredate'];
		$_SESSION['operadora'] = $LnAdm['operadora'];
		$_SESSION['msginterna'] = $LnAdm['msginterna'];
		return true;
	}
	
		return false;
}

function curl($url,$cookies,$post,$header=1){
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, $header);
		if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:12.0) Gecko/20100101 Firefox/12.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER,$url); 
		if ($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		$page = curl_exec( $ch);
		curl_close($ch); 
		return $page;
}

function ProtegePag(){
  include("conexao.php");
  
  if(empty($_SESSION['id']) || empty($_SESSION['id_cad']) || empty($_SESSION['acesso']) || empty($_SESSION['login']) ) {
   		ExpulsaVisitante();
  } 
  elseif(!empty($_SESSION['id']) && !empty($_SESSION['id_cad']) && !empty($_SESSION['acesso']) && !empty($_SESSION['login'])) {
		return true;
  }
  		return false;
}

function Sair(){
  include("conexao.php");
    
  unset($_SESSION['id'], $_SESSION['login'], $_SESSION['id_cad'], $_SESSION['acesso']);
  session_destroy();
  header("Location: login.php");
}

function ExpulsaVisitante(){
  include("conexao.php");
  unset($_SESSION['id'], $_SESSION['login'], $_SESSION['id_cad'], $_SESSION['acesso']);
  session_destroy();
  header("Location: login.php");
}


function MensagemAlerta($titulo, $texto, $tipo, $url = NULL){
	include("conexao.php");
	
	if($tipo == "success"){
		$class = "fa-check";
	}
	elseif($tipo == "warning"){
		$class = "fa-warning";
	}
	elseif($tipo == "info"){
		$class = "fa-info";
	}
	elseif($tipo == "danger"){
		$class = "fa-times";
	}
	else{
		$class = "fa-info";
	}
	
	if(empty($url)){
	$red = "$(this).parents('#MensagemBox').removeClass('open'); $(\"LimparScript\").remove(); return false;";
	}
	else{
	$red = "var url = '".$url."'; $.post('RedirecionarScript.php', {url: url}, function(resposta) { $(\"#StatusGeral\").html(''); $(\"#StatusGeral\").html(resposta); });";
	}
	
	$msg = "<LimparScript><script>$('.mb-control-close').on('click',function(){ ".$red." }); $('#MensagemBox').toggleClass('open');</script><div class=\"message-box message-box-".$tipo." animated fadeIn\" id=\"MensagemBox\"><div class=\"mb-container\"><div class=\"mb-middle\"><div class=\"mb-title\"><span class=\"fa ".$class."\"></span> ".$titulo."</div><div class=\"mb-content\"><p>".$texto."</p></div><div class=\"mb-footer\"><button class=\"btn btn-default btn-lg pull-right mb-control-close\">Fechar</button></div></div></div></div></LimparScript>";
	return $msg;	
}

function MensagemConfirmar($titulo, $texto, $tipo, $link, $fa, $bt1, $bt2){
	
	$msg = "<script>$('.mb-control-close').on('click',function(){ $(this).parents('#MensagemBox').removeClass('open'); return false; }); $('#MensagemBox').toggleClass('open');</script><div class=\"message-box animated fadeIn\" id=\"MensagemBox\"><div class=\"mb-container\"><div class=\"mb-middle\"><div class=\"mb-title\"><span class=\"fa ".$fa."\"></span> ".$titulo."</div><div class=\"mb-content\"><p>".$texto."</p></div><div class=\"mb-footer\"><div class=\"pull-right\"><a href=\"".$link."\" class=\"btn btn-".$tipo." btn-lg\">".$bt1."</a>&nbsp;<button class=\"btn btn-default btn-lg mb-control-close\">".$bt2."</button></div></div></div></div></div>";
	return $msg;	
}

function MensagemConfirmarJS($id, $titulo, $texto, $tipo, $link, $fa, $bt1, $bt2){

	$msg = "<script>$(function(){ $(\"a.MensagemJS\").click(function() { var id = '".$id."'; $(\".mb-content\").html(''); $(\".mb-content\").html('<p><center>Aguarde, esta ação poderá demorar...<br><img src=\"img/owl/AjaxLoader.gif\"></p></center>'); $.post('".$link.".php', {id: id}, function(resposta) { $(\"#MensagemBox\").removeClass('open'); $(\"#StatusGeral\").html(''); $(\"#StatusGeral\").html(resposta); }); }); }); $('.mb-control-close').on('click',function(){ $(this).parents('#MensagemBox').removeClass('open'); return false; }); $('#MensagemBox').toggleClass('open');</script><div class=\"message-box animated fadeIn\" id=\"MensagemBox\"><div class=\"mb-container\"><div class=\"mb-middle\"><div class=\"mb-title\"><span class=\"fa ".$fa."\"></span> ".$titulo."</div><div class=\"mb-content\"><p>".$texto."</p></div><div class=\"mb-footer\"><div class=\"pull-right\"><a class=\"MensagemJS btn btn-".$tipo." btn-lg\">".$bt1."</a>&nbsp;<button class=\"btn btn-default btn-lg mb-control-close\">".$bt2."</button></div></div></div></div></div>";
	return $msg;
}

function MensagemConfirmarJS2($camposMarcados, $status, $titulo, $texto, $tipo, $link, $fa, $bt1, $bt2){
	
	$string_array = implode("|", $camposMarcados);

	$msg = "<script>$(function(){ $(\"a.MensagemJS\").click(function() { var i, camposMarcados, string_array; string_array = '".$string_array."'; camposMarcados = string_array.split('|'); var status = '".$status."'; $(\".mb-content\").html(''); $(\".mb-content\").html('<p><center>Aguarde, esta ação poderá demorar...<br><img src=\"img/owl/AjaxLoader.gif\"></p></center>'); $.post('".$link.".php', {camposMarcados: camposMarcados, status: status}, function(resposta) { $(\"#MensagemBox\").removeClass('open'); $(\"#StatusGeral\").html(''); $(\"#StatusGeral\").html(resposta); }); }); }); $('.mb-control-close').on('click',function(){ $(this).parents('#MensagemBox').removeClass('open'); return false; }); $('#MensagemBox').toggleClass('open');</script><div class=\"message-box animated fadeIn\" id=\"MensagemBox\"><div class=\"mb-container\"><div class=\"mb-middle\"><div class=\"mb-title\"><span class=\"fa ".$fa."\"></span> ".$titulo."</div><div class=\"mb-content\"><p>".$texto."</p></div><div class=\"mb-footer\"><div class=\"pull-right\"><a class=\"MensagemJS btn btn-".$tipo." btn-lg\">".$bt1."</a>&nbsp;<button class=\"btn btn-default btn-lg mb-control-close\">".$bt2."</button></div></div></div></div></div>";
	return $msg;
}

function Redirecionar($url){
	return "<script language= \"JavaScript\">
			location.href=\"".$url."\"
			</script>";
}

function FullRedirect($url){
	return "<script language= \"JavaScript\">
			window.location.replace(\"".$url."\")
			</script>";
}

function ArvoreUser($CadUser){	
	include("conexao.php");
	$Arvore = array();
	
	$AcessoUser = 3;
	$AcessoTeste = 4;
	$SQL = "SELECT id FROM login WHERE id_cad = :id_cad AND acesso = :AcessoUser OR id_cad = :id_cad AND acesso = :AcessoTeste";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_STR);
	$SQL->bindParam(':AcessoUser', $AcessoUser, PDO::PARAM_STR);
	$SQL->bindParam(':AcessoTeste', $AcessoTeste, PDO::PARAM_STR);
	$SQL->execute();
	
	while($LnSelect = $SQL->fetch()){
		if($LnSelect['id'] != $CadUser){
		$Arvore[] = $LnSelect['id'];
		$SomarArvore = ArvoreUser($LnSelect['id']);
		$Arvore = array_merge($Arvore, $SomarArvore);
		}
	}
	
	return array_values(array_unique($Arvore));
}

function ArvoreUsuario($id){	
	include("conexao.php");
	
	$SQL2 = "SELECT id, id_cad, login FROM login WHERE id = '".$id."'";
	$SQL2 = $banco->prepare($SQL2);
	$SQL2->execute();
	$LnSelect2 = $SQL2->fetch();
		
	$SQL = "SELECT id, id_cad, login FROM login";
	$SQL = $banco->prepare($SQL);
	$SQL->execute();
	
	$_arvore = array();
	$_arvore[] = $LnSelect2['login'];
	while($LnSelect = $SQL->fetch()){
		
		if($LnSelect2['id_cad'] == $LnSelect['id']){
		$_arvore[] = $LnSelect['login'];
		if($id != $LnSelect['id_cad']){
		$valor = ArvoreUsuario($LnSelect['id_cad']);
		$_arvore = array_merge($_arvore, $valor);
		}
		}
		
	}
	return array_values(array_unique($_arvore));
	
}

function ExplodeArvore($login, $exibir){
	include("conexao.php");
		
	$ex = explode($login." >", $exibir);
	$exibirRevFinal = $login." > ".$ex[1];
	
	return $exibirRevFinal;
}

function ArvoreUsuarioExibir($id){	
	$exibirRev = "";	
	$_arvore = ArvoreUsuario($id);
	$ArvoreFinal = array_reverse($_arvore);
	$ArvoreFinalTotal = count($ArvoreFinal);
	for($i = 0; $i < $ArvoreFinalTotal; $i++){
		$exibirRev .= $ArvoreFinal[$i];
		
		if($i != ($ArvoreFinalTotal - 1) ){
			$exibirRev .= " > ";
		}
	}
	
	return $exibirRev;
}	

function SelecionarExibirAll($id, $login){
	include("conexao.php");

	$return = "<option value=\"".$id."\">".$login."</option>";
		
	$TodosArvoreRev = TodosArvoreRev($id);
	for($i = 0; $i < count($TodosArvoreRev); $i++){
	$ArvoreUsuarioExibir = ArvoreUsuarioExibir($TodosArvoreRev[$i]);
	$ExplodeArvore = ExplodeArvore($login, $ArvoreUsuarioExibir);
		$return .= "<option value=\"".$TodosArvoreRev[$i]."\">".$ExplodeArvore."</option>";
	}
	
	return $return;
	
}

function TodosArvoreRev($UserOnline){	
	include("conexao.php");
	
	$SQL = "SELECT id, acesso FROM login WHERE id_cad = '".$UserOnline."'";
	$SQL = $banco->prepare($SQL);
	$SQL->execute();
	
	$_arvore = array();
	while($LnSelect = $SQL->fetch()){
		
		if($LnSelect['acesso'] == 2){
		$_arvore[] = $LnSelect['id'];
		}
		if($UserOnline != $LnSelect['id']){
		$valor = TodosArvoreRev($LnSelect['id']);
		$_arvore = array_merge($_arvore, $valor);
		}
		
	}
return array_values(array_unique($_arvore));
}

function ArvoreRev($CadUser){	
	include("conexao.php");
	$Arvore = array();
	
	$acesso = 2;
	$SQL = "SELECT id FROM login WHERE id_cad = :id_cad AND acesso = :acesso";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_STR);
	$SQL->bindParam(':acesso', $acesso, PDO::PARAM_STR);
	$SQL->execute();
	
	while($LnSelect = $SQL->fetch()){
		if($LnSelect['id'] != $CadUser){
		$Arvore[] = $LnSelect['id'];
		$SomarArvore = ArvoreRev($LnSelect['id']);
		$Arvore = array_merge($Arvore, $SomarArvore);
		}
	}
	
	return array_values(array_unique($Arvore));
}

function SelecionarExibir($id, $login){
	include("conexao.php");
	
	$return = "<option>-- Selecione --</option>";
	$return .= "<option value=\"Todos\">Todos</option><option value=\"".$id."\">".$login."</option>";
		
	$TodosArvoreRev = TodosArvoreRev($id);
	for($i = 0; $i < count($TodosArvoreRev); $i++){
	$ArvoreUsuarioExibir = ArvoreUsuarioExibir($TodosArvoreRev[$i]);
	$ExplodeArvore = ExplodeArvore($login, $ArvoreUsuarioExibir);
		$return .= "<option value=\"".$TodosArvoreRev[$i]."\">".$ExplodeArvore."</option>";
	}
	
	return $return;
	
}

function SelecionarAlterarRev($CadUser){	
	include("conexao.php");
	
	$retornar = "";
	
	$acesso = 2;
	$SQL = "SELECT id, login FROM login WHERE id_cad = :id_cad AND acesso = :acesso";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_INT);
	$SQL->bindParam(':acesso', $acesso, PDO::PARAM_INT);
	$SQL->execute();
	
	while($LnSelect = $SQL->fetch()){
		$retornar .= "<option value=\"".$LnSelect['id']."\">".$LnSelect['login']."</option>";
	}
	return $retornar;
}

function ExcluirPorUsuario($CadUser){
	include("conexao.php");
	
	$SQL = "SHOW TABLES";
	$SQL = $banco->prepare($SQL);
	$SQL->execute();
	while($Ln = $SQL->fetch(PDO::FETCH_NUM)) { 
		$SQLTable = "SHOW FIELDS FROM ".$Ln[0]." WHERE Field = 'id_cad'";
		$SQLTable = $banco->prepare($SQLTable);
		$SQLTable->execute();
		$TotalResul = count($SQLTable->fetchAll());	
		
		if($TotalResul > 0){
			
		$SQLDel = "DELETE FROM ".$Ln[0]." WHERE id_cad = :id_cad";
		$SQLDel = $banco->prepare($SQLDel);
		$SQLDel->bindParam(':id_cad', $CadUser, PDO::PARAM_INT); 
		$SQLDel->execute();
		
		
		}
	}
	
	return 1;	
}


function ExcluirSSHUser($CadUser){
	
		$SQLUser = "SELECT login, operadora FROM login WHERE id_cad = :id_cad";
		$SQLUser = $banco->prepare($SQLUser);
		$SQLUser->bindParam(':id_cad', $CadUser, PDO::PARAM_INT);
		$SQLUser->execute();
		
		while($Ln = $SQLUser->fetch()){
		
		//Seleciona o servidor
		$SQLServer = "SELECT * FROM servidor WHERE nome = :nome";
		$SQLServer = $banco->prepare($SQLServer);
		$SQLServer->bindParam(':nome', $Ln['operadora'], PDO::PARAM_STR);
		$SQLServer->execute();
		$LnServer = $SQLServer->fetch();	
			
		$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
		ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
		$stream = ssh2_exec($connection, 'sudo pkill -KILL -u '.$Ln['login'].'');
		$stream = ssh2_exec($connection, 'sudo userdel '.$Ln['login'].'');
		stream_set_blocking($stream, true);
		stream_set_timeout($stream, 15);
		fclose($stream);
		}
		
		return true;
		
}

function cut_str($str, $left, $right){
		$str = substr(stristr($str, $left) , strlen($left));
		$leftLen = strlen(stristr($str, $right));
		$leftLen = $leftLen ? -($leftLen) : strlen($str);
		$str = substr($str, 0, $leftLen);
		return $str;
	}
	
function PerfilAdminEditar(){
	include("conexao.php");
	$Perfil = "";	
	
	$SQLServer = "SELECT nome FROM servidor";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->execute();
	
		while($LnServer = $SQLServer->fetch()){
			$Perfil .= "<div style=\"border:0px; padding: 0px 0px 5px 0px;\" class=\"col-md-5\"><label class=\"check\"><input name=\"EditarOperadora[]\" id=\"EditarOperadora[]\" value=\"".$LnServer['nome']."\" type=\"checkbox\" class=\"icheckbox\" /> ".$LnServer['nome']."</label></div>";	
		}
		
	if( empty($Perfil)) $Perfil = "<div style=\"border:0px;\" class=\"col-md-5\"><label class=\"check\">Não existe operadora cadastrada.</label></div>";
	return $Perfil;
}

function PerfilAdminEditarTeste(){
	include("conexao.php");
	$Perfil = "";	
	
	$SQLServer = "SELECT nome FROM servidor";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->execute();
	
		while($LnServer = $SQLServer->fetch()){
			$Perfil .= "<option value=\"".$LnServer['nome']."\">".$LnServer['nome']."</option>";	
		}
		
	if( empty($Perfil)) $Perfil = "<option value=\"0\">Não existe operadora cadastrada.</option>";
	return $Perfil;
}

function PerfilAdminEditar2($User){
	include("conexao.php");
	$Perfil = "";	
	
	$SQLServer = "SELECT nome FROM servidor";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->execute();
	
	$SQLPerfil = "SELECT operadora FROM login WHERE login = :login";
	$SQLPerfil = $banco->prepare($SQLPerfil);
	$SQLPerfil->bindParam(':login', $User, PDO::PARAM_STR); 
	$SQLPerfil->execute();
	$LnPerfil = $SQLPerfil->fetch();
	
		while($LnServer = $SQLServer->fetch()){
			$ckecked = substr_count($LnPerfil['operadora'], $LnServer['nome']) > 0 ? "checked=\"checked\"" : "";
			$Perfil .= "<div style=\"border:0px; padding: 0px 0px 5px 0px;\" class=\"col-md-5\"><label class=\"check\"><input name=\"EditarOperadora[]\" id=\"EditarOperadora[]\" value=\"".$LnServer['nome']."\" type=\"checkbox\" class=\"icheckbox\" ".$ckecked." /> ".$LnServer['nome']."</label></div>";	
		}
		
	if( empty($Perfil)) $Perfil = "<div style=\"border:0px;\" class=\"col-md-5\"><label class=\"check\">Não existe operadora cadastrada.</label></div>";
	return $Perfil;
}

function UrlAtual(){
 $dominio= $_SERVER['HTTP_HOST'];
 $rest = $_SERVER['REQUEST_URI'];
 $ex = explode("/", $rest);
 $e = end($ex);
 $pasta = str_replace($e,"",$rest);
 $url = "http://" . $dominio.$pasta;
 return $url;
 }
 
function PerfilTeste(){
	include("conexao.php");
	$Perfil = "";	
	
	$SQLServer = "SELECT nome FROM servidor";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->execute();
	
		while($LnServer = $SQLServer->fetch()){
			$Perfil .= "<option value=\"".$LnServer['nome']."\">".$LnServer['nome']."</option>";	
		}
		
	if( empty($Perfil)) $Perfil = "<option value=\"0\">Não existe operadora cadastrada.</option>";
	return $Perfil;
}

function ValidacaoCelular($celular){
	
	//Digitos DDD
	$c = explode("(",$celular);
	$c2 = explode(")",$c[1]);
	$ddd = $c2[0];
	
	$p1 = explode(")",$celular);
	$pF = trim($p1[1]);
	$pF = str_replace("-","",$pF);
	$pF = str_replace(" ","",$pF);
	
	if(substr_count($celular, "(") == 0 || substr_count($celular, ")") == 0) {
		return 1;
	}
	elseif(strlen($ddd) > 2){
		return 2;
	}
	elseif(strlen($pF) < 8){
		return 3;
	}
	elseif(strlen($pF) > 9){
		return 4;
	}	
}

function makeRandomPassword(){
 		
		$pass = "";
        $salt = "abchefghjkmnpqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        $i = 0;
 
        while ($i <= 4){
 
            $num = rand() % 33;
            $tmp = substr($salt, $num, 1);
            $pass = $pass . $tmp;
            $i++;
 
        }
        return $pass;
}

function LimparCelular($celular){
	$celular = str_replace("(","",$celular);
	$celular = str_replace(")","",$celular);
	$celular = str_replace("-","",$celular);
	$celular = str_replace(" ","",$celular);
	
	return trim($celular);
}

function EnviarSMS($mensagem, $celular){
	include("conexao.php");
	
	//Seleciona o servidor
	$SQLSMS = "SELECT login, senha FROM sms";
	$SQLSMS = $banco->prepare($SQLSMS);
	$SQLSMS->execute();
	$LnSMS = $SQLSMS->fetch();

	$usuario = $LnSMS['login'];	
	$senha = $LnSMS['senha'];	
	
	$celular = LimparCelular($celular);
	$celular = "55".$celular;
		
	$url = "http://torpedus.com.br/sms/index.php?app=webservices&u=".urlencode($usuario)."&p=".urlencode($senha)."&ta=pv&to=".urlencode($celular)."&msg=".urlencode($mensagem)."";
	
	$curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $msg = curl_exec($curl);
    curl_close($curl);
	
	$resp = trim(cut_str($msg, "STATUS:", ">>>"));
	
	if($resp == "SMS Aceito"){
		return 1;
	}else{
		return 2;
	}
	
}

function LeituraTxt(){
	$fp = fopen("ver.txt", "r");
	$linha = fgets($fp);
	fclose($fp);
	
	return trim($linha);
}

function CriarTxt($texto){
	$fp = fopen("ver.txt", "w");
	$escreve = fwrite($fp, $texto);
	fclose($fp);
	
	return false;
}

function ArvoreRevReverso($id){	
	include("conexao.php");
	
	$SQL2 = "SELECT id, id_cad FROM login WHERE id = '".$id."'";
	$SQL2 = $banco->prepare($SQL2);
	$SQL2->execute();
	$LnSelect2 = $SQL2->fetch();
		
	$SQL = "SELECT id, id_cad FROM login";
	$SQL = $banco->prepare($SQL);
	$SQL->execute();
	
	$_arvore = array();
	$_arvore[] = $LnSelect2['id'];
	while($LnSelect = $SQL->fetch()){
		
		if($LnSelect2['id_cad'] == $LnSelect['id']){
		$_arvore[] = $LnSelect['id'];
		if($id != $LnSelect['id_cad']){
		$valor = ArvoreRevReverso($LnSelect['id_cad']);
		$_arvore = array_merge($_arvore, $valor);
		}
		}
		
	}
	return array_values(array_unique($_arvore));
	
}

function VerificarLimiteTeste($CadUser){
	include("conexao.php");
	
	$ArvoreRev = ArvoreRevReverso($CadUser);
			
	for($i=0; $i < count($ArvoreRev); $i++){
		$Revendedor = $ArvoreRev[$i];
		
		$SQL = "SELECT LimiteTeste FROM login WHERE id = :id";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':id', $Revendedor, PDO::PARAM_STR);
		$SQL->execute();
		$Ln = $SQL->fetch();
				
		if(!empty($Ln['LimiteTeste'])) return $Ln['LimiteTeste'];
	}
	
	return 0;
	
}

function VerificarCotaTeste($CadUser){
	include("conexao.php");
	
	$DataAtual = date('Y-m-d');
	$DataAtual = strtotime($DataAtual);
	$acesso = 4;
		
	$SQL = "SELECT id FROM login WHERE data = :data AND id_cad = :id_cad AND acesso = :acesso";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':data', $DataAtual, PDO::PARAM_STR);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_STR);
	$SQL->bindParam(':acesso', $acesso, PDO::PARAM_STR);
	$SQL->execute();
	$Total = count($SQL->fetchAll());
	
	return $Total;
}

function FotoPerfil($foto){
	if(empty($foto)){
		$f = "img/icone/sem-perfil.ico";
	}
	else{
		$f = "img/icone/".$foto;
		if(file_exists($f)){
			return $f;
		}
		else{
			return "img/icone/sem-perfil.ico";
		}
		
	}
	return $f;
}

function NomeMes($mes){
	
	if($mes == 1){
		return "Janeiro";
	}
	elseif($mes == 2){
		return "Fevereiro";
	}
	elseif($mes == 3){
		return "Março";
	}
	elseif($mes == 4){
		return "Abril";
	}
	elseif($mes == 5){
		return "Maio";
	}
	elseif($mes == 6){
		return "Junho";
	}
	elseif($mes == 7){
		return "Julho";
	}
	elseif($mes == 8){
		return "Agosto";
	}
	elseif($mes == 9){
		return "Setembro";
	}
	elseif($mes == 10){
		return "Outubro";
	}
	elseif($mes == 11){
		return "Novembro";
	}
	elseif($mes == 12){
		return "Dezembro";
	}
	
	return false;
	
}

function VerificarRelatorioMes(){
	include("conexao.php");
	$id_cad = $_SESSION['id'];
	
	$ArrayMes = array();
	
	$mes = date('n');
	
	$ArrayMes[] = $mes;
	$return = "<option value=\"".$mes."\">".NomeMes($mes)."</option>";
	
	$SQL = "SELECT mes FROM relatorio WHERE id_cad = :id_cad";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $id_cad, PDO::PARAM_STR);
	$SQL->execute();
	
	while($Ln = $SQL->fetch()){
		if(!in_array($Ln['mes'], $ArrayMes)) {
			$ArrayMes[] = $Ln['mes'];
			$return .= "<option value=\"".$Ln['mes']."\">".NomeMes($Ln['mes'])."</option>";
		}
	}
	
	return $return;
}

function VerificarRelatorioAno(){
	include("conexao.php");
	$id_cad = $_SESSION['id'];
	
	$ArrayAno = array();
	
	$Ano = date('Y');
	
	$ArrayAno[] = $Ano;
	$return = "<option value=\"".$Ano."\">".$Ano."</option>";
	
	$SQL = "SELECT ano FROM relatorio WHERE id_cad = :id_cad";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $id_cad, PDO::PARAM_STR);
	$SQL->execute();
	
	while($Ln = $SQL->fetch()){
		if(!in_array($Ln['ano'], $ArrayAno)) {
			$ArrayAno[] = $Ln['ano'];
			$return .= "<option value=\"".$Ln['ano']."\">".$Ln['ano']."</option>";
		}
	}
	
	return $return;
}

function SelecionarExibirRelatorio($id, $login){
	include("conexao.php");
	
	$return = "<option value=\"".$id."\">".$login."</option>";
		
	$TodosArvoreRev = TodosArvoreRev($id);
	for($i = 0; $i < count($TodosArvoreRev); $i++){
	$ArvoreUsuarioExibir = ArvoreUsuarioExibir($TodosArvoreRev[$i]);
	$ExplodeArvore = ExplodeArvore($login, $ArvoreUsuarioExibir);
		$return .= "<option value=\"".$TodosArvoreRev[$i]."\">".$ExplodeArvore."</option>";
	}
	
	return $return;
	
}

function GerarRelatorio($id_cad, $rev, $mes, $ano){
	include("conexao.php");
	$relatorio = array();
	$SelectMes = "";
	$SelectAno = "";
	
	if($mes != "T"){
		$SelectMes = " AND mes = :mes";
	}
	
	if($ano != "T"){
		$SelectAno = " AND ano = :ano";
	}

	if($rev == "S"){
		$CadUser = ArvoreRev($id_cad);
		$CadUser[] = $id_cad;
		$CadUser = implode(',', $CadUser);
		$Pesq = "FIND_IN_SET(id_cad,:id_cad)".$SelectMes.$SelectAno;
	}
	else{
		$CadUser = $id_cad;
		$Pesq = "id_cad = :id_cad".$SelectMes.$SelectAno;
	}
	
	$SQL = "SELECT id_cad, mes, ano, usuario, data FROM relatorio WHERE ".$Pesq;
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_STR);
	if($mes != "T") $SQL->bindParam(':mes', $mes, PDO::PARAM_STR);
	if($ano != "T") $SQL->bindParam(':ano', $ano, PDO::PARAM_STR);
	$SQL->execute();
	
	while($Ln = $SQL->fetch()){
		$relatorio[] = array($Ln['id_cad'], $Ln['mes'], $Ln['ano'], $Ln['usuario'], $Ln['data']);
	}
	
	return $relatorio;
}

function GerarRelatorioFinal($id_cad, $rev, $mes, $ano){
	include("conexao.php");
	$relatorio = array();
	$SelectMes = "";
	$SelectAno = "";
	$acessoAdmin = 1;
	$acessoRev = 2;
	$IDUserOn = $_SESSION['id'];
	
	if($mes != "T"){
		$SelectMes = " AND mes = :mes";
	}
	
	if($ano != "T"){
		$SelectAno = " AND ano = :ano";
	}

	if($rev == "S"){
		$CadUser = $id_cad;
		$Pesq = "id_cad = :id_cad AND acesso = :acessoAdmin OR id = :id AND acesso = :acessoAdmin OR id_cad = :id_cad AND acesso = :acessoRev OR id = :id AND acesso = :acessoRev";
	}
	else{
		$CadUser = $id_cad;
		$Pesq = "id = :id_cad AND acesso = :acessoAdmin OR id = :id_cad AND acesso = :acessoRev";
	}
	
	$SQL = "SELECT id, login, ValorCobrado FROM login WHERE ".$Pesq;
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_STR);
	$SQL->bindParam(':acessoAdmin', $acessoAdmin, PDO::PARAM_STR);
	$SQL->bindParam(':acessoRev', $acessoRev, PDO::PARAM_STR);
	if($rev == "S") $SQL->bindParam(':id', $CadUser, PDO::PARAM_STR);
	$SQL->execute();
	
	while($Ln = $SQL->fetch()){
				
		if($IDUserOn == $Ln['id']){
			$Pesq2 = "id_cad = :id_cad".$SelectMes.$SelectAno;
			$SQLTo = "SELECT id FROM relatorio WHERE ".$Pesq2;
			$SQLTo = $banco->prepare($SQLTo);
			$SQLTo->bindParam(':id_cad', $Ln['id'], PDO::PARAM_STR);
			if($mes != "T") $SQLTo->bindParam(':mes', $mes, PDO::PARAM_STR);
			if($ano != "T") $SQLTo->bindParam(':ano', $ano, PDO::PARAM_STR);
			$SQLTo->execute();
			$Total = count($SQLTo->fetchAll());
		}
		else{
			$CadUser2 = ArvoreRev($Ln['id']);
			$CadUser2[] = $Ln['id'];
			$CadUser2 = implode(',', $CadUser2);
		
			$Pesq2 = "FIND_IN_SET(id_cad,:id_cad)".$SelectMes.$SelectAno;
			$SQLTo = "SELECT id FROM relatorio WHERE ".$Pesq2;
			$SQLTo = $banco->prepare($SQLTo);
			$SQLTo->bindParam(':id_cad', $CadUser2, PDO::PARAM_STR);
			if($mes != "T") $SQLTo->bindParam(':mes', $mes, PDO::PARAM_STR);
			if($ano != "T") $SQLTo->bindParam(':ano', $ano, PDO::PARAM_STR);
			$SQLTo->execute();
			$Total = count($SQLTo->fetchAll());
		}
		
		$ValorPagar = $Total * $Ln['ValorCobrado'];
		$relatorio[] = array($Ln['login'], $Ln['ValorCobrado'], $Total, $ValorPagar);
		
	}
	
	return $relatorio;
}

function ConverterDinheiro($valor){
	$data = str_replace("R$","",$valor);
	$data = str_replace("€","",$data);
	$data = str_replace("US$","",$data);
	$data = str_replace(",",".",$data);
	$data = str_replace(" ",".",trim($data));
	return trim($data);
}

function ConverterDataTime($data){
		return date('d/m/Y', $data);
}

function ConverterData($data){
		$ex = explode("/",$data);
		$return =  $ex[2]."-".$ex[1]."-".$ex[0];
		
		return strtotime($return);
}

function InfoConfigSuporte(){
	include("conexao.php");

	$CadUser = $_SESSION['login'];
	
	if( isset($_SESSION['SuportePaginacao']) ){
		return array(ceil($_SESSION['SuportePaginacao']));
	}
	else{
	
	$SQL = "SELECT SuportePaginacao FROM config_suporte WHERE id_cad = :id_cad";
	$SQL = $banco->prepare($SQL);
	$SQL->bindParam(':id_cad', $CadUser, PDO::PARAM_STR);
	$SQL->execute();
	$Total = count($SQL->fetchAll());
	
		if($Total > 0){
			$SQL->execute();
			$Ln = $SQL->fetch();
			
			$_SESSION['SuportePaginacao'] = ceil($Ln['SuportePaginacao']);
			return array(ceil($Ln['SuportePaginacao']));
			
		}
		else{
			$_SESSION['SuportePaginacao'] = 10;
			return array(10);
		}
	}
}

function LimitarTexto($texto, $limite){
  $contador = strlen($texto);
  if ( $contador >= $limite ) {      
      $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '';
      return $texto;
  }
  else{
    return $texto;
  }
} 

function DataSuporte($data){
	
	$hoje = date("j", time());
	$ontem = date("j", time() - (24*3600) ); 
	
	$dia = date("j", $data);
	$mes = date("n", $data);
	
	$hora = date("H", $data);
	$minuto = date("i", $data);
	
	if($mes == 1){
		$mesBr = "Jan";
	}
	elseif($mes == 2){
		$mesBr = "Fev";
	}
	elseif($mes == 3){
		$mesBr = "Mar";
	}
	elseif($mes == 4){
		$mesBr = "Abr";
	}
	elseif($mes == 5){
		$mesBr = "Maio";
	}
	elseif($mes == 6){
		$mesBr = "Jun";
	}
	elseif($mes == 7){
		$mesBr = "Jul";
	}
	elseif($mes == 8){
		$mesBr = "Ago";
	}
	elseif($mes == 9){
		$mesBr = "Set";
	}
	elseif($mes == 10){
		$mesBr = "Out";
	}
	elseif($mes == 11){
		$mesBr = "Nov";
	}
	elseif($mes == 12){
		$mesBr = "Dez";
	}
	
	if($dia == $hoje){
		$DataRetorno = "Hoje".", ".$hora.":".$minuto."";
	}
	elseif($dia == $ontem){
		$DataRetorno = "Ontem".", ".$hora.":".$minuto."";
	}
	else{
		$DataRetorno = $mesBr." ".date("d", $data);
	}
	
	return $DataRetorno;
}

function DataSuporte2($data){	
	$hoje = date("j", time());
	$ontem = date("j", time() - (24*3600) ); 
	
	$dia = date("j", $data);
	$mes = date("n", $data);
	
	$hora = date("H", $data);
	$minuto = date("i", $data);
	
	if($mes == 1){
		$mesBr = "Jan";
	}
	elseif($mes == 2){
		$mesBr = "Fev";
	}
	elseif($mes == 3){
		$mesBr = "Mar";
	}
	elseif($mes == 4){
		$mesBr = "Abr";
	}
	elseif($mes == 5){
		$mesBr = "Maio";
	}
	elseif($mes == 6){
		$mesBr = "Jun";
	}
	elseif($mes == 7){
		$mesBr = "Jul";
	}
	elseif($mes == 8){
		$mesBr = "Ago";
	}
	elseif($mes == 9){
		$mesBr = "Set";
	}
	elseif($mes == 10){
		$mesBr = "Out";
	}
	elseif($mes == 11){
		$mesBr = "Nov";
	}
	elseif($mes == 12){
		$mesBr = "Dez";
	}
	
	if($dia == $hoje){
		$DataRetorno = "Hoje".", ".$mesBr." ".date("d", $data).", ".$hora.":".$minuto."";
	}
	elseif($dia == $ontem){
		$DataRetorno = "Ontem".", ".$mesBr." ".date("d", $data).", ".$hora.":".$minuto."";
	}
	else{
		$DataRetorno = $mesBr." ".date("d", $data).", ".$hora.":".$minuto;
	}
	
	return $DataRetorno;
}

function ImagemAnexo($anexo){
	
	if( ($anexo == "zip") || ($anexo == "rar") ){
		$img = "<img src=\"img/filetree/zip.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "xls"){
		$img = "<img src=\"img/filetree/xls.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "txt"){
		$img = "<img src=\"img/filetree/txt.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "psd"){
		$img = "<img src=\"img/filetree/psd.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "ppt"){
		$img = "<img src=\"img/filetree/ppt.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif( ($anexo == "gif") || ($anexo == "png") || ($anexo == "jpg") || ($anexo == "jpeg") ){
		$img = "<img src=\"img/filetree/picture.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "php"){
		$img = "<img src=\"img/filetree/php.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "pdf"){
		$img = "<img src=\"img/filetree/pdf.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "mp3"){
		$img = "<img src=\"img/filetree/music.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif( ($anexo == "html") || ($anexo == "htm") ){
		$img = "<img src=\"img/filetree/html.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif( ($anexo == "doc") || ($anexo == "docm")){
		$img = "<img src=\"img/filetree/doc.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif( ($anexo == "db") || ($anexo == "sql") ){
		$img = "<img src=\"img/filetree/db.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	elseif($anexo == "css"){
		$img = "<img src=\"img/filetree/css.png\" width=\"16\" height=\"16\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$anexo."\" data-original-title=\"".$anexo."\"/>";
	}
	else{
		$img = "<span class=\"label label-primary\">".$anexo."</span>";
	}
	
	return $img;
}

function UrlTeste($acao, $code = NULL){
	if($acao == 1){
		$CadUser = $_SESSION['login'];
		$url = UrlAtual()."cadtest.php?r=".base64_encode(base64_encode(base64_encode(base64_encode($CadUser))));
	}
	elseif($acao == 2){
		$url = base64_decode(base64_decode(base64_decode(base64_decode($code))));
	}
	
	return trim($url);
	
}

function VerTeste($CadUser){
	include("conexao.php");	
	$status = 0;
	
	$SQLUrlT = "SELECT status, tempo, cemail, email FROM urlteste WHERE CadUser = :CadUser";
	$SQLUrlT = $banco->prepare($SQLUrlT);
	$SQLUrlT->bindParam(':CadUser', $CadUser, PDO::PARAM_STR);
	$SQLUrlT->execute();
	$LnUrlT = $SQLUrlT->fetch();
	$tempo = empty($LnUrlT['tempo']) ? 0 : $LnUrlT['tempo'];
	$cemail = empty($LnUrlT['cemail']) ? "N" : $LnUrlT['cemail'];
	$email = empty($LnUrlT['email']) ? "" : $LnUrlT['email'];
	
	if(empty($LnUrlT)){
		$status = 0;
	}
	elseif($LnUrlT['status'] == "S"){
		$status = 1;
	}
	else{
		$status = 0;
	}
	
	
	return array($status, $tempo, $cemail, $email);
}
?>