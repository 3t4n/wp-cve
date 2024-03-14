( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Woocommerce Add to cart with ajax.
		 */
		$( document ).on( 'click', '.youzify-activate-addon-key', function (e) {
			// return;
		    e.preventDefault();

		    var button = $( this );

		    if ( button.hasClass( 'loading' ) ) {
		    	return;
		    }

	        var parent = button.closest( '.youzify-addon-license-area' ),
	        	title = button.text(),
	        	data = {
		        action: 'youzify_save_addon_key_license',
		        license: $( '.youzify-addon-license-key' ).find( 'input' ).val(),
		        product_name : button.data( 'product-name' ),
		        name: button.data( 'option-name' )
		    };
		    $.ajax({
		        type: 'post',
		        url: Youzify.ajax_url,
		        data: data,
		        beforeSend: function (response) {
		        	button.addClass( 'loading' );
		            button.html( '<i class="fas fa-spin fa-spinner"></i>' );
		            parent.find( '.youzify-addon-license-msg' ).remove();
		        },
		        complete: function (response) {
		            button.html( title );
		        	button.removeClass( 'loading' );
		        },
		        success: function (response) {

		            if ( response.success ) {
		            	button.parent().hide( 100, function() {
		            		$.ShowPanelMessage( { msg : response.data.message, type : 'success' });
				            location.reload();
		            		// button.closest( '.youzify-addon-license-area' ).append( '<div class="youzify-addon-license-msg youzify-addon-success-msg">' +  response.success + '</div>' );
		            		// $( this ).remove();
		            	});
		            } else {
		            	$.ShowPanelMessage( { msg : response.data.message, type : 'error' });
		            }
		        }
		    });

		    return false;
		});

	});

})( jQuery );