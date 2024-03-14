<?php

/**
 * Add Patches Settings Tab
 */
function youzify_patches_settings() {

	do_action( 'youzify_patches_settings' );

	wp_enqueue_script( 'jquery' );

	// Get Patches JS.
	youzify_patches_settings_js();

}

function youzify_patches_settings_js() {

	?>

	<script type="text/javascript">

	( function( $ ) {

		/**
		 * Process Updating Fields.
		 */
		$.youzify_patch_process_step = function( current_button, action, step, perstep, total, self ) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: action,
					step: step,
					total: total,
					perstep: perstep,
				},
				dataType: 'json',
				success: function( response ) {

					if ( 'done' == response.step ) {

						current_button.addClass( 'youzify-is-updated' );

						// window.location = response.url;
						current_button.html( '<i class="fas fa-check"></i>Done !' );

					} else {

						current_button.find( '.youzify-button-progress' ).animate({
							width: response.percentage + '%',
						}, 50, function() {
							// Animation complete.
						});

						var total_items = ( response.step * response.perstep ) - response.perstep,
							items = total_items < response.total ? total_items : response.total;

						current_button.find( '.youzify-items-count' ).html( items );

						$.youzify_patch_process_step( current_button, action, parseInt( response.step ), parseInt( response.perstep ), parseInt( response.total ), self );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}


		/**
		 * Process Updating Fields.
		 */
		$.youzify_run_patch = function( current_button, action, self ) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: action,
				},
				dataType: "json",
				success: function( response ) {

					if ( 'done' == response.step ) {

						current_button.addClass( 'youzify-is-updated' );

						// window.location = response.url;
						current_button.html( '<i class="fas fa-check"></i>Done !' );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}

		$( 'body' ).on( 'click', 'a[data-run-single-patch="true"]', function(e) {

			if ( $( this ).hasClass( 'youzify-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating...' );

			// Start The process.
			$.youzify_run_patch( $( this ), $( this ).data( 'action' ), self );


		});

		$( 'body' ).on( 'click', 'a[data-run-patch="true"]', function(e) {

			if ( $( this ).hasClass( 'youzify-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			var per_step = $( this ).data( 'perstep' );
			var total = $( this ).data( 'total' );
			var action = $( this ).data( 'action' );

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating <div class="youzify-button-progress"></div><span class="youzify-items-count">' + 1 + '</span>' + ' / ' + total + ' ' + $( this ).data( 'items' ) );

			// Start The process.
			$.youzify_patch_process_step( $( this ), action, 1, per_step, total, self );

		});

	})( jQuery );

	</script>

	<?php

}