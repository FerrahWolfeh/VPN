<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

$InfoConfigSuporte = InfoConfigSuporte();

$pasta = empty($_GET['a']) ? 1 : $_GET['a'];
$m = empty($_GET['m']) ? "" : $_GET['m'];
$CadUser = $_SESSION['login'];
$excluirfinal = "N";

//Caixa de Entrada
$PastaEntrada = 1;
$LidaReceptor = "N";
$SQLEntrada = "SELECT id FROM suporte WHERE UserReceptor = :UserReceptor AND PastaReceptor = :PastaReceptor AND LidaReceptor = :LidaReceptor";
$SQLEntrada = $banco->prepare($SQLEntrada);
$SQLEntrada->bindParam(':UserReceptor', $CadUser, PDO::PARAM_STR);
$SQLEntrada->bindParam(':LidaReceptor', $LidaReceptor, PDO::PARAM_STR);
$SQLEntrada->bindParam(':PastaReceptor', $PastaEntrada, PDO::PARAM_STR);
$SQLEntrada->execute();
$TotalSuporte = count($SQLEntrada->fetchAll());
$naolida = $TotalSuporte > 1 ? 'Não lidas' : 'Não lida';
$unread = $TotalSuporte > 0 ? "(".$TotalSuporte." ".$naolida.")" : "";
$CEntrada = $TotalSuporte > 0 ? "<span class=\"badge badge-success\">".$TotalSuporte."</span>" : "";

$CEactive = ($pasta != 2) && ($pasta != 3) && ($pasta != 4) ? "active" : "";
$CSactive = $pasta == 2 ? "active" : "";
$CEsactive = $pasta == 3 ? "active" : "";
$CLixactive = $pasta == 4 ? "active" : "";

//Estrela
$PastaEstrela = 4;
$Estrela = "S";
$SQLEstrela = "SELECT id FROM suporte WHERE UserReceptor = :UserReceptor AND Estrela = :Estrela AND PastaReceptor != :PastaReceptor";
$SQLEstrela = $banco->prepare($SQLEstrela);
$SQLEstrela->bindParam(':UserReceptor', $CadUser, PDO::PARAM_STR);
$SQLEstrela->bindParam(':Estrela', $Estrela, PDO::PARAM_STR);
$SQLEstrela->bindParam(':PastaReceptor', $PastaEstrela, PDO::PARAM_STR);
$SQLEstrela->execute();
$TotalEstrela = count($SQLEstrela->fetchAll());
$CEstrela = $TotalEstrela > 0 ? "<span class=\"badge badge-warning\" id=\"StatusEstrela\">".$TotalEstrela."</span>" : "";

//Lixeira
$PastaLixeira = 4;
$SQLLixeira = "SELECT id FROM suporte WHERE UserReceptor = :UserReceptor AND PastaReceptor = :PastaReceptor AND ExcluirReceptor = :ExcluirReceptor OR UserEmissor = :UserEmissor AND PastaEmissor = :PastaEmissor AND ExcluirEmissor = :ExcluirEmissor";
$SQLLixeira = $banco->prepare($SQLLixeira);
$SQLLixeira->bindParam(':UserReceptor', $CadUser, PDO::PARAM_STR);
$SQLLixeira->bindParam(':UserEmissor', $CadUser, PDO::PARAM_STR);
$SQLLixeira->bindParam(':PastaReceptor', $PastaLixeira, PDO::PARAM_STR);
$SQLLixeira->bindParam(':PastaEmissor', $PastaLixeira, PDO::PARAM_STR);
$SQLLixeira->bindParam(':ExcluirEmissor', $excluirfinal, PDO::PARAM_STR);
$SQLLixeira->bindParam(':ExcluirReceptor', $excluirfinal, PDO::PARAM_STR);
$SQLLixeira->execute();
$TotalLixeira = count($SQLLixeira->fetchAll());
$CLixeira = $TotalLixeira > 0 ? "<span class=\"badge badge-default\">".$TotalLixeira."</span>" : "";

