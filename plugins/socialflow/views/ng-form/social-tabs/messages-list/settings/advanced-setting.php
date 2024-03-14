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

<div class="sf-advanced-setting sf-advanced-setting-{{ $ctrl.index }}">
	<span class="sf-setting-item sf-setting-item-publish">
		<select
			name="{{ $ctrl.getFieldName('publish_option') }}"
			ng-options="option.value for option in $ctrl.getPublishOptions() track by option.key"
			ng-model="$ctrl.setting.publish_option"
		></select>
	</span>

	<span ng-if="'optimize' == $ctrl.setting.publish_option.key">
		<span class="sf-setting-item-optimize sf-setting-item">
			<span class="sf-setting-title sf-setting-must-send">
				<span class="clickable" ng-click="$ctrl.toggleMustSend()" ng-hide="1 != $ctrl.must_send">
					<?php echo esc_attr_e( 'Must Send', 'socialflow' ); ?>
				</span>
				<span class="clickable" ng-click="$ctrl.toggleMustSend()" ng-hide="1 == $ctrl.must_send">
					<?php echo esc_attr_e( 'Can Send', 'socialflow' ); ?>
				</span>
			</span>

			<input class="must_send" name="{{ $ctrl.getFieldName('must_send') }}" type="hidden" value="{{ $ctrl.must_send }}">

			<select
				class="optimize-period"
				name="{{ $ctrl.getFieldName('optimize_period') }}"
				ng-options="option.value for option in $ctrl.getConstantOptions( 'optimize_period' ) track by option.key"
				ng-model="$ctrl.setting.optimize_period"
			></select>
		</span>

		<span ng-if="'range' == $ctrl.setting.optimize_period.key">
			<span class="sf-setting-item sf-setting-item-optimize-range">
				<span class="sf-setting-title">
					<?php esc_html_e( 'from', 'socialflow' ); ?>
				</span>
				<date-picker
					tz-offset="$ctrl.getConstantData( 'data_tz_offset' )"
					name="$ctrl.getFieldName('optimize_start_date')"
					value="$ctrl.setting.optimize_start_date"
				></date-picker>
			</span>
			<span class="sf-setting-item sf-setting-item-optimize-range">
				<span class="sf-setting-title">
					<?php esc_html_e( 'to', 'socialflow' ); ?>
				</span>
				<date-picker
					tz-offset="$ctrl.getConstantData( 'data_tz_offset' )"
					name="$ctrl.getFieldName('optimize_end_date')"
					value="$ctrl.setting.optimize_end_date"
				></date-picker>
			</span>
		</span>
	</span>


	<span class="sf-setting-item sf-setting-item-schedule" ng-if="'schedule' == $ctrl.setting.publish_option.key">
		<span class="sf-setting-title">
			<?php esc_html_e( 'Send at', 'socialflow' ); ?>
		</span>
		<date-picker
			tz-offset="$ctrl.getConstantData( 'data_tz_offset' )"
			name="$ctrl.getFieldName('scheduled_date')"
			value="$ctrl.setting.scheduled_date"
		></date-picker>
	</span>

	<span class="sf-setting-item sf-advanced-item-actions">
		<span class="sf-button sf-button-remove" ng-if="0 != $ctrl.index" ng-click="$ctrl.remove({ setting: item })"><?php esc_html_e( 'X', 'socialflow' ); ?></span>
	</span>
</div>
