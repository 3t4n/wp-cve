<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div id="sf-form-popup" style="display:none;">
	<div ng-app="sfComposeForm">
		<div class="sf-spinner-image"></div>
		<div class="sf-spinner-text">
			<?php esc_html_e( 'Loading...', 'socialflow' ); ?>
		</div>
		<form-in-popup></form-in-popup>
	</div>
</div>
