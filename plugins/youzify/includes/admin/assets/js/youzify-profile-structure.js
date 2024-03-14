( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 *  Move Profile Widgets
		 */
		$( '.youzify-draggable-area' ).sortable( {
			connectWith: '.youzify-draggable-area',
			receive : function( event, ui ) {

				// Get Widget Data
				var wg_type 	 = $( this ).data( 'widgetsType'),
					wg_name 	 = ui.item.data( 'widgetName' ),
					wg_name_attr = 'youzify_profile_' + wg_type + '[' + wg_name +  ']';

				// Change widget name.
				ui.item.find( '.youzify_profile_widget' ).attr( 'name', wg_name_attr );
		    }

		} );

		/**
		 *  Hide Profile Widgets
		 */
		$( document ).on( 'click', '.youzify-hide-wg', function() {
			var widget = $( this ).closest( 'li' );
			widget.toggleClass( 'youzify-hidden-wg' );
			// Change Input Value
			if ( widget.hasClass( 'youzify-hidden-wg' ) ) {
				widget.find( '.youzify_profile_widget' ).val( 'invisible' );
				widget.find( '.youzify-hide-wg' ).attr( 'title', Youzify_Profile_Structure.show_wg );
			} else {
				widget.find( '.youzify_profile_widget' ).val( 'visible' );
				widget.find( '.youzify-hide-wg' ).attr( 'title', Youzify_Profile_Structure.hide_wg );
			}
		});

		/**
		 * Show Main Sidebar.
		 */
		$( document ).on( 'change', 'input[name="youzify_options[youzify_profile_layout]"]', function() {

			$( '.youzify-profile-structure' ).attr( 'data-layout', $( this ).val() );

			var box = $( '.youzify-profile-main-sidebar' );

	        if ( $( this ).val() == 'youzify-3columns' ) {
	        	box.fadeIn();
	        } else {
	        	box.fadeOut();
        	}

    	});

		$( 'input[name="youzify_options[youzify_profile_layout]"]:checked').trigger( 'change' );

	});

})( jQuery );