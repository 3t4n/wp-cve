<?php
/**
 * The admin functionality of the plugin
 *
 * @link       https://theme4press.com/widget-box/
 * @since      1.0.0
 * @package    Widget Box Lite
 * @author     Theme4Press
 */

if ( ! class_exists( 'Widget_Box_Lite_Admin' ) ) {
	class Widget_Box_Lite_Admin {

		/**
		 * @since    1.0.0
		 */
		private $plugin_name;

		/**
		 * @since    1.0.0
		 */
		private $version;

		/**
		 * @since    1.0.0
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			add_action( 'load-plugins.php', array( $this, 'admin_notice' ) );
			add_action( 'wp_loaded', array( $this, 'hide_notice' ) );

			if ( isset( $_GET['hide-notice'] ) && $_GET['hide-notice'] == 'widget_box_lite_no_theme4press_theme_notice' ) {
				update_option( 'widget_box_lite_no_theme4press_theme_notice', 0 );
			}
			if ( get_option( 'widget_box_lite_no_theme4press_theme_notice', 1 ) ) {
				add_action( 'admin_notices', array( $this, 'no_theme4press_theme_notice' ) );
			}
		}

		/**
		 * @since    1.0.0
		 */
		public function admin_notice() {
			if ( ! get_option( 'widget_box_lite_activation_notice' ) ) {
				add_action( 'admin_notices', array( $this, 'activation_notice' ) );
				update_option( 'widget_box_lite_activation_notice', 0 );
			} elseif ( get_option( 'widget_box_lite_activation_notice' ) == '1' ) {
				// Don't show any notice
			}
		}

		/**
		 * @since    1.0.0
		 */
		public static function hide_notice() {
			if ( isset( $_GET['widget-box-hide-notice'] ) && $_GET['widget-box-hide-notice'] == 'activation_notice' ) {
				if ( ! wp_verify_nonce( $_GET['_widget_box_lite_notice'], 'widget_box_lite_hide_notice' ) ) {
					wp_die( __( 'Action failed. Please refresh the page and retry.', 'widget-box-lite' ) );
				}

				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( __( 'Cheatin&#8217; huh?', 'widget-box-lite' ) );
				}

				$hide_notice = sanitize_text_field( $_GET['widget-box-hide-notice'] );
				update_option( 'widget_box_lite_' . $hide_notice, 1 );
			}
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_theme4press_theme() {
			$widget_box_lite_my_theme = wp_get_theme();
			if ( $widget_box_lite_my_theme->get( 'Name' ) == 'evolve' || $widget_box_lite_my_theme->get( 'Name' ) == 'evolve Child' || $widget_box_lite_my_theme->get( 'Name' ) == 'evolve Plus' || $widget_box_lite_my_theme->get( 'Name' ) == 'evolve Plus Child' ) {
				return true;
			}

			return false;
		}

		/**
		 * @since    1.0.0
		 */
		public function activation_notice() {
			if ( ! Widget_Box_Lite_Admin::is_theme4press_theme() ) {
				return;
			}

			wp_enqueue_style( 'widget-box-notice', plugin_dir_url( __FILE__ ) . 'css/notice.css' ); ?>

            <div class="notice widget-box-notice is-dismissible">
                <p>
                    <img src="<?php echo plugin_dir_url( __FILE__ ) ?>images/logo.png"/><?php echo sprintf( esc_html__( 'Thank you for installing %1$sWidget Box Lite%2$s plugin by Theme4Press. To start creating new widgets please visit the widgets page', 'widget-box-lite' ), '<strong>', '</strong>' ); ?>
                    <a class="button"
                       href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Let\'s Get Started', 'widget-box-lite' ); ?></a>
                    <a href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'activated' ), add_query_arg( 'widget-box-hide-notice', 'activation_notice' ) ), 'widget_box_lite_hide_notice', '_widget_box_lite_notice' ) ); ?>"><?php esc_html_e( 'Dismiss', 'widget-box-lite' ); ?></a>
                </p>
            </div>
			<?php
		}

		/**
		 * @since    1.0.0
		 */
		public function no_theme4press_theme_notice() {
			if ( Widget_Box_Lite_Admin::is_theme4press_theme() ) {
				return;
			}

			wp_enqueue_style( 'widget-box-notice', plugin_dir_url( __FILE__ ) . 'css/notice.css' ); ?>

            <div class="notice widget-box-notice is-dismissible">
                <p><?php echo Widget_Box_Lite_Admin::is_theme4press_theme_message(); ?><a
                            href="<?php echo esc_url( add_query_arg( 'hide-notice', 'widget_box_lite_no_theme4press_theme_notice' ) ); ?>"><?php esc_html_e( 'Dismiss', 'widget-box-lite' ); ?></a>
                </p>
            </div>
			<?php
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_theme4press_theme_message() {
			$message = '';
			if ( ! Widget_Box_Lite_Admin::is_theme4press_theme() ) {
				$message = "<span><img src='" . plugin_dir_url( __FILE__ ) . "images/logo.png' />" . sprintf( esc_html__( 'The %1$sWidget Box Lite%2$s plugin is designed only for %3$sTheme4Press%4$s themes', 'widget-box-lite' ), '<strong>', '</strong>', '<strong>', '</strong>' ) . "</span><a class='button button-primary' target='_blank' href='" . get_admin_url() . "theme-install.php?search=theme4press" . "'>" . esc_html__( 'Install theme', 'widget-box-lite' ) . "</a>";
			}

			return $message;
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_free_theme() {
			$widget_box_lite_my_theme = wp_get_theme();
			if ( $widget_box_lite_my_theme->get( 'Name' ) == 'evolve' || $widget_box_lite_my_theme->get( 'Name' ) == 'evolve Child' ) {
				return true;
			}

			return false;
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_premium_theme() {
			$widget_box_lite_my_theme = wp_get_theme();
			if ( $widget_box_lite_my_theme->get( 'Name' ) == 'evolve Plus' || $widget_box_lite_my_theme->get( 'Name' ) == 'evolve Plus Child' ) {
				return true;
			}

			return false;
		}

		/**
		 * @since    1.0.0
		 */
		public static function upgrade() {
			$message = "<div class='alert' role='alert'>" . sprintf( esc_html__( 'Need more options? Check out the premium version of %s', 'widget-box-lite' ), '<a target="_blank" href="' . esc_url( 'https://theme4press.com/widget-box/' ) . '">Widget Box</a>' ) . "</div>";

			return $message;
		}

		/**
		 * @since    1.0.0
		 */
		public static function get_svg( $icon = null ) {

			if ( empty( $icon ) ) {
				return;
			}

			$svg = '<svg class="widget-box-icon-' . esc_attr( $icon ) . '" aria-hidden="true" role="img">';
			$svg .= ' <use xlink:href="' . plugin_dir_url( __FILE__ ) . ( '/images/icons.svg#widget-box-icon-' ) . esc_html( $icon ) . '"></use> ';
			$svg .= '</svg>';

			return $svg;
		}

		/**
		 * @since    1.0.0
		 */
		public function enqueue_styles( $hook ) {
			if ( 'widgets.php' !== $hook ) {
				return;
			}

			wp_enqueue_style( 'widget-box-lite', plugin_dir_url( __FILE__ ) . 'css/admin.css' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		/**
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook ) {
			if ( 'widgets.php' !== $hook ) {
				return;
			}

			wp_enqueue_media();
			wp_enqueue_script( 'widget-box-lite', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wp-color-picker' );

			$local_variables = array(
				'plugin_url'           => plugin_dir_url( __FILE__ ),
				'website_url'          => get_site_url(),
				'admin_url'            => admin_url(),
				'plugin_home_url'      => esc_url( 'https://theme4press.com/' ),
				'is_premium_version'   => Widget_Box_Lite_Admin::is_premium_theme(),
				'is_free_version'      => Widget_Box_Lite_Admin::is_free_theme(),
				'is_theme4press_theme' => Widget_Box_Lite_Admin::is_theme4press_theme()
			);

			wp_localize_script( 'widget-box-lite', 'widget_box_lite_js_local_vars', $local_variables );
		}
	}
}