<?php
/*
Plugin Name: BuddyPress Simple Terms And Conditions
Plugin URI: https://florianmai.de/plugins/plugin-buddypress-simple-terms-and-conditions/
Description: Adds an opt-in checkbox to the registration form for accepting the terms and conditions
Version: 1.3
Author: Florian Mai
Author URI: https://florianmai.de
Text Domain: bp-simple-terms-and-conditions
Domain Path: /lang
Requires at least: WordPress 4.4, BuddyPress 2.4.3
License: GPL2
*/
/* Make sure that the plugin is not accessed directly */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Language functions. Textdomain is bp-simple-terms-and-conditions */
function tandc_load_textdomain() {
    load_plugin_textdomain( 'bp-simple-terms-and-conditions', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'tandc_load_textdomain' );

/* WordPress Admin functions */
function tandc_init() {
	$tandc_headline		=	__('Terms and conditions', 'bp-simple-terms-and-conditions');
	$tandc_description	=	__('In order to sign up to this service, you have to read and agree to our <a href="http://example.com" target="_blank">terms and conditions</a>.', 'bp-simple-terms-and-conditions');
	$tandc_checkboxtext	=	__('Yes, I have read and agree to the terms and conditions.', 'bp-simple-terms-and-conditions');
	$tandc_error			=	__('You have to agree to the terms and conditions to proceed.', 'bp-simple-terms-and-conditions');
	$tandc_style			=	'clear:both; float:left; margin-top:30px;';

	register_setting(	'tandc',						'tandc_headline'		);
	register_setting( 'tandc',						'tandc_description'	);
	register_setting( 'tandc',						'tandc_checkboxtext'	);
	register_setting( 'tandc',						'tandc_style'			);
	register_setting( 'tandc',						'tandc_error'			);
	add_option( 		'tandc_headline',			$tandc_headline		);
	add_option( 		'tandc_description',		$tandc_description	);
	add_option( 		'tandc_checkboxtext',	$tandc_checkboxtext	);
	add_option( 		'tandc_error',				$tandc_error			);
	add_option( 		'tandc_style',				$tandc_style			);
}
add_action('admin_init', 'tandc_init' );

function tandc_register_options_page() {
	add_options_page(__('Simple Terms and Conditions', 'bp-simple-terms-and-conditions'), __('Simple Terms and Conditions', 'bp-simple-terms-and-conditions'), 'manage_options', 'bp-tandc', 'tandc_options_page');
}
add_action('admin_menu', 'tandc_register_options_page');

function tandc_options_page() {
	include (plugin_dir_path( __FILE__ ).'tandc-options-template.php');
}


/* Load the terms and conditions box on the registration page */
add_action('bp_signup_validate', 'tandc_check_input');
add_action('bp_before_registration_submit_buttons', 'tandc_display_checkbox',1,1);

function tandc_check_input(){
	global $bp;
	$tandc_checked = $_POST['tandc_checked'];
	if ($tandc_checked < 1) {
		$bp->signup->errors['custom_field'] = get_option('tandc_error');
	}
	return;
}
 
 
function tandc_display_checkbox(){ ?>
	<div id="tandc-checkbox" class="register-section" style="<?php echo get_option('tandc_style');?>">
	<div id="tandc-checkbox-container" class="register-section">
	<h3><?php echo get_option('tandc_headline');?></h3>
	<?php echo get_option('tandc_description');?>
	</div>

	<?php do_action( 'bp_custom_field_errors' ); ?>
	<input id="tandc_checkbox_hidden" type="hidden" value="0" name="tandc_checked" />
	<input type="checkbox" name="tandc_checked" id="tandc_checkbox" value="1" /> <strong><?php echo get_option('tandc_checkboxtext');?></strong>
 	</div>
<?php } ?>