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
<div class="sf-compose-form-stats full-stats-container" ng-if="0 != $ctrl.logs.length">
	<p>
		<?php printf( esc_attr( 'Last time message was successfully sent at %s', 'socialflow' ), '{{ $ctrl.lastSent }}' ); ?>
		<span class="clickable" ng-click="$ctrl.toggleList( $event )">
			<?php esc_html_e( 'Expand Statistics.', 'socialflow' ); ?>
		</span>
	</p>
	<table ng-if="true == $ctrl.showList" cellspacing="0" class="wp-list-table widefat fixed sf-statistics">
		<thead>
			<tr>
				<th style="width:150px" class="manage-column column-date" scope="col">
					<span>
						<?php esc_html_e( 'Last Sent', 'socialflow' ); ?>
					</span>
				</th>
				<th class="manage-column column-status" scope="col">
					<?php esc_html_e( 'Account', 'socialflow' ); ?>
				</th>
				<th class="manage-column column-status" scope="col">
					<?php esc_html_e( 'Status', 'socialflow' ); ?>
				</th>
				<th class="manage-column column-content_item_id" scope="col">
					<?php esc_html_e( 'Content Item ID', 'socialflow' ); ?>
				</th>
				<th scope="col" width="40px">
					<img 
						title="<?php esc_html_e( 'Refresh Message Stats', 'socialflow' ); ?>" 
						alt="<?php esc_attr_e( 'Refresh', 'socialflow' ); ?>" 
						class="sf-js-update-multiple-messages" 
						src="<?php echo esc_url( plugins_url( 'assets/images/reload.png', SF_FILE ) ); ?>"
						ng-click="$ctrl.updateLogs( $event )"
					/>
				</th>
			</tr>
		</thead>

		<tbody class="list:statistics">
			<tr ng-repeat="log in $ctrl.logs track by $index" class="message">
				<td class="username column-username">
					{{ log.date }}
				</td>
				<td class="account column-account">
					{{ log.account.name }}
				</td>
				<td class="status column-status" ng-bind-html="$ctrl.trustAsHtml( log.message.status )"></td>
				<td class="status column-content_item_id">
					{{ log.message.content_item_id }}
				</td>
				<td>
					<img class="sf-message-loader" src="<?php echo esc_url( plugins_url( 'assets/images/wpspin.gif', SF_FILE ) ); ?>" alt="" ng-show="true == log.showSpinner">
				</td>
			</tr>
		</tbody>
	</table>
</div>
