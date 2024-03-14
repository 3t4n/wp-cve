<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div class="socialflow-messages error notice notice-error" ng-if="true == $ctrl.showErrors">
	<p class="sf-error" ng-repeat="error in $ctrl.errors track by $index" ng-bind-html="$ctrl.trustAsHtml( error )"></p>
</div>
