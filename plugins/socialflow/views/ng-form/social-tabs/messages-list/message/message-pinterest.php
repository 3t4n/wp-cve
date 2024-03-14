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

<textarea 
	data-content-selector="#title"
	class="autofill widefat socialflow-message-{{ $ctrl.social.type }}" 
	id="{{ $ctrl.getFieldId( 'message' ) }}" 
	name="{{ $ctrl.getName( 'message' ) }}" 
	cols="30" 
	rows="5" 
	placeholder="<?php esc_html_e( 'Message', 'socialflow' ); ?>"
	ng-model="$ctrl.message.fields['message']"
></textarea>
