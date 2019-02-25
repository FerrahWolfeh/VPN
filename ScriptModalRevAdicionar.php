<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
$expiredate = time();

echo "<div class=\"modal animated fadeIn\" id=\"EditarAdmin\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Adicionar</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"AdicionarAdministrador form-horizontal\" action=\"javascript:MDouglasMS();\">
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Nome</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarNome\" name=\"EditarNome\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
                      
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Login</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarUsuario\" name=\"EditarUsuario\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Senha</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarSenha\" name=\"EditarSenha\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Vencimento</label>
                            <div class=\"col-md-9\">
								<div class=\"input-group date\">
                                	<input type=\"text\" id=\"DataPremium\" name=\"DataPremium\" class=\"form-control\" value=\"".ConverterDataTime($expiredate)."\"/>
                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                            	</div>
                            </div>
						</div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Cota</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarCota\" name=\"EditarCota\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Limite Teste</label>
                            <div class=\"col-md-9\">
                                <input id=\"LimiteTeste\" name=\"LimiteTeste\" type=\"text\" class=\"validate[required] form-control\" value=\"0\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Valor Cobrado</label>
                            <div class=\"col-md-9\">
                                <input id=\"ValorCobrado\" name=\"ValorCobrado\" type=\"text\" class=\"form-control\">
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
<script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>

<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/locales/bootstrap-datepicker-br.js"></script>

<!-- START TEMPLATE -->
<script type="text/javascript" src="js/plugins.js"></script>   
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney-br.js"></script>     
<!-- END TEMPLATE -->

<script>
$("#EditarAdmin").modal("show");

$(function(){  
 $("button.SalvarAdicionar").click(function() { 
 
 		var Data = $(".AdicionarAdministrador").serialize();
		
		panel_refresh($(".AdicionarAdministrador"));
		
		$.post('EnviarAdicionarRev.php', Data, function(resposta) {
				setTimeout(panel_refresh($(".AdicionarAdministrador")),500);
				$("#StatusModal").html('');
				$("#StatusGeral").append(resposta);
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