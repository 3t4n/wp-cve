<?php
defined( 'ABSPATH' ) || die();

/**
 * Class Funnel_Redirect.
 * Handle funnel step base url.
 *
 * @since 1.8.1
 */
class Funnel_Redirect {
	/**
	 * The funnel step url.
	 *
	 * @since 1.8.1
	 * @var string
	 */
	public static $step_base = 'sellkit_step';

	/**
	 * Funnel_Redirect constructor.
	 *
	 * @since 1.8.1
	 */
	public function __construct() {
		self::$step_base = get_option( 'sellkit_funnel_permalink_base', 'sellkit_step' );

		if ( 'sellkit_step' === self::$step_base ) {
			return;
		}

		add_filter( 'register_post_type_args', [ $this, 'replace_sellkit_step_slug' ], 10, 2 );
	}

	/**
	 * Change sellkit_step slug in url.
	 *
	 * @param array  $args      Post type arguments.
	 * @param string $post_type Post type slug.
	 * @since 1.8.1
	 * @return array
	 */
	public function replace_sellkit_step_slug( $args, $post_type ) {
		if ( 'sellkit_step' === $post_type ) {
			$args['rewrite']['slug'] = self::$step_base;
		}

		return $args;
	}
};

new Funnel_Redirect();

