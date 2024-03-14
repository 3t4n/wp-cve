<?php

// no direct access!
defined('ABSPATH') or die("No direct access");

function wpgs_options_page() {

	global $wpgs_options;
	global $wpdb;
	global $wpgs_load_sh;
	global $sh_;
	
	global $default_bcp1,
			$default_cp1,
			$default_bca1,
			$default_ca1,
			$default_spop1,
			$default_spop1,
			$default_spoa1,
			$default_spoa1_,
			$default_animation_time_1,
			$default_speaker_type_1,
			$default_speaker_size_1,
			$default_tooltip_1,
			
			$default_bcp2,
			$default_cp2,
			$default_bca2,
			$default_ca2,
			$default_spop2,
			$default_spop2_,
			$default_spoa2,
			$default_spoa2_,
			$default_animation_time_2,
			$default_speaker_type_2,
			$default_speaker_size_2,
			$default_tooltip_2,
			
			$default_bcp3,
			$default_cp3,
			$default_bca3,
			$default_ca3,
			$default_spop3,
			$default_spop3_,
			$default_spoa3,
			$default_spoa3_,
			$default_animation_time_3,
			$default_speaker_type_3,
			$default_speaker_size_3,
			$default_tooltip_3,
			
			$default_bcp4,
			$default_cp4,
			$default_bca4,
			$default_ca4,
			$default_spop4,
			$default_spop4_,
			$default_spoa4,
			$default_spoa4_,
			$default_animation_time_4,
			$default_speaker_type_4,
			$default_speaker_size_4,
			$default_tooltip_4,
			
			$default_bcp5,
			$default_cp5,
			$default_bca5,
			$default_ca5,
			$default_spop5,
			$default_spop5_,
			$default_spoa5,
			$default_spoa5_,
			$default_animation_time_5,
			$default_speaker_type_5,
			$default_speaker_size_5,
			$default_tooltip_5;
	
			$tooltips = array("apple-green" => "Apple Green","apricot" => "Apricot","black" => "Black","bright-lavender" => "Bright Lavender","carrot-orange" => "Carrot Orange","dark-midnight-blue" => "Dark Midnight Blue","eggplant" => "Eggplant","forest-green" => "Forest Green","magic-mint" => "Magic Mint","mustard" => "Mustard","sienna" => "Sienna","sky-blue" => "Sky Blue");
			$speech_title = 'Click to listen highlighted text!';
	ob_start(); ?>
	<form method="post" action="options.php" id="gsp_form" class="submit_disabled">
		<div id="gsp_old_block"  class="wrap" style="overflow: hidden;margin-bottom: 10px; display: none;">
			<?php settings_fields('wpgs_settings_group'); ?>
		</div>

		<div class="gsp_dashboard_wrapper">

			<div class="gsp_dash_title"><img width="64px" src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/g_logo.png" /><span>GSpeech Dashboard</span><span class="gsp_v_i">(Version: <?php echo PLG_VERSION;?>)</span></div>

			<div id="gsp_token_inner" data-val="<?php echo isset($_SESSION["gsp_token_val"]) ? $_SESSION["gsp_token_val"] : ''; ?>" style="display: none;"></div>

			<div id="gsp_data">
				<?php
		        	$current_user = wp_get_current_user();
		        	
		        	$username =  $current_user->user_login;
		        	$useremail =  $current_user->user_email;
		        	$realname = $current_user->display_name;
		        	$userid = get_current_user_id();

		        	$sitename = get_bloginfo('name');

		        	$old_plugin_lang = isset($wpgs_options['language']) ? $wpgs_options['language'] : '';
		        	$old_plugin_speak_any_text = isset($wpgs_options['speak_any_text']) ? $wpgs_options['speak_any_text'] : 0;

		        	// get gspeech data
					$sql_g = "SELECT * FROM ".$wpdb->prefix."gspeech_data";
					$row_g = $wpdb->get_row($sql_g);
					$widget_id = esc_html($row_g->widget_id);
					$email_us = esc_html($row_g->email);

					$email_127 = $email_us == '' ? $useremail : $email_us;
				?>
				<div id="gsp_site_name"><?php echo $sitename; ?></div>
				<div id="gsp_username"><?php echo $username; ?></div>
				<div id="gsp_realname"><?php echo $realname; ?></div>
				<div id="gsp_useremail"><?php echo $email_127; ?></div>
				<div id="gsp_useremail_written"><?php echo $email_us; ?></div>
				<div id="gsp_userid"><?php echo $userid; ?></div>
				<div id="gsp_old_p_lang"><?php echo $old_plugin_lang; ?></div>
				<div id="gsp_old_p_speak_any_text"><?php echo $old_plugin_speak_any_text; ?></div>
				<div id="gsp_widget_id_val"><?php echo $widget_id; ?></div>
				<div id="gsp_load_shortcode_widgets"><?php echo $wpgs_load_sh; ?></div>
				<div id="gsp_sh_"><?php echo $sh_; ?></div>
			</div>

			<div id="gsp_tabs_wrapper">
				<div data-tab_ident="website_settings" class="gsp_tab gsp_tab_website_settings gsp_hidden"><div class="ss_top_menu_icon"></div><span>Cloud Console</span></div>
				<div data-tab_ident="video_demo" class="gsp_tab gsp_tab_video_demo gsp_tab_selected"><div class="ss_top_menu_icon"></div><span>Dashboard</span></div>
				<div data-tab_ident="sign_up" class="gsp_tab gsp_tab_sign_up"><div class="ss_top_menu_icon"></div><span>Activate</span></div>
				<div data-tab_ident="sign_in" class="gsp_tab gsp_tab_sign_in "><div class="ss_top_menu_icon"></div><span>Login</span></div>
				<div data-tab_ident="sign_out" class="gsp_tab gsp_tab_sign_out gsp_hidden"><div class="ss_top_menu_icon"></div><span>Logout</span></div>
				<div data-tab_ident="add_website" class="gsp_tab gsp_tab_add_website gsp_hidden"><div class="ss_top_menu_icon"></div><span>Activate</span></div>
				<a data-tab_ident="contact_us" class="gsp_tab gsp_tab_link gsp_tab_contact_us" href="https://gspeech.io/contact-us" target="_blank"><div class="ss_top_menu_icon"></div><span>Contact Us</span></a>
				<a data-tab_ident="rate_us" class="gsp_tab gsp_tab_link gsp_tab_rate_us" href="https://wordpress.org/plugins/gspeech/#reviews" target="_blank"><div class="ss_top_menu_icon"></div><span>Rate Us</span></a>
				<div data-tab_ident="old_basic" class="gsp_tab gsp_tab_old_basic"><div class="ss_top_menu_icon"></div><span>GSpeech 2.X</span></div>
				<div data-tab_ident="old_styles" class="gsp_tab gsp_tab_old_styles gsp_hidden"><div class="ss_top_menu_icon"></div><span>Styles</span></div>
			</div>

			<div class="gsp_tab_c gsp_tab_c_sign_in " style="display: none">

				<div class="gsp_login_wrapper gsp_login_wrapper_element">
					<div class="gsp_login_title">Welcome back!</div>
					<div class="gsp_login_subtitle">Enter your credentials to login</div>
					<input type="text" class="gsp_login_input gsp_login_email_uni" id="gsp_login_email" placeholder="Email" />
					<input type="password" class="gsp_login_input gsp_login_password_uni" id="" placeholder="Password" />
					<input type="text" class="gsp_login_input gsp_login_custom_widget gsp_hidden" id="" placeholder="Custom Widget" />
					<div class="gsp_login_button gsp_login_button_uni" id="">Login</div>
					<div class="gsp_input_forgot_wrapper"><a href="https://gspeech.io/forgot" target="_blank" class="gsp_forgot_link">Forgot Password</a></div>
					<div class="gsp_input_cw_wrapper"><span class="gsp_input_cw_val">Input custom widget</span></div>
				</div>
				
			</div>

			<div class="gsp_tab_c gsp_tab_c_video_demo gsp_tab_active">

				<?php include('tab_videos.php'); ?>
				
			</div>

			<div class="gsp_tab_c gsp_tab_c_sign_up" style="display: none">

				<div class="gsp_login_wrapper gsp_register_wrapper">
					<div class="gsp_login_title">Activate Cloud Console</div>
					<div class="gsp_login_subtitle">Just a single click is required :)</div>
					<input type="text" class="gsp_input" id="gsp_reg_name" placeholder="Name" />
					<input type="text" class="gsp_input" id="gsp_reg_email" placeholder="Email" />
					<input type="password" class="gsp_input" id="gsp_reg_password" placeholder="Password" />
					<input type="password" class="gsp_input" id="gsp_reg_password_retype" placeholder="Retype Password" />
					<div class="gsp_input_holder">
						<div id="reg_website_lang" data-val="" class="items_select_filter_wrapper" data-def_txt="Select language (for voices)">
							<div class="items_select_filter">
								<div class="items_select_filter_content">
									<span>Select language (for voices)</span>
									<input type="text" class="li_search_input" />
								</div>
								<div class="items_select_filter_icon_wrapper">
									<div class="items_select_filter_icon_holder">
										<div class="items_select_filter_icon_inner">
											<span class="items_select_filter_icon">
												<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
											</span>
										</div>
									</div>
								</div>
								<div class="items_select_ul_wrapper">
									<div class="items_select_ul_holder">
										<div class="items_select_ul_inner">
											<ul class="items_select_ul">
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="gsp_terms_holder">
						<span class="ss_checkbox_wrapper ss_checked" id="gsp_agree_terms">
							<span class="ss_checkbox_line1"></span>
							<span class="ss_checkbox_line2"></span>
							<span class="ss_checkbox_ripple"></span>
							<span class="ss_checkbox_bg"></span>
						</span>
						<span class="ss_checkbox_label">Agree to <a href="https://gspeech.io/terms" target="_blank" class="ss_label_link">terms</a>.</span>
					</div>
					<div class="gsp_login_button" id="gsp_reg_button">Activate</div>
				</div>
				
			</div>

			<div class="gsp_tab_c gsp_tab_c_add_website" style="display: none">

				<div class="gsp_login_wrapper gsp_add_website_wrapper">
					<div class="gsp_login_title">Activate Cloud Console</div>
					<div class="gsp_login_subtitle">Start your audio journey :)</div>
					<input type="hidden" class="gsp_input" id="gsp_add_w_title" placeholder="Title" />
					<input type="hidden" class="gsp_input" id="gsp_add_w_url" placeholder="Url" />
					<div class="gsp_input_holder">
						<div id="add_website_lang" data-val="" class="items_select_filter_wrapper" data-def_txt="Select language (for voices)">
							<div class="items_select_filter">
								<div class="items_select_filter_content">
									<span>Select language (for voices)</span>
									<input type="text" class="li_search_input" />
								</div>
								<div class="items_select_filter_icon_wrapper">
									<div class="items_select_filter_icon_holder">
										<div class="items_select_filter_icon_inner">
											<span class="items_select_filter_icon">
												<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
											</span>
										</div>
									</div>
								</div>
								<div class="items_select_ul_wrapper">
									<div class="items_select_ul_holder">
										<div class="items_select_ul_inner">
											<ul class="items_select_ul">
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="gsp_login_button" id="gsp_create_website_button">Activate</div>
				</div>
				
			</div>

			<div class="gsp_tab_c gsp_tab_c_sign_out" style="display: none">

				<div class="gsp_sign_out_button_wrapper">
					<div class="gsp_login_button" id="gsp_sign_out_button">Logout</div>
				</div>
				
			</div>

			<div class="gsp_tab_c gsp_tab_c_website_settings" style="display: none">

				<div class="gsp_dash_col_1">

					<div data-tab_ident="settings" class="gsp_left_menu gsp_left_menu_settings gsp_left_m_selected">Settings</div>
					<div data-tab_ident="widgets" class="gsp_left_menu gsp_left_menu_widgets">Widgets</div>
					<div data-tab_ident="audios" class="gsp_left_menu gsp_left_menu_audios">Audios</div>
					<div data-tab_ident="analytics" class="gsp_left_menu gsp_left_menu_analytics">Analytics</div>
					<div data-tab_ident="" class="gsp_left_menu_dummy"><a class="gsp_left_link" href="https://gspeech.io/docs" target="_blank">Docs and guides</a></div>
					<div data-tab_ident="" class="gsp_left_menu_dummy"><a class="gsp_left_link" href="https://gspeech.io/contact-us" target="_blank">Contact Us</a></div>
					
				</div>
				<div class="gsp_dash_col_2">
					
					<div class="gsp_left_m_c gsp_left_m_c_settings gsp_left_m_c_active">
						<?php include('tab_website_settings.php'); ?>
					</div>
					<div class="gsp_left_m_c gsp_left_m_c_widgets">
						<?php include('tab_widgets.php'); ?>
					</div>
					<div class="gsp_left_m_c gsp_left_m_c_widget">
						<?php include('tab_widget.php'); ?>
					</div>
					<div class="gsp_left_m_c gsp_left_m_c_audios">
						<?php include('tab_audios.php'); ?>
					</div>
					<div class="gsp_left_m_c gsp_left_m_c_audio">
						<?php include('tab_audio.php'); ?>
					</div>
					<div class="gsp_left_m_c gsp_left_m_c_analytics">
						<?php include('tab_analytics.php'); ?>
					</div>
				</div>
				
			</div>

			<div class="gsp_tab_c gsp_tab_c_old_basic" style="display: none">
				<?php include('tab1.php');?>
			</div>

			<div class="gsp_tab_c gsp_tab_c_old_styles" style="display: none">
		  		<?php include('tab2.php');?>
			</div>
			
			<div class="old_p" class="submit">
				<input type="submit" class="gsp_login_button gsp_submit_button gsp_hidden" value="<?php _e('Save', 'wpgs_domain'); ?>" />
			</div>

		</div>
	</form>
	<?php
	echo ob_get_clean();
}

