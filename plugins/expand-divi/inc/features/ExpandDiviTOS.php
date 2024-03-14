<?php
/**
 * Expand Divi TOS
 * adds a terms of use checbox in the register page
 *
 * @package  ExpandDivi/ExpandDiviTOS
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviTOS {
	/**
	 * constructor
	 */
	function __construct() {
		add_filter( 'register_form', array( $this, 'expand_divi_output_tos_checkbox' ) );
		add_filter( 'registration_errors', array( $this, 'expand_divi_auth_checkbox' ), 10, 3 );
	}

	/**
	 * add checbox to register page
	 *
	 * @return object
	 */
	function expand_divi_output_tos_checkbox() {
		?>
		<p>
			<input type="checkbox" class="checkbox" name="ed_tos" id="ed_tos" />
		<?php echo sprintf( __( 'I agree to the <a href="%s" target="_blank">terms of use.</a>', 'expand-divi' ), get_site_url() . '/tos' ); ?>
		</p>
		<?php
	}

	/**
	 * checkbox to be required
	 *
	 * @return string
	 */
	function expand_divi_auth_checkbox( $errors, $sanitized_user_login, $user_email ) {
		if ( ! isset( $_POST['ed_tos'] ) ) :
			$errors->add( 'ed_terms_of_use_error', __( '<strong>ERROR</strong>: Please accept the terms of use.', 'expand-divi' ) );
		    return $errors;
		endif;
		return $errors;
	}
}

new ExpandDiviTOS();