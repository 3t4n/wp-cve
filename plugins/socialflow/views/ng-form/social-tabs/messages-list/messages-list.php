<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div class="sf-messages-list">
	<div class="sf-message-wrapper" ng-repeat="message in $ctrl.messages track by $index">
		<div class="sf-message-block" ng-class="$ctrl.setMessageClass( message , $index)">
			<message-twitter 
				ng-if="true == $ctrl.loadComponent( 'twitter', $ctrl.social.type )" 
				message="message" 
				index="$index" 
				social="$ctrl.social" 
				global="$ctrl.global"
			></message-twitter>

			<message-pinterest 
				ng-if="true == $ctrl.loadComponent( 'pinterest', $ctrl.social.type )" 
				message="message" 
				index="$index" 
				social="$ctrl.social" 
				global="$ctrl.global"
			></message-pinterest>

			<message 
				ng-if="true == $ctrl.loadComponent( 'default', $ctrl.social.type )" 
				message="message" 
				index="$index" 
				social="$ctrl.social" 
				global="$ctrl.global"
			></message>

			<advanced-settings-list settings="message.settings" social="$ctrl.social" index="$index"></advanced-settings-list>
		</div>

		<span class="sf-remove-message" ng-if="0 != $index" ng-click="$ctrl.removeItem( message )">
			<?php esc_html_e( 'Remove Message', 'socialflow' ); ?>
		</span>
	</div>
</div>
<span class="sf-button sf-button-blue sf-create-new-message" ng-click="$ctrl.addItem( $event )">
	<?php esc_html_e( 'Create New Message', 'socialflow' ); ?>
</span>
