<?php
/**
 * Quicktag Integration.
 */

add_action(
	'admin_enqueue_scripts',
	function() {
		$settings = get_option( 'quick_adsense_settings' );
		if ( isset( $settings['enable_quicktag_buttons'] ) && ( $settings['enable_quicktag_buttons'] ) ) {
			$args = [
				'active_ads'         => [],
				'enable_randomads'   => ( ( isset( $settings['disable_randomads_quicktag_button'] ) && ( $settings['disable_randomads_quicktag_button'] ) ) ? false : true ),
				'enable_disableads'  => ( ( isset( $settings['disable_disablead_quicktag_buttons'] ) && ( $settings['disable_disablead_quicktag_buttons'] ) ) ? false : true ),
				'enable_positionads' => ( ( isset( $settings['disable_positionad_quicktag_buttons'] ) && ( $settings['disable_positionad_quicktag_buttons'] ) ) ? false : true ),
			];
			for ( $i = 1; $i <= 10; $i++ ) {
				if ( '' !== quick_adsense_get_value( $settings, 'onpost_ad_' . $i . '_content', '' ) ) {
					$args['active_ads'][] = $i;
				}
			}
			wp_add_inline_script(
				'quicktags',
				quick_adsense_load_file( 'templates/js/quicktags.php', $args )
			);
		}
	}
);
