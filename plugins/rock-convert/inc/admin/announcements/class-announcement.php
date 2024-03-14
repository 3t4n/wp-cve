<?php
/**
 * Announcements Class
 *
 * @package Rock_Convert
 */

namespace Rock_Convert\inc\admin\announcements;

use Rock_Convert\inc\admin\Utils;

/**
 * Class Announcement
 *
 * @package Rock_Convert\inc\admin\announcements
 */
class Announcement {

	/**
	 * Construct
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_post_rock_convert_announcements_save_form', array( $this, 'save_announcements' ) );
	}

	/**
	 * Get announcements options
	 *
	 * @return mixed|string
	 */
	public static function options() {
		$post    = get_post();
		$options = get_option( 'rock_convert_announcement_settings', array() );

		$post_options = array(
			'isSingle' => is_single( $post ),
			'postType' => ! empty( $post ) ? $post->post_type : 'none',
		);

		return wp_json_encode(
			array_merge( $options, $post_options )
		);
	}

	/**
	 * Add menu admin
	 *
	 * @return void
	 */
	public function add_menu_page() {
		add_submenu_page(
			'edit.php?post_type=cta',
			__( 'Barra de anúncios', 'rock-convert' ) . ' - Rock Convert',
			__( 'Barra de anúncios', 'rock-convert' ),
			'manage_options',
			'rock-convert-announcements',
			array(
				$this,
				'create_admin_page',
			)
		);
	}

	/**
	 * Announcements load view
	 *
	 * @return void
	 */
	public function create_admin_page() {
		include_once 'views/announcements-settings-page.php';
	}

	/**
	 * Save annoucements
	 *
	 * @return void
	 */
	public function save_announcements() {
		if ( isset( $_POST['announcements_nonce'] ) && wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['announcements_nonce'] ) ),
			'announcements_nonce'
		) ) {

			$activate       = sanitize_key( Utils::getArrayValue( $_POST, 'rconvert_activate_announcement' ) );
			$link           = esc_url_raw( Utils::getArrayValue( $_POST, 'rconvert_announcement_link' ) );
			$urls           = Utils::sanitize_array( $_POST['rconvert_announcement_excluded_pages'] );
			$position       = sanitize_text_field( Utils::getArrayValue( $_POST, 'rconvert_announcement_position' ) );
			$text           = sanitize_text_field( Utils::getArrayValue( $_POST, 'rconvert_announcement_text' ) );
			$btn            = sanitize_text_field( Utils::getArrayValue( $_POST, 'rconvert_announcement_btn' ) );
			$visibility     = sanitize_text_field( Utils::getArrayValue( $_POST, 'rock_convert_visibility' ) );
			$bg_color       = sanitize_hex_color( Utils::getArrayValue( $_POST, 'rconvert_announcement_bg_color' ) );
			$text_color     = sanitize_hex_color( Utils::getArrayValue( $_POST, 'rconvert_announcement_text_color' ) );
			$btn_color      = sanitize_hex_color( Utils::getArrayValue( $_POST, 'rconvert_announcement_btn_color' ) );
			$btn_text_color = sanitize_hex_color( Utils::getArrayValue( $_POST, 'rconvert_announcement_btn_text_color' ) );

			$settings = array(
				'activated'      => $activate,
				'text'           => $text,
				'btn'            => $btn,
				'link'           => $link,
				'position'       => $position,
				'visibility'     => $visibility,
				'urls'           => $urls,
				'bg_color'       => $bg_color,
				'text_color'     => $text_color,
				'btn_color'      => $btn_color,
				'btn_text_color' => $btn_text_color,
			);

			update_option( 'rock_convert_announcement_settings', $settings );

			wp_safe_redirect( admin_url( '/edit.php?post_type=cta&page=rock-convert-announcements&success=true' ), 301 );
			exit;
		}
	}
}
