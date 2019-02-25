<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

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
	 
//Usuário
$CadUser = $_SESSION['id'];
$acesso = 3;
$acessoT = 4;

$SQLUser = "SELECT * FROM login WHERE id_cad = :id_cad AND acesso = :acesso OR id_cad = :id_cad AND acesso = :acessoT";
$SQLUser = $banco->prepare($SQLUser);
$SQLUser->bindParam(':id_cad', $CadUser, PDO::PARAM_INT);
$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
$SQLUser->bindParam(':acessoT', $acessoT, PDO::PARAM_INT);
$SQLUser->execute();
?>

	<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li class="active">Online</li>
                </ul>
                <!-- END BREADCRUMB -->  
                
                <!-- PAGE TITLE -->
          <div class="page-title">                    
          <h2><span class="fa fa-circle"></span> Online</h2>
          </div>
                <!-- END PAGE TITLE -->   
 
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                
                    <div class="row">
                        <div class="col-md-12">
                        
                        <div class="panel panel-default">
                                <div class="panel-heading">
                 
    <div class="ExibirRevs col-md-2">         
    <div class="form-group" style="padding:5px 0px 5px 0px;">
    	<select class="form-control select" id="Exibir" name="Exibir">
        	<?php echo SelecionarExibir($_SESSION['id'], $_SESSION['login']); ?>
        </select>
    </div>
    </div>
    
    <div class="ExibirAllOpcoes btn-group" style="padding:5px 0px 5px 0px;"></div>

                                    <ul class="panel-controls">
                                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-cog"></span></a>                                            
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span> Esconder</a></li>
                                                <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span> Atualizar</a></li>
                                            </ul>                                        
                                        </li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                                                
                                    <div class="table-responsive">
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
                                    </div>
                                </div>                             
                            </div>



                        </div>
                    </div>                                
                    
                </div>
                <!-- PAGE CONTENT WRAPPER -->      
        
    

		<div id="StatusGeral"></div>        
<!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>  
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>  
        <script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>  
        
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
        
                <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/DataTables-br.js"></script>  
        
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- END TEMPLATE -->
        
        <script type='text/javascript'>  
		
		function BloquearUser(id){ 
 		
 				var titulo = 'Bloquear?';
				var texto = 'Tem certeza que deseja bloquear o usuário?';
				var tipo = 'danger';
				var url = 'EnviarBloquearUser';
				var fa = 'fa fa-lock';  
			
				$.post('ScriptAlertaJS.php', {id: id, titulo: titulo, texto: texto, tipo: tipo, url: url, fa: fa}, function(resposta) {
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
				});
				
		}
		
		function DesbloquearUser(id){ 
 		
 				var titulo = 'Desbloquear?';
				var texto = 'Tem certeza que deseja desbloquear o usuário?';
				var tipo = 'danger';
				var url = 'EnviarDesbloquearUser';
				var fa = 'fa fa-unlock-alt';  
			
				$.post('ScriptAlertaJS.php', {id: id, titulo: titulo, texto: texto, tipo: tipo, url: url, fa: fa}, function(resposta) {
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
				});
				
		}
		
		$(function(){  
 			$(".ExibirRevs select[name=Exibir]").change(function(){
 				
				var id = $(this).val();
				
				var panel = $(this).parents(".panel");
       		    panel_refresh(panel);

				$.post('online-status.php', {id: id}, function(resposta) {
				
				setTimeout(function(){
            	panel_refresh(panel);
        		},500);	
					
				$(".table-responsive").html('');
				$(".table-responsive").html(resposta);
				});
				
				
			});
		});
		
		</script>
          

    <!-- END SCRIPTS -->    
<?php
}else{
	echo Redirecionar('index.php');
}	

}else{
	echo Redirecionar('login.php');
}	
?>