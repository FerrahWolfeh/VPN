<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if($_SESSION['acesso'] == 1){
 
//Servidor
$SQLServer = "SELECT * FROM sms";
$SQLServer = $banco->prepare($SQLServer);
$SQLServer->execute();
?>

	<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li class="active">Configurações</li>
                </ul>
                <!-- END BREADCRUMB -->  
                
                <!-- PAGE TITLE -->
          <div class="page-title">                    
          <h2><span class="fa fa-mobile"></span> Conta SMS</h2>
          </div>
                <!-- END PAGE TITLE -->   
 
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                
                    <div class="row">
                        <div class="col-md-12">
                        
                        <div class="panel panel-default">
                                <div class="panel-heading">
     
    <div class="btn-group" style="padding:5px 0px 5px 0px;">
    <button type="button" class="Adicionar btn btn-info active">Adicionar</button>
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
                                    	<th>Usuário</th>
                                        <th>Senha</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
     
                                
                                        <?php
										while($LnServer = $SQLServer->fetch()){
																																																		
										echo "
                                        <tr>
											<td>".$LnServer['login']."</td>
                                        	<td>".$LnServer['senha']."</td>
											";

											
											
                                    echo "<td><div class=\"form-group\">";
																		
									echo "<a onclick=\"Deletar('".$LnServer['id']."', '".$LnServer['login']."')\" class=\"deletar label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Excluir\"><i class=\"fa fa-trash-o\"></i></a>&nbsp;";
									
									echo "<a onclick=\"Editar('".$LnServer['id']."')\" class=\"label label-warning\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Editar\"><i class=\"fa fa-pencil\"></i></a>&nbsp;";
																		
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
                <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/DataTables-br.js"></script>  
        
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- END TEMPLATE -->
        
        <script type='text/javascript'>  
		
		function Editar(id){
			
				panel_refresh($(".page-container"));
			
				$.post('ScriptModalSMSEditar.php', {id: id}, function(resposta) {
					
				setTimeout(panel_refresh($(".page-container")),500);
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
				
				});
				
		}
		
		$(function(){  
 			$("button.Adicionar").click(function() { 
			
				panel_refresh($(".page-container"));
 						
				$.post('ScriptModalAdicionarContaSMS.php', function(resposta) {
					
				setTimeout(panel_refresh($(".page-container")),500);
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
				
				});
				
			});
		});
		
		function Deletar(id, nome){
 				
 				var titulo = 'Excluir?';
				var texto = 'Tem certeza que deseja excluir o servidor '+nome+'?';
				var tipo = 'danger';
				var url = 'EnviarDeletarSMS';
				var fa = 'fa fa-trash-o';  
			
				$.post('ScriptAlertaJS.php', {id: id, titulo: titulo, texto: texto, tipo: tipo, url: url, fa: fa}, function(resposta) {
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
				});
				
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