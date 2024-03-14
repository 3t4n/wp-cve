<!-- Dashboard Settings panel content -->
<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

?>
<div class="row">
	<div class="post-social-wrapper clearfix">
		<div class="col-md-12 post-social-item">
			<div class="panel panel-default">
				<div class="panel-heading padding-none">
					<div class="post-social post-social-xs" id="post-social-5">
						<div class="text-center padding-all text-center">
							<div class="textbox text-white   margin-bottom settings-title">
								<?php esc_html_e('Login Settings', WEBLIZAR_ACL); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php //var_dump($login_form_position); die; ?>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Login Form Position', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<select id="login_form_position" name="login_form_position" class="standard-dropdown" onchange='form_position_change()'>
							<option value="default" <?php if ($login_form_position == "default") echo esc_attr("selected"); ?>><?php esc_html_e('Default', WEBLIZAR_ACL); ?></option>
							<option value="lf_float_style" <?php if ($login_form_position == "lf_float_style") echo esc_attr("selected"); ?>><?php esc_html_e('Floating', WEBLIZAR_ACL); ?></option>
							<option value="lf_customize_style" <?php if ($login_form_position == "lf_customize_style") echo esc_attr("selected"); ?>><?php esc_html_e('Floating With Customization', WEBLIZAR_ACL); ?></option>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div id="div-login-float" class="lf_float_style" style="display:none;">
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e('Float Settings', WEBLIZAR_ACL); ?></th>
				<td></td>
			</tr>
			<tr class="radio-span" style="border-bottom:none;">
				<td>
					<span>
						<input type="radio" name="login_form_float" value="left" id="login_form_floatL" <?php if ($login_form_float == "left") echo esc_attr("checked"); ?> />&nbsp;<label for="login_form_floatL"><?php esc_html_e('Left', WEBLIZAR_ACL) ?></label><br>
					</span>
					<span>
						<input type="radio" name="login_form_float" value="center" id="login_form_floatC" <?php if ($login_form_float == "center") echo esc_attr("checked"); ?> />&nbsp;<label for="login_form_floatC"><?php esc_html_e('Center', WEBLIZAR_ACL) ?></label><br>
					</span>
					<span>
						<input type="radio" name="login_form_float" value="right" id="login_form_floatR" <?php if ($login_form_float == "right") echo esc_attr("checked"); ?> />&nbsp;<label for="login_form_floatR"><?php esc_html_e('Right', WEBLIZAR_ACL) ?></label><br>
					</span>
				</td>
			</tr>
		</table>
	</div>

	<div id="div-login-custom" class="lf_customize_style" style="display:none;">
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e('Floating With Customization Settings', WEBLIZAR_ACL); ?></th>
				<td></td>
			</tr>
			<tr style="border-bottom:none;">
				<td>
					<h4><?php esc_html_e('Left Margin', WEBLIZAR_ACL); ?></h4>
					<div id="button_left" class="size-slider" style="width: 30%;display:inline-block"></div>
					<input type="text" class="slider-text" id="login_form_left" name="login_form_left" readonly="readonly">
					<span class="slider-text-span"></span>
				</td>
			</tr>
			<tr>
				<td>
					<h4><?php esc_html_e('Top Margin', WEBLIZAR_ACL); ?></h4>
					<div id="button_top" class="size-slider" style="width: 30%;display:inline-block"></div>
					<input type="text" class="slider-text" id="login_form_top" name="login_form_top" readonly="readonly">
					<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL) ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<p><?php esc_html_e('Note: This form position setting will be not responsive.', WEBLIZAR_ACL); ?></p>
				</td>
			</tr>
		</table>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Select Background', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<select id="select-login-bg" class="standard-dropdown" name="select-background" onchange='loginbgchange()'>
							<optgroup label="<?php esc_html_e('Select background', WEBLIZAR_ACL); ?>">
								<option value="static-background-color"><?php esc_html_e('Static Background Color', WEBLIZAR_ACL); ?></option>
								<option value="static-background-image"><?php esc_html_e('Static Background Image', WEBLIZAR_ACL); ?></option>
							</optgroup>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="div-login-bg-color" class="no-login-bg">
		<div style="margin-bottom: 10px;">
			<img src="<?php echo esc_url(WEBLIZAR_NALF_PLUGIN_URL . '/images/background-color1.png'); ?>" class="img-responsive" style="margin-right: auto;" alt="">
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e('Background Color', WEBLIZAR_ACL); ?></th>
						<td></td>
					</tr>
					<tr style="border-bottom:none;">
						<td id="td-login-background-color">
							<input id="login-background-color" name="login-background-color" type="text" value="<?php echo esc_attr($login_bg_color); ?>" class="my-color-field" data-default-color="#ffffff" />
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e('Login Form Opacity', WEBLIZAR_ACL) ?></th>
						<td></td>
					</tr>
					<tr style="border-bottom:none;">
						<td>
							<div id="login-opacity-slider" class="size-slider" style="width: 30%;display:inline-block"></div>
							<input type="text" class="slider-text" id="login-opacity-text-box" name="login-opacity-text-box" readonly="readonly">
							<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL) ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div id="div-login-bg-image" class="no-login-bg">
		<div style="margin-bottom: 10px;">
			<img src="<?php echo esc_url(WEBLIZAR_NALF_PLUGIN_URL . '/images/background-image.png'); ?>" class="img-responsive" style="margin-right: auto;">
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e('Background Image', WEBLIZAR_ACL) ?></th>
						<td></td>
					</tr>
					<tr style="border-bottom:none;">
						<td>
							<input type="text" class="pro_text" id="login_bg_image" placeholder="<?php esc_attr_e('No media selected!', WEBLIZAR_ACL) ?>" name="upload_image" disabled="disabled" value="<?php echo esc_attr($login_bg_image); ?>" />
							<input type="button" value="<?php esc_attr_e('Upload', WEBLIZAR_ACL) ?>" id="upload-logo" class="button-primary rcsp_media_upload" />

							<input type="button" value="<?php esc_attr_e('Preview', WEBLIZAR_ACL) ?>" data-toggle="modal" data-target="#about_us_image_builder" id="login-image-previewer" title="Font Awesome Icons" class="button  " onclick="Acl_show_Image_2()" />

							<input type="button" id="display-logo" value="<?php esc_attr_e('Remove', WEBLIZAR_ACL) ?>" class="button " onclick="Acl_login_img_clear();" />

							<!-- Modal -->
							<div class="modal " id="about_us_image_builder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">

											<h4 class="modal-title" id="myModalLabel"><?php esc_html_e('Login Background Image', WEBLIZAR_ACL) ?></h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										</div>
										<div class="modal-body">
											<img class="show_prev_img" src="" style="width:100%; height:50%" id="img" />
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', WEBLIZAR_ACL) ?></button>
										</div>
									</div>
								</div>
							</div>
							<!--End Modal -->
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e('Background Repeat', WEBLIZAR_ACL) ?></th>
						<td></td>
					</tr>
					<tr class="radio-span" style="border-bottom:none;">
						<td>
							<select id="login_bg_repeat" class="standard-dropdown" name="login_bg_repeat">
								<option value="no-repeat"><?php esc_html_e('No Repeat', WEBLIZAR_ACL) ?></option>
								<option value="repeat"><?php esc_html_e('Repeat', WEBLIZAR_ACL) ?></option>
								<option value="repeat-x"><?php esc_html_e('Repeat Horizontally', WEBLIZAR_ACL) ?></option>
								<option value="repeat-y"><?php esc_html_e('Repeat Vertically', WEBLIZAR_ACL) ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e('Background Position', WEBLIZAR_ACL) ?></th>
						<td></td>
					</tr>
					<tr class="radio-span" style="border-bottom:none;">
						<td>
							<select id="login_bg_position" class="standard-dropdown" name="login_bg_position">
								<option value="left top"><?php esc_html_e('Left Top', WEBLIZAR_ACL) ?> </option>
								<option value="left center"><?php esc_html_e('Left Center', WEBLIZAR_ACL) ?> </option>
								<option value="left bottom"><?php esc_html_e('Left Bottom', WEBLIZAR_ACL) ?> </option>
								<option value="right top"><?php esc_html_e('Right Top', WEBLIZAR_ACL) ?> </option>
								<option value="right center"><?php esc_html_e('Right Center', WEBLIZAR_ACL) ?> </option>
								<option value="right bottom"><?php esc_html_e('Right Bottom', WEBLIZAR_ACL) ?></option>
								<option value="center top"><?php esc_html_e('Center Top', WEBLIZAR_ACL) ?> </option>
								<option value="center"><?php esc_html_e('Center Center', WEBLIZAR_ACL) ?> </option>
								<option value="center bottom"><?php esc_html_e('Center Bottom', WEBLIZAR_ACL) ?> </option>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Background Effect', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<select id="login_bg_color_overlay" class="standard-dropdown" name="login_bg_color_overlay">
							<optgroup label="<?php esc_attr_e('Select overlay effect', WEBLIZAR_ACL) ?>">
								<option value="no_effect"><?php esc_html_e('No Overlay Effect', WEBLIZAR_ACL) ?></option>
								<option value="pattern-1"><?php esc_html_e('Overlay Effect 1', WEBLIZAR_ACL) ?> </option>
								<option value="pattern-2"><?php esc_html_e('Overlay Effect 2', WEBLIZAR_ACL) ?> </option>
								<option value="logo"><?php esc_html_e('Overlay Effect 3', WEBLIZAR_ACL) ?> </option>
							</optgroup>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Login Form Width', WEBLIZAR_ACL) ?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p>
					</th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<div id="button-size-slider4" class="size-slider" style="width: 30%;display:inline-block"></div>
						<input type="text" class="slider-text" id="login-width-text-box" name="login-width-text-box" readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL) ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Border Color', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td id="td-login-Border-color">
						<input id="login-Border-color" name="login-Border-color" type="text" value="<?php echo esc_attr($login_border_color); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Border Radius', WEBLIZAR_ACL) ?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p>
					</th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<div id="button-size-slider5" class="size-slider" style="width: 30%;display:inline-block"></div>
						<input type="text" class="slider-text" id="login-Radius-text-box" name="login-Radius-text-box" readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL) ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Border Style', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<select id="login_border_style" class="standard-dropdown" name="login_border_style">
							<option value="none"><?php esc_html_e('None', WEBLIZAR_ACL) ?> </option>
							<option value="solid"><?php esc_html_e('Solid', WEBLIZAR_ACL) ?> </option>
							<option value="dotted"><?php esc_html_e('Dotted', WEBLIZAR_ACL) ?> </option>
							<option value="dashed"><?php esc_html_e('Dashed', WEBLIZAR_ACL) ?> </option>
							<option value="double"><?php esc_html_e('Double', WEBLIZAR_ACL) ?> </option>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Border Thickness', WEBLIZAR_ACL) ?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p>
					</th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<div id="button-size-slider6" class="size-slider" style="width: 30%;display:inline-block"></div>
						<input type="text" class="slider-text" id="login-thickness-text-box" name="login-thickness-text-box" readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL) ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Enable Form Shadow?', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<span>
							<input type="radio" name="enable_form_shadow" value="yes" id="login_enable_shadow1" <?php if ($login_enable_shadow == "yes") echo esc_attr("checked"); ?> />&nbsp;<label for="login_enable_shadow1"><?php esc_html_e('Yes', WEBLIZAR_ACL) ?></label><br>
						</span>
						<span>
							<input type="radio" name="enable_form_shadow" value="no" id="login_enable_shadow2" <?php if ($login_enable_shadow == "no") echo esc_attr("checked"); ?> />&nbsp;<label for="login_enable_shadow2"><?php esc_html_e('No', WEBLIZAR_ACL) ?></label><br>
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Enable Form Language Switcher?', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<span>
							<input type="radio" name="enable_form_language_switcher" value="yes" id="login_enable_language_switcher1" <?php if ($login_enable_language_switcher == "yes") echo esc_attr("checked"); ?> />&nbsp;<label for="login_enable_language_switcher1"><?php esc_html_e('Yes', WEBLIZAR_ACL) ?></label><br>
						</span>
						<span>
							<input type="radio" name="enable_form_language_switcher" value="no" id="login_enable_language_switcher2" <?php if ($login_enable_language_switcher == "no") echo esc_attr("checked"); ?> />&nbsp;<label for="login_enable_language_switcher2"><?php esc_html_e('No', WEBLIZAR_ACL) ?></label><br>
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Form Shadow Color', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td id="td_login_shadow_color">
						<input id="login_shadow_color" name="login_shadow_color" type="text" value="<?php echo esc_attr($login_shadow_color); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Login Form Username / Email Label Text -->
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Username or Email Field Label Text', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<input type="text" placeholder="<?php esc_attr_e('Type username or email field label text', WEBLIZAR_ACL); ?>" id="label_username" name="label_username" value="<?php echo esc_attr(stripslashes($label_username)); ?>" style="width: 70%;">
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!--Username Placeholder Text-->
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Username or Email Field Placeholder Text', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<input type="text" placeholder="<?php esc_attr_e('Type username or email placeholder text', WEBLIZAR_ACL); ?>" id="user_cust_lbl" name="user_cust_lbl" value="<?php echo esc_attr($user_cust_lbl);  ?>" style="width: 70%;">
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Login Form Password Field Label Text -->
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Password Field Label Text', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<input type="text" placeholder="<?php esc_attr_e('Type password field label text', WEBLIZAR_ACL); ?>" id="label_password" name="label_password" value="<?php echo esc_attr($label_password); ?>" style="width: 70%;">
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Password Placeholder Text -->
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Password Field Placeholder Text', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<input type="text" placeholder="<?php esc_attr_e('Type password field placeholder text', WEBLIZAR_ACL); ?>" id="pass_cust_lbl" name="pass_cust_lbl" value="<?php echo esc_attr($pass_cust_lbl); ?>" style="width: 70%;">
					</td>
				</tr>
			</table>
		</div>
	</div>


	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Log In Button Text', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<input type="text" placeholder="<?php esc_attr_e('Type log in button text', WEBLIZAR_ACL); ?>" id="label_loginButton" name="label_loginButton" value="<?php echo esc_attr($label_loginButton); ?>" style="width: 70%;">
					</td>
				</tr>
			</table>
		</div>
	</div>
	<!-- change the text of labels and log in button -->

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Forcefully Redirect', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<select id="login_redirect_force" class="standard-dropdown" name="login_redirect_force">
							<option value="yes"><?php esc_html_e('Yes', WEBLIZAR_ACL) ?> </option>
							<option value="no"><?php esc_html_e('No', WEBLIZAR_ACL) ?> </option>

						</select>
					</td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<input type="text" class="login_force_redirect_url" id="login_force_redirect_url" name="login_force_redirect_url" placeholder="<?php esc_attr_e('Redirect Force URL', WEBLIZAR_ACL) ?>" size="56" value="<?php echo esc_attr($login_force_redirect_url); ?>"><br>
						<span style="color:#ef4238"><?php esc_html_e('Enter the URL to user forcefully redirect to admin panel or another when if hit the site url.', WEBLIZAR_ACL) ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Redirect Users After Login (Not Work For Admin)', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<input type="text" class="login_redirect_user" id="login_redirect_user" name="login_redirect_user" placeholder="<?php esc_attr_e('Redirect URL', WEBLIZAR_ACL) ?>" size="56" value="<?php echo esc_attr($login_redirect_user); ?>"><br>
						<span style="color:#ef4238"><?php esc_html_e('Enter the URL to redirect users after login, Setting will not work for an administrator.', WEBLIZAR_ACL) ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Message Display Above Login Form start-->
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Display Note To User Above Login Form', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<textarea type="text" class="pro_text" placeholder="<?php esc_attr_e('Type Message', WEBLIZAR_ACL); ?>" id="log_form_above_msg" name="log_form_above_msg"><?php echo esc_html($log_form_above_msg); ?></textarea>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<!-- Message Display Above Login Form end-->


	<!-- Message Display Above Login Form start-->
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Display Login Form Password Reset Message', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<textarea type="text" class="pro_text" placeholder="<?php esc_attr_e('Type Message', WEBLIZAR_ACL); ?>" id="login_pass_reset_msg" name="login_pass_reset_msg"><?php echo esc_html($login_pass_reset_msg); ?></textarea>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Message Font Size', WEBLIZAR_ACL) ?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p>
					</th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<div id="button-msg-font-resizer" class="size-slider" style="width: 30%;display:inline-block"></div>
						<input type="text" class="slider-text" id="login-msg-text-size" name="login-msg-text-size" readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL) ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Message Font Color', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td id="td-login-msg-font-color">
						<input id="login-msg-font-color" name="login-msg-font-color" type="text" value="<?php echo esc_attr($login_msg_font_color); ?>" class="my-color-field" data-default-color="#000000" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Tagline message Display Below Login Form start-->
	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Tagline Message Display Below Login Form', WEBLIZAR_ACL); ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td>
						<textarea type="text" rows="4" class="pro_text" placeholder="<?php esc_attr_e('Type Message', WEBLIZAR_ACL); ?>" id="tagline_msg" name="tagline_msg"><?php $edit_tagline_msg = stripslashes($tagline_msg);
																																																echo wp_kses_post($edit_tagline_msg); ?></textarea>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<!-- Tagline message Display Below Login Form end-->

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Tagline Text Color', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td id="td-login-tagline-text-color">
						<input id="login-tagline-text-color" name="login-tagline-text-color" type="text" value="<?php echo esc_attr($login_tagline_text_color); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel col-lg-6">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Tagline Link Color', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr style="border-bottom:none;">
					<td id="td-login-tagline-link-color">
						<input id="login-tagline-link-color" name="login-tagline-link-color" type="text" value="<?php echo esc_attr($login_tagline_link_color); ?>" class="my-color-field" data-default-color="#f00" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel ">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e('Custom CSS', WEBLIZAR_ACL) ?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<textarea id="login_custom_css" name="login_custom_css" placeholder="<?php esc_attr_e('Custom CSS', WEBLIZAR_ACL) ?>" type="text" class="login_custom_css" rows="10" cols="75" style="width:80%"><?php echo esc_html($login_custom_css); ?></textarea>
						<p class="description">
							<?php esc_html_e('Enter any custom css you want to apply on login panel.', WEBLIZAR_ACL); ?>.<br>
							<?php esc_html_e('Note: Please Do Not Use', WEBLIZAR_ACL); ?> <b><?php esc_html_e('Style', WEBLIZAR_ACL) ?></b> <?php esc_html_e('Tag With Custom CSS', WEBLIZAR_ACL); ?>.
						</p>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<button data-dialog2="somedialog2" class="dialog-button2" style="display:none"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL) ?></button>
	<div id="somedialog2" class="dialog" style="position: fixed; z-index: 9999;">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Login', WEBLIZAR_ACL) ?></strong> <?php esc_html_e('Setting Save Successfully', WEBLIZAR_ACL) ?></h2>
				<div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button2"><?php esc_html_e('Close', WEBLIZAR_ACL) ?></button></div>
			</div>
		</div>
	</div>
	<button data-dialog8="somedialog8" class="dialog-button8" style="display:none"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL) ?></button>
	<div id="somedialog8" class="dialog" style="position: fixed; z-index: 9999;">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Login', WEBLIZAR_ACL) ?></strong> <?php esc_html_e('Setting Reset Successfully', WEBLIZAR_ACL) ?></h2>
				<div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button8"><?php esc_html_e('Close', WEBLIZAR_ACL) ?></button></div>
			</div>
		</div>
	</div>
	
	<div class="panel panel-primary save-button-block">
		<div class="panel-body">
			<div class="pull-left">
				<button type="button" onclick="return Custom_login_login('loginbgSave', '');" class="btn btn-info btn-lg"><?php esc_html_e('Save Changes', WEBLIZAR_ACL) ?></button>
				<button type="button" onclick="return Custom_login_login('loginbgReset', '');" class="btn btn-primary btn-lg"><?php esc_html_e('Reset Default', WEBLIZAR_ACL) ?></button>
		</div>
		</div>
	</div>