if($pasta == 1){
	$BREADCRUMB = 'Caixa de Entrada';
	$CONTENTFRAME = "<span class=\"fa fa-inbox\"></span> Caixa de Entrada <small>".$unread."</small>";
}
elseif($pasta == 2){
	$BREADCRUMB = 'Enviados';
	$CONTENTFRAME = "<span class=\"fa fa-rocket\"></span> Enviados";
}
elseif($pasta == 3){
	$BREADCRUMB = 'Estrela';
	$CONTENTFRAME = "<span class=\"fa fa-star\"></span> Estrela";
}
elseif($pasta == 4){
	$BREADCRUMB = 'Lixeira';
	$CONTENTFRAME = "<span class=\"fa fa-trash-o\"></span> Lixeira";
}
else{
	$BREADCRUMB = 'Caixa de Entrada';
	$CONTENTFRAME = "<span class=\"fa fa-inbox\"></span> Caixa de Entrada <small>".$unread."</small>";
}
?>

				<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                	<li>Suporte</li>
                    <li class="active"><?php echo $BREADCRUMB; ?></li>
                </ul>
                <!-- END BREADCRUMB -->  
 
                                                
                <!-- START CONTENT FRAME -->
                <div class="content-frame">                                    
                    <!-- START CONTENT FRAME TOP -->
                    <div class="content-frame-top">                        
                        <div class="page-title">                    
                            <h2><?php echo $CONTENTFRAME; ?></h2>
                        </div>      
                        <div class="pull-right">                            
                            <button class="Configuracoes btn btn-default"><span class="fa fa-cogs"></span> Configurações</button>
                            <button class="btn btn-default content-frame-left-toggle"><span class="fa fa-bars"></span></button>
                        </div>                                                                          
                    </div>
                    <!-- END CONTENT FRAME TOP -->
                    
                    <!-- START CONTENT FRAME LEFT -->
                    <div class="content-frame-left">
                        <div class="block">
                            <button class="AbrirSuporte btn btn-danger btn-block btn-lg"><span class="fa fa-edit"></span> Abrir Suporte</button>
                        </div>
                        <div class="block">
                            <div class="list-group border-bottom">
                                <a href="index.php?p=suporte&a=1" class="list-group-item <?php echo $CEactive; ?>"><span class="fa fa-inbox"></span> Caixa de Entrada <?php echo $CEntrada; ?></a>
                                <a href="index.php?p=suporte&a=2" class="list-group-item <?php echo $CSactive; ?>"><span class="fa fa-rocket"></span> Enviados</a>
                                <a href="index.php?p=suporte&a=3" class="list-group-item <?php echo $CEsactive; ?>"><span class="fa fa-star"></span> Estrela <?php echo $CEstrela; ?></a>
                                <a href="index.php?p=suporte&a=4" class="list-group-item <?php echo $CLixactive; ?>"><span class="fa fa-trash-o"></span> Lixeira <?php echo $CLixeira; ?></a>                            
                            </div>                        
                        </div>
                        <div class="block">
                            <h4>Marcadores</h4>
                            <div class="list-group list-group-simple">                                
                                <a href="#" class="list-group-item"><span class="fa fa-circle text-success"></span> Renovação</a>
                                <a href="#" class="list-group-item"><span class="fa fa-circle text-warning"></span> Comprovante</a>
                                <a href="#" class="list-group-item"><span class="fa fa-circle text-danger"></span> Vencimento</a>
                                <a href="#" class="list-group-item"><span class="fa fa-circle text-info"></span> Dados de Acesso</a>
                                <a href="#" class="list-group-item"><span class="fa fa-circle text-primary"></span> Outros</a>
                            </div>
                        </div>
                    </div>
                    <!-- END CONTENT FRAME LEFT -->
                    
                    <!-- START CONTENT FRAME BODY -->
                    <div id="StatusCaixaEntrada">
                    <?php
					
					if(empty($m)){
					
					if($pasta == 1){
						include('suporte-1.php');
					}
					elseif($pasta == 2){
						include('suporte-2.php');
					}
					elseif($pasta == 3){
						include('suporte-3.php');
					}
					elseif($pasta == 4){
						include('suporte-4.php');
					}
					else{
						include('suporte-1.php');
					}
					
					}
					else{
						include('suporte-entrada.php');
					}
					?>
                    </div>
                    <!-- END CONTENT FRAME BODY -->
                </div>
                <!-- END CONTENT FRAME -->
        
        <div id="StatusGeral"></div>
        
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/locales/bootstrap-datepicker-br.js"></script>    
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/plugins/summernote/summernote-br.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
        
		<script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>   
        <!-- END TEMPLATE -->     
        
        <script type='text/javascript'> 
		$(function(){  
 			$("button.Configuracoes").click(function() { 
			
				panel_refresh($(".page-container"));
 						
				$.post('ScriptModalConfigSuporte.php', function(resposta) {
					setTimeout(panel_refresh($(".page-container")),500);
					$("#StatusGeral").html('');
					$("#StatusGeral").html(resposta);
				});
				
			});
		});
		
		$(function(){  
 			$("button.AbrirSuporte").click(function() { 
			
				panel_refresh($(".page-container"));
 						
				$.post('ScriptModalSuporte.php', function(resposta) {
					setTimeout(panel_refresh($(".page-container")),500);
					$("#StatusGeral").html('');
					$("#StatusGeral").html(resposta);
				});
				
			});
		});
		
		$(function(){  
 			$("div.AtualizarEstrela").click(function() { 
 				
				var id = $(this).attr("id"); 	
				
				$.post('AtualizarEstrela.php', {id: id}, function(resposta) {
					$("#StatusEstrela").html(resposta.trim());
				});
				
			});
		});
		
		$(function(){  
 			$(".mail-checkbox .iCheck-helper").on("click",function(){
				var TotalBox = $('[name="SelectBox[]"]:checked').length;	
				
				if(TotalBox > 0){
					$("#StatusTrash").html('<button data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir" class="btn btn-default"><span class="fa fa-trash-o"></span></button>');
				}
				else{
					$("#StatusTrash").html('');
				}
				
			});
		});
		
		$(function(){  
 			$(".mail-checkall .iCheck-helper").on("click",function(){
				var TotalBox = $('[name="SelectBox[]"]:checked').length;	
				
				if(TotalBox > 0){
					$("#StatusTrash").html('<button data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir" class="btn btn-default"><span class="fa fa-trash-o"></span></button>');
				}
				else{
					$("#StatusTrash").html('');
				}
				
			});
		});
		
		$(function(){  
 			$(".panel-heading #StatusTrash").on("click",function(){
				var Data = $(".ConfigSelect").serialize();
				
				pageLoadingFrame("show");
				
				$.post('AtualizarLixeiraSuporte.php', Data, function(resposta) {
					
					setTimeout(function(){
                        pageLoadingFrame("hide");
						$("#StatusGeral").html(resposta);
              		},1000);
					
				});
			});
		});
		
		$(function(){  
 			$(".mail-checkboxLixeira .iCheck-helper").on("click",function(){
				var TotalBox = $('[name="SelectBox[]"]:checked').length;	
				
				if(TotalBox > 0){
					$("#StatusTrashLixeira").html('<button data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir" class="btn btn-default"><span class="fa fa-trash-o"></span></button>');
				}
				else{
					$("#StatusTrashLixeira").html('');
				}
				
			});
		});
		
		$(function(){  
 			$(".mail-checkallLixeira .iCheck-helper").on("click",function(){
				var TotalBox = $('[name="SelectBox[]"]:checked').length;	
				
				if(TotalBox > 0){
					$("#StatusTrashLixeira").html('<button data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir" class="btn btn-default"><span class="fa fa-trash-o"></span></button>');
				}
				else{
					$("#StatusTrashLixeira").html('');
				}
				
			});
		});
		
		$(function(){  
 			$(".panel-heading #StatusTrashLixeira").on("click",function(){
				var Data = $(".ConfigSelect").serialize();
				
				pageLoadingFrame("show");
				
				$.post('AtualizarLixeiraSuporteLixeira.php', Data, function(resposta) {
					
					setTimeout(function(){
                        pageLoadingFrame("hide");
						$("#StatusGeral").html(resposta);
              		},1000);
					
				});
			});
		});
		
				
		$(function(){ 
 			$(".AbrirCaixaEntrada .Abrir").on("click",function(){
								
				var id = $(this).attr("id");
				var pasta = '<?php echo $pasta; ?>';
				
				pageLoadingFrame("show");
				
				$.post('suporte-entrada.php', {id: id, pasta: pasta}, function(resposta) {
					
					setTimeout(function(){
                        pageLoadingFrame("hide");
						$("#StatusCaixaEntrada").html(resposta);
              		},1000);
				
				});
							
			});
		});
		

		//EnviarDeletarSuporte.php
		</script>

<?php
}else{
	echo Redirecionar('login.php');
}	
?>