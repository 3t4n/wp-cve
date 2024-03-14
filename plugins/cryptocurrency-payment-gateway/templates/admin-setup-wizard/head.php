<?php
/**
 * Setup wizard head template.
 *
 * @package    CryptoWoo
 * @subpackage CryptoWoo\Admin\SetupWizard
 */

defined( 'ABSPATH' ) || exit;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta name="viewport" content="width=device-width"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>
		<?php esc_html_e( 'Setup Wizard - Cryptocurrency Payment Gateway by CryptoWoo', 'cryptowoo' ); ?>
	</title>
	<?php wp_print_scripts( 'cw-setup-wizard' ); ?>
	<?php wp_print_styles( 'cw-setup-wizard' ); ?>
	<?php do_action( 'admin_print_styles' ); ?>
	<?php do_action( 'admin_head' ); ?>
</head>
<body class="cw-setup-wizard cw-setup-wizard-page" id="cw-setup-wizard-body">
<noscript>
	<p style="color:red; font-size:medium; text-align:center">
		This setup wizard will not work without javascript. Enable Javascript and reload the page.
	</p>
</noscript>
