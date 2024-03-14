<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div class="sf-socials-tabs-list">
	<div ng-repeat="social in $ctrl.socials track by $index">
		<div class="tabs-panel" ng-show="$ctrl.isActive( social )">
			<messages-list messages="social.messages" social="social" global="$ctrl.global"></messages-list>
		</div>
	</div>
</div>
