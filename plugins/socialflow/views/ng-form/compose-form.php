<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div ng-if="true == $ctrl.showForm">
	<stats ajax-data="$ctrl.ajaxData"></stats>

	<div ng-if="true == $ctrl.issetAccounts" class="sf-compose-form-inn">
		<div class="sf-compose-form-sidebar">
			<div class="sf-compose-form-globa-settings">
				<global-settings global="$ctrl.global"></global-settings>
			</div>
			<div class="sf-compose-form-accounts">
				<div class="sf-compose-form-accounts-inn">
					<accounts-list global="$ctrl.global"></accounts-list>
				</div>
			</div>
		</div>
		<div class="sf-compose-form-main">
			<errors></errors>

			<compose-media global="$ctrl.global"></compose-media>
			<social-tabs global="$ctrl.global"></social-tabs>
			<social-tabs-list global="$ctrl.global"></social-tabs-list>
		</div>
	</div>
	<div ng-if="false == $ctrl.issetAccounts" class="misc-pub-section">
		<p>
			<span class="sf-error">
				<?php esc_html_e( "You don't have any active accounts.", 'socialflow' ); ?>
			</span>
		</p>
	</div>
</div>
