<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<script type="text/javascript" >
	jQuery( document ).ready(function( $ ) {
		$( '.fs-notice.fs-sticky .fs-close' ).click(function() {
			var
				notice           = $( this ).parents( '.fs-notice' ),
				id               = notice.attr( 'data-id' ),
				ajaxActionSuffix = notice.attr( 'data-manager-id' ).replace( ':', '-' );

			notice.fadeOut( 'fast', function() {
				var data = {
					action   : 'fs_dismiss_notice_action_' + ajaxActionSuffix,
                    // As such we don't need to use `wp_json_encode` method but using it to follow wp.org guideline.
                    _wpnonce : <?php echo wp_json_encode( wp_create_nonce( 'fs_dismiss_notice_action' ) ); ?>,
					message_id: id
				};

				$.post( <?php echo Freemius::ajax_url() ?>, data, function( response ) {

				});

				notice.remove();
			});
		});
	});
</script>
