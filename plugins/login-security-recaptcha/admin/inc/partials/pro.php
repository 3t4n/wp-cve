<?php
defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'STLSP_Login_Security_Pro' ) ) {
	?>
	<div class="stlsr-pro">
		<div class="stlsr-pro-description">
			<div class="stlsr-pro-heading">
			<?php
			echo wp_kses(
				__( '<span class="stlsr-pro-light">Upgrade to</span> <span class="stlsr-pro-bold">Login Security <span class="stlsr-pro-bold stlsr-pro-underline">Pro</span>', 'login-security-recaptcha' ),
				array( 'span' => array( 'class' => array() ) )
			);
			?>
			</div>
			<div class="stlsr-pro-check-list-wrap">
				<ul class="stlsr-pro-check-list">
					<li><?php esc_html_e( 'Limit Login Attempts', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'Login-Logout Redirect', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'Role-Based Redirection', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'Show Recent Login History', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'Plugin Documentation Guide', 'login-security-recaptcha' ); ?></li>
				</ul>
				<ul class="stlsr-pro-check-list">
					<li><?php esc_html_e( 'Login History by Username', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'WooCommerce Login / Register', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'WooCommerce Lost Password', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'WooCommerce Checkout Form', 'login-security-recaptcha' ); ?></li>
					<li><?php esc_html_e( 'Advanced Security & More', 'login-security-recaptcha' ); ?></li>
				</ul>
			</div>
		</div>
		<div class="stlsr-pro-links">
			<a target="_blank" href="<?php echo esc_url( STLSR_Helper::get_pro_url() ); ?>" class="stlsr-pro-link stlsr-pro-bold"><?php esc_html_e( 'Buy Now', 'login-security-recaptcha' ); ?></a>
			<a target="_blank" href="<?php echo esc_url( STLSR_Helper::get_pro_detail_url() ); ?>" class="stlsr-pro-link stlsr-pro-link--alt"><?php esc_html_e( 'Learn More', 'login-security-recaptcha' ); ?></a>
		</div>
	</div>
	<?php
}
