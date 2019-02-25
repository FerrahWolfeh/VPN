<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
$IdUser = (isset($_POST['id'])) ? $_POST['id'] : '';
$SQLUser = "SELECT * FROM login WHERE id = :id";
$SQLUser = $banco->prepare($SQLUser);
$SQLUser->bindParam(':id', $IdUser, PDO::PARAM_INT);
$SQLUser->execute();
$LnUser = $SQLUser->fetch();

echo "<div class=\"modal animated fadeIn\" id=\"EditarAdmin\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Editar (".$LnUser['login'].")</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"EditarUsuario form-horizontal\" action=\"javascript:MDouglasMS();\">
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Nome</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarNome\" name=\"EditarNome\" type=\"text\" class=\"validate[required] form-control\" value=\"".$LnUser['nome']."\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">E-mail</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarEmail\" name=\"EditarEmail\" type=\"text\" class=\"validate[required] form-control\" value=\"".$LnUser['email']."\">
                            </div>
                        </div>
                      
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Login</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarUsuario\" name=\"EditarUsuario\" type=\"text\" class=\"validate[required] form-control\" value=\"".$LnUser['login']."\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Senha</label>
                            <div class=\"col-md-9\">
                                <input id=\"EditarSenha\" name=\"EditarSenha\" type=\"text\" class=\"validate[required] form-control\" value=\"".$LnUser['senha']."\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Operadora</label>
                            <div class=\"col-md-9\">
							".PerfilAdminEditar2($LnUser['login'])."
                            </div>
                        </div>
						
						<input type=\"hidden\" name=\"id\" id=\"id\" value=\"".$IdUser."\" />

						</form>
                    </div>
                    <div class=\"modal-footer\">
						<div id=\"StatusModal\"></div>
                        <button type=\"button\" class=\"SalvarAdicionar btn btn-danger\">Alterar</button>
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>
                    </div>
                </div>
            </div>
        </div>";
?>
        
<script type='text/javascript' src='js/plugins/validationengine/languages/jquery.validationEngine-br.js'></script>
<script type='text/javascript' src='js/plugins/validationengine/jquery.validationEngine.js'></script>
<script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>

<!-- START TEMPLATE -->
<script type="text/javascript" src="js/plugins.js"></script>        
<!-- END TEMPLATE -->

<script>
$("#EditarAdmin").modal("show");

$(function(){  
 $("button.SalvarAdicionar").click(function() { 
 
 		var Data = $(".EditarUsuario").serialize();
		
		$('#StatusModal').html("<center><img src=\"img/owl/AjaxLoader.gif\"><br><br></center>");
		
		$.post('EnviarEditarUser.php', Data, function(resposta) {
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