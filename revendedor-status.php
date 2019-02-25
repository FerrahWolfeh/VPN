<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
	
if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

$UserOnline = $_SESSION['id'];
$IdUser = (isset($_POST['id'])) ? $_POST['id'] : '';
$status = (isset($_POST['status'])) ? $_POST['status'] : '';

if($status == "Ativos"){
	$PesStatus = " AND cota > '1'";
}
elseif($status == "Inativos"){
	$PesStatus = " AND cota < '1'";
}
else{
	$PesStatus = "";
}

if($IdUser == "Todos"){
	$CadUser = ArvoreRev($UserOnline);
	$CadUser[] = $UserOnline;
	$CadUser = implode(',', $CadUser);
	
	$acesso = 2;
	$SQLUser = "SELECT * FROM login WHERE FIND_IN_SET(id_cad,'".$CadUser."') AND acesso = :acesso AND id != :id".$PesStatus."";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
	$SQLUser->bindParam(':id', $UserOnline, PDO::PARAM_INT);
	$SQLUser->execute();
}else{
	$acesso = 2;
	
	$SQLUser = "SELECT * FROM login WHERE id_cad = :id_cad AND acesso = :acesso AND id != :id".$PesStatus."";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':id_cad', $IdUser, PDO::PARAM_STR);
	$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
	$SQLUser->bindParam(':id', $UserOnline, PDO::PARAM_INT);
	$SQLUser->execute();
}
?>

  <table id="Tabela" class="table datatable">
                               <thead>
                               		<tr>
                                    	<th width="5"><input type="checkbox" name="TotalAll" id="TotalAll" class="MarcarAll" OnClick="marcardesmarcar();"></th>
                                        <th>Nome</th>
                                        <th>Usuário</th>
                                        <th>Senha</th>
                                        <th>Cota</th>
                                        <th>Limite Teste</th>
                                        <th>Vencimento</th>
                                        <th>Valor Cobrado</th>
                                        <th>Criador Por</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
     
                                
                                        <?php
										while($LnUser = $SQLUser->fetch()){
											
										$SqlU = "SELECT login FROM login WHERE id = :id";
										$SqlU = $banco->prepare($SqlU);
										$SqlU->bindParam(':id', $LnUser['id_cad'], PDO::PARAM_INT);
										$SqlU->execute();
										$LnU = $SqlU->fetch();
											
										if($LnUser['cota'] < 1){
										$status = "&nbsp;&nbsp;<span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Esgotado\">E</span>";
										}else{
										$status = "&nbsp;&nbsp;<span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Ativado\">A</span>";
										}
										
										$valor = empty($LnUser['ValorCobrado']) ? "R$ 0,00" : "R$ ".number_format($LnUser['ValorCobrado'], 2, ',', '');		
										
										$DataExpirar = date('d/m/Y', $LnUser['expiredate']);
										$DataAtual = time();
										$DataFinal = $LnUser['expiredate'];
										
										$FaltaDias = $DataFinal - $DataAtual;
										$dias_restantes = floor($FaltaDias / 60 / 60 / 24);
										
										$ColorStatus = $dias_restantes < 0 ? "style=\"color:#F00;\"" : "";
																																															
										echo "
                                        <tr>
											<td><input type=\"checkbox\" class=\"MarcarTodos\" name=\"SelectUser[]\" id=\"SelectUser\" value=\"".$LnUser['id']."\" Onclick=\"VerificarCheck()\"></td>
                                        	<td ".$ColorStatus.">".$LnUser['nome'].$status."</td>
                                        	<td ".$ColorStatus.">".$LnUser['login']."</td>
                                        	<td ".$ColorStatus.">".$LnUser['senha']."</td>
											<td ".$ColorStatus.">".$LnUser['cota']."</td>
											<td ".$ColorStatus.">".$LnUser['LimiteTeste']."</td>";
											
											if($dias_restantes < 0){
												echo "<td ".$ColorStatus."><span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Esgotado\">Esgotado</span> ".$DataExpirar."</td>";	
											}else{
												$DiasSS = $dias_restantes > 1 ? "dias" : "dia";
												echo "<td ".$ColorStatus."><span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"".$dias_restantes." ".$DiasSS."\">".$dias_restantes." ".$DiasSS."</span> ".$DataExpirar."</td>";	
											}

											echo "<td ".$ColorStatus.">".$valor."</td>";
											
									echo "<td ".$ColorStatus.">".$LnU['login']."</td>";
											
                                    echo "<td><div class=\"form-group\">";
																		
									echo "<a onclick=\"Deletar('".$LnUser['id']."', '".$LnUser['login']."')\" class=\"label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Excluir\"><i class=\"fa fa-trash-o\"></i></a>&nbsp;";
									
									echo "<a onclick=\"Editar('".$LnUser['id']."')\" class=\"label label-warning\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Editar\"><i class=\"fa fa-pencil\"></i></a>&nbsp;";
											
									echo "</div>
											
											</td>";
											
									echo "</tr>";
										}
										?>
                                            </tbody>
                                        </table>
          
	<!-- START THIS PAGE PLUGINS-->        
                <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/DataTables-br.js"></script>  
        
        <!-- END THIS PAGE PLUGINS-->        
	
    <script type='text/javascript'>  
		        
		function marcardesmarcar(){
		
		TotalAll = $('[name="TotalAll"]:checked').length;		
		TotalSUser = $('[name="SelectUser[]"]:checked').length;
		TotalSGeral = $('[name="SelectUser[]"]').length;
				
 		$('.MarcarTodos').each(
        function(){
				if ( (TotalAll > 0) && (TotalSUser == 0) ){
					$(this).prop("checked", true);
				}
           		else if ( (TotalAll == 0) && (TotalSUser == TotalSGeral) ){
           			$(this).prop("checked", false);  
				}
				else if ( (TotalAll > 0) && (TotalSUser > 0) ){
					$(this).prop("checked", true);
				}
				else if ( (TotalAll == 0) && (TotalSGeral != TotalSUser) ){
					$(this).prop("checked", false); 
				}
           		else {
				$(this).prop("checked", false);   
				}
         		}
   		);
				VerificarCheck();		 
		}
		
		function VerificarCheck(){
		
		TotalSUser = $('[name="SelectUser[]"]:checked').length;
		TotalSUserGeral = $('[name="SelectUser[]"]').length;
		
		
		if(TotalSUser == TotalSUserGeral){
			$(".MarcarAll").prop("checked", true); 
		}
		else{
			$(".MarcarAll").prop("checked", false); 
		}
		
		if( TotalSUser > 0){
			$.post('SelecionarOpcoesRev.php', function(resposta) {
				$(".ExibirAllOpcoes").html(resposta);
			});
		}
		else{
			$(".ExibirAllOpcoes").html(''); 
			$(".MarcarAll").prop("checked", false);
		}
		}
		
		</script> 
 
<?php
}else{
	echo Redirecionar('index.php');
}	

}else{
	echo Redirecionar('login.php');
}	
?>