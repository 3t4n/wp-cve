<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<ul class="sf-compose-tabs" id="sf-compose-tabs">
	<li 
		class="sf-compose-tab {{ social.type }}" 
		ng-repeat="social in $ctrl.getFilteredSocials() track by $index" 
		ng-click="$ctrl.activateTab( social )" 
		ng-class="$ctrl.setActiveClass( social )"
	></li>
</ul>
