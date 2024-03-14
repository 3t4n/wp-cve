<?php
/*
Plugin Name: Popup Confirmation for Gravity Forms
Description: Display Gravity Forms confirmation messages in a modal popup.
Version: 1.2.0
Author: Altitude Media
Author URI: https://www.altitudemedia.com.au/?ref=gravityforms-popup-confirmation
License: GPL2
Requires PHP: 5.5
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Making sure Gravity Form is active
register_activation_hook( __FILE__, 'pcgf_activation_hook' );
function pcgf_activation_hook() {
	if (!class_exists('GFForms') ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'Sorry, you can\'t activate unless you have Gravity Forms installed. ' );
	}

}

// injecting our custom form fields for each Gravity Form Confirmation setting page
//add_filter( 'gform_confirmation_ui_settings', 'pcgf_confirmation_setting_fields', 10, 3 );
add_filter( 'gform_confirmation_settings_fields', 'pcgf_confirmation_setting_fields', 10, 3 );
function pcgf_confirmation_setting_fields( $fields, $confirmation, $form ) {

	$fields[0]['fields'][] = array( 'type' => 'checkbox', 'name' => 'gform_modal_confirmation', 'choices' => array( array( 'label' => 'Display confirmation text in a modal popup', 'name' => 'display-popup', 'default_value' => 1 ) ) );
	$fields[0]['fields'][] = array( 'type' => 'textarea', 'name' => 'gform_modal_script', 'placeholder' => 'Additional script to fire when confirmation popup is loaded' );
   
    return $fields;
}

// Display confirmation popup in frontend
add_filter( 'gform_confirmation', 'pcgf_confirmation_loaded', 10, 4 );
function pcgf_confirmation_loaded( $confirmation, $form, $entry, $ajax ) {

	foreach ($form['confirmations'] as $confirm) {

		if( ( $confirm['type'] == "message" ) && ( $confirm['display-popup'] == 1 ) ){
			// get the required overlay styles
			$overlay = pcgf_confirmation_overlay();

			// get additional custom scripts to fire if available
			if( isset( $confirm['gform_modal_script'] ) && !empty( $confirm['gform_modal_script'] ) ){
				$overlay .= "<script>" . $confirm['gform_modal_script'] . "</script>";
			}

			
			return sprintf( '<div id="gform-modal-notification">
								%s
								<a class="button" href="#">&nbsp;</a>
							</div>
							%s', 
							$confirm["message"], 
							$overlay );
		}
		else{
			return $confirmation;
		}

	}
}

function pcgf_confirmation_overlay() {

	$html = '<style type="text/css">
				#gform_confirmation_overlay {
					background: #000;
					background: rgba(0, 0, 0, 0.8);
					display: block;
					float: left;
					height: 100%;
					position: fixed;
					top: 0; left: 0;
					width: 100%;
					z-index: 99;
				}

				#gform-modal-notification {
			        background: #fff;
				    border-radius: 3px;
				    display: block;
				    margin: auto;
				    max-width: 800px;
				    padding: 40px 40px;
				    position: absolute;
				    top: 50%;
				    left: 0;
				    right: 0;
				    text-align: center;
				    width: 90%;
				    z-index: 101;
				    height: auto;
				    transform: translateY(-50%);
				    overflow: visible;
				}

				#gform-modal-notification .button {
					font-size: 24px;
				    margin: 0;
				    position: absolute;
				    padding: 0;
				    right: -32px;
				    font-weight: bold;
				    line-height: 1;
				    top: 0;
				}

				#gform-modal-notification .button:hover{
					background-color: transparent;
					opacity: 0.8;
				}

				#gform-modal-notification .button:before{
					content: "x";
				}

				@media only screen and (max-width: 767px){
					#gform-modal-notification {
						max-width: 90%;
					}
				}
			}

	     </style>';

	$html .= '<div id="gform_confirmation_overlay"></div>';
	$html .= '<script type="text/javascript">
					(function($){
						$("body").addClass("message-sent");
						$("#gform-modal-notification a").click(function() {
							$("#gform_confirmation_overlay, #gform-modal-notification").fadeOut("normal", function() {
								$(this).remove();
							});
						});
					})(jQuery);

			</script>';

	return $html;
}


function pcgf_confirmation_settings_css(){
	return "<style type='text/css'>
				.gform_modal_confirmation_container .input-container{
					margin-bottom: 1.5em;
				}
				.gform_modal_confirmation_container #gform_modal_script{
					font-family: 'Courier', serif;
					width: 100%;
				}
			</style>";
}

function pcgf_confirmation_settings_js(){
	return "<script type='text/javascript'>
				jQuery(document).ready(function($){
					
					if( $('#confirmation_edit_form input[name=\"form_confirmation\"]:checked').val() == 'message' ){
						$('.gform_modal_confirmation_container').show();
					}

					$('#confirmation_edit_form input[name=\"form_confirmation\"]').on('change', function(){
						if( $(this).val() == 'message' ){
							$('.gform_modal_confirmation_container').show();
						}
						else{
							$('.gform_modal_confirmation_container').hide();	
						}
					});
				});
			</script>";
}