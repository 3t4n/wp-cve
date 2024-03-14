<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// get current plugin version
$csmm_current_version = get_option( 'csmm_current_version' );
$csmm_last_version = get_option( 'csmm_last_version' );

//defaults
$csmm_content = array();
$csmm_templates = array();
$csmm_settings = array();
$csmm_posts = array();
$csmm_pages = array();
$csmm_other_pages = array();
$csmm_website_mode = 3;
$csmm_template_id = 1;
$csmm_logo_id = "";
$csmm_logo_alt = "coming-soon-logo";
$csmm_logo_url[0] = "";
$csmm_title = sanitize_text_field("Coming Soon");
$csmm_description = sanitize_text_field("Thank you for visiting our website! We are currently working on creating a new and exciting online experience for you. While we finish up the final touches, please sign up for our newsletter to receive exclusive updates and offers.");
$csmm_countdown = 1;
$csmm_countdown_title = sanitize_text_field("Launching In...");
$csmm_current_date = date('Y-m-d');
$csmm_launch_dt = date('Y-m-d', strtotime($csmm_current_date . ' +1 month'));
$csmm_countdown_date = date('Y-m-d', strtotime("$csmm_launch_dt"));
$csmm_countdown_time = current_datetime()->format('H:i');
$csmm_sm_facebook = $csmm_sm_twitter = $csmm_sm_instagram = "#";

//load CSMM settings
$csmm_settings = get_option('csmm_settings');
//print_r($csmm_settings);
if(is_array($csmm_settings)){
	if(array_key_exists('website_mode', $csmm_settings)){ $csmm_website_mode = $csmm_settings['website_mode']; }
	if(array_key_exists('selected_posts', $csmm_settings)){ $csmm_posts = $csmm_settings['selected_posts']; }
	if(array_key_exists('selected_pages', $csmm_settings)){ $csmm_pages = $csmm_settings['selected_pages']; }
	if(array_key_exists('selected_other_pages', $csmm_settings)){ $csmm_other_pages = $csmm_settings['selected_other_pages']; }
}
//load CSMM templates
$csmm_templates = get_option('csmm_templates');
if(is_array($csmm_templates)){
	if(array_key_exists('template_id', $csmm_templates)){ $csmm_template_id = $csmm_templates['template_id']; }
}
//load CSMM content
$csmm_content = get_option('csmm_content');
if(is_array($csmm_content)){
	if(array_key_exists('logo', $csmm_content)){ 
		$csmm_logo_id = $csmm_content['logo']; 
		$csmm_logo_url = wp_get_attachment_image_src($csmm_logo_id, 'medium', true); // attachment medium URL
	}
	if(array_key_exists('title', $csmm_content)){ $csmm_title = $csmm_content['title']; }
	if(array_key_exists('description', $csmm_content)){ $csmm_description = $csmm_content['description']; }
	if(array_key_exists('countdown', $csmm_content)){ $csmm_countdown = $csmm_content['countdown']; }
	if(array_key_exists('countdown_title', $csmm_content)){ $csmm_countdown_title = $csmm_content['countdown_title']; }
	if(array_key_exists('countdown_date', $csmm_content)){ $csmm_countdown_date = $csmm_content['countdown_date']; }
	if(array_key_exists('countdown_time', $csmm_content)){ $csmm_countdown_time = $csmm_content['countdown_time']; }
	if(array_key_exists('susbcriber_form', $csmm_content)){ $csmm_susbcriber_form = $csmm_content['susbcriber_form']; }
	if(array_key_exists('video_url', $csmm_content)){ $csmm_video_url = $csmm_content['video_url']; }
	if(array_key_exists('slide_ids', $csmm_content)){ $csmm_slide_ids = $csmm_content['slide_ids']; }
	if(array_key_exists('custom_css', $csmm_content)){ $csmm_custom_css = $csmm_content['custom_css']; }
	
	// launch date calculation
	//date_default_timezone_set(wp_timezone_string());
	$csmm_launch_date = date('F d, Y', strtotime($csmm_countdown_date));
	$csmm_launch_time = date('H:i:s', strtotime($csmm_countdown_time));
	$csmm_launch_dt = $csmm_launch_date." ".$csmm_launch_time; // March 25, 2024 15:37:25
}

// load social media
$csmm_social_media = get_option('csmm_social_media');
if(is_array($csmm_social_media)){
	if(array_key_exists('csmm_sm_facebook', $csmm_social_media)){ $csmm_sm_facebook = $csmm_social_media['csmm_sm_facebook']; }
	if(array_key_exists('csmm_sm_twitter', $csmm_social_media)){ $csmm_sm_twitter = $csmm_social_media['csmm_sm_twitter']; }
	if(array_key_exists('csmm_sm_youtube', $csmm_social_media)){ $csmm_sm_youtube = $csmm_social_media['csmm_sm_youtube']; }
	if(array_key_exists('csmm_sm_instagram', $csmm_social_media)){ $csmm_sm_instagram = $csmm_social_media['csmm_sm_instagram']; }
	if(array_key_exists('csmm_sm_linkedin', $csmm_social_media)){ $csmm_sm_linkedin = $csmm_social_media['csmm_sm_linkedin']; }
	if(array_key_exists('csmm_sm_pinterest', $csmm_social_media)){ $csmm_sm_pinterest = $csmm_social_media['csmm_sm_pinterest']; }
	if(array_key_exists('csmm_sm_tumblr', $csmm_social_media)){ $csmm_sm_tumblr = $csmm_social_media['csmm_sm_tumblr']; }
	if(array_key_exists('csmm_sm_snapchat', $csmm_social_media)){ $csmm_sm_snapchat = $csmm_social_media['csmm_sm_snapchat']; }
	if(array_key_exists('csmm_sm_behance', $csmm_social_media)){ $csmm_sm_behance = $csmm_social_media['csmm_sm_behance']; }
	if(array_key_exists('csmm_sm_dribbble', $csmm_social_media)){ $csmm_sm_dribbble = $csmm_social_media['csmm_sm_dribbble']; }
	if(array_key_exists('csmm_sm_whatsapp', $csmm_social_media)){ $csmm_sm_whatsapp = $csmm_social_media['csmm_sm_whatsapp']; }
	if(array_key_exists('csmm_sm_tiktok', $csmm_social_media)){ $csmm_sm_tiktok = $csmm_social_media['csmm_sm_tiktok']; }
	if(array_key_exists('csmm_sm_qq', $csmm_social_media)){ $csmm_sm_qq = $csmm_social_media['csmm_sm_qq']; }
}
?>
<div class="m-3">


