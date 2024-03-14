<?php
/**
 * Add new Page
 *
 * @package Copy the Code
 * @since 2.0.0
 */

?>
<div class="wrap copy-the-code">
	<div class="wrap">
		<h1><?php echo esc_html( COPY_THE_CODE_TITLE ); ?> <small>v<?php echo esc_html( COPY_THE_CODE_VER ); ?></small></h1>

		<p>
			<a href="<?php echo admin_url( 'edit.php?post_type=copy-to-clipboard' ); ?>" class="button">Back to List</a>
		</p>

		<p class="description"><?php _e( 'Add the new button as per your requirement. See more with <a  target="_blank" href="https://clipboard.agency/doc/" target="_blank">getting started guide</a>', 'copy-the-code' ); ?></p>

		<div id="poststuff">
			<div id="post-body" class="columns-2">
				<div id="post-body-content">

					<form enctype="multipart/form-data" method="post">
						<div class="tabs">
							<div id="tab-general">
								<table class="form-table">
									<tr>
										<th scope="row"><?php _e( 'Selector', 'copy-the-code' ); ?></th>
										<td>
											<fieldset>
												<input type="text" name="selector" class="regular-text" value="<?php echo esc_attr( $data['selector'] ); ?>" />
												<p class="description"><?php _e( 'It is the selector which contain the content  which you want to copy.<br/>Default its &lt;pre&gt; html tag.', 'copy-the-code' ); ?></p>
											</fieldset>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php _e( 'Style', 'copy-the-code' ); ?></th>
										<td>
											<fieldset>
												<select name="style" class="style">
													<option value="button">Button</option>
													<option value="svg-icon">SVG Icon</option>
													<option value="cover">Cover</option>
												</select>

											</fieldset>
											<p class="description"><?php _e( 'Select the button style.', 'copy-the-code' ); ?></p>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php _e( 'Button Position', 'copy-the-code' ); ?></th>
										<td>
											<fieldset>
												<select name="button-position" class="button-position">
													<option value="inside" <?php selected( $data['button-position'], 'inside' ); ?>><?php echo 'Inside'; ?></option>
													<option value="outside" <?php selected( $data['button-position'], 'outside' ); ?>><?php echo 'Outside'; ?></option>
												</select>
												<p class="description"><?php _e( 'Button Position Inside/Outside. Default Inside.', 'copy-the-code' ); ?></p>
											</fieldset>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php _e( 'Copy Format', 'copy-the-code' ); ?></th>
										<td>
											<fieldset>
												<select name="copy-format" class="copy-format">
													<option value="default" <?php selected( $data['copy-format'], 'default' ); ?>><?php echo 'Default'; ?></option>
													<option value="google-docs" <?php selected( $data['copy-format'], 'google-docs' ); ?>><?php echo 'Google Docs'; ?></option>
													<option value="email" <?php selected( $data['copy-format'], 'email' ); ?>><?php echo 'Email'; ?></option>
												</select>
												<p class="description"><?php _e( 'Copy the content for specific format.', 'copy-the-code' ); ?></p>
											</fieldset>
										</td>
									</tr>									
								</table>
							</div>
							<div id="tab-style">
								<table class="form-table">
									<tr>
										<th scope="row"><?php _e( 'Button Text', 'copy-the-code' ); ?></th>
										<td>
											<fieldset>
												<input type="text" name="button-text" class="regular-text" value="<?php echo esc_attr( $data['button-text'] ); ?>" />
												<p class="description"><?php _e( 'Copy button text. Default \'Copy\'.', 'copy-the-code' ); ?></p>
											</fieldset>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php _e( 'Button Copy Text', 'copy-the-code' ); ?></th>
										<td>
											<fieldset>
												<input type="text" name="button-copy-text" class="regular-text" value="<?php echo esc_attr( $data['button-copy-text'] ); ?>" />
												<p class="description"><?php _e( 'Copy button text which appear after click on it. Default \'Copied!\'.', 'copy-the-code' ); ?></p>
											</fieldset>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php _e( 'Button Title', 'copy-the-code' ); ?></th>
										<td>
											<fieldset>
												<input type="text" name="button-title" class="regular-text" value="<?php echo esc_attr( $data['button-title'] ); ?>" />
												<p class="description"><?php _e( 'It is showing on hover on the button. Default \'Copy to Clipboard\'.', 'copy-the-code' ); ?></p>
											</fieldset>
										</td>
									</tr>
								</table>
							</div>
						</div>

						<input type="hidden" name="message" value="saved" />
						<?php wp_nonce_field( 'copy-the-code-nonce', 'copy-the-code' ); ?>

						<?php submit_button( 'Create' ); ?>
					</form>
				</div>
				<div class="postbox-container" id="postbox-container-1">
					<h3>Preview:</h3>
					<div id="preview"></div>

					<?php
					/**
					 * Customization Options
					 */
					$stored = get_option( 'ctc_default_style' );
					if ( empty( $stored ) ) {
						$stored = array();
					}

					$data    = array(
						'btn_color'          => isset( $stored['btn_color'] ) ? $stored['btn_color'] : '#424242',
						'btn_bg_color'       => isset( $stored['btn_bg_color'] ) ? $stored['btn_bg_color'] : '#e1e3e8',
						'btn_font_size'      => isset( $stored['btn_font_size'] ) ? $stored['btn_font_size'] : '14',
						'btn_line_height'    => isset( $stored['btn_line_height'] ) ? $stored['btn_line_height'] : '18',
						'btn_t_padding'      => isset( $stored['btn_t_padding'] ) ? $stored['btn_t_padding'] : '10',
						'btn_r_padding'      => isset( $stored['btn_r_padding'] ) ? $stored['btn_r_padding'] : '20',
						'btn_b_padding'      => isset( $stored['btn_b_padding'] ) ? $stored['btn_b_padding'] : '10',
						'btn_l_padding'      => isset( $stored['btn_l_padding'] ) ? $stored['btn_l_padding'] : '20',
						'btn_l_margin'       => isset( $stored['btn_l_margin'] ) ? $stored['btn_l_margin'] : '0',
						'btn_t_margin'       => isset( $stored['btn_t_margin'] ) ? $stored['btn_t_margin'] : '0',
						'btn_r_margin'       => isset( $stored['btn_r_margin'] ) ? $stored['btn_r_margin'] : '0',
						'btn_b_margin'       => isset( $stored['btn_b_margin'] ) ? $stored['btn_b_margin'] : '0',
						'btn_tl_radius'      => isset( $stored['btn_tl_radius'] ) ? $stored['btn_tl_radius'] : '0',
						'btn_tr_radius'      => isset( $stored['btn_tr_radius'] ) ? $stored['btn_tr_radius'] : '0',
						'btn_br_radius'      => isset( $stored['btn_br_radius'] ) ? $stored['btn_br_radius'] : '0',
						'btn_bl_radius'      => isset( $stored['btn_bl_radius'] ) ? $stored['btn_bl_radius'] : '0',
						'btn_h_color'        => isset( $stored['btn_h_color'] ) ? $stored['btn_h_color'] : '#424242',
						'btn_h_bg_color'     => isset( $stored['btn_h_bg_color'] ) ? $stored['btn_h_bg_color'] : '#d0d1d6',

						// SVG Icon
						'svg_icon_color'     => isset( $stored['svg_icon_color'] ) ? $stored['svg_icon_color'] : '#23282d',
						'svg_icon_h_color'   => isset( $stored['svg_icon_h_color'] ) ? $stored['svg_icon_h_color'] : '#23282d',
						'svg_icon_width'     => isset( $stored['svg_icon_width'] ) ? $stored['svg_icon_width'] : '20',
						'svg_icon_t_padding' => isset( $stored['svg_icon_t_padding'] ) ? $stored['svg_icon_t_padding'] : '10',
						'svg_icon_r_padding' => isset( $stored['svg_icon_r_padding'] ) ? $stored['svg_icon_r_padding'] : '10',
						'svg_icon_b_padding' => isset( $stored['svg_icon_b_padding'] ) ? $stored['svg_icon_b_padding'] : '10',
						'svg_icon_l_padding' => isset( $stored['svg_icon_l_padding'] ) ? $stored['svg_icon_l_padding'] : '10',

						// Cover
						'cover_color'        => isset( $stored['cover_color'] ) ? $stored['cover_color'] : '#ffffff',
						'cover_font_size'    => isset( $stored['cover_font_size'] ) ? $stored['cover_font_size'] : '14',
					);
					$is_free = ctc_fs()->is_not_paying() ? 'is-free' : 'is-pro';
					?>
					<form id="ctc-style-form" class="<?php echo $is_free; ?>">

						<?php if ( ctc_fs()->is_not_paying() ) { ?>
							<div class="upgrade">
								<a href="<?php echo ctc_fs()->get_upgrade_url(); ?>">
									<?php esc_html_e( 'Upgrade to Premium to Access', 'copy-the-code' ); ?>
								</a>
							</div>
						<?php } ?>

						<table class="style-svg-icon widefat striped">
							<tr>
								<th colspan="2">
									<b>Normal Style</b>
								</th>
							</tr>
							<tr>
								<td>Icon Color</td>
								<td>
									<input type="color" class="ctc-svg-icon-color" name="ctc-svg-icon-color" value="<?php echo $data['svg_icon_color']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Icon Size</td>
								<td>
									<input type="number" class="ctc-svg-icon-width" name="ctc-svg-icon-width" value="<?php echo $data['svg_icon_width']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Icon Padding</td>
								<td>
									<input type="number" class="ctc-svg-icon-t-padding" name="ctc-svg-icon-t-padding" value="<?php echo $data['svg_icon_t_padding']; ?>" />
									<input type="number" class="ctc-svg-icon-r-padding" name="ctc-svg-icon-r-padding" value="<?php echo $data['svg_icon_r_padding']; ?>" />
									<input type="number" class="ctc-svg-icon-b-padding" name="ctc-svg-icon-b-padding" value="<?php echo $data['svg_icon_b_padding']; ?>" />
									<input type="number" class="ctc-svg-icon-l-padding" name="ctc-svg-icon-l-padding" value="<?php echo $data['svg_icon_l_padding']; ?>" />
								</td>
							</tr>
							<tr>
								<th colspan="2">
									<b>Hover Style</b>
								</th>
							</tr>
							<tr>
								<td>Icon Color</td>
								<td>
									<input type="color" class="ctc-svg-icon-h-color" name="ctc-svg-icon-h-color" value="<?php echo $data['svg_icon_h_color']; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right">
									<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'ctc-save-style' ); ?>" />
									<input type="submit" class="button button-primary" value="Save Changes" />
								</td>
							</tr>
						</table>
						<table class="style-cover widefat striped">
							<tr>
								<td>Text Color</td>
								<td>
									<input type="color" class="ctc-cover-color" name="ctc-cover-color" value="<?php echo $data['cover_color']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Font Size</td>
								<td>
									<input type="number" class="ctc-cover-font-size" name="ctc-cover-font-size" value="<?php echo $data['cover_font_size']; ?>" />
								</tr>
							<tr>
							<tr>
								<td colspan="2" align="right">
									<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'ctc-save-style' ); ?>" />
									<input type="submit" class="button button-primary" value="Save Changes" />
								</td>
							</tr>
						</table>
						<table class="style-button widefat striped">
							<tr>
								<th colspan="2">
									<b>Normal Style</b>
								</th>
							</tr>
							<tr>
								<td>Button Color</td>
								<td>
									<input type="color" class="ctc-btn-color" name="ctc-btn-color" value="<?php echo $data['btn_color']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Button Background Color</td>
								<td>
									<input type="color" class="ctc-btn-bg-color" name="ctc-btn-bg-color" value="<?php echo $data['btn_bg_color']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Font Size</td>
								<td>
									<input type="number" class="ctc-btn-font-size" name="ctc-btn-font-size" value="<?php echo $data['btn_font_size']; ?>" />
								</tr>
							<tr>
								<td>Line Height</td>
								<td>
									<input type="number" class="ctc-btn-line-height" name="ctc-btn-line-height" value="<?php echo $data['btn_line_height']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Button Padding</td>
								<td>
									<input type="number" class="ctc-btn-t-padding" name="ctc-btn-t-padding" value="<?php echo $data['btn_t_padding']; ?>" />
									<input type="number" class="ctc-btn-r-padding" name="ctc-btn-r-padding" value="<?php echo $data['btn_r_padding']; ?>" />
									<input type="number" class="ctc-btn-b-padding" name="ctc-btn-b-padding" value="<?php echo $data['btn_b_padding']; ?>" />
									<input type="number" class="ctc-btn-l-padding" name="ctc-btn-l-padding" value="<?php echo $data['btn_l_padding']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Button Margin</td>
								<td>
									<input type="number" class="ctc-btn-t-margin" name="ctc-btn-t-margin" value="<?php echo $data['btn_t_margin']; ?>" />
									<input type="number" class="ctc-btn-r-margin" name="ctc-btn-r-margin" value="<?php echo $data['btn_r_margin']; ?>" />
									<input type="number" class="ctc-btn-b-margin" name="ctc-btn-b-margin" value="<?php echo $data['btn_b_margin']; ?>" />
									<input type="number" class="ctc-btn-l-margin" name="ctc-btn-l-margin" value="<?php echo $data['btn_l_margin']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Border Radius</td>
								<td>
									<input type="number" class="ctc-btn-tl-radius" name="ctc-btn-tl-radius" value="<?php echo $data['btn_tl_radius']; ?>" />
									<input type="number" class="ctc-btn-tr-radius" name="ctc-btn-tr-radius" value="<?php echo $data['btn_tr_radius']; ?>" />
									<input type="number" class="ctc-btn-br-radius" name="ctc-btn-br-radius" value="<?php echo $data['btn_br_radius']; ?>" />
									<input type="number" class="ctc-btn-bl-radius" name="ctc-btn-bl-radius" value="<?php echo $data['btn_bl_radius']; ?>" />
								</td>
							</tr>
							<tr>
								<th colspan="2">
									<b>Hover Style</b>
								</th>
							</tr>
							<tr>
								<td>Button Color</td>
								<td>
									<input type="color" class="ctc-btn-h-color" name="ctc-btn-h-color" value="<?php echo $data['btn_h_color']; ?>" />
								</td>
							</tr>
							<tr>
								<td>Button Background Color</td>
								<td>
									<input type="color" class="ctc-btn-h-bg-color" name="ctc-btn-h-bg-color" value="<?php echo $data['btn_h_bg_color']; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right">
									<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'ctc-save-style' ); ?>" />
									<input type="submit" class="button button-primary" value="Save Changes" />
								</td>
							</tr>
						</table>
					</form>

					<div class="postbox">
						<h2 class="hndle"><span><?php _e( 'Getting Started', 'copy-the-code' ); ?></span></h2>
						<div class="inside">
							<div class="table-of-contents">
								<ol class="items">
									<li><a target="_blank" href="https://clipboard.agency/doc/">Way 1 - Copy with Shortcode</a>
										<ol>
											<li><a target="_blank" href="https://clipboard.agency/doc/">Example 1: Copy Same Display Text in
													Clipboard</a></li>
											<li><a target="_blank" href="https://clipboard.agency/doc/">Example 2: Copy Different
													Text than Display Text in Clipboard</a></li>
											<li><a target="_blank" href="https://clipboard.agency/doc/">Shortcode Parameters</a>
											</li>
										</ol>
									</li>
									<li><a target="_blank" href="https://clipboard.agency/doc/">Way 2 - Copy with Target</a>
										<ol>
											<li><a target="_blank" href="https://clipboard.agency/doc/">Use Case 1: How to add the copy
													button to Blockquote</a></li>
											<li><a target="_blank" href="https://clipboard.agency/doc/">Use Case 2: How to add the copy
													button to Code Snippet</a></li>
											<li><a target="_blank" href="https://clipboard.agency/doc/">Use Case 3: How to add the copy button
													to HTML list</a>
											</li>
										</ol>
									</li>
									<li><a target="_blank" href="https://clipboard.agency/doc/">Other Customizations</a></li>
								</ol>
							</div>
						</div>
					</div>

					<div class="postbox">
						<h2 class="hndle"><span><?php _e( 'Support', 'copy-the-code' ); ?></span></h2>
						<div class="inside">
							<p><?php _e( 'Do you have any issue with this plugin? Or Do you have any suggessions?', 'copy-the-code' ); ?></p>
							<p><?php _e( 'Please don\'t hesitate to <a href="https://clipboard.agency/doc/" target="_blank">send request »</a>.', 'copy-the-code' ); ?></p>
						</div>
					</div>

					<?php if ( ctc_fs()->is_not_paying() ) { ?>
						<div class="postbox">
							<h2 class="hndle"><span><?php _e( 'Donate', 'copy-the-code' ); ?></span></h2>
							<div class="inside">
								<p><?php _e( 'Would you like to support the advancement of this plugin?', 'copy-the-code' ); ?></p>
								<a href="https://www.paypal.me/mwaghmare7/" target="_blank" class="button button-primary"><?php _e( 'Donate Now!', 'copy-the-code' ); ?></a>
							</div>
						</div>
					<?php } ?>

				</div>
			</div>
		</div>
	</div>
</div>
