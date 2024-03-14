<?php
namespace um_ext\um_online\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Online_Shortcode
 * @package um_ext\um_online\core
 */
class Online_Shortcode {

	/**
	 * Online_Shortcode constructor.
	 */
	public function __construct() {
		add_shortcode( 'ultimatemember_online', array( &$this, 'ultimatemember_online' ) );
	}

	/**
	 * Online users list shortcode
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function ultimatemember_online( $args = array() ) {
		UM()->Online()->enqueue_scripts();

		$args = shortcode_atts(
			array(
				'max'   => 11,
				'roles' => 'all',
			),
			$args,
			'ultimatemember_online'
		);

		$args['online'] = UM()->Online()->get_users();
		$template       = ( $args['online'] && count( $args['online'] ) > 0 ) ? 'online' : 'nobody';

		return UM()->get_template( "{$template}.php", um_online_plugin, $args );
	}
}
