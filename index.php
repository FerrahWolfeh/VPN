<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>        
        <!-- META SECTION -->
        <title>Eon Team Brasil | Painel</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="/SharedAssets/img/raposa png.png" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="css/theme-dark.css"/>
        <!-- EOF CSS INCLUDE -->                   
    </head>
    <body>
        <!-- START PAGE CONTAINER -->
        <div class="page-container">
       <?php
			include("menu.php");
			?> 
            <div class="page-content">
                
            <!-- MENU VERTICAL -->
            <?php
			include("menu-vertical.php");
			?>              
			<!-- END MENU VERTICAL -->
                        
                
            <!-- PAGINACAO -->
            <?php
		if (isset($_GET['p'])){
        	if (file_exists($_GET['p'] . ".php") && $_GET['p'] != 'index'){
        		include $_GET['p'] . ".php"; 
        	}
			else{
                include "error.php";
       		}
		}
		else{
       		include "inicio.php";
		}
				?>
                <!-- END PAGINACAO -->                                
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->
        
        <script type='text/javascript' src='js/plugins/noty/jquery.noty.js'></script>
        <script type='text/javascript' src='js/plugins/noty/layouts/topCenter.js'></script>
        <script type='text/javascript' src='js/plugins/noty/layouts/topLeft.js'></script>
        <script type='text/javascript' src='js/plugins/noty/layouts/topRight.js'></script>            
        <script type='text/javascript' src='js/plugins/noty/themes/default.js'></script>
        
        <script type='text/javascript'> 
		
				<?php
				if(!empty($_SESSION['msginterna'])){
					
				$obs = str_replace("\n","<br>",$_SESSION['msginterna']);
				$obs = str_replace("\r","",$obs);
				?>
								
				notyConfirm();
				
				function notyConfirm(){
                    noty({
                        text: '<?php echo $obs; ?>',
                        layout: 'topRight',
                        buttons: [
                                {addClass: 'btn btn-success btn-clean', text: 'Lido', onClick: function($noty) {
                                    $noty.close();
                                    noty({text: 'Obrigado, esta mensagem não irá mais aparecer!', layout: 'topRight', type: 'success'});
									$.post('EnviarFecharAlerta.php');
                                }
                                },
                                {addClass: 'btn btn-danger btn-clean', text: 'Fechar', onClick: function($noty) {
                                    $noty.close();
                                 }
                                }
                            ]
                    })                                                    
                }      
				<?php
				}
				
				if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
				?>
		
				function MensagemInterna(){
			
					panel_refresh($(".page-container"));
 		
					$.post('ScriptModalEnviarCircular.php', function(resposta) {
					
						setTimeout(panel_refresh($(".page-container")),500);
					
						$("#StatusCircular").html('');
						$("#StatusCircular").html(resposta);
						
					});
				
				}
		
				<?php
				}
				?>
		
		</script>
           
      <div id="StatusCircular"></div>    
      <div id="StatusMensagemCircular"></div>
          
    </body>
</html>

<?php
}else{
	echo Redirecionar('login.php');
}
?>




