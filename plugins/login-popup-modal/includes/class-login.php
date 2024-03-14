<?php
/**
 * Login modal box Login.
 *
 * @since   0.0.0
 * @package Login_Modal_Box
 */

/**
 * Login modal box Login.
 *
 * @since 0.0.0
 */
class LMB_Login {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.0
	 *
	 * @var   Login_Modal_Box
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param  Login_Modal_Box $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->register();
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 */
	public function register() {
		// Register Login Modal Box styles.
		wp_register_style(
			'lmb-style',
			sprintf( '%sassets/css/lmb-style.css', $this->plugin->url ),
			array(),
			$this->plugin->version
		);

		// Register Login Modal Box scripts.
		wp_register_script(
			'lmb-remodal',
			$this->plugin->url . 'assets/js/remodal.js',
			array( 'jquery' ),
			$this->plugin->version
		);
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {

		add_shortcode(
			'lmb_login',
			function( $atts, $content ) {
				$content = isset( $content ) ? trim( sanitize_text_field( $content ) ) : null;
				return $this->lmb_login( $atts, $content );
			}
		);

		add_action( 'wp_footer', array( $this, 'create_login_form' ) );
		add_action( 'wp_login_failed', array( $this, 'login_fail' ) );

		add_filter( 'wp_nav_menu_items', array( $this, 'new_nav_menu_items' ), 10, 2 );

		wp_enqueue_style( 'lmb-style' );
		wp_enqueue_script( 'lmb-remodal' );
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 * @param  string $items Items.
	 * @param  string $args Args.
	 * @return string
	 */
	public function new_nav_menu_items( $items, $args ) {

		$settings = $this->plugin->settings->get_settings();

		if ( $args->theme_location == $settings['menu-location'] ) {
			if ( is_user_logged_in() ) {
				/* is_user_logged_in() genera cookies diferentes con http, https
				 si tienes force ssl, la funcion is_user_logged_in no funcionará
				 (tampoco verás el admin bar en el frontal) */
				$logout_url = $settings['site-url'];
				$logout_id = $settings['logout-id'];
				if ( 0 != $logout_id ) {
					$logout_url = get_permalink( $logout_id );
				}

				$link = sprintf( '<li><a href="%s" title="Logout">%s</a></li>', wp_logout_url( $logout_url ), esc_html__( 'Logout', 'login-modal-box' ) );

			} else {
				global $wp;
				$query_args = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
				$link = sprintf( ' <li><a href="%s#login">%s</a></li>', $query_args, esc_html__( 'Login', 'login-modal-box' ) );
			}
			$items = $items . $link;
		}
		return $items;
	}


	/**
	 * The shortcode function.
	 *
	 * @since  0.0.0
	 * @param  string $atts Atts.
	 * @param  string $content Content.
	 * @return string
	 */
	public function lmb_login( $atts = [], $content = '' ) {

		if ( ! is_user_logged_in() ) {

			$content = isset( $content ) ? $content : __( 'Login', 'login-modal-box' );

			return '<a href="#login" title="login" class="login">' . esc_html( $content ) . '</a>';

		} else {

			$settings = $this->plugin->settings->get_settings();

			$logout_url = $settings['site-url'];
			$logout_id = $settings['logout-id'];
			if ( 0 != $logout_id ) {
				$logout_url = get_permalink( $logout_id );
			}

			return '<a href="' . wp_logout_url( $logout_url ) . '" title="Logout">' . esc_html__( 'Logout', 'login-modal-box' ) . '</a></li>';
		}
	}

	/**
	 * Bla bla bla
	 *
	 * @since  0.0.0
	 */
	public function create_login_form() {

		$settings = $this->plugin->settings->get_settings();

		$login_id = $settings['login-id'];
		switch ( $login_id ) {
			case -1:
				// Link to admin area.
				$login_url = admin_url();
				break;
			case 0:
				// Stay on the same page.
				global $wp;
				$login_url = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
				break;
			default:
				$login_url = get_permalink( $login_id );
		}

		$login_args = array(
			'echo'           => true,
			'redirect'       => $login_url,
			'form_id'        => 'login',
			'label_username' => __( 'Username', 'login-modal-box' ),
			'label_password' => __( 'Password', 'login-modal-box' ),
			'label_remember' => __( 'Remember Me', 'login-modal-box' ),
			'label_log_in'   => __( 'Log In', 'login-modal-box' ),
			'id_username'    => 'log',
			'id_password'    => 'pwd',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'remember'       => true,
			'value_username' => '',
			'value_remember' => false,
		);

		$request_uri = trim( sanitize_text_field( wp_unslash ( $_SERVER['REQUEST_URI'] ) ) );
		?>

		<div class="remodal" data-remodal-id="login">		
			<p class="signin-title"><?php echo esc_html( $settings['header-title'] ); ?></p>
			<button data-remodal-action="close" class="remodal-close ion-close" aria-label="Close"></button>

			<?php
			if ( basename( $request_uri ) == '?' ) {
				?>
					<div id="login-error">
						<?php esc_html_e( 'Login failed: You have entered an incorrect Username or Password, please try again.', 'login-modal-box' ); ?>
					</div>
				<?php
			}
			?>

			<div class="login">
				<?php wp_login_form( $login_args ); ?>
			</div> <!-- #login -->

			<div class="login-links">
				<?php 
				printf( '<a href="%s">%s</a>', esc_url( wp_lostpassword_url() ), esc_html__( 'Reset password', 'login-modal-box' ) ); 
				if ( get_option( 'users_can_register' ) ) {
					?>
					<span class="login-links-separator">
						<?php esc_html_e( 'or', 'login-modal-box' ); ?>
					</span>
					<?php
					printf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), esc_html__( 'Create account', 'login-modal-box' ) ); 
				}
				?>
			</div>
		</div> <!-- .remodal -->
		
		<?php
	}

	/**
	 * Login fail
	 *
	 * @since  0.0.0
	 * @param  string $username Username.
	 */
	public function login_fail( $username ) {
		wp_redirect( home_url() . '/?#login' );
		exit;
	}

} // class
