<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

$UserOnline = $_SESSION['id'];
$IdUser = (isset($_POST['id'])) ? $_POST['id'] : '';
$status = (isset($_POST['status'])) ? $_POST['status'] : '';
$DataAtual = time();

if($status == "Ativos"){
	$PesStatus = " AND expiredate > '".$DataAtual."'";
}
elseif($status == "Inativos"){
	$PesStatus = " AND expiredate < '".$DataAtual."'";
}
else{
	$PesStatus = "";
}

if($IdUser == "Todos"){
	$CadUser = ArvoreRev($UserOnline);
	$CadUser[] = $UserOnline;
	$CadUser = implode(',', $CadUser);
	
	$acesso = 3;
	$SQLUser = "SELECT * FROM login WHERE FIND_IN_SET(id_cad,'".$CadUser."') AND acesso = :acesso".$PesStatus."";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
	$SQLUser->execute();
}else{
	$acesso = 3;
	
	$SQLUser = "SELECT * FROM login WHERE id_cad = :id_cad AND acesso = :acesso".$PesStatus."";
	$SQLUser = $banco->prepare($SQLUser);
	$SQLUser->bindParam(':id_cad', $IdUser, PDO::PARAM_STR);
	$SQLUser->bindParam(':acesso', $acesso, PDO::PARAM_INT);
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
                                        <th>Operadora</th>
                                        <th>Expira em</th>
                                        <th>Criador Por</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
     
                                
                                        <?php
										while($LnUser = $SQLUser->fetch()){
											
										$SqlS = "SELECT icone FROM servidor WHERE nome = :nome";
										$SqlS = $banco->prepare($SqlS);
										$SqlS->bindParam(':nome', $LnUser['operadora'], PDO::PARAM_STR);
										$SqlS->execute();
										$LnU = $SqlS->fetch();
										
										$SqlI = "SELECT imagem FROM icone_perfil WHERE id = :id";
										$SqlI = $banco->prepare($SqlI);
										$SqlI->bindParam(':id', $LnU['icone'], PDO::PARAM_STR);
										$SqlI->execute();
										$LnI = $SqlI->fetch();
										$FotoPerfil = FotoPerfil($LnI['imagem']);
										$icone_perfil = "<img src=\"".$FotoPerfil."\" height=\"20\" width=\"20\">";
											
										$SqlU = "SELECT login FROM login WHERE id = :id";
										$SqlU = $banco->prepare($SqlU);
										$SqlU->bindParam(':id', $LnUser['id_cad'], PDO::PARAM_INT);
										$SqlU->execute();
										$LnU = $SqlU->fetch();
										
										$DataExpirar = date('d/m/Y', $LnUser['expiredate']);
										$DataAtual = time();
										$DataFinal = $LnUser['expiredate'];
									
										$FaltaDias = $DataFinal - $DataAtual;
										$dias_restantes = floor($FaltaDias / 60 / 60 / 24);
									
										if($dias_restantes < 0){
										$status = "&nbsp;&nbsp;<span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Esgotado\">E</span>";
										}else{
										$status = "&nbsp;&nbsp;<span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Ativado\">A</span>";
										}
																																																		
										echo "
                                        <tr>
											<td><input type=\"checkbox\" class=\"MarcarTodos\" name=\"SelectUser[]\" id=\"SelectUser\" value=\"".$LnUser['id']."\" Onclick=\"VerificarCheck()\"></td>
                                        	<td>".$LnUser['nome'].$status."</td>
                                        	<td>".$LnUser['login']."</td>
                                        	<td>".$LnUser['senha']."</td>";
											
									echo "<td>".$icone_perfil."</td>";
									
									if($dias_restantes < 0){
									echo "<td><span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Esgotado\">Esgotado</span> ".$DataExpirar."</td>";	
									}else{
									$dias_restantes = 1 + $dias_restantes;
									$DiasSS = $dias_restantes > 1 ? "dias" : "dia";
									echo "<td><span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"".$dias_restantes." ".$DiasSS."\">".$dias_restantes." ".$DiasSS."</span> ".$DataExpirar."</td>";	
									}
											
									echo "<td>".$LnU['login']."</td>";
											
                                    echo "<td><div class=\"form-group\">";
									
									echo "<a class=\"label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Editar\" Onclick=\"EditarData('".$LnUser['id']."')\"><i class=\"fa fa-calendar-o\"></i></a>&nbsp;";
									
									echo "<a class=\"label label-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Editar\" Onclick=\"EditarUser('".$LnUser['id']."')\"><i class=\"fa fa-pencil\"></i></a>&nbsp;";
									
									if($LnUser['bloqueado'] == "S"){
										echo "<span id=\"StatusBloDes".$LnUser['id']."\"><a class=\"desbloquear label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Desbloquear\" Onclick=\"DesbloquearUser('".$LnUser['id']."')\"><i class=\"fa fa-unlock-alt\"></i></a></span>&nbsp;";
									}else{
										echo "<span id=\"StatusBloDes".$LnUser['id']."\"><a class=\"bloquear label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Bloquear\" Onclick=\"BloquearUser('".$LnUser['id']."')\"><i class=\"fa fa-lock\"></i></a></span>&nbsp;";
									}	
												
										echo "<span id=\"StatusDeletar".$LnUser['id']."\"><a class=\"deletar label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Excluir\" onclick=\"Deletar('".$LnUser['id']."', 'StatusDeletar".$LnUser['id']."');\"><i class=\"fa fa-trash-o\"></i></a></span>&nbsp;";
									
									echo "<a class=\"renovar label label-info\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Renovar\" onclick=\"Renovar('".$LnUser['id']."', '".$LnUser['login']."');\"><i class=\"fa fa-repeat\"></i></a>&nbsp;";
									
									if($_SESSION['acesso'] == 1){
										echo "<a class=\"revendedor label label-warning\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Tornar Revendedor?\" onclick=\"Revendedor('".$LnUser['id']."', '".$LnUser['login']."');\"><i class=\"fa fa-user\"></i></a>&nbsp;";
									}
									
									echo "</div></td>";
											
									echo "</tr>";
										}
										?>
                                            </tbody>
                                        </table>
          
	<!-- START THIS PAGE PLUGINS-->        
         <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/DataTables-br.js"></script>  
        
        <!-- END THIS PAGE PLUGINS-->        
      
 
<?php
}else{
	echo Redirecionar('index.php');
}	

}else{
	echo Redirecionar('login.php');
}	
?>