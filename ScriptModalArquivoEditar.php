<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if($_SESSION['acesso'] == 1){
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
$id = (isset($_POST['id'])) ? trim($_POST['id']) : '';

$SQL = "SELECT * FROM arquivo WHERE id = :id";
$SQL = $banco->prepare($SQL);
$SQL->bindParam(':id', $id, PDO::PARAM_STR);
$SQL->execute();
$Ln = $SQL->fetch();

$nome = $Ln['nome'];
$titulo = $Ln['titulo'];
$descricao = $Ln['descricao'];
$botao = $Ln['botao'];
$apn = $Ln['apn'];
$imagem = $Ln['imagem'];
$url = $Ln['url'];
$file = $Ln['file'];
$tipo = $Ln['tipo'];
$operadora = $Ln['operadora'];

$ArrayTipo = array('default','primary','success','info','warning','danger');
	
$SQLImagem = "SELECT id, imagem FROM imagem_perfil WHERE id != :id";
$SQLImagem = $banco->prepare($SQLImagem);
$SQLImagem->bindParam(':id', $imagem, PDO::PARAM_STR);
$SQLImagem->execute();

$SQLImagem2 = "SELECT id, imagem FROM imagem_perfil WHERE id = :id";
$SQLImagem2 = $banco->prepare($SQLImagem2);
$SQLImagem2->bindParam(':id', $imagem, PDO::PARAM_STR);
$SQLImagem2->execute();
$LnImagem2 = $SQLImagem2->fetch();

$SQLOp = "SELECT nome FROM servidor WHERE nome != :nome";
$SQLOp = $banco->prepare($SQLOp);
$SQLOp->bindParam(':nome', $operadora, PDO::PARAM_STR);
$SQLOp->execute();

$SQLOp2 = "SELECT nome FROM servidor WHERE nome = :nome";
$SQLOp2 = $banco->prepare($SQLOp2);
$SQLOp2->bindParam(':nome', $operadora, PDO::PARAM_STR);
$SQLOp2->execute();
$TotalOp2 = count($SQLOp2->fetchAll());

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
										
										echo "<option data-content='<img src=\"img/perfil/".$LnImagem2['imagem']."\" height=\"54\" width=\"163\">' value=\"".$LnImagem2['id']."\"></option>";
										while($LnImagem = $SQLImagem->fetch()){
										echo "<option data-content='<img src=\"img/perfil/".$LnImagem['imagem']."\" height=\"54\" width=\"163\">' value=\"".$LnImagem['id']."\"></option>";
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
										
										if($TotalOp2 > 0){
											$SQLOp2->execute();
											$LnOp2 = $SQLOp2->fetch();
											echo "<option value=\"".$LnOp2['nome']."\">".$LnOp2['nome']."</option>";
										}
										
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
                                <input value=\"".$nome."\" id=\"Nome\" name=\"Nome\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
                      
                        <div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Título</label>
                            <div class=\"col-md-9\">
                                <input value=\"".$titulo."\" id=\"Titulo\" name=\"Titulo\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Descrição</label>
                            <div class=\"col-md-9\">
                                <input value=\"".$descricao."\" id=\"Descricao\" name=\"Descricao\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Nome do Botão</label>
                            <div class=\"col-md-9\">
                                <input value=\"".$botao."\" id=\"Botao\" name=\"Botao\" type=\"text\" class=\"validate[required] form-control\">
                            </div>
                        </div>
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">APN</label>
                            <div class=\"col-md-9\">
                                <input value=\"".$apn."\" id=\"apn\" name=\"apn\" type=\"text\" class=\"form-control\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                        	<label class=\"col-md-3 control-label\">Tipo</label>
                            	<div class=\"col-md-9\">                                        
                                	<select class=\"form-control select\" id=\"Tipo\" name=\"Tipo\">";
									
									echo "<option data-content='<button type=\"button\" class=\"btn btn-".$tipo."\">".ucfirst($tipo)."</button>' value=\"".$tipo."\"></option>";
									for($i=0; $i<count($ArrayTipo); $i++){
										if($tipo != $ArrayTipo[$i]){
										echo "<option data-content='<button type=\"button\" class=\"btn btn-".$ArrayTipo[$i]."\">".ucfirst($ArrayTipo[$i])."</button>' value=\"".$ArrayTipo[$i]."\"></option>";
										}
									}
									
                                    echo "</select>
                                 </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Url</label>
                            <div class=\"col-md-9\">
                               <input value=\"".$url."\" id=\"Url\" name=\"Url\" type=\"text\" class=\"form-control\">
                            </div>
                        </div>
						
						<div class=\"form-group\">
                            <label class=\"col-md-3 control-label\">Arquivo</label>
                            <div class=\"col-md-9\">
                                <input type=\"file\" class=\"fileinput btn-default\" name=\"anexo\" id=\"anexo\" title=\"Selecione o Arquivo\"/>
                            </div>
                        </div>
						
						<input name=\"id\" id=\"id\" type=\"hidden\" value=\"".$id."\" />
						
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
    						url: 'UploadArquivoEditar.php',
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