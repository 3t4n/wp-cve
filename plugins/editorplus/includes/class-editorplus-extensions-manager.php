<?php
/**
 * Main file for extension manager.
 *
 * @package EditorPlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'extensions/icon-inserter/index.php';


/**
 * Main editorplus extension manager
 */
class EditorPlus_Extensions_Manager {

	const GROUP = 'editor_plus_extensions';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->extensions = array(
			'styling'           => array(
				'title'       => __( 'Styling - NoCode Visual Styler', 'editor_plus' ),
				'description' => __( 'This extension extends Gutenberg bocks with powerful visual style options.', 'editor_plus' ),
			),
			'custom_block_code' => array(
				'title'          => __( 'CSS Editor', 'editor_plus' ),
				'description'    => __( 'This extension extends Gutenberg blocks with custom CSS editor and also enable a Global CSS editor in the admin.', 'editor_plus' ),
				'extra_settings' => array( 'ep_custom_global_css', 'ep_custom_global_js', 'ep_generate_static_file', 'ep_custom_global_css_version', 'ep_custom_global_styles_position' ),
			),

			'blocks_extender'   => array(
				'title'       => __( 'Extend Core Blocks', 'editor_plus' ),
				'description' => __( 'This extension core blocks with more useful features besides the styling.', 'editor_plus' ),
			),
			'icon_inserter'     => array(
				'title'       => __( 'Icons Library', 'editor_plus' ),
				'description' => __( 'This extension extends enables you to insert icons in Gutenberg anywhere in RichText area.', 'editor_plus' ),
			),
			'animation_builder' => array(
				'title'       => __( 'Animations', 'editor_plus' ),
				'description' => __( 'The Animations extension lets you animate your content in Gutenberg. If enabled you will see Animations panel on all core blocks.', 'editor_plus' ),
			),
		);

		// exposing this list to be extended by pro plugin.
		$this->extensions = apply_filters( 'editor_plus_extensions', $this->extensions );

	}

	/**
	 * All settings should be registered here
	 *
	 * @return void
	 */
	public function register_settings() {
		foreach ( $this->extensions as $name => $data ) {

			$slug        = self::GROUP . '_' . $name;
			$enable_slug = self::GROUP . '_' . $name . '__enable';

			register_setting(
				self::GROUP,
				$enable_slug,
				array(
					'type'         => 'boolean',
					'show_in_rest' => true,
					'default'      => true,
				)
			);

			if ( array_key_exists( 'extra_settings', $data ) && ! empty( $data['extra_settings'] ) ) {

				$extra_settings = $data['extra_settings'];

				foreach ( $extra_settings as $extra_setting ) {

					register_setting(
						self::GROUP,
						$extra_setting,
						array(
							'type'         => 'string',
							'show_in_rest' => true,
							'default'      => '',
						)
					);

				}
			}
		}

	}

	/**
	 * Get the all the registered settings with status by the extension manager
	 *
	 * @return stdClass - Settings.
	 */
	public function get_settings() {

		foreach ( $this->extensions as $name => $data ) {

			$slug        = self::GROUP . '_' . $name;
			$enable_slug = self::GROUP . '_' . $name . '__enable';

			$this->extensions[ $name ]['enabled'] = get_option( $enable_slug );

			if ( array_key_exists( 'extra_settings', $data ) && ! empty( $data['extra_settings'] ) ) {

				$extra_settings = $data['extra_settings'];

				foreach ( $extra_settings as $extra_setting ) {

					if ( 'ep_custom_global_js' === $extra_setting ) {

						$this->extensions[ $name ]['extra_setting_values'][ $extra_setting ] = base64_decode( urldecode( get_option( $extra_setting ) ) );
					} else {
						$this->extensions[ $name ]['extra_setting_values'][ $extra_setting ] = get_option( $extra_setting );
					}
				}
			}
		}

		$settings = new stdClass();

		$settings->extensions = $this->extensions;

		return $settings;
	}
}
