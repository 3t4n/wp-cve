<?php
/**
 * Sibs Payments Deregister Form
 *
 * The file is for displaying the Sibs deregister form
 * Copyright (c) SIBS
 *
 * @package     Sibs/Templates
 * @located at  /template/ckeckout/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

?>

<h2 class="header-title"><?php echo esc_attr( __( 'FRONTEND_MC_DELETE', 'wc-sibs' ) ); ?></h2>

<div class="box-unreg">
	<form action="<?php echo esc_attr( $url_config['return_url'] ); ?>" method="post">
		<p class="text-unreg"><?php echo esc_attr( __( 'FRONTEND_MC_DELETESURE', 'wc-sibs' ) ); ?></p>
		<a class="btnCustom btnLink button-primary" href="<?php echo esc_attr( $url_config['cancel_url'] ); ?>"><?php echo esc_attr( __( 'FRONTEND_BT_CANCEL', 'wc-sibs' ) ); ?></a>
		<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_BT_CONFIRM', 'wc-sibs' ) ); ?></button>
	</form>
</div>

