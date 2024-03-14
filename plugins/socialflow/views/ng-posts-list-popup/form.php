<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

?>
<form id="sf-compose-form" ng-if="true == $ctrl.showForm" novalidate ng-submit="$ctrl.submit( $event )">
	<div id="socialflow-compose" class="socialflow socialflow-compose">
		<compose-form ajax-data="$ctrl.ajaxData"></compose-form>
	</div>

	<input name="action" type="hidden" value="sf-compose" >

	<div class="sf-compose-form-popup-send">
		<input 
			type="submit" 
			name="submit" 
			class="sf-button sf-button-blue" 
			value="<?php esc_attr_e( 'Send Message', 'socialflow' ); ?>" 
		/> 
		<img class="sf-loader" ng-show="true == $ctrl.showSpinner" src="<?php echo esc_url( plugins_url( 'assets/images/wpspin.gif', SF_FILE ) ); ?>" alt="">
	</div>
	<div ng-bind-html="$ctrl.message" class="sf-compose-popup-message"></div>
</form>
