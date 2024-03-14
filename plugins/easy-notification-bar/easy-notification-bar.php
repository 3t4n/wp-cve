<?php
/**
 * Plugin Name: Easy Notification Bar
 * Plugin URI: https://wordpress.org/plugins/easy-notification-bar/
 * Description: Easily display a notice at the top of your site.
 * Author: WPExplorer
 * Author URI: https://www.wpexplorer.com/
 * Version: 1.5
 *
 * Text Domain: easy-notification-bar
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Easy_Notification_Bar Class.
 *
 * @since 1.0
 */
if ( ! class_exists( 'Easy_Notification_Bar' ) ) {

	final class Easy_Notification_Bar {

		/**
		 * @var Holds the plugin version.
		 * @since 1.4
		 */
		public $version = '1.5';

		/**
		 * @var Holds the plugin default settings.
		 * @since 1.0
		 */
		public $default_settings = array();

		/**
		 * @var Holds the plugin user based settings.
		 * @since 1.0
		 */
		public $settings = array();

		/**
		 * Easy_Notification_Bar constructor.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->constants();

			// Add settings link to plugins admin page.
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

			// Define default settings.
			$default_settings = array(
				// main settings.
				'enable'                    => true,
				'allow_collapse'            => false,
				'collapse_trigger'          => 'close_icon',
				'is_sticky'                 => false,
				'front_page_only'           => false,
				'enable_system_font_family' => true,
				'close_icon'                => 'plain',
				'message'                   => '',
				// button settings.
				'button_text'               => '',
				'button_link'               => '',
				'button_nofollow'           => false,
				'button_sponsored'          => false,
				'button_target_blank'       => false,
				// styling options.
				'padding_y'                 => '',
				'padding_x'                 => '',
				'background_color'          => '',
				'text_color'                => '',
				'text_align'                => 'center',
				'space_between'             => false,
				'font_size'                 => '',
				'button_align'              => 'right',
				'button_background_color'   => '',
				'button_text_color'         => '',
				'button_font_weight'        => '',
				'button_padding'            => '',
				'button_border_radius'      => '',
			);

			/**
			 * Filters the default notification bar settings.
			 *
			 * @param array $settings
			 * @param object $this Current class object.
			 */
			$default_settings = (array) apply_filters( 'easy_notification_bar_default_settings', $default_settings, $this );

			// Update class default_settings var.
			$this->default_settings = $default_settings;

			// Add notification to the site.
			add_action( 'wp', array( $this, 'add_notification' ) );

			// Add body class if notification bar is enabled.
			add_filter( 'body_class', array( $this, 'add_body_class' ) );

			// Register Customizer settings.
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_register', array( $this, 'customizer_partial_refresh' ) );
			add_action( 'customize_save', array( $this, 'customize_save' ) );
			add_action( 'customize_save_after', array( $this, 'customize_save_after' ) );
		}

		/**
		 * Define plugin constants.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function constants() {
			define( 'ENB_MAIN_FILE_PATH', __FILE__ );
			define( 'ENB_PLUGIN_DIR_PATH', plugin_dir_path( ENB_MAIN_FILE_PATH ) );
		}

		/**
		 * Add settings link to plugins admin page.
		 *
		 * @since  1.0
		 * @access public
		 * @return array | $links
		 */
		public function plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . esc_url( admin_url( '/customize.php?autofocus[section]=easy_nb' ) ) . '">' . esc_html__( 'Settings', 'easy-notification-bar' ) . '</a>',
			);
			return array_merge( $plugin_links, $links );
		}

		/**
		 * Get plugin settings.
		 *
		 * @since  1.0
		 * @access public
		 * @return $settings | array
		 */
		public function get_settings() {
			if ( ! empty( $this->settings ) && ! is_customize_preview() ) {
				return $this->settings;
			}

			/**
			 * Filters the notification bar settings.
			 *
			 * @param array $settings
			 * @param object $this Current class object.
			 */
			$this->settings = (array) apply_filters( 'easy_notification_bar_settings', get_theme_mod( 'easy_nb' ), $this );

			$this->settings = wp_parse_args( $this->settings, $this->default_settings );

			return $this->settings;
		}

		/**
		 * Get plugin setting.
		 *
		 * @since  1.0
		 * @access public
		 * @return $settings | array
		 */
		public function get_setting( $name, $fallback = false ) {
			$this->get_settings();
			if ( isset( $this->settings[ $name ] ) ) {
				return $this->settings[ $name ];
			}
			if ( $fallback ) {
				return $this->defaults[ $name ];
			}
		}

		/**
		 * Add notification to the site.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function add_notification() {
			$this->get_settings();

			if ( is_customize_preview() || $this->is_enabled() ) {

				/**
				 * Filters the default hook where the notification bar is inserted.
				 *
				 * @param string $hook
				 * @param obj $this Current object class.
				 */
				$hook_name = (string) apply_filters( 'easy_notification_bar_hook_name', 'wp_body_open', $this );

				/**
				 * Filters the hook priority for the add_action functions used to display the notification.
				 *
				 * @param int $priority.
				 * @param obj $this Current object class.
				 */
				$hook_priority = (int) apply_filters( 'easy_notification_bar_hook_priority', 10, $this );

				// Apply filters to the notification bar message for sanitization.
				add_filter( 'easy_notification_bar_message', 'wp_kses_post'      );
				add_filter( 'easy_notification_bar_message', 'shortcode_unautop' );
				add_filter( 'easy_notification_bar_message', 'do_shortcode', 11  );

				// Display Notification Bar.
				add_action( $hook_name, array( $this, 'display_notification' ), $hook_priority );

				// Add Support for AMP Leagacy mode theme.
				if ( $this->is_amp_legacy() ) {

					// Add Notification to AMP leagacy mode.
					add_action( 'amp_post_template_body_open', array( $this, 'display_notification' ), $hook_priority );

					// Add inline CSS.
					add_action( 'amp_post_template_css', array( $this, 'amp_reader_mode_leagacy_theme' ) );

				}

				// Enqueue Notification Scripts.
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			}

		}

		/**
		 * Add body class if notification bar is enabled.
		 *
		 * @since  1.2
		 * @access public
		 * @return array $class
		 */
		public function add_body_class( $class ) {
			if ( $this->is_enabled() ) {
				$class[] = 'has-easy-notification-bar';
			}

			return $class;
		}

		/**
		 * Check if the notification bar is enabled.
		 *
		 * @since  1.0
		 * @access public
		 * @return $enabled | bool
		 */
		public function is_enabled() {
			$enabled = wp_validate_boolean( $this->get_setting( 'enable' ) );

			if ( $this->get_setting( 'front_page_only' ) && ! is_front_page() ) {
				$enabled = false;
			}

			/**
			 * Filters whether the notification bar is enabled or not.
			 *
			 * @param bool $enabled
			 * @param object $this Current class object.
			 */
			$enabled = (bool) apply_filters( 'easy_notification_bar_is_enabled', $enabled, $this );

			return (bool) $enabled;
		}

		/**
		 * Display Notification Bar.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function display_notification() {
			$is_customize_preview = is_customize_preview();

			if ( $is_customize_preview ) {

				echo '<div class="easy-notification-bar-customize-selector">';

				// Inline style used for partialRefresh only. See enqueue_scripts for front-end CSS output.
				if ( $inline_css = $this->inline_css() ) {
					echo '<style>' . $inline_css . '</style>';
				}

			}

			if ( $this->is_enabled() ) {
				$this->render_notification();
			}

			if ( $is_customize_preview ) {
				echo '</div>';
			}
		}

		/**
		 * Renders the notification content.
		 *
		 * @since 1.5
		 * @access private
		 * @return void
		 */
		private function render_notification(): void {
			$message          = $this->get_setting( 'message' );
			$button_link      = $this->get_setting( 'button_link' );
			$button_text      = $this->get_setting( 'button_text' );
			$collapse_trigger = $this->get_setting( 'collapse_trigger', true );

			if ( ! $button_link && in_array( $collapse_trigger , array( 'button', 'any' ) ) ) {
				$button_link = '#';
			}

			if ( ! $message && ! $this->has_button() ) {
				return;
			}

			?>
			<div class="<?php echo esc_attr( join( ' ', $this->get_wrap_class() ) ); ?>">
				<div class="<?php echo esc_attr( join( ' ', $this->get_container_class() ) ); ?>">
					<?php
					// Display Message
					if ( $message ) { ?>
						<div class="easy-notification-bar-message"><?php
							echo wp_kses_post( apply_filters( 'easy_notification_bar_message', $message, $this ) );
						?></div>
					<?php } ?>
					<?php
					// Display button
					if ( $button_link ) { ?>
						<div class="easy-notification-bar-button">
							<a class="easy-notification-bar-button__link" href="<?php echo esc_url( $button_link ); ?>"<?php $this->button_rel() . $this->button_target_blank(); ?><?php echo ( in_array( $collapse_trigger , array( 'button', 'any' ) ) && ! $this->is_amp() ) ? ' data-easy-notification-bar-close' : ''; ?>><?php echo wp_kses_post( $button_text ); ?></a>
						</div>
					<?php } ?>
				</div>
				<?php if ( in_array( $collapse_trigger , array( 'close_icon', 'any' ) ) && true === $this->get_setting( 'allow_collapse' ) && ! $this->is_amp() ) { ?>
					<a class="easy-notification-bar__close" href="#" aria-label="<?php esc_html_e( 'Close notification', 'easy-notification-bar' ); ?>" data-easy-notification-bar-close><?php $this->close_icon(); ?></a>
				<?php } ?>
			</div>
			<?php
		}

		/**
		 * Check if the notice button is enabled.
		 *
		 * @since 1.5
		 * @access private
		 * @return boolean
		 */
		private function has_button(): bool {
			return $this->get_setting( 'button_text' ) && ( $this->get_setting( 'button_link' ) || 'button' === $this->get_setting( 'collapse_trigger', true ));
		}

		/**
		 * Check if AMP endpoint.
		 *
		 * @since  1.4.5
		 * @access public
		 * @return boolean
		 */
		public function is_amp(): bool {
			$check = false;

			if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
				$check = true;
			}

			/**
			 * Filters whether the current page is an amp page
			 *
			 * @param bool $check
			 */
			$check = apply_filters( 'easy_notification_bar_is_amp', $check );

			return (bool) $check;
		}

		/**
		 * Check if AMP endpoint.
		 *
		 * @since  1.4.5
		 * @access public
		 * @return boolean
		 */
		public function is_amp_legacy() {
			$check = false;

			if ( $this->is_amp() && function_exists( 'amp_is_legacy' ) && amp_is_legacy() ) {
				$check = true;
			}

			/**
			 * Filters whether the current page is an amp page
			 *
			 * @param bool $check
			 */
			$check = (bool) apply_filters( 'easy_notification_bar_is_amp', $check );

			return $check;
		}

		/**
		 * Adds Notification bar CSS to AMP Legacy theme.
		 *
		 * @since  1.3
		 * @access public
		 * @return void
		 */
		public function amp_reader_mode_leagacy_theme() {
			$easy_notification_bar_css = file_get_contents( __DIR__ . '/assets/css/easy-notification-bar.css' );
			echo $easy_notification_bar_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$inline_css = $this->inline_css();
			if ( ! empty( $inline_css ) ) {
				echo $inline_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Get wrap class.
		 *
		 * @since  1.4.2
		 * @access public
		 * @return $classes | array
		 */
		public function get_wrap_class(): array {
			$is_customize_preview = is_customize_preview();

			$class = array(
				'easy-notification-bar'
			);

			if ( $align = $this->get_setting( 'text_align' ) ) {
				$class[] = 'easy-notification-bar--align_' . sanitize_text_field( $align );
			};

			if ( $this->has_button() && $button_align = $this->get_setting( 'button_align', true ) ) {
				$class[] = 'easy-notification-bar--button_' . sanitize_text_field( $button_align );
			}

			if ( isset( $align )
				&& in_array( $align, array( 'left', 'right' ) )
				&& wp_validate_boolean( $this->get_setting( 'space_between', true ) )
			) {
				$class[] = 'easy-notification-bar--space_between';
			}

			if ( true === $this->get_setting( 'allow_collapse' ) && ! $this->is_amp() ) {
				if ( ! $is_customize_preview ) {
					$class[] = 'easy-notification-bar--hidden';
				}
				$class[] = 'easy-notification-bar--collapsible';
				if ( in_array( $this->get_setting( 'collapse_trigger', true ) , array( 'close_icon', 'any' ) ) ) {
					$class[] = 'easy-notification-bar--has_close_icon';
				}
			}

			if ( true === $this->get_setting( 'is_sticky' ) && ! $is_customize_preview ) {
				$class[] = 'easy-notification-bar--sticky'; // sticky doesn't work in the customizer.
			}

			/**
			 * Filters the wrap classes added to the easy-notification-bar element.
			 *
			 * @param array $class
			 * @param object $this
			 */
			$class = (array) apply_filters( 'easy_notification_bar_wrap_class', $class, $this );

			return $class;
		}

		/**
		 * Get container class.
		 *
		 * @since  1.0
		 * @access public
		 * @return $class | array
		 */
		public function get_container_class(): array {
			$class = array(
				'easy-notification-bar-container',
			);

			if ( ! empty( $this->settings['enable_system_font_family'] ) ) {
				$class[] =  'enb-system-font';
			}

			/**
			 * Filters the easy-notification-bar-container element classes.
			 *
			 * @param array $class
			 * @param object $this Current class object.
			 */
			$class = (array) apply_filters( 'easy_notification_bar_container_class', $class, $this );

			return $class;
		}

		/**
		 * Enqueue Notification Scripts.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function enqueue_scripts() {
			$is_customize_preview = is_customize_preview();

			if ( $is_customize_preview ) {
				wp_enqueue_style(
					'easy-notification-bar-customize',
					plugins_url( '/assets/css/customize.css', ENB_MAIN_FILE_PATH ),
					array(),
					$this->version
				);
			}

			/**
			 * Filters whether the plugin should load it's CSS files or not.
			 *
			 * @param bool $check
			 * @param object $this Current class object.
			 */
			$enqueue_css_check = (bool) apply_filters( 'easy_notification_bar_enqueue_css', true, $this );

			if ( $enqueue_css_check ) {

				wp_enqueue_style(
					'easy-notification-bar',
					plugins_url( '/assets/css/front.css', ENB_MAIN_FILE_PATH ),
					array(),
					$this->version
				);

				if ( ! $is_customize_preview && $inline_css = $this->inline_css() ) {
					wp_add_inline_style( 'easy-notification-bar', $inline_css );
				}

			}

			if ( true === $this->get_setting( 'allow_collapse' ) && ! $this->is_amp() ) {

				wp_enqueue_script(
					'easy-notification-bar',
					plugins_url( '/assets/js/front.js', ENB_MAIN_FILE_PATH ),
					array(),
					$this->version,
					false
				);

				$ls_keyname = 'easy_notification_bar_is_hidden';

				$ls_keyname_refresh_timestamp = get_option( 'easy_nb_refresh_timestamp', null );

				if ( $ls_keyname_refresh_timestamp ) {
					$ls_keyname = $ls_keyname . '_' . $ls_keyname_refresh_timestamp;
				}

				wp_localize_script(
					'easy-notification-bar',
					'easyNotificationBar',
					array(
						'local_storage_keyname' => wp_strip_all_tags( $ls_keyname ),
					)
				);

			}
		}

		/**
		 * Return notification bar CSS.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function inline_css() {
			$this->get_settings();

			$all_css = '';

			// Main styles
			$main_css = '';

			if ( $background_color = $this->get_setting( 'background_color' ) ) {
				$main_css .= 'background:' . sanitize_hex_color( $background_color ) . ';';
			}

			if ( $text_color = $this->get_setting( 'text_color' ) ) {
				$main_css .= 'color:' . sanitize_hex_color( $text_color ) . ';';
			}

			if ( $font_size = $this->get_setting( 'font_size' ) ) {
				$font_size_escaped = is_numeric( $font_size ) ? absint( $font_size ) . 'px' : esc_attr( $font_size );
				$main_css .= 'font-size:' . $font_size_escaped . ';';
			}

			if ( $padding_y = $this->get_setting( 'padding_y' ) ) {
				$padding_y_escaped = is_numeric( $padding_y ) ? absint( $padding_y ) . 'px' : esc_attr( $padding_y );
				$main_css .= '--enb-padding-y:' . $padding_y_escaped . ';';
			}

			if ( $padding_x = $this->get_setting( 'padding_x' ) ) {
				$padding_x_escaped = is_numeric( $padding_x ) ? absint( $padding_x ) . 'px' : esc_attr( $padding_x );
				$main_css .= '--enb-padding-x:' . $padding_x_escaped . ';';
			}

			if ( $main_css ) {
				$all_css .= '.easy-notification-bar{' . $main_css . '}';
			}

			// Button styles
			$button_css = '';

			if ( $button_background_color = $this->get_setting( 'button_background_color' ) ) {
				$button_css .= 'background:' . sanitize_hex_color( $button_background_color ) . ';';
			}

			if ( $button_text_color = $this->get_setting( 'button_text_color' ) ) {
				$button_css .= 'color:' . sanitize_hex_color( $button_text_color ) . ';';
			}

			if ( $button_padding = $this->get_setting( 'button_padding' ) ) {
				$button_css .= 'padding:' . esc_attr( $button_padding ) . ';';
			}

			if ( $button_font_weight = $this->get_setting( 'button_font_weight' ) ) {
				$button_css .= 'font-weight:' . esc_attr( absint( $button_font_weight ) ) . ';';
			}

			if ( $button_border_radius = $this->get_setting( 'button_border_radius' ) ) {
				switch ( $button_border_radius ) {
					case 'sm':
						$button_border_radius_val = 'var(--wpex-rounded-sm, 0.125em)';
						break;
					case 'sm':
						$button_border_radius_val = 'var(--wpex-rounded, 0.25em)';
						break;
					case 'md':
						$button_border_radius_val = 'var(--wpex-rounded-md, 0.375em)';
						break;
					case 'lg':
						$button_border_radius_val = 'var(--wpex-rounded-lg, 0.5em)';
						break;
					case 'full':
						$button_border_radius_val = 'var(--wpex-rounded-full, 9999px)';
						break;
				}
				if ( isset( $button_border_radius_val ) ) {
					$button_css .= 'border-radius:' . esc_attr( $button_border_radius_val ) . ';';
				}
			}

			if ( $button_css ) {
				$all_css .= '.easy-notification-bar-button :is(a,a:hover,a:visited,a:focus) {' . $button_css . '}';
			}

			return $all_css;
		}

		/**
		 * Register Customizer settings.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function customize_register( $wp_customize ) {
			$wp_customize->add_section( 'easy_nb', array(
				'title'    => esc_html__( 'Easy Notification Bar', 'easy-notification-bar' ),
				'priority' => 1,
			) );

			/* Enable Notification Bar */
			$wp_customize->add_setting( 'easy_nb[enable]', array(
				'default'           => $this->default_settings['enable'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			) );

			$wp_customize->add_control( 'easy_nb_enable', array(
				'label'       => esc_html__( 'Enable Notification Bar', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[enable]',
				'type'        => 'checkbox',
				'description' => esc_html__( 'Note: If you do not see the bar on your site your theme has not been updated to include the "wp_body_open" action hook required since WordPress 5.2.0.', 'easy-notification-bar' ),
			) );

			/* Close/Collapse */
			$wp_customize->add_setting( 'easy_nb[allow_collapse]', array(
				'default'           => $this->default_settings['allow_collapse'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			) );

			$wp_customize->add_control( 'easy_nb_allow_collapse', array(
				'label'       => esc_html__( 'Can be closed?', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[allow_collapse]',
				'type'        => 'checkbox',
				'description' => esc_html__( 'Makes use of localStorage (not cookies) so when a user clicks to hide the notifcation bar they will not see it again until they clear their browser cache.', 'easy-notification-bar' ),
			) );

			/* close Type */
			$wp_customize->add_setting( 'easy_nb[collapse_trigger]', array(
				'default'           => $this->default_settings['collapse_trigger'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			) );

			$wp_customize->add_control( 'easy_nb_collapse_trigger', array(
				'label'          => esc_html__( 'Close Action', 'easy-notification-bar' ),
				'description' => esc_html__( 'Select what action(s) triggers closing of the notification bar.', 'easy-notification-bar' ),
				'section'        => 'easy_nb',
				'settings'       => 'easy_nb[collapse_trigger]',
				'type'           => 'select',
				'choices'        => [
					'close_icon' => esc_html__( 'Close Icon Click', 'easy-notification-bar' ),
					'button'     => esc_html__( 'Button Click', 'easy-notification-bar' ),
					'any'        => esc_html__( 'Close Icon or Button Click', 'easy-notification-bar' ),
				],
				'active_callback' => function() {
					return (bool) wp_validate_boolean( $this->get_setting( 'allow_collapse' ) );
				},
			) );

			/* Close Icon */
			$wp_customize->add_setting( 'easy_nb[close_icon]', array(
				'default'           => $this->default_settings['close_icon'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			) );

			$wp_customize->add_control( 'easy_nb_close_icon', array(
				'label'    => esc_html__( 'Close Icon', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[close_icon]',
				'type'     => 'radio',
				'choices'  => array(
					'plain'   => esc_html__( 'Plain', 'easy-notification-bar' ),
					'outline' => esc_html__( 'Outline', 'easy-notification-bar' ),
				),
				'active_callback' => function() {
					return wp_validate_boolean( $this->get_setting( 'allow_collapse' ) ) && in_array( $this->get_setting( 'collapse_trigger' ), array( 'close_icon', 'any' ) );
				},
			) );

			/* Sticky */
			$wp_customize->add_setting( 'easy_nb[is_sticky]', array(
				'default'           => $this->default_settings['is_sticky'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_is_sticky', array(
				'label'       => esc_html__( 'Enable Sticky?', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[is_sticky]',
				'type'        => 'checkbox',
				'description' => esc_html__( 'This option uses the modern "sticky" CSS position so it will only work in modern browsers and could cause conflicts with your theme\'s build in sticky functions so be sure to test accordingly and include the proper offsets.', 'easy-notification-bar' ),
			) );

			/* Homepage Only */
			$wp_customize->add_setting( 'easy_nb[front_page_only]', array(
				'default'           => $this->default_settings['front_page_only'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			) );

			$wp_customize->add_control( 'easy_nb_front_page_only', array(
				'label'    => esc_html__( 'Display on Front Page Only?', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[front_page_only]',
				'type'     => 'checkbox',
			) );

			/* Notification Message */
			$wp_customize->add_setting( 'easy_nb[message]', array(
				'default'           => $this->default_settings['message'],
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_message', array(
				'label'       => esc_html__( 'Message', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[message]',
				'type'        => 'textarea',
				'description' => esc_html__( 'Note: Whenever you alter your message anyone that had previously closed the notice bar will see it again.', 'easy-notification-bar' ),
			) );

			/* Notification Background */
			$wp_customize->add_setting( 'easy_nb[background_color]', array(
				'default'           => $this->default_settings['background_color'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'easy_nb_background_color', array(
				'label'    => esc_html__( 'Background', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[background_color]',
				'type'     => 'color',
			) ) );

			/* Notification Color */
			$wp_customize->add_setting( 'easy_nb[text_color]', array(
				'default'           => $this->default_settings['text_color'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'easy_nb_text_color', array(
				'label'    => esc_html__( 'Text Color', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[text_color]',
				'type'     => 'color',
			) ) );

			/* Alignment */
			$wp_customize->add_setting( 'easy_nb[text_align]', array(
				'default'           => $this->default_settings['text_align'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_text_align', array(
				'label'    => esc_html__( 'Alignment', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[text_align]',
				'type'     => 'radio',
				'choices'    => array(
					'left'   => esc_html__( 'Left', 'easy-notification-bar' ),
					'center' => esc_html__( 'Center', 'easy-notification-bar' ),
					'right'  => esc_html__( 'Right', 'easy-notification-bar' ),
				),
			) );

			/* Justify Content */
			$wp_customize->add_setting( 'easy_nb[space_between]', array(
				'default'           => $this->default_settings['space_between'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_space_between', array(
				'label'           => esc_html__( 'Displace Buton?', 'easy-notification-bar' ),
				'description'     => esc_html__( 'Enable to align the text and buttons at opposite ends (for left and right alignments only).', 'easy-notification-bar' ),
				'section'         => 'easy_nb',
				'settings'        => 'easy_nb[space_between]',
				'type'            => 'checkbox'
			) );

			/* Enable System Fonts */
			$wp_customize->add_setting( 'easy_nb[enable_system_font_family]', array(
				'default'           => $this->default_settings['enable_system_font_family'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_enable_system_font_family', array(
				'label'       => esc_html__( 'Apply System Font Family?', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[enable_system_font_family]',
				'type'        => 'checkbox',
				'description' => esc_html__( 'Use the common system UI font stack font family for your notification bar. If disabled it will inherit the font family from your theme.', 'easy-notification-bar' ),
			) );

			/* Vertical Padding */
			$wp_customize->add_setting( 'easy_nb[padding_y]', array(
				'default'           => $this->default_settings['padding_y'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_padding_y', array(
				'label'       => esc_html__( 'Vertical Padding', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[padding_y]',
				'type'        => 'text',
				'description' => esc_html__( 'If a unit is not specified "px" will be used.', 'easy-notification-bar' ),
			) );

			/* Horizontal Padding */
			$wp_customize->add_setting( 'easy_nb[padding_x]', array(
				'default'           => $this->default_settings['padding_x'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_padding_x', array(
				'label'       => esc_html__( 'Horizontal Padding', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[padding_x]',
				'type'        => 'text',
				'description' => esc_html__( 'If a unit is not specified "px" will be used.', 'easy-notification-bar' ),
			) );

			/* Font Size */
			$wp_customize->add_setting( 'easy_nb[font_size]', array(
				'default'           => $this->default_settings['font_size'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_font_size', array(
				'label'       => esc_html__( 'Font Size', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[font_size]',
				'type'        => 'text',
				'description' => esc_html__( 'If a unit is not specified "px" will be used.', 'easy-notification-bar' ),
			) );

			/* Notification Button Text */
			$wp_customize->add_setting( 'easy_nb[button_text]', array(
				'default'           => $this->default_settings['button_text'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_text', array(
				'label'    => esc_html__( 'Button Text', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_text]',
				'type'     => 'text',
			) );

			/* Notification Button Link */
			$wp_customize->add_setting( 'easy_nb[button_link]', array(
				'default'           => $this->default_settings['button_link'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_link', array(
				'label'       => esc_html__( 'Button Link', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[button_link]',
				'type'        => 'text',
				'description' => esc_html__( 'Leave Empty to disable.', 'easy-notification-bar' ),
			) );

			/* Notification Button Nofollow */
			$wp_customize->add_setting( 'easy_nb[button_nofollow]', array(
				'default'           => $this->default_settings['button_nofollow'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_nofollow', array(
				'label'    => esc_html__( 'Add rel="nofollow" to button?', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_nofollow]',
				'type'     => 'checkbox',
			) );

			/* Notification Button Sponsored */
			$wp_customize->add_setting( 'easy_nb[button_sponsored]', array(
				'default'           => $this->default_settings['button_sponsored'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_sponsored', array(
				'label'    => esc_html__( 'Add rel="sponsored" to button?', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_sponsored]',
				'type'     => 'checkbox',
			) );

			/* Notification Button Nofollow */
			$wp_customize->add_setting( 'easy_nb[button_target_blank]', array(
				'default'           => $this->default_settings['button_target_blank'],
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_target_blank', array(
				'label'    => esc_html__( 'Open button link in new tab?', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_target_blank]',
				'type'     => 'checkbox',
			) );

			/* Notification Button Align */
			$wp_customize->add_setting( 'easy_nb[button_align]', array(
				'default'           => $this->default_settings['button_align'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_align', array(
				'label'    => esc_html__( 'Button Placement', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_align]',
				'type'     => 'radio',
				'choices'    => array(
					'left'   => esc_html__( 'Left', 'easy-notification-bar' ),
					'right'  => esc_html__( 'Right', 'easy-notification-bar' ),
					'bottom' => esc_html__( 'Below Text', 'easy-notification-bar' ),
				),
			) );

			/* Notification Button Background */
			$wp_customize->add_setting( 'easy_nb[button_background_color]', array(
				'default'           => $this->default_settings['button_background_color'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'easy_nb_button_background_color', array(
				'label'    => esc_html__( 'Button Background', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_background_color]',
				'type'     => 'color',
			) ) );

			/* Notification Button Color */
			$wp_customize->add_setting( 'easy_nb[button_text_color]', array(
				'default'           => $this->default_settings['button_text_color'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'easy_nb_button_text_color', array(
				'label'    => esc_html__( 'Button Text Color', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_text_color]',
				'type'     => 'color',
			) ) );

			/* Notification Button Font Weight */
			$wp_customize->add_setting( 'easy_nb[button_font_weight]', array(
				'default'           => $this->default_settings['button_font_weight'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_font_weight', array(
				'label'       => esc_html__( 'Button Font Weight', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[button_font_weight]',
				'type'        => 'select',
				'choices'     => [
					'' => esc_html__( 'Default', 'easy-notification-bar' ),
					'300' => esc_html__( 'Light', 'easy-notification-bar' ),
					'400' => esc_html__( 'Normal', 'easy-notification-bar' ),
					'500' => esc_html__( 'Medium', 'easy-notification-bar' ),
					'600' => esc_html__( 'Semibold', 'easy-notification-bar' ),
					'700' => esc_html__( 'Bold', 'easy-notification-bar' ),
					'800' => esc_html__( 'Extra Bold', 'easy-notification-bar' ),
				],
			) );

			/* Notification Button Border Radius */
			$wp_customize->add_setting( 'easy_nb[button_border_radius]', array(
				'default'           => $this->default_settings['button_padding'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_border_radius', array(
				'label'    => esc_html__( 'Button Border Radius', 'easy-notification-bar' ),
				'section'  => 'easy_nb',
				'settings' => 'easy_nb[button_border_radius]',
				'type'     => 'select',
				'choices'  => [
					''     => esc_html__( 'None', 'easy-notification-bar' ),
					'sm'   => esc_html__( 'Small', 'easy-notification-bar' ),
					'md'   => esc_html__( 'Medium', 'easy-notification-bar' ),
					'lg'   => esc_html__( 'Large', 'easy-notification-bar' ),
					'full' => esc_html__( 'Full', 'easy-notification-bar' ),
				],
			) );

			/* Notification Button Padding */
			$wp_customize->add_setting( 'easy_nb[button_padding]', array(
				'default'           => $this->default_settings['button_padding'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			) );

			$wp_customize->add_control( 'easy_nb_button_padding', array(
				'label'       => esc_html__( 'Button Padding', 'easy-notification-bar' ),
				'description' => esc_html__( 'Enter a custom padding for your button. Example: 10px. You can use the CSS shorthand format as well such as 10px 20px.', 'easy-notification-bar' ),
				'section'     => 'easy_nb',
				'settings'    => 'easy_nb[button_padding]',
				'type'        => 'text',
			) );
		}

		/**
		 * Add Customizer Partial Refresh.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function customizer_partial_refresh( $wp_customize ) {
			if ( ! isset( $wp_customize->selective_refresh ) ) {
				return;
			}

			$wp_customize->selective_refresh->add_partial( 'easy_nb[message]', array(
				'selector'            => '.easy-notification-bar-customize-selector',
				'settings'            => array(
					'easy_nb[message]',
					'easy_nb[enable_system_font_family]',
					'easy_nb[button_link]',
					'easy_nb[button_text]',
					'easy_nb[button_nofollow]',
					'easy_nb[button_sponsored]',
					'easy_nb[button_target_blank]',
					'easy_nb[is_sticky]',
					'easy_nb[close_icon]',
					'easy_nb[text_align]',
					'easy_nb[space_between]',
					'easy_nb[button_align]',
					'easy_nb[background_color]',
					'easy_nb[text_color]',
					'easy_nb[padding_y]',
					'easy_nb[padding_x]',
					'easy_nb[font_size]',
					'easy_nb[button_background_color]',
					'easy_nb[button_text_color]',
					'easy_nb[button_padding]',
					'easy_nb[button_border_radius]',
					'easy_nb[button_font_weight]',
				),
				'primarySetting'      => 'easy_nb[message]',
				'container_inclusive' => true,
				'fallback_refresh'    => true,
				'render_callback'     => array( $this, 'display_notification' ),
			) );
		}

		/**
		 * Runs on the customizer_save hook.
		 *
		 * @since  1.4.4
		 * @access public
		 * @return void
		 */
		public function customize_save( $wp_customize ) {
			global $easy_nb_current_message;
			$easy_nb_current_message = $this->get_setting( 'message' );
		}

		/**
		 * Runs on the customizer_save_after hook.
		 *
		 * @since  1.4.4
		 * @access public
		 * @return void
		 */
		public function customize_save_after( $wp_customize ) {
			global $easy_nb_current_message;

			if ( ! $easy_nb_current_message ) {
				return;
			}

			if ( $easy_nb_current_message !== $this->get_setting( 'message' ) ) {
				update_option( 'easy_nb_refresh_timestamp', current_time( 'timestamp' ) );
			}

			unset( $easy_nb_current_message );
		}

		/**
		 * Echos rel="nofollow" tag for button if enabled.
		 *
		 * @since  1.0
		 * @access public
		 */
		public function button_rel() {
			$rel = array();

			if ( wp_validate_boolean( $this->get_setting( 'button_nofollow' ) ) ) {
				if ( wp_validate_boolean( $this->get_setting( 'button_target_blank' ) ) ) {
					$rel[] = 'nofollow';
					$rel[] = 'noopener';
				} else {
					$rel[] = 'nofollow';
				}
			}

			if ( wp_validate_boolean( $this->get_setting( 'button_sponsored' ) ) ) {
				$rel[] = 'sponsored';
			}

			if ( ! empty( $rel ) && is_array( $rel ) ) {
				$rel = (string) apply_filters( 'easy_notification_bar_button_rel', implode( ' ' , $rel ) );
				echo ' rel="' . esc_attr( $rel ) . '"';
			}
		}

		/**
		 * Echos target="blank" tag for button if enabled.
		 *
		 * @since  1.0
		 * @access public
		 */
		public function button_target_blank() {
			if ( wp_validate_boolean( $this->get_setting( 'button_target_blank' ) ) ) {
				if ( wp_validate_boolean( $this->get_setting( 'button_nofollow' ) ) ) {
					echo ' target="_blank"';
				} else {
					echo ' rel="noreferrer" target="_blank"';
				}
			}
		}

		/**
		 * Display the close icon.
		 *
		 * @since  1.4
		 * @access public
		 */
		public function close_icon() {
			$icon_style = $this->get_setting( 'close_icon', true );

			switch ( $icon_style ) {
				case 'outline':
					$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none" opacity=".87"/><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.59-13L12 10.59 8.41 7 7 8.41 10.59 12 7 15.59 8.41 17 12 13.41 15.59 17 17 15.59 13.41 12 17 8.41z"/></svg>';
					break;
				case 'plain':
				default:
					$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>';
					break;
			}

			$icon_size = 24;

			$icon = str_replace( '<svg', '<svg width="' . absint( $icon_size ) .'px" height="' . absint( $icon_size ) .'px"', $icon );

			echo (string) apply_filters( 'easy_notification_bar_close_icon', $icon );
		}

	}

	new Easy_Notification_Bar;
}
