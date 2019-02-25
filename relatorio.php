<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
?>

	<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li class="active">Relatório</li>
                </ul>
                <!-- END BREADCRUMB -->  
                
                <!-- PAGE TITLE -->
          <div class="page-title">                    
          <h2><span class="fa fa-file-text-o"></span> Relatório</h2>
          </div>
                <!-- END PAGE TITLE -->   
 
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                
                    <div class="row">
                        <div class="col-md-12">
                        
                        <div class="panel panel-default">
                                <div class="panel-heading ClassExibirRelatorio">
   
   <h3 class="panel-title">
    	<select class="form-control select" id="id_cad" name="id_cad">
        	<?php echo SelecionarExibirRelatorio($_SESSION['id'], $_SESSION['login']); ?>
        </select>
   </h3>
    
     <h3 class="panel-title">
     	<select class="form-control select" id="rev" name="rev">
        	<option value="N">Exibir dos Revendedores?</option>
            <option value="S">SIM</option>
            <option value="N">NÃO</option>
        </select>
     </h3>
    
   <h3 class="panel-title">
     	<select class="form-control select" id="mes" name="mes">
        	<option value="T">Todos</option>
            <?php echo VerificarRelatorioMes(); ?>
        </select>
     </h3>
     
     <h3 class="panel-title">
     	<select class="form-control select" id="ano" name="ano">
        	<option value="T">Todos</option>
        	<?php echo VerificarRelatorioAno(); ?>
        </select>
     </h3>
    
    <h3 class="panel-title">
    	<button type="button" class="ExibirRelatorio btn btn-success">Exibir</button>
    </h3>
    
    
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
                            </div>
                            
                            <div id="StatusRelatorio"></div> 



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
		$(function(){  
 			$("button.ExibirRelatorio").click(function() {
				
				var id_cad = $('.ClassExibirRelatorio select[name="id_cad"]').val();
				var rev = $('.ClassExibirRelatorio select[name="rev"]').val();
				var mes = $('.ClassExibirRelatorio select[name="mes"]').val();
				var ano = $('.ClassExibirRelatorio select[name="ano"]').val();				
					
				var panel = $('.panel-default');
       		    panel_refresh(panel);
		
				$.post('EnviarRelatorio.php', {id_cad: id_cad, rev: rev, mes: mes, ano: ano}, function(resposta) {
					
					setTimeout(function(){
            			panel_refresh(panel);
        			},500);	

					$("#StatusRelatorio").html(resposta);
					
				});
			});
		});
		
		
		<?php
		if($_SESSION['acesso'] == 1){
		?>
		function Deletar(id, status){
			
				panel_refresh($(".page-container"));
 
				$.post('EnviarDeletarUserRelatorio.php', {id: id}, function(resposta) {
					
					setTimeout(panel_refresh($(".page-container")),500);
					
					$("#"+status+"").html('');
					$("#"+status+"").html(resposta);
				});
				
		}
		<?php
		}
		?>
		
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