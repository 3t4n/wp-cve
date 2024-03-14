<?php
if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}
require_once CANVAS_DIR . 'core/push/canvas-notifications.class.php';
?>

<div class="canvas-send-notification-setting">
	<div class="canvas__sns-group-title">
		<?php esc_html_e( 'Send notification', 'canvas' ); ?>
	</div>
	<div class="canvas__sns-fields">
		<div class="canvas__sns-field-group">
			<label class="canvas__sns-field-label" for=""></label>
		</div>

		<!-- Notification Type -->
		<div class="canvas__sns-field-group">
			<label class="canvas__sns-field-label" for="canvas__sns-notification-type">
				<?php esc_html_e( 'Notification type:', 'canvas' ); ?>
			</label>
			<select name="canvas__sns-notification-type" id="canvas__sns-notification-type">
				<option value="post"><?php esc_html_e( 'Post', 'canvas' ); ?></option>
				<option value="url"><?php esc_html_e( 'URL', 'canvas' ); ?></option>
			</select>
		</div>

		<!-- Notification Type:Post -->
		<div class="canvas__sns-field-group canvas__sns-post-search">
			<label class="canvas__sns-field-label" for="canvas__sns-post-search">
				<?php esc_html_e( 'Post:', 'canvas' ); ?>
			</label>
			<select name="canvas__sns-post-search" id="canvas__sns-post-search">

			</select>
		</div>

		<!-- Notification Type:URL -->
		<div class="canvas__sns-field-group sns--hide canvas__sns-notification-url">
			<label class="canvas__sns-field-label" for="canvas__sns-notification-url">
				<?php esc_html_e( 'Notification URL:', 'canvas' ); ?>
			</label>
			<input type="url" name="canvas__sns-notification-url" id="canvas__sns-notification-url" />
		</div>

		<!-- Notification title -->
		<div class="canvas__sns-field-group">
			<label class="canvas__sns-field-label" for="canvas__sns-notification-title">
				<?php esc_html_e( 'Notification title:', 'canvas' ); ?>
			</label>
			<input type="text" name="canvas__sns-notification-title" id="canvas__sns-notification-title" placeholder="" />
		</div>

		<!-- Notification message -->
		<div class="canvas__sns-field-group">
			<label class="canvas__sns-field-label" for="canvas__sns-notification-text-area">
				<?php esc_html_e( 'Notification message:', 'canvas' ); ?>
			</label>
			<textarea name="canvas__sns-notification-text-area" id="canvas__sns-notification-text-area" rows="5" placeholder=""></textarea>
		</div>

		<!-- Notification image -->
		<div class="canvas__sns-field-group">
			<label class="canvas__sns-field-label" for="">
				<?php esc_html_e( 'Notification image:', 'canvas' ); ?>
			</label>
			<button class="canvas__sns-upload-featured-image sns--hide" id="canvas__sns-upload-featured-image">
				<?php esc_html_e( 'Upload file', 'canvas' ); ?>
			</button>
			<div id="canvas__sns-featured-image-wrapper" style="max-width: 350px; display: block;"></div>
			<input type="hidden" value="" id="canvas__sns-featured-image-url" name="canvas__sns-featured-image-url" />
			<label class="canvas__sns-field-label--2 canvas__sns-use-post-featured-image">
				<input type="checkbox" name="canvas__sns-use-post-featured-image" id="canvas__sns-use-post-featured-image" checked>
				<?php esc_html_e( 'Use post featured image', 'canvas' ); ?>
			</label>
		</div>

		<!-- Send to platforms -->
		<div class="canvas__sns-field-group">
			<label class="canvas__sns-field-label" for="">
				<?php esc_html_e( 'Send to platforms:', 'canvas' ); ?>
			</label>
			<div class="canvas__sns-field-group--radio">
				<label class="canvas__sns-field-label--2">
					<input type="radio" name="canvas__sns-send-to-platforms" id="canvas__sns-send-to-platforms" value="all" checked>
					<?php esc_html_e( 'All (iOS and Android)', 'canvas' ); ?>
				</label>
				<label class="canvas__sns-field-label--2">
					<input type="radio" name="canvas__sns-send-to-platforms" id="canvas__sns-send-to-platforms" value="ios">
					<?php esc_html_e( 'iOS', 'canvas' ); ?>
				</label>
				<label class="canvas__sns-field-label--2">
					<input type="radio" name="canvas__sns-send-to-platforms" id="canvas__sns-send-to-platforms" value="android">
					<?php esc_html_e( 'Android', 'canvas' ); ?>
				</label>
			</div>
		</div>

		<!-- Notification tags -->
		<div class="canvas__sns-field-group">
			<label class="canvas__sns-field-label" for="">
				<?php esc_html_e( 'Notification tags', 'canvas' ); ?>
			</label>
			<label class="canvas__sns-field-label--2 canvas__sns-use-post-category-as-tags">
				<input type="checkbox" name="canvas__sns-use-post-category-as-tags" id="canvas__sns-use-post-category-as-tags">
				<span><?php printf( __( 'Use post categories as tags %s', 'canvas' ), '<span class="canvas__sns--all-tags"></span>' ); ?></span>
			</label>
			<input type="text" class="canvas__sns-additional-tags" name="canvas__sns-additional-tags" id="canvas__sns-additional-tags" placeholder="<?php esc_html_e( 'Additional tags in a comma separated list', 'canvas' ); ?>">
		</div>

		<div class="canvas__sns-field-group canvas-line">
			<div class="canvas__sns-error-wrapper">
				<div id="success-message" class="updated" style="display: none;">Your message has been sent!</div>
				<div id="error-message" class="error" style="display: none;"></div>
			</div>

			<input type="submit" onclick="return false;" class='button button-primary button-large'
				id="canvas_notification_manual_send_submit" value="<?php _e( 'Send' ); ?>"
				data-send="<?php _e( 'Send' ); ?>"
				data-sending="<?php _e( 'Sending...' ); ?>"
				/>

			<img class="canvas__sns-spinner" src="<?php echo esc_url( get_admin_url( null, 'images/spinner.gif' ) ); ?>" style="margin: 5px 0 0 4px;" />
		</div>
	</div>
</div>


<div class="canvas-block" id="canvas_history_block">
	<div class="canvas-header"><h1>Notification history</h1></div>
	<div class="canvas-body">
		<div id="canvas_notification_history"></div>
	</div>
</div>

