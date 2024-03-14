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
<div id="socialflow-compose" class="socialflow socialflow-compose" ng-app="sfComposeForm">
	<compose-form>
		<div class="sf-spinner-image"></div>
		<div class="sf-spinner-text">
			<?php esc_html_e( 'Loading...', 'socialflow' ); ?>
		</div>
	</compose-form>
</div>
