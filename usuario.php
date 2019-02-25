<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
 
//Usuário
$CadUser = $_SESSION['id'];
$acesso = 3;
$SQLUser = "SELECT * FROM login WHERE id_cad = :id_cad AND acesso = :acesso";
$SQLUser = $banco->prepare($SQLUser);
$SQLUser->bindParam(':id_cad', $CadUser, PDO::PARAM_INT);
$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
$SQLUser->execute();
?>

	<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li class="active">Usuário</li>
                </ul>
                <!-- END BREADCRUMB -->  
                
                <!-- PAGE TITLE -->
          <div class="page-title">                    
          <h2><span class="fa fa-user"></span> Usuário</h2>
          </div>
                <!-- END PAGE TITLE -->   
 
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                
                    <div class="row">
                        <div class="col-md-12">
                        
                        <div class="panel panel-default">
                                <div class="panel-heading">
    
    <?php
	global $CotaUserOnline;
	if($CotaUserOnline > 0){
	?>      
    <div class="btn-group" style="padding:5px 0px 5px 0px; float:left;">
    <button type="button" class="Adicionar btn btn-info active">Adicionar</button>
    &nbsp;&nbsp; 
    </div> 
    <?php
	}
	?> 

                 
    <div class="ExibirRevs col-md-2">         
    <div class="form-group" style="padding:5px 0px 5px 0px;">
    	<select class="form-control select" id="Exibir" name="Exibir">
        	<?php echo SelecionarExibir($_SESSION['id'], $_SESSION['login']); ?>
        </select>
    </div>
    </div>
    
    <div class="btn-group" style="padding:5px 0px 5px 0px;">
    <button type="button" class="btn btn-default"><span value="Todos" id="StatusE">Status</span></button>
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
     <ul class="dropdown-menu" role="menu">
     <li><a status="Todos" class="ExibirStatus pointer">Todos</a></li>
     <li><a status="Ativos" class="ExibirStatus pointer">Ativos</a></li>
     <li><a status="Inativos" class="ExibirStatus pointer">Esgotados</a></li>
     </ul>
     &nbsp;&nbsp;
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
                                        <th width="5"><input type="checkbox" name="TotalAll" id="TotalAll" class="MarcarAll" OnClick="marcardesmarcar();"></th>
                                        <th>Nome</th>
                                        <th>Usuário</th>
                                        <th>Senha</th>
                                        <th>Operadora</th>
                                        <th>Expira em</th>
                                        <th>Criador Por</th>
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
											
										$SqlU = "SELECT login FROM login WHERE id = :id";
										$SqlU = $banco->prepare($SqlU);
										$SqlU->bindParam(':id', $LnUser['id_cad'], PDO::PARAM_INT);
										$SqlU->execute();
										$LnU = $SqlU->fetch();
										
										$DataExpirar = date('d/m/Y', $LnUser['expiredate']);
										$DataAtual = time();
										$DataFinal = $LnUser['expiredate'];
										
										$FaltaDias = $DataFinal - $DataAtual;
										$dias_restantes = floor($FaltaDias / 60 / 60 / 24);
										
										if($dias_restantes < 0){
										$status = "&nbsp;&nbsp;<span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Esgotado\">E</span>";
										}else{
										$status = "&nbsp;&nbsp;<span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Ativado\">A</span>";
										}
																																																		
										echo "
                                        <tr>
											<td><input type=\"checkbox\" class=\"MarcarTodos\" name=\"SelectUser[]\" id=\"SelectUser\" value=\"".$LnUser['id']."\" Onclick=\"VerificarCheck()\"></td>
                                        	<td>".$LnUser['nome'].$status."</td>
                                        	<td>".$LnUser['login']."</td>
                                        	<td>".$LnUser['senha']."</td>";
											
									echo "<td>".$icone_perfil."</td>";
									
									if($dias_restantes < 0){
									echo "<td><span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Esgotado\">Esgotado</span> ".$DataExpirar."</td>";	
									}else{
									$dias_restantes = 1 + $dias_restantes;
									$DiasSS = $dias_restantes > 1 ? "dias" : "dia";
									echo "<td><span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"".$dias_restantes." ".$DiasSS."\">".$dias_restantes." ".$DiasSS."</span> ".$DataExpirar."</td>";	
									}
											
									echo "<td>".$LnU['login']."</td>";
											
                                    echo "<td><div class=\"form-group\">";
									
									echo "<a class=\"label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Editar\" Onclick=\"EditarData('".$LnUser['id']."')\"><i class=\"fa fa-calendar-o\"></i></a>&nbsp;";
									
									echo "<a class=\"label label-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Editar\" Onclick=\"EditarUser('".$LnUser['id']."')\"><i class=\"fa fa-pencil\"></i></a>&nbsp;";
									
									if($LnUser['bloqueado'] == "S"){
										echo "<span id=\"StatusBloDes".$LnUser['id']."\"><a class=\"desbloquear label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Desbloquear\" Onclick=\"DesbloquearUser('".$LnUser['id']."', 'StatusBloDes".$LnUser['id']."')\"><i class=\"fa fa-unlock-alt\"></i></a></span>&nbsp;";
									}else{
									echo "<span id=\"StatusBloDes".$LnUser['id']."\"><a class=\"bloquear label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Bloquear\" Onclick=\"BloquearUser('".$LnUser['id']."', 'StatusBloDes".$LnUser['id']."')\"><i class=\"fa fa-lock\"></i></a></span>&nbsp;";
									}		
									
										echo "<span id=\"StatusDeletar".$LnUser['id']."\"><a class=\"deletar label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Excluir\" onclick=\"Deletar('".$LnUser['id']."', 'StatusDeletar".$LnUser['id']."');\"><i class=\"fa fa-trash-o\"></i></a></span>&nbsp;";
									
									echo "<a class=\"renovar label label-info\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Renovar\" onclick=\"Renovar('".$LnUser['id']."', '".$LnUser['login']."');\"><i class=\"fa fa-repeat\"></i></a>&nbsp;";
									
									if($_SESSION['acesso'] == 1){
										echo "<a class=\"revendedor label label-warning\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Tornar Revendedor?\" onclick=\"Revendedor('".$LnUser['id']."', '".$LnUser['login']."');\"><i class=\"fa fa-user\"></i></a>&nbsp;";
									}
									
									echo "</div>
											
											</td>";
											
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

		function EditarData(id){ 
		
				panel_refresh($(".page-container"));
			
				$.post('ScriptModalEditarData.php', {id: id}, function(resposta) {
					
					setTimeout(panel_refresh($(".page-container")),500);
					$("#StatusGeral").html('');
					$("#StatusGeral").html(resposta);
					
				});
				
		}
		
		function EditarUser(id){ 
		
				panel_refresh($(".page-container"));
			
				$.post('ScriptModalUserEditar.php', {id: id}, function(resposta) {
					
					setTimeout(panel_refresh($(".page-container")),500);
					$("#StatusGeral").html('');
					$("#StatusGeral").html(resposta);
					
				});
				
		}
		
		function BloquearUser(id, status){ 
		
				panel_refresh($(".page-container"));
			
				$.post('EnviarBloquearUser.php', {id: id}, function(resposta) {
					setTimeout(panel_refresh($(".page-container")),500);
					$("#"+status+"").html('');
					$("#"+status+"").html(resposta);
				});
				
		}
		
		function DesbloquearUser(id, status){ 
		
				panel_refresh($(".page-container"));
			
				$.post('EnviarDesbloquearUser.php', {id: id}, function(resposta) {
					setTimeout(panel_refresh($(".page-container")),500);
					$("#"+status+"").html('');
					$("#"+status+"").html(resposta);
				});
				
		}
		
		function Renovar(id, usuario){
 				
 				var titulo = 'Renovar?';
				var texto = 'Tem certeza que deseja renovar o usuário '+usuario+'?<br>O Cliente será renovado por mais 30 dias e será descontado 1 cota do seu saldo.';
				var tipo = 'info';
				var url = 'EnviarRenovarUser';
				var fa = 'fa fa-repeat';  
			
				$.post('ScriptAlertaJS.php', {id: id, titulo: titulo, texto: texto, tipo: tipo, url: url, fa: fa}, function(resposta) {
					$("#StatusGeral").html('');
					$("#StatusGeral").html(resposta);
				});
			}
		
		<?php
		if($_SESSION['acesso'] == 1){
		?>
		function Revendedor(id, usuario){
 				
 				var titulo = 'Tornar Revendedor?';
				var texto = 'Tem certeza que deseja tornar o usuário '+usuario+' revendedor?';
				var tipo = 'warning';
				var url = 'EnviarRevendedorUser';
				var fa = 'fa fa-user';  
			
				$.post('ScriptAlertaJS.php', {id: id, titulo: titulo, texto: texto, tipo: tipo, url: url, fa: fa}, function(resposta) {
					$("#StatusGeral").html('');
					$("#StatusGeral").html(resposta);
				});
				
		}
		<?php
		}
		?>
		

		function Deletar(id, status){
			
				panel_refresh($(".page-container"));
			
				$.post('EnviarDeletarUser.php', {id: id}, function(resposta) {
					setTimeout(panel_refresh($(".page-container")),500);
					$("#"+status+"").html('');
					$("#"+status+"").html(resposta);
				});
				
		}
		
		<?php
		if($CotaUserOnline > 0){
		?>
		$(function(){  
 			$("button.Adicionar").click(function() { 
			
				panel_refresh($(".page-container"));
 						
				$.post('ScriptModalUserAdicionar.php', function(resposta) {
					
				setTimeout(panel_refresh($(".page-container")),500);
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
				
				});
				
			});
		});
		<?php
		}
		?>

		$(function(){  
 			$(".ExibirRevs select[name=Exibir]").change(function(){
 				
				var id = $(this).val();
				var status = $('#StatusE').attr('value');
				
				var panel = $(this).parents(".panel");
       		    panel_refresh(panel);

				$.post('usuario-status.php', {id: id, status: status}, function(resposta) {
				
				setTimeout(function(){
            	panel_refresh(panel);
        		},500);	
					
				$(".table-responsive").html('');
				$(".table-responsive").html(resposta);
				});
				
				
			});
		});
		
		$(function(){  
 			$("a.ExibirStatus").click(function() { 
 				$(".ExibirAllOpcoes").html('');
				
				var status = $(this).attr("status"); 
				
				if(status == 'Todos'){
					statusE = 'Todos';
				}
				else if(status == 'Ativos'){
					statusE = 'Ativos';
				}
				else if(status == 'Inativos'){
					statusE = 'Esgotados';
				}
				else{
					statusE = 'Todos';
				}
				
				$("#StatusE").html(statusE);
				$("#StatusE").attr('value', status);
				
				var usuario = $('#StatusUE').attr('value');
				
				var panel = $(this).parents(".panel");
       		    panel_refresh(panel);

				$.post('usuario-status.php', {usuario: usuario, status: status}, function(resposta) {
				
				setTimeout(function(){
            	panel_refresh(panel);
        		},500);	
					
				$(".table-responsive").html('');
				$(".table-responsive").html(resposta);
				});
								
			});
		});
		
		
		function marcardesmarcar(){
		
		TotalAll = $('[name="TotalAll"]:checked').length;		
		TotalSUser = $('[name="SelectUser[]"]:checked').length;
		TotalSGeral = $('[name="SelectUser[]"]').length;
				
 		$('.MarcarTodos').each(
        function(){
				if ( (TotalAll > 0) && (TotalSUser == 0) ){
					$(this).prop("checked", true);
				}
           		else if ( (TotalAll == 0) && (TotalSUser == TotalSGeral) ){
           			$(this).prop("checked", false);  
				}
				else if ( (TotalAll > 0) && (TotalSUser > 0) ){
					$(this).prop("checked", true);
				}
				else if ( (TotalAll == 0) && (TotalSGeral != TotalSUser) ){
					$(this).prop("checked", false); 
				}
           		else {
				$(this).prop("checked", false);   
				}
         		}
   		);
				VerificarCheck();		 
		}
		
		function VerificarCheck(){
		
		TotalSUser = $('[name="SelectUser[]"]:checked').length;
		TotalSUserGeral = $('[name="SelectUser[]"]').length;
		
		
		if(TotalSUser == TotalSUserGeral){
			$(".MarcarAll").prop("checked", true); 
		}
		else{
			$(".MarcarAll").prop("checked", false); 
		}
		
		if( TotalSUser > 0){
			$.post('SelecionarOpcoes.php', function(resposta) {
				$(".ExibirAllOpcoes").html(resposta);
			});
		}
		else{
			$(".ExibirAllOpcoes").html(''); 
			$(".MarcarAll").prop("checked", false);
		}
		}
		
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