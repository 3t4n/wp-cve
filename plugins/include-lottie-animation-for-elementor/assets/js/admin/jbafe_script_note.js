jQuery(document).ready(function(){
    $ = jQuery.noConflict();    
	$(document).on('click', '.jbafe-notice .notice-dismiss, .jbafe-notice .jbafe-done', function() {
		var $jbafe_click = $(this).closest('.jbafe-notice');		
		$jbafe_click.slideUp();
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'jbafe_top_notice'
			}
		})
	});
});