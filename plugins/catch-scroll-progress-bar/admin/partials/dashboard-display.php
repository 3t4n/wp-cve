<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link      https://catchplugins.com/plugins
 * @since      1.0.0
 *
 * @package    Catch_Scroll_Progress_Bar
 * @subpackage Catch_Scroll_Progress_Bar/admin/partials
 */
?>

<div id="catch-progress-menu">
	<div class="content-wrapper">
		<div class="header">
			<h2><?php esc_html_e( 'Settings', 'catch-scroll-progress-bar' ); ?></h2>
		</div> <!-- .Header -->
		<div class="content">
			<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="notice updated fade">
		  		<p><strong><?php esc_html_e( 'Plugin Options Saved.', 'catch-scroll-progress-bar' ) ?></strong></p>
		  	</div>
			<?php } ?>
			<?php // Use nonce for verification.
				wp_nonce_field( CATCH_SCROLL_PROGRESS_BAR_BASENAME, 'catch_progress_bar_nonce' );
			?>
			<div id="progress_main">
				<form method="post" action="options.php">
					<?php settings_fields( 'catch-scroll-progress-bar-group' ); ?>
					<?php
					$defaults =catch_progress_bar_default_options();
					$settings = catch_progress_bar_get_options();
					?>
					<div class="option-container">
			  			<table class="form-table" bgcolor="white">
							<tbody>
								<tr>
									<th>
										<label><?php echo esc_html__( 'Progress Bar Position', 'catch-scroll-progress-bar' ); ?></label>
									</th>
									<td>
										<select name="catch_progress_bar_options[progress_bar_position]" id="catch_progress_bar_options[progress_bar_position]">
							                	<?php
													$progress_bar_position = catch_progress_bar_position();
													foreach ( $progress_bar_position as $k => $value) {
														echo '<option value="' . $k . '"' . selected( $settings['progress_bar_position'], $k, false ) . '>' . $value . '</option>';
													}
													?>
							             </select>
							             <span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'let you to decide the progress bar position.', 'catch-scroll-progress-bar' ); ?>"></span>
							         </td>
							     </tr>
								<tr>
									<th>
					  					<label><?php esc_html_e( 'Background Color', 'catch-scroll-progress-bar' ); ?></label>
									</th>
									<td>
								  		<input type="text" name="catch_progress_bar_options[background_color]" id="background-color" class="color-picker" data-alpha="true" value="<?php echo esc_attr( $settings['background_color'] ); ?>"/>
									</td>
				  				</tr>
				  				

				  				<tr>
									<th>
					  					<label><?php esc_html_e( 'Foreground Color', 'catch-scroll-progress-bar' ); ?></label>
									</th>
									<td>
								  		<input type="text" name="catch_progress_bar_options[foreground_color]" id="foreground-color" class="color-picker" data-alpha="true" value="<?php echo esc_attr( $settings['foreground_color'] ); ?>"/>
									</td>
				  				</tr>
				  				<tr>
									<th>
					  					<label><?php esc_html_e( 'Background Opacity', 'catch-scroll-progress-bar' ); ?></label>
									</th>
									<td>
								  		<input type="number" min="0" max="1" step="0.1"   name="catch_progress_bar_options[background_opacity]" id="background-opacity" class="background-opacity" data-alpha="true" value="<?php echo esc_attr( $settings['background_opacity'] ); ?>"/>
								  		<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Background Opacity helps you to set the  transparency-level, 1 is not transparent at all where as 0 is completely transparent.', 'catch-scroll-progress-bar' ); ?>"></span>
									</td>
				  				</tr>
				  				<tr>
									<th>
					  					<label><?php esc_html_e( 'Foreground Opacity', 'catch-scroll-progress-bar' ); ?></label>
									</th>
									<td>
								  		<input type="number" min="0" max="1" step="0.1"   name="catch_progress_bar_options[foreground_opacity]" id="foreground-opacity" class="foreground-opacity" data-alpha="true" value="<?php echo esc_attr( $settings['foreground_opacity'] ); ?>"/>
								  		<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Foreground Opacity helps you to set the  transparency-level, 1 is not transparent at all where as 0 is completely transparent.', 'catch-scroll-progress-bar' ); ?>"></span>
									</td>
				  				</tr>

								<tr>
									<th>
					  					<label><?php esc_html_e( 'Progress Bar Height', 'catch-scroll-progress-bar' ); ?></label>
									</th>
									<td>
								  		<input type="number" min="0" max="100" step="1"   name="catch_progress_bar_options[bar_height]" id="bar-height" class="bar_height" data-alpha="true" value="<?php echo esc_attr( $settings['bar_height'] ); ?>"/>
								  		<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'This will let you to set the height of the progress bar', 'catch-scroll-progress-bar' ); ?>"></span>
									</td>
				  				</tr>
				  				<tr>
									<th>
					  					<label><?php esc_html_e( 'Boder Radius', 'catch-scroll-progress-bar' ); ?></label>
									</th>
									<td>
								  		<input type="number" min="0" max="100" step="1"   name="catch_progress_bar_options[radius]" id="bar-height" class="bar_height" data-alpha="true" value="<?php echo esc_attr( $settings['radius'] ); ?>"/>
								  		<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'This will let you to set the border radius of the progress bar', 'catch-scroll-progress-bar' ); ?>"></span>
									</td>
				  				</tr>


				  				<tr>
								<th>
									<?php echo esc_html__('Select the Template Condition to progress bar'); ?>
								</th>
								<td>
									<p class="description">Template condition...</p>
									<div>
										<input type="checkbox" id="catch_progress_bar_options[home]" name="catch_progress_bar_options[home]" value="1"
										<?php echo checked( isset($settings['home']) ? $settings['home'] : '' , true, false ); ?> />
										<label for="catch_progress_bar_options[home]">Front page/Home Page </label>
									</div>

									<div>
										<input type="checkbox" id="catch_progress_bar_options[blog]" name="catch_progress_bar_options[blog]" value="1"
										<?php echo checked( isset($settings['blog']) ? $settings['blog'] : '' , true, false ); ?> />
										<label for="catch_progress_bar_options[blog]">Blog Page</label>
									</div>

									<div>
										<input type="checkbox" id="catch_progress_bar_options[archive]" name="catch_progress_bar_options[archive]" value="1"
										<?php echo checked( isset($settings['archive']) ? $settings['archive'] : '' , true, false ); ?> />
										<label for="catch_progress_bar_options[archive]">Archives and Categories <p class="description">Select post type to apply progress bar</p></label>
									</div>

									<div>
										<input type="checkbox" id="catch_progress_bar_options[single]" name="catch_progress_bar_options[single]" value="1"
										<?php echo checked( isset($settings['single']) ? $settings['single'] : '' , true, false ); ?> />
										<label for="catch_progress_bar_options[single]">Single page/post<p class="description">Select post type to apply progress bar</p></label>
									</div>
								</td>
							    </tr>
							    <tr>
								<th>
									<?php echo esc_html__('Select the Post type to  apply progress bar'); ?>
								</th>
								<td>
									<p class="description">Select post type to apply progress bar</p>

									<div>
								<?php
								$optionNamePostType = '';
									if (isset($settings['field_posttypes'])) {
										$optionPostTypes = $settings['field_posttypes'];
										$post_types = get_post_types( array( 'public' => true ), 'objects' );
										foreach ( $post_types as $type => $obj ) {
											if (isset($optionPostTypes[$obj->name])) : $optionNamePostType = $optionPostTypes[$obj->name]; else : $optionNamePostType = ''; endif;
											?>
											<p><input type='checkbox' name='catch_progress_bar_options[field_posttypes][<?php echo $obj->name; ?>]' id="catch_progress_bar_options[field_posttypes][<?php echo $obj->name; ?>]"
										<?php echo checked( isset($optionNamePostType) ? $optionNamePostType : '' , true, false ); ?> value='1' /> <?php echo $obj->labels->name; ?></p>
											<?php
										}
									} else {
										$post_types = get_post_types( array( 'public' => true ), 'objects' );
										foreach ( $post_types as $type => $obj ) {
											?>
											<p><input type='checkbox' name='catch_progress_bar_options[field_posttypes][<?php echo $obj->name; ?>]' value='1' /> <?php echo $obj->labels->name; ?></p>
											<?php
										}
									} ?>

									</div>

							    <tr>
                                    <th scope="row"><?php esc_html_e( 'Reset Options', 'catch-scroll-progress-bar' ); ?></th>
                                    <td>
                                        <?php
                                            echo '<input name="catch_progress_bar_options[reset]" id="catch_progress_bar_options[reset]" type="checkbox" value="1" class="catch_progress_bar_options[reset]" />' . esc_html__( 'Check to reset', 'catch-scroll-progress-bar' );
                                        ?>
                                        <span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Caution: Reset all settings to default.', 'catch-scroll-progress-bar' ); ?>"></span>
                                    </td>
                                </tr>

							</tbody>
						</table>
						<?php submit_button( esc_html__( 'Save Changes', 'catch-scroll-progress-bar' ) ); ?>
					</div><!-- .option-container -->
				</form>
			</div><!-- progress_main -->
		</div><!-- .content -->
	</div><!-- .content-wrapper -->
</div><!---catch--progress-->
