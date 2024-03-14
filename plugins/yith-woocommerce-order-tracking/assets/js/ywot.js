/**
 * YWOT JS
 *
 * @package YITH\OrderTracking\Assets\JS
 */

jQuery(
	function ($) {
		$( 'a.track-button' ).each( function() {
			$(this).tooltipster({
				theme: [ 'tooltipster-borderless', ywot.is_account_page ? 'tooltipster-borderless-customized-frontend' : 'tooltipster-borderless-customized-backend' ],
				content: $(this).attr( 'data-title' ),
				contentAsHTML: true,
				side: 'left',
				updateAnimation: null,
				delay: 200,
				interactive: true,
			});
		});

		// Handle fields dependencies in the order metabox.
		var order_picked_up_field = $( '.yith-ywot-order-picked-up-container #ywot_picked_up' ),
        	order_picked_up       = order_picked_up_field.val(),
        	fields_deps           = $( '.yith-ywot-tracking-code, .yith-ywot-tracking-carrier-name, .yith-ywot-tracking-pickup-date, .yith-ywot-tracking-carrier-url' );

		if ( 'no' === order_picked_up ) {
			fields_deps.hide();
		}

		order_picked_up_field.on( 'change', function( e ) {
			var opacity = 'yes' === $( this ).val() ? 1 : 0,
				display = 'yes' === $( this ).val() ? 'block' : 'none';

			fields_deps.fadeTo(
				'slow',
				opacity,
				function () {
					$( this ).css( { 'display': display } );
				}
			);
		});
	}
);
