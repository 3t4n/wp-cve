<?php
/**
 * Notices class.
 *
 * @since      2.0.0
 * @package    FAL
 * @subpackage FAL\Notices
 * @author     FAL <dev@surror.com>
 */

namespace FAL;

use FAL\Surror\Notices as Core;

defined( 'ABSPATH' ) || exit;

/**
 * Notices class.
 */
class Notices extends Core {

	/**
	 * Instance
	 */

	/**
	 * The single instance of the class.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_notices', [ $this, 'add_notices' ] );
	}

	/**
	 * Add notices.
	 */
	public function add_notices() {

		// Welcome notice.
		self::register( [
			'id' => 'fal-welcome',
			'message' => __( '<h2>Welcome aboard!</h2><p>We\'re thrilled to have you using our "Free Assets Library" plugin! Discover the Benefits of Our Plugin and learn <a href="#" target="_blank">how you can use it</a> or <a href="#" target="_blank">reach out to us</a> for assistance.</p>', 'free-images' ),
		] );
	}

}
