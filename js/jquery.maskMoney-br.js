jQuery(function($){
	var ExecutarValor = function(){
			$('#ValorCobrado').maskMoney({
				prefix:"R$ ",
				decimal:",",
				thousands:"."
			});
	}  
	
	ExecutarValor();
});