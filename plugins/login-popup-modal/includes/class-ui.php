<?php
/**
 * Login modal box Ui.
 *
 * @since   0.0.0
 * @package Login_Modal_Box
 */

/**
 * Login modal box Ui.
 *
 * @since 0.0.0
 */
class LMB_Ui {
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
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Actions to control tabs and show their content.
		add_action( 'lmb_settings_tab', array( $this, 'configurar_nav_tab' ), 1 );
		add_action( 'lmb_settings_tab', array( $this, 'ayuda_nav_tab' ), 2 );

		add_action( 'lmb_settings_content', array( $this, 'configurar_content' ) );
		add_action( 'lmb_settings_content', array( $this, 'ayuda_content' ) );
	}

	/**
	 * 'Configurar' control tab.
	 *
	 * @since  0.0.0
	 */
	public function configurar_nav_tab() {

		global $lmb_active_tab;

		$classname = ( empty( $lmb_active_tab ) || 'configurar' == $lmb_active_tab ) ? 'nav-tab-active' : '';
		?>
		<a 	class="nav-tab <?php echo esc_attr( $classname ); ?>" 
			href="<?php echo esc_attr( admin_url( 'admin.php?page=login-modal-box&tab=configurar' ) ); ?>">
			<?php esc_html_e( 'Settings', 'login-modal-box' ); ?>
		</a>
		<?php
	}

	/**
	 * 'Ayuda' control tab.
	 *
	 * @since  0.0.0
	 */
	public function ayuda_nav_tab() {

		global $lmb_active_tab;

		$classname = ( ! empty( $lmb_active_tab ) && 'ayuda' == $lmb_active_tab ) ? 'nav-tab-active' : '';
		?>
		<a 	class="nav-tab <?php echo esc_attr( $classname ); ?>" 
			href="<?php echo esc_attr( admin_url( 'admin.php?page=login-modal-box&tab=ayuda' ) ); ?>">
			<?php esc_html_e( 'Help', 'login-modal-box' ); ?>
		</a>
		<?php
	}

	/**
	 * Show 'Ayuda' content.
	 *
	 * @since  0.0.0
	 */
	public function ayuda_content() {

		global $lmb_active_tab;

		if ( empty( $lmb_active_tab ) || 'ayuda' != $lmb_active_tab ) {
			return;
		}
		?>
		<div class="wrap">					
			
			<h2><?php esc_html_e( 'Settings', 'login-modal-box' ); ?></h2>
			<p>
				<ul>
					<li>
						<b><?php esc_html_e( 'Header title', 'login-modal-box' ); ?>: </b>
						<?php esc_html_e( 'Set the title of the modal box.', 'login-modal-box' ); ?></li>
					<li>
						<b><?php esc_html_e( 'Page after login', 'login-modal-box' ); ?>: </b>
						<?php esc_html_e( 'Set stay on the same page after login, or on admin area or choose a page where to redirect.', 'login-modal-box' ); ?>
					</li>
					<li>
						<b><?php esc_html_e( 'Page after logout', 'login-modal-box' ); ?>: </b>
						<?php esc_html_e( 'Choose a page where to redirect after logout.', 'login-modal-box' ); ?>
					</li>
					<li>
						<b><?php esc_html_e( 'Add to menu', 'login-modal-box' ); ?>:</b>
						<?php esc_html_e( 'Choose a menu where to show login/logout item.', 'login-modal-box' ); ?>
					</li>
				</ul>				
			</p>			

			<br/><h2><?php esc_html_e( 'Shortcode', 'login-modal-box' ); ?></h2>
			<p>
				<?php esc_html_e( 'To show a login/logout link that open the modal form use the shortcode:', 'login-modal-box' ); ?>
			</p>
			<p>
				<span class="large-text code">[lmb_login]<?php esc_html_e( 'Link text', 'login-modal-box' ); ?>[/lmb_login]</span>
			</p>
			<br/>
		</div>
		<?php
	}

	/**
	 * Show 'Configurar' content.
	 *
	 * @since  0.0.0
	 */
	public function configurar_content() {

		global $lmb_active_tab;

		if ( empty( $lmb_active_tab ) || 'configurar' != $lmb_active_tab ) {
			return;
		}

		$settings = $this->plugin->settings;
		?>
				
		<form method="post" action="admin-post.php">
			
			<?php wp_nonce_field( 'login-modal-box' ); ?>
			<input type="hidden" value="save_settings" name="action"/>			
			<table class="form-table">
				<tbody>
					
					<?php /* Header title */ ?>
					<tr>
						<th scope="row">
							<label for="header-title"><span><?php esc_html_e( 'Header title', 'login-modal-box' ); ?></span></label>
						</th>
						<td>
							<input 	type="text" 
									name="header-title" 
									value="<?php echo esc_attr( $settings->get_setting( 'header-title' ) ); ?>" 
									/>
						</td>
					</tr>

					<?php /* Page after login */ ?>
					<tr>
						<th scope="row">
							<label for="login"><span><?php esc_html_e( 'Page after login', 'login-modal-box' ); ?></span></label>
						</th>
						<td>
							<select name="login-id" id="login-id">
								<optgroup>
									<option value="0" 
									<?php
									if ( $settings->get_setting( 'login-id' ) == 0 ) {
										printf( 'selected="selected"' );
									}
									?>
									><?php esc_attr_e( 'Stay on the same page', 'login-modal-box' ); ?></option>
								
									<option value="-1" 
									<?php
									if ( $settings->get_setting( 'login-id' ) == -1 ) {
										printf( 'selected="selected"' );
									}
									?>
									><?php esc_attr_e( 'Admin area', 'login-modal-box' ); ?></option>

								</optgroup>
								<optgroup label=<?php esc_attr_e( 'Pages', 'login-modal-box' ); ?>>
									<?php
									foreach ( get_pages() as $page ) {
										$selected = ( $page->ID == $settings->get_setting( 'login-id' ) ) ? ( 'selected="selected"' ) : '';
										printf( '<option value="%s" %s>%s</option>', esc_attr( $page->ID ), esc_attr( $selected ), esc_html( $page->post_title ) );
									}
									?>
								</optgroup>
							</select>								
						</td>
					</tr>

					<?php /* Page after logout */ ?>
					<tr>
						<th scope="row">
							<label for="logout"><span><?php esc_html_e( 'Page after logout', 'login-modal-box' ); ?></span></label>
						</th>
						<td>
							<select name="logout-id" id="logout-id">
								<option value="0" 
								<?php
								if ( $settings->get_setting( 'logout-id' ) == 0 ) {
									printf( 'selected="selected"' );
								}
								?>
								><?php esc_attr_e( 'Home Page', 'login-modoal-box' ); ?></option>

								<?php
								foreach ( get_pages() as $page ) {
									$selected = ( $page->ID == $settings->get_setting( 'logout-id' ) ) ? ( 'selected="selected"' ) : '';
									printf( '<option value="%s" %s>%s</option>', esc_attr( $page->ID ), esc_attr( $selected ), esc_html( $page->post_title ) );
								}
								?>
							</select>
						</td>
					</tr>

					<?php /* Add to menu */ ?>
					<tr>
						<th scope="row">
							<label for="menu-location"><span><?php esc_html_e( 'Add to menu', 'login-modal-box' ); ?></span></label>
						</th>
						<td>
							<select name="menu-location" id="menu-location">
								<option value="0" 
								<?php
								if ( $settings->get_setting( 'menu-location' ) == null ) {
									printf( 'selected="selected"' );
								}
								?>
								><?php esc_attr_e( 'None', 'login-modoal-box' ); ?></option>
								<?php
								foreach ( get_registered_nav_menus() as $location => $description ) {
									$selected = ( $location == $settings->get_setting( 'menu-location' ) ) ? ( 'selected="selected"' ) : '';
									printf( '<option value="%s" %s>%s</option>', esc_attr( $location ), esc_attr( $selected ), esc_html( $location ) );
								}
								?>
							</select>								
						</td>
					</tr>

				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
			
		<?php
	}

	/**
	 * Echo plugin settings view
	 *
	 * @since  0.0.0
	 */
	public function options_ui() {

		global $lmb_active_tab;

		$lmb_active_tab = ! empty( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'configurar';
		?>
		
		<?php
		/* Login modal box messages */

		$message       = __( 'Something went wrong', 'login-modal-box' );
		$message_class = 'notice-success';

		if ( isset( $_GET['message'] ) ) {
			switch ( $_GET['message'] ) {
				case 'saved':
					$message = __( 'Settings saved', 'login-modal-box' );
					break;
				default:
					$message       = __( 'Something went wrong', 'login-modal-box' );
					$message_class = 'notice-error';
					break;
			}
			?>
		<div id="message" class="notice <?php echo esc_attr( $message_class ); ?> is-dismissible">
			<p><?php echo esc_html( $message ); ?></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Discard this notice', 'login-modal-box' ); ?> </span>
			</button>
		</div>
		<?php } ?>

		<?php /* Tabs */ ?>
		<div class="wrap">		
			<br/><h1><?php esc_html_e( 'All Login Form', 'login-modal-box' ); ?></h1><br/>
			<div>
				<h2 class="nav-tab-wrapper">
					<?php
					// show tabs by tab param.
					do_action( 'lmb_settings_tab' );
					?>
				</h2>
		
				<?php
					// show content by tab param.
					do_action( 'lmb_settings_content' );
				?>
			</div>
			<hr>
			<p>
				<?php
					printf(
						// translators: 1 is the plugin name 2 is a link with starts rating text.
						__( 'If you like %1$s please leave us a %2$s rating. Thanks!', 'login-modal-box' ),
						sprintf( '<strong>%s</strong>', esc_html__( 'All Login Form', 'login-modal-box' ) ),
						'<a href="https://wordpress.org/support/plugin/login-popup-modal/reviews?rate=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
					);
				?>
			</p>
		</div>
		<?php
	}
}
