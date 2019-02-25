<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
?>

<script type='text/javascript'>
$(function(){  
 			$("a.ExibirOpcoes").click(function() { 
			
			var status = $(this).attr("status"); 
			
			if(status == 'alterarrev'){
				statusE = 'Alterar Revendedor';
			}
			else if(status == 'excluirall'){
				statusE = 'Excluir';
				var fa = 'fa fa-trash-o';  
				var tipo = 'danger';
			}
			else{
				statusE = 'Opções';
				var fa = 'fa fa-gear';  
				var tipo = 'success';
			}
			
			$("#StatusEG").html(statusE);
						
				camposMarcados = new Array();
				$("input[type=checkbox][name='SelectUser[]']:checked").each(function(){
    				camposMarcados.push($(this).val());
				});
			
				if(status == 'alterarrev'){
					$.post('ScriptModalAlterarRevRev.php', {camposMarcados: camposMarcados}, function(resposta) {
						$("#StatusGeral").html('');
						$("#StatusGeral").html(resposta);
					});
				}
				else{
 					var titulo = statusE + '?';
					var texto = 'Tem certeza que deseja '+statusE+'?';
					var url = 'EnviarOpcoesRev';
					$.post('ScriptAlertaJS2.php', {camposMarcados: camposMarcados, status: status, titulo: titulo, texto: texto, tipo: tipo, url: url, fa: fa}, function(resposta) {
						$("#StatusGeral").html('');
						$("#StatusGeral").html(resposta);
					});
				}

								
			});
		});
</script>

<button type="button" class="btn btn-default"><span id="StatusEG">Opções</span></button>
<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
<ul class="dropdown-menu" role="menu">

<?php
echo "<li><a status=\"alterarrev\" class=\"ExibirOpcoes pointer\">Alterar Revendedor</a></li>";
echo "<li><a status=\"excluirall\" class=\"ExibirOpcoes pointer\">Excluir</a></li>";
?>
</ul>
&nbsp;&nbsp; 

<?php

}else{
	echo Redirecionar('index.php');
}

}else{
	echo Redirecionar('login.php');
}	
?>