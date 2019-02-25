<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if($_SESSION['acesso'] == 1){
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
$id = (isset($_POST['id'])) ? trim($_POST['id']) : '';

$SQL = "SELECT * FROM sms WHERE id = :id";
$SQL = $banco->prepare($SQL);
$SQL->bindParam(':id', $id, PDO::PARAM_STR);
$SQL->execute();
$Ln = $SQL->fetch();

$login = $Ln['login'];
$senha = $Ln['senha'];

echo "<div class=\"modal animated fadeIn\" id=\"EditarAdmin\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Editar</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"AdicionarAdministrador form-horizontal\" action=\"javascript:MDouglasMS();\">
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Usuário</label>
                            <div class=\"col-md-9\">
                                <input value=\"".$login."\" id=\"Login\" name=\"Login\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Senha</label>
                            <div class=\"col-md-9\">
                                <input value=\"".$senha."\" id=\"Senha\" name=\"Senha\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						
						<input name=\"id\" id=\"id\" type=\"hidden\" value=\"".$id."\" />

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
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
<script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>

<!-- START TEMPLATE -->
<script type="text/javascript" src="js/plugins.js"></script>        
<!-- END TEMPLATE -->

<script>
$("#EditarAdmin").modal("show");

$(function(){  
 $("button.SalvarAdicionar").click(function() { 
 
 		var Data = $(".AdicionarAdministrador").serialize();
		
		panel_refresh($(".AdicionarAdministrador"));
		
		$.post('EnviarEditarSMS.php', Data, function(resposta) {
			
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