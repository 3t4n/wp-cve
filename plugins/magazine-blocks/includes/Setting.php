<?php
/**
 * Setting API class.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks;

defined( 'ABSPATH' ) || exit;

/**
 * Setting class.
 */
class Setting {

	/**
	 * Default data.
	 *
	 * @var array
	 */
	private static $data = array(
		'blocks'           => array(
			'section'             => true,
			'heading'             => true,
			'advertisement'       => true,
			'banner-posts'        => true,
			'grid-module'         => true,
			'featured-posts'      => true,
			'featured-categories' => true,
			'tab-post'            => true,
			'post-list'           => true,
			'post-video'          => true,
			'category-list'       => true,
			'news-ticker'         => true,
			'date-weather'        => true,
			'social-icons'        => true,
			'slider'              => true,
		),
		'editor'           => array(
			'section-width'          => 1170,
			'editor-blocks-spacing'  => 24,
			'design-library'         => true,
			'responsive-breakpoints' => array(
				'tablet' => 992,
				'mobile' => 768,
			),
			'copy-paste-styles'      => true,
			'auto-collapse-panels'   => true,
		),
		'performance'      => array(
			'local-google-fonts'        => false,
			'preload-local-fonts'       => false,
			'allow-only-selected-fonts' => false,
			'allowed-fonts'             => array(),
		),
		'asset-generation' => array(
			'external-file' => false,
		),
		'version-control'  => array(
			'beta-tester' => false,
		),
		'maintenance-mode' => array(
			'mode'             => 'none',
			'maintenance-page' => null,
		),
		'integrations'     => array(
			'dateWeatherApiKey'     => '',
			'dateWeatherZipCode' => '',
		),
		'global-styles'    => '',
	);

	/**
	 * Sanitize callbacks.
	 *
	 * @var array
	 */
	private static $sanitize_callbacks = array(
		'blocks'           => array(
			'section'        => 'magazine_blocks_string_to_bool',
			'heading'        => 'magazine_blocks_string_to_bool',
			'paragraph'      => 'magazine_blocks_string_to_bool',
			'button'         => 'magazine_blocks_string_to_bool',
			'image'          => 'magazine_blocks_string_to_bool',
			'spacing'        => 'magazine_blocks_string_to_bool',
			'socials'        => 'magazine_blocks_string_to_bool',
			'tabs'           => 'magazine_blocks_string_to_bool',
			'toc'            => 'magazine_blocks_string_to_bool',
			'counter'        => 'magazine_blocks_string_to_bool',
			'lottie'         => 'magazine_blocks_string_to_bool',
			'team'           => 'magazine_blocks_string_to_bool',
			'countdown'      => 'magazine_blocks_string_to_bool',
			'info'           => 'magazine_blocks_string_to_bool',
			'blockquote'     => 'magazine_blocks_string_to_bool',
			'timeline'       => 'magazine_blocks_string_to_bool',
			'notice'         => 'magazine_blocks_string_to_bool',
			'progress'       => 'magazine_blocks_string_to_bool',
			'call-to-action' => 'magazine_blocks_string_to_bool',
			'slider'         => 'magazine_blocks_string_to_bool',
			'map'            => 'magazine_blocks_string_to_bool',
			'testimonial'    => 'magazine_blocks_string_to_bool',
		),
		'editor'           => array(
			'section-width'          => 'absint',
			'editor-blocks-spacing'  => 'absint',
			'design-library'         => 'magazine_blocks_string_to_bool',
			'copy-paste-styles'      => 'magazine_blocks_string_to_bool',
			'auto-collapse-panels'   => 'magazine_blocks_string_to_bool',
			'responsive-breakpoints' => array(
				'tablet' => 'absint',
				'mobile' => 'absint',
			),
		),
		'performance'      => array(
			'local-google-fonts'        => 'magazine_blocks_string_to_bool',
			'preload-local-fonts'       => 'magazine_blocks_string_to_bool',
			'allow-only-selected-fonts' => 'magazine_blocks_string_to_bool',
		),
		'asset-generation' => array(
			'external-file' => 'magazine_blocks_string_to_bool',
		),
		'version-control'  => array(

			'beta-tester' => 'magazine_blocks_string_to_bool',
		),
		'integrations'     => array(
			'google-maps-embed-api-key' => 'sanitize_text_field',
		),
		'maintenance-mode' => array(
			'mode'             => 'sanitize_text_field',
			'maintenance-page' => array(
				'id'    => 'absint',
				'title' => 'sanitize_text_field',
			),
		),
	);

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	public static function read() {
		self::set_default_global_styles();
		$settings   = get_option( '_magazine_blocks_settings', self::$data );
		self::$data = magazine_blocks_parse_args( $settings, self::$data );
		return self::$data;
	}

