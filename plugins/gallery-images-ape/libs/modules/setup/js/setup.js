/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

(function($){
	
	var data_plugin = false;
	
	$('tr[data-slug^="'+ ape_gallery_setup.slug +'"] .deactivate a, .ape_gallery_setup-footer .button-close').click(function(){
		data_plugin = $(this).parent().parent().parent().parent().attr('data-plugin');
		$('button.allow-deactivate').html( ape_gallery_setup.skip );
		$('.ape_gallery_setup-deactivation-feedback').toggleClass('active');
		$('.ape_gallery_setup input[type=text]').hide()
		$('.ape_gallery_setup input[name=check]').prop('checked', false);
		return false;
	});
	
	$('.ape_gallery_setup input[name=check]').change(function(){
		var val = $(this).val();
		$('.ape_gallery_setup input[type=text]').hide().val('');
		$('button.allow-deactivate').html( ape_gallery_setup.submit );
		
		if( val == 2 || val == 7 ){
			$(this).parent().parent().parent().find('.internal-message input[type=text]').show();
		}
	});
	
	$('.ape_gallery_setup-footer .allow-deactivate').click(function(){
		var data = $('form.ape_gallery_setup').serialize();

		/*console.log(data);
		console.log(ape_gallery_setup);
		console.log(data + '&plugin='+ data_plugin +'&action=ape_gallery_setup');*/

		data += '&_ajax_nonce='+ape_gallery_setup.ajax_nonce;

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data + '&plugin='+ data_plugin +'&action=ape_gallery_setup',
			success: function(data) {
				location.reload();
			},
			error:  function(xhr, str){
				console.log('error: ' + xhr.responseCode);
			}
		});
	
		return false;
	});

})(jQuery);