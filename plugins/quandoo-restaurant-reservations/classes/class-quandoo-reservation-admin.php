<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Quandoo_Reservation_Admin {
	private static $_instance = null;
	private $_hook;
	public function __construct () {
		// Register the settings with WordPress.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Register the settings screen within WordPress.
		add_action( 'admin_menu', array( $this, 'register_settings_screen' ) );
	} // End __construct()

	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	public function register_settings_screen () {
		$this->_hook = add_submenu_page('edit.php?post_type=quandoo-reservation', __( 'Quick Start', 'quandoo-reservation' ), __( 'Quick Start', 'quandoo-reservation' ), 'manage_options', 'quandoo-reservation', array( $this, 'settings_screen' ) );
	} // End register_settings_screen()

	public function settings_screen () {
		global $title;
		$sections = Quandoo_Reservation()->settings->get_settings_sections();
		$tab = $this->_get_current_tab( $sections );
		?>
		<div class="wrap quandoo-reservation-wrap">
			<?php
				echo $this->get_admin_header_html( $sections, $title );
			?>
			<form action="options.php" method="post">
				<?php
					settings_fields( 'quandoo-reservation-settings-' . $tab );
					do_settings_sections( 'quandoo-reservation-' . $tab );
					submit_button( __( 'Save Changes', 'quandoo-reservation' ) );
				?>
			</form>
		</div><!--/.wrap-->
		<?php
	} // End settings_screen()

	public function register_settings () {
		$sections = Quandoo_Reservation()->settings->get_settings_sections();
		if ( 0 < count( $sections ) ) {
			foreach ( $sections as $k => $v ) {
				register_setting( 'quandoo-reservation-settings-' . sanitize_title_with_dashes( $k ), 'quandoo-reservation-' . $k, array( $this, 'validate_settings' ) );
				add_settings_section( sanitize_title_with_dashes( $k ), $v, array( $this, 'render_settings' ), 'quandoo-reservation-' . $k, $k, $k );
			}
		}
	} // End register_settings()

	public function render_settings ( $args ) {

		$token = $args['id'];
		$fields = Quandoo_Reservation()->settings->get_settings_fields( $token );

		if ( 0 < count( $fields ) ) {
			foreach ( $fields as $k => $v ) {
				$args 		= $v;
				$args['id'] = $k;

				add_settings_field( $k, $v['name'], array( Quandoo_Reservation()->settings, 'render_field' ), 'quandoo-reservation-' . $token , $v['section'], $args );
			}
		}

	} // End render_settings()

	public function validate_settings ( $input ) {
		$sections = Quandoo_Reservation()->settings->get_settings_sections();
		$tab = $this->_get_current_tab( $sections );
		return Quandoo_Reservation()->settings->validate_settings( $input, $tab );
	} // End validate_settings()

	public function get_admin_header_html ( $sections, $title ) {
		$defaults = array(
							'tag' => 'h2',
							'atts' => array( 'class' => 'quandoo-reservation-wrapper' ),
							'content' => $title
						);

		$args = $this->_get_admin_header_data( $sections, $title );

		$args = wp_parse_args( $args, $defaults );

		$atts = '';
		if ( 0 < count ( $args['atts'] ) ) {
			foreach ( $args['atts'] as $k => $v ) {
				$atts .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
			}
		}

		$response = '<' . esc_attr( $args['tag'] ) . $atts . '>' . $args['content'] . '</' . esc_attr( $args['tag'] ) . '>' . "\n";

		return $response;
	} // End get_admin_header_html()

	private function _get_current_tab ( $sections = array() ) {
		if ( isset ( $_GET['tab'] ) ) {
			$response = sanitize_title_with_dashes( $_GET['tab'] );
		} else {
			if ( is_array( $sections ) && ! empty( $sections ) ) {
				list( $first_section ) = array_keys( $sections );
				$response = $first_section;
			} else {
				$response = '';
			}
		}

		return $response;
	} // End _get_current_tab()

	private function _get_admin_header_data ( $sections, $title ) {
		$response = array( 'tag' => 'h2', 'atts' => array( 'class' => 'quandoo-reservation-wrapper' ), 'content' => $title );

		if ( is_array( $sections ) && 1 < count( $sections ) ) {
			$response['content'] = '';
			$response['atts']['class'] = 'nav-tab-wrapper';

			$tab = $this->_get_current_tab( $sections );

			foreach ( $sections as $key => $value ) {
				$class = 'nav-tab';
				if ( $tab == $key ) {
					$class .= ' nav-tab-active';
				}

				$response['content'] .= '<a href="' . admin_url( 'edit.php?post_type=quandoo-reservation&page=quandoo-reservation&tab=' . sanitize_title_with_dashes( $key ) ) . '" class="' . esc_attr( $class ) . '">' . esc_html( $value ) . '</a>';
			}
		}

		return (array)apply_filters( 'quandoo-reservation-get-admin-header-data', $response );
	} // End _get_admin_header_data()
} // End Class



