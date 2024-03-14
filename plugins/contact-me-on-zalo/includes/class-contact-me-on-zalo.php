<?php
/**
 * Handles logic for the admin settings page.
 *
 * @since 1.0.0
 */
final class CMOZ {

	/**
	 * The single instance of the class.
	 *
	 * @var Contact_Me_On_Zalo
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Contact_Me_On_Zalo Instance.
	 *
	 * Ensures only one instance of Contact_Me_On_Zalo is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Contact_Me_On_Zalo - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init_hooks' ) );
	}

	/**
	 * Adds the admin menu
	 * the plugin's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'frontend' ) );
		add_filter( 'plugin_action_links_' . CMOZ_BASE_NAME, array( $this, 'add_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_row_meta' ), 10, 4 );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 500 );

		if ( isset( $_REQUEST['page'] ) && 'contact-me-on-zalo' == $_REQUEST['page'] ) {
			$this->save();
		}
	}

	/**
	 * Enqueue frontend styles & scripts.
	 */
	public function enqueue_scripts() {
		$data = $this->data();

		if ( '2' == $data['style'] ) {
			wp_enqueue_style( 'cmoz-style', CMOZ_ASSETS_URL . 'css/style-2.css', array(), CMOZ_VERSION );
		} else {
			wp_enqueue_style( 'cmoz-style', CMOZ_ASSETS_URL . 'css/style-1.css', array(), CMOZ_VERSION );
		}
	}

	/**
	 * Admin Enqueue frontend styles & scripts.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'cmoz-admin-style', CMOZ_ASSETS_URL . 'css/admin.css', array(), CMOZ_VERSION );
	}

	/**
	 * Register admin settings menu.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function menu() {
		if ( is_main_site() || ! is_multisite() ) {
			if ( current_user_can( 'manage_options' ) ) {

				$title    = esc_html__( 'CMOZ Options', 'contact-me-on-zalo' );
				$cap      = 'manage_options';
				$slug     = 'contact-me-on-zalo';
				$func     = array( $this, 'backend' );
				$icon     = CMOZ_ASSETS_URL . 'images/zalo-icon.png';
				$position = 500;

				add_menu_page( $title, $title, $cap, $slug, $func, $icon, $position );
			}
		}
	}

	/**
	 * Get settings data.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function data() {
		$defaults = array(
			'phone'    => '0987654321',
			'margin'   => '',
			'position' => 'left',
			'style'    => '2',
		);

		$data = $this->option( 'cmoz_options', true );

		if ( ! is_array( $data ) || empty( $data ) ) {
			return $defaults;
		}

		if ( is_array( $data ) && ! empty( $data ) ) {
			return wp_parse_args( $data, $defaults );
		}
	}

	/**
	 * Renders the update message.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function message() {
		if ( ! empty( $_POST ) ) {
			echo '<div class="updated"><p>' . esc_html__( 'Settings updated!', 'contact-me-on-zalo' ) . '</p></div>';
		}
	}

	/**
	 * Admin html form setting.
	 *
	 * @return [type] [description]
	 */
	public function backend() {
		include CMOZ_PATH . 'includes/backend.php';
	}

	/**
	 * Contact Me On Zalo frontend template.
	 * @return [type] [description]
	 */
	public function frontend() {
		$data = $this->data();

		$hotline = '';
		if ( ! empty( $data['phone'] ) ) {
			$hotline = $data['phone'];
		} else {
			return;
		}
		?>
		<div class="zalo-container <?php echo empty( $data['position'] ) ? 'left' : $data['position']; ?>"<?php echo empty( $data['margin'] ) ? '' : ' style="bottom:' . $data['margin'] . 'px;"'; ?>>
			<a id="zalo-btn" href="https://zalo.me/<?php echo $hotline; ?>" target="_blank" rel="noopener noreferrer nofollow">
				<?php if ( '2' == $data['style'] ) : ?>
				<div class="animated_zalo infinite zoomIn_zalo cmoz-alo-circle"></div>
				<div class="animated_zalo infinite pulse_zalo cmoz-alo-circle-fill"></div>
				<span><img src="<?php echo CMOZ_ASSETS_URL . 'images/zalo-2.png'; ?>" alt="Contact Me on Zalo"></span>
				<?php else : ?>
				<div class="zalo-ico zalo-has-notify">
					<div class="zalo-ico-main">
						<img src="<?php echo CMOZ_ASSETS_URL . 'images/zalo-1.png'; ?>" alt="Contact Me on Zalo" />
					</div>
					<em></em>
				</div>
				<?php endif; ?>
			</a>
		</div>
	<?php
	}

