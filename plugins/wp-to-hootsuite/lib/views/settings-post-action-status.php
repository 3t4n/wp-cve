<?php
/**
 * Outputs the single status configuration form.  Its values are populated by statuses.js, based
 * on the status that has been selected for editing.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>
<div id="<?php echo esc_attr( $this->base->plugin->name ); ?>-status-form-container" class="hidden">
	<div id="<?php echo esc_attr( $this->base->plugin->name ); ?>-status-form" class="wp-to-social-pro-status-form">
		<div class="wpzinc-option">
			<div class="full">
				<!-- Tags and Feat. Image -->
				<div class="tags-featured-image">
					<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>_sub_profile" size="1" class="right"></select> 
					<input type="url" name="<?php echo esc_attr( $this->base->plugin->name ); ?>_sub_profile" placeholder="<?php esc_attr_e( 'Pinterest Board URL', 'wp-to-hootsuite' ); ?>" class="right" />
				   
					<!-- Image -->
					<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>_image" size="1" class="right image">
						<?php
						foreach ( $this->base->get_class( 'image' )->get_featured_image_options( $post_type ) as $value => $label ) {
							?>
							<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_attr( $label ); ?></option>
							<?php
						}
						?>
					</select>

					<?php
					// Tags.
					$textarea = 'textarea.message';
					require 'settings-post-action-status-tags.php';
					?>
				</div>
			</div>

			<!-- Status Message -->
			<div class="full">
				<textarea name="<?php echo esc_attr( $this->base->plugin->name ); ?>_message" rows="3" class="widefat wpzinc-autosize-js message"></textarea>
			</div>

			<!-- Scheduling -->
			<div class="full">
				<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>_schedule" size="1" class="schedule widefat">
					<?php
					foreach ( $this->base->get_class( 'common' )->get_schedule_options( $post_type, $is_post_screen ) as $schedule_option => $label ) {
						?>
						<option value="<?php echo esc_attr( $schedule_option ); ?>"><?php echo esc_attr( $label ); ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
	</div>
</div>
