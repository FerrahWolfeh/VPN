<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
	$result = "";
	//Seleciona os servidores
	$SQLServer = "SELECT * FROM servidor";
	$SQLServer = $banco->prepare($SQLServer);
	$SQLServer->execute();
	
	$ArrayServer = array();
	while($LnServer = $SQLServer->fetch()){
		if(!in_array($LnServer['server'], $ArrayServer)) {
		$ArrayServer[] = $LnServer['server'];
			$connection = ssh2_connect($LnServer['server'], $LnServer['porta']);
			ssh2_auth_password($connection, $LnServer['user'], $LnServer['senha']);
			$stream = ssh2_exec($connection, 'ps aux | grep priv | grep Ss');
			stream_set_blocking($stream, true);
			$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
			$result .= stream_get_contents($stream_out);
		}
	}
						
$UserOnline = $_SESSION['id'];
$IdUser = (isset($_POST['id'])) ? $_POST['id'] : '';
$DataAtual = time();

if($IdUser == "Todos"){
	$CadUser = ArvoreRev($UserOnline);
	$CadUser[] = $UserOnline;
	$CadUser = implode(',', $CadUser);
	
	$acesso = 3;
	$acessoT = 4;
	
	$SQLUser = "SELECT * FROM login WHERE FIND_IN_SET(id_cad,'".$CadUser."') AND acesso = :acesso OR FIND_IN_SET(id_cad,'".$CadUser."') AND acesso = :acessoT";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
	$SQLUser->bindParam(':acessoT', $acessoT, PDO::PARAM_INT);
	$SQLUser->execute();
}else{
	$acesso = 3;
	$acessoT = 4;
	
	$SQLUser = "SELECT * FROM login WHERE id_cad = :id_cad AND acesso = :acesso OR id_cad = :id_cad AND acesso = :acessoT";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':id_cad', $IdUser, PDO::PARAM_STR);
	$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
	$SQLUser->bindParam(':acessoT', $acessoT, PDO::PARAM_INT);
	$SQLUser->execute();
}
?>

<table id="Tabela" class="table datatable">
                               <thead>
                               		<tr>
                                        <th>Nome</th>
                                        <th>Usuário</th>
                                        <th>Criador Por</th>
                                        <th>Status</th>
                                        <th>Operadora</th>
                                        <th>Conexão</th>
                                        <th>Derrubado</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
     
                                
                                        <?php		

										while($LnUser = $SQLUser->fetch()){
											
										$SqlS = "SELECT icone FROM servidor WHERE nome = :nome";
										$SqlS = $banco->prepare($SqlS);
										$SqlS->bindParam(':nome', $LnUser['operadora'], PDO::PARAM_STR);
										$SqlS->execute();
										$LnU = $SqlS->fetch();
										
										$SqlI = "SELECT imagem FROM icone_perfil WHERE id = :id";
										$SqlI = $banco->prepare($SqlI);
										$SqlI->bindParam(':id', $LnU['icone'], PDO::PARAM_STR);
										$SqlI->execute();
										$LnI = $SqlI->fetch();
										$FotoPerfil = FotoPerfil($LnI['imagem']);
										$icone_perfil = "<img src=\"".$FotoPerfil."\" height=\"20\" width=\"20\">";
											
										$conexao = substr_count($result, "sshd: ".trim($LnUser['login'])." [priv]");
										$conexao = empty($conexao) ? 0 : $conexao;
																				
										$SqlU = "SELECT login FROM login WHERE id = :id";
										$SqlU = $banco->prepare($SqlU);
										$SqlU->bindParam(':id', $LnUser['id_cad'], PDO::PARAM_INT);
										$SqlU->execute();
										$LnU = $SqlU->fetch();
										
										if($conexao > 0) {
											$status = "&nbsp;&nbsp;<span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Online\">Online</span>";
										}else{
											$status = "&nbsp;&nbsp;<span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Offline\">Offline</span>";
										}
																																																		
										echo "
                                        <tr>
                                        	<td>".$LnUser['nome']."</td>
                                        	<td>".$LnUser['login']."</td>";
									
										echo "<td>".$LnU['login']."</td>";
										
										echo "<td>".$status."</td>";
										
										echo "<td>".$icone_perfil."</td>";
										
										echo "<td>".$conexao."</td>";
										
										echo "<td>".$LnUser['derrubado']."</td>";
										
										echo "<td>";
										if($LnUser['bloqueado'] == "S"){
											echo "<a class=\"desbloquear label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Desbloquear\" Onclick=\"DesbloquearUser('".$LnUser['id']."')\"><i class=\"fa fa-unlock-alt\"></i></a>&nbsp;";
										}else{
											echo "<a class=\"bloquear label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Bloquear\" Onclick=\"BloquearUser('".$LnUser['id']."')\"><i class=\"fa fa-lock\"></i></a>&nbsp;";
										}	
										echo "</td>";
											
									echo "</tr>";
										}
										
										?>
                                            </tbody>
                                        </table>
          
	<!-- START THIS PAGE PLUGINS-->        
        <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/DataTables-br.js"></script>  
        
        <!-- END THIS PAGE PLUGINS-->        

 
<?php
}else{
	echo Redirecionar('index.php');
}	

}else{
	echo Redirecionar('login.php');
}	
?>