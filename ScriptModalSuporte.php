<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
		
echo "<div class=\"modal animated fadeIn\" id=\"EditarModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Abrir Suporte</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"EnviarSuporte form-horizontal\" action=\"javascript:MDouglasMS();\" enctype=\"multipart/form-data\" method=\"post\">
						
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Assunto</label>
                            <div class=\"col-md-9\">
                                <input id=\"assunto\" name=\"assunto\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
                       
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Mensagem</label>
                            <div class=\"col-md-9\">
							    <textarea class=\"summernote\" id=\"mensagem\" name=\"mensagem\"></textarea>
                            </div>
                        </div>
						
						<div class=\"form-group\">
						<label class=\"col-md-3 control-label\">Anexar</label>
                        <div class=\"col-md-9\"><input type=\"file\" class=\"fileinput btn-default\" name=\"anexo\" id=\"anexo\" title=\"Anexar\"/></div>
						</div>
												
						</form>
                    </div>
                    <div class=\"modal-footer\">
						<div id=\"StatusModal\"></div>
						<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>
                        <button type=\"button\" class=\"ESuporte btn btn-success\">Enviar</button>
                    </div>
                </div>
            </div>
        </div>";
?>

<script type='text/javascript' src='js/plugins/validationengine/languages/jquery.validationEngine-br.js'></script>
<script type='text/javascript' src='js/plugins/validationengine/jquery.validationEngine.js'></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>

<script type="text/javascript" src="js/plugins/summernote/summernote-br.js"></script>
<script type='text/javascript' src='js/plugins.js'></script>

<script>
$("#EditarModal").modal("show");

$(function(){  
 $("button.ESuporte").click(function() { 
 														
						var formData = new FormData($(".EnviarSuporte")[0]); 
						formData.append( 'mensagem', $('.EnviarSuporte textarea[name="mensagem"]').code() );
						
						panel_refresh($(".EnviarSuporte"));
						
  						$.ajax({
    						url: 'UploadAnexoSuporte.php',
     						type: 'POST',
     						data: formData,
     						async: false,
     						cache: false,
     						contentType: false,
     						enctype: 'multipart/form-data',
    						processData: false,
     						success: function (response) {
								setTimeout(function(){
                       				setTimeout(panel_refresh($(".EnviarSuporte")),500);
									$("#StatusGeral").append(response);
              					},1000);
     						}
   						});
						
				});
			});
</script>
   
<?php  
}else{
	echo Redirecionar('login.php');
}
?>