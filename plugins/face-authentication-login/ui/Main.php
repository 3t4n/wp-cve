<?php

/**
* Main ui for the plugin
*/
namespace DataPeen\FaceAuth\UI;

use DataPeen\FaceAuth\CodeMail;
use DataPeen\FaceAuth\EmailVerification;
use DataPeen\FaceAuth\Option_Names;
use DataPeen\FaceAuth\Static_UI as UI;
use DataPeen\FaceAuth\Options_Form as Form;
use DataPeen\FaceAuth\Options;
use DataPeen\FaceAuth\Option_Names as Oname;
use DataPeen\FaceAuth\Config;
use DataPeen\FaceAuth\GoogleAuthenticator;
use DataPeen\FaceAuth\UserOptions;
use DataPeen\FaceAuth\Helpers;

class Main
{
    public static function ui()
    {

	    /**
	     * the option i∑s unique per user so we need to append user name after the common option name
	     * One user will have one unique option name
	     */

	    $user = wp_get_current_user();
            
		$user_option_ui = UserOptions::get_form_ui($user);
		$user_option = UserOptions::get_option($user);
		$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);

	    $common_option_ui = new Form(Config::COMMON_OPTION_NAME, Options::get_the_only_option_id(Config::COMMON_OPTION_NAME));

	    $qr_image = GoogleAuthenticator::get_image_url($user->ID);

	    $site_verified  = Helpers::is_site_verified();

		$tab_pin = array(
			UI::open_form(false),
			UI::card_section('Select methods to get PIN', array(
				UI::label('','Select methods to get your PIN after face confirmation', false),
				$user_option_ui->multiple_checkbox(Oname::PIN_METHODS,
					array(
						'email' => 'Email',
						'google_authenticator' => 'Time based One-Time password (Google Authenticator)',
					),
					false,
					'',
					'block',
					false
				)
			), '', false),

			UI::card_section('Setup your email', array(
				UI::label(Oname::EMAIL_TO_RECEIVE_TOKEN, __('Enter the email you want to receive PIN token'), false),
				$user_option_ui->input_field(Oname::EMAIL_TO_RECEIVE_TOKEN, 'text', '', false, 250, false),

			),'bc-uk-width-1-1', false),

			UI::card_section('Setup your Google Authenticator', array(
				UI::label('', 'Scan the QR code below to your Authenticator app', false),

				sprintf('<div><img src="%1$s"></div>', $qr_image),
				//hide this field if authenticator has been verified
				UI::label('', 'Enter code from authenticator and click on Verify', false, $user_option->get_bool(Oname::AUTHENTICATOR_VERIFIED)),
				UI::flex_section(
					array(
						UI::temp_input_field('authenticator_verification_code', 'number', '', false, 200, false),
						UI::temp_hidden('authenticator_generated_key', GoogleAuthenticator::get_secret($user->ID), false),
						UI::temp_hidden('authenticator_user_id', $user->ID, false),
						UI::button(Oname::AUTHENTICATOR_VERIFICATION_BUTTON, 'Verify', false)
					), 'bc-uk-flex-left',
//					false
							$user_option->get_bool(Oname::AUTHENTICATOR_VERIFIED)

				),

			), 'bc-uk-width-1-1', false),

			$user_option_ui->setting_fields(false),
			$user_option_ui->submit_button('Save PIN settings', false),
			UI::close_form(false)
		);
		$tab_token = array(
			UI::open_form(false),

			UI::conditional(
				!$site_verified,
				array(
				UI::card_section(__('Verify your site'),
					array(
						UI::heading(__('Your site URL'), 3, false),
						UI::notice('Please copy this URL and enter it in the add your site section on datapeen.com', 'notice', false, false),
						UI::heading(get_site_url(), 4, false) ,
						UI::heading(__('Enter the token you got from datapeen dashboard'), 3, false),
						UI::flex_section(array(
							$common_option_ui->input_field(Oname::VERIFY_TOKEN, 'text', '', false, 400, false),
							UI::button('verify-token-button', 'Verify', false)
						))
					), '', false)

				),
				array(
					UI::card_section(__('Site verified!'), array(
						UI::notice(__('You site has been successfully verified'), 'info', false, false)
					), '', false)
				),
				false),



			UI::close_form(false)

		);
		$tab_face = array(
            UI::open_form(false),
            UI::card_section(__('Setup your face for authentication'), array(

			UI::label('', 'Take clear pictures of your face. Make sure no one else is in the camera', false),
			UI::line(false),
			UI::flex_section(array(
				'<div id="dp-face-camera">
					<video width="445" height="250" id="video">Video stream not available.</video>
				</div>',
				'<canvas id="canvas">
				</canvas>'
			), '', false),
			UI::flex_section(array(
				UI::button('dp-add-face', '<i class="fas fa-camera-retro"></i> ' . __('Take face image'), false),
				UI::button('dp-turn-on-camera', '<i class="fas fa-camera"></i> ' . __('Turn on camera'), false),
			), '', false),

            UI::heading('Added faces', 3, false),
			'<div class="bc-uk-flex" id="all-images"></div>',
            UI::line(false)), '', false),
			UI::close_form(false)

		);



        UI::open_root();

                UI::heading('Configure your face login', 2, true);

                UI::tabs(
                	array(

						array(
			                'title' => 'Token',
			                'content' => $tab_token
		                ),

		                array(
			                'title' => 'Face setup',
			                'content' => $tab_face
		                ),
		                array(
			                'title' => 'PIN',
			                'content' => $tab_pin
		                )

	                ),
	                true
                );

				UI::js_post_form();

        UI::close_root();
    }

}
