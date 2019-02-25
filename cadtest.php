<?php
include("conexao.php");
include_once("functions.php");

$GET = empty($_GET['r']) ? "" : $_GET['r'];
$CadUser = UrlTeste(2, $GET);

//Verificar se o revendedor existe
$SQLUrlT = "SELECT login FROM login WHERE login = :login";
$SQLUrlT = $banco->prepare($SQLUrlT);
$SQLUrlT->bindParam(':login', $CadUser, PDO::PARAM_STR);
$SQLUrlT->execute();
$TotalUrldeTeste = count($SQLUrlT->fetchAll());

$VerTeste = VerTeste($CadUser);
$TempoDias = $VerTeste[1] > 1 ? "dias" : "dia";

if( ($VerTeste[0] == 1) && ($TotalUrldeTeste > 0) ){
?>

<!DOCTYPE html>
<html lang="pt" class="body-full-height">
    <head>        
        <!-- META SECTION -->
        <title>Eon Team Brasil | Cadastro</title>            
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
        
        <div class="registration-container">            
            <div class="registration-box animated fadeInDown">
                <div class="registration-logo">Eon Team Brasil</div>
                <div class="registration-body">
                    <div class="registration-title"><strong>Criar Teste</strong>, <?php echo $VerTeste[1]." ".$TempoDias; ?></div>
                                        
                    <form action="javascript:MDouglasMS();" class="TesteCadastrar form-horizontal" method="post" id="FormLogin">
                    
                    <h4>Dados da Conta</h4>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input name="EditarNome" id="EditarNome" type="text" class="form-control" placeholder="Nome"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-12">
                            <input name="EditarUsuario" id="EditarUsuario" type="text" class="form-control" placeholder="Usuário"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-12">
                            <input name="EditarSenha" id="EditarSenha" type="text" class="form-control" placeholder="Senha"/>
                        </div>
                    </div>
                    
                    <h4>Operadora</h4>
                    
                    <div class="form-group">
                    	<div class="col-md-12">                                        
                        	<select class="form-control select" id="EditarOperadora" name="EditarOperadora">
							<?php echo PerfilAdminEditarTeste(); ?>
                            </select>
                         </div>
                    </div>
                                         
                    <div class="form-group push-up-30">
                        <div class="col-md-12">
                        	<div id="StatusCadastro"></div>
                            <input name="r" id="r" type="hidden" value="<?php echo $GET; ?>"/>
                            <button class="CadastrarTeste btn btn-danger btn-block">Criar Teste</button>
                        </div>
                    </div>
                    
                    <input type="hidden" id="CadUser" name="CadUser" value="<?php echo $CadUser; ?>" />
                    
                    </form>
                </div>
                <div class="registration-footer">
                    <div class="pull-left">
                        &copy; 2019 Hub de VPNs Eon Team Brasil
                    </div>
                </div>
            </div>
            
        </div>
        
        <div id="StatusGeral"></div>  
        
 		 <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <!-- END PLUGINS -->
  
        <script type='text/javascript' src='js/plugins/bootstrap/bootstrap-select.js'></script>        

        
        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- END TEMPLATE -->
      
    </body>
</html>

<script type="text/javascript">
$(function(){  
 $("button.CadastrarTeste").click(function() {
	 
	 	panel_refresh($(".registration-container")); 
  
 		var Data = $(".TesteCadastrar").serialize();
				
		$.post('EnviarAdicionarCriarTesteAuto.php', Data, function(resposta) {
			
				setTimeout(function(){
            		panel_refresh($(".registration-container")); 
        		},500);	
			
				$("#StatusGeral").append(resposta);	
		});
	});
});
</script>

<?php
}else{
	echo "http://eonteambrasil.com.br/index.html";
}
?>




