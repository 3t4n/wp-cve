(function($){
    "use strict";

    jQuery(document).ready(function($) {
    'use strict';


        function getButtonState() {
			$( '.dafe-admin-element-state-saving' ).addClass('addons-save-button-state').removeAttr('disabled').css('cursor', 'pointer');
			$( '.dafe-admin-element-state-saving' ).css('background-color', '#000').css('color', '#fff');
		}
		
		
      
	   $('.dafe-definitive-admin-section-item input').on( 'click', function() {
            getButtonState();
        } );

 	
			$( '.dafe-disable-all-element' ).click( function() {
							$('.switcher').each( function() {
								this.checked = false;
							} );
							
				getButtonState();
                
				return false;
			} );
			
			$( '.dafe-enable-all-element' ).click( function() {
							$('.switcher').each( function() {
								this.checked = true;
							} );
							
				getButtonState();
				return false;
			} );
		
		
        // Elemements state saving With Ajax Request 
        $( '.dafe-admin-element-state-saving' ).on( 'click', function(e) {
            e.preventDefault();

            if( $(this).hasClass('addons-save-button-state') ) {

                // Elemements state saving
                $.ajax( {
					cache:false,
                    url: dafe_admin_settings_js.ajax_url,
                    type: 'post',
                    data: {
                        action: 'dafe_dashboard_save_elements',
                        security: dafe_admin_settings_js.security,
                        fields: $( '#dafe-addons-tab-settings' ).serialize(),
                    },
					beforeSend: function beforeSend() {
					
						$('.dafe-admin-element-state-saving').append('<span class="da-element-loading"><i class="eicon-spinner eicon-animation-spin"></i></span>');
						
					
					},
                    success: function( response ) {
						
						$( '.dafe-admin-element-state-saving' ).find('.da-element-loading').remove();
						$(".da-element-saved").show();
						$( '.da-element-saved' ).text('Saved Successfully');
                        $( '.dafe-admin-element-state-saving' ).removeClass( 'addons-save-button-state');
						
						setTimeout(function(){
 
							$(".da-element-saved").hide("2000")
 
						}, 2000);
                    
					},
                    error: function(jqXHR, error){
						var errorMessage = jqXHR.status + ': ' + jqXHR.statusText
						alert('Error - ' + errorMessage);
					}
                } );

            } else {
                
				$(this).css('background-color', '#cccccc').css('color', '#ddd');
				$(this).prop('disabled', 'true').css('cursor', 'not-allowed');
            }


        } );

});

})(jQuery);
