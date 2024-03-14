<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Incude emails to user.
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-user/receipt-email.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-user/refund-email.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-user/renewal-reminder-email.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-user/cancellation-notice-email.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-user/renewal-failed-notice-email.php';

// Incude emails to admin.
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-admin/receipt-email.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-admin/refund-email.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails-to-admin/cancellation-notice-email.php';

/**
 * Get the standalone HTML for an email
 *
 * @param  string $body The unique body of the email being sent.
 * @since  1.0.0.
 * @return bool
 */
function church_tithe_wp_get_html_email( $body ) {

	ob_get_clean();
	ob_start();

	$saved_settings = get_option( 'church_tithe_wp_settings' );
	$image          = church_tithe_wp_aq_resize( church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_image' ), 100, 100 );

	?>
	<div class="church-tithe-wp-receipt">
		<div class="church-tithe-wp-payment-confirmation" style="
		width: 100%;
		box-sizing: border-box;
		color: #333;
		background-color: #f5f5f7;
		border-top: 1px solid #ffffff;
		border-radius: 6px;
		text-align: left;
		border: 1px solid rgba(0,0,0,.1);
		z-index: 2147481000;
		transition-delay: .001s;
		transition: all 300ms ease-in-out,-webkit-transform 300ms ease-in-out;
		filter: drop-shadow(0px 2px 2px rgba(34,36,38,.15));
		-webkit-filter: drop-shadow(0px 2px 2px rgba(34,36,38,.15));"
		>
		<header class="church-tithe-wp-header" role="banner" style="
		background-color: #e8e9eb;
		border-bottom: 1px solid #d9d9d9;
		border-radius: 6px 6px 0px 0px;
		padding: 25px 10px 25px 10px;"
		>
		<?php if ( ! empty( $image ) ) { ?>
			<div class="church-tithe-wp-logo" style="
			margin-bottom: 25px;
			text-align: center;
		display: inline;
			"
			>
			<div class="church-tithe-wp-header-logo-container" style="
			box-sizing:border-box;
			top: 0px;
			right: 0;
			left: 0;
			width: 70px;
			height: 70px;
			margin: 2px auto;"
			>
			<div class="church-tithe-wp-header-logo-img" style="
			box-sizing:border-box;
			border: 3px solid #fff;
			width: 70px;
			height: 70px;
			border-radius: 100%;
			box-shadow: 0 0 0 1px rgba(0,0,0,.18), 0 2px 2px 0 rgba(0,0,0,.08);
			top: 0;
			left: 0;
			z-index: 1;
			background-size: cover;
			background-position: center;
			clip-path: circle(100%);

			background-image: url(<?php echo esc_url( $image ); ?>);"
			><img
				src="<?php echo esc_url( $image ); ?>"
				style="
				border: 3px solid #ffffff;
			width: 64px;
			height: 64px;
			border-radius: 100%;
			box-shadow: inset 0 1px 0 0 hsla(0,0%,100%,.1);
			position: absolute;
			top: 3px;
			left: 3px;
			z-index: 2;
				"
			/></div>
		</div>
	</div>
	<?php } ?>
	<h1 class="church-tithe-wp-header-title" style="
		margin: 0;
		text-align: center;
		font-size: 17px;
		line-height: 18px;
		font-weight: 700;
		color: #000;
		text-shadow: 0 1px 0 #fff;"
	><?php echo esc_textarea( get_bloginfo( 'name' ) ); ?></h1>
	</header>
	<div class="church-tithe-wp-payment-confirmation-view" style="padding: 36px;">
		<div class="church-tithe-wp-payment-confirmation">
			<?php echo wp_kses( $body, wp_kses_allowed_html( 'post' ) ); ?>
		</div>
	</div>
	</div>
	</div>

	<?php
	return ob_get_clean();

}
