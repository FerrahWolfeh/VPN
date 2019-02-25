<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
$IDUser = $_SESSION['id'];
			
echo "<div class=\"modal animated fadeIn\" id=\"EditarModalCircular\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Enviar Mensagem Interna</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"FormEnviarCircular form-horizontal\" action=\"javascript:MDouglasMS();\">
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Entre Datas</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"EntreDatas\" name=\"EntreDatas\">
                                    	<option value=\"N\">Não</option>
										<option value=\"S\">Sim</option>
                                     </select>
                                 </div>
                        </div>
						
						<span id=\"StatusEntreDatas\"></span>
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Enviar Para</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"Grupo\" name=\"Grupo\">
                                    	<option value=\"Todos\">Todos</option>
										<option value=\"2\">Revendedor</option>
										<option value=\"3\">Usuário</option>
										<option value=\"4\">Teste</option>
                                     </select>
                                 </div>
                        </div>
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Todos?</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"Todos\" name=\"Todos\">
                                    	<option value=\"N\">Não</option>
										<option value=\"S\">Sim</option>
                                     </select>
                                 </div>
                        </div>
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Status</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"Status\" name=\"Status\">
                                    	<option value=\"Todos\">Todos</option>
										<option value=\"Ativos\">Ativos</option>
										<option value=\"Esgotados\">Esgotados</option>
										<option value=\"Bloqueados\">Bloqueados</option>
                                     </select>
                                 </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Mensagem</label>
                            <div class=\"col-md-9\">
							    <textarea class=\"form-control\" rows=\"10\" id=\"Mensagem\" name=\"Mensagem\"></textarea>
                            </div>
                        </div>
												
						</form>
                    </div>
                    <div class=\"modal-footer\">
						<div id=\"StatusModal\"></div>
						<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>
                        <button type=\"button\" class=\"BotaoEnviarCircular btn btn-success\">Enviar</button>
                    </div>
                </div>
            </div>
        </div>";
?>

<script type='text/javascript' src='js/plugins/validationengine/languages/jquery.validationEngine-br.js'></script>
<script type='text/javascript' src='js/plugins/validationengine/jquery.validationEngine.js'></script>
<script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>

<script type='text/javascript' src='js/plugins.js'></script>

<script>
$("#EditarModalCircular").modal("show");

$(function(){  
 $("button.BotaoEnviarCircular").click(function() { 
 		
		var Grupo = $('.FormEnviarCircular select[name="Grupo"]').val();
		var Todos = $('.FormEnviarCircular select[name="Todos"]').val();
		var Status = $('.FormEnviarCircular select[name="Status"]').val();
		var Mensagem = $('.FormEnviarCircular textarea[name="Mensagem"]').val();
		var EntreDatas = $('.FormEnviarCircular select[name="EntreDatas"]').val();
		var DataInicio = $('.FormEnviarCircular input[name="DataInicio"]').val();
		var DataFinal = $('.FormEnviarCircular input[name="DataFinal"]').val();
		
		panel_refresh($(".FormEnviarCircular"));
		
		$.post('EnviarMensagemCircular.php', {Grupo: Grupo, Todos: Todos, Status: Status, Mensagem: Mensagem, EntreDatas: EntreDatas, DataInicio: DataInicio, DataFinal: DataFinal}, function(resposta) {
			
				setTimeout(panel_refresh($(".FormEnviarCircular")),500);
			
				$("#StatusModal").html('');
				$("#StatusMensagemCircular").append(resposta);
		});
	});
});

$(function(){
	$(".FormEnviarCircular select[name=EntreDatas]").change(function(){
		
		var id = $(this).val();
		panel_refresh($(".FormEnviarCircular"));
		
		$.post('ExibirModeloEntreData.php', {id: id}, function(resposta) {
			
				setTimeout(panel_refresh($(".FormEnviarCircular")),500);
				$("#StatusEntreDatas").html(resposta.trim());
				
		});
	});
});
</script>
   
<?php  
}else{
	echo Redirecionar('index.php');
}
}else{
	echo Redirecionar('login.php');
}
?>