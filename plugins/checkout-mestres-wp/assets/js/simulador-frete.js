jQuery(document).ready(function($) {
	$('.cwmp-simulador-frete button').click(function(){
		$.ajax({
			type: 'POST',
			url: cwmp.ajaxUrl,
			data: { 'action': 'cwmp_simulador_frete', 'cep': $('.cwmp-simulador-frete input').val(), 'produto': $('.cwmp-simulador-frete button').attr('id'), 'qtde': $('.qty').val() },
			success: function(dados) {
				
				$('.cwmp-simulador-frete-retorno ul').html(dados);
			}
		});	
		return false;
	});
});