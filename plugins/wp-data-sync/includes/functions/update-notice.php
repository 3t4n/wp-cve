<?php
/**
 * Update Notice
 *
 * Display update notices
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'in_plugin_update_message-wp-data-sync/plugin.php', 'WP_DataSync\App\update_message', 10, 2 );

/**
 * print an update message.
 *
 * @param $data
 * @param $response
 */

function update_message( $data, $response ) {

	if( isset( $data['upgrade_notice'] ) ) {

		printf(
			'<div class="update-message">%s</div>',
			wpautop( $data['upgrade_notice'] )
		);

	}

}
