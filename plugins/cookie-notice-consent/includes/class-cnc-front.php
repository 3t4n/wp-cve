<?php

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent_Front {

	/**
	 * Constructor
	 */
	public function __construct( $instance ) {
		$this->cnc = $instance;
		// Add actions in init, since settings need to be loaded earlier
		add_action( 'init', array( $this, 'init_front' ) );
	}
	
	/**
	 * Init front
	 */
	public function init_front() {
		if( is_admin() )
			return;
		add_filter( 'body_class', array( $this, 'add_cookie_status_body_classes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_cookie_notice_consent_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_cookie_notice_consent_scripts' ) );
		// Only print notice and category code if no privacy signal was sent
		if( ! $this->privacy_signal_detected() ) {
			add_action( 'wp_footer', array( $this, 'print_cookie_notice' ), 1000 );
			$this->maybe_print_category_code();
		}
	}
	
	/**
	 * Check if client sent a privacy default and CNC should respect it
	 */
	public function privacy_signal_detected() {
		// Do Not Track
		if( $this->cnc->settings->get_option( 'general_settings', 'respect_dnt' ) ) {
			return ( isset( $_SERVER['HTTP_DNT'] ) && $_SERVER['HTTP_DNT'] === '1' );
		}
		// Global Privacy Control
		if( $this->cnc->settings->get_option( 'general_settings', 'respect_dnt' ) ) {
			return ( isset( $_SERVER['HTTP_SEC_GPC'] ) && $_SERVER['HTTP_SEC_GPC'] === '1' );
		}
		return false;
	}
	
	/**
	 * Print cookie notice
	 */
	public function print_cookie_notice() {
		$categories = $this->cnc->helper->get_active_cookie_categories();
		$cookie_consent_set = $this->cnc->helper->is_cookie_consent_set();
		ob_start();
		?>
		<div id="cookie-notice-consent" role="banner" class="cookie-notice-consent <?php echo ( $cookie_consent_set || $this->cnc->helper->is_cache_active() ) ? 'cookie-notice-consent--hidden' : 'cookie-notice-consent--visible'; ?>" aria-label="<?php _e( 'Cookie Notice & Consent', 'cookie-notice-consent' ) ?>">
			<?php do_action( 'cookie_notice_consent_before_notice_container' ); ?>
			<div class="cookie-notice-consent__container">
				<?php do_action( 'cookie_notice_consent_before_notice_text' ); ?>
				<?php if( ! empty( $this->cnc->settings->get_option( 'general_settings', 'notice_text' ) ) ) { ?>
				<div class="cookie-notice-consent__text"><?php echo wp_kses_post( $this->cnc->settings->get_option( 'general_settings', 'notice_text' ) ); ?></div>
				<?php } ?>
				<?php do_action( 'cookie_notice_consent_after_notice_text' ); ?>
				<?php do_action( 'cookie_notice_consent_before_notice_categories' ); ?>
				<?php if( ! empty( $categories ) ) { ?>
				<div class="cookie-notice-consent__categories<?php if( '1' !== $this->cnc->settings->get_option( 'design_settings', 'show_category_descriptions' ) ) { echo ' cookie-notice-consent__categories--inline'; } ?>">
					<?php foreach( $categories as $category ) { ?>
					<?php $slug = str_replace( '_', '-', $category ); ?>
					<div class="cookie-notice-consent__category cookie-notice-consent__<?php echo $slug; ?>">
						<input <?php echo 'category_essential' == $category ? 'checked="checked" disabled="disabled" ' : ''; ?>type="checkbox" name="cookie-notice-consent__<?php echo $slug; ?>__checkbox" id="cookie-notice-consent__<?php echo $slug; ?>__checkbox" data-cookie-category="<?php echo $category; ?>">
						<label for="cookie-notice-consent__<?php echo $slug; ?>__checkbox"><?php echo esc_html( $this->cnc->settings->get_option( $category, 'label' ) ); ?></label>
						<?php if( '1' === $this->cnc->settings->get_option( 'design_settings', 'show_category_descriptions' ) ) { ?>
						<p class="cookie-notice-consent__category-description"><?php echo esc_html( $this->cnc->settings->get_option( $category, 'description' ) ); ?></p>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				<?php do_action( 'cookie_notice_consent_after_notice_categories' ); ?>
				<?php do_action( 'cookie_notice_consent_before_notice_buttons' ); ?>
				<div class="cookie-notice-consent__buttons">
					<?php do_action( 'cookie_notice_consent_notice_buttons_open' ); ?>
					<?php if( ! empty( $this->cnc->settings->get_option( 'general_settings', 'accept_button_label' ) ) ) { ?>
					<a href="#cookies-accepted" class="cookie-notice-consent__button cookie-notice-consent__accept-button" id="cookie-notice-consent__accept-button" aria-label="<?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'accept_button_label' ) ); ?>"><?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'accept_button_label' ) ); ?></a>
					<?php } ?>
					<?php if( ( count( $categories ) > 1 || ( count( $categories ) == 1 && ! in_array( 'category_essential', $categories ) ) ) && ! empty( $this->cnc->settings->get_option( 'general_settings', 'confirm_choice_button_label' ) ) ) { ?>
					<a href="#cookies-confirmed" class="cookie-notice-consent__button cookie-notice-consent__confirm-choice-button" id="cookie-notice-consent__confirm-choice-button" aria-label="<?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'confirm_choice_button_label' ) ); ?>"><?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'confirm_choice_button_label' ) ); ?></a>
					<?php } ?>
					<?php if( ! empty( $this->cnc->settings->get_option( 'general_settings', 'reject_button_label' ) ) ) { ?>
					<a href="#cookies-rejected" class="cookie-notice-consent__button cookie-notice-consent__reject-button" id="cookie-notice-consent__reject-button" aria-label="<?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'reject_button_label' ) ); ?>"><?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'reject_button_label' ) ); ?></a>
					<?php } ?>
					<?php if( function_exists( 'get_privacy_policy_url' ) && ! empty( get_privacy_policy_url() ) && ! empty( $this->cnc->settings->get_option( 'general_settings', 'privacy_policy_button_label' ) ) ) { ?>
					<a href="<?php echo get_privacy_policy_url(); ?>" target="_blank" class="cookie-notice-consent__button cookie-notice-consent__privacy-policy-button" id="cookie-notice-consent__privacy-policy-button" aria-label="<?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'privacy_policy_button_label' ) ); ?>"><?php echo esc_html( $this->cnc->settings->get_option( 'general_settings', 'privacy_policy_button_label' ) ); ?></a>
					<?php } ?>
					<?php do_action( 'cookie_notice_consent_notice_buttons_close' ); ?>
				</div>
				<?php do_action( 'cookie_notice_consent_after_notice_buttons' ); ?>
			</div>
			<?php do_action( 'cookie_notice_consent_after_notice_container' ); ?>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'cookie_notice_consent_output', $output );
	}
	
	/**
	 * Enqueue frontend scripts
	 */
	public function enqueue_cookie_notice_consent_scripts() {
		
		// Enqueue frontend script
		wp_enqueue_script( 'cookie-notice-consent', plugins_url( 'js/front.min.js', dirname( __FILE__ ) ), array(), CNC_VERSION, !apply_filters( 'cookie_notice_consent_print_plugin_script_in_head', false ) );
		
		// Add arguments to the frontend script
		$cnc_args = array(
			'uuid'				=> $this->cnc->helper->is_cache_active() ? 0 : wp_generate_uuid4(),
			'reload'			=> (int) $this->cnc->settings->get_option( 'general_settings', 'reload' ),
			'cache'				=> (int) $this->cnc->helper->is_cache_active(),
			'secure'			=> (int) is_ssl(),
			'log'				=> 0,
			'cookieExpiration'	=> apply_filters( 'cookie_notice_consent_cookie_expiration', 30 ),
			'revokeAll'			=> (int) $this->cnc->settings->get_option( 'consent_settings', 'revoke_delete_all' ),
			'revokeNotice'		=> __( 'Your consent settings have been reset.', 'cookie-notice-consent' ) . ( $this->cnc->settings->get_option( 'consent_settings', 'revoke_delete_all' ) ? ( ' ' . __( 'Additionally, any cookies set for this domain have been cleared.', 'cookie-notice-consent' ) ) : '' ),
		);
		
		// Add additional arguments if logging is on
		if( $this->cnc->settings->get_option( 'consent_settings', 'log_consents' ) ) {
			$cnc_args = array_merge( $cnc_args, array(
				'log'				=> 1,
				'ajax_url'			=> admin_url( 'admin-ajax.php' ),
				'ajax_nonce'		=> wp_create_nonce( 'cookie_notice_consent' ),
				'remote_addr'		=> filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP ),
				'http_user_agent'	=> $_SERVER['HTTP_USER_AGENT'],
			) );
		}
		
		// Inline arguments
		wp_add_inline_script( 'cookie-notice-consent', 'var cncArgs = ' . json_encode( $cnc_args ), 'before' );
		
	}
	
	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_cookie_notice_consent_styles() {
		
		// Enqueue frontend styles, theme if active
		$theme = $this->cnc->settings->get_option( 'design_settings', 'theme' );
		wp_enqueue_style( 'cookie-notice-consent' . ( $theme != 'default' ? '-theme-' . $theme : '' ), plugins_url( 'css/front' . ( $theme != 'default' ? '-theme-' . $theme : '' ) . '.min.css', dirname( __FILE__ ) ), array(), CNC_VERSION );
		
		// Add inline theme color if active
		if( !empty( $color_accent = $this->cnc->settings->get_option( 'design_settings', 'color_accent' ) ) ) {
			$inline_style = "#cookie-notice-consent, .cookie-notice-consent__embed { --cnc-color-accent: {$color_accent} }";
			wp_add_inline_style( 'cookie-notice-consent' . ( $theme != 'default' ? '-theme-' . $theme : '' ), $inline_style );
		}
		
	}
	
	/**
	 * Add new body classes based on cookie status
	 */
	public function add_cookie_status_body_classes( $classes ) {
		if( is_admin() )
			return $classes;
		$classes[] = $this->cnc->helper->is_cookie_consent_set() ? 'cookie-consent-set' : 'cookie-consent-not-set';
		if( $this->privacy_signal_detected() )
			$classes[] = 'privacy-signal';
		return $classes;
	}
	
	/**
	 * Print the defined category code in head or footer, if applicable
	 */
	public function maybe_print_category_code() {
		$cookie_categories = $this->cnc->helper->get_registered_cookie_categories();
		foreach( array_keys( $cookie_categories ) as $category ) {
			if( ! $this->cnc->helper->is_cookie_category_accepted( $category ) )
				continue;
			$category_code = apply_filters( 'cookie_notice_consent_' . $category . '_code', html_entity_decode( trim( wp_kses( $this->cnc->settings->get_option( $category, 'code' ), $this->cnc->helper->get_allowed_html() ) ) ) );
			if( empty( $category_code ) )
				continue;
			$category_hook = apply_filters( 'cookie_notice_consent_print_' . $category . '_code_in_head', false ) ? 'wp_head' : 'wp_footer';
			add_action( $category_hook, function() use( $category_code ) { echo $category_code; } );
		}
	}
	
}