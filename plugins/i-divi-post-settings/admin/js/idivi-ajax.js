jQuery(document).ready(function($) {
    $('.idivi-notice.is-dismissible').on('click', '.notice-dismiss', function(e){
       e.preventDefault();
        
      data = {
      	action: 'idivi_dismiss',
      	idivi_nonce: idivi_vars.idivi_nonce
      };

     	$.post(ajaxurl, data, function(response) {
            $('.idivi-notice.notice-dismiss').html(response);

		});

		return false;
	});

});
