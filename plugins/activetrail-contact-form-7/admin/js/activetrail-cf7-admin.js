(function( $ ) {
	'use strict';
		
	$( document ).ready(function() {
		$("#mailing_list_id_tag").attr("placeholder", "Digits only");  

		$("#mailing_list_id_tag").keypress(function (e) {
			//if the letter is not digit then display error and don't type anything
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			   //display error message
			   	$("#mailing_list_id_tag").css("border", "2px solid red");
				   	setTimeout(function(){
						$("#mailing_list_id_tag").css("border", "none");
					}, 500);
					return false;
		   	}
		  });
		
		$('#at-opt-add').click(function() {			
			var $template = $('#at-opt-template');
			
			var rfc4 = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
				var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
				return v.toString(16);
			});
			
			var $clone = $template.clone();
			
			$clone.attr('id', null);
			$clone.removeClass('at-hidden');
			
			$clone.find('input[type="text"]').attr('name', 'wpcf7-activetrail-optional['+rfc4+'][src]');
			$clone.find('input[type="hidden"]').attr('name', 'wpcf7-activetrail-optional['+rfc4+'][action]');
			$clone.find('input[type="checkbox"]').attr('name', 'wpcf7-activetrail-optional['+rfc4+'][merge]');
			$clone.find('select').attr('name', 'wpcf7-activetrail-optional['+rfc4+'][dst]');

			$('#at-opt-cntr').append($clone);
		});
		
		$('#at-opt-cntr').on('click', 'button.at-opt-remove', function(){
			$(this).closest('tr').remove();
		});		
	});

})( jQuery );

