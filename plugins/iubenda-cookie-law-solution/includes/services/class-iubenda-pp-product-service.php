<?php
/**
 * Iubenda pp product service.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PP product service.
 */
class Iubenda_PP_Product_Service extends Iubenda_Abstract_Product_Service {

	/**
	 * Accepted PP Options.
	 *
	 * @var array
	 */
	private $accepted_options = array(
		'button_style'    => array( 'white', 'black' ),
		'button_position' => array( 'automatic', 'manual' ),
	);

	/**
	 * Saving Iubenda privacy policy solution options and generate codes for languages that have Public ID
	 *
	 * @param   bool $with_generator With privacy policy generator or not.
	 */
	public function saving_pp_options( $with_generator = true ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$request_pp_option = (array) iub_array_get( $_POST, 'iubenda_privacy_policy_solution', array() );
		$pp_default_keys   = array_keys( iubenda()->defaults['pp'] );
		$pp_default_keys   = array_merge( $this->get_languages_code_keys( false ), $pp_default_keys );
		$new_pp_option     = iub_array_only( $request_pp_option, $pp_default_keys );

		$codes_statues                    = array();
		$new_pp_option['button_style']    = $this->get_only_valid_values( iub_array_get( $new_pp_option, 'button_style' ), $this->accepted_options['button_style'], iubenda()->defaults['pp']['button_style'] );
		$new_pp_option['button_position'] = $this->get_only_valid_values( iub_array_get( $new_pp_option, 'button_position' ), $this->accepted_options['button_position'], iubenda()->defaults['pp']['button_position'] );

		$privacy_policy_generator = new Privacy_Policy_Generator();
		$global_options           = iubenda()->options['global_options'];

		$languages = ( new Product_Helper() )->get_languages();
		// loop on iubenda->>language.
		foreach ( $languages as $lang_id => $v ) {
			$code = iub_array_get( $new_pp_option, "code_{$lang_id}" );
			if ( empty( $code ) && $with_generator ) {
				// getting privacy policy id from saved QG response.
				$privacy_policy_id = sanitize_key( iub_array_get( $global_options, "public_ids.{$lang_id}" ) );

				if ( empty( $privacy_policy_id ) ) {
					continue;
				}

				// Insert PP Simplified code into options.
				$new_pp_option[ "code_{$lang_id}" ] = $privacy_policy_generator->handle( $lang_id, $privacy_policy_id, $new_pp_option['button_style'] );
				$codes_statues[]                    = true;
			} else {
				$parsed_code = iubenda()->parse_tc_pp_configuration( $code );
				// check if code is empty or code is invalid.
				$codes_statues[] = (bool) $parsed_code;

				// getting public id to save it into Iubenda global option lang.
				if ( $parsed_code ) {
					$global_options['public_ids'][ $lang_id ] = sanitize_key( iub_array_get( $parsed_code, 'cookie_policy_id' ) );
					$new_pp_option['button_style']            = $this->get_only_valid_values( iub_array_get( $parsed_code, 'button_style' ), $this->accepted_options['button_style'], iubenda()->defaults['pp']['button_style'] );
				}

				$new_pp_option[ "code_{$lang_id}" ]        = $code;
				$new_pp_option[ "manual_code_{$lang_id}" ] = $code;
			}
		}

		// validating Embed Codes of product contains at least one valid code if the product is activated.
		// Count valid codes per iubenda privacy policy solution and return error if doesn't have at least 1 valid code.
		if ( count( array_filter( $codes_statues ) ) === 0 ) {
			wp_send_json(
				array(
					'status'       => 'error',
					'responseText' => '( iubenda privacy policy solution ) At least one code must be valid.',
				)
			);
		}

		// Update PP codes with new button style.
		$new_pp_option = $this->update_button_style( $new_pp_option );

		// set the product configured option true.
		$new_pp_option['configured'] = 'true';

		// Sanitize all options except the option with %code% because it contains a user embed script.
		$new_pp_option = $this->sanitize_options( $new_pp_option );

		// Merging new PP options with old ones.
		$new_pp_option  = $this->iub_strip_slashes_deep( $new_pp_option );
		$old_pp_options = $this->iub_strip_slashes_deep( iubenda()->options['pp'] );
		$new_pp_option  = wp_parse_args( $new_pp_option, $old_pp_options );

		// Saving and update the current instance with new CS options.
		iubenda()->options['pp'] = $new_pp_option;
		iubenda()->iub_update_options( 'iubenda_privacy_policy_solution', $new_pp_option );

		// update only pp make it activated service.
		iubenda()->options['activated_products']['iubenda_privacy_policy_solution'] = 'true';
		iubenda()->iub_update_options( 'iubenda_activated_products', iubenda()->options['activated_products'] );

		// update iubenda global options (site_id & public_ids).
		iubenda()->options['global_options'] = $global_options;
		iubenda()->iub_update_options( 'iubenda_global_options', $global_options );

		// Add a widget in the sidebar if the button is positioned automatically.
		if ( 'automatic' === $new_pp_option['button_position'] ) {
			iubenda()->assign_legal_block_or_widget();
		}
	}
}
