jQuery(document).ready(function($) {

	$("body").on('change', "input[name='request_type']", function() {
			var value = $(this).val();
			if (value == 'praise_report') {
				$('#prayer_title_row').parent().parent().addClass('hiderow');	
			} else {
				$('#prayer_title_row').parent().parent().removeClass('hiderow');

			}
    });
    
    if($("input[name='request_type']:checked").val() == 'praise_report') {
		$('#prayer_title_row').parent().parent().addClass('hiderow');	
	} else {
		$('#prayer_title_row').parent().parent().removeClass('hiderow');
	}
	
});
