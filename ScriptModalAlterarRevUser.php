<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
$camposMarcados = (isset($_POST['camposMarcados'])) ? $_POST['camposMarcados'] : '';
$string_array = implode("|", $camposMarcados);

echo "<div class=\"modal animated fadeIn\" id=\"EditarAdmin\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallModalHead\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Fechar</span></button>
                        <h4 class=\"modal-title\" id=\"smallModalHead\">Alterar Revendedor</h4>
                    </div>
                    <div class=\"modal-body form-horizontal form-group-separated\">     
						<form id=\"validate\" role=\"form\" class=\"UserAlterarRev form-horizontal\" action=\"javascript:MDouglasMS();\">
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Revendedor</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"RevendedorAll\" name=\"RevendedorAll\">
									
		".SelecionarExibirAll($_SESSION['id'], $_SESSION['login'])."
																		
		</select>
                                 </div>
                        </div>
						
						</form>
                    </div>
                    <div class=\"modal-footer\">
						<div id=\"StatusModal\"></div>
                        <button type=\"button\" class=\"SalvarAdicionar btn btn-warning\">Alterar Revendedor</button>
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>
                    </div>
                </div>
            </div>
        </div>";
?>
        
<script type='text/javascript' src='js/plugins/validationengine/languages/jquery.validationEngine-br.js'></script>
<script type='text/javascript' src='js/plugins/validationengine/jquery.validationEngine.js'></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
<script type='text/javascript' src='js/plugins.js'></script>    

<script>
$("#EditarAdmin").modal("show");

$(function(){  
 $("button.SalvarAdicionar").click(function() { 
 		
		var rev = $('.UserAlterarRev select[name="RevendedorAll"]').val();
		
		var i, camposMarcados, string_array;
		var status = 'alterarrev';
		string_array = '<?php echo $string_array; ?>';
		camposMarcados = string_array.split('|');
		
		$('#StatusModal').html("<center><img src=\"img/owl/AjaxLoader.gif\"><br><br></center>");
		
		$.post('EnviarOpcoesUser.php', {camposMarcados: camposMarcados, rev: rev, status: status}, function(resposta) {
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