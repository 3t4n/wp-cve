<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @see        https://www.madebytribe.com
 * @since      1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Tribe Interactive <success@madebytribe.co>
 */
class Caddy_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string the ID of this plugin
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string the current version of this plugin
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name the name of this plugin
	 * @param string $version     the version of this plugin
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $pagenow;
		if ( isset( $_GET['page'] ) ) {
			// Get the 'page' parameter from the URL
			$raw_page_name = filter_input(INPUT_GET, 'page', FILTER_DEFAULT);
			
			// Sanitize the 'page' parameter
			$page_name = sanitize_text_field($raw_page_name);

			if ( 'caddy' == $page_name || 'caddy-addons' === $page_name ) {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/caddy-admin.css', array(), $this->version, 'all' );
			}
		}
		if ( $pagenow == 'plugins.php' ) {
			wp_enqueue_style( 'caddy-deactivation-popup', plugin_dir_url( __FILE__ ) . 'css/caddy-deactivation.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $pagenow;
		if ( isset( $_GET['page'] ) ) {
			// Get the 'page' parameter from the URL
			$raw_page_name = filter_input(INPUT_GET, 'page', FILTER_DEFAULT);
			
			// Sanitize the 'page' parameter
			$page_name = sanitize_text_field($raw_page_name);

			if ( 'caddy' == $page_name ) {
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/caddy-admin.js', [ 'jquery' ], $this->version, true );
				// make the ajaxurl var available to the above script
				$params = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'caddy' ),
				);
				wp_localize_script( $this->plugin_name, 'caddyAjaxObject', $params );
			}
		}
		if ( $pagenow == 'plugins.php' ) {
			wp_enqueue_script( 'caddy-deactivation-popup', plugin_dir_url( __FILE__ ) . 'js/caddy-deactivation.min.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'caddy-deactivation-popup', 'caddyAjaxObject', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'cc_admin_nonce' ),
			) );
		}
	}

	/**
	 * Register a caddy menu page.
	 */
	public function cc_register_menu_page() {
		add_menu_page(
			__( 'Caddy', 'caddy' ),
			__( 'Caddy', 'caddy' ),
			'manage_options',
			'caddy',
			[ $this, 'caddy_menu_page_callback' ],
			'dashicons-smiley',
			65
		);
		add_submenu_page(
			'caddy',
			__( 'Settings', 'caddy' ),
			__( 'Settings', 'caddy' ),
			'manage_options',
			'caddy'
		);
		add_submenu_page(
			'caddy',
			__( 'Add-ons', 'caddy' ),
			__( 'Add-ons', 'caddy' ),
			'manage_options',
			'caddy-addons',
			[ $this, 'caddy_addons_page_callback' ]
		);
	}

	/**
	 * Display a caddy menu page.
	 */
	public function caddy_menu_page_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/caddy-admin-display.php';
	}

	/**
	 * Display a caddy add-ons submenu page.
	 */
	public function caddy_addons_page_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/caddy-addons-page.php';
	}

	/**
	 * Dismiss the welcome notice.
	 */
	public function cc_dismiss_welcome_notice() {

		//Check nonce
		if ( wp_verify_nonce( $_POST['nonce'], 'caddy' ) ) {
			update_option( 'cc_dismiss_welcome_notice', 'yes' );
		}

		wp_die();
	}

	/**
	 * Dismiss the optin notice.
	 */
	public function cc_dismiss_optin_notice() {

		$current_user_id = get_current_user_id();
		//Check nonce
		if ( wp_verify_nonce( $_POST['nonce'], 'caddy' ) ) {
			update_user_meta( $current_user_id, 'cc_dismiss_user_optin_notice', 'yes' );
		}

		wp_send_json_success();

		wp_die();
	}

	/**
	 * Include tab screen files
	 */
	public function cc_include_tab_screen_files() {
		$caddy_tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'settings';

		if ( 'settings' === $caddy_tab ) {
			include plugin_dir_path( __FILE__ ) . 'partials/caddy-admin-settings-screen.php';
		} elseif ( 'styles' === $caddy_tab ) {
			include plugin_dir_path( __FILE__ ) . 'partials/caddy-admin-style-screen.php';
		}
	}

	/**
	 * Upgrade to premium HTML
	 */
	public function cc_upgrade_to_premium_html() {
		$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );
		// Display only if premium plugin is not active
		if ( 'valid' !== $caddy_license_status ) {
			?>
			<div class="cc-box cc-box-cta cc-upgrade">
				<span class="dashicons dashicons-superhero-alt"></span>
				<h3><?php echo esc_html( __( 'Upgrade to Premium', 'caddy' ) ); ?></h3>
				<p><?php echo esc_html( __( 'Unlock powerful new Caddy features:', 'caddy' ) ); ?></p>
				<ul>
					<li><span class="dashicons dashicons-saved"></span><?php echo esc_html( __( '7 different cart icon styles.', 'caddy' ) ); ?></li>
					<li><span class="dashicons dashicons-saved"></span><?php echo esc_html( __( '15+ custom color options.', 'caddy' ) ); ?></li>
					<li><span class="dashicons dashicons-saved"></span><?php echo esc_html( __( 'Bubble positioning options.', 'caddy' ) ); ?></li>
					<li><span class="dashicons dashicons-saved"></span><?php echo esc_html( __( 'Cart notices, add-ons & more.', 'caddy' ) ); ?></li>
				</ul>
				<p><strong><?php echo esc_html( __( 'Use promo code "PREMIUM20" to get 20% off for a limited time.', 'caddy' ) ); ?></strong></p>
				<?php
				echo sprintf(
					'<a href="%1$s" target="_blank" class="button-primary">%2$s</a>',
					esc_url( 'https://usecaddy.com/?utm_source=upgrade-notice&amp;utm_medium=plugin&amp;utm_campaign=plugin-links' ),
					esc_html( __( 'Get Premium Edition', 'caddy' ) )
				); ?>
			</div>
			<?php
		}
	}

	/**
	 * Display addons tab html
	 */
	public function cc_addons_html_display() {
		$add_on_html_flag = false;

		if ( isset( $_GET['page'] ) && 'caddy-addons' === $_GET['page'] ) {
			$add_on_html_flag = true;

			if ( isset( $_GET['tab'] ) && 'addons' !== $_GET['tab'] ) {
				$add_on_html_flag = false;
			}
		}

		if ( $add_on_html_flag ) {
			$caddy_premium_license_status = get_option( 'caddy_premium_edd_license_status' );
			$caddy_ann_license_status     = get_transient( 'caddy_ann_license_status' );
			$caddy_ga_license_status      = get_transient( 'ga_tracking_license_status' );

			$caddy_addons_array = [
				'caddy-premium'      => [
					'icon'        => 'dashicons-awards',
					'title'       => __( 'Caddy Premium Edition', 'caddy' ),
					'description' => __( 'Premium unlocks powerful customization features for Caddy including an in-cart "offers" tab, exclusion rules for recommendations and free shipping meter, color style management, positioning and more.', 'caddy' ),
					'btn_title'   => __( 'Get Premium', 'caddy' ),
					'btn_link'    => 'https://www.usecaddy.com/?utm_source=caddy-addons&utm_medium=plugin&utm_campaign=addon-links',
					'activated'   => in_array( 'caddy-premium/caddy-premium.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ? 'true' : 'false',
					'license'     => ( 'valid' === $caddy_premium_license_status ) ? 'activated' : 'not_activated',
				],
				'caddy-announcement' => [
					'icon'        => 'dashicons-megaphone',
					'title'       => __( 'Caddy Announcement', 'caddy' ),
					'description' => __( 'Add a customizable annoucement bar within the Caddy cart.', 'caddy' ),
					'btn_title'   => __( 'Get Add-on', 'caddy' ),
					'btn_link'    => 'https://www.usecaddy.com/?utm_source=caddy-addons&utm_medium=plugin&utm_campaign=addon-links',
					'activated'   => in_array( 'caddy-announcements/caddy-announcements.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ? 'true' : 'false',
					'license'     => ( 'valid' === $caddy_ann_license_status ) ? 'activated' : 'not_activated',
				]
			];

			if ( ! empty( $caddy_addons_array ) ) {
				?>
				<div class="cc-addons-wrap">
					<?php foreach ( $caddy_addons_array as $key => $addon ) { ?>
						<div class="cc-addon">
							<span class="dashicons <?php echo esc_html( $addon['icon'] ); ?>"></span>
							<h4 class="addon-title"><?php echo esc_html( $addon['title'] ); ?></h4>
							<p class="addon-description"><?php echo esc_html( $addon['description'] ); ?></p>
							<?php if ( 'false' == $addon['activated'] ) { ?>
								<a class="button addon-button" href="<?php echo $addon['btn_link']; ?>" target="_blank"><?php echo esc_html( $addon['btn_title'] ); ?></a>
							<?php } else { ?>
								<?php if ( 'activated' === $addon['license'] ) { ?>
									<span class="active-addon-btn"><?php esc_html_e( 'Activated', 'caddy' ); ?></span>
								<?php } else { ?>
									<span class="installed-addon-btn"><?php esc_html_e( 'Installed', 'caddy' ); ?></span>
								<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Caddy header links html
	 */
	public function caddy_header_links_html() {
		?>
		<div class="cc-header-links">
			<a href="?page=caddy"><?php echo esc_html( __( 'Settings', 'caddy' ) ); ?></a>
			| <a href="https://usecaddy.com/docs/?utm_source=caddy-plugin&amp;utm_medium=plugin&amp;utm_campaign=plugin-links"><?php echo esc_html( __( 'Documentation', 'caddy' ) ); ?></a>
			| <a href="https://wordpress.org/support/plugin/caddy/reviews/#new-post" target="_blank"><?php echo esc_html( __( 'Leave a Review', 'caddy' ) ); ?></a>
			| <a href="?page=caddy-addons"><?php echo esc_html( __( 'Get Add-ons', 'caddy' ) ); ?></a>
			<?php
			$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );

			if ( isset( $caddy_license_status ) && 'valid' === $caddy_license_status ) {
				?>
				| <a href="?page=caddy-licenses"><?php echo esc_html( __( 'Licenses', 'caddy' ) ); ?></a>
				<?php
			} ?>
			<?php
			$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );

			if ( ! isset( $caddy_license_status ) || 'valid' !== $caddy_license_status ) {
				?>
				| <a href="https://www.usecaddy.com" target="_blank"><?php echo esc_html( __( 'Upgrade to Premium', 'caddy' ) ); ?></a>
				<?php
			} ?>
		</div>
		<?php
	}

	/**
	 * Renders the Caddy Deactivation Survey HTML
	 * Note: only for internal use
	 *
	 * @since 1.8.3
	 */
	public function caddy_load_deactivation_html() {
		$current_user       = wp_get_current_user();
		$current_user_email = $current_user->user_email;
		?>
		<div id="caddy-deactivation-survey-wrap" style="display:none;">
			<div class="cc-survey-header">
				<h2 id="deactivation-survey-title">
					<?php esc_html_e( 'We are sad to see you go :( If you have a moment, please let us know how we can improve', 'caddy' ); ?>
				</h2>
			</div>
			<form class="deactivation-survey-form" method="POST">
				<div class="cc-survey-reasons-wrap">

					<div>
						<label class="caddy-field-description">
							<input type="radio" name="caddy-survey-radios" value="1" required>
							<span><?php esc_html_e( "It's missing a specific feature", 'caddy' ); ?></span>
						</label>
					</div>

					<div>
						<label class="caddy-field-description">
							<input type="radio" name="caddy-survey-radios" value="2" required>
							<span><?php esc_html_e( "It's not working", 'caddy' ); ?></span>
						</label>
					</div>

					<div>
						<label class="caddy-field-description">
							<input type="radio" name="caddy-survey-radios" value="3" required>
							<span><?php esc_html_e( "It's not what I was looking for", 'caddy' ); ?></span>
						</label>
					</div>

					<div>
						<label class="caddy-field-description">
							<input type="radio" name="caddy-survey-radios" value="4" required>
							<span><?php esc_html_e( 'It did not work as I expected', 'caddy' ); ?></span>
						</label>
					</div>

					<div>
						<label class="caddy-field-description">
							<input type="radio" name="caddy-survey-radios" value="5" required>
							<span><?php esc_html_e( 'I found a better plugin', 'caddy' ); ?></span>
						</label>
					</div>
					
					<div>
						<label class="caddy-field-description">
							<input type="radio" name="caddy-survey-radios" value="6" required>
							<span><?php esc_html_e( "It's a temporary deactivation", 'caddy' ); ?></span>
						</label>
					</div>
					
					<div>
						<label class="caddy-field-description">
							<input type="radio" name="caddy-survey-radios" value="7" required>
							<span><?php esc_html_e( 'Something else', 'caddy' ); ?></span>
						</label>
					</div>
				</div>
				<div class="cc-survey-extra-wrap">
					<div class="caddy-survey-extra-field" style="display: none;">
						<textarea name="user-reason" class="widefat user-reason" rows="6" placeholder="<?php esc_html_e( "Please explain", 'caddy' ); ?>"></textarea>
						<p><input type="checkbox" name="caddy-contact-for-issue" class="caddy-contact-for-issue"
						          value="cc-contact-me"><?php esc_html_e( "I would like someone to contact me and help resolve my issue", 'caddy' ); ?></p>
						<p><?php
							printf(
								'%1$s %2$s %3$s',
								__( "By submitting this you're allowing Caddy to collect and send some basic site data to troubleshoot problems & make product improvements. Read our ", 'caddy' ),
								'<a href="https://usecaddy.com/privacy-policy" target="_blank">privacy policy</a>.',
								__( ' for more info.', 'caddy' )
							);
							?></p>
					</div>
					<input type="hidden" name="current-user-email" value="<?php echo $current_user_email; ?>">
					<input type="hidden" name="current-site-url" value="<?php echo esc_url( get_bloginfo( 'url' ) ); ?>">
					<input type="hidden" name="caddy-export-class" value="Caddy_Tools_Reset_Stats">
				</div>
				<div class="cc-survey-footer">
					<a class="cc-skip-deactivate-button" href="#"><?php esc_html_e( 'Skip and Deactivate', 'caddy' ); ?></a>
					<div class="cc-deactivate-buttons">
						<a class="button-secondary cc-cancel-survey"><?php esc_html_e( 'Cancel', 'caddy' ); ?></a>
						<input type="submit" class="button button-primary" value="<?php esc_html_e( 'Submit and Deactivate', 'caddy' ); ?>">
					</div>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Submit Deactivation Form Data
	 * Note: only for internal use
	 *
	 * @since 1.8.3
	 */
	public function caddy_submit_deactivation_form_data() {
		//Check nonce
		if ( ! wp_verify_nonce( $_POST['nonce'], 'cc_admin_nonce' ) ) {
			return;
		}

		global $wp_version;
		$current_user          = wp_get_current_user();
		
		// Get the 'popUpSelectedReason' parameter from the POST request
		$raw_popup_selected_reason = filter_input(INPUT_POST, 'popUpSelectedReason', FILTER_DEFAULT);
		
		// Sanitize the 'popUpSelectedReason' parameter
		$popup_selected_reason = sanitize_text_field($raw_popup_selected_reason);
		
		// Get the 'deactivationReason' parameter from the POST request
		$raw_deactivation_reason = filter_input(INPUT_POST, 'deactivationReason', FILTER_DEFAULT);
		
		// Sanitize the 'deactivationReason' parameter
		$deactivation_reason = sanitize_text_field($raw_deactivation_reason);
		
		// Get the 'contactMeCheckbox' parameter from the POST request
		$raw_contact_me_checkbox = filter_input(INPUT_POST, 'contactMeCheckbox', FILTER_DEFAULT);
		
		// Sanitize the 'contactMeCheckbox' parameter
		$contact_me_checkbox = sanitize_text_field($raw_contact_me_checkbox);

		$mail_to      = 'success@usecaddy.com';
		$mail_subject = __( 'Caddy Deactivation Survey Response', 'caddy' );
		$mail_body    = sprintf( __( 'WordPress website URL: %s', 'caddy' ), esc_url( site_url() ) ) . '<br>';
		$mail_body    .= sprintf( __( 'WordPress version: %s', 'caddy' ), esc_html( $wp_version ) ) . '<br>';
		$mail_body    .= sprintf( __( 'The plugin version: %s', 'caddy' ), esc_html( CADDY_VERSION ) ) . '<br>';
		$mail_body    .= sprintf( __( 'Selected Deactivation Reason: %s', 'caddy' ), esc_html( $popup_selected_reason ) ) . '<br>';
		$mail_body    .= sprintf( __( 'Deactivation Reason Text: %s', 'caddy' ), esc_html( $deactivation_reason ) ) . '<br>';

		if ( 'yes' === $contact_me_checkbox ) {
			$first_name = $current_user->first_name;
			$last_name  = $current_user->last_name;
			$full_name  = $first_name . ' ' . $last_name;
			$mail_body  .= sprintf( __( 'User display name: %s', 'caddy' ), esc_html( $full_name ) ) . '<br>';
			$mail_body  .= sprintf( __( 'User email: %s', 'caddy' ), esc_html( $current_user->user_email ) );
		}

		$mail_headers = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $mail_to, $mail_subject, $mail_body, $mail_headers );

		wp_die();
	}
}
