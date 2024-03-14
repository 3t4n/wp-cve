jQuery(document).ready(function($)
{
	$('#ichart-ichart_chart'). on("click", function(e){
		e.preventDefault();
		
		$('#ichart-qcld-chart-field-modal').show();
		
		
	})
	$( '.ichart-chart-field-modal-close' ).on( 'click', function() {
		
		$('#ichart-qcld-chart-field-modal').hide();
	});
	
	$('#ichart-ichartaddrow'). on("click", function (e){
		e.preventDefault();
		
		$("#ichart-iChartdatasettable tbody").append('<tr><td class="check-column" scope="row"><input type="text" name="label[]" /></td><td class="column-columnname"><input type="text" name="value[]" /></td><td class="column-columnname"><input type="text" name="bgcolor[]" class="color-field" /></td><td class="column-columnname"><a href="javascript:void(0);" class="button button-secondary iChartremoverow">Remove</a></td></tr>');
		$('.color-field').wpColorPicker();
	})
	
	
	
	
	$('#ichart-chartremoverow'). on("click", function (e){
		e.preventDefault();
		$('#ichart-iChartdatasettable').each(function(){
			if($('tbody', this).length > 0){
				$('tbody tr:last', this).remove();
			}else {
				$('tr:last', this).remove();
			}
		});
	})
	$('#ichart-iChartdatasettable').on ("click", ".iChartremoverow", function(e){
		e.preventDefault();
		
		$(this).parent().parent().remove();
	})
//charttype onchange event
	
});