function wpgs_add_options_link() {
	$icon_url=plugins_url( '/images/g_logo_small.png' , __FILE__ );
	$page = add_menu_page('GSpeech Plugin Options', 'GSpeech', 'manage_options', 'wpgs-options', 'wpgs_options_page',$icon_url);
	add_action('admin_print_scripts-' . $page, 'wpgs_load_admin_scripts');
}

function wpgs_register_settings() {
	// creates our settings in the options table
	register_setting('wpgs_settings_group', 'wpgs_settings');
}

function wpgs_load_admin_scripts() {

	global $plugin_version;

	wp_enqueue_style('wpgs-styles9', plugin_dir_url( __FILE__ ) . 'css/ui-lightness/jquery-ui-1.10.1.custom.css' . '?g_version=' . $plugin_version);
	wp_enqueue_style('wpgs-styles10', plugin_dir_url( __FILE__ ) . 'css/admin.css' . '?g_version=' . $plugin_version);
	wp_enqueue_style('wpgs-styles11', plugin_dir_url( __FILE__ ) . 'css/colorpicker.css' . '?g_version=' . $plugin_version);
	wp_enqueue_style('wpgs-styles12', plugin_dir_url( __FILE__ ) . 'css/layout.css' . '?g_version=' . $plugin_version);
	wp_enqueue_style('wpgs-styles13', plugin_dir_url( __FILE__ ) . 'css/the-tooltip.css' . '?g_version=' . $plugin_version);
	
	wp_enqueue_script('wpgs-script14', plugin_dir_url( __FILE__ ) . 'js/colorpicker.js', array('jquery'));
	wp_enqueue_script('wpgs-script12', plugin_dir_url( __FILE__ ) . 'js/eye.js', array('jquery'));
	wp_enqueue_script('wpgs-script13', plugin_dir_url( __FILE__ ) . 'js/utils.js', array('jquery'));
	wp_enqueue_script('wpgs-script16', plugin_dir_url( __FILE__ ) . 'js/highstock.js', array('jquery'));
	wp_enqueue_script('wpgs-script15', plugin_dir_url( __FILE__ ) . 'js/admin.js'  . '?g_version=' . $plugin_version, array('jquery','jquery-ui-core','jquery-ui-accordion','jquery-ui-tabs','jquery-ui-slider'));
}

add_action('admin_menu', 'wpgs_add_options_link');
add_action('admin_init', 'wpgs_register_settings');