</div>
<!-- /row -->
<?php
if (isset($_POST['Action'])) {
	// var_dump($login_page); die;
	$Action = sanitize_text_field($_POST['Action']);
	if( ! wp_verify_nonce( $_POST['nonce_ajax'], 'weblizar_admin_nonce' ) ) {
		die('Not authorized');
	}
	else {
		//Save
		if ($Action == "loginbgSave") {
			$login_form_position            = sanitize_text_field($_POST['login_form_position']);
			$login_form_left                = sanitize_text_field( $_POST['login_form_left']);
			$login_form_top                 = sanitize_text_field( $_POST['login_form_top']);
			$login_form_float               = sanitize_text_field( $_POST['login_form_float']);
			$Login_bg_value                 = sanitize_text_field( $_POST['Login_bg_value']);
			$login_background_color         = sanitize_text_field( $_POST['login_background_color']);
			$login_bg_color_overlay         = sanitize_text_field( $_POST['login_bg_color_overlay']);
			$login_bg_image                 = sanitize_text_field( $_POST['login_bg_image']);
			$login_form_opacity             = sanitize_text_field( $_POST['login_form_opacity']);
			$login_form_width               = sanitize_text_field( $_POST['login_form_width']);
			$login_form_radius              = sanitize_text_field( $_POST['login_form_radius']);
			$login_border_style             = sanitize_text_field( $_POST['login_border_style']);
			$login_redirect_force           = sanitize_text_field( $_POST['login_redirect_force']);
			$login_border_thikness          = sanitize_text_field( $_POST['login_border_thikness']);
			$login_border_color             = sanitize_hex_color( $_POST['login_border_color']);
			$login_bg_repeat                = sanitize_text_field( $_POST['login_bg_repeat']);
			$login_bg_position              = sanitize_text_field( $_POST['login_bg_position']);
			$login_enable_shadow            = sanitize_text_field( $_POST['login_enable_shadow']);
			$login_enable_language_switcher = sanitize_text_field( $_POST['login_enable_language_switcher']);
			$login_shadow_color             = sanitize_hex_color( $_POST['login_shadow_color']);
			$login_custom_css               = wp_strip_all_tags( $_POST['login_custom_css']);
			$login_redirect_user            = sanitize_text_field( $_POST['login_redirect_user']);
			$login_force_redirect_url       = sanitize_text_field( $_POST['login_force_redirect_url']);
			$log_form_above_msg             = wp_kses_post( $_POST['log_form_above_msg']);
			$login_pass_reset_msg           = sanitize_text_field( $_POST['login_pass_reset_msg']);
			$tagline_msg                    = wp_kses_post( $_POST['tagline_msg']);
			$login_msg_fontsize             = sanitize_text_field( $_POST['login_msg_fontsize']);
			$login_msg_font_color           = sanitize_hex_color( $_POST['login_msg_font_color']);
			$login_tagline_text_color       = sanitize_hex_color( $_POST['login_tagline_text_color']);
			$login_tagline_link_color       = sanitize_hex_color( $_POST['login_tagline_link_color']);
			$user_cust_lbl                  = sanitize_text_field( $_POST['user_cust_lbl']);
			$pass_cust_lbl                  = sanitize_text_field( $_POST['pass_cust_lbl']);
			$label_username                 = sanitize_text_field( $_POST['label_username']);
			$label_password                 = sanitize_text_field( $_POST['label_password']);
			$label_loginButton              = sanitize_text_field( $_POST['label_loginButton']);

			// Save Values in Option Table
			$login_page = serialize(array(
				'login_form_position'            => $login_form_position,
				'login_form_left'                => $login_form_left,
				'login_form_top'                 => $login_form_top,
				'login_form_float'               => $login_form_float,
				'login_bg_type'                  => $Login_bg_value,
				'login_bg_color'                 => $login_background_color,
				'login_bg_effect'                => $login_bg_color_overlay,
				'login_bg_image'                 => $login_bg_image,
				'login_form_opacity'             => $login_form_opacity,
				'login_form_width'               => $login_form_width,
				'login_form_radius'              => $login_form_radius,
				'login_border_style'             => $login_border_style,
				'login_redirect_force'           => $login_redirect_force,
				'login_border_thikness'          => $login_border_thikness,
				'login_border_color'             => $login_border_color,
				'login_custom_css'               => $login_custom_css,
				'login_bg_repeat'                => $login_bg_repeat,
				'login_bg_position'              => $login_bg_position,
				'login_enable_shadow'            => $login_enable_shadow,
				'login_enable_language_switcher' => $login_enable_language_switcher,
				'login_shadow_color'             => $login_shadow_color,
				'login_redirect_user'            => $login_redirect_user,
				'login_force_redirect_url'       => $login_force_redirect_url,
				'log_form_above_msg'             => $log_form_above_msg,
				'login_pass_reset_msg'           => $login_pass_reset_msg,
				'tagline_msg'                    => $tagline_msg,
				'login_msg_fontsize'             => $login_msg_fontsize,
				'login_msg_font_color'           => $login_msg_font_color,
				'login_tagline_text_color'       => $login_tagline_text_color,
				'login_tagline_link_color'       => $login_tagline_link_color,
				'user_cust_lbl'                  => $user_cust_lbl,
				'pass_cust_lbl'                  => $pass_cust_lbl,
				'label_username'                 => $label_username,
				'label_password'                 => $label_password,
				'label_loginButton'              => $label_loginButton,
			));

			
			update_option('Admin_custome_login_login', $login_page);
		}

		if ($Action == "loginbgReset") {
			$login_page = serialize(array(
				'login_form_position'            => 'default',
				'login_form_float'               => 'center',
				'login_form_left'                => '100',
				'login_form_top'                 => '100',
				'login_bg_type'                  => 'static-background-image',
				'login_bg_color'                 => '#1e73be',
				'login_bg_effect'                => 'pattern-1',
				'login_bg_image'                 => WEBLIZAR_NALF_PLUGIN_URL . '/images/3d-background.jpg',
				'login_form_opacity'             => '10',
				'login_form_width'               => '358',
				'login_form_radius'              => '10',
				'login_border_style'             => 'solid',
				'login_redirect_force'           => 'no',
				'login_border_thikness'          => '4',
				'login_border_color'             => '#0069A0',
				'login_bg_repeat'                => 'repeat',
				'login_bg_position'              => 'left top',
				'login_enable_shadow'            => 'yes',
				'login_custom_css'               => '',
				'login_enable_language_switcher' => 'yes',
				'login_shadow_color'             => '#C8C8C8',
				'login_redirect_user'            => '',
				'login_force_redirect_url'       => get_home_url() . "/wp-login.php",
				'log_form_above_msg'             => '',
				'login_pass_reset_msg'           => '',
				'tagline_msg'                    => '',
				'login_msg_fontsize'             => '16',
				'login_msg_font_color'           => '#000000',
				'login_tagline_text_color'       => '#ffffff',
				'login_tagline_link_color'       => '#f00',
				'user_cust_lbl'                  => 'Type Username or Email',
				'pass_cust_lbl'                  => 'Type Password',
				'label_username'                 => 'Username / Email',
				'label_password'                 => 'Password',
				'label_loginButton'              => 'Log In',
			));
			update_option('Admin_custome_login_login', $login_page);
		}
	}	
}
?>
