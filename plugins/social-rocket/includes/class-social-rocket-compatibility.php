<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Compatibility {

	protected static $instance = null;

	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
	
		add_filter( 'social_rocket_insert_floating_data', array( $this, 'tasty_recipes' ) );
		add_filter( 'social_rocket_insert_inline_data', array( $this, 'tasty_recipes' ) );
		
	}
	
	
	/**
	 * Compatibility with plugin "Tasty Recipes"
	 *
	 * Tasty Recipes gets the page content early to add some meta tag stuff
	 * in the <head>. We need to catch if this is the case and make sure not
	 * to output the buttons here.
	 *
	 * @since 1.2.5
	 *
	 * @param array $data The data for the current insert.
	 *
	 * @return array The data for the current insert.
	 */
	public function tasty_recipes( $data ) {
		
		if ( class_exists( 'Tasty_Recipes' ) && function_exists( 'debug_backtrace' ) ) {
			$data['doing_excerpt'] = false;
			$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
			foreach ( $backtrace as $call ) {
				if ( 
					( isset( $call['class'] ) && $call['class'] === 'Tasty_Recipes\Distribution_Metadata' ) ||
					( isset( $call['function'] ) && $call['function'] === 'get_the_excerpt' )
				) {
					$data['doing_excerpt'] = true;
					break;
				}
			}
		}
		
		return $data;
	}

}
