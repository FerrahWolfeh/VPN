<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;
?>

<div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                        <a href="index.php"><span>Eon Team Brasil</span></a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>
                     

                     <li class="xn-title">Bem Vindo</li>
					    <li>
                        <a href="index.php?p=inicio"><span class="fa fa-home"></span> <span class="xn-text">Inicio</span></a>
                    </li>
                     
                     <li class="xn-openable">
                     <a href="#"><span class="fa fa-envelope"></span> <span class="xn-text">Suporte</span></a>
                    <ul>   

					<li>
                        <a href="index.php?p=suporte&a=1"><span class="fa fa-inbox"></span> Caixa de Entrada</a>
                    </li>
       
                    <li>
                        <a href="index.php?p=suporte&a=2"><span class="fa fa-rocket"></span> Enviados</a>
                    </li>
                    
                    <li>
                        <a href="index.php?p=suporte&a=3"><span class="fa fa-star"></span> Estrela</a>
                    </li>
                    
                    <li>
                        <a href="index.php?p=suporte&a=4"><span class="fa fa-trash-o"></span> Lixeira</a>
                    </li>

        			</ul>
                    </li>
                    
                    <?php
					 if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
					 ?>
                     <li>
                        <a href="index.php?p=revendedor"><span class="fa fa-user"></span> <span class="xn-text">Revendedor</span></a>
                    </li>
                    
                     <li>
                        <a href="index.php?p=usuario"><span class="fa fa-users"></span> <span class="xn-text">Usuário</span></a>
                    </li>
                    
                    <li>
                        <a href="index.php?p=criar-teste"><span class="fa fa-users"></span> <span class="xn-text">Teste</span></a>
                    </li>
                    
                    <li>
                        <a href="index.php?p=relatorio"><span class="fa fa-file-text-o"></span> <span class="xn-text">Relatório</span></a>
                    </li>
                    
                    <li>
                        <a onclick="MensagemInterna()" class="pointer"><span class="fa fa-file-text-o"></span> <span class="xn-text">Enviar Mensagem Interna</span></a>
                    </li>

					<li>
					    <a href="index.php?p=online"><span class="fa fa-users"></span> <span class="xn-text">Online</span></a>
                    
                    <?php
					}
					 
					 if($_SESSION['acesso'] == 1){
					?>
					 <li>
                        <a class="pointer" onclick="AlterarOperadora();"><span class="fa fa-circle-o"></span> <span class="xn-text">Alterar Operadora</span></a>
                    </li>
                    <li class="xn-openable">
					
                     <a href="#"><span class="fa fa-users"></span> <span class="xn-text">Configurações</span></a>
                     <ul> 
                        
                  	 <li>
                        <a href="index.php?p=servidor"><span class="fa fa-globe"></span>Servidor</a>
                   	 </li>
                     <li>
                        <a href="index.php?p=imagem-perfil"><span class="fa fa-picture-o"></span>Imagem de Perfil</a>
                   	 </li>
                   	
                  	  <li>
                        <a href="index.php?p=icone-perfil"><span class="fa fa-plus-square-o"></span>Ícone de Perfil</a>
                   	 </li>
                     
                      <li>
                        <a href="index.php?p=arquivo-perfil"><span class="fa fa-file-o"></span>Arquivo de Perfil</a>
                   	 </li>
                     <li>
                        <a href="index.php?p=sms"><span class="fa fa-mobile"></span>Conta SMS</a>
                   	 </li>
        			</ul>
                    </li> 
						 
					<?php	 
					}
					?>
                   
        
                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            
            
            <script type='text/javascript'>  
			
			function AlterarOperadora(){
				 				
				panel_refresh($(".page-container"));	
						
				$.post('ScriptModalUserEditarOperadora.php', function(resposta) {
					
					setTimeout(panel_refresh($(".page-container")),500);
					
					$("#StatusGeral").html('');
					$("#StatusGeral").html(resposta);
				});
				
			}
			
			</script>
            
<?php
}else{
	echo Redirecionar('login.php');
}
?>