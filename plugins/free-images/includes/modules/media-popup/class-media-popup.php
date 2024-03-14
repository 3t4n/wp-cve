<?php
/**
 * Media Popup class.
 *
 * @since      2.0.0
 * @package    FAL
 * @subpackage FAL\Media_Popup
 * @author     FAL <support@surror.com>
 */

namespace FAL;

defined( 'ABSPATH' ) || exit;

/**
 * Media Popup class.
 */
class Media_Popup {

	/**
	 * The single instance of the class.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @param  string $hook
	 */
	public function enqueue_scripts( $hook = '' ) {

		wp_enqueue_style(
			'fal-media-popup',
			FAL_URI . 'css/media-popup.css',
			[ 'fal' ],
			FAL_VERSION,
			'all'
		);

        wp_enqueue_script(
			'fal-media-popup',
			FAL_URI . 'includes/modules/media-popup/build/stats.js',
			[
				'wp-blocks',
                'wp-i18n',
                'wp-element',
                'wp-components',
                'wp-api-fetch'
			],
			FAL_VERSION,
			true
		);

	}

}
