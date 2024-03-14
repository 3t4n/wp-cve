<?php
/**
 * Iubenda TC service.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TC service.
 */
class Iubenda_TC_Product_Service extends Iubenda_Abstract_Product_Service {

	/**
	 * Accepted TC Options.
	 *
	 * @var array
	 */
	private $accepted_options = array(
		'button_style'    => array( 'white', 'black' ),
		'button_position' => array( 'automatic', 'manual' ),
	);

	/**
	 * Saving TC options
	 */
	public function saving_tc_options() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$request_tc_option = (array) iub_array_get( $_POST, 'iubenda_terms_conditions_solution', array() );
		$tc_default_keys   = array_keys( iubenda()->defaults['tc'] );
		$tc_default_keys   = array_merge( $this->get_languages_code_keys( false ), $tc_default_keys );
		$new_tc_option     = iub_array_only( $request_tc_option, $tc_default_keys );

		$codes_statues                    = array();
		$global_options                   = iubenda()->options['global_options'];
		$new_tc_option['button_style']    = $this->get_only_valid_values( iub_array_get( $new_tc_option, 'button_style' ), $this->accepted_options['button_style'], iubenda()->defaults['tc']['button_style'] );
		$new_tc_option['button_position'] = $this->get_only_valid_values( iub_array_get( $new_tc_option, 'button_position' ), $this->accepted_options['button_position'], iubenda()->defaults['tc']['button_position'] );

		$languages = ( new Product_Helper() )->get_languages();
		// loop on iubenda->>language.
		foreach ( $languages as $lang_id => $v ) {
			$code        = iub_array_get( $new_tc_option, "code_{$lang_id}" );
			$parsed_code = iubenda()->parse_tc_pp_configuration( $code );
			// check if code is empty or code is invalid.
			$codes_statues[] = (bool) $parsed_code;

			// getting public id to save it into Iubenda global option lang.
			if ( $parsed_code ) {
				$global_options['public_ids'][ $lang_id ] = sanitize_key( iub_array_get( $parsed_code, 'cookie_policy_id' ) );
				$new_tc_option['button_style']            = $this->get_only_valid_values( iub_array_get( $parsed_code, 'button_style' ), $this->accepted_options['button_style'], iubenda()->defaults['tc']['button_style'] );
			}

			$new_tc_option[ "code_{$lang_id}" ] = $code;
		}

		// validating Embed Codes of product contains at least one valid code if the product is activated.
		// Count valid codes per iubenda terms conditions solution and return error if doesn't have at least 1 valid code.
		if ( count( array_filter( $codes_statues ) ) === 0 ) {
			wp_send_json(
				array(
					'status'       => 'error',
					'responseText' => '( iubenda terms conditions solution ) At least one code must be valid.',
				)
			);
		}

		// Update TC codes with new button style.
		$new_tc_option = $this->update_button_style( $new_tc_option );

		// set the product configured option true.
		$new_tc_option['configured'] = 'true';

		// Sanitize all options except the option with %code% because it contains a user embed script.
		$new_tc_option = $this->sanitize_options( $new_tc_option );

		// Merging new TC options with old ones.
		$new_tc_option  = $this->iub_strip_slashes_deep( $new_tc_option );
		$old_tc_options = $this->iub_strip_slashes_deep( iubenda()->options['tc'] );
		$new_tc_option  = wp_parse_args( $new_tc_option, $old_tc_options );

		// Saving and update the current instance with new CS options.
		iubenda()->options['tc'] = $new_tc_option;
		iubenda()->iub_update_options( 'iubenda_terms_conditions_solution', $new_tc_option );

		// update only tc make it activated service.
		iubenda()->options['activated_products']['iubenda_terms_conditions_solution'] = 'true';
		iubenda()->iub_update_options( 'iubenda_activated_products', iubenda()->options['activated_products'] );

		// update iubenda global options (site_id & public_ids).
		iubenda()->options['global_options'] = $global_options;
		iubenda()->iub_update_options( 'iubenda_global_options', $global_options );

		// Add a widget in the sidebar if the button is positioned automatically.
		if ( 'automatic' === $new_tc_option['button_position'] ) {
			iubenda()->assign_legal_block_or_widget();
		}
	}
}
