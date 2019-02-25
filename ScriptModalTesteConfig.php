<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
	if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
	$UserOnline = $_SESSION['login'];
	$SQLUrlT = "SELECT status, tempo, cemail, email FROM urlteste WHERE CadUser = :CadUser";
	$SQLUrlT = $banco->prepare($SQLUrlT);
	$SQLUrlT->bindParam(':CadUser', $UserOnline, PDO::PARAM_STR);
	$SQLUrlT->execute();
	$LnUrlT = $SQLUrlT->fetch();
	
	$status = empty($LnUrlT['status']) ? "N" : $LnUrlT['status'];
	$tempo = empty($LnUrlT['tempo']) ? "" : $LnUrlT['tempo'];
	$cemail = empty($LnUrlT['cemail']) ? "N" : $LnUrlT['cemail'];
	$email = empty($LnUrlT['email']) ? "" : $LnUrlT['email'];
	
echo "<div class=\"modal animated fadeIn\" id=\"EditarAdmin\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Url de Teste</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"ConfigTeste form-horizontal\" action=\"javascript:MDouglasMS();\">
										
						 <div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Status</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"EditarStatus\" name=\"EditarStatus\">";
									
									if($status == "S"){
										echo "<option value=\"S\">Disponível</option>
										<option value=\"N\">Indisponível</option>";
									}
									else{
										echo "<option value=\"N\">Indisponível</option>
										<option value=\"S\">Disponível</option>";										
									}
									
									echo "</select>
                                 </div>
                        </div>
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Tempo</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"EditarTempo\" name=\"EditarTempo\">";
								
									echo "<option value=\"1\">1 dia</option><option value=\"2\">2 dias</option><option value=\"3\">3 dias</option><option value=\"4\">4 dias</option>";
									
                                    echo "</select>
                                 </div>
                        </div>
						
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Cópia de E-mail</label>
                           <div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"EditarCopia\" name=\"EditarCopia\">";
									
									if($cemail == "S"){
										echo "<option value=\"S\">Sim</option>
										<option value=\"N\">Não</option>";
									}
									else{
										echo "<option value=\"N\">Não</option>
										<option value=\"S\">Sim</option>";
									}
									
                                    echo "</select>
                            </div>
                        </div>
						
                        <div class=\"form-group\" id=\"StatusConfigTeste\">";
						if($cemail == "S"){
							echo "
							<div class=\"form-group\">
    							<label class=\"col-md-3 control-label\">E-mail</label>
        						<div class=\"col-md-9\">
           							<input id=\"EditarEmail\" name=\"EditarEmail\" value=\"".$email."\" type=\"text\" class=\"validate[custom[email]] form-control\">
           						</div>
   							</div>
							";							
						}
						echo "</div>
						
						</form>
                    </div>
                    <div class=\"modal-footer\">
						<div id=\"StatusModal\"></div>
                        <button type=\"button\" class=\"SalvarConfigTeste btn btn-danger\">Configurar</button>
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>
                    </div>
                </div>
            </div>
        </div>";
?>
<script type='text/javascript' src='js/plugins/validationengine/languages/jquery.validationEngine-br.js'></script>
<script type='text/javascript' src='js/plugins/validationengine/jquery.validationEngine.js'></script>
<script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>

<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>

<!-- START TEMPLATE -->  
<script type="text/javascript" src="js/plugins.js"></script>     
<!-- END TEMPLATE -->      


<script>
$("#EditarAdmin").modal("show");

$(function(){  
 $("button.SalvarConfigTeste").click(function() { 
 
 		var Data = $(".ConfigTeste").serialize();
		
		$('#StatusModal').html("<center><img src=\"img/owl/AjaxLoader.gif\"><br><br></center>");
		
		$.post('EnviarConfigTeste.php', Data, function(resposta) {
				$("#StatusModal").html('');
				$("#StatusGeral").append(resposta);
		});
	});
});

$(function(){
	$(".ConfigTeste select[name=EditarCopia]").change(function(){
		
		var id = $(this).val();
		
		$.post('ExibirSelectConfigTeste.php', {id: id}, function(resposta) {
				$("#StatusConfigTeste").html('');
				$("#StatusConfigTeste").html(resposta);
		});
	});
});
</script>

<?php  
	}
}else{
	echo Redirecionar('login.php');
}	
?>