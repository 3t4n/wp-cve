<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

?>
<div class="" ng-if="'attachment' != $ctrl.post.type && $ctrl.social.type !='linkedin'">
	<input hidden type="checkbox" name="socialflow[global][compose_media]"
		ng-model="$ctrl.settings.compose_media"
		ng-true-value="1"
		ng-false-value="0"
		ng-change="$ctrl.showButtonImage()"
		ng-disabled="$ctrl.disableComposeMedia()"
		value="1"
	/>
	<label for="sf_media_compose">
		<?php esc_html_e( 'Image Post', 'socialflow' ); ?>
	</label>
</div>
<div  class="sf-media-attachment" ng-if="1 == $ctrl.socialsButtonShow[$ctrl.social.type]" ng-class="$ctrl.setLoadingClass()">
	<div class="sf-image-container" ng-if="!!$ctrl.media">
		<img  ng-src="{{ $ctrl.media['medium_thumbnail_url'] }}" alt="">
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
</div>

