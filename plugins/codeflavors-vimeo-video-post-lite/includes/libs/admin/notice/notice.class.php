<?php

namespace Vimeotheque\Admin\Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Notice
 *
 * Can be used to register notice on the fly
 * Usage:
 *
 * Admin_Notices::instance()->register( new Notice( 'The message to be displayed' ) );
 *
 * @package Vimeotheque
 * @ignore
 */
class Notice extends Notice_Abstract implements Notice_Interface{
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @param $message
	 */
	public function __construct( $message ){
		$this->message = $message;
		parent::__construct();
	}

	/**
	 * Returns notice content
	 * @return mixed
	 */
	public function get_notice() {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			$this->message
		);
	}
}