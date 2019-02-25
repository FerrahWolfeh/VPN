<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if($_SESSION['acesso'] == 1){
 
//Servidor
$SQLServer = "SELECT * FROM icone_perfil";
$SQLServer = $banco->prepare($SQLServer);
$SQLServer->execute();
?>

	<link rel="stylesheet" type="text/css" href="css/cropper/cropper.min.css"/>

	<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li class="active">Configurações</li>
                </ul>
                <!-- END BREADCRUMB -->  
                
                <!-- PAGE TITLE -->
          <div class="page-title">                    
          <h2><span class="fa fa-plus-square-o"></span> Ícone de Perfil</h2>
          </div>
                <!-- END PAGE TITLE -->   
 
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                
                    <div class="row">
                        <div class="col-md-12">
                        
                        <div class="panel panel-default">
                                <div class="panel-heading">
     
    <div class="btn-group" style="padding:5px 0px 5px 0px;">
    <button type="button" class="Adicionar btn btn-info active" data-toggle="modal" data-target="#modal_change_photo">Adicionar</button>
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
                                        <th>Imagem</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
     
                                
                                        <?php
										while($LnServer = $SQLServer->fetch()){
										
										$img = "<img src=\"img/icone/".$LnServer['imagem']."\" height=\"20\" width=\"20\"  />";
																																																		
										echo "
                                        <tr>
                                        	<td>".$img."</td>
											";

											
											
                                    echo "<td><div class=\"form-group\">";
																		
									echo "<a onclick=\"Deletar('".$LnServer['id']."')\" class=\"label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Excluir\"><i class=\"fa fa-trash-o\"></i></a>&nbsp;";
																				
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
        
    
    	<div class="modal animated fadeIn" id="modal_change_photo" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                        <h4 class="modal-title" id="smallModalHead">Adicionar Ícone de Perfil</h4>
                    </div>                    
                    <form id="cp_crop" method="post" action="javascript:MDouglasMS();">
                    <div class="modal-body">
                        <div class="text-center" id="cp_target">Formatos permitidos: jpg, png e gif.</div>
                        <input type="hidden" name="cp_img_path" id="cp_img_path"/>
                        <input type="hidden" name="ic_x" id="ic_x"/>
                        <input type="hidden" name="ic_y" id="ic_y"/>
                        <input type="hidden" name="ic_w" id="ic_w"/>
                        <input type="hidden" name="ic_h" id="ic_h"/>                        
                    </div>                    
                    </form>
                    <form id="cp_upload" method="post" enctype="multipart/form-data" action="upload_icone.php">
                    <div class="modal-body form-horizontal form-group-separated">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nova Imagem</label>
                            <div class="col-md-4">
                                <input type="file" class="fileinput btn-info" name="file" id="cp_photo" data-filename-placement="inside" title="Selecionar Imagem"/>
                            </div>                            
                        </div>                              
                    </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success disabled" id="cp_accept">Aceitar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
		
		<div id="StatusGeral"></div>        

        <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        
        <script type="text/javascript" src="js/plugins/jquery/jquery-migrate.min.js"></script>
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>  
        
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
        <script type="text/javascript" src="js/plugins/form/jquery.form.js"></script>
        
        <script type="text/javascript" src="js/plugins/cropper/cropper.min.js"></script>
		
        <script type='text/javascript' src='js/plugins/validationengine/languages/jquery.validationEngine-br.js'></script>
        <script type='text/javascript' src='js/plugins/validationengine/jquery.validationEngine.js'></script>     
        
        <script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>  
        <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/DataTables-br.js"></script>  
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>
        <?php include_once("js/demo_edit_icone.php"); ?>
        <!-- END TEMPLATE -->
        
        <script type='text/javascript'>  

		function Deletar(id){
 				
 				var titulo = 'Excluir?';
				var texto = 'Tem certeza que deseja excluir esta imagem?';
				var tipo = 'danger';
				var url = 'EnviarDeletarIconePerfil';
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