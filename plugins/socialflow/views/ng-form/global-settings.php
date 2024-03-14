<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div class="sf-accounts-global-settings">
	<input type="hidden" name="socialflow_nonce" value="{{ $ctrl.settings.wpnonce }}" />

	<div class="sf_compose sf-checkbox-block">
		<input 
			id="sf_compose" 
			type="checkbox" 
			name="socialflow[global][compose_now]" 
			ng-model="$ctrl.settings.compose_now" 
			ng-true-value="1" 
			ng-false-value="0"
			value="1"
		/>
		<label for="sf_compose">
			<span ng-if="'publish' == $ctrl.post.status">
				<?php esc_html_e( 'Send to SocialFlow when the post is updated', 'socialflow' ); ?>
			</span>
			<span ng-if="'publish' != $ctrl.post.status">
				<?php esc_html_e( 'Send to SocialFlow when the post is published', 'socialflow' ); ?>
			</span>
		</label>
	</div>

</div>
