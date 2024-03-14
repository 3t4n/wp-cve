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
<div class="" ng-if="'attachment' != $ctrl.post.type && $ctrl.social.type !='linkedin'">
	<div class=""  ng-if="'attachment' != $ctrl.post.type && $ctrl.social.type !='linkedin' && !$ctrl.message.showContent">
		<div style="padding-bottom: 20px; padding-top: 20px;">
		<input ng-if="!$ctrl.contextShow()" hidden type="checkbox"
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
		<input ng-if="$ctrl.message.showButton()" type="hidden" name="socialflow[global][media][compose_media_pos_{{$ctrl.social.type}}]" ng-value = "$ctrl.slider.pos">
		<input ng-if="!$ctrl.contextShow() && $ctrl.message.showButton()" form="sf-compose-form"  type="hidden" name="socialflow[global][media][compose_media_url_{{$ctrl.social.type}}]" ng-value = "$ctrl.slider.mediaUrl">
		<input ng-if="$ctrl.contextShow() && $ctrl.message.showButton()" form="post"  type="hidden" name="socialflow[global][media][compose_media_url_{{$ctrl.social.type}}]" ng-value = "$ctrl.slider.mediaUrl">
		<label for="sf_media_compose">
			<?php esc_html_e( 'Image Post', 'socialflow' ); ?>
		</label>
			</div>
	</div>
</div>

<div  class="sf-media-attachment" ng-if="'attachment' == $ctrl.post.type && $ctrl.social.type !='linkedin'" ng-class="$ctrl.setLoadingClass()">
	<input ng-if="$ctrl.contextShow()"
		type="text"
		name="socialflow[global][media][compose_media_{{$ctrl.social.type}}]"
		value="1"
	/>

	<input ng-if="$ctrl.contextShow()"
		type="hidden"
		name="socialflow[global][media][compose_media_{{$ctrl.social.type}}]"
		value="1"
	/>
	<div class="sf-image-container" ng-if="!!$ctrl.media">
		<img  ng-src="{{ $ctrl.media[$ctrl.social.type]['medium_thumbnail_url'] }}" alt="">
	</div>
</div>
<textarea
		class="widefat socialflow-message-{{ $ctrl.type }}"
		id="{{ $ctrl.getFieldId( 'message' ) }}"
		name="{{ $ctrl.getName( 'message' ) }}"
		cols="30"
		rows="5"
		placeholder="<?php esc_html_e( 'Message', 'socialflow' ); ?>"
		ng-model="$ctrl.message.fields['message']"
></textarea>
<div class="" ng-if="'attachment' != $ctrl.post.type && $ctrl.social.type !='linkedin' && !$ctrl.message.showContent">
	<div class="sf-attachments" style="position: inherit" ng-if="1 == $ctrl.socialsButtonShow[$ctrl.social.type]" ng-class="$ctrl.setLoadingClass()">
		<!--$ctrl.showHideAtachment[$ctrl.social.type]-->
		<div class="sf-attachments-slider">
			<div class="image-container sf-attachment-slider">
				<div ng-if=" !$ctrl.message.showContent" class="slide" ng-repeat="slide in $ctrl.slider.getsLiderForSocial() track by $index">
					<img ng-src="{{ slide }}" ng-show="$ctrl.slider.showSlideForSicials( $index , $ctrl.social.type, $ctrl.slider.mediaUrl)" alt="">
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
<div class="sf-additional-fields" ng-if="1 != $ctrl.global.globalSettings.compose_media && !$ctrl.message.showContent && !$ctrl.socialsButtonShow[$ctrl.social.type]">
	<message-attachments ng-if="!$ctrl.message.showContent"  message="$ctrl.message" index="$ctrl.index" social="$ctrl.social" !$ctrl.socialsButtonShow[$ctrl.social.type]"></message-attachments>



	<input
		ng-if="true == $ctrl.editableAdditional"
		class="widefat socialflow-title-{{ $ctrl.type }}"
		ng-readonly="$ctrl.facebookMetaDisabled($ctrl.social.type)"
		type="text"
		name="{{ $ctrl.getName( 'title' ) }}"
		placeholder="<?php esc_html_e( 'Title', 'socialflow' ); ?>"
		ng-model="$ctrl.message.fields['title']"
	/>

	<textarea
		ng-if="true == $ctrl.editableAdditional"
		class="widefat socialflow-description-{{ $ctrl.type }}"
		name="{{ $ctrl.getName( 'description' ) }}"
		cols="30"
		rows="5"
		ng-readonly="$ctrl.facebookMetaDisabled($ctrl.social.type)"
		placeholder="<?php esc_html_e( 'Description', 'socialflow' ); ?>"
		ng-model="$ctrl.message.fields['description']"
	></textarea>

	<div ng-if="false == $ctrl.editableAdditional">
		<div class="sf-muted-text sf-muted-message" ng-if="1 != $ctrl.global.globalSettings.compose_media">
			<small>
				<?php esc_html_e( '* Metadata title and description are not editable for G+', 'socialflow' ); ?>
			</small>
		</div>
		<div class="sf-muted-text sf-muted-title">
			{{ $ctrl.message.fields['title'] }}
		</div>
		<div class="sf-muted-text sf-muted-description">
			{{ $ctrl.message.fields['description'] }}
		</div>
	</div>
</div>
