<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;

if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){

$id_cad = empty($_POST['id_cad']) ? '' : $_POST['id_cad'];
$rev = empty($_POST['rev']) ? '' : $_POST['rev'];
$mes = empty($_POST['mes']) ? '' : $_POST['mes'];
$ano = empty($_POST['ano']) ? '' : $_POST['ano'];

$SQLRev = "SELECT ValorCobrado FROM login WHERE id = :id";
$SQLRev = $banco->prepare($SQLRev);
$SQLRev->bindParam(':id', $id_cad, PDO::PARAM_STR);
$SQLRev->execute();
$LnRev = $SQLRev->fetch();
$ValorCobrado = $LnRev['ValorCobrado'];

if(empty($id_cad)){
	echo MensagemAlerta('Erro', 'Selecione um usuário da árvore!', "danger");
}
elseif(empty($rev)){
	echo MensagemAlerta('Erro', 'Exibir dos Revendedores é um campo obrigatório!', "danger");
}
elseif(empty($mes)){
	echo MensagemAlerta('Erro', 'Mês é um campo obrigatório!', "danger");
}
elseif(empty($ano)){
	echo MensagemAlerta('Erro', 'Ano é um campo obrigatório!', "danger");
}
else{
	$GerarRelatorio = GerarRelatorio($id_cad, $rev, $mes, $ano);
	$GerarRelatorioFinal = GerarRelatorioFinal($id_cad, $rev, $mes, $ano);
			
?>

							<div class="btn-group pull-right" style="padding:0px 0px 15px 0px;">
                                        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Exportar Tabela</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onClick ="SalvarPDF();"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                                            <li><a href="#" onClick ="SalvarDOC();"><img src='img/icons/word.png' width="24"/> WORD</a></li>
                                            <li><a href="#" onClick ="SalvarExcel();"><img src='img/icons/xls.png' width="24"/> EXCEL</a></li>
                                            <li><a href="#" onClick ="SalvarPNG();"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                                            
                                        </ul>
                            </div>
                            
                            
                           <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title">Relatório Final</h3>                                  
                                </div>
                                
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                     
                                       <table id="Tabela" class="SalvarTabela table table-striped">
                               <thead>
                               		<tr>
                                        <th>Total de Cotas Utilizadas</th>
                                        <th>Valor à Receber</th>
                                        <th>Valor à Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
										
									$CotaInicial = 0;
									$ValorReceber = 0;
									
									for($i=0; $i<count($GerarRelatorioFinal); $i++){
										$ValorReceber += $GerarRelatorioFinal[$i][3];
										$CotaInicial += $GerarRelatorioFinal[$i][2];
									}
									
									$ValorPagar = $ValorCobrado * $CotaInicial;
									$ValorReceber = empty($ValorReceber) ? "R$ 0,00" : "R$ ".number_format($ValorReceber, 2, ',', '');
									$ValorPagar = empty($ValorPagar) ? "R$ 0,00" : "R$ ".number_format($ValorPagar, 2, ',', '');
									
									echo "	
                                        <tr>
											<td>".$CotaInicial."</td>
											<td>".$ValorReceber."</td>
											<td>".$ValorPagar."</td>
                                       	 ";
									
								 	echo "</tr>";
								  
										?>
										
                                            </tbody>
                                        </table>
                                                                 
                                    </div>
                                </div>
                            </div>
                            
                            
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title">Relatório Detalhado</h3>                                  
                                </div>
                                
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                     
                                       <table id="Tabela" class="SalvarTabela table table-striped">
                               <thead>
                               		<tr>
                                    	<th>Revendedor</th>
                                    	<th>Valor Cobrado</th>
                                        <th>Total</th>
                                        <th>Valor à Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
									
									for($i=0; $i<count($GerarRelatorioFinal); $i++){
										
									$valor = empty($GerarRelatorioFinal[$i][1]) ? "R$ 0,00" : "R$ ".number_format($GerarRelatorioFinal[$i][1], 2, ',', '');
									$ValorPagar = empty($GerarRelatorioFinal[$i][3]) ? "R$ 0,00" : "R$ ".number_format($GerarRelatorioFinal[$i][3], 2, ',', '');
										
									echo "	
                                        <tr>
											<td width=\"50\">".$GerarRelatorioFinal[$i][0]."</td>
											<td>".$valor."</td>
											<td>".$GerarRelatorioFinal[$i][2]."</td>
											<td>".$ValorPagar."</td>
                                       	 ";
									
								 	echo "</tr>";
									}
								  
										?>
										
                                            </tbody>
                                        </table>
                                                                 
                                    </div>
                                </div>
                            </div>
                            

						<div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title">Relatório de Usuários</h3>                                  
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                     
                                     
                                       <table id="Tabela" class="SalvarTabela table table-striped">
                               <thead>
                               		<tr>
                                    	<th>Revendedor</th>
                                    	<th>Mês</th>
                                        <th>Ano</th>
                                        <th>Usuário</th>
                                        <th>Operadora</th>
                                        <th>Status</th>
                                        <th>Data de Ativação</th>
                                        <?php if($_SESSION['acesso'] == 1) echo "<th>Deletar</th>"; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
									
									for($i=0; $i<count($GerarRelatorio); $i++){
																				
									$SQLRev = "SELECT login FROM login WHERE id = :id";
									$SQLRev = $banco->prepare($SQLRev);
									$SQLRev->bindParam(':id', $GerarRelatorio[$i][0], PDO::PARAM_STR);
									$SQLRev->execute();
									$LnRev = $SQLRev->fetch();
									
									$SQLUser = "SELECT login, operadora, expiredate FROM login WHERE id = :id";
									$SQLUser = $banco->prepare($SQLUser);
									$SQLUser->bindParam(':id', $GerarRelatorio[$i][3], PDO::PARAM_STR);
									$SQLUser->execute();
									$LnUser = $SQLUser->fetch();
									
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
									
									$DataExpirar = date('d/m/Y', $LnUser['expiredate']);
									$DataAtual = time();
									$DataFinal = $LnUser['expiredate'];
										
									$FaltaDias = $DataFinal - $DataAtual;
									$dias_restantes = floor($FaltaDias / 60 / 60 / 24);
									
									if($dias_restantes < 0){
									$ExibirStatus = "<span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Esgotado\">Esgotado</span> ".$DataExpirar."";	
									}else{
									$dias_restantes = 1 + $dias_restantes;
									$DiasSS = $dias_restantes > 1 ? "dias" : "dia";
									$ExibirStatus = "<span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"".$dias_restantes." ".$DiasSS."\">".$dias_restantes." ".$DiasSS."</span> ".$DataExpirar."";	
									}
									
									echo "	
                                        <tr>
											<td width=\"50\">".$LnRev['login']."</td>
											<td>".NomeMes($GerarRelatorio[$i][1])."</td>
											<td>".$GerarRelatorio[$i][2]."</td>
											<td>".$LnUser['login']."</td>
											<td>".$icone_perfil." ".$LnUser['operadora']."</td>
											<td>".$ExibirStatus."</td>
											<td>".date("d/m/Y", $GerarRelatorio[$i][4])."</td>
                                       	 ";
										 
										 if($_SESSION['acesso'] == 1){							
										echo "<td><span id=\"StatusDeletar".$GerarRelatorio[$i][3]."\"><a class=\"deletar label label-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Excluir\" onclick=\"Deletar('".$GerarRelatorio[$i][3]."', 'StatusDeletar".$GerarRelatorio[$i][3]."');\"><i class=\"fa fa-trash-o\"></i></a>&nbsp;</span></td>";
									}
										 
									
								 	echo "</tr>";
									}
								  
										?>
										
                                            </tbody>
                                        </table>
                                                                 
                                    </div>
                                </div>
                            </div>
		
        
		
                            
        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
                                    
        <script type="text/javascript" src="js/plugins/tableexport/libs/FileSaver/FileSaver.min.js"></script>
        <script type="text/javascript" src="js/plugins/tableexport/libs/jsPDF/jspdf.min.js"></script>
        <script type="text/javascript" src="js/plugins/tableexport/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
        <script type="text/javascript" src="js/plugins/tableexport/libs/html2canvas/html2canvas.min.js"></script>
        <script type="text/javascript" src="js/plugins/tableexport/tableExport.min.js"></script>
        <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
        
        <script type="text/javascript" src="js/plugins.js"></script>
        
        <script type="text/javascript">
        function SalvarPDF(){
			
					$('.SalvarTabela').tableExport({
						fileName: 'Relatorio_<?php echo time(); ?>',
                        type: 'pdf',
						escape: 'false',
                        jspdf: {format: 'bestfit',
                                margins: {left:20, right:10, top:20, bottom:20},
								autotable: {styles: {fontSize: '14'},
											tableWidth: 'wrap'
										   }
                               }
                    });
					
		}
		
	    function SalvarDOC(){
			$('.SalvarTabela').tableExport({
				fileName: 'Relatorio_<?php echo time(); ?>',
				type: 'doc',
				escape: 'false'
				})			
		}
		
		function SalvarExcel(){
			$('.SalvarTabela').tableExport({
				fileName: 'Relatorio_<?php echo time(); ?>',
				type: 'xls',
				escape: 'false'
				})			
		}
		
		function SalvarPNG(){
			$('.SalvarTabela').tableExport({
				fileName: 'Relatorio_<?php echo time(); ?>',
				type: 'png',
				escape: 'false'
				})			
		}
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