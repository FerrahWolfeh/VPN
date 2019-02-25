<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$UsuarioOnline = $_SESSION['id'];

if(!empty($id)){

$SQLUser = "SELECT * FROM login WHERE id = :id";
$SQLUser = $banco->prepare($SQLUser);
$SQLUser->bindParam(':id', $id, PDO::PARAM_INT);
$SQLUser->execute();
$LnUser = $SQLUser->fetch();

$expiredate = $LnUser['expiredate'];

echo "<div class=\"modal animated fadeIn\" id=\"EditarAdmin\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Editar ".$LnUser['login']."</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"EditarAdministrador form-horizontal\" action=\"javascript:MDouglasMS();\">
						
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Nome</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarNome\" name=\"EditarNome\" type=\"text\" value=\"".$LnUser['nome']."\" class=\"validate[required] form-control\">
                            </div>
                        </div>
                      
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Login</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarUsuario\" name=\"EditarUsuario\" type=\"text\" value=\"".$LnUser['login']."\"  class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Senha</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarSenha\" name=\"EditarSenha\" type=\"text\" value=\"".$LnUser['senha']."\"  class=\"validate[required] form-control\">
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
                                <input id=\"EditarCota\" name=\"EditarCota\" type=\"text\" value=\"".$LnUser['cota']."\"  class=\"validate[required] form-control\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Limite Teste</label>
                            <div class=\"col-md-9\">
                                <input id=\"LimiteTeste\" name=\"LimiteTeste\" type=\"text\" class=\"validate[required] form-control\" value=\"".$LnUser['LimiteTeste']."\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Valor Cobrado</label>
                            <div class=\"col-md-9\">
                                <input id=\"ValorCobrado\" name=\"ValorCobrado\" type=\"text\" class=\"form-control\" value=\"".str_replace(".",",",$LnUser['ValorCobrado'])."\">
                            </div>
                        </div>
                      

						<input type=\"hidden\" name=\"EditarID\" id=\"EditarID\" value=\"".$id."\">
						</form>
                    </div>
                    <div class=\"modal-footer\">
						<div id=\"StatusModal\"></div>
                        <button type=\"button\" class=\"SalvarEditar btn btn-danger\">Alterar</button>
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
 $("button.SalvarEditar").click(function() { 
 
 		var Data = $(".EditarAdministrador").serialize();
		
		$('#StatusModal').html("<center><img src=\"img/owl/AjaxLoader.gif\"><br><br></center>");
		
		$.post('EnviarEditarRev.php', Data, function(resposta) {
				$("#StatusModal").html('');
				$("#StatusGeral").append(resposta);
		});
	});
});
</script>
   
<?php  
}
}
}else{
	echo Redirecionar('index.php');
}	
}else{
	echo Redirecionar('login.php');
}	
?>