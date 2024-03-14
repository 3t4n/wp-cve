<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<div class="wrap">
	<h1>Float Left Right Advertising Setting</h1>

	<form method="post" action="options.php" novalidate="novalidate">
	<?php
	settings_fields( $this->_optionGroup );
	$flra_options = wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);

	?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="activeplugin">Active Plugin</label></th>
					<td>
						<label><input type="radio" name="<?php echo $this->_optionName?>[flra_is_active]" <?php checked('1',$flra_options['flra_is_active'])?> value="1" />Yes</label>
	                    <label><input type="radio" name="<?php echo $this->_optionName?>[flra_is_active]" <?php checked('0',$flra_options['flra_is_active'])?> value="0" />No</label>
					</td>
				</tr>
                <tr>
                    <th scope="row"><label for="show_only">Show on</label></th>
                    <td>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[show_only][]" value="home" <?php if(in_array('home',$flra_options['show_only'])):?>checked="checked"<?php endif;?>> Front page - Home Page</label><br>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[show_only][]" value="blog" <?php if(in_array('blog',$flra_options['show_only'])):?>checked="checked"<?php endif;?>> Posts page - Blog page</label><br>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[show_only][]" value="any_single" <?php if(in_array('any_single',$flra_options['show_only'])):?>checked="checked"<?php endif;?>> Any Single</label><br>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[show_only][]" value="any_page" <?php if(in_array('any_page',$flra_options['show_only'])):?>checked="checked"<?php endif;?>> Any Page</label><br>
                        <label>By Page ID or Post ID <input type="text" name="<?php echo $this->_optionName?>[show_only_page]" placeholder="Ex: 23,34,99" value="<?php echo $flra_options['show_only_page']?>" style="width: 230px;"></label><br>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="screen_w">Exclude show on</th>
                    <td>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[exclude][]" value="home" <?php if(in_array('home',$flra_options['exclude'])):?>checked="checked"<?php endif;?>> Front page - Home Page</label><br>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[exclude][]" value="blog" <?php if(in_array('blog',$flra_options['exclude'])):?>checked="checked"<?php endif;?>> Posts page - Blog page</label><br>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[exclude][]" value="any_single" <?php if(in_array('any_single',$flra_options['exclude'])):?>checked="checked"<?php endif;?>> Any Single</label><br>
                        <label><input type="checkbox" name="<?php echo $this->_optionName?>[exclude][]" value="any_page" <?php if(in_array('any_page',$flra_options['exclude'])):?>checked="checked"<?php endif;?>> Any Page</label><br>
                        <label>By Page ID or Post ID <input type="text" name="<?php echo $this->_optionName?>[exclude_page]" placeholder="Ex: 23,34,99" value="<?php echo esc_attr($flra_options['exclude_page'])?>" style="width: 230px;"></label><br>
                    </td>
                </tr>
				<tr>
					<th scope="row"><label for="screen_w">Show ads if client <strong>screen width</strong> >=</label></th>
					<td>
						<input type="number" name="<?php echo $this->_optionName?>[screen_w]" id="screen_w" value="<?php echo esc_attr($flra_options['screen_w'])?>"/> px
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="content_w">Main content width:</label></th>
					<td>
						<input type="number" name="<?php echo $this->_optionName?>[content_w]" id="content_w" value="<?php echo esc_attr($flra_options['content_w'])?>"/> px
						<p class="description">Width of your website</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="show_on_mobile">Show Ads on mobile devices</label></th>
					<td>
						<label><input type="radio" name="<?php echo $this->_optionName?>[show_on_mobile]" <?php checked('1',$flra_options['show_on_mobile'])?> value="1" />Yes</label>
	                    <label><input type="radio" name="<?php echo $this->_optionName?>[show_on_mobile]" <?php checked('0',$flra_options['show_on_mobile'])?> value="0" />No</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="">Banner Left:</label></th>
					<td>
						<table>
							<tr>
								<td style="padding:0">
									<input type="number" name="<?php echo $this->_optionName?>[banner_left_w]" id="banner_left_w" value="<?php echo esc_attr($flra_options['banner_left_w'])?>"/> px<br>
									<p class="description">Width of your left banner.</p>
								</td>
								<td style="padding:0 0 0 10px">
									<input type="number" name="<?php echo $this->_optionName?>[banner_left_h]" id="banner_left_h" value="<?php echo esc_attr($flra_options['banner_left_h'])?>"/> px<br>
									<p class="description">Height of your left banner.</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>				
				<tr>
					<th scope="row"><label for="">Banner Right:</label></th>
					<td>
						<table>
							<tr>
								<td style="padding:0">
									<input type="number" name="<?php echo $this->_optionName?>[banner_right_w]" id="banner_right_w" value="<?php echo esc_attr($flra_options['banner_right_w'])?>"/> px<br>
									<p class="description">Width of your right banner.</p>
								</td>
								<td style="padding:0 0 0 10px">
									<input type="number" name="<?php echo $this->_optionName?>[banner_right_h]" id="banner_right_h" value="<?php echo esc_attr($flra_options['banner_right_h'])?>"/> px<br>
									<p class="description">Height of your right banner.</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="margin_l">Margin Left:</label></th>
					<td>
						<input type="number" name="<?php echo $this->_optionName?>[margin_l]" id="margin_l" value="<?php echo esc_attr($flra_options['margin_l'])?>"/> px<br>
						<p class="description">Space banner left to main content.</p>
					</td>
				</tr>				
				<tr>
					<th scope="row"><label for="margin_r">Margin Right:</label></th>
					<td>
						<input type="number" name="<?php echo $this->_optionName?>[margin_r]" id="margin_r" value="<?php echo esc_attr($flra_options['margin_r'])?>"/> px<br>
						<p class="description">Space banner right to main content.</p>
					</td>
				</tr>				
				<tr>
					<th scope="row"><label for="margin_t">Margin Top:</label></th>
					<td>
						<input type="number" name="<?php echo $this->_optionName?>[margin_t]" id="margin_t" value="<?php echo esc_attr($flra_options['margin_t'])?>"/> px<br>
						<p class="description">Space two banner to top.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="margin_t_scroll">Margin Top - After scroll:</label></th>
					<td>
						<input type="number" min="0" name="<?php echo $this->_optionName?>[margin_t_scroll]" id="margin_t_scroll" value="<?php echo esc_attr($flra_options['margin_t_scroll'])?>"/> px<br>
						<p class="description">Space two banner to top when scroll over Margin Top above.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="z_index">Z-index</label></th>
					<td>
						<input type="number" name="<?php echo $this->_optionName?>[z_index]" id="z_index" value="<?php echo esc_attr($flra_options['z_index'])?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="html_code_l">HTML left Code:<br><small>Put HTML code for your left ads</small></label></th>
					<td>
						<textarea rows="5" cols="50" name="<?php echo $this->_optionName?>[html_code_l]" id="html_code_l"><?php echo esc_textarea($flra_options['html_code_l'])?></textarea>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><label for="html_code_r">HTML right Code:<br><small>Put HTML code for your right ads</small></label></th>
					<td>
						<textarea rows="5" cols="50" name="<?php echo $this->_optionName?>[html_code_r]" id="html_code_r"><?php echo esc_textarea($flra_options['html_code_r'])?></textarea>
					</td>
				</tr>
				<?php do_settings_fields('flra-options-group', 'flra-default'); ?>
			</tbody>
		</table>
		<?php do_settings_sections('flra-options-group'); ?>
		<?php submit_button();?>
	</form>
</div>
