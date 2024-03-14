jQuery(document).ready(function($){
	$('.color-picker').wpColorPicker()
	
	$('#density-range').on('input change', function(e){
		var percent = Math.round( 100 * $(this).val() )
		$(this).attr( 'title', percent + '%' )
		$('#density-value').val( percent ).attr( 'title', percent + '%' )
	}).change()
	
	
	wp.codeEditor.initialize( $( '#rn_pbwp_custom_css' ) )	
})