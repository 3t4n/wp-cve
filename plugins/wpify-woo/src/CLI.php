<?php

namespace WpifyWoo;


use WP_CLI;
use WP_CLI_Command;

use WpifyWoo\Api\FeedApi;
use WpifyWooDeps\Wpify\Core\Traits\ComponentTrait;

use function WP_CLI\Utils\make_progress_bar;

/**
 * Class CLI
 * @package  ApCompetitions
 * @property Plugin $plugin
 */
class CLI extends WP_CLI_Command {

	use ComponentTrait;

	public function __construct() {
		parent::__construct();
		WP_CLI::add_command( 'wpify_woo', self::class );
		$this->plugin = wpify_woo();
	}

	public function setup() {
		add_filter( 'http_request_args', array( $this, 'allow_unsafe_request_args' ), 20, 2 );
	}

	function allow_unsafe_request_args( $args, $url ) {
		$args['reject_unsafe_urls'] = false;

		return $args;
	}


	/**
	 * Prints a greeting.
	 *
	 * ## OPTIONS
	 *
	 * <feed>
	 * : The ID of the feed to generate.

	 * ## EXAMPLES
	 *
	 *     wp example hello Newman
	 *
	 * @when after_wp_load
	 */
	public function generate_feed( $args ) {
		$module = $this->plugin->get_api(FeedApi::class)->get_module($args[0]);

		if ( ! $module ) {
			return new \WP_Error( 'module-not-found', __( 'Module not found', 'wpify-woo' ) );
		}

		$module->get_feed()->delete_tmp_file();
		$module->get_feed()->generate_feed();
	}
}
