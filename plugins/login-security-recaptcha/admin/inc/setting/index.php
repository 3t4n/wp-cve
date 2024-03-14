<?php
defined( 'ABSPATH' ) || die();

$menu_tab = ( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'captcha';

$menu_tabs = array(
	'captcha'    => esc_html__( 'Captcha', 'login-security-recaptcha' ),
	'error_logs' => esc_html__( 'Error Logs', 'login-security-recaptcha' ),
	'reset'      => esc_html__( 'Reset', 'login-security-recaptcha' ),
);
?>

<div class="wrap stlsr">
	<?php if ( ! class_exists( 'STLSP_Login_Security_Pro' ) ) { ?>
	<div class="stlsr-pro-main">
		<a href="<?php echo esc_url( STLSR_Helper::get_pro_url() ); ?>" target="_blank" class="stlsr-pro-main-link">
			<img src="<?php echo esc_url( STLSR_PLUGIN_URL . 'assets/images/Login-Security-Pro.png' ); ?>" alt="<?php esc_attr_e( 'Upgrade to Login Security Pro', 'login-security-recaptcha' ); ?>" class="stlsr-pro-main-img">
		</a>
		<div class="stlsr-pro-main-desc">
			<div class="stlsr-pro-main-title"><?php echo wp_kses( __( 'Upgrade to <span>Pro</span> Version', 'login-security-recaptcha' ), array( 'span' => array() ) ); ?></div>
			<div class="stlsr-pro-main-btn-group">
				<a href="<?php echo esc_url( STLSR_Helper::get_pro_url() ); ?>" target="_blank" class="stlsr-pro-main-btn stlsr-pro-main-btn--highlight"><?php esc_html_e( 'Buy Now', 'login-security-recaptcha' ); ?></a>
				<a href="<?php echo esc_url( STLSR_Helper::get_pro_detail_url() ); ?>" target="_blank" class="stlsr-pro-main-btn"><?php esc_html_e( 'More Detail', 'login-security-recaptcha' ); ?></a>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="stlsr-page-heading"><?php esc_html_e( 'Login Security Captcha', 'login-security-recaptcha' ); ?></div>

	<div class="nav-tab-wrapper">
	<?php
	foreach ( $menu_tabs as $key => $value ) {
		$class = ( $menu_tab === $key ) ? ' nav-tab-active' : '';
		?>
		<a class="nav-tab<?php echo esc_attr( $class ); ?>" href="?page=stlsr_settings&tab=<?php echo esc_attr( $key ); ?>">
			<?php echo esc_html( $value ); ?>
		</a>
		<?php
	}
	?>
	</div>

	<div class="stlsr-section">
	<?php
	if ( 'captcha' === $menu_tab ) {
		require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/setting/tabs/captcha.php';
	} elseif ( 'error_logs' === $menu_tab ) {
		require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/setting/tabs/error-logs.php';
	} elseif ( 'reset' === $menu_tab ) {
		require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/setting/tabs/reset.php';
	}
	?>
	</div>
</div>
