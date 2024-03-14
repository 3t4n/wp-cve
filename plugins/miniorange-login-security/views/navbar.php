<?php
/**
 * Navigation bar of the plugin dashboard
 *
 * @package miniorange-login-security/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	$user         = wp_get_current_user();
	$onprem_admin = get_site_option( 'mo2f_onprem_admin' );
	$user_roles   = (array) $user->roles;
	$is_onprem    = get_site_option( 'is_onprem' );
		$flag     = 0;
foreach ( $user_roles as $user_role ) {
	if ( get_site_option( 'mo2fa_' . $user_role ) === '1' ) {
		$flag = 1;
	}
}
if ( $shw_feedback ) {
	echo wp_kses(
		Momls_Wpns_Messages::momls_show_message( 'FEEDBACK' ),
		array(
			'div'    => array(
				'class' => array(),
			),
			'p'      => array(
				'class' => array(),
			),
			'i'      => array(),
			'button' => array(
				'class' => array(),
			),
		)
	);
}

	echo '<div class="mo2f-header" id="momls_wrap" >
				<div><img  style="float:left;margin-top:5px;" src="' . esc_url( $logo_url ) . '"></div>
				<h1>
					<a class="button button-secondary button-large" href="' . esc_url( $profile_url ) . '">My Account</a>
					<a class="license-button button button-secondary button-large" href="' . esc_url( $license_url ) . '" target="_blank">See Plans and Pricing</a>
				</h1>			
		</div>';
?>

		<br>			