<div class="contain">
	<div class="row" style="--bs-gutter-x: 0rem;">
		<div class="col-md-6 p-2">
			<h3 class="float-start"><?php esc_html_e( 'Coming Soon Maintenance Mode', 'coming-soon-maintenance-mode' ); ?>
			<small>
			<?php
			if ( $csmm_current_version != '' ) {
				echo esc_html( 'v' );
				echo esc_html($csmm_current_version);
			}
			?>
			</small>
			</h3>
		</div>
		<div class="col-md-6 p-2 sticky">
			<div class="website-mode-top btn-group d-none" role="group" aria-label="Basic radio toggle button group">
				<input onclick="return csmm_save('website-mode-top', '');" type="radio" class="btn-check" name="website-mode-top" id="website-mode-top1" value="1" autocomplete="off" <?php if($csmm_website_mode == 1 ) echo esc_attr("checked"); ?>>
				<label class="btn btn-lg btn-outline-danger" for="website-mode-top1"><?php esc_html_e( 'Coming Soon', 'coming-soon-maintenance-mode' ); ?></label>
				<input onclick="return csmm_save('website-mode-top', '');" type="radio" class="btn-check" name="website-mode-top" id="website-mode-top2" value="2" autocomplete="off" <?php if($csmm_website_mode == 2 ) echo esc_attr("checked"); ?>>
				<label class="btn btn-lg btn-outline-danger" for="website-mode-top2"><?php esc_html_e( 'Maintenance', 'coming-soon-maintenance-mode' ); ?></label>
				<input onclick="return csmm_save('website-mode-top', '');" type="radio" class="btn-check" name="website-mode-top" id="website-mode-top3" value="3" autocomplete="off" <?php if($csmm_website_mode == 3 ) echo esc_attr("checked"); ?>>
				<label class="btn btn-lg btn-outline-danger" for="website-mode-top3"><?php esc_html_e( 'Live', 'coming-soon-maintenance-mode' ); ?></label>
			</div>
			<a id="csmm-live-preview" href="<?php echo esc_url( get_site_url().'/?preview=true&csmm=true' ); ?>" target="framename" class="btn btn-lg btn-outline-warning float-end"><strong><?php esc_html_e( 'Check Live Preview', 'coming-soon-maintenance-mode' ); ?></strong></a>
		</div>
		<div class="col-md-12 p-2 border">
			<div class="col-md-12 p-2">
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<button class="nav-link active" id="nav-settings-tab" data-bs-toggle="tab" data-bs-target="#nav-settings" type="button" role="tab" aria-controls="nav-settings" aria-selected="true"><?php esc_html_e( 'Website Mode', 'coming-soon-maintenance-mode' ); ?></button>
						<button class="nav-link" id="nav-templates-tab" data-bs-toggle="tab" data-bs-target="#nav-templates" type="button" role="tab" aria-controls="nav-templates" aria-selected="false"><?php esc_html_e( 'Templates', 'coming-soon-maintenance-mode' ); ?></button>
						<button class="nav-link" id="nav-content-tab" data-bs-toggle="tab" data-bs-target="#nav-content" type="button" role="tab" aria-controls="nav-content" aria-selected="false"><?php esc_html_e( 'Settings', 'coming-soon-maintenance-mode' ); ?></button>
						<button class="nav-link" id="nav-social-media-tab" data-bs-toggle="tab" data-bs-target="#nav-social-media" type="button" role="tab" aria-controls="nav-social-media" aria-selected="false"><?php esc_html_e( 'Social Media', 'coming-soon-maintenance-mode' ); ?></button>
						<!--<button class="nav-link" id="nav-more-tab" data-bs-toggle="tab" data-bs-target="#nav-more" type="button" role="tab" aria-controls="nav-more" aria-selected="false"><?php esc_html_e( 'More', 'coming-soon-maintenance-mode' ); ?></button>-->
						<button class="nav-link" id="nav-docs-tab" data-bs-toggle="tab" data-bs-target="#nav-docs" type="button" role="tab" aria-controls="nav-docs" aria-selected="false"><?php esc_html_e( 'Docs', 'coming-soon-maintenance-mode' ); ?></button>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					
					<div class="tab-pane fade show active" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings-tab" tabindex="0">
						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Website Mode', 'coming-soon-maintenance-mode' ); ?></h5>
								<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
									<input type="radio" class="btn-check" name="website-mode" id="website-mode1" value="1" autocomplete="off" <?php if($csmm_website_mode == 1 ) echo esc_attr("checked"); ?>>
									<label class="btn btn-lg btn-outline-secondary" for="website-mode1"><?php esc_html_e( 'Coming Soon', 'coming-soon-maintenance-mode' ); ?></label>
									<input type="radio" class="btn-check" name="website-mode" id="website-mode2" value="2" autocomplete="off" <?php if($csmm_website_mode == 2 ) echo esc_attr("checked"); ?>>
									<label class="btn btn-lg btn-outline-secondary" for="website-mode2"><?php esc_html_e( 'Maintenance', 'coming-soon-maintenance-mode' ); ?></label>
									<input type="radio" class="btn-check" name="website-mode" id="website-mode3" value="3" autocomplete="off" <?php if($csmm_website_mode == 3 ) echo esc_attr("checked"); ?>>
									<label class="btn btn-lg btn-outline-secondary" for="website-mode3"><?php esc_html_e( 'Live', 'coming-soon-maintenance-mode' ); ?></label>
								</div>
								<br><br>
								<div>
									<h5 class="mt-3"><?php esc_html_e( 'Video Tutorial - Check How Plugin Works', 'coming-soon-maintenance-mode' ); ?></h5>
									<iframe width="100%" height="415" src="https://www.youtube.com/embed/HYVdcnvPi08" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Website Mode Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-website-mode-info" class="form-text"><strong><?php esc_html_e( 'What are the website modes?', 'coming-soon-maintenance-mode' ); ?></strong></div>
									<div id="csmm-website-mode-info" class="form-text">
										<?php esc_html_e( 'Website mode defines the status of the website, such as whether it is available to access for visitors or not.', 'coming-soon-maintenance-mode' ); ?>
									</div>
									<div id="csmm-website-mode-info" class="form-text">
										<strong><?php esc_html_e( 'Coming Soon', 'coming-soon-maintenance-mode' ); ?></strong> - <?php esc_html_e( 'this mode is used to inform visitors that a website is under construction or coming soon. Expect an update from the administrator.', 'coming-soon-maintenance-mode' ); ?>
									</div>
									<div id="csmm-website-mode-info" class="form-text">
										<strong><?php esc_html_e( 'Maintenance', 'coming-soon-maintenance-mode' ); ?></strong> - <?php esc_html_e( 'this mode is a way for website administrators to temporarily disable access to the particular part of website while it is being updated or undergoing maintenance.', 'coming-soon-maintenance-mode' ); ?>
									</div>
									<div id="csmm-website-mode-info" class="form-text">
										<strong><?php esc_html_e( 'Live', 'coming-soon-maintenance-mode' ); ?></strong> - <?php esc_html_e( 'this mode means that the website is accessible and available for anyone to visit on the internet.', 'coming-soon-maintenance-mode' ); ?>
									</div>
									<div id="csmm-website-mode-info" class="form-text">
										<span class="badge text-bg-danger"><?php esc_html_e( 'Important Note', 'coming-soon-maintenance-mode' ); ?></span><br>
										<strong><?php esc_html_e( 'Check Live Preview', 'coming-soon-maintenance-mode' ); ?></strong> <?php esc_html_e( 'button is used to check the website as a visitor only for admin.', 'coming-soon-maintenance-mode' ); ?>
										<?php esc_html_e( 'Coming Soon and Maintenance Mode will not work for admin.', 'coming-soon-maintenance-mode' ); ?>
									</div>
								</div>
							</div>
							<div id="maintenance-mode-options" class="row" style="<?php if($csmm_website_mode != 2 ) echo esc_attr("display:none"); ?>">
								<div class="col-md-6 p-2 mt-3 border bg-light">
									<h5 class=""><?php esc_html_e( 'Maintenance Mode Options', 'coming-soon-maintenance-mode' ); ?></h5>
								</div>
								<div class="col-md-6 p-2 mt-3 border">
									<h5 class=""><?php esc_html_e( 'Maintenance Mode Options Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
									<div id="csmm-maintenance-mode-info-1" class="form-text">
										<?php esc_html_e( 'If you need to perform updates on a specific part of your website, you can use the option below to display a maintenance message to visitors on specific posts and pages. All other parts of your website will remain accessible during this time.', 'coming-soon-maintenance-mode' ); ?>
									</div>
								</div>
								<div class="col-md-4 p-2 border bg-light">
									<h6 class=""><?php esc_html_e( 'Select Posts', 'coming-soon-maintenance-mode' ); ?></h6>
									<div class="">
										<?php $posts = get_posts(); ?>
										<div>
										<input type="checkbox" id="check-all-posts" name="csmm_posts" value="-1" <?php if(in_array( -1, $csmm_posts)) echo esc_attr("checked"); ?> />&nbsp;&nbsp;<strong><?php _e('Select All', 'coming-soon-maintenance-mode' ); ?></strong>
										</div>
										<?php foreach ( $posts as $post ) { ?>
										<div>
											<input type="checkbox" class="csmm-post-list" name="csmm_posts" value="<?php echo esc_attr($post->ID); ?>" <?php if(in_array( $post->ID, $csmm_posts)) echo esc_attr("checked"); ?> />&nbsp;&nbsp;<?php echo esc_html($post->post_title); ?>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="col-md-4 p-2 border bg-light">
									<h6 class=""><?php esc_html_e( 'Select Pages', 'coming-soon-maintenance-mode' ); ?></h6>
									<div class="">
										<?php $pages = get_pages(); ?>
										<div>
											<input type="checkbox" id="check-all-pages" name="csmm_pages" value="-1" <?php if(in_array( -1, $csmm_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp;<strong><?php _e('Select All', 'coming-soon-maintenance-mode'); ?></strong>
										</div>
										<?php foreach ( $pages as $page ) { ?>
										<div>
											<input type="checkbox" class="csmm-page-list" name="csmm_pages" value="<?php echo $page->ID; ?>" <?php if(in_array( $page->ID, $csmm_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp;<?php echo esc_html($page->post_title); ?>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="col-md-4 p-2 border bg-light">
									<h6 class=""><?php esc_html_e( 'Select Other Pages', 'coming-soon-maintenance-mode' ); ?></h6>
									<div class="">
										<div>
										<input type="checkbox" id="check-all-other-pages" name="csmm_other_pages" value="-1" <?php if(in_array( -1, $csmm_other_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp;<strong><?php _e('Select All', 'coming-soon-maintenance-mode'); ?></strong>
										</div>
										<div>
										<input type="checkbox" class="csmm-other-page-list" name="csmm_other_pages" value="front" <?php if(in_array( "front", $csmm_other_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp; <a href="https://developer.wordpress.org/reference/functions/is_front_page/" target="_blank"><?php _e('Front Page', 'coming-soon-maintenance-mode'); ?></a>
										</div>
										<div>
										<input type="checkbox" class="csmm-other-page-list" name="csmm_other_pages" value="home" <?php if(in_array( "home", $csmm_other_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp; <a href="https://developer.wordpress.org/reference/functions/is_home/" target="_blank"><?php _e('Home Page', 'coming-soon-maintenance-mode'); ?></a>
										</div>
										<div>
										<input type="checkbox" class="csmm-other-page-list" name="csmm_other_pages" value="category" <?php if(in_array( "category", $csmm_other_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp; <a href="https://developer.wordpress.org/reference/functions/is_category/" target="_blank"><?php _e('Category Page', 'coming-soon-maintenance-mode'); ?></a>
										</div>
										<div>
										<input type="checkbox" class="csmm-other-page-list" name="csmm_other_pages" value="tag" <?php if(in_array( "tag", $csmm_other_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp; <a href="https://developer.wordpress.org/reference/functions/is_tag/" target="_blank"><?php _e('Tag Page', 'coming-soon-maintenance-mode'); ?></a>
										</div>
										<div>
										<input type="checkbox" class="csmm-other-page-list" name="csmm_other_pages" value="search" <?php if(in_array( "search", $csmm_other_pages)) echo esc_attr("checked"); ?> />&nbsp;&nbsp; <a href="https://developer.wordpress.org/reference/functions/is_search/" target="_blank"><?php _e('Search Page', 'coming-soon-maintenance-mode'); ?></a>
										</div>
									</div>
								</div>
							</div>
						
							<div class="col-md-12 p-2 mt-3">
								<div id="csmm-setings-saving" class="col-md-12 p-2 mt-3 spinner-grow text-dark d-none" role="status">
									<span class="visually-hidden"></span>
								</div>
							</div>
							<div class="col-md-12 p-2 mt-3">
								<button type="button" id="csmm-save-setings" name="csmm-save-setings" class="btn btn-lg btn-outline-secondary" onclick="return csmm_save('settings', '')"><i class="fa-regular fa-floppy-disk"></i> <?php esc_html_e( 'Save', 'coming-soon-maintenance-mode' ); ?></button>
							</div>
						</div><!-- settings tab row end-->
					</div><!-- settings tab content end-->
					
					<!-- templates tab content start-->
					<div class="tab-pane fade" id="nav-templates" role="tabpanel" aria-labelledby="nav-templates-tab" tabindex="0">
						<div class="col-md-12 p-2 mt-3">
							<h5 class=""><?php esc_html_e( 'Select Template', 'coming-soon-maintenance-mode' ); ?></h5>
							<div id="csmm-templates-info-1" class="form-text"><?php esc_html_e( 'Click on activate button under the template for selection.', 'coming-soon-maintenance-mode' ); ?></div>
							<div id="csmm-templates-info-2" class="form-text"><span class="badge text-bg-info"><?php esc_html_e( 'Important Note', 'coming-soon-maintenance-mode' ); ?></span> Only <strong>Template 1, 4 and 8</strong> is available in free version.</div>
						</div>
						<div class="row">
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/1.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<button type="button" class="btn btn-lg btn-secondary" disabled><?php esc_html_e( 'Template 1', 'coming-soon-maintenance-mode' ); ?></button>
									<button type="button" class="btn btn-lg <?php if($csmm_template_id == 1) echo esc_attr("btn-primary"); else echo esc_attr("btn-outline-primary"); ?> cmss-templates" id="csmm-t1" value="1" onclick="return csmm_save('templates', this.id);"><?php if($csmm_template_id == 1) echo esc_attr("Activated"); else echo esc_attr("Activate"); ?></button>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/2.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" class="btn btn-lg btn-warning"><i class="fa-solid fa-cart-shopping"></i> Pro Version</a>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/3.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" class="btn btn-lg btn-warning"><i class="fa-solid fa-cart-shopping"></i> Pro Version</a>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/4.webp'); ?>" class="w-100 h-100 rounded-4 border">
								</div>
								<div class="p-2 text-center">
									<button type="button" class="btn btn-lg btn-secondary" disabled><?php esc_html_e( 'Template 4', 'coming-soon-maintenance-mode' ); ?></button>
									<button type="button" class="btn btn-lg <?php if($csmm_template_id == 4) echo esc_attr("btn-primary"); else echo esc_attr("btn-outline-primary"); ?> cmss-templates" id="csmm-t4" value="4" onclick="return csmm_save('templates', this.id);"><?php if($csmm_template_id == 4) echo esc_attr("Activated"); else echo esc_attr("Activate"); ?></button>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/5.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" class="btn btn-lg btn-warning"><i class="fa-solid fa-cart-shopping"></i> Pro Version</a>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/6.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" class="btn btn-lg btn-warning"><i class="fa-solid fa-cart-shopping"></i> Pro Version</a>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/7.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" class="btn btn-lg btn-warning"><i class="fa-solid fa-cart-shopping"></i> Pro Version</a>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/8.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<button type="button" class="btn btn-lg btn-secondary" disabled><?php esc_html_e( 'Template 8', 'coming-soon-maintenance-mode' ); ?></button>
									<button type="button" class="btn btn-lg <?php if($csmm_template_id == 8) echo esc_attr("btn-primary"); else echo esc_attr("btn-outline-primary"); ?> cmss-templates" id="csmm-t8" value="8" onclick="return csmm_save('templates', this.id);"><?php if($csmm_template_id == 8) echo esc_attr("Activated"); else echo esc_attr("Activate"); ?></button>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/9.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" class="btn btn-lg btn-warning"><i class="fa-solid fa-cart-shopping"></i> Pro Version</a>
								</div>
							</div>
							<div class="col-md-6 p-2">
								<div class="p-4">
									<img src="<?php echo esc_url( CSMM_URL.'admin/assets/img/10.webp'); ?>" class="w-100 h-100 rounded-4">
								</div>
								<div class="p-2 text-center">
									<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" class="btn btn-lg btn-warning"><i class="fa-solid fa-cart-shopping"></i> Pro Version</a>
								</div>
							</div>
							<div class="col-md-12 p-2">
								<div class="d-grid gap-2">
									<button disabled class="btn btn-lg btn-primary" type="button">We are working hard, more new FREE templates will be available soon.</button>
									<a class="btn btn-lg btn-info" target="_blank" href="https://wordpress.org/plugins/coming-soon-maintenance-mode/">Rate <i class="fa-solid fa-star"></i> and Share Feedback <i class="fa-solid fa-comment-dots"></i> on <i class="fa-brands fa-wordpress"></i> if you like our plugin.</a>
								</div>
							</div>
						</div>
					</div><!-- templates tab content end-->
					
					<div class="tab-pane fade p-2" id="nav-content" role="tabpanel" aria-labelledby="nav-content-tab" tabindex="0">
						<div class="row">
							<div class="col-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Logo', 'coming-soon-maintenance-mode' ); ?></h5>
								<div>
									<ul id="csmm-logo">
										<?php if($csmm_logo_id) { ?>
										<li class="col-md-4 csmm-logo-<?php echo esc_attr($csmm_logo_id); ?>" data-position="<?php echo esc_attr($csmm_logo_id); ?>">
											<input type="hidden" class="form-control csmm-logo-id" id="csmm-logo-id" name="csmm-logo-id" value="<?php echo esc_attr($csmm_logo_id); ?>">
											<img src="<?php echo esc_url($csmm_logo_url[0]); ?>" class="img-thumbnail mt-3 bg-light">
											<div class="d-grid gap-2">
												<button type="button" id="csmm-remove-logo" onclick="csmm_save('remove-logo', <?php echo esc_attr($csmm_logo_id); ?>);" class="btn btn-danger btn-block"><i class="fa-solid fa-trash"></i> <?php esc_html_e( 'Remove Logo', 'coming-soon-maintenance-mode' ); ?></button>
											</div>
										</li>
										<?php } ?>
									</ul>
									<div>
										<button type="button" id="csmm-upload-logo" class="btn btn-secondary"><i class="fa-solid fa-upload"></i> <?php esc_html_e( 'Upload Logo', 'coming-soon-maintenance-mode' ); ?></button>
										<!--<button type="button" id="csmm-remove-logo" class="btn btn-secondary mt-3" onclick="csmm_save('remove-logo', '');"><i class="fa-solid fa-trash"></i> <?php esc_html_e( 'Remove Logo', 'coming-soon-maintenance-mode' ); ?></button>-->
									</div>
								</div>
							</div>
							<div class="col-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Logo Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-upload-logo-info" class="form-text"><?php esc_html_e( 'Upload your logo for your website.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-upload-logo-info" class="form-text"><?php esc_html_e( 'The recommended logo size is 300x170px.', 'coming-soon-maintenance-mode' ); ?></div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Title', 'coming-soon-maintenance-mode' ); ?></h5>
								<div>
									<input type="text" class="form-control" id="csmm-title" name="csmm-title" value="<?php echo esc_attr($csmm_title); ?>">
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Title Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-title-info-1" class="form-text"><?php esc_html_e( 'Write a title for the website.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-title-info-2" class="form-text"><?php esc_html_e( 'Leave it blank if you dont want to display it.', 'coming-soon-maintenance-mode' ); ?></div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Description', 'coming-soon-maintenance-mode' ); ?></h5>
								<div>
									<textarea id="csmm-description" name="csmm-description" class="form-control" rows="3" style="height:101px;"><?php echo esc_textarea(stripslashes($csmm_description)); ?></textarea>
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Description Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-description-info" class="form-text"><?php esc_html_e( 'Write a brief description for the website.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-title-info" class="form-text"><?php esc_html_e( 'Leave it blank if you dont want to display it.', 'coming-soon-maintenance-mode' ); ?></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Countdown', 'coming-soon-maintenance-mode' ); ?></h5>
								<div>
									<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
										<input type="radio" class="btn-check" name="csmm-countdown" id="csmm-countdown1" value="1" autocomplete="off" <?php if($csmm_countdown == 1 ) echo esc_attr("checked"); ?>>
										<label class="btn btn-outline-secondary" for="csmm-countdown1"><?php esc_html_e( 'Yes', 'coming-soon-maintenance-mode' ); ?></label>
										<input type="radio" class="btn-check" name="csmm-countdown" id="csmm-countdown2" value="2" autocomplete="off" <?php if($csmm_countdown == 2 ) echo esc_attr("checked"); ?>>
										<label class="btn btn-outline-secondary" for="csmm-countdown2"><?php esc_html_e( 'No', 'coming-soon-maintenance-mode' ); ?></label>
									</div>
								</div>
								<div class="col-md-6 mt-3">
									<h6><?php esc_html_e( 'Timezone', 'coming-soon-maintenance-mode' ); ?></h6>
									<a href="options-general.php#timezone_string" target="_blank" id="csmm-timezone" class="btn btn-secondary"><?php esc_html_e( 'set timezone', 'coming-soon-maintenance-mode' ); ?></a>
								</div>
								<div class="col-md-6 mt-3">
									<h6><?php esc_html_e( 'Countdown Heading', 'coming-soon-maintenance-mode' ); ?></h6>
									<input type="text" class="form-control" id="csmm-countdown-title" name="csmm-countdown-title" value="<?php echo esc_attr($csmm_countdown_title); ?>">
								</div>
								<div class="col-md-6 mt-3">
									<h6><?php esc_html_e( 'Date', 'coming-soon-maintenance-mode' ); ?></h6>
									<input type="date" class="form-control" id="csmm-countdown-date" name="csmm-countdown-date" value="<?php echo esc_attr($csmm_countdown_date); ?>">
								</div>
								<div class="col-md-6 mt-3">
									<h6><?php esc_html_e( 'Time', 'coming-soon-maintenance-mode' ); ?></h6>
									<input type="time" class="form-control" id="csmm-countdown-time" name="csmm-countdown-time" value="<?php echo esc_attr($csmm_countdown_time); ?>">
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Countdown Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-countdown-info" class="form-text"><?php esc_html_e( 'Set a website launch countdown.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-countdown-timezone-info" class="form-text"><?php esc_html_e( 'Timezone setting used to calculate website launch.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-countdown-title-info" class="form-text"><?php esc_html_e( 'Write a countdown heading which will appear above the countdown.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-countdown-dt-info-1" class="form-text"><?php esc_html_e( 'Set a date and time for the website launch.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-countdown-dt-info-2" class="form-text"><?php esc_html_e( 'When the countdown ends, the website will go live automatically.', 'coming-soon-maintenance-mode' ); ?></div>
									<div class="form-text"><span class="badge text-bg-danger"><?php esc_html_e( 'Important Note', 'coming-soon-maintenance-mode' ); ?></span><br><?php esc_html_e( 'Automatic website launch on the countdown end is available only in the Pro version.', 'coming-soon-maintenance-mode' ); ?></div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Subscriber Form', 'coming-soon-maintenance-mode' ); ?></h5>
								<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
									<input disabled type="radio" class="btn-check" name="susbcriber-form" id="susbcriber-form1" value="1" autocomplete="off">
									<label class="btn btn-outline-secondary" for="susbcriber-form1"><?php esc_html_e( 'Yes', 'coming-soon-maintenance-mode' ); ?></label>
									<input disabled type="radio" class="btn-check" name="susbcriber-form" id="susbcriber-form2" value="2" autocomplete="off" checked>
									<label class="btn btn-outline-secondary" for="susbcriber-form2"><?php esc_html_e( 'No', 'coming-soon-maintenance-mode' ); ?></label>
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Subscriber Form Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-countdown-info-1" class="form-text"><?php esc_html_e( 'Display an email subscription form for visitors to sign up for the mailing list.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-countdown-info-2" class="form-text"><?php esc_html_e( 'The email subscription mailing list, used to send updates and offers about your website via email.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-countdown-info-3" class="form-text"><?php esc_html_e( 'Admin can download mailing list from Subscribers sub menu and import in any email maketing tools.', 'coming-soon-maintenance-mode' ); ?></div>
									<div class="form-text"><a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank"><span class="badge text-bg-warning"><?php esc_html_e( 'Setting available in Pro', 'coming-soon-maintenance-mode' ); ?></span></a></div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Video URL', 'coming-soon-maintenance-mode' ); ?></h5>
								<div>
									<input disabled type="text" class="form-control" id="csmm-video-url" name="csmm-video-url" value="">
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Video URL Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-video-url-info" class="form-text"><?php esc_html_e( 'Provide a Youtube / Vimeo embed video URL.', 'coming-soon-maintenance-mode' ); ?></div>
									<div class="form-text"><a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank"><span class="badge text-bg-warning"><?php esc_html_e( 'Setting available in Pro', 'coming-soon-maintenance-mode' ); ?></span></a></div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Background Slides', 'coming-soon-maintenance-mode' ); ?></h5>
								<div>
									<ul id="csmm-slides">

									</ul>
									<div>
										<button disabled type="button" id="csmm-upload-slide" class="btn btn-secondary mt-3"><i class="fa-solid fa-upload"></i> <?php esc_html_e( 'Upload Slide', 'coming-soon-maintenance-mode' ); ?></button>
										<!--<button type="button" id="csmm-remove-all-slide" class="btn btn-secondary mt-3" onclick="csmm_save('remove-all-slides', '');"><i class="fa-solid fa-trash"></i> <?php esc_html_e( 'Remove All Slide', 'coming-soon-maintenance-mode' ); ?></button>-->
									</div>
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<div>
									<h5 class=""><?php esc_html_e( 'Background Slides Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
									<div id="csmm-upload-slides-info-1" class="form-text"><?php esc_html_e( 'Upload the images for the background slideshow.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-upload-slides-info-2" class="form-text"><?php esc_html_e( 'Recommended image slide resolution is 1920x1080px.', 'coming-soon-maintenance-mode' ); ?></div>
									<div class="form-text"><a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank"><span class="badge text-bg-warning"><?php esc_html_e( 'Setting available in Pro', 'coming-soon-maintenance-mode' ); ?></span></a></div>
								</div>
							</div>
						</div>
							
						<div class="row">
							<div class="col-md-6 p-2 mt-3 border bg-light">
								<h5 class=""><?php esc_html_e( 'Custom CSS', 'coming-soon-maintenance-mode' ); ?></h5>
								<div>
									<textarea disabled id="csmm-custom-css" name="csmm-custom-css" class="form-control" rows="3" style="height:145px;"></textarea>
								</div>
							</div>
							<div class="col-md-6 p-2 mt-3 border">
								<h5 class=""><?php esc_html_e( 'Custom CSS Tips', 'coming-soon-maintenance-mode' ); ?> <i class="fa-solid fa-circle-info m-1"></i></h5>
								<div>
									<div id="csmm-custom-css-info" class="form-text"><?php esc_html_e( 'Write custom CSS code to modify the template designs.', 'coming-soon-maintenance-mode' ); ?></div>
									<div id="csmm-custom-css-info" class="form-text"><?php esc_html_e( 'Do not use the <style> tag in the CSS field.', 'coming-soon-maintenance-mode' ); ?></div>
									<div class="form-text"><a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank"><span class="badge text-bg-warning"><?php esc_html_e( 'Setting available in Pro', 'coming-soon-maintenance-mode' ); ?></span></a></div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div id="csmm-content-saving" class="col-md-12 p-2 mt-3 spinner-grow text-dark d-none" role="status">
								<span class="visually-hidden"></span>
							</div>
							<div class="col-md-12 p-2 mt-3">
								<button type="button" id="csmm-save-content" name="csmm-save-content" class="btn btn-lg btn-outline-secondary" onclick="return csmm_save('content', '')"><i class="fa-regular fa-floppy-disk"></i> <?php esc_html_e( 'Save', 'coming-soon-maintenance-mode' ); ?></button>
							</div>
						</div>
					</div><!-- content tab content end-->
					
					<div class="tab-pane fade" id="nav-social-media" role="tabpanel" aria-labelledby="nav-social-media-tab" tabindex="0">
						<div class="row">
							<div class="col-6 p-2 mt-3 border bg-light">
								<div id="csmm-website-mode-info" class="form-text">
									<?php esc_html_e( 'Add your social media account URLs in given social media section to display on website.', 'coming-soon-maintenance-mode' ); ?>
									<br><?php esc_html_e( 'You can keep it blank if you dont want to display.', 'coming-soon-maintenance-mode' ); ?>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon1"><i class="fa-brands fa-facebook-f"></i></span>
										<input type="text" class="form-control" id="csmm-sm-facebook" name="csmm-sm-facebook" value="<?php echo esc_attr($csmm_sm_facebook); ?>" placeholder="facebook profile / page / group url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon2"><i class="fa-brands fa-twitter"></i></span>
										<input type="text" class="form-control" id="csmm-sm-twitter" name="csmm-sm-twitter" value="<?php echo esc_attr($csmm_sm_twitter); ?>" placeholder="twitter profile url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-instagram"></i></span>
										<input type="text" class="form-control" id="csmm-sm-instagram" name="csmm-sm-instagram" value="<?php echo esc_attr($csmm_sm_instagram); ?>" placeholder="instagram profile / brand page url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank"><span class="badge text-bg-warning"><?php esc_html_e( 'More Social Media options are available in Pro', 'coming-soon-maintenance-mode' ); ?></span></a>
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon3"><i class="fa-brands fa-youtube"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-youtube" name="csmm-sm-youtube" value="" placeholder="youtube channel url">
									</div>
								</div>
								
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-linkedin-in"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-linkedin" name="csmm-sm-linkedin" value="" placeholder="linkedin profile / business page url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-pinterest"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-pinterest" name="csmm-sm-pinterest" value="" placeholder="pinterest profile / brand page url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-tumblr"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-tumblr" name="csmm-sm-tumblr" value="" placeholder="tumblr profile / page url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-snapchat"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-snapchat" name="csmm-sm-snapchat" value="" placeholder="snapchat profile url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-behance"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-behance" name="csmm-sm-behance" value="" placeholder="behance profile url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-dribbble"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-dribbble" name="csmm-sm-dribbble" value="" placeholder="dribbble profile url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-whatsapp"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-whatsapp" name="csmm-sm-whatsapp" value="" placeholder="whatsapp profile url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-tiktok"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-tiktok" name="csmm-sm-tiktok" value="" placeholder="tiktok profile url">
									</div>
								</div>
								<div>
									<div class="input-group p-2 mt-3">
										<span class="input-group-text" id="basic-addon4"><i class="fa-brands fa-qq"></i></span>
										<input disabled type="text" class="form-control" id="csmm-sm-qq" name="csmm-sm-qq" value="" placeholder="qq profile url">
									</div>
								</div>
								<div id="csmm-sm-saving" class="col-md-12 p-2 mt-3 spinner-grow text-dark d-none" role="status">
									<span class="visually-hidden"></span>
								</div>
								<div class="col-md-12 p-2 mt-3">
									<button type="button" id="csmm-save-sm" name="csmm-save-sm" class="btn btn-lg btn-outline-secondary" onclick="return csmm_save('social-media', '')"><i class="fa-regular fa-floppy-disk"></i> <?php esc_html_e( 'Save', 'coming-soon-maintenance-mode' ); ?></button>
								</div>
							</div>
						</div>
					</div><!-- social media tab content end-->
					
					<div class="tab-pane fade" id="nav-docs" role="tabpanel" aria-labelledby="nav-docs-tab" tabindex="0">
						<div class="row">
							<div class="col-6 p-2 mt-3 border">
								<div id="csmm-website-mode-info" class="form-text">
									<h5 class=""><?php esc_html_e( 'Have a problem with the plugin?', 'coming-soon-maintenance-mode' ); ?></h5>
									<?php esc_html_e( 'Raise a support ticket and we will respond you ASAP.', 'coming-soon-maintenance-mode' ); ?>
									<br><br><a href="https://wordpress.org/support/plugin/coming-soon-maintenance-mode/" target="_blank" class="btn btn-lg btn-outline-primary"><i class="fa-solid fa-ticket"></i> <?php esc_html_e( 'Create Ticket', 'coming-soon-maintenance-mode' ); ?></a>
								</div>
								<br>
								<div id="csmm-website-mode-info" class="form-text">
									<h5 class=""><?php esc_html_e( 'Visit & Subscribe Our YouTube Channel for More Tutorial Videos', 'coming-soon-maintenance-mode' ); ?></h5>
									<?php esc_html_e( 'In upcoming plugin updates you will get all video docs on our YouTube official website.', 'coming-soon-maintenance-mode' ); ?>
									<br><br><a href="https://www.youtube.com/channel/UCqbxQzbTEE2p3o33fKB5NIQ/" target="_blank" id="csmm-subscribe" name="csmm-subscribe" class="btn btn-lg btn-outline-danger"><i class="fa-brands fa-youtube"></i> <?php esc_html_e( 'Subscribe', 'coming-soon-maintenance-mode' ); ?></a>
								</div>
								<br>
								<div id="csmm-website-mode-info" class="form-text">
									<h5 class=""><?php esc_html_e( 'Upgrade To Pro', 'coming-soon-maintenance-mode' ); ?></h5>
									<?php esc_html_e( 'Upgrade the plugin to the Pro version to get more templates and features.', 'coming-soon-maintenance-mode' ); ?>
									<br><br><a href="https://webenvo.com/coming-soon-maintenance-mode-pro/" target="_blank" id="csmm-subscribe" name="csmm-subscribe" class="btn btn-lg btn-danger"><i class="fa-solid fa-cart-shopping"></i> <?php esc_html_e( 'Get Pro Plugin', 'coming-soon-maintenance-mode' ); ?></a>
								</div>
							</div>
						</div>
					</div><!-- social media tab content end-->
					
					<div class="tab-pane fade" id="nav-more" role="tabpanel" aria-labelledby="nav-more-tab" tabindex="0">
					</div><!-- more tab content end-->
					
					<div class="tab-pane fade" id="nav-docs" role="tabpanel" aria-labelledby="nav-docs-tab" tabindex="0">
					</div><!-- docs tab content end-->
				</div>
			</div>
		</div><!-- left section end-->
	</div><!-- end row-->
</div><!-- end container-->
</div>
<script>
function csmm_save(tab, id){
	
	// website-mode-top tab data post start
	if(tab == 'website-mode-top'){
		var website_mode = jQuery('input[name="website-mode-top"]:checked').val();
		var selected_posts = [];
		var selected_pages = [];
		var selected_other_pages = [];
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
			data: {
				'action': 'csmm_save', //this is the name of the AJAX method called in WordPress
				'tab': 'settings',
				'website_mode': website_mode,
				'selected_posts': selected_posts,
				'selected_pages': selected_pages,
				'selected_other_pages': selected_other_pages,
				'nonce': "<?php echo esc_js( wp_create_nonce( 'csmm-save' ) ); ?>",
			}, 
			success: function (response) {
				console.log('website-mode-top-saved');
			},
			error: function () {
				//alert("error");
			}
		});
	}
	// website-mode-top tab data post end
	
	// settings tab data post start
	if(tab == 'settings'){
		jQuery('button#csmm-save-setings').addClass('d-none');
		jQuery('div#csmm-setings-saving').removeClass('d-none');
		var website_mode = jQuery('input[name="website-mode"]:checked').val();
		var selected_posts = [];
		jQuery("input:checkbox[name=csmm_posts]:checked").each(function(){
			selected_posts.push(jQuery(this).val());
		});
		var selected_pages = [];
		jQuery("input:checkbox[name=csmm_pages]:checked").each(function(){
			selected_pages.push(jQuery(this).val());
		});
		var selected_other_pages = [];
		jQuery("input:checkbox[name=csmm_other_pages]:checked").each(function(){
			selected_other_pages.push(jQuery(this).val());
		});
		//console.log(selected_posts);
		//console.log(selected_pages);
		//console.log(selected_other_pages);
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
			data: {
				'action': 'csmm_save', //this is the name of the AJAX method called in WordPress
				'tab': tab,
				'website_mode': website_mode,
				'selected_posts': selected_posts,
				'selected_pages': selected_pages,
				'selected_other_pages': selected_other_pages,
				'nonce': "<?php echo esc_js( wp_create_nonce( 'csmm-save' ) ); ?>",
			}, 
			success: function (response) {
				//console.log(response);
				jQuery(function() {
					// it will wait for 5 sec. and then will fire
					setTimeout(function() {
						// hide processing icon and show button
						jQuery('div#csmm-setings-saving').addClass('d-none');
						jQuery('button#csmm-save-setings').removeClass('d-none');
						// shake live preview button
						jQuery( "#csmm-live-preview" ).effect("shake");
					}, 500);
				});
			},
			error: function () {
				//alert("error");
			}
		});
	}
	// settings tab data post end
	
	// templates tab data post start
	if(tab == 'templates'){
		var template_id = jQuery("#" + id).val();
		jQuery('button.cmss-templates').html('Activate'); // change all template button text to Activate
		jQuery('button.cmss-templates').removeClass('btn-primary'); // to remove previouly selected template button class
		jQuery('button.cmss-templates').addClass('btn-outline-primary'); // change all template button class to btn-outline-primary
		jQuery('button#' + id).removeClass('btn-outline-primary');
		jQuery('button#' + id).addClass('btn-primary');
		jQuery('button#' + id).html('Activated'); // change clicled template button text to Activated
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
			data: {
				'action': 'csmm_save', //this is the name of the AJAX method called in WordPress
				'tab': tab,
				'template_id': template_id,
				'nonce': "<?php echo esc_js( wp_create_nonce( 'csmm-save' ) ); ?>",
			}, 
			success: function (response) {
				//console.log(response);
				// shake live preview button
				jQuery( "#csmm-live-preview" ).effect("shake");
			},
			error: function () {
				//alert("error");
			}
		});
	}
	// templates tab data post end
	
	// content tab data post start
	if(tab == 'content'){
		jQuery('button#csmm-save-content').addClass('d-none');
		jQuery('div#csmm-content-saving').removeClass('d-none');
		var logo = jQuery("#csmm-logo-id").val();
		var title = jQuery("#csmm-title").val();
		var description = jQuery("#csmm-description").val();
		var countdown = jQuery('input[name="csmm-countdown"]:checked').val();
		var countdown_title = jQuery("#csmm-countdown-title").val();
		var countdown_date = jQuery("#csmm-countdown-date").val();
		var countdown_time = jQuery("#csmm-countdown-time").val();
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
			data: {
				'action': 'csmm_save', //this is the name of the AJAX method called in WordPress
				'tab': tab,
				'logo': logo,
				'title': title,
				'description': description,
				'countdown': countdown,
				'countdown_title': countdown_title,
				'countdown_date': countdown_date,
				'countdown_time': countdown_time,
				'nonce': "<?php echo esc_js( wp_create_nonce( 'csmm-save' ) ); ?>",
			}, 
			success: function (response) {
				jQuery(function() {
					// it will wait for 5 sec. and then will fire
					setTimeout(function() {
						// hide processing icon and show button
						jQuery('div#csmm-content-saving').addClass('d-none');
						jQuery('button#csmm-save-content').removeClass('d-none');
						// shake live preview button
						jQuery( "#csmm-live-preview" ).effect("shake");
					}, 500);
				});
			},
			error: function () {
				//alert("error");
			}
		});
	}
	// content tab data post end
	
	// social media tab data post start
	if(tab == 'social-media'){
		jQuery('button#csmm-save-sm').addClass('d-none');
		jQuery('div#csmm-sm-saving').removeClass('d-none');
		var csmm_sm_facebook = jQuery("#csmm-sm-facebook").val();
		var csmm_sm_twitter = jQuery("#csmm-sm-twitter").val();
		var csmm_sm_instagram = jQuery("#csmm-sm-instagram").val();
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
			data: {
				'action': 'csmm_save', //this is the name of the AJAX method called in WordPress
				'tab': tab,
				'csmm_sm_facebook': csmm_sm_facebook,
				'csmm_sm_twitter': csmm_sm_twitter,
				'csmm_sm_instagram': csmm_sm_instagram,
				'nonce': "<?php echo esc_js( wp_create_nonce( 'csmm-save' ) ); ?>",
			}, 
			success: function (response) {
				jQuery(function() {
					// it will wait for 5 sec. and then will fire
					setTimeout(function() {
						// hide processing icon and show button
						jQuery('div#csmm-sm-saving').addClass('d-none');
						jQuery('button#csmm-save-sm').removeClass('d-none');
						// shake live preview button
						jQuery( "#csmm-live-preview" ).effect("shake");
					}, 500);
				});
			},
			error: function () {
				//alert("error");
			}
		});
	}
	// social media tab data post end
	
	// remove logo start
	if(tab == 'remove-logo'){
		jQuery("li.csmm-logo-" + id).fadeOut(700, function() {
			jQuery("li.csmm-logo-" + id).remove();
		});
	}
	// remove logo end
	
}

jQuery(document).ready(function(){
	// select all posts
	jQuery("#check-all-posts").click(function(){
		jQuery(".csmm-post-list").prop('checked', jQuery(this).prop('checked'));
	});
	
	// select all pages
	jQuery("#check-all-pages").click(function(){
		jQuery(".csmm-page-list").prop('checked', jQuery(this).prop('checked'));
	});
	// select all other pages
	jQuery("#check-all-other-pages").click(function(){
		jQuery(".csmm-other-page-list").prop('checked', jQuery(this).prop('checked'));
	});
	
	//get active tab id start
	jQuery('button').click(function() {
		if(this.id == "nav-settings-tab") {
			jQuery("div.website-mode-top").addClass('d-none');
		}
		if(this.id == "nav-templates-tab" || this.id == "nav-content-tab" || this.id == "nav-social-media-tab") {
			jQuery("div.website-mode-top").removeClass('d-none');
		}
	});
	//get active tab id end
	
	//set website mode on top start
	// at top
	jQuery('input[name="website-mode-top"]').click(function() {
		var website_mode = jQuery('input[name="website-mode-top"]:checked').val();
		if(website_mode == 1) {
			jQuery("#website-mode1").prop('checked', true);
			jQuery("#maintenance-mode-options").fadeOut( "slow", function() {});
		}
		if(website_mode == 2) {
			jQuery("#website-mode2").prop('checked', true);
			jQuery("#maintenance-mode-options").fadeIn( "slow", function(){ });
		}
		if(website_mode == 3) {
			jQuery("#website-mode3").prop('checked', true);
			jQuery("#maintenance-mode-options").fadeOut( "slow", function() {});
		}
	});
	
	// in tab
	jQuery('input[name="website-mode"]').click(function() {
		var website_mode = jQuery('input[name="website-mode"]:checked').val();
		if(website_mode == 1) {
			jQuery("#website-mode-top1").prop('checked', true);
		}
		if(website_mode == 2) {
			jQuery("#website-mode-top2").prop('checked', true);
		}
		if(website_mode == 3) {
			jQuery("#website-mode-top3").prop('checked', true);
		}
		//hide and show maintenance-mode-options settings
		if(website_mode == 2){
			jQuery("#maintenance-mode-options").fadeIn( "slow", function() {});
		} else {
			jQuery("#maintenance-mode-options").fadeOut( "slow", function() {});
		}
	});
	//set website mode on top end

});
</script>