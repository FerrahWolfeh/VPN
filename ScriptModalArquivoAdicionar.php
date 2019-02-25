<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if($_SESSION['acesso'] == 1){
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
$SQLImagem = "SELECT id, imagem FROM imagem_perfil";
$SQLImagem = $banco->prepare($SQLImagem);
$SQLImagem->execute();
$TotalImagem = count($SQLImagem->fetchAll());

$SQLOp = "SELECT nome FROM servidor";
$SQLOp = $banco->prepare($SQLOp);
$SQLOp->execute();

$ArrayTipo = array('default','primary','success','info','warning','danger');

echo "<div class=\"modal animated fadeIn\" id=\"EditarAdmin\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Adicionar</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"AdicionarAdministrador form-horizontal\" enctype=\"multipart/form-data\" action=\"javascript:MDouglasMS();\">
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Imagem de Perfil</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"ImagemPerfil\" name=\"ImagemPerfil\">";

									if($TotalImagem > 0){
										$SQLImagem->execute();
										while($LnImagem = $SQLImagem->fetch()){
										echo "<option data-content='<img src=\"img/perfil/".$LnImagem['imagem']."\" height=\"54\" width=\"163\">' value=\"".$LnImagem['id']."\"></option>";
										}
									}
									else{
										echo "<option value=\"0\">Não existe ícone de perfil adicionado</option>";
									}
										
										echo "
                                     </select>
									 <br><br><br><br>
                                 </div>
                        </div>
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Operadora</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"Operadora\" name=\"Operadora\">";
					
										echo "<option value=\"Todos\">Todos</option>";
										
										while($LnOp = $SQLOp->fetch()){
											echo "<option value=\"".$LnOp['nome']."\">".$LnOp['nome']."</option>";
										}
									
										echo "
                                     </select>
                                 </div>
                        </div>
						
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Nome</label>
                            <div class=\"col-md-9\">
                                <input id=\"Nome\" name=\"Nome\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
                      
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Título</label>
                            <div class=\"col-md-9\">
                                <input id=\"Titulo\" name=\"Titulo\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Descrição</label>
                            <div class=\"col-md-9\">
                                <input id=\"Descricao\" name=\"Descricao\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Nome do Botão</label>
                            <div class=\"col-md-9\">
                                <input id=\"Botao\" name=\"Botao\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">APN</label>
                            <div class=\"col-md-9\">
                                <input id=\"apn\" name=\"apn\" type=\"text\" class=\"form-control\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Tipo</label>
                            	<div class=\"col-md-9\">
									<select class=\"form-control select\" id=\"Tipo\" name=\"Tipo\">
								";                                 
                                	
								for($i=0; $i<count($ArrayTipo); $i++){
										echo "<option data-content='<button type=\"button\" class=\"btn btn-".$ArrayTipo[$i]."\">".ucfirst($ArrayTipo[$i])."</button>' value=\"".$ArrayTipo[$i]."\"></option>";
								}
									
                                   echo " </select>
                                 </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Url</label>
                            <div class=\"col-md-9\">
                               <input id=\"Url\" name=\"Url\" type=\"text\" class=\"form-control\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Arquivo</label>
                            <div class=\"col-md-9\">
                                <input type=\"file\" class=\"fileinput btn-default\" name=\"anexo\" id=\"anexo\" title=\"Selecione o Arquivo\"/>
                            </div>
                        </div>
						
						</form>
                    </div>
                    <div class=\"modal-footer\">
						<div id=\"StatusModal\"></div>
                        <button type=\"button\" class=\"SalvarAdicionar btn btn-danger\">Adicionar</button>
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>
                    </div>
                </div>
            </div>
        </div>";
?>
        
<script type='text/javascript' src='js/plugins/validationengine/languages/jquery.validationEngine-br.js'></script>
<script type='text/javascript' src='js/plugins/validationengine/jquery.validationEngine.js'></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>

<!-- START TEMPLATE -->
<script type="text/javascript" src="js/plugins.js"></script>        
<!-- END TEMPLATE -->

<script>
$("#EditarAdmin").modal("show");

$(function(){  
 $("button.SalvarAdicionar").click(function() { 
 								
						panel_refresh($(".AdicionarAdministrador"));
						
						var formData = new FormData($(".AdicionarAdministrador")[0]); 
						
  						$.ajax({
    						url: 'UploadArquivo.php',
     						type: 'POST',
     						data: formData,
     						async: false,
     						cache: false,
     						contentType: false,
     						enctype: 'multipart/form-data',
    						processData: false,
     						success: function (response) {
								setTimeout(panel_refresh($(".AdicionarAdministrador")),500);
								$("#StatusGeral").html('');
								$("#StatusGeral").html(response);
     						}
   						});
						
				});
			});
</script>
   
<?php  
}
}else{
	echo Redirecionar('index.php');
}	
}else{
	echo Redirecionar('login.php');
}	
?>