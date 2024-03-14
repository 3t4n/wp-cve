<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

	<div class="" ng-if="'attachment' != $ctrl.post.type && $ctrl.social.type !='linkedin' && !$ctrl.message.showContent">
		<div style="padding-bottom: 20px; padding-top: 20px;">
		<input ng-if="!$ctrl.contextShow()" hidden
			type="checkbox"
			form="sf-compose-form"
			name="socialflow[global][media][compose_media_{{$ctrl.social.type}}]"
			ng-model="$ctrl.composeSocial[$ctrl.social.type]"
			ng-true-value="1"
			ng-false-value="0"
			ng-change="$ctrl.showButton($ctrl.social.type)"
			ng-disabled="$ctrl.disableComposeMedia()"
			value="1"
		/>

		<input ng-if="$ctrl.contextShow()" hidden
			type="checkbox"
			form="post"
			name="socialflow[global][media][compose_media_{{$ctrl.social.type}}]"
			ng-model="$ctrl.composeSocial[$ctrl.social.type]"
			ng-true-value="1"
			ng-false-value="0"
			ng-change="$ctrl.showButton($ctrl.social.type)"
			ng-disabled="$ctrl.disableComposeMedia()"
			value="1"
		/>
		<label for="sf_media_compose">
			<?php esc_html_e( 'Image Post', 'socialflow' ); ?>
		</label>
		<input type="hidden" name="socialflow[global][media][compose_media_pos_{{$ctrl.social.type}}]" ng-value="$ctrl.slider.pos">
		<input ng-if="!$ctrl.contextShow()" form="sf-compose-form"  type="hidden" name="socialflow[global][media][compose_media_url_{{$ctrl.social.type}}]" ng-value = "$ctrl.slider.mediaUrl">
		<input ng-if="$ctrl.contextShow()" form="post"  type="hidden" name="socialflow[global][media][compose_media_url_{{$ctrl.social.type}}]" ng-value = "$ctrl.slider.mediaUrl">
		</div>
	</div>
<div  class="sf-media-attachment"ng-if="'attachment' == $ctrl.post.type && $ctrl.social.type !='linkedin'" ng-class="$ctrl.setLoadingClass()">
	<div class="sf-image-container" ng-if="!!$ctrl.media">
		<img ng-if="'attachment' == $ctrl.post.type && $ctrl.social.type !='linkedin'" ng-src="{{ $ctrl.media[$ctrl.social.type]['medium_thumbnail_url'] }}" alt="">
	</div>
</div>
<textarea
	class="autofill widefat socialflow-message-{{ $ctrl.social.type }}"
	id="{{ $ctrl.getFieldId( 'message' ) }}"
	name="{{ $ctrl.getName( 'message' ) }}"
	cols="30"
	rows="5"
	placeholder="<?php esc_html_e( 'Message', 'socialflow' ); ?>"
	ng-keyup="$ctrl.changeModel($event)"
	ng-model="$ctrl.message.fields['message']"
></textarea>
<div class="status">{{$ctrl.max}} characters left</div>
<div class="" ng-if="'attachment' != $ctrl.post.type && $ctrl.social.type !='linkedin' && !$ctrl.message.showContent">
	<div class="sf-attachments" style="position: inherit" ng-if="1 == $ctrl.socialsButtonShow[$ctrl.social.type]" ng-class="$ctrl.setLoadingClass()">
		<!--$ctrl.showHideAtachment[$ctrl.social.type]-->
		<div class="sf-attachments-slider" >
			<div class="image-container sf-attachment-slider">
				<div class="slide"  ng-repeat="slide in $ctrl.slider.getsLiderForSocial() track by $index">
					<img ng-src="{{ slide }}" ng-show="$ctrl.slider.showSlideForSicials( $index, $ctrl.social.type )" alt="">
				</div>
			</div>

			<button
					ng-if="'attachment' != $ctrl.post.type"
					ng-click="$ctrl.setImage( $event )"
					class="sf-button sf-button-blue sf-custom-attachment-button"
			>
				<?php esc_html_e( 'Select', 'socialflow' ); ?>
				<span class="additional-hint">
			<?php esc_html_e( 'image', 'socialflow' ); ?>
		</span>
			</button>

			<span
					ng-if="$ctrl.slider.getsLiderForSocial().length > 1"
					ng-click="$ctrl.slider.prevForSocial($ctrl.social.type)"
					title="<?php esc_html_e( 'Previous', 'socialflow' ); ?>"
					class="sf-attachment-slider-nav sf-attachment-slider-prev"
			>
			<?php esc_html_e( 'Previous', 'socialflow' ); ?>
		</span>

			<span
					ng-if="$ctrl.slider.getsLiderForSocial().length > 1"
					ng-click="$ctrl.slider.nextForSocial($ctrl.social.type)"
					title="<?php esc_html_e( 'Next', 'socialflow' ); ?>"
					class="sf-attachment-slider-nav sf-attachment-slider-next"
			>
			<?php esc_html_e( 'Next', 'socialflow' ); ?>
		</span>

		</div>

	</div>
</div>
<div class="sf_message_postfix">
	<message-attachments message="$ctrl.message" index="$ctrl.index" social="$ctrl.social"></message-attachments>
	<input
		type="text"
		id="{{ $ctrl.getFieldId( 'message_postfix' ) }}"
		name="{{ $ctrl.getName( 'message_postfix' ) }}"
		placeholder="<?php esc_attr_e( 'Message postfix', 'socialflow' ); ?>"
		ng-model="$ctrl.message.fields['message_postfix'] "
	/>
</div>