	/**
	 * Renders the action for a form.
	 *
	 * @since 1.0.0
	 * @param string $type The type of form being rendered.
	 * @return void
	 */
	public function form_action( $type = '' ) {
		return admin_url( '/admin.php?page=contact-me-on-zalo' . $type );
	}

	/**
	 * Returns an option from the database for
	 * the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @return mixed
	 */
	public function option( $key, $network_override = true ) {
		if ( is_network_admin() ) {
			$value = get_site_option( $key );
		}
			elseif ( ! $network_override && is_multisite() ) {
				$value = get_site_option( $key );
			}
			elseif ( $network_override && is_multisite() ) {
				$value = get_option( $key );
				$value = ( false === $value || ( is_array( $value ) && in_array( 'disabled', $value ) ) ) ? get_site_option( $key ) : $value;
			}
			else {
			$value = get_option( $key );
		}

		return $value;
	}

	/**
	 * Saves settings.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private function save() {
		if ( ! isset( $_POST['cmoz-settings-nonce'] ) || ! wp_verify_nonce( $_POST['cmoz-settings-nonce'], 'cmoz-settings' ) ) {
			return;
		}

		$data = $this->data();

		$data['phone']    = isset( $_POST['cmoz_options']['phone'] ) ? sanitize_text_field( $_POST['cmoz_options']['phone'] ) : '';
		$data['margin']   = isset( $_POST['cmoz_options']['margin'] ) ? sanitize_text_field( $_POST['cmoz_options']['margin'] ) : '';
		$data['position'] = isset( $_POST['cmoz_options']['position'] ) ? sanitize_text_field( $_POST['cmoz_options']['position'] ) : 'left';
		$data['style']    = isset( $_POST['cmoz_options']['style'] ) ? sanitize_text_field( $_POST['cmoz_options']['style'] ) : '1';

		update_site_option( 'cmoz_options', $data );
	}

	/**
	 * Admin footer text.
	 *
	 * Modifies the "Thank you" text displayed in the admin footer.
	 *
	 * Fired by `admin_footer_text` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $footer_text The content that will be printed.
	 *
	 * @return string The content that will be printed.
	 */
	public function admin_footer_text( $footer_text ) {
		$current_screen = get_current_screen();
		$is_screen = ( $current_screen && false !== strpos( $current_screen->id, 'contact-me-on-zalo' ) );

		if ( $is_screen ) {
			$footer_text = __( ' Enjoyed <strong>Contact Me On Zalo</strong>? Please leave us a <a href="https://namncn.com/plugins/contact-me-on-zalo/" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support!', 'contact-me-on-zalo' );
		}

		return $footer_text;
	}

	/**
	 * [add_action_links description]
	 * @param  [type] $links_array [description]
	 * @return [type]              [description]
	 */
	public function add_action_links( $links ) {
		$links[] = '<a href="' . admin_url( '/admin.php?page=contact-me-on-zalo' ) . '">' . esc_html__( 'Settings', 'contact-me-on-zalo' ) . '</a>';

		return array_merge( $links );
	}

	/**
	 * [add_row_meta description]
	 * @param  [type] $links            [description]
	 * @param  [type] $plugin_file_name [description]
	 * @param  [type] $plugin_data      [description]
	 * @param  [type] $status           [description]
	 * @return [type]                   [description]
	 */
	public function add_row_meta( $links, $plugin_file_name, $plugin_data, $status ) {

		if ( strpos( $plugin_file_name, CMOZ_NAME ) ) {
			$links[] = '<a href="https://namncn.com/plugins/contact-me-on-zalo/" target="_blank">' . esc_html__( 'FAQ', 'contact-me-on-zalo' ) . '</a>';
			$links[] = '<a href="https://namncn.com/lien-he/" target="_blank">' . esc_html__( 'Support', 'contact-me-on-zalo' ) . '</a>';
			$links[] = '<a href="https://namncn.com/chuyen-muc/plugins/" target="_blank">' . esc_html__( 'Other Plugins', 'contact-me-on-zalo' ) . '</a>';
		}

		return $links;
	}
}
