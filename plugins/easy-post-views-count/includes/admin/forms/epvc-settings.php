<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Post count html
 *
 * The html markup for the post count
 * 
 * @package Easy Post Views Count
 * @since 1.0.0
 */

global $epvc_settings;

$args = array(
   'public'   => true
);

$post_types = get_post_types( $args, 'names', 'and' );

?>
<div class="wrap wpeob-settings">	
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Easy post count settings', 'epvc' ); ?></h1>
	<?php
		// Print error messages
		settings_errors(); ?>

	<form method="post" id="epvc-settings-form" action="options.php">
		<?php settings_fields( 'epvc-plugin-settings' ); ?>
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'epvc' ); ?>"><br /></div>
					<h3 class="hndle">
						<span style='vertical-align: top;'><?php esc_html_e( 'Settings', 'epvc' ); ?></span>
					</h3>
					<div class="inside">
						<table class="form-table fh-profile-upload-options">
					        <tr>
								<th><label for="image"><?php _e('Enable for Post Types', 'epvc') ?></label></th>
								<td>
									<input type="hidden" name="epvc_settings[post_types]" value="no" class="regular-text" /> 
									<?php foreach( $post_types as $post_type ){ ?>
										<label><input type="checkbox" name="epvc_settings[post_types][<?php echo $post_type; ?>]" value="yes" class="regular-text" <?php if(!empty( $epvc_settings['post_types']) && $epvc_settings['post_types'] != 'no' && in_array( $post_type , array_keys($epvc_settings['post_types']) ) ) echo 'checked'; ?>/> <?php echo ucfirst($post_type); ?>
										</label><br>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<th><label for="image"><?php _e('Display (Icon,Label)', 'epvc') ?></th>
								<td>
									<input type="hidden" name="epvc_settings[display_icon]" value="no" class="regular-text" /> 
									<input type="hidden" name="epvc_settings[display_label]" value="no" class="regular-text" /> 
									<label><input type="checkbox" name="epvc_settings[display_icon]" value="yes" class="regular-text" <?php if($epvc_settings['display_icon'] == 'yes' ) echo 'checked'; ?>/> <?php _e( 'Icon', 'epvc' ); ?></label>
									<label><input type="checkbox" name="epvc_settings[display_label]" value="yes" class="regular-text" <?php if($epvc_settings['display_label'] == 'yes' ) echo 'checked'; ?>/> <?php _e( 'Label', 'epvc' ); ?></label>

								</td>
							</tr>
							<tr>
								<th><label for="image"><?php _e('Label text', 'epvc') ?></th>
								<td>
									<input type="text" name="epvc_settings[label_text]" value="<?php echo esc_attr($epvc_settings['label_text']); ?>" class="regular-text" /><br />
								</td>
							</tr>
							<tr>
								<th><label for="image"><?php _e('Position', 'epvc') ?></th>
								<td>
									<select  name="epvc_settings[position]">
										<option value="no">Select Postion</option>
										<option value="before_content" <?php if( $epvc_settings['position'] == 'before_content' ) echo 'selected'; ?> >Before Content</option>
										<option value="after_content" <?php if( $epvc_settings['position'] == 'after_content' ) echo 'selected'; ?> >After Content</option>
									</select>
								</td>
							</tr>
							<tr>
								<th><label for="image"><?php _e('Exclude login users', 'epvc') ?></th>
								<td>
									<input type="hidden" name="epvc_settings[login_users]" value="no" class="regular-text" /> 
									<input type="checkbox" name="epvc_settings[login_users]" value="yes" class="regular-text" <?php if($epvc_settings['login_users'] == 'yes' ) echo 'checked'; ?>/> 
								</td>
							</tr>
							<tr>
								<th><label for="image"><?php _e('Exclude IPs', 'epvc') ?></th>
								<td>
									<textarea style="height: 100px;width: 362px;" name="epvc_settings[ips]"><?php echo esc_attr($epvc_settings['ips']); ?></textarea>
									<p class="description">Add comma(,) seprator to each ips if multiple ips</p>
								</td>
							</tr>
							
						</table>
						<?php
							if ( empty( $GLOBALS['hide_save_button'] ) ) :
								submit_button( __( 'Save Changes', 'epvc' ), 'primary', 'epvc-settings-save-button' );
							endif; ?>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>