	/**
	 * Get all data.
	 *
	 * @return array
	 */
	public static function all() {
		return self::read();
	}

	/**
	 * Get setting.
	 *
	 * @param string $key
	 * @param mixed $default_value
	 * @return mixed
	 */
	public static function get( $key, $default_value = null ) {
		self::read();
		return magazine_blocks_array_get( self::$data, $key, $default_value );
	}

	/**
	 * Set multiple data.
	 *
	 * @param array $data
	 * @return void
	 */
	public static function set_data( $data ) {
		$data = magazine_blocks_array_dot( $data );
		array_walk(
			$data,
			function ( &$value, $key ) {
				$value = self::sanitize( $key, $value );
			}
		);
		$data       = magazine_blocks_array_undot( $data );
		self::$data = magazine_blocks_parse_args( $data, self::$data );
	}

	/**
	 * Set default global styles.
	 *
	 * @return void
	 */
	private static function set_default_global_styles() {
		$styles                      = array(
			'colors'       => array(
				array(
					'id'    => 'primary',
					'name'  => 'Primary',
					'value' => '#2563eb',
				),
				array(
					'id'    => 'secondary',
					'name'  => 'Secondary',
					'value' => '#54595F',
				),
				array(
					'id'    => 'text',
					'name'  => 'Text',
					'value' => '#7A7A7A',
				),
				array(
					'id'    => 'accent',
					'name'  => 'Accent',
					'value' => '#61CE70',
				),
			),
			'typographies' => array(
				array(
					'id'    => 'primary',
					'name'  => 'Primary',
					'value' => array(
						'fontFamily' => 'Default',
						'weight'     => 600,
					),
				),
				array(
					'id'    => 'secondary',
					'name'  => 'Secondary',
					'value' => array(
						'fontFamily' => 'Default',
						'weight'     => 400,
					),
				),
				array(
					'id'    => 'text',
					'name'  => 'Text',
					'value' => array(
						'fontFamily' => 'Default',
						'weight'     => 600,
					),
				),
				array(
					'id'    => 'accent',
					'name'  => 'Accent',
					'value' => array(
						'fontFamily' => 'Default',
						'weight'     => 500,
					),
				),
			),
		);
		self::$data['global-styles'] = wp_json_encode( $styles );
	}

	/**
	 * Set setting data.
	 *
	 * @param string $key Key to set.
	 * @param mixed $value Value to set.
	 * @return void
	 */
	public static function set( $key, $value ) {
		$value      = self::sanitize( $key, $value );
		self::$data = magazine_blocks_array_set( self::$data, $key, $value );
	}

	/**
	 * Sanitize value.
	 *
	 * @param string $key Key to sanitize.
	 * @param mixed $value Value to sanitize.
	 * @return mixed Sanitized value.
	 */
	private static function sanitize( $key, $value ) {
		$sanitize_callback = magazine_blocks_array_get(
			(array) apply_filters(
				'magazine_blocks_setting_sanitize_callbacks',
				self::$sanitize_callbacks
			),
			$key
		);
		if ( is_callable( $sanitize_callback ) || ( is_string( $sanitize_callback ) && function_exists( $sanitize_callback ) ) ) {
			return call_user_func_array( $sanitize_callback, array( $value ) );
		}
		return $value;
	}

	/**
	 * Save setting data after sanitization.
	 *
	 * @return void
	 */
	public static function save() {
		self::watch_responsive_breakpoints();
		update_option( '_magazine_blocks_settings', self::$data );
	}

	/**
	 * Watch responsive breakpoints.
	 *
	 * @return void
	 */
	private static function watch_responsive_breakpoints() {
		$new_breakpoints = magazine_blocks_array_get( self::$data, 'editor.responsive-breakpoints', array() );
		$old_breakpoints = wp_parse_args(
			magazine_blocks_array_get( get_option( '_magazine_blocks_settings', array() ), 'editor.responsive-breakpoints', array() ),
			magazine_blocks_array_get( self::$data, 'editor.responsive-breakpoints', array() )
		);

		ksort( $new_breakpoints );
		ksort( $old_breakpoints );

		if ( $new_breakpoints !== $old_breakpoints ) {
			do_action( 'magazine_blocks_responsive_breakpoints_changed', $new_breakpoints, $old_breakpoints );
		}
	}
}
