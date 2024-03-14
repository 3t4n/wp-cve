<?php
defined( 'ABSPATH' ) || die();
$wl_wsio_options = weblizar_wsio_get_options(); ?>
<div class="col-xs-8 tab-content" id="spa_general">
	<!-- plugin dashboard Main class div setting -->
	<div class="tab-pane col-md-12 block ui-tabs-panel active" id="general-option">
		<!-- plugin template selection setting -->
		<div class="row ml-3">
			<div class="col-md-9 option">
				<h1><?php esc_html_e( 'Images Alt and Title Override Options', WSIO_TEXT_DOMAIN ); ?>
				</h1>
				<div class="tab-content">
					<form method="post" id="weblizar_wsio_section_general">
						<?php $nonce = wp_create_nonce( 'wl_wsio_settings' ); ?>
						<input type="hidden" name="security" value="<?php echo esc_attr( $nonce ); ?>">
						<div class="col-md-12 form-group">
							<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e( 'Override Existing Title', WSIO_TEXT_DOMAIN ); ?></label>
									<p class=""><?php esc_html_e( 'Do you want to Over Ride existing title tags?', WSIO_TEXT_DOMAIN ); ?>
									</p>
									<br /><br />
								</div>
								<div class="col-md-6">
									<input data-toggle="toggle" data-offstyle="off" type="checkbox" 
									<?php
									if ( $wl_wsio_options['wsio_override_title_value'] == 'on' ) {
										echo esc_attr( "checked='checked'" );
									}
									?>
																									id="wsio_override_title_value" name="wsio_override_title_value">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e( 'Title Value', WSIO_TEXT_DOMAIN ); ?></label>
									<div class="col-md-12">
										<p class=""><?php esc_html_e( 'The Title Tag will be dynamically replaced by the above value.', WSIO_TEXT_DOMAIN ); ?>
										</p>
										<p class=""><?php esc_html_e( 'You can managed by enable and disable the tag selection option', WSIO_TEXT_DOMAIN ); ?>
										</p>
									</div>
								</div>
								<div class="col-md-6">
									<select id='wsio_title_tag_value' name="wsio_title_tag_value[]" multiple="multiple" class="form-control">
										<?php
										$title_tag_value = array( '%site_name', '%name', '%title', '%category', '%tags' );
										if ( is_array( $title_tag_value ) ) {
											foreach ( $title_tag_value as $title_tag ) {
												?>
												<option value="<?php echo esc_attr( $title_tag ); ?>" 
																		  <?php
																			if ( is_array( $wl_wsio_options['wsio_title_tag_value'] ) ) {
																				foreach ( $wl_wsio_options['wsio_title_tag_value'] as $selected_list ) {
																					if ( $selected_list == $title_tag ) {
																						echo esc_attr( 'selected="selected"' );
																					}
																				}
																			}
																			?>
																									><?php esc_html_e( $title_tag, WSIO_TEXT_DOMAIN ); ?>
												</option>
												<?php
											}
										}
										?>
									</select>
									<br>
									<blockquote>
										<b><?php echo esc_html__( '%Site Name - It will insert Site Name', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Image Name - It will insert Image Name', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Post Title - It will insert Post Title', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Post Category - It will insert Post Categories', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Post Tag - It will insert Post Tag', WSIO_TEXT_DOMAIN ); ?></b>
									</blockquote>
									<br>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e( 'Custom Title Value', WSIO_TEXT_DOMAIN ); ?></label>
									<p class=""><?php esc_html_e( 'Do you want to Over Ride existing Title with custom values?', WSIO_TEXT_DOMAIN ); ?>
									</p>
								</div>
								<div class="col-md-6">
									<input placeholder="Add custom title value" class="form-control" type="text" id="wsio_override_title_custom_value"                       name="wsio_override_title_custom_value" value="<?php if ($wl_wsio_options['wsio_override_title_custom_value']) {
										echo esc_attr_e($wl_wsio_options['wsio_override_title_custom_value']);
									} ?>">
								</div>                  
							</div>
						</div>
						<div class="col-md-12 form-group">
							<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e( 'Override Existing Alt Attribute', WSIO_TEXT_DOMAIN ); ?></label>
									<p class=""><?php esc_html_e( 'Do you want to Over Ride existing alt tags?', WSIO_TEXT_DOMAIN ); ?>
									</p>
									<br /><br />
								</div>
								<div class="col-md-6">
									<input data-toggle="toggle" data-offstyle="off" type="checkbox" 
									<?php
									if ( $wl_wsio_options['wsio_override_alt_value'] == 'on' ) {
										echo esc_attr( "checked='checked'" );
									}
									?>
									id="wsio_override_alt_value" name="wsio_override_alt_value">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e( 'Alt Attribute Value', WSIO_TEXT_DOMAIN ); ?></label>
									<div class="col-md-12">
										<p class=""><?php esc_html_e( 'The Alt attributes will be dynamically replaced by the above value.', WSIO_TEXT_DOMAIN ); ?>
										</p>
										<p class=""><?php esc_html_e( 'You can managed by select option and enable/disable the tag attributes from output', WSIO_TEXT_DOMAIN ); ?>
										</p>
										<br /><br />
									</div>
								</div>
								<div class="col-md-6">
									<select id='wsio_alt_attribute_value' name="wsio_alt_attribute_value[]" multiple="multiple" class="form-control">
										<?php
										$alt_attribute_value = array( '%site_name', '%name', '%title', '%category', '%tags' );
										if ( is_array( $alt_attribute_value ) ) {
											foreach ( $alt_attribute_value as $alt_attribute ) {
												?>
												<option value="<?php echo esc_attr( $alt_attribute ); ?>" 
																		  <?php
																			if ( is_array( $wl_wsio_options['wsio_alt_attribute_value'] ) ) {
																				foreach ( $wl_wsio_options['wsio_alt_attribute_value'] as $selected_list ) {
																					if ( $selected_list == $alt_attribute ) {
																						echo esc_attr( 'selected="selected"' );
																					}
																				}
																			}
																			?>
																										><?php esc_html_e( $alt_attribute, WSIO_TEXT_DOMAIN ); ?>
												</option>
												<?php
											}
										}
										?>
									</select>
									<br />
									<blockquote>
										<b><?php echo esc_html__( '%Site Name - It will insert Site Name', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Image Name - It will insert Image Name', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Post Title - It will insert Post Title', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Post Category - It will insert Post Categories', WSIO_TEXT_DOMAIN ); ?></b>
										<br>
										<b><?php echo esc_html__( '%Post Tag - It will insert Post Tag', WSIO_TEXT_DOMAIN ); ?>
										</b>
									</blockquote>
								</div>
							</div>
							<div class="row">
								<br />
								<div class="col-md-6">
									<label><?php esc_html_e( 'Custom Alt Attribute', WSIO_TEXT_DOMAIN ); ?></label>
									<p class=""><?php esc_html_e( 'Do you want to Over Ride existing alt with custom tags?', WSIO_TEXT_DOMAIN ); ?>
									</p>
									<br /><br />
								</div>
								<div class="col-md-6">
									<input placeholder="Add custom alt value" class="form-control mt-3" type="text" id="wsio_override_alt_custom_value" name="wsio_override_alt_custom_value" value="<?php if ($wl_wsio_options['wsio_override_alt_custom_value']) {
									echo esc_attr_e($wl_wsio_options['wsio_override_alt_custom_value']);
									} ?>">
								</div>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="1" id="weblizar_wsio_settings_save_section_general" name="weblizar_wsio_settings_save_section_general" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e( 'Restore Defaults', WSIO_TEXT_DOMAIN ); ?>" onclick="weblizar_wsio_option_data_reset('section_general');">
							<input class="button button-primary left" type="button" name="save" value="<?php esc_attr_e( 'Save Options', WSIO_TEXT_DOMAIN ); ?>" onclick="weblizar_wsio_option_data_save('section_general')">
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-3">
				<div class="update_pro_button"><a target="_blank" href="https://weblizar.com/plugins/seo-image-optimizer-pro/"><?php esc_html_e( 'Get Pro Now $11', WSIO_TEXT_DOMAIN ); ?></a>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane col-md-12 block ui-tabs-panel deactive" id="image-size-option">
		<div class="row ml-3">
			<div class="col-md-9 option">
				<h1><?php esc_html_e( 'Images Re-sizing and Compression Options', WSIO_TEXT_DOMAIN ); ?>
				</h1>
				<div class="tab-content">
					<form method="post" id="weblizar_wsio_image_size_option">
						<?php $nonce = wp_create_nonce( 'wl_wsio_img_settings' ); ?>
						<input type="hidden" name="security" value="<?php echo esc_attr( $nonce ); ?>">
						<div class="col-md-12 form-group">
							<div class="col-md-12">
								<label><?php esc_html_e( 'Re-sizing options', WSIO_TEXT_DOMAIN ); ?></label>
								<p style="max-width:700px"><?php esc_html_e( 'The following settings will apply to only when image uploaded.', WSIO_TEXT_DOMAIN ); ?>
								</p>
								<br />
								<div class="row">
									<div class="col-md-4">
										<label><?php esc_html_e( 'Enable re-sizing', WSIO_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="col-md-8">
										<input data-toggle="toggle" data-offstyle="off" type="checkbox" 
										<?php
										if ( $wl_wsio_options['wsio_image_resize_yesno'] == 'on' ) {
																											echo esc_attr( "checked='checked'" );
										}
										?>
																										 id="wsio_image_resize_yesno" name="wsio_image_resize_yesno">
									</div>
								</div>
								<br /><br />
							</div>

							<div class="col-md-12">
								<div class="row">
									<div class="col-md-4">
										<label><?php esc_html_e( 'Max image dimensions', WSIO_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="col-md-8">
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Maximum width and height', WSIO_TEXT_DOMAIN ); ?></span>
											</legend>
											<label><?php esc_html_e( 'Max width  ', WSIO_TEXT_DOMAIN ); ?></label>
											<input style="margin-right: 10px; display: initial; width: 15%;" class="form-control" name="wsio_image_width" id="wsio_image_width" type="text" value="<?php echo esc_attr( $wl_wsio_options['wsio_image_width'] ); ?>">
											<label><?php esc_html_e( 'Max height  ', WSIO_TEXT_DOMAIN ); ?></label>
											<input style="display: initial; width: 15%;" class="form-control" name="wsio_image_height" id="wsio_image_height" type="text" value="<?php echo esc_attr( $wl_wsio_options['wsio_image_height'] ); ?>">
										</fieldset>
										<br />
										<blockquote>
											<b><?php echo esc_html__( 'Note- ', WSIO_TEXT_DOMAIN ); ?></b><?php echo esc_html__( 'Set to zero or very high value to prevent resizing in that dimension.', WSIO_TEXT_DOMAIN ); ?><br /><b><?php echo esc_html__( 'Recommended values-', WSIO_TEXT_DOMAIN ); ?><code><?php echo esc_html__( '1200', WSIO_TEXT_DOMAIN ); ?></code></b>
										</blockquote>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 form-group">
							<label><?php esc_html_e( 'Compression options', WSIO_TEXT_DOMAIN ); ?></label>
							<p style="max-width:700px"><?php esc_html_e( 'The following settings will apply to only when image uploaded.', WSIO_TEXT_DOMAIN ); ?>
							</p>
							<br />
							<div class="row">
								<div class="col-md-4">
									<label><?php esc_html_e( 'Force Image re-compression', WSIO_TEXT_DOMAIN ); ?></label>
								</div>
								<div class="col-md-8">
									<?php $wsio_recompress_yesno = $wl_wsio_options['wsio_image_recompress_yesno']; ?>
									<select class="form-control" name="wsio_image_recompress_yesno" id="wsio_image_recompress_yesno">
										<option value="off" <?php echo selected( $wsio_recompress_yesno, 'off' ); ?>><?php esc_html_e( 'NO - only re-compress the images if option selected', WSIO_TEXT_DOMAIN ); ?>
										</option>
										<option value="on" <?php echo selected( $wsio_recompress_yesno, 'on' ); ?>><?php esc_html_e( 'YES - re-compress all uploaded images', WSIO_TEXT_DOMAIN ); ?>
										</option>
									</select>
								</div>
								<br /><br /><br />
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-4">
										<label><?php esc_html_e( 'Image compression level', WSIO_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="col-md-8">
										<?php $wsio_quality = $wl_wsio_options['wsio_image_quality']; ?>
										<input class="form-control hc_range wsio-img-qual" name="wsio-img-comp" id="wsio-img-comp" type="range" step="1" min="1" max="100" value="<?php echo esc_attr( $wl_wsio_options['wsio_image_quality'] ); ?>" data-rangeSlider>
										<input type="text" class="form-control hc_text" name="wsio_image_quality" value="<?php echo esc_attr( $wl_wsio_options['wsio_image_quality'] ); ?>" id="wsio_image_quality" readonly>
										<p style="margin:5px 0px;"><b style="margin-left: 5px; font-size: 15px;"><?php esc_html_e( '%', WSIO_TEXT_DOMAIN ); ?></b>
										</p>
										<br />
										<blockquote>
											<code><?php echo esc_html__( '1', WSIO_TEXT_DOMAIN ); ?></code>
											<?php echo esc_html__( '= low quality (smallest files)', WSIO_TEXT_DOMAIN ); ?>
											<br>
											<code><?php echo esc_html__( '100', WSIO_TEXT_DOMAIN ); ?></code>
											<?php echo esc_html__( '= best quality (largest files)', WSIO_TEXT_DOMAIN ); ?>
											<br>
											<code><?php echo esc_html__( '90', WSIO_TEXT_DOMAIN ); ?></code>
											<?php echo esc_html__( '= Recommended value', WSIO_TEXT_DOMAIN ); ?>
										</blockquote>
									</div>
								</div>
							</div>
						</div>
						<script>
							var slider = document.getElementById("wsio-img-comp");
							var output = document.getElementById("wsio-range-val");

							var x = slider.value;
							var y = x / 1000;
							// output.innerHTML = y;

							slider.oninput = function() {
								var x = slider.value;
								var y = x / 1000;
								// output.innerHTML = y;
							}
						</script>
						<div class="restore">
							<input type="hidden" value="1" id="weblizar_wsio_settings_save_image_size_option" name="weblizar_wsio_settings_save_image_size_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e( 'Restore Defaults', WSIO_TEXT_DOMAIN ); ?>" onclick="weblizar_wsio_option_data_reset('image_size_option');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e( 'Save Options', WSIO_TEXT_DOMAIN ); ?>" onclick="weblizar_wsio_option_data_save('image_size_option')">
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-3">
				<div class="update_pro_button"><a target="_blank" href="https://weblizar.com/plugins/seo-image-optimizer-pro/"><?php esc_attr_e( 'Get Pro $11', WSIO_TEXT_DOMAIN ); ?></a>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane col-md-12 block ui-tabs-panel deactive" id="how-work-option">
		<div class="row ml-3">
			<div class="col-md-9 option">
				<div class="tab-content">
					<div class="col-md-12 form-group">
						<label><?php esc_html_e( 'How Does it Work?', WSIO_TEXT_DOMAIN ); ?></label>
						<ul>
							<li><?php esc_html_e( '1. The plugin dynamically replaces the alt tags with the pattern specified by you. It makes no changes to the database.', WSIO_TEXT_DOMAIN ); ?>
							</li>
							<li><?php esc_html_e( '2. Since there are no changes to the database, one can have different alt tags for same images on different pages / posts.', WSIO_TEXT_DOMAIN ); ?>
							</li>
							<li><?php esc_html_e( '3. Site name - The name of the site that is setted up on WordPress settings', WSIO_TEXT_DOMAIN ); ?>
							</li>
							<li><?php esc_html_e( "4. Image name - The title of the image that was uploaded. ( To avoid duplicate names, it's highly recommended always use this option. )", WSIO_TEXT_DOMAIN ); ?>
							</li>
							<li><?php esc_html_e( '5. Post title - The title given to your post.', WSIO_TEXT_DOMAIN ); ?>
							</li>
							<li><?php esc_html_e( '6. Post category - The name of the category that the post belongs to', WSIO_TEXT_DOMAIN ); ?>
							</li>
						</ul>
						<blockquote>
							<b><?php echo esc_html__( '%Site Name - It will insert Site Name', WSIO_TEXT_DOMAIN ); ?>
							</b><br><b><?php echo esc_html__( '%Image Name - It will insert Image Name', WSIO_TEXT_DOMAIN ); ?>
							</b><br><b><?php echo esc_html__( '%Post Title - It will insert Post Title', WSIO_TEXT_DOMAIN ); ?>
							</b><br><b><?php echo esc_html__( '%Post Category - It will insert Post Categories', WSIO_TEXT_DOMAIN ); ?></b><br><b><?php echo esc_html__( '%Post Tag - It will insert Post Tag', WSIO_TEXT_DOMAIN ); ?></b>
						</blockquote>
					</div>
					<div class="col-md-12 form-group">
						<label><?php esc_html_e( 'Why Optimize Alt Tags', WSIO_TEXT_DOMAIN ); ?></label>
						<ul>
							<li>
								<p><?php echo esc_html__( '1. According to', WSIO_TEXT_DOMAIN ); ?>&nbsp;<a target="_blank" href="http://googlewebmastercentral.blogspot.in/2007/12/using-alt-attributes-smartly.html"><?php echo esc_html__( 'this post', WSIO_TEXT_DOMAIN ); ?></a>&nbsp;<?php echo esc_html__( 'on the Google Webmaster Blog, Google tends to focus on the information in the ALT text. Creating a optimized alt tags can bring more traffic from Search Engines', WSIO_TEXT_DOMAIN ); ?>
								</p>
							</li>
							<li><?php esc_html_e( '2. Take note that the plugin does not makes changes to the database. It dynamically replaces the tags at the times of page load.', WSIO_TEXT_DOMAIN ); ?>
							</li>
						</ul>
					</div>
					<div class="col-md-12 form-group">
						<label><?php esc_html_e( 'Recommended value for image re-size', WSIO_TEXT_DOMAIN ); ?></label>
						<ul>
							<li><?php esc_html_e( '1. Image dimensions : Set to zero or very high value to prevent resizing in that dimension.', WSIO_TEXT_DOMAIN ); ?>
							</li>
							<li><b><?php echo esc_html__( '2. Recommended values:', WSIO_TEXT_DOMAIN ); ?><code><?php echo esc_html__( '1200', WSIO_TEXT_DOMAIN ); ?></code></b>
							</li>
						</ul>
					</div>
					<div class="col-md-12 form-group">
						<label><?php esc_html_e( 'Recommended value for image compresstion', 'naf_TEXT_DOMAIN' ); ?></label>
						<ul>
							<li><b><?php echo esc_html__( '1. Smallest files :', WSIO_TEXT_DOMAIN ); ?><code><?php echo esc_html__( '1', WSIO_TEXT_DOMAIN ); ?></code><?php echo esc_html__( '= low quality (smallest files)', WSIO_TEXT_DOMAIN ); ?></b>
							</li>
							<li><b><?php echo esc_html__( '2. Largest files :', WSIO_TEXT_DOMAIN ); ?><code><?php echo esc_html__( '100', WSIO_TEXT_DOMAIN ); ?></code><?php echo esc_html__( '= best quality (largest files)', WSIO_TEXT_DOMAIN ); ?></b>
							</li>
							<li><b><?php echo esc_html__( '3. Recommended files :', WSIO_TEXT_DOMAIN ); ?><code><?php echo esc_html__( '90', WSIO_TEXT_DOMAIN ); ?></code><?php echo esc_html__( '= Recommended value', WSIO_TEXT_DOMAIN ); ?></b>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="update_pro_button"><a target="_blank" href="https://weblizar.com/plugins/seo-image-optimizer-pro/"><?php esc_html_e( 'Get Pro $11', WSIO_TEXT_DOMAIN ); ?></a>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
