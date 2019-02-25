<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){

$m = empty($_GET['m']) ? "" : $_GET['m'];
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$id = empty($id) ? $m : $id;
$UserReceptor = $_SESSION['login'];
$pastaGet = empty($_GET['a']) ? 1 : $_GET['a'];
$Pasta = empty($_POST['pasta']) ? $pastaGet : $_POST['pasta'];

$SQL = "SELECT * FROM suporte WHERE id = :id AND UserReceptor = :UserReceptor OR id = :id AND UserEmissor = :UserEmissor";
$SQL = $banco->prepare($SQL);
$SQL->bindParam(':id', $id, PDO::PARAM_INT);
$SQL->bindParam(':UserReceptor', $UserReceptor, PDO::PARAM_STR);
$SQL->bindParam(':UserEmissor', $UserReceptor, PDO::PARAM_STR);
$SQL->execute();
$Ln = $SQL->fetch();
$UserEmissor = $Ln['UserEmissor'];
$Assunto = empty($Ln['Assunto']) ? "" : "Re: ".$Ln['Assunto']." ";
$Mensagem = empty($Ln['Mensagem']) ? "" : $Ln['Mensagem'];
$Anexo = empty($Ln['anexo']) ? "" : $Ln['anexo'];

if( ($Ln['UserReceptor'] == $UserReceptor) && ($Ln['LidaReceptor'] == "N") ){
		$LidaReceptor = "S";
		$SQL = "UPDATE suporte SET
			LidaReceptor = :LidaReceptor
            WHERE id = :id AND UserReceptor = :UserReceptor";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':LidaReceptor', $LidaReceptor, PDO::PARAM_STR); 
		$SQL->bindParam(':id', $id, PDO::PARAM_INT);
		$SQL->bindParam(':UserReceptor', $UserReceptor, PDO::PARAM_STR); 
		$SQL->execute(); 
}
if( ($Ln['UserEmissor'] == $UserReceptor) && ($Ln['LidaEmissor'] == "N") ){
		$LidaEmissor = "S";
		$SQL = "UPDATE suporte SET
			LidaEmissor = :LidaEmissor
            WHERE id = :id AND UserEmissor = :UserEmissor";
		$SQL = $banco->prepare($SQL);
		$SQL->bindParam(':LidaEmissor', $LidaEmissor, PDO::PARAM_STR); 
		$SQL->bindParam(':id', $id, PDO::PARAM_INT);
		$SQL->bindParam(':UserEmissor', $UserReceptor, PDO::PARAM_STR); 
		$SQL->execute();
}

//Resposta Anexo
$SQLResp = "SELECT * FROM suporteresp WHERE id_suporte = :id_suporte";
$SQLResp = $banco->prepare($SQLResp);
$SQLResp->bindParam(':id_suporte', $id, PDO::PARAM_INT);
$SQLResp->execute();
$TotalResp = count($SQLResp->fetchAll());
?>

<div class="content-frame-body">
                        
                        <div class="panel panel-default">
                        
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h3 class="panel-title"><?php echo $UserEmissor; ?></h3>
                                </div>
                                <?php
								if($Pasta == 4){
								?>
                                <div value="<?php echo $id; ?>" id="DeletarMensagemLixeira" class="pull-right">
                                    <button class="btn btn-default"><span class="fa fa-trash-o"></span></button>                                </div>
                                <?php	
								}
								else{
								?>
                                <div value="<?php echo $id; ?>" id="DeletarMensagem" class="pull-right">
                                    <button class="btn btn-default"><span class="fa fa-trash-o"></span></button>                                </div>
                                <?php
								}
								?>
                            </div>
                            <div class="panel-body">
                                <h3><?php echo $Assunto; ?><small class="pull-right text-muted"><span class="fa fa-clock-o"></span> <?php echo DataSuporte2($Ln['data']); ?></small></h3>
                                <?php echo $Mensagem; ?>  
                            </div>
                        </div>
                        
                        <?php
						$SQLResp->execute();
						while($LnResp = $SQLResp->fetch()){
							$UserEmissorResp = $LnResp['UserEmissor'];										
							$MensagemResp = empty($LnResp['mensagem']) ? "" : $LnResp['mensagem'];
						?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h3 class="panel-title"><?php echo $UserEmissorResp; ?></h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                <h3><small class="pull-right text-muted"><span class="fa fa-clock-o"></span> <?php echo DataSuporte2($LnResp['data']); ?></small></h3>
                                <?php echo $MensagemResp; ?>  
                            </div>
                        </div>
                        <?php
						}
						?>

                        
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form role="form" method="post" enctype="multipart/form-data" id="FormAnexo" action="javascript:MDouglasMS();">   
                                <div class="ResponderMensagem form-group push-up-20">
                                    <label>Resposta Rápida</label>
                                    <textarea class="form-control summernote_lite" name="mensagem" id="mensagem" rows="3" placeholder="Clique aqui para responder"></textarea>
                                </div>
                                
                                <div class="FormAnexar form-group" style="display:none;">
                                    <input type="file" class="fileinput btn-default" name="anexo" id="anexo" title="Anexar"/>
                                </div>
                                </form>
                            </div>
                            
                            <?php
							$AnexoResposta = "";
							if($TotalResp > 0){
										$SQLResp->execute();
										while($LnResp = $SQLResp->fetch()){
										$AnexoResp = empty($LnResp['anexo']) ? "" : $LnResp['anexo'];
										
										if(!empty($AnexoResp)){
										$ex2 = explode(".",$AnexoResp);	
										$extensao2 = end($ex2);
							
										$AnexoResposta .= "<tr>
										<td>".ImagemAnexo($extensao2)."</td>
										<td><a target=\"_blank\" href=\"anexo.php?a=".base64_encode($AnexoResp)."\">".$AnexoResp."</a></td>
                                	 </tr>";
										}
										}
									}
									
							if( !empty($Anexo) || !empty($AnexoResposta)){
							
							
                            echo "<div class=\"panel-body panel-body-table\">
                                <h6>Anexos</h6>
                                <table class=\"table table-bordered table-striped\">
                                    <tr>
                                        <th width=\"50\">Tipo</th>
										<th>Nome</th>
                                    </tr>";
									
									if( !empty($Anexo) ){
									
									$ex = explode(".",$Anexo);	
									$extensao = end($ex);	
									
                                    echo "<tr>
                                        <td>".ImagemAnexo($extensao)."</td>
										<td><a target=\"_blank\" href=\"anexo.php?a=".base64_encode($Anexo)."\">".$Anexo."</a></td>
                                    </tr>";
									}
									
									if(!empty($AnexoResposta)) echo $AnexoResposta;
									                                                            
                               echo "</table>
                            </div>";
							}
                            ?>
                            
                            <div class="ResponderPost panel-footer">
                            </div>
                        </div>
                        
                    </div>
                 
                <?php
				if(empty($m)){
				?>
                <script type="text/javascript" src="js/plugins/summernote/summernote-br.js"></script>
                <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
                <script type='text/javascript' src='js/plugins.js'></script>
                <?php
				}
				?>
                
                <script type='text/javascript'> 
                $(function(){  
 					$(".panel-heading #DeletarMensagem").on("click",function(){
				
						var SelectBox = $(this).attr("value");
																
						$.post('AtualizarLixeiraSupp.php', {SelectBox: SelectBox}, function(resposta) {
							$("#StatusGeral").html(resposta);
						});
						
					});
				});
				
				$(function(){  
 					$(".panel-heading #DeletarMensagemLixeira").on("click",function(){
				
						var SelectBox = $(this).attr("value");
																
						$.post('AtualizarLixeiraSuppLixeira.php', {SelectBox: SelectBox}, function(resposta) {
							$("#StatusGeral").html(resposta);
						});
						
					});
				});
				
				if($(".summernote_lite").length > 0){
        			$(".summernote_lite").on("focus",function(){
           	 			$(".ResponderPost").html('<button class="btn btn-success pull-right"><span class="fa fa-mail-reply"></span> Responder</button>');
						$( ".FormAnexar" ).show();
        			});                
        		}
				
				$(function(){  
 					$(".ResponderPost").on("click",function(){
						
						pageLoadingFrame("show");
						
						var formData = new FormData($("#FormAnexo")[0]); 
						formData.append( 'mensagem', $('#FormAnexo textarea[name="mensagem"]').code() );
						formData.append( 'id_suporte', '<?php echo $id; ?>');
						formData.append( 'pasta', '<?php echo $Pasta; ?>');
						
  						$.ajax({
    						url: 'UploadAnexo.php',
     						type: 'POST',
     						data: formData,
     						async: false,
     						cache: false,
     						contentType: false,
     						enctype: 'multipart/form-data',
    						processData: false,
     						success: function (response) {
								setTimeout(function(){
                       				pageLoadingFrame("hide");
									$("#StatusGeral").html(response);
              					},1000);
     						}
   						});
						
					});
				});
				
			</script>

<?php
}else{
	echo Redirecionar('login.php');
}	
?>