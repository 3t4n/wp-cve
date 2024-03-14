<?php
/**
 * Login page
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_archive() && ( empty( $_SESSION ) || empty( $_SESSION['upstream'] ) || empty( $_SESSION['upstream']['user_id'] ) ) && ! is_user_logged_in() ) {
	wp_redirect( wp_login_url( get_post_type_archive_link( 'project' ) ) );
	exit;
}

$header_text    = upstream_login_heading();
$plugin_options = get_option( 'upstream_general' );

$should_display_client_logo = isset( $plugin_options['login_client_logo'] ) ? (bool) $plugin_options['login_client_logo'] : false;
if ( $should_display_client_logo ) {
	$client_logo_url = upstream_client_logo();
}

$should_display_project_name = isset( $plugin_options['login_project_name'] ) ? $plugin_options['login_project_name'] : false;
if ( $should_display_project_name ) {
	$header_text .= ! empty( $header_text ) ? '<br /><small>' . esc_html( get_the_title() ) . '</small>' : esc_html( get_the_title() );
}

$login         = new UpStream_Login();
$post_data     = isset( $_POST ) ? wp_unslash( $_POST ) : array();
$nonce         = isset( $post_data['upstream_login_nonce'] ) ? $post_data['upstream_login_nonce'] : null;
$up_user_email = '';

if ( wp_verify_nonce( $nonce, 'upstream-login-nonce' ) && isset( $post_data['user_email'] ) ) {
	$up_user_email = sanitize_email( $post_data['user_email'] );
}

?>

<?php upstream_get_template_part( 'global/header.php' ); ?>

<div class="col-xs-12 col-sm-4 col-sm-offset-4 text-center">
	<?php if ( $should_display_client_logo && ! empty( $client_logo_url ) ) : ?>
		<img src="<?php echo esc_url( $client_logo_url ); ?>"/>
	<?php endif; ?>

	<div class="account-wall">
		<?php if ( ! empty( $header_text ) ) : ?>
			<header>
				<h3 class="text-center"><?php echo esc_html( $header_text ); ?></h3>
			</header>
		<?php endif; ?>

		<?php do_action( 'upstream_login_before_form' ); ?>

		<form class="loginform" action="" method="POST">
			<input type="text" class="form-control" placeholder="<?php esc_attr_e( 'Your Email', 'upstream' ); ?>"
				name="user_email" required
				value="<?php echo esc_attr( $up_user_email ); ?>" <?php echo empty( $up_user_email ) ? 'autofocus' : ''; ?> />
			<input type="password" class="form-control" placeholder="<?php esc_attr_e( 'Password', 'upstream' ); ?>"
				name="user_password" required <?php echo ! empty( $up_user_email ) ? 'autofocus' : ''; ?> />

			<input type="hidden" name="upstream_login_nonce"
				value="<?php echo esc_attr( wp_create_nonce( 'upstream-login-nonce' ) ); ?>"/>

			<input type="submit" class="btn btn-lg btn-primary btn-block" value="<?php esc_attr_e( 'Sign In', 'upstream' ); ?>"
				name="login"/>
		</form>

		<?php do_action( 'upstream_login_after_form' ); ?>

		<div class="text-center">
			<?php echo esc_html( upstream_login_text() ); ?>
		</div>
	</div>

	<?php if ( $login->has_feedback_message() ) : ?>
		<div class="alert alert-danger">
			<?php echo esc_html( $login->get_feedback_message() ); ?>
		</div>
	<?php endif; ?>
</div>
