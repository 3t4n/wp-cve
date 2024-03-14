<!-- Call the option setting -->
<?php $wl_rcsm_options = weblizar_rcsm_get_options(); ?>
<?php $comsoon_security_action_nonce = wp_create_nonce("comsoon_security_action"); ?>
<div class="col-xs-8 tab-content" id="spa_general">
	<!-- plugin dashboard Main class div setting -->
	<?php $site_layout = $wl_rcsm_options['layout_status']; ?>
	<div class="class_live <?php if ($site_layout == 'deactivate' || $site_layout == 'service_unavailable_switch' || $site_layout == 'redirect_switch') echo "deactive"; ?>">
		<a class="class_live btn btn-warning" href="<?php echo esc_url(home_url("/?rcsm_live_preview=yes")); ?>" target="_blank"><span class="fas fa-eye"><?php esc_html_e(' Live Preview', 'RCSM_TEXT_DOMAIN'); ?></span></a>
	</div>
	<div class=" tab-pane col-md-12 active" id="templates-option">
		<!-- plugin template selection setting -->
		<div class="col-md-12 option">
			<h1><?php esc_html_e('Templates Options', 'RCSM_TEXT_DOMAIN'); ?></h1>			
			<div class="tab-content">
				<div id="weblizar-template" class="tab-pane fade in active">
					<!-- plugin template free theme layout selection setting -->
					<form method="post" id="weblizar_rcsm_template_option">
						<div class="row">
							<?php for ($i = 1; $i <= 1; $i++) { ?>
								<div style="width:48%" class="col-md-3 col-sm-6 op_tem site_template <?php if ($wl_rcsm_options['select_template'] == 'select_template' . $i) echo 'active'; ?>" id="select_template<?php echo esc_attr($i); ?>">
									<div class="selected_template active">
										<div class="row op_show" data-orient="top">
											<div class="op_weblizar-pics-activated">
												<span class="image-shop-scroll" style="background-image: url('<?php echo plugin_dir_url(__FILE__) . 'images/screen-shot' . $i . '.jpg'; ?>"></span>
											</div>
										</div>
										<h4 class="op_name"><?php esc_html_e('Template ' . $i, 'RCSM_TEXT_DOMAIN'); ?> <a class="btn btn-primary btn_template" target="_new" href="<?php echo esc_url('http://demo.weblizar.com/coming-soon-page-pro/template-1/'); ?>" rel="nofollow"><?php esc_html_e('View Demo', 'RCSM_TEXT_DOMAIN') ?></a></h4>
										<span class="op_name1 green"><span class="activate"> <?php esc_html_e('Template Activated', 'RCSM_TEXT_DOMAIN'); ?></span></span>

									</div>
								</div>
							<?php } ?>
							<?php for ($i = 2; $i <= 9; $i++) { ?>
								<div style="width:48%" class="col-md-3 col-sm-6 op_tem site_template <?php if ($wl_rcsm_options['select_template'] == 'select_template' . $i) echo 'active'; ?>" id="select_template<?php echo esc_attr($i); ?>">
									<div class="selected_template active">
										<div class="row op_show" data-orient="top">
											<div class="op_weblizar-pics">
												<span class="image-shop-scroll" style="background-image: url('<?php echo plugin_dir_url(__FILE__) . 'images/screen-shot' . $i . '.jpg'; ?>"></span>
											</div>
										</div>
										<h4 class="op_name"><?php esc_html_e('Template ' . $i, 'RCSM_TEXT_DOMAIN'); ?>
											<a class="btn btn-primary btn_template" target="_new" href="<?php echo esc_url('http://demo.weblizar.com/coming-soon-page-pro/template-'.esc_attr($i) .'/'); ?>" rel="nofollow"><?php esc_html_e('View Demo', 'RCSM_TEXT_DOMAIN') ?></a></h4>
									</div>
								</div>
							<?php } ?>
						</div>
					</form>
				</div>
				
			</div>
		</div>
	</div>

	<div class="ml-3 tab-pane col-md-12 " id="general-settings">
		<!-- plugin General selection setting -->
		<div class="col-md-12 option">
			<h1><?php esc_html_e('General Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#appearance"><?php esc_html_e('Appearance Settings', 'RCSM_TEXT_DOMAIN'); ?></a></li>
				<li><a data-toggle="tab" href="#general-option"><?php esc_html_e('SEO Settings', 'RCSM_TEXT_DOMAIN'); ?></a></li>
				<li><a data-toggle="tab" href="#access-control"><?php esc_html_e('Access Control Settings', 'RCSM_TEXT_DOMAIN'); ?></a></li>
				<li><a data-toggle="tab" href="#layout"><?php esc_html_e('Layout Manager', 'RCSM_TEXT_DOMAIN'); ?></a></li>
			</ul>
			<div class="tab-content">
				<!-- General - Appearance Selection Setting -->
				<div id="appearance" class="tab-pane fade in active">
					<!-- Appearance selection setting -->
					<form method="post" id="weblizar_rcsm_appearance_option">
						<h1><?php esc_html_e('Appearance Selection Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
						<div class="row">
							<div class="col-md-3 form-group">
								<?php $site_layout = $wl_rcsm_options['layout_status']; ?>
								<label><?php esc_html_e('Status', 'RCSM_TEXT_DOMAIN'); ?></label>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enable Coming Soon and 503 service unavailable Mode on Site.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
								</br>
							</div>
							<div class="col-md-3 mt-2" id="weblizar_rcsm_layout_switch">
								<select id="layout_status" name="layout_status" class="form-control">
									<option value="deactivate" <?php echo selected($site_layout, 'deactivate'); ?>><?php esc_html_e('Deactivate', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="coming_soon_switch" <?php echo selected($site_layout, 'coming_soon_switch'); ?>><?php esc_html_e('Activate Coming Soon Mode', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="service_unavailable_switch" <?php echo selected($site_layout, 'service_unavailable_switch'); ?>><?php esc_html_e('503 - Service unavailable', 'RCSM_TEXT_DOMAIN'); ?></option>
								</select>
							</div>
						</div>

						<div class="layout-options <?php if ($site_layout == 'deactivate') echo "active"; ?>" id="deactivate">
							<div class="col-md-8 form-group">
								<label><?php esc_html_e('How Plugin Deactivate Mode Works?', 'RCSM_TEXT_DOMAIN'); ?></label><br />
								<ul class="instruction_points">
									<li><?php esc_html_e('Plugin deactivated, You can choose any action from following list.', 'RCSM_TEXT_DOMAIN'); ?></li>
								</ul>
								<hr style="border-color:#999;">
								<label><?php esc_html_e('How Coming Soon Mode Works?', 'RCSM_TEXT_DOMAIN'); ?></label><br />
								<ul class="instruction_points">
									<li><?php esc_html_e('Hide the all site from users and show the Only Coming Soon page template', 'RCSM_TEXT_DOMAIN'); ?></li>
									<li><?php esc_html_e('For Maintain the site SEO , You will use it only one time from the launched the site.', 'RCSM_TEXT_DOMAIN'); ?></li>
									<li><?php esc_html_e('In Our Pro Version, You can used the under construction and maintenance mode in future for your live site', 'RCSM_TEXT_DOMAIN'); ?></li>
								</ul>
								<hr style="border-color:#999;">
								<label><?php esc_html_e('How 503 - Service unavailable Mode Action', 'RCSM_TEXT_DOMAIN'); ?></label><br />
								<ul class="instruction_points">
									<li><?php esc_html_e('The Web server (running the Web site) is currently unable to handle the HTTP request due to a temporary overloading or maintenance of the server.', 'RCSM_TEXT_DOMAIN'); ?></li>
								</ul>
							</div>
						</div>

						<div class="layout-options <?php if ($site_layout == 'coming_soon_switch') echo "active"; ?>" id="coming_soon_switch">
							<div class="col-md-8 form-group">
								<label><?php esc_html_e('How Coming Soon Mode Works?', 'RCSM_TEXT_DOMAIN'); ?></label><br />
								<ul class="instruction_points">
									<li><?php esc_html_e('Hide the all site from users and show the Only Coming Soon page template', 'RCSM_TEXT_DOMAIN'); ?></li>
									<li><?php esc_html_e('For Maintain the site SEO , You will use it only one time from the launched the site.', 'RCSM_TEXT_DOMAIN'); ?></li>
									<li><?php esc_html_e('In Our Pro Version, You can used the under construction and maintenance mode in future for your live site', 'RCSM_TEXT_DOMAIN'); ?></li>
								</ul>
							</div>
							<div class="col-md-6 form-group">
								<h4><?php esc_html_e('Coming Soon Mode Settings', 'RCSM_TEXT_DOMAIN'); ?></h4><br />
								<div class="row">
									<div class="col-md-3">
										<label><?php esc_html_e('Title', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type Here Page Heading", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
									</div>
									<div class="col-md-6">
										<input class="form-control" type="text" name="coming-soon_title" id="coming-soon_title" value="<?php echo esc_attr($wl_rcsm_options['coming-soon_title']); ?>">
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<label><?php esc_html_e('Sub Title', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type Here Page Sub Heading", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
									</div>
									<div class="col-md-6">
										<input class="form-control" type="text" name="coming-soon_sub_title" id="coming-soon_sub_title" value="<?php echo esc_attr($wl_rcsm_options['coming-soon_sub_title']); ?>">
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<label><?php esc_html_e('Message', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type Here A Message", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
									</div>
									<div class="col-md-6">
										<textarea class="form-control" rows="8" cols="8" id="coming-soon_message" name="coming-soon_message"><?php if ($wl_rcsm_options['coming-soon_message'] != '') { echo esc_attr($wl_rcsm_options['coming-soon_message']); } ?></textarea>
									</div>
								</div>
							</div>
						</div>

						<!-- Start favicon -->
						<div class="col-md-12 form-group">
								<label><?php esc_html_e('Your Site Favicon', 'RCSM_TEXT_DOMAIN'); ?></label>
								<input class="form-control" type="text" value="<?php if ($wl_rcsm_options['upload_favicon'] != '') {
										echo esc_attr($wl_rcsm_options['upload_favicon']);
									} ?>" id="upload_favicon" name="upload_favicon" size="50" class="upload has-file">
								<button type="button" class="btn btn-primary upload_image_button"><?php esc_html_e('Upload Favicon Image', 'RCSM_TEXT_DOMAIN'); ?></button>
						</div>
						<!-- End favicon -->

						<div class="all_content_show layout-options <?php if ($site_layout == 'service_unavailable_switch') echo "active"; ?>" id="service_unavailable_switch">
							<div class="col-md-12 form-group">
								<label><?php esc_html_e('The Web server (running the Web site) is currently unable to handle the HTTP request due to a temporary overloading or maintenance of the server.', 'RCSM_TEXT_DOMAIN'); ?></label>
							</div>
						</div>

						<div class="all_content_hide <?php if ($site_layout == 'deactivate' || $site_layout == 'service_unavailable_switch') echo "active"; ?>" id="service_unavailable_switch">
							<!-- Add this div to hide all setting when deactivate mode on-->
							<div class="all_content_hide" id="deactivate">	
								<!-- Start site logo -->
								<div class="col-md-12 form-group">
									<div class="row">
										<div class="col-md-3">
											<label><?php esc_html_e('Your Site Logo', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Select logo as text and image ", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
										</div>
										<div class="col-md-3">
											<?php $site_logo = $wl_rcsm_options['site_logo']; ?>
											<select name="site_logo" id="site_logo" class="form-control">
												<option value="<?php echo esc_attr('logo_text'); ?>" <?php echo selected($site_logo, 'logo_text'); ?>><?php esc_html_e('Logo Text', 'RCSM_TEXT_DOMAIN'); ?></option>
												<option value="<?php echo esc_attr('logo_image'); ?>" <?php echo selected($site_logo, 'logo_image'); ?>><?php esc_html_e('Logo Image', 'RCSM_TEXT_DOMAIN'); ?></option>
											</select>
										</div>
									</div>
								</div>
								<!-- End site logo -->

								<!-- Start logo text -->
								<div class="col-md-12 form-group logo-option <?php if ($site_logo == 'logo_text') echo "active"; ?>" id="logo_text">
									<label><?php esc_html_e('Logo Text ', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add your site logo text here ", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
									</br>
									<div class="col-md-3">
										<input class="form-control" type="text" name="logo_text_value" id="logo_text_value" value="<?php echo esc_attr($wl_rcsm_options['logo_text_value']); ?>">
									</div>
								</div>
								<!-- End logo text  -->


								<!-- Start logo image  -->
								<div class="col-md-12 form-group logo-option <?php if ($site_logo == 'logo_image') echo "active"; ?>" id="logo_image">
									<div class="row">
										<div class="col-md-12 no-pad">
											<label><?php esc_html_e('Logo Image ', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add your site logo here suggested size is 250X150", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
											<div class="col-md-12">
												<input class="form-control" type="text" value="<?php if ($wl_rcsm_options['upload_image_logo'] != '') {
												echo esc_attr($wl_rcsm_options['upload_image_logo']);
												} ?>" id="upload_image_logo" name="upload_image_logo" size="50" class="upload has-file">
												<button type="button" class="btn btn-primary upload_image_button"><?php esc_html_e('Upload Your Logo', 'RCSM_TEXT_DOMAIN'); ?></button>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<label><?php esc_html_e('Logo Width ', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Default Logo Width : 250px, if you want to increase than specify your value", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
											<div class="col-md-12">
												<input class="form-control" type="text" name="logo_width" id="logo_width" value="<?php echo esc_attr($wl_rcsm_options['logo_width']); ?>">
											</div>
										</div>
										<div class="col-md-3">
											<label><?php esc_html_e('Logo Height ', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Default Logo Height : 150px, if you want to increase than specify your value", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
											<div class="col-md-12">
												<input class="form-control" type="text" name="logo_height" id="logo_height" value="<?php echo esc_attr($wl_rcsm_options['logo_height']); ?>">
											</div>
										</div>
									</div>
								</div>
								<!-- End logo image -->

								<!-- Start logo & form alignment -->
								<div class="col-md-12 form-group">
									<div class="row">
										<div class="col-md-3">
											<label><?php esc_html_e('Logo & Form Alignment', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Default Logo & Form Alignment is Center", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
										</div>
										<div class="col-md-3">
											<?php $site_logo_alignment = $wl_rcsm_options['site_logo_alignment']; ?>
											<select name="site_logo_alignment" id="site_logo_alignment" class="form-control">
												<option value="<?php echo esc_attr('left'); ?>" <?php echo selected($site_logo_alignment, 'left'); ?>><?php esc_html_e('Left', 'RCSM_TEXT_DOMAIN'); ?></option>
												<option value="<?php echo esc_attr('center'); ?>" <?php echo selected($site_logo_alignment, 'center'); ?>><?php esc_html_e('Center', 'RCSM_TEXT_DOMAIN'); ?></option>
												<option value="<?php echo esc_attr('right'); ?>" <?php echo selected($site_logo_alignment, 'right'); ?>><?php esc_html_e('Right', 'RCSM_TEXT_DOMAIN'); ?></option>
											</select>
										</div>
									</div>
								</div>
								<!-- End logo & form alignment -->

								<!-- Start select background type -->
								<div class="col-md-12 form-group">
									<div class="row">
										<div class="col-md-3">
											<label><?php esc_html_e('Select Background Type', 'RCSM_TEXT_DOMAIN'); ?> </label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("With the Help of selection box you can show page background as your wish", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label></br>
										</div>
										<div class="col-md-3">
											<?php $theme_bg = $wl_rcsm_options['template_bg_select']; ?>
											<select name="template_bg_select" id="template_bg_select" class="form-control">
												<option value="<?php echo esc_attr('Background_Color'); ?>" <?php echo selected($theme_bg, 'Background_Color'); ?>><?php esc_html_e('Background Color', 'RCSM_TEXT_DOMAIN'); ?></option>
												<option value="<?php echo esc_attr('Custom_Background'); ?>" <?php echo selected($theme_bg, 'Custom_Background'); ?>><?php esc_html_e('Background Image', 'RCSM_TEXT_DOMAIN'); ?></option>
											</select>
										</div>
									</div>
								</div>
								<!-- End select background type -->

								<!-- Start background color -->
								<div class="col-md-12 form-group template-option <?php if ($theme_bg == 'Background_Color') echo "active"; ?>" id="Background_Color">
									<label><?php esc_html_e('Background Color', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Change Background Color", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
									<div class="col-md-3 no-pad">
										<div class="row">
											<input class="color no-sliders" type="text" value="<?php echo esc_attr($wl_rcsm_options['bg_color']); ?>" id="bg_color" name="bg_color" class="colorpicker" />
										</div>
									</div>
								</div>
								<!-- End background color -->

								<!-- Start background image -->
								<div class="col-md-12 form-group template-option <?php if ($theme_bg == 'Custom_Background') echo "active"; ?>" id="Custom_Background">
									<label><?php esc_html_e('Background Image', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add Background Image", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
									<div class="col-md-12">
										<div class="row">
											<input class="form-control" type="text" value="<?php if ($wl_rcsm_options['custom_bg_img'] != '') {
													echo esc_attr($wl_rcsm_options['custom_bg_img']);
													} ?>" id="custom_bg_img" name="custom_bg_img" size="50" class="upload has-file" />
											<button type="button" class="btn btn-primary upload_image_button"><?php esc_html_e('Upload Image', 'RCSM_TEXT_DOMAIN'); ?></button>
										</div>
									</div>
								</div>
								<!-- End background image -->

								<!-- set the title color and description text color -->
								<div class="col-md-12 form-group">
									<div class="row">
										<div class="col-md-3">
											<label>
												<?php esc_html_e('Title Color', 'RCSM_TEXT_DOMAIN'); ?>
											</label>
											<input class="color no-sliders" type="text" value="<?php echo esc_attr($wl_rcsm_options['custom_bg_title_color']); ?>" id="bg_title_color" name="bg_title_color" class="colorpicker" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<label>
												<?php esc_html_e('Description Color', 'RCSM_TEXT_DOMAIN'); ?>
											</label>
											<input class="color no-sliders" type="text" value="<?php echo esc_attr($wl_rcsm_options['custom_bg_desc_color']); ?>" id="custom_bg_desc_color" name="custom_bg_desc_color" class="colorpicker" />
										</div>
									</div>
								</div>
								<!-- End of title and description text color setting -->

								<!-- Start select background type for subscriber form -->
								<div class="col-md-12 form-group">
									<div class="row">
										<div class="col-md-3">
											<label><?php esc_html_e('Select Background Type for Subscriber Form', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Select background type for subscription form as color and image ", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
										</div>
										<div class="col-md-3">
											<?php $select_bg_subs = $wl_rcsm_options['select_bg_subs']; ?>
											<select name="select_bg_subs" id="select_bg_subs" class="form-control">
												<option value="<?php echo esc_attr('sub_bg_clr'); ?>" <?php echo selected($select_bg_subs, 'sub_bg_clr'); ?>><?php esc_html_e('Background Color For Subscription Form', 'RCSM_TEXT_DOMAIN'); ?></option>
												<option value="<?php echo esc_attr('sub_bg_img'); ?>" <?php echo selected($select_bg_subs, 'sub_bg_img'); ?>><?php esc_html_e('Background Image For Subscription Form', 'RCSM_TEXT_DOMAIN'); ?></option>		
											</select>
										</div>
									</div>
								</div>
								<!-- End select background type for subscriber form -->								

								<!-- Start background color for subscriber form -->
								<div class="col-md-12 form-group select-option <?php if ($select_bg_subs == 'sub_bg_clr') echo "active"; ?>" id="sub_bg_clr">
									<label><?php esc_html_e('Background Color For Subscription Form', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Change Background Color for Subscription Form", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
									<div class="col-md-3 no-pad">
										<div class="row">
											<input class="color no-sliders" type="text" value="<?php echo esc_attr($wl_rcsm_options['sub_bg_color']); ?>" id="sub_bg_color" name="sub_bg_color" class="colorpicker" />
										</div>
									</div>
								</div>
								<!-- End background color for subscriber form -->

								<!-- Start background image for subscriber form -->
								<div class="col-md-12 form-group select-option <?php if ($select_bg_subs == 'sub_bg_img') echo "active"; ?>" id="sub_bg_img">
									<label><?php esc_html_e('Background Image For Subscriber Form', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add Background Image for Subscription Form", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>	
									<div class="col-md-12">
										<div class="row">
											<input class="form-control" type="text" value="<?php if ($wl_rcsm_options['custom_sub_bg_img'] != '') {
																		echo esc_attr($wl_rcsm_options['custom_sub_bg_img']);
																		} ?>" id="custom_sub_bg_img" name="custom_sub_bg_img" class="upload has-file" />
											<button type="button" class="btn btn-primary upload_image_button"><?php esc_html_e('Upload Image', 'RCSM_TEXT_DOMAIN'); ?></button>
										</div>
									</div>
								</div>
								<!-- End background image for subscriber form -->

								<!-- Start button link -->
								<div class="col-md-12 form-group">
									<div class="row">
										<div class="col-md-3">
											<label><?php esc_html_e('Button Link On/Off', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enable Button Link of top Image, Video, Slider.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
										</div>
										<div class="col-md-3">
											<input data-toggle="toggle" data-offstyle="off" type="checkbox" <?php if ($wl_rcsm_options['button_onoff'] == 'on') echo "checked='checked'"; ?> id="button_onoff" name="button_onoff">
										</div>
									</div>
								</div>
								<div class="col-md-12 form-group button_show-option <?php if ($wl_rcsm_options['button_onoff'] == 'on') echo "active"; ?>" id="rcsm_button_onoff">
									<div class="row">
										<div class="col-md-3">
											<label><?php esc_html_e('Button Text', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add text of button ", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
											<div class="col-md-12">
												<input class="form-control" type="text" name="button_text" id="button_text" value="<?php echo esc_attr($wl_rcsm_options['button_text']); ?>">
											</div>
										</div>
										<div class="col-md-3">
											<label><?php esc_html_e('Button Link', 'RCSM_TEXT_DOMAIN'); ?></label>
											<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add link on button", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
											<div class="col-md-12">
												<input class="form-control" type="text" name="button_text_link" id="button_text_link" value="<?php esc_attr_e($wl_rcsm_options['button_text_link'], 'RCSM_TEXT_DOMAIN'); ?>">
											</div>
										</div>
									</div>
								</div>
								<!--End button link -->

								<!--Start link to admin -->
								<div class="col-md-12 form-group">
									<div class="row">
									<div class="col-md-3 info">
										<label><?php esc_html_e('Link to Admin', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enable Admin Link of Dashboard for Users.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
									</div>
									<div class="col-md-3">
										<input data-toggle="toggle" data-offstyle="off" type="checkbox" <?php if ($wl_rcsm_options['link_admin'] == 'on') echo "checked='checked'"; ?> id="link_admin" name="link_admin">
									</div>
									</div>
								</div>
								<div class="col-md-12 form-group link_admin-option <?php if ($wl_rcsm_options['link_admin'] == 'on') echo "active"; ?>" id="rcsm_link_admin">
									<div class="row">
									<div class="col-md-3">
										<label><?php esc_html_e('Link To Admin Text ', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="#" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add text name for Admin Link of Dashboard for Users.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
									</div>
										<div class="col-md-3">
										<input class="form-control" type="text" name="admin_link_text" id="admin_link_text" value="<?php echo esc_attr($wl_rcsm_options['admin_link_text']); ?>">
									</div>
									</div>
								</div>
								<!--End link to admin -->
							</div>
							<!-- End of the all content hide class div for deactivate mode-->
						</div>
						<!-- End of the all content hide class div for 503 service unavailable mode on-->
							<div class="restore">
								<input type="hidden" id="security" name="security" value="<?php echo esc_attr($comsoon_security_action_nonce); ?>" />
								<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_appearance_option" name="weblizar_rcsm_settings_save_appearance_option" />
								<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('appearance_option');">
								<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" name="weblizar_rcsm_option_data_save_appearance_option" id="weblizar_rcsm_option_data_save_appearance_option">
							</div>
					</form>
				</div>

				<!-- General - SEO Setting --> 
				<div id="general-option" class="tab-pane fade in">
					<form method="post" id="weblizar_rcsm_general_option">
						<h1><?php esc_html_e('SEO Selection Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
						<div class="col-md-12 form-group">
							<div class="row">
								<label><?php esc_html_e('Page Meta Title', 'RCSM_TEXT_DOMAIN'); ?></label>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type here meta title for seo", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								<br />
								<div class="col-md-3">
									<input class="form-control" type="text" name="page_meta_title" id="page_meta_title" value="<?php echo esc_attr($wl_rcsm_options['page_meta_title']); ?>">
								</div>
							</div>
							<div class="row">
								<label><?php esc_html_e('Page Meta Keywords', 'RCSM_TEXT_DOMAIN'); ?></label>
								.<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add relative keywords for page and site seo", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								<br />
								<div class="col-md-3">
									<textarea class="form-control" rows="8" cols="8" id="page_meta_keywords" placeholder="<?php echo esc_html('Enter comma separated keywords here which is related to your website', 'RCSM_TEXT_DOMAIN'); ?>" name="page_meta_keywords"><?php if ($wl_rcsm_options['page_meta_keywords'] != '') {
																																																								echo esc_attr($wl_rcsm_options['page_meta_keywords']);
																																																							} ?></textarea>
								</div>
							</div>
							<div class="row">
								<label><?php esc_html_e('Page Meta Description', 'RCSM_TEXT_DOMAIN'); ?></label>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type here the page related short and relevent description", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								<br />
								<div class="col-md-3">
									<textarea class="form-control" rows="8" cols="8" id="page_meta_discription" name="page_meta_discription"><?php if ($wl_rcsm_options['page_meta_discription'] != '') {
																																					echo esc_attr($wl_rcsm_options['page_meta_discription']);
																																				} ?></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<label><?php esc_html_e('Enable Search Robots', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enable the Robot for search", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
							</br>
							<div class="col-md-3">
								<input data-toggle="toggle" data-offstyle="off" type="checkbox" <?php if ($wl_rcsm_options['search_robots'] == 'on') echo "checked='checked'"; ?> id="search_robots" name="search_robots">
							</div>
						</div>
						<div class="col-md-12 form-group search-option <?php if ($wl_rcsm_options['search_robots'] == 'on') echo "active"; ?>" id="rcsm_search_robots">
							<label><?php esc_html_e('Select Robots Meta Tag', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Search engines like Google, Yahoo and AltaVista show Metatags in their search results. Robot tag tell the spider ( Search engines ) if you want your whole website or single page to be crawled or not.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
							</br>
							<div class="col-md-3">
								<?php $rcsm_robots = $wl_rcsm_options['rcsm_robots_meta']; ?>
								<select class="form-control rcsm_select" name="rcsm_robots_meta" id="rcsm_robots_meta">
									<option value="<?php echo esc_attr('index follow'); ?>" <?php echo selected($rcsm_robots, 'index follow'); ?>><?php esc_html_e('Index, Follow', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="<?php echo esc_attr('index nofollow'); ?>" <?php echo selected($rcsm_robots, 'index nofollow'); ?>><?php esc_html_e('Index, NoFollow', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="<?php echo esc_attr('noindex follow'); ?>" <?php echo selected($rcsm_robots, 'noindex follow'); ?>><?php esc_html_e('NoIndex, Follow', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="<?php echo esc_attr('noindex nofollow'); ?>" <?php echo selected($rcsm_robots, 'noindex nofollow'); ?>><?php esc_html_e('NoIndex, NoFollow', 'RCSM_TEXT_DOMAIN'); ?></option>
								</select>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<label><?php esc_html_e('Your Site Favicon', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Make sure you upload .ico image type which is not more then 25X25 px.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
							<br />
							<div class="col-md-3 no-pad">
								<div class="row">
									<div class="col-md-12">
										<input class="form-control" type="text" value="<?php if ($wl_rcsm_options['upload_favicon'] != '') {
																							echo esc_attr($wl_rcsm_options['upload_favicon']);
																						} ?>" id="upload_favicon" name="upload_favicon" size="36" class="upload has-file" />
										<input type="button" id="upload_button" value="<?php esc_attr_e('Upload Favicon Icon', 'RCSM_TEXT_DOMAIN'); ?>" class="btn upload_image_button" />
									</div>
								</div>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_general_option" name="weblizar_rcsm_settings_save_general_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('general_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('general_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>')">
						</div>
					</form>
				</div>

				<!-- Access control setting -->
				<div id="access-control" class="tab-pane fade in">
					<form action="post" id="weblizar_rcsm_access_control_option">
						<h1> <?php esc_html_e('Access Control Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
						<div class="col-md-12 form-group show_page-option active" id="as_role">
							<label><?php esc_html_e('Hide The Site For The Following Role Users', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Select Users for showing coming soon page and hide the site whenever users login or not", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
							<br />
							<div class="col-md-4 no-pad">
								<select multiple="multiple" name="user_value[]" class="form-control">
									<?php global $wp_roles;
									$wp_roles = new WP_Roles();
									$all_roles = $wp_roles->get_names();
									if (isset($all_roles)) {
										foreach ($all_roles as $roles) { ?>
											<option value="<?php echo esc_attr($roles); ?>" <?php if (isset($wl_rcsm_options['user_value'])) {
																								foreach ($wl_rcsm_options['user_value'] as $roles_users) {
																									if ($roles_users == $roles) echo 'selected="selected"';
																								}
																							} ?>>
												<?php esc_html_e($roles, 'RCSM_TEXT_DOMAIN'); ?>
											</option>
									<?php
										}
									} ?>
								</select>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_access_control_option" name="weblizar_rcsm_settings_save_access_control_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('access_control_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('access_control_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
						</div>
					</form>
				</div>

				<!-- Layout swap control setting -->
				<div id="layout" class="tab-pane fade in">
					<div id="page-layout" class="tab-pane fade in active">
						<form action="post" id="weblizar_rcsm_pagelayoutmanger">
							<h1> <?php esc_html_e('Layout Manager', 'RCSM_TEXT_DOMAIN'); ?></h1>
							<div class="col-md-12 option section">
								<table class="form-table">
									<div class="dhe-example-section-content">
										<div id="page_layout_swap">
											<div class="column left first">
												<font color="#333333" size="+2"> <?php esc_html_e('Disabled', 'RCSM_TEXT_DOMAIN'); ?></font>
												<p></p>
												<div class="sortable-list" id="disable">
													<?php
													$data = $wl_rcsm_options['page_layout_swap'];
													$home_page_data = array('Count Down Timer', 'Subscriber Form');
													$todisable = array_diff($home_page_data, $data);
													if ($todisable != '') {
														foreach ($todisable as $value) { ?>
															<div class="sortable-item" id="<?php echo esc_attr($value); ?>">
																<?php echo ucfirst($value); ?>
															</div>
													<?php
														}
													} ?>
												</div>
											</div>
											<div class="column left">
												<font color="#333333" size="+2"> <?php esc_html_e('Enabled', 'RCSM_TEXT_DOMAIN'); ?></font>
												<p></p>
												<div class="sortable-list" id="enable">
													<?php
													$enable = $wl_rcsm_options['page_layout_swap'];
													if ($enable[0] != '') {
														foreach ($enable as $value) { ?>
															<div class="sortable-item" id="<?php echo esc_attr($value) ?>">
																<?php echo ucfirst($value); ?>
															</div><?php
																}
															} ?>
												</div>
											</div>
										</div>
									</div>
									<!--end redify_frontpage -->
								</table>
							</div>
							<div class="restore">
								<input type="hidden" id="security" name="security" value="<?php echo esc_attr($comsoon_security_action_nonce); ?>" />
								<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_pagelayoutmanger" name="weblizar_rcsm_settings_save_pagelayoutmanger" />
								<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" id="weblizar_home_layout_reset_pagelayoutmanger">
								<input class="button button-primary left" type="button" id="weblizar_home_layout_save_pagelayoutmanger" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Layout setting -->
	<div class="ml-3 tab-pane col-md-12" id="layout-settings">
		<div class="col-md-12 option">
			<h1><?php esc_html_e('Layout Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
			<!-- <ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#layout"><?php //_e('Layout Settings','RCSM_TEXT_DOMAIN');
																		?></a></li>
			</ul> -->
			<div class="tab-content">
				<div id="layout" class="tab-pane fade in active">
					<!-- Layout selection setting -->
					<form method="post" id="weblizar_rcsm_layout_option">						
						<div class="col-md-12 form-group">
							<?php $theme_color_schemes = $wl_rcsm_options['theme_color_schemes']; ?>
							<label><?php esc_html_e('Theme Color Schemes', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Select color Schemes for page theme layout.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
							<div class="col-md-3" id="weblizar_rcsm_layout_color_switch">
								<select id="theme_color_schemes" name="theme_color_schemes" class="form-control">
									<option value="<?php echo esc_attr('#FF6347'); ?>" <?php echo selected($theme_color_schemes, '#FF6347'); ?>><?php esc_html_e('Default', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="<?php echo esc_attr('#33ADFF'); ?>" <?php echo selected($theme_color_schemes, '#33ADFF'); ?>><?php esc_html_e('Blue', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="<?php echo esc_attr('#29AB87'); ?>" <?php echo selected($theme_color_schemes, '#29AB87'); ?>><?php esc_html_e('Green', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="<?php echo esc_attr('#FF6347'); ?>" <?php echo selected($theme_color_schemes, '#FF6347'); ?>><?php esc_html_e('Red', 'RCSM_TEXT_DOMAIN'); ?></option>
									<option value="<?php echo esc_attr('#FF69B4'); ?>" <?php echo selected($theme_color_schemes, '#FF69B4'); ?>><?php esc_html_e('Pink', 'RCSM_TEXT_DOMAIN'); ?></option>
								</select>
							</div> <br>
						</div>

						<div class="col-md-12 form-group">
							<label><?php esc_html_e('Theme Fonts', 'RCSM_TEXT_DOMAIN'); ?></label><br>
							<?php $theme_font = $wl_rcsm_options['theme_font_family']; ?>
							<?php $google_font = array('Merienda', 'Neucha', 'Bad Script', 'Sans Serif', 'Indie Flower', 'Shadows Into Light', 'Dancing Script', 'Kaushan Script', 'Tangerine', 'Pinyon Script', 'Great Vibes', 'Bad Script', 'Calligraffitti', 'Homemade Apple', 'Allura', 'Megrim', 'Nothing You Could Do', 'Fredericka the Great', 'Rochester', 'Arizonia', 'Astloch', 'Bilbo', 'Cedarville Cursive', 'Clicker Script', 'Dawning of a New Day', 'Ewert', 'Felipa', 'Give You Glory', 'Italianno', 'Jim Nightshade', 'Kristi', 'La Belle Aurore', 'Meddon', 'Montez', 'Mr Bedfort', 'Over the Rainbow', 'Princess Sofia', 'Reenie+Beanie', 'Ruthie', 'Sacramento', '
							 Seaweed Script', 'Stalemate', 'Trade Winds', 'UnifrakturMaguntia', 'Waiting for the Sunrise', 'Yesteryear', 'Zeyada', 'Warnes', 'Abril Fatface', 'Advent Pro', 'Aldrich', 'Alex Brush', 'Amatic SC', 'Antic Slab', 'Candal'); ?>
							<div class="col-md-3">
								<select name="theme_font_family" id="theme_font_family" class="form-control">
									<?php foreach ($google_font as $font) { ?>
										<option value="<?php echo esc_attr($font); ?>" <?php echo selected($theme_font, $font); ?>><?php echo esc_html($font); ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_layout_option" name="weblizar_rcsm_settings_save_layout_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('layout_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('layout_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Social Media setting -->
	<div class="ml-3 tab-pane col-md-12" id="social">
		<div class="col-md-12 option">
			<h1><?php esc_html_e('Social Media Options', 'RCSM_TEXT_DOMAIN'); ?><sub class="smo_sub"><?php esc_html_e('Social Link And Icon Settings', 'RCSM_TEXT_DOMAIN'); ?></sub></h1>
			<!-- <ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#page-social"><?php //_e('Social Media Options','RCSM_TEXT_DOMAIN');
																			?></a></li>
			</ul> -->
			<div class="tab-content">
				<div id="page-social" class="tab-pane fade in active">
					<!-- Social link and icon setting -->
					<form action="post" id="weblizar_rcsm_social_option">
						<!-- <h1> <?php //esc_html_e('Social Link And Icon Settings', 'RCSM_TEXT_DOMAIN'); ?></h1> -->
						<div class="social-option active" id="rcsm_social">
							<div id="rcsm_social_fields">
								<?php for ($i = 1; $i <= 5; $i++) { ?>
									<div class="col-md-12 form-group" id="rcsm_social-<?php echo esc_attr($i); ?>">
										<div class="row">
											<label><?php esc_html_e('Social Icon ', 'RCSM_TEXT_DOMAIN');
													echo esc_html($i); ?>
											</label></br>
											
											<div class="col-md-3">
												<input class="form-control" type="text" name="social_icon_<?php echo esc_attr($i); ?>" id="social_icon_<?php echo esc_attr($i); ?>" value="<?php echo esc_attr($wl_rcsm_options['social_icon_' . $i]); ?>">
											</div>

											<div class="col-md-3">
												<input class="form-control" type="text" name="social_link_<?php echo esc_attr($i); ?>" id="social_link_<?php echo esc_attr($i); ?>" value="<?php echo esc_attr($wl_rcsm_options['social_link_' . $i]); ?>">
												
												<label><?php esc_html_e('Open As New Tab ', 'RCSM_TEXT_DOMAIN'); ?></label>
	
												<input class="form-control" type="checkbox" <?php if ($wl_rcsm_options['link_tab_' . $i] == 'on'){ echo "checked='checked'"; }?> id="link_tab_<?php echo esc_attr($i); ?>" name="link_tab_<?php echo esc_attr($i); ?>" />
											</div>
											
										</div>
									</div>
								<?php } ?>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_social_option" name="weblizar_rcsm_settings_save_social_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('social_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>'">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('social_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Subscriber Form option setting -->
	<div class="ml-3 tab-pane col-md-12" id="subscriber">
		<div class="col-md-12 option">
			<h1><?php esc_html_e('Subscriber', 'RCSM_TEXT_DOMAIN'); ?></h1>
			<ul class="nav nav-tabs">
				<li class=""><a data-toggle="tab" href="#subscriber-settings"><?php esc_html_e('Subscriber Form Settings', 'RCSM_TEXT_DOMAIN'); ?></a></li>
				<li><a data-toggle="tab" href="#subscriber-provider-option"><?php esc_html_e('Subscriber Email Settings', 'RCSM_TEXT_DOMAIN'); ?></a></li>
				<li><a data-toggle="tab" href="#subscriber-list"><?php esc_html_e('Subscriber List', 'RCSM_TEXT_DOMAIN'); ?></a></li>
			</ul>
			<div class="tab-content">
				<!-- Subscriber Form general settings -->
				<div id="subscriber-settings" class="tab-pane fade in active">
					<form action="post" id="weblizar_rcsm_subscriber_option">
						<h1><?php esc_html_e('Subscriber Form Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
						<div class="col-md-12 form-group subscriber-option active" id="rcsm_subscriber">
							<h4><?php esc_html_e('Subscriber Form Text', 'RCSM_TEXT_DOMAIN'); ?></h4><br />
							<div class="row">
								<div class="col-md-3">
									<label for="subscriber_form_title"><?php esc_html_e('Title', 'RCSM_TEXT_DOMAIN'); ?></label>

									<label>
										<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type here subscriber form Heading", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i>
										</a>
									</label>
									</div>
								<br />
								<div class="col-md-6">
									<input class="form-control" type="text" name="subscriber_form_title" id="subscriber_form_title" value="<?php echo esc_attr($wl_rcsm_options['subscriber_form_title']); ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label for="subscriber_form_icon"><?php esc_html_e('Font Awesome Icons', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label>
										<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add relevant FontAwesome Icon Here", "RCSM_TEXT_DOMAIN"); ?>">
											<i class="fas fa-info-circle tt-icon"></i>
										</a>
									</label>
								</div>
								<div class="col-md-6">
									<input class="form-control" type="text" name="subscriber_form_icon" id="subscriber_form_icon" value="<?php echo esc_attr($wl_rcsm_options['subscriber_form_icon']); ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label><?php esc_html_e('Sub Title', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label>
										<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type here subscriber form Sub Heading", "RCSM_TEXT_DOMAIN"); ?>">
											<i class="fas fa-info-circle tt-icon"></i>
										</a>
									</label>
									<br />
								</div>

								<div class="col-md-6">
									<input class="form-control" type="text" name="subscriber_form_sub_title" id="subscriber_form_sub_title" value="<?php echo esc_attr($wl_rcsm_options['subscriber_form_sub_title']); ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label><?php esc_html_e('Message', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label>
										<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type here subscriber form Message", "RCSM_TEXT_DOMAIN"); ?>">
											<i class="fas fa-info-circle tt-icon"></i>
										</a>
									</label>
									<br />
								</div>
								<div class="col-md-6">
									<textarea class="form-control" rows="8" cols="8" id="subscriber_form_message" name="subscriber_form_message"><?php if ($wl_rcsm_options['subscriber_form_message'] != '') {
																																						echo esc_attr($wl_rcsm_options['subscriber_form_message']);
																																					} ?></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-12 form-group subscriber-option active" id="rcsm_subscriber">
							<label><?php esc_html_e('Subscriber Form Button Text & Message Settings', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Type here subscriber form Setting", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label><br />
							<div class="col-md-12 checkbox">
								<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e('Button Text', 'RCSM_TEXT_DOMAIN'); ?></label>
								</div>
								<div class="col-md-2">
									<input type="text" class="form-control color-control" name="sub_form_button_text" id="sub_form_button_text" value="<?php echo esc_attr($wl_rcsm_options['sub_form_button_text']); ?>" size="20">
								</div>
								</div>
							</div>
							<div class="col-md-12 checkbox">
								<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e('First Name Placeholder ', 'RCSM_TEXT_DOMAIN'); ?></label>
								</div>
								<div class="col-md-2">
									<input type="text" class="form-control color-control" name="sub_form_button_f_name" id="sub_form_button_f_name" value="<?php echo esc_attr($wl_rcsm_options['sub_form_button_f_name']); ?>" size="20">
								</div>
								</div>
							</div>
							<div class="col-md-12 checkbox">
								<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e('Last Name Placeholder ', 'RCSM_TEXT_DOMAIN'); ?></label>
								</div>
								<div class="col-md-2">
									<input type="text" class="form-control color-control" name="sub_form_button_l_name" id="sub_form_button_l_name" value="<?php echo esc_attr($wl_rcsm_options['sub_form_button_l_name']); ?>" size="20">
								</div>
								</div>
							</div>
							<div class="col-md-12 checkbox">
								<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e('Email Placeholder ', 'RCSM_TEXT_DOMAIN'); ?></label>
								</div>
								<div class="col-md-2">
									<input type="text" class="form-control color-control" name="sub_form_subscribe_title" id="sub_form_subscribe_title" value="<?php echo esc_attr($wl_rcsm_options['sub_form_subscribe_title']); ?>" size="20">
								</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<label><?php esc_html_e('Subscribe Success Message', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add a text message for Subscribed Success Message", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" name="sub_form_subscribe_seuccess_message" id="sub_form_subscribe_seuccess_message" value="<?php echo esc_attr($wl_rcsm_options['sub_form_subscribe_seuccess_message']); ?>">
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e('Subscribe Invalid Message', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add a text for Invalid Email Id Message", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								</div>
								<div class="col-md-3">
									<input type="text" class="form-control" name="sub_form_subscribe_invalid_message" id="sub_form_subscribe_invalid_message" value="<?php echo esc_attr($wl_rcsm_options['sub_form_subscribe_invalid_message']); ?>">
								</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<label><?php esc_html_e('Subscribe After Confirmation Success Message', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add a text for a confirmation message.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
									</div>

									<div class="col-md-3"><input type="text" class="form-control" name="sub_form_subscribe_confirm_success_message" id="sub_form_subscribe_confirm_success_message" value="<?php echo esc_attr($wl_rcsm_options['sub_form_subscribe_confirm_success_message']); ?>">
								</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e('Already Subscribed Information Message', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add a text for a already subscribed message.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></div>
									<div class="col-md-3">
									<input type="text" class="form-control" name="sub_form_subscribe_already_confirm_message" id="sub_form_subscribe_already_confirm_message" value="<?php echo esc_attr($wl_rcsm_options['sub_form_subscribe_already_confirm_message']); ?>">
								</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
								<div class="col-md-6">
									<label><?php esc_html_e('Invaid Details send Error Message', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add a text for a error message about showing of the Invalid details sent by subscribed users", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></div>
									<div class="col-md-3">
									<input type="text" class="form-control" name="sub_form_invalid_confirmation_message" id="sub_form_invalid_confirmation_message" value="<?php echo esc_attr($wl_rcsm_options['sub_form_invalid_confirmation_message']); ?>">
								</div>
								</div>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_subscriber_option" name="weblizar_rcsm_settings_save_subscriber_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('subscriber_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('subscriber_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>')">
						</div>
					</form>
				</div>

				<!-- Subscriber Form provider option setting -->
				<div id="subscriber-provider-option" class="tab-pane fade in ">
					<form action="post" id="weblizar_rcsm_subscriber_provider_option">
						<h1> <?php esc_html_e('Subscriber Email Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
						<div class="col-md-12 form-group">
							<div class="col-md-3 checkbox">
								<label><?php esc_html_e('Enable Email Based Subscriptions', 'RCSM_TEXT_DOMAIN'); ?></label><br />
								<div class="info">
									<input data-toggle="toggle" data-offstyle="off" type="checkbox" <?php if ($wl_rcsm_options['confirm_email_subscribe'] == 'on') echo "checked='unchecked'"; ?> id="confirm_email_subscribe" name="confirm_email_subscribe">
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enable the email confirmation system for valid subscribers.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
								</div>
							</div>
						</div>
						<div class="form_deactivate-option <?php if ($wl_rcsm_options['confirm_email_subscribe'] == 'off') echo "active"; ?>" id="deactivated_confirm_email_subscribe">
							<div class="col-md-12 form-group">
								<ul class="instruction_points">
									<li><?php esc_html_e('If Email Subscription is Enable: You have options "Wp Mail" to mail the subscribers and confirm its subscription through email.', 'RCSM_TEXT_DOMAIN'); ?></li>
									<li style="list-style: none;">&nbsp;</li>
									<li><?php esc_html_e('If email subscription option is disable: Email confirmation process not required. Users/Visitors will be added at subscriber list as active subscriber.', 'RCSM_TEXT_DOMAIN'); ?></li>
								</ul>
							</div>
						</div>
						<div class="form_select-option <?php if ($wl_rcsm_options['confirm_email_subscribe'] == 'on') echo "active"; ?>" id="confirm_email_subscribe">
							<div class="col-md-12 form-group">
								<label><?php esc_html_e('Select Email Carrier Type', 'RCSM_TEXT_DOMAIN'); ?> </label>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Select a email carrier type to send subscriber mails", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label><br />
								<div class="col-md-3">
									<?php $subscribe_select = $wl_rcsm_options['subscribe_select']; ?>
									<select name="subscribe_select" id="subscribe_select" class="form-control">
										<option value="<?php echo esc_attr('wp_mail'); ?>" <?php echo selected($subscribe_select, 'wp_mail'); ?>><?php esc_html_e('WP Mail', 'RCSM_TEXT_DOMAIN'); ?></option>
									</select>
									<ul class="instruction_points theme_msg_heading">
										<li><?php esc_html_e('WordPress Guideline: PHP Mailer Library Removed due to not supported by WordPress.org 4.7.2.', 'RCSM_TEXT_DOMAIN'); ?></li>

									</ul>
								</div>
							</div>
							<div class="col-md-12 form-group subscribe-option <?php if ($subscribe_select == 'wp_mail') echo "active"; ?>" id="wp_mail">
								<label><?php esc_html_e('WP Mail Subscriber', 'RCSM_TEXT_DOMAIN'); ?></label><br /><br />
								<div class="col-md-12">
									<div class="col-md-3">
										<label><?php esc_html_e('Mail ID', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add Sender Email Id. By default User mail id has added", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
										<input type="text" class="form-control" name="wp_mail_email_id" id="wp_mail_email_id" value="<?php echo esc_attr($wl_rcsm_options['wp_mail_email_id']); ?>" />
									</div>
								</div>
							</div>

						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_subscriber_provider_option" name="weblizar_rcsm_settings_save_subscriber_provider_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('subscriber_provider_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('subscriber_provider_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>')">
						</div>
					</form>
				</div>

				<!-- Get the Subscriber Form database output setting -->
				<div id="subscriber-list" class="tab-pane fade in">
					<form action="post" id="weblizar_rcsm_subscribe_form">
						<h1> <?php esc_html_e('Subscribers Options and List', 'RCSM_TEXT_DOMAIN'); ?></h1>
						<div class="col-md-12 form-group">
							<div class="col-md-3">
								<label><?php esc_html_e('Auto Email sent to Subscribed Active Users after site Launched', 'RCSM_TEXT_DOMAIN'); ?></label><br />
								<div class="info">
									<input data-toggle="toggle" data-offstyle="off" type="checkbox" <?php if ($wl_rcsm_options['auto_sentto_activeusers'] == 'on') echo "checked='unchecked'"; ?> id="auto_sentto_activeusers" name="auto_sentto_activeusers">
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enable auto email option to subscribed active users after site lunched.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
								</div>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<div class="col-md-3">
								<label><?php esc_html_e('Manual E-Mail To Subscribers', 'RCSM_TEXT_DOMAIN'); ?></label>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("This section used for mail to subscriber user", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								<?php $sub_mail_option = $wl_rcsm_options['subscriber_users_mail_option']; ?>
								</br>
								<span><?php esc_html_e('Select options', 'RCSM_TEXT_DOMAIN'); ?></span>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Below selection option have some types of user list (Active users , Pending users, Already mailed users)", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
								</br>
								<div class="col-md-12 no-pad " id="weblizar_rcsm_layout_switch">
									<select id="subscriber_users_mail_option" name="subscriber_users_mail_option" class="form-control">
										<option value="<?php echo esc_attr('all_users'); ?>" <?php echo selected($sub_mail_option, 'all_users'); ?>><?php esc_html_e('All Users', 'RCSM_TEXT_DOMAIN'); ?></option>
										<option value="<?php echo esc_attr('selected_users'); ?>" <?php echo selected($sub_mail_option, 'selected_users'); ?>><?php esc_html_e('Selected Users', 'RCSM_TEXT_DOMAIN'); ?></option>
										<option value="<?php echo esc_attr('pending_users'); ?>" <?php echo selected($sub_mail_option, 'pending_users'); ?>><?php esc_html_e('Pending Users', 'RCSM_TEXT_DOMAIN'); ?></option>
										<option value="<?php echo esc_attr('active_users'); ?>" <?php echo selected($sub_mail_option, 'active_users'); ?>><?php esc_html_e('Active Users', 'RCSM_TEXT_DOMAIN'); ?></option>
										<option value="<?php echo esc_attr('already_mailed_users'); ?>" <?php echo selected($sub_mail_option, 'already_mailed_users'); ?>><?php esc_html_e('Mail Received Users', 'RCSM_TEXT_DOMAIN'); ?></option>
									</select>
								</div>
								<span><?php esc_html_e('Subject', 'RCSM_TEXT_DOMAIN'); ?></span>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add a Subject for sending mail.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								<input class="form-control" type="text" name="subscriber_mail_subject" id="subscriber_mail_subject" value="<?php echo esc_attr($wl_rcsm_options['subscriber_mail_subject']); ?>">
								<span><?php esc_html_e('Message', 'RCSM_TEXT_DOMAIN'); ?></span>
								<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add a Message to send subscriber users", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								
								<textarea class="form-control" rows="8" cols="8" id="subscriber_mail_message" name="subscriber_mail_message" placeholder="<?php esc_html_e('Add a Message to send subscriber Users', 'RCSM_TEXT_DOMAIN'); ?>"><?php if ($wl_rcsm_options['subscriber_mail_message'] != '') {	echo esc_attr($wl_rcsm_options['subscriber_mail_message']);	} ?></textarea>
								<!--<button name="submit_subscriber" class="subscriber_submit btn" type="submit"><?php // esc_html_e('Send','RCSM_TEXT_DOMAIN');?></button> -->
								<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_submit_subscriber" name="weblizar_rcsm_submit_subscriber" />
								<input class="subscriber_submit btn" id="submit_subscriber" name="submit_subscriber" type="button" value="<?php esc_html_e('Send', 'RCSM_TEXT_DOMAIN'); ?>">
							</div>
						</div>
						<div class="col-md-12 form-group">
							<div class="subscribers-settings-wrap settings-content">

								<h2><?php esc_html_e('Manage Subscriptions', 'RCSM_TEXT_DOMAIN'); ?></h2>
								<div class="col-md-12 str">
									<strong><?php esc_html_e('Export List as CSV', 'RCSM_TEXT_DOMAIN'); ?></strong>
									<?php
										$upload_dir_all = wp_upload_dir();
										$upload_dir_url = esc_url($upload_dir_all['baseurl']);
									?>
									<div class="row o_buttons">
										<div class="col-md-7 form-group">											
											<a class="button" href="#" onclick="return download_list('subscribers', '<?php echo esc_attr($comsoon_security_action_nonce); ?>', '<?php echo date("d-m-Y-H-i-s"); ?>', '<?php echo $upload_dir_url; ?>')"><?php esc_html_e('All Subscribers', 'RCSM_TEXT_DOMAIN'); ?></a>
											<a class="button" href="#" onclick="return download_list('active', '<?php echo esc_attr($comsoon_security_action_nonce); ?>', '<?php echo date("d-m-Y-H-i-s"); ?>', '<?php echo $upload_dir_url; ?>')"><?php esc_html_e('Active Subscribers', 'RCSM_TEXT_DOMAIN'); ?></a>
											<a class="button" href="#" onclick="return download_list('pending', '<?php echo esc_attr($comsoon_security_action_nonce); ?>', '<?php echo date("d-m-Y-H-i-s"); ?>', '<?php echo $upload_dir_url; ?>')"><?php esc_html_e('Pending Subscribers', 'RCSM_TEXT_DOMAIN'); ?></a>
											
										</div>
										<div class="col-md-5 form-group row">
											<?php
											global $wpdb;
											$table_name	   = $wpdb->prefix . "rcsm_subscribers";
											$user_sets_all = $wpdb->get_results("SELECT * FROM $table_name");
											if ($user_sets = $user_sets_all)
												if (count($user_sets) > 0) { ?>
												<!-- <input class="button button5 red left" name="remove_id" type="button" value="<?php //esc_attr_e('Remove Selected Id', 'RCSM_TEXT_DOMAIN'); ?>" id="remove-id"> -->
												<input class="button button5 red left" name="remove_subs" type="button" value="<?php esc_attr_e('Remove Selected Subscriber', 'RCSM_TEXT_DOMAIN'); ?>" id="remove-sub">
												<input class="button red button4 right" type="button" name="remove-all-subs" value="<?php esc_attr_e('Removed All Users', 'RCSM_TEXT_DOMAIN'); ?>" id="remove-all-subs">
											<?php } ?>
										</div>
										<div class="modal fade" id="appearance_removed_option" role="dialog">
											<div class="modal-dialog">
												<!-- Modal content-->
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h2 class="modal-title"><?php esc_html_e('Data Deleted SuccessFully', 'RCSM_TEXT_DOMAIN'); ?></h2>
													</div>
													<div class="modal-body">
														<p><?php esc_html_e('Your Selected Data Removed SuccessFully', 'RCSM_TEXT_DOMAIN'); ?></p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'RCSM_TEXT_DOMAIN'); ?></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
									$table_name 		   = $wpdb->prefix . "rcsm_subscribers";
									$user_sets_unsubscribe = $wpdb->get_results("SELECT * FROM $table_name WHERE flag = '0' ");
									$user_sets_subscribe   = $wpdb->get_results("SELECT * FROM $table_name WHERE flag = '1' ");
									$user_sets_all 		   = $wpdb->get_results("SELECT * FROM $table_name");
								?>
								<table class="wp-list-table widefat fixed posts" id="dataTables-example" data-wp-lists="list:post">
									<thead>
										<tr>
											<th scope="col" id="sub_cbx" class="manage-column column-title sortable asc"></th>
											<th scope="col" id="sub_cbx" class="manage-column column-title sortable asc">
												<span><?php esc_html_e('First Name', 'RCSM_TEXT_DOMAIN'); ?></span>
											</th>
											<th scope="col" id="sub_cbx" class="manage-column column-title sortable asc">
												<span><?php esc_html_e('Last Name', 'RCSM_TEXT_DOMAIN'); ?></span>
											</th>
											<th scope="col" id="sub_email" class="manage-column column-title sortable asc">
												<span><?php esc_html_e('Email', 'RCSM_TEXT_DOMAIN'); ?></span>
											</th>
											<th scope="col" id="sub_date" class="manage-column column-shortcode">
												<span><?php esc_html_e('Date', 'RCSM_TEXT_DOMAIN'); ?></span>
											</th>
											<th scope="col" id="act_code" class="manage-column column-shortcode">
												<span><?php esc_html_e('Subscription Status', 'RCSM_TEXT_DOMAIN'); ?></span>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($user_sets = $user_sets_all)
											if (count($user_sets) > 0) {
												foreach ($user_sets as $user_set) { ?>
												<tr style="background-color: #f9f6f6;" class="all_users">
													<!-- <td class="check-column"><?php //echo '<input type="checkbox" name="checkboxlist" class="select_ids" value="' . esc_js(esc_html($user_set->id)) . '">'; ?></td> -->
													<td class="check-column"><?php echo '<input type="checkbox" name="rem[]" class="select_subs" value="' . esc_js(esc_html($user_set->id)) . '">'; ?></td>
													<td class="shortcode column-shortcode"><?php echo esc_html($user_set->f_name); ?></td>
													<td class="shortcode column-shortcode"><?php echo esc_html($user_set->l_name); ?></td>
													<td class="shortcode column-shortcode"><?php echo esc_html($user_set->email); ?></td>
													<td class="shortcode column-shortcode"><?php echo esc_html($user_set->date); ?></td>
													<td class="shortcode column-shortcode">
														<?php 
															if ($user_set->flag == '1') {
																echo 'Active';
															} elseif ($user_set->flag == '2') {
																echo 'Mail Send';
															} else {
																echo 'Pending';
															} 
														?>
													</td>
												</tr>
											<?php
												}
											} else { ?>
											<tr>
												<td colspan="2">
													<div class="edmm-noresult"><?php esc_html_e('No Subscribers Found.', 'RCSM_TEXT_DOMAIN'); ?></div>
												</td>
											</tr>
										<?php
											} ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="1" id="weblizar_rcsm_settings_save_subscribe_form" name="weblizar_rcsm_settings_save_subscribe_form" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('subscribe_form', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('subscribe_form', '<?php echo esc_attr($comsoon_security_action_nonce); ?>')">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Counter Clock and progress Bar Setting -->
	<div class="ml-3 tab-pane col-md-12" id="counter-clock">
		<div class="col-md-12 option">
			<h1><?php esc_html_e('Counter Clock Timer Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
			
			<div class="tab-content">
				<div id="counter-settings" class="tab-pane fade in active">
					<!-- Counter Clock and Progress Bar General Settings -->
					<form action="post" id="weblizar_rcsm_counter_clock_option">
						<!-- <h1> <?php esc_html_e('Counter Clock Timer Settings', 'RCSM_TEXT_DOMAIN'); ?></h1> -->
						<div class="counter-option active" id="counter_clock">
							<div class="col-md-12 form-group">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
										<label><?php esc_html_e('Icon', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enter Relevant FontAwesome Icon Here", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
										<br />
									</div>
										<div class="col-md-3">
											<input class="form-control" type="text" name="counter_title_icon" id="counter_title_icon" value="<?php echo esc_attr($wl_rcsm_options['counter_title_icon']); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
										<label><?php esc_html_e('Title', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enter Counter Clock Title Here", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label><br /></div>
										<div class="col-md-3">
											<input class="form-control" type="text" name="counter_title" id="counter_title" value="<?php echo esc_attr($wl_rcsm_options['counter_title']); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
										<label><?php esc_html_e('Discription', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enter Counter Clock Message Here", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label><br /></div>
										<div class="col-md-3">
											<input class="form-control" type="text" name="counter_msg" id="counter_msg" value="<?php echo esc_attr($wl_rcsm_options['counter_msg']); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
										<label id="maintenance_time-label"><?php esc_html_e('Time To Live', 'RCSM_TEXT_DOMAIN'); ?></label>
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Add Live time here.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label><br /></div>
										<div class="col-md-3">
											<input class="form-control" type="text" name="maintenance_date" id="maintenance_date" value="<?php if ($wl_rcsm_options['maintenance_date'] != '') { echo esc_attr($wl_rcsm_options['maintenance_date']); } ?>">
											<label><?php esc_html_e('When does your site launch? Server time is :', 'RCSM_TEXT_DOMAIN'); ?> <?php echo '<em>' . esc_html(current_time(get_option('date_format')) . ' ' . esc_html(get_option('time_format'))) . '</em> <a href="' . esc_url(admin_url('options-general.php')) . '" target="_blank">(' . esc_html__('Edit', 'RCSM_TEXT_DOMAIN') . ')</a>'; ?></label>
										</div>
										<script>
											jQuery('#maintenance_date').datetimepicker();
										</script>
									</div>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<div class="col-md-3">
									<label><?php esc_html_e('Auto site Launched', 'RCSM_TEXT_DOMAIN'); ?></label><br />
									<div class="info">
										<input data-toggle="toggle" data-offstyle="off" type="checkbox" <?php if ($wl_rcsm_options['disable_the_plugin'] == 'on') echo "checked='unchecked'"; ?> id="disable_the_plugin" name="disable_the_plugin">
										<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enable and disable option to auto site launched and diactivate the coming soon page.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
									</div>
								</div>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="1" id="weblizar_rcsm_settings_save_counter_clock_option" name="weblizar_rcsm_settings_save_counter_clock_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('counter_clock_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('counter_clock_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>')">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Footer Section Setting -->
	<div class="ml-3 tab-pane col-md-12" id="footer">
		<div class="col-md-12 option">
			<h1><?php esc_html_e('Footer Options & Settings', 'RCSM_TEXT_DOMAIN'); ?></h1>
			<div class="tab-content">
				<div id="footer-setting" class="tab-pane fade in active">
					<!--Footer Section General Setting-->
					<form action="post" id="weblizar_rcsm_footer_option">
						<!-- <h1> <?php esc_html_e('Footer Settings', 'RCSM_TEXT_DOMAIN'); ?></h1> -->
						<div class="col-md-12 form-group">
							<div class="row">
								<div class="col-md-3">
							<label><?php esc_html_e('Footer Copyright Text', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enter Footer Copyright Text Here", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
						</div>

							<div class="col-md-3">
								<input class="form-control" type="text" name="footer_copyright_text" id="footer_copyright_text" value="<?php echo esc_attr($wl_rcsm_options['footer_copyright_text']); ?>">
							</div>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<div class="row">
								<div class="col-md-3">
									<label><?php esc_html_e('Footer Link', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enter Footer Copyright Link Here", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
								</div>
								<!-- </br> -->
								<div class="col-md-3">
									<input class="form-control" type="text" name="footer_link" id="footer_link" value="<?php echo esc_attr($wl_rcsm_options['footer_link']); ?>">
								</div>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<div class="row">
								<div class="col-md-3">
								<label><?php esc_html_e('Footer Link Text', 'RCSM_TEXT_DOMAIN'); ?></label>
									<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Enter Footer Link Text Here", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label></br>
								</div>
								<div class="col-md-3">
									<input class="form-control" type="text" name="footer_link_text" id="footer_link_text" value="<?php echo esc_attr($wl_rcsm_options['footer_link_text']); ?>">
								</div>
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_footer_option" name="weblizar_rcsm_settings_save_footer_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('footer_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('footer_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>')">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Advance options Setting -->
	<div class="ml-3 tab-pane col-md-12" id="advance">
		<div class="col-md-12 option">
			<h1><?php esc_html_e('Advance Options', 'RCSM_TEXT_DOMAIN'); ?></h1>
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#advance-settings"><?php esc_html_e('Advance Options Settings', 'RCSM_TEXT_DOMAIN'); ?></a></li>
			</ul>

			<div class="tab-content">
				<div id="advance-settings" class="tab-pane fade in active">
					<!-- Advance options General Setting -->
					<form action="post" id="weblizar_rcsm_advance_settings_option">


						<!-- <h1><?php esc_html_e('Advance Options Settings', 'RCSM_TEXT_DOMAIN'); ?></h1> -->
						<div class="col-md-12 form-group">

							<label><?php esc_html_e('Custom CSS Editor', 'RCSM_TEXT_DOMAIN'); ?> <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("This is a powerful feature provided here. No need to use custom css plugin, just paste your css code and see the magic.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
							<textarea class="form-control" rows="12" cols="8" id="custom_css" name="custom_css" placeholder="<?php esc_attr_e("Please type css directly. Don't add <style> tag after before CSS.", "RCSM_TEXT_DOMAIN"); ?>"><?php if ($wl_rcsm_options['custom_css'] != '') {
																																																												echo esc_attr($wl_rcsm_options['custom_css']);
																																																											} ?></textarea>
						</div>
						<div class="col-md-12 form-group">
							<label><?php esc_html_e('Google Analytic Tracking Code', 'RCSM_TEXT_DOMAIN'); ?> <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Paste your Google Analytics tracking code here. This will be added into themes footer. Copy only scripting code i.e no need to use script tag ", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a></label>
							<textarea class="form-control" rows="12" cols="8" id="google_analytics" name="google_analytics" placeholder="<?php esc_attr_e("Please directly copy and paste your Google Analytics script code here. Don't add <script> tag after before code.", "RCSM_TEXT_DOMAIN"); ?>"><?php if ($wl_rcsm_options['google_analytics'] != '') {
																																																																											echo esc_attr($wl_rcsm_options['google_analytics']);
																																																																										} ?></textarea>
						</div>
						<div class="col-md-12 form-group">
							<label><?php esc_html_e('All Settings Restored', 'RCSM_TEXT_DOMAIN'); ?></label>
							<label><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e("Use This button only when you want resotred all section default data.", "RCSM_TEXT_DOMAIN"); ?>"><i class="fas fa-info-circle tt-icon"></i></a> </label>
							<ul class="instruction_points">
								<li><?php esc_html_e('This option will deleted the all saved settings and Restored as default.', 'RCSM_TEXT_DOMAIN'); ?></li>
							</ul>
							<div class="restored">
								<input type="hidden" value="1" id="weblizar_rcsm_settings_all_restored_settings_option" name="weblizar_rcsm_settings_all_restored_settings_option" />
								<input class="button red" type="button" name="weblizar_rcsm_option_data_restored" value="<?php esc_attr_e('Restored All Default Settings', 'RCSM_TEXT_DOMAIN'); ?>" id="weblizar_rcsm_option_data_restored">
							</div>
						</div>
						<div class="restore">
							<input type="hidden" value="<?php echo esc_attr('1'); ?>" id="weblizar_rcsm_settings_save_advance_settings_option" name="weblizar_rcsm_settings_save_advance_settings_option" />
							<input class="button left" type="button" name="reset" value="<?php esc_attr_e('Restore Defaults', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_reset('advance_settings_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>');">
							<input class="button button-primary left" type="button" value="<?php esc_attr_e('Save Options', 'RCSM_TEXT_DOMAIN'); ?>" onclick="weblizar_rcsm_option_data_save('advance_settings_option', '<?php echo esc_attr($comsoon_security_action_nonce); ?>')">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


