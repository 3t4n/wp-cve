<?php
/**
 * Include files for wp-admin
 *
 * @package CryptoWoo
 * @subpackage Admin
 */

// Load the embedded Redux Framework.
// TODO: Stop embedding Redux and use TGM to require it instead?
if ( ! class_exists( ReduxFramework::class ) ) {
	if ( file_exists( dirname( __FILE__ ) . '/redux-framework/framework.php' ) ) {
		include_once dirname( __FILE__ ) . '/redux-framework/framework.php';
	}
}

// Only load things using Redux if a compatible Redux version is active. TODO: Add compatibility Redux 3 and below?
if ( ! method_exists( Redux::class, 'set_field' ) ) {
	return;
}

// Load the theme/plugin options.
if ( file_exists( dirname( __FILE__ ) . '/options-init.php' ) ) {
	include_once dirname( __FILE__ ) . '/options-init.php';
}

// Load Redux extensions.
if ( file_exists( dirname( __FILE__ ) . '/redux-extensions/extensions-init.php' ) ) {
	include_once dirname( __FILE__ ) . '/redux-extensions/extensions-init.php';
}

// Load Setup Wizard.
if ( file_exists( dirname( __FILE__ ) . '/class-cw-setup-wizard.php' ) ) {
	include_once dirname( __FILE__ ) . '/class-cw-setup-wizard.php';
}
