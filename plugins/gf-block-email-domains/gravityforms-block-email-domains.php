<?php
/**
 * Plugin Name:       Gravity Forms Block Email Domains
 * Plugin URI:        http://roadwarriorcreative.com
 * Description:       Easily set a list of email domains to block on email fields in Gravity Forms.
 * Version:           1.0.2
 * Author:            Road Warrior Creative
 * Author URI:        https://roadwarriorcreative.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) or die( 'No direct file access allowed!' );

/*
Check if Gravity Forms is installed
*/
if ( class_exists( 'GFCommon' ) ) {

	/*
	Define Custiom Setting
	*/
	add_action( 'gform_field_advanced_settings', 'rwc_bed_advanced_settings', 10, 2 );
	function rwc_bed_advanced_settings( $position, $form_id ) {
		// create settings on position 425 (right after visibility)
		if ( $position == 425 ) {
			?>
			<li class="block_email_domains_setting field_setting">
				<label for="field_block_email_domains">
					<?php esc_html_e( 'Block Email Domains', 'gravityforms' ); ?>
					<?php gform_tooltip( 'form_field_block_email_domains' ) ?>
				</label>
				<input type="text" id="field_block_email_domains" class="fieldwidth-3" onkeyup="SetFieldProperty('block_email_domains', this.value);" />
			</li>
			<li class="block_email_domains_validation field_setting">
				<label for="field_block_email_domains_validation">
					<?php esc_html_e( 'Block Email Domains Validation Message', 'gravityforms' ); ?>
					<?php gform_tooltip( 'form_field_block_email_domains_validation' ) ?>
				</label>
				<input type="text" id="field_block_email_domains_validation" class="fieldwidth-3" onkeyup="SetFieldProperty('block_email_domains_validation', this.value);" />
			</li>
			<?php
		}
	}

	/*
	Add Custom Setting to Email Fields
	*/
	add_action( 'gform_editor_js', 'rwc_bed_editor_script' );
	function rwc_bed_editor_script(){
		?>
		<script type='text/javascript'>
			jQuery(document).ready(function($) {
				// adding setting to fields of type "email"
				fieldSettings.email += ', .block_email_domains_setting';
				fieldSettings.email += ', .block_email_domains_validation';
				// binding to the load field settings event to initialize the text input
				$(document).bind('gform_load_field_settings', function(event, field, form){
					$('#field_block_email_domains').val(field.block_email_domains);
					$('#field_block_email_domains_validation').val(field.block_email_domains_validation);
				});
			});
		</script>
		<?php
	}

	/*
	Custom Setting Tooltip
	*/
	add_filter( 'gform_tooltips', 'rwc_bed_add_block_domains_tooltips' );
	function rwc_bed_add_block_domains_tooltips( $tooltips ) {
		$tooltips['form_field_block_email_domains'] = "<h6>Block Email Domains</h6>Add a comma separated list of email domains to block. Example: gmail.com, yahoo.com, outlook.com";
		$tooltips['form_field_block_email_domains_validation'] = "<h6>Block Email Domains Validation Message</h6>The message that will show if a blocked email domain is entered.";
		return $tooltips;
	}

	/*
	Custom Email Field Validation
	*/
	add_filter( 'gform_field_validation', function ( $result, $value, $form, $field ) {
		if ( $field->type == 'email' ) {
			// replace white space
			$blocked_domains_string = preg_replace('/\s+/', '', strtolower($field["block_email_domains"]));
			// convert string to array
			$blocked_domains = explode(",", $blocked_domains_string);

			// check if value is an array for when a second email confirmation filed is enabled
			if(is_array($value)){
				$values = $value;
			}else{
				$values[] = $value;
			}

			if($values){
				foreach ($values as $value) {
					$domain = substr(strrchr(strtolower($value), "@"), 1);
					if($field["block_email_domains"] && in_array($domain, $blocked_domains)){
						$result['is_valid'] = false;
						if(!empty($field["block_email_domains_validation"])){
							$error_message = $field["block_email_domains_validation"];
						}else{
							$error_message = 'Sorry, '.$domain.' email addresses are not accepted on this field. Please provide another email address and try again.';
						}
						$result['message']  = empty( $field->errorMessage ) ? __( $error_message, 'gravityforms' ) : $field->errorMessage;
					}
				}
			}
		}
		return $result;
	}, 10, 4 );

}

?>