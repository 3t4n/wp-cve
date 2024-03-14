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

<div class="advanced-settings">
	<div class="advanced-settings-list">
		<advanced-setting ng-repeat="item in $ctrl.settings track by $index" setting="item" social="$ctrl.social" index="$index" message-index="$ctrl.index" remove="$ctrl.removeItem( item )"></advanced-setting>
	</div>
</div>
