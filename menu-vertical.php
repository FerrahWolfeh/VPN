<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
?>
<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
<script type='text/javascript'>  
$(function(){  
 $("a.Sair").click(function() { 
 
 		var titulo = 'Sair?';
		var texto = 'Tem certeza que deseja sair?';
		var tipo = 'danger';
		var link = 'sair.php';
		var fa = 'fa-sign-out';  
			
		$.post('ScriptAlerta.php', {titulo: titulo, texto: texto, tipo: tipo, link: link, fa: fa}, function(resposta) {
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
		});
	});
});
</script>
<div id="StatusGeral"></div>

<style>
.SpanIco{
	color:#FFF;
	height: 50px;
    display: flex;
	align-items: center; 
	justify-content: center;
}
</style>

<!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                	
                    <?php
					$userCota = $_SESSION['id'];
					$VerificarLimiteTeste = VerificarLimiteTeste($userCota);
					$VerificarLimiteTeste = $VerificarLimiteTeste == 0 ? "Ilimitado" : $VerificarLimiteTeste;
					$VerificarCotaTeste = VerificarCotaTeste($userCota);
					
					
					$SQLDes = "SELECT cota, expiredate, LimiteTeste FROM login WHERE id = :id";
					$SQLDes = $banco->prepare($SQLDes);
					$SQLDes->bindParam(':id', $userCota, PDO::PARAM_STR);
					$SQLDes->execute();
					$LnDes = $SQLDes->fetch();
					
					if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
					$CotaUserOnline = $LnDes['cota'];
					
					if($CotaUserOnline < 1){
						$CotaRev = "Esgotado";
						$Cota = "<td><span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"\" data-original-title=\"Esgotado\">Esgotado</span></td>";	
					}else{
						$CotaRev = $CotaUserOnline;
						$Cota = "<td><span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"\" data-original-title=\"".$CotaUserOnline."\">".$CotaUserOnline."</span></td>";	
					}
					
					echo "<li class=\"xn-icon-button\">
                        <span class=\"SpanIco\" style=\"width: 120px;\">Creditos:&nbsp;&nbsp;".$Cota."</span>
                    </li>";
					
					if($_SESSION['acesso'] == 2){
					$DataAtual = time();	
					$FaltaDias = $LnDes['expiredate'] - $DataAtual;
					$dias_restantes = floor($FaltaDias / 60 / 60 / 24);
					$DiasSS = $dias_restantes > 1 ? "dias" : "dias";
					
					$DataExpirar = date('d/m/Y', $LnDes['expiredate']);
					
					if($dias_restantes < 1){
						$TempoPremium = "<td><span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"\" data-original-title=\"Esgotado\">Esgotado</span> ".$DataExpirar."</td>";	
					}else{
						$TempoPremium = "<td><span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"\" data-original-title=\"".$dias_restantes." ".$DiasSS."\">".$dias_restantes." ".$DiasSS."</span>&nbsp;&nbsp;".$DataExpirar."</td>";	
					}
						
					echo "<li class=\"xn-icon-button\">
                    <span class=\"SpanIco\" style=\"width: 150px;\">".$TempoPremium."</span>
                    </li>";	
					}
				
					}
					else{
					$DataAtual = time();	
					$FaltaDias = $LnDes['expiredate'] - $DataAtual;
					$dias_restantes = floor($FaltaDias / 60 / 60 / 24);
					$DiasSS = $dias_restantes > 1 ? "dias" : "dias";
					
					$DataExpirar = date('d/m/Y', $LnDes['expiredate']);
					
					if($dias_restantes < 1){
						$TempoPremium = "<td><span class=\"pointer label label-danger\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"\" data-original-title=\"Esgotado\">Esgotado</span> ".$DataExpirar."</td>";	
					}else{
						$TempoPremium = "<td><span class=\"pointer label label-success\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"\" data-original-title=\"".$dias_restantes." ".$DiasSS."\">".$dias_restantes." ".$DiasSS."</span>&nbsp;&nbsp;".$DataExpirar."</td>";	
					}
						
					echo "<li class=\"xn-icon-button\">
                    <span class=\"SpanIco\" style=\"width: 260px;\">Tempo Premium:&nbsp;&nbsp;".$TempoPremium."</span>
                    </li>";	
					}
					?>
               		 
                    <!-- POWER OFF -->
                    <li class="xn-icon-button pull-right last">
                        <a href="#"><span class="fa fa-power-off"></span></a>
                        <ul class="xn-drop-left animated zoomIn">
                            <li><a href="#" class="mb-control Sair"><span class="fa fa-sign-out"></span> Sair</a></li>
                        </ul>                        
                    </li> 
                    <!-- END POWER OFF -->                    
                     
                
				 <!-- MESSAGES -->
                    <li class="xn-icon-button pull-right">
                        <a href="index.php?p=suporte&a=1"><span class="fa fa-envelope"></span></a></li>
				<!-- END MESSAGES -->                  
                     
                </ul>

                <!-- END X-NAVIGATION VERTICAL --> 

                <!-- END X-NAVIGATION VERTICAL --> 
                
<?php
}else{
	echo Redirecionar('login.php');
}
?>