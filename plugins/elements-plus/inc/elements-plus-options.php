<?php

class Elements_Plus extends \Elementor\Settings {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ], 502 );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
	}

	public function add_admin_menu() {

		add_submenu_page( \Elementor\Settings::PAGE_ID, 'ElementsPlus', __( 'Elements <em>Plus!</em>', 'elements-plus' ), 'manage_options', 'elements_plus', [ $this, 'options_page' ] );
	}

	function settings_init() {

		register_setting(
			'elements_plus_settings_group',
			'elements_plus_settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' ),
				'default'           => null,
			)
		);

		add_settings_section(
			'elements_plus_settings_section',
			esc_html__( 'Theme Settings', 'elements-plus' ),
			null,
			'elements_plus_settings'
		);

		add_settings_field(
			'elements_plus_custom_setting',
			esc_html__( 'Theme Custom Setting', 'elements-plus' ),
			array( $this, 'custom_settings_html' ),
			'elements_plus_settings',
			'elements_plus_settings_section'
		);

	}

	/**
	 * Available elements.
	 */
	private static function available_elements() {

		$available_elements = array(
			'checkbox_audioigniter'        => array(
				'title'   => __( 'AudioIgniter <em>Plus!</em>', 'elements-plus' ),
				'label'   => __( 'Embed AudioIgniter playlists.', 'elements-plus' ),
				'plugin'  => 'AudioIgniter',
				/* translators: %s is a URL. */
				'warning' => sprintf( __( '<a href="%s" target="_blank">AudioIgniter</a> is not active. Install and activate the plugin to use this module.', 'elements-plus' ), 'https://wordpress.org/plugins/audioigniter/' ),
			),

			'checkbox_dual_input'          => array(
				'title' => __( 'Button <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Create buttons with two lines of text.', 'elements-plus' ),
			),

			'checkbox_caldera_forms'                 => array(
				'title'   => __( 'Caldera Forms <em>Plus!</em>', 'elements-plus' ),
				'label'   => __( 'Embed and style Caldera Forms in Elementor.', 'elements-plus' ),
				'plugin'  => 'Caldera_Forms',
				/* translators: %s is a URL. */
				'warning' => sprintf( __( '<a href="%s" target="_blank">Caldera Forms</a> is not active. Install and activate the plugin to use this module.', 'elements-plus' ), 'https://wordpress.org/plugins/caldera-forms/' ),
			),

			'checkbox_cta'                 => array(
				'title' => __( 'Call to Action <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'A CTA widget with two lines of text and a button.', 'elements-plus' ),
			),

			'checkbox_cf7'                 => array(
				'title'   => __( 'Contact Form 7 <em>Plus!</em>', 'elements-plus' ),
				'label'   => __( 'Embed and style Contact Form 7 forms in Elementor.', 'elements-plus' ),
				'plugin'  => 'WPCF7',
				/* translators: %s is a URL. */
				'warning' => sprintf( __( '<a href="%s" target="_blank">Contact Form 7</a> is not active. Install and activate the plugin to use this module.', 'elements-plus' ), 'https://wordpress.org/plugins/contact-form-7/' ),
			),

			'checkbox_content_toggle'           => array(
				'title' => __( 'Content Toggle <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Use this simple widget to create interactive content on your Elementor pages.', 'elements-plus' ),
			),

			'checkbox_countdown'           => array(
				'title' => __( 'Countdown <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'A simple yet versatile countdown widget.', 'elements-plus' ),
			),

			'checkbox_dual_button'         => array(
				'title' => __( 'Dual Button <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Display two buttons with different options for each one.', 'elements-plus' ),
			),

			'checkbox_flipclock'           => array(
				'title' => __( 'FlipClock <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'A versatile flipclock timer to add to your projects.', 'elements-plus' ),
			),

			'checkbox_heading'           => array(
				'title' => __( 'Heading <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Create flexible headings.', 'elements-plus' ),
			),

			'checkbox_justified'           => array(
				'title' => __( 'Gallery <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Gallery widget using the popular JustifiedGallery jQuery library to help you create beautiful justified galleries.', 'elements-plus' ),
			),

			'checkbox_maps'                => array(
				'title' => __( 'Google Maps <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Maps widget which allows you to use a curated list of custom styles from snazzymaps.', 'elements-plus' ),
			),

			'api_maps'                     => array(
				'title' => __( 'Google Maps API Key', 'elements-plus' ),
				/* translators: %s is a URL. */
				'label' => sprintf( __( 'Paste your Google Maps API Key below. This is <strong>required</strong> in order to get the maps widget working. For info on how to get an API key read <a href="%s" target="_blank">this article</a>.', 'elements-plus' ), 'https://www.cssigniter.com/kb/generate-a-google-maps-api-key/' ),
			),

			'checkbox_hotspots'            => array(
				'title' => __( 'Hotspots <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Add hotspots with tooltips on images. ', 'elements-plus' ),
			),

			'checkbox_icon'                => array(
				'title' => __( 'Icon <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'An icon element similar to the one bundled with Elementor, with custom icon sets.', 'elements-plus' ),
			),

			'checkbox_image_accordion'     => array(
				'title' => __( 'Image Accordion <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'A responsive image accordion widget.', 'elements-plus' ),
			),

			'checkbox_image_comparison'    => array(
				'title' => __( 'Image Comparison <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'An element which allows you to highlight the differences between two images.', 'elements-plus' ),
			),

			'checkbox_image_hover_effects' => array(
				'title' => __( 'Image Hover Effects <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'An element which allows you to switch between two images on hover with beautiful effects. Caution: These effects are WebGL based and may hurt the performance of your pages. Do not use more than 4 - 5 instances of this widget in any given page. ', 'elements-plus' ),
			),

			'checkbox_inline_svg'          => array(
				'title'   => __( 'Inline SVG <em>Plus!</em>', 'elements-plus' ),
				'label'   => __( 'Add SVG as markup instead of an HTML image tag.', 'elements-plus' ),
				'plugin'  => 'SafeSvg\safe_svg',
				/* translators: %s is a URL. */
				'warning' => sprintf( __( '<a href="%s" target="_blank">Safe SVG</a> is not active. Install and activate the plugin to use this module.', 'elements-plus' ), 'https://wordpress.org/plugins/safe-svg/' ),
			),

			'checkbox_instagram_filters'   => array(
				'title' => __( 'Instagram Filters <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'This option will enable an Instagram Filters Plus! drop-down in the default Elementor Image widget.', 'elements-plus' ),
			),

			'checkbox_label'               => array(
				'title' => __( 'Label <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Add a label above any element.', 'elements-plus' ),
			),

			'checkbox_preloader'           => array(
				'title' => __( 'Preloader <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Display a loading animation while your page loads.', 'elements-plus' ),
			),

			'checkbox_pricing_list'           => array(
				'title' => __( 'Pricing List <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Create flexible pricing lists.', 'elements-plus' ),
			),

			'checkbox_scheduled'           => array(
				'title' => __( 'Scheduled <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'With this module you can set date/time-based display restrictions on every module available. Check the "Schedule" section in the "Advanced" tab of your modules.', 'elements-plus' ),
			),

			'checkbox_search'              => array(
				'title' => __( 'Search <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Create highly configurable AJAX powered search boxes.', 'elements-plus' ),
			),

			'checkbox_sticky_videos'       => array(
				'title' => __( 'Sticky Videos <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Make videos stick to the bottom of the screen when scrolling.', 'elements-plus' ),
			),

			'checkbox_tables'              => array(
				'title' => __( 'Tables <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'This option will enable the tables element.', 'elements-plus' ),
			),

			'checkbox_tilt'                => array(
				'title' => __( 'Tilt Effect <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'This option will enable the tilt effect.', 'elements-plus' ),
			),

			'checkbox_tooltip'             => array(
				'title' => __( 'Tooltip <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'This option will enable a tooltip section in the following Elementor default widgets: Heading, Button, Icon, and Icon Box.', 'elements-plus' ),
			),

			'checkbox_wpforms'             => array(
				'title'   => __( 'WPForms <em>Plus!</em>', 'elements-plus' ),
				'label'   => __( 'Embed WPForms.', 'elements-plus' ),
				'plugin'  => 'WPForms',
				/* translators: %s is a URL. */
				'warning' => sprintf( __( '<a href="%s" target="_blank">WPForms</a> is not active. Install and activate the plugin to use this module.', 'elements-plus' ), 'https://wordpress.org/plugins/wpforms-lite/' ),
			),

			'checkbox_video_slider'        => array(
				'title' => __( 'YouTube Slideshow <em>Plus!</em>', 'elements-plus' ),
				'label' => __( 'Create a slideshow using your favorite YouTube videos.', 'elements-plus' ),
			),
		);

		return $available_elements;
	}

	/**
	 * Get the settings.
	 *
	 * @param string $option Option name.
	 *
	 * @return bool|mixed
	 */
	public static function get_setting( $option = '' ) {

		$defaults = self::get_default_settings();

		$settings = wp_parse_args( get_option( 'elements_plus_settings', $defaults ), $defaults );

		return isset( $settings[ $option ] ) ? $settings[ $option ] : false;

	}

	/**
	 * Generate default settings.
	 */
	public static function get_default_settings() {

		$panels = self::available_elements();

		$default = array();

		foreach ( $panels as $key => $val ) {
			$default[ $key ] = 0;
		}

		return apply_filters( 'elements_plus_settings_defaults', $default );

	}

	/**
	 * Sanitize data from custom settings form.
	 */
	public function sanitize( $input ) {
		$sanitized_input = array();

		if ( empty( $input ) ) {
			return $sanitized_input;
		}

		foreach ( $input as $key => $value ) {
			if ( 'api_maps' === $key ) {
				$sanitized_input[ $key ] = sanitize_text_field( $value );
			} else {
				$sanitized_input[ $key ] = intval( $value );
			}
		}

		return $sanitized_input;
	}

	/**
	 * HTML for the theme's custom settings.
	 */
	public function custom_settings_html() {
		$panels = self::available_elements();

		foreach ( $panels as $key => $value ) {

			$setting = self::get_setting( $key );
			?>
			<div class="elements-plus-setting">
				<h2>
				<?php
				echo wp_kses(
					$value['title'],
					array(
						'em' => true,
					)
				);
				?>
				</h2>
				<label for="<?php echo esc_attr( $key ); ?>">

				<?php if ( isset( $value['plugin'] ) && ! elements_plus_is_plugin_active( $value['plugin'] ) ) : ?>
					<p>
					<?php
					echo wp_kses(
						$value['warning'],
						array(
							'a' => array(
								'href'   => true,
								'target' => true,
							),
						)
					);
					?>
					</p>

				<?php elseif ( 'api_maps' === $key ) : ?>
					<p style="margin-bottom: 10px;">
						<?php
							/* translators: %s is a URL. */
							echo wp_kses(
								$value['label'],
								array(
									'a' => array(
										'href'   => true,
										'target' => true,
									),
								)
							);
						?>
					</p>
					<?php $api_key = 0 === $setting ? '' : $setting; ?>
					<input type='text' name='elements_plus_settings[api_maps]' value="<?php echo esc_attr( $api_key ); ?>">
				<?php else : ?>

					<input <?php checked( $setting, 1 ); ?> id="<?php echo esc_attr( $key ); ?>" name="elements_plus_settings[<?php echo esc_attr( $key ); ?>]" value="1" type="checkbox" />
					<?php
					echo wp_kses(
						$value['label'],
						array(
							'a' => array(
								'href'   => true,
								'target' => true,
							),
						)
					);
					?>

				<?php endif; ?>
				</label>
			</div>
			<?php
		}
	}

	/**
	 * Prints out one specified field from settings section.
	 *
	 * Based on:
	 *
	 * @see do_settings_sections
	 *
	 * as seen in https://wordpress.stackexchange.com/a/316096
	 *
	 * @global array $wp_settings_sections Storage array of all settings sections added to admin pages.
	 * @global array $wp_settings_fields   Storage array of settings fields and info about their pages/sections.
	 *
	 * @param string $page                 The slug name of the page whose settings sections you want to output.
	 * @param string $field_id             Field ID for output.
	 */
	public static function do_settings_section_field( $page, $field_id ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}

		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {

			if ( $section['callback'] ) {
				call_user_func( $section['callback'], $section );
			}

			if ( ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
				continue;
			}

			foreach ( (array) $wp_settings_fields[ $page ][ $section['id'] ] as $field ) {
				if ( $field['id'] !== $field_id ) {
					continue;
				}

				call_user_func( $field['callback'], $field['args'] );
			}
		}
	}

	public function options_page() {

		?>
		<div class="elements-plus-container">

			<div class="elements-plus-content">
				<h2 class="page-title"><?php esc_html_e( 'Elements Plus!', 'elements-plus' ); ?></h2>
				<?php settings_errors(); ?>
				<p class="page-subtitle"><?php esc_html_e( 'Use the checkboxes below to enable or disable the custom elements.', 'elements-plus' ); ?></p>

				<form action='options.php' method='post' class="elements-plus-form">
					<div class="elements-plus-settings">
						<?php
							settings_fields( 'elements_plus_settings_group' );
							$this->do_settings_section_field( 'elements_plus_settings', 'elements_plus_custom_setting' );
						?>
					</div>

					<?php submit_button(); ?>
				</form>
			</div><!-- /elements-plus-content -->
			<div class="elements-plus-sidebar">
				<a href="https://www.cssigniter.com/?utm_source=dashboard&utm_medium=banner&utm_campaign=ElementsPlus"><img
							src="<?php echo esc_url( ELEMENTS_PLUS_URL . 'assets/images/banner2.jpg' ); ?>"
							class="elements-plus-banner"/></a>
			</div>
		</div><!-- /elements-plus-container -->
		<?php

	}

}

new Elements_Plus();
