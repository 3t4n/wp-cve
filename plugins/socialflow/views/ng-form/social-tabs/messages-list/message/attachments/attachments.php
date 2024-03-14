<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div class="sf-attachments" ng-if ="$ctrl.showHideImageForm($ctrl.social.type) == 1">
<!--$ctrl.showHideAtachment[$ctrl.social.type]-->
	<div class="sf-attachments-slider">
		<div class="image-container sf-attachment-slider">
			<div class="slide" ng-repeat="slide in $ctrl.slider.slides track by $index">
				<img ng-src="{{ slide }}" ng-show="$ctrl.slider.showSlide( $index, $ctrl.slider.slides, $ctrl.social.type )" alt="">
			</div>
		</div>

		<button
			ng-disabled="$ctrl.facebookMetaDisabled($ctrl.social.type)"
			ng-click="$ctrl.setCustomImage( $event )"
			class="button sf-message-select-attachment"
		>
			<?php esc_html_e( 'Select Image', 'socialflow' ); ?>
		</button>

		<span
			ng-click="$ctrl.slider.prev($ctrl.social.type)"
			ng-if="$ctrl.slider.slides.length > 1"
			title="<?php esc_html_e( 'Previous', 'socialflow' ); ?>"
			class="sf-attachment-slider-nav sf-attachment-slider-prev"
		>
			<?php esc_html_e( 'Previous', 'socialflow' ); ?>
		</span>

		<span
			ng-click="$ctrl.slider.next($ctrl.social.type)"
			ng-if="$ctrl.slider.slides.length > 1"
			title="<?php esc_html_e( 'Next', 'socialflow' ); ?>"
			class="sf-attachment-slider-nav sf-attachment-slider-next"
		>
			<?php esc_html_e( 'Next', 'socialflow' ); ?>
		</span>

		<span
			class="sf-attachment-slider-refresh clickable"
			ng-click="$ctrl.slider.refreshSlides( $event )"
			ng-class="$ctrl.slider.setLoadingClass()"
			ng-if="!$ctrl.facebookMetaDisabled($ctrl.social.type)"
		>
			<?php esc_html_e( 'Refresh', 'socialflow' ); ?>
		</span>
	</div>
	<input ng-if="!$ctrl.message.showButton()" type="hidden" name="socialflow[global][media][compose_media_pos_{{$ctrl.social.type}}]" ng-value = "$ctrl.slider.posSl">
	<input ng-if="!$ctrl.message.showButton()" type="hidden" name="socialflow[global][media][compose_media_url_{{$ctrl.social.type}}]" ng-value = "$ctrl.slider._current">
	<input ng-if="!$ctrl.message.showButton()" type="hidden" name="socialflow[global][slide_content_{{$ctrl.social.type}}]]" value="1">
	<input
		class="sf-current-attachment"
		type="hidden"
		name="{{ $ctrl.getName( 'image' ) }}"
		ng-value="$ctrl.message.fields['image']"
	/>

	<input
		class="sf-is-custom-image"
		type="hidden"
		name="{{ $ctrl.getName( 'is_custom_image' ) }}"
		ng-value="$ctrl.message.fields['is_custom_image']"
	/>
<!--	<input -->
<!--		class="sf-custom-image" -->
<!--		type="hidden" -->
<!--		name="{{ $ctrl.getName( 'custom_image' ) }}"-->
<!--		value="{{ $ctrl.message.fields['custom_image'] }}"-->
<!--	/>-->

	<input
		class="sf-custom-image"
		type="hidden"
		name="{{ $ctrl.getName( 'custom_image' ) }}"
		ng-value="$ctrl.message.fields['image']"
	/>
	<input
		class="sf-custom-image-filename"
		type="hidden"
		name="{{ $ctrl.getName( 'custom_image_filename' ) }}"
		ng-value="$ctrl.message.fields['custom_image_filename']"
	/>
</div>
