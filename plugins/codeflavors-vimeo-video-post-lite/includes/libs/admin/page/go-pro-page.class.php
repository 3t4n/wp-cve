<?php

namespace Vimeotheque\Admin\Page;

use Vimeotheque\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Go_Pro_Page
 * @package Vimeotheque\Admin
 * @ignore
 */
class Go_Pro_Page extends Page_Abstract implements Page_Interface{

	/**
	 * Page output callback
	 */
	public function get_html() {

		include VIMEOTHEQUE_PATH . 'views/go_pro.php';
	}

	/**
	 * Page on_load event callback
	 */
	public function on_load() {
		remove_all_actions( 'admin_notices' );
		wp_enqueue_style( 'cvm_gopro', VIMEOTHEQUE_URL . 'assets/back-end/css/gopro.css' );
		Helper::enqueue_player();
	}
}