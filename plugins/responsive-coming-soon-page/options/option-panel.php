<?php
/**
 * Coming Soon Admin Menu
 */
add_action('admin_menu', 'rcsm_admin_menu_pannel');
function rcsm_admin_menu_pannel() {
	$page = add_menu_page(RCSM_PLUGIN_NAME, esc_html__('Responsive Coming Soon Page', RCSM_TEXT_DOMAIN), 'manage_options','rcsm-weblizar', 'rcsm_option_panal_function', 'dashicons-backup', 65);
 	add_action('admin_print_styles-'.$page, 'rcsm_admin_enqueue_script'); // add_action function for adding the js and css files
	add_action('admin_bar_menu','show_rcsm_mode_link',99999); // add_action function for adding the notice link on dashboard top menubar
	add_action('admin_footer','rcsm_style_sheet_function'); //  add_action function for adding the custom css on footer area
}
function rcsm_truncateString($str, $chars, $to_space, $replacement="...") {
	if($chars > strlen($str)) return $str;
		$str 	   = substr($str, 0, $chars);
		$space_pos = strrpos($str, " ");
		if($to_space && $space_pos >= 0) {
		$str = substr($str, 0, strrpos($str, " "));
	}
	return($str . $replacement);
}

function rcsm_limit_words($string, $word_limit, $replacement){
    $words 	  = explode(" ",$string);
    $returns  = implode(" ",array_splice($words,0,$word_limit));
	return($returns . $replacement);
}

function rcsm_style_sheet_function() {  // for custom css
	$wl_rcsm_options = weblizar_rcsm_get_options();
}

// for show_admin_link of plugin
function show_rcsm_mode_link($wp_admin_bar) {
	$wl_rcsm_options = weblizar_rcsm_get_options();
	if ($wl_rcsm_options['layout_status']=='coming_soon_switch' ) {
		$args = array(
			'id' 	=> 'rcsm-coming-soon',
			'title' => 'Coming Soon Mode Active',
			'href'  => get_admin_url().'admin.php?page=rcsm-weblizar',
			'meta'  => array(
				'class' => 'weblizar_wp_admin_plugin rcsm_plugin rcsm-coming-soon',
				'title' => 'Coming Soon Mode Active'
			)
		);
		$wp_admin_bar->add_node($args);
	} elseif ($wl_rcsm_options['layout_status']=='service_unavailable_switch' ) {
		$args = array(
			'id'    => 'rcsm-service-unavailable',
			'title' => 'Site 503 Service Unavailable Active',
			'href'  => get_admin_url().'admin.php?page=rcsm-weblizar',
			'meta'  => array(
				'class' => 'weblizar_wp_admin_plugin rcsm_plugin rcsm-service-unavailable-link',
				'title' => 'Site 503 Service Unavailable Active'
			)
		);
		$wp_admin_bar->add_node($args);
	}
}

// for show_notice_bar of plugin
function rcsm_show_admin_notices() {
	$wl_rcsm_options = weblizar_rcsm_get_options();
	if ($wl_rcsm_options['layout_status']=='coming_soon_switch') { ?>
		<style>
		.weblizar_wp_admin_plugin{
			margin-left: 5px!important;
		}

		.weblizar_notice_bar.p_activate {
			background-color: #333;
			color: #fff;
			height: auto !important;
			margin: 10px 0 !important;
			min-height: 50px !important;
		}

		.weblizar_notice_bar.p_activate .plugin_title,
		.weblizar_notice_bar.p_activate .plugin_msg{
		display:inline-block !important;
		font-size: 16px;
		}

		.weblizar_notice_bar.p_activate .plugin_title{
		font-size: 18px;
		font-weight:bold;
		}

		.p_activate .notice-dismiss {
			top: 10px;
			right: 10px;
			padding:5px !important;
			background-color: red;
		}

		.weblizar_wp_admin_plugin a:hover {
			background-color: #0098FF!important;
			color: #ffffff!important;
			border-radius: 4px !important;
		}

		.weblizar_wp_admin_plugin a {
			background-color: red!important;
			color: #ffffff!important;
			border-radius: 4px !important;
		}

		.weblizar_notice_bar {
			min-height: 50px;
			margin: 10px;
			display: block;
			background-color: #666;
			color: #fff;
			padding-top: 10px;
			z-index:1;
		}

		.weblizar_notice_bar .plugin_title {
			margin: 0px !important;
			padding: 5px 0px !important;
			font-size: 16px !important;
			font-weight: bold !important;
			height: auto;
			float: left;
			line-height: initial;
		}

		.weblizar_notice_bar .plugin_msg {
			margin: 0px !important;
			padding: 8px 10px !important;
			font-size: 14px !important;
			font-weight: normal !important;
			height: auto;
			float: left;
			line-height: initial;
		}

		.notice-dismiss {
			top: 10px;
			right: 10px;
			padding: 5px;
			background-color: #ff3333;
		}
		</style>
		<div class="weblizar_notice_bar weblizar-rcsm_notice_bar notice notice-success is-dismissible p_activate">
			<p class="navbar-brand plugin_title"><?php esc_html_e('Responsive Coming Soon Page : ','RCSM_TEXT_DOMAIN');?> </p>
			<span class="navbar-brand plugin_msg"><?php esc_html_e('Site Coming Soon Mode is Active','RCSM_TEXT_DOMAIN');?> </span>
		</div>
		<?php
	} elseif ($wl_rcsm_options['layout_status']=='service_unavailable_switch') { ?>
		<div class="weblizar_notice_bar weblizar-rcsm_notice_bar notice notice-success is-dismissible p_activate">
			<p class="navbar-brand plugin_title"><?php esc_html_e('Responsive Coming Soon Page : ','RCSM_TEXT_DOMAIN');?> </p>
			<span class="navbar-brand plugin_msg"><?php esc_html_e('Site 503 - Service unavailable Mode is Active','RCSM_TEXT_DOMAIN');?> </span>
		</div>
		<?php
	}

}

/**
 * Weblizar Admin Menu CSS
 */
// for Adding css and js files of plugin
function rcsm_admin_enqueue_script() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script('popper', RCSM_PLUGIN_URL. 'options/js/popper.min.js');
	wp_enqueue_script('weblizar-tab-js', RCSM_PLUGIN_URL.'options/js/option-js.js',array('media-upload', 'jquery-ui-sortable','wp-color-picker'));
	wp_enqueue_script('weblizar-bt-toggle', RCSM_PLUGIN_URL.'options/js/bt-toggle.js');
	wp_enqueue_script('bootstrap', RCSM_PLUGIN_URL. 'options/js/bootstrap.min.js');
	wp_enqueue_script('dataTables', RCSM_PLUGIN_URL. 'options/js/jquery.dataTables.js');
	wp_enqueue_script('multiselectjs', RCSM_PLUGIN_URL. 'options/js/jquery.multiselect.js');
	wp_enqueue_script('jquery.datetimepicker.full', RCSM_PLUGIN_URL. 'options/js/jquery.datetimepicker.full.min.js');
	wp_enqueue_style('thickbox');
	wp_enqueue_style('weblizar-option-style', RCSM_PLUGIN_URL.'options/css/option-style.css');
	wp_enqueue_style('bootstrap', RCSM_PLUGIN_URL. 'options/css/bootstrap.min.css');
	wp_enqueue_style('font-awesome', RCSM_PLUGIN_URL. 'options/css/all.min.css');
	wp_enqueue_style('optionpanal-dragdrop', RCSM_PLUGIN_URL.'options/css/weblizar-dragdrop.css');
	wp_enqueue_style('datetimepicker', RCSM_PLUGIN_URL.'options/css/jquery.datetimepicker.css');
	wp_enqueue_style('recom', RCSM_PLUGIN_URL.'options/css/recom.css');
}

/**
 * Weblizar Plugin Option Form
 */
function rcsm_option_panal_function() { ?>
	<div class="msg-overlay">
		<div class="success-msg">
			<div class="alert alert-success">
				<strong><?php esc_html_e('Success!','RCSM_TEXT_DOMAIN');?></strong> <?php esc_html_e('Data Save Successfully.','RCSM_TEXT_DOMAIN');?>
			</div>
		</div>
		<div class="reset-msg">
			<div class="alert alert-danger">
				<strong><?php esc_html_e('Success!','RCSM_TEXT_DOMAIN');?></strong> <?php esc_html_e('Data Reset Successfully.','RCSM_TEXT_DOMAIN');?>
			</div>
		</div>
		<div class="remove-msg">
			<div class="alert alert-info">
				<strong><?php esc_html_e('Success!','RCSM_TEXT_DOMAIN');?></strong> <?php esc_html_e('Selected Data Remove Successfully.','RCSM_TEXT_DOMAIN');?>
			</div>
		</div>
		<div class="deleted-msg">
			<div class="alert alert-info">
				<strong><?php esc_html_e('Success!','RCSM_TEXT_DOMAIN');?></strong> <?php esc_html_e('All Data Removed Successfully.','RCSM_TEXT_DOMAIN');?>
			</div>
		</div>
		<div class="send_mail-msg">
			<div class="alert alert-success">
				<strong><?php esc_html_e('Success!','RCSM_TEXT_DOMAIN');?></strong> <?php esc_html_e('Mail sent Successfully.','RCSM_TEXT_DOMAIN');?>
			</div>
		</div>
	</div>

	<header>
		<div class="row">
			<div class="col-md-12">
				<div class="container-fluid top">
					<div class="row">
						<div class="col-md-8 col-sm-8">
							<h2 class="rcinline"><img src="<?php echo RCSM_PLUGIN_URL ?>options/images/logo.png" alt="Weblizar" />
							<span><?php esc_html_e('Responsive Coming Soon Page','RCSM_TEXT_DOMAIN');?></span></h2>
						</div>
						<div class="col-md-4 col-sm-4 search1" >
							<a href="<?php echo esc_url('https://wordpress.org/support/plugin/responsive-coming-soon-page'); ?>" target="_blank"><span class="fa fa-comment-o"></span><?php esc_html_e(' Support Forum','RCSM_TEXT_DOMAIN');?></a>
							<a href="<?php echo esc_url('https://weblizar.com/documentation/plugins/coming-soon-page-maintenance-mode-pro/'); ?>" target="_blank"><span class="fa fa-pencil-square-o"></span> <?php esc_html_e(' View Documentation','RCSM_TEXT_DOMAIN');?></a>
						</div>
					</div>							
				</div>
			</div>		
		</div>		
	</header>
	

	<div class="row">
		<div class="container-fluid support">
			<div class="row left-sidebar">
				<div class="col-md-12 menu">
					<!-- tabs left -->
					<div class="col-xs-12 tabbable tabs-left">
						<ul class="col-xs-4 nav nav-tabs collapsible collapsible-accordion">
							<?php 
								// get option settings from saved database 
								$wl_rcsm_options = get_option('weblizar_rcsm_options'); 							
							?>
							<li class="active" data-toggle="" data-placement="right" title="<?php esc_attr_e('Templates Option','RCSM_TEXT_DOMAIN');?>">
								<a href="#templates-option" data-toggle="tab">
									<i class="fa fa-desktop icon"></i><?php esc_html_e('Templates Option','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
							<li data-toggle="" data-placement="right" title="<?php esc_attr_e('General Settings','RCSM_TEXT_DOMAIN');?>">
								<a href="#general-settings" data-toggle="tab">
									<i class="fa fa-cog icon"></i><?php esc_html_e('General Settings','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
							<li data-toggle="" data-placement="right" title="<?php esc_attr_e('Layout Options','RCSM_TEXT_DOMAIN');?>">
								<a href="#layout-settings" data-toggle="tab">
									<i class="fa fa-paint-brush icon"></i><?php esc_html_e(' Layout Options','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
							<li data-toggle="" data-placement="right" title="<?php esc_attr_e('Social Media Options','RCSM_TEXT_DOMAIN');?>">
								<a href="#social" data-toggle="tab">
									<i class="fab fa-twitter icon"></i><?php esc_html_e('Social Media Options','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
							<li data-toggle="" data-placement="right" title="<?php esc_attr_e('Subscriber Options','RCSM_TEXT_DOMAIN');?>">
								<a href="#subscriber" data-toggle="tab">
									<i class="far fa-envelope-open icon"></i><?php esc_html_e('Subscriber Options','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
							<li data-toggle="" data-placement="right" title="<?php esc_attr_e('Counter clock Options','RCSM_TEXT_DOMAIN');?>">
								<a href="#counter-clock" data-toggle="tab">
									<i class="far fa-clock icon"></i><?php esc_html_e('Counter clock Options','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
							<li data-toggle="" data-placement="right" title="<?php esc_attr_e('Footer Options','RCSM_TEXT_DOMAIN');?>">
								<a href="#footer" data-toggle="tab">
									<i class="fa fa-credit-card icon"></i><?php esc_html_e('Footer Options','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
							<li data-toggle="" data-placement="right" title="<?php esc_attr_e('Advance Options','RCSM_TEXT_DOMAIN');?>">
								<a href="#advance" data-toggle="tab">
									<i class="fas fa-sliders-h icon"></i><?php esc_html_e('Advance Options','RCSM_TEXT_DOMAIN');?>
								</a>								
							</li>
							<li class="get-pro" data-toggle="" data-placement="right" title="<?php esc_attr_e('Try Pro Version','RCSM_TEXT_DOMAIN');?>">
								<a href="<?php echo esc_url('https://weblizar.com/plugins/coming-soon-page-maintenance-mode-pro/'); ?>" target="_blank">
									<?php esc_html_e('Get Premium Version','RCSM_TEXT_DOMAIN');?>
								</a>
							</li>
						</ul>
						<!-- Option Data saving  -->
						<?php require_once('option-data.php'); ?>

						<!-- Option Settings form  -->
						<?php require_once('option-settings.php'); ?>

						<a class="back-to-top back-top" href="#" style="display: inline;"><i class="fa fa-angle-up"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

// Restore all defaults
if(isset($_POST['restore_all_defaults'])) {
	$wl_rcsm_options = weblizar_rcsm_default_settings();
	update_option('weblizar_rcsm_options', $wl_rcsm_options);
}

//maintenance enable function 'template redirect'
function rcsm_maintenance_template_redirect() {
	$wl_rcsm_options = get_option('weblizar_rcsm_options'); // get option settings from saved database
	$preview ="yes";
	$live_page = home_url("/?rcsm_live_preview=yes");
	//compare condition to show the Coming soon page
	if((!empty($live_page)) && isset($wl_rcsm_options['layout_status']) && ($wl_rcsm_options['layout_status'] == 'coming_soon_switch')) {

		if (isset($_GET['rcsm_live_preview'])) {
			if($_GET['rcsm_live_preview']=="yes") {
				include 'themes/index.php';
				exit();
			}
		}

	} elseif( isset( $wl_rcsm_options['layout_status'] ) && $wl_rcsm_options['layout_status']=='service_unavailable_switch') {
		// If condition is true : site is going to 503 service unavailable mode
		if (isset($_GET['rcsm_live_preview'])) {
			if($_GET['rcsm_live_preview']=="yes") {
				$site_url_link = get_site_url();
				wp_redirect($site_url_link,503);
				exit();
			}
		}
	}

	$wl_rcsm_options 		 = get_option('weblizar_rcsm_options'); // get option settings from saved database
	$get_counter_time 		 = isset( $wl_rcsm_options['maintenance_date'] ) ? $wl_rcsm_options['maintenance_date']."</br>" : '';
	$current_timestamp 		 = date("Y/m/d H:i", strtotime("5 hours 30 mins"))."</br>";
	$current_status 		 = isset($wl_rcsm_options['layout_status']) ? $wl_rcsm_options['layout_status'] : '';
	$disable_the_plugin 	 = isset($wl_rcsm_options['disable_the_plugin']) ? $wl_rcsm_options['disable_the_plugin'] : '';
	$auto_sentto_activeusers = isset($wl_rcsm_options['auto_sentto_activeusers']) ? $wl_rcsm_options['auto_sentto_activeusers'] : '';
	if(isset($disable_the_plugin)){
	  if($disable_the_plugin == 'on')
	   {
		if($wl_rcsm_options['layout_status']!='deactivate' && $get_counter_time > $current_timestamp){
			$current_status = $wl_rcsm_options['layout_status'];
		}
		elseif($wl_rcsm_options['layout_status']!='deactivate' && $get_counter_time <= $current_timestamp){
			$wl_rcsm_options = get_option('weblizar_rcsm_options');
			$wl_rcsm_options['layout_status']= 'deactivate';
			update_option('weblizar_rcsm_options', $wl_rcsm_options);
			if(isset($auto_sentto_activeusers)){
			  if($auto_sentto_activeusers == 'on')
			   {
				global $wpdb;
				$table_name = $table_name = $wpdb->prefix . "rcsm_subscribers";
				$email_check = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag = '1'" );
					if($email_check){
						foreach($email_check as $all_emails){
							$subscriber_email = $all_emails->email;
							$f_name = $all_emails->f_name;
							$l_name = $all_emails->l_name;
							$flag_act = $all_emails->flag;
							$current_time = current_time( 'Y-m-d h:i:s' );
							$adminemail = $wl_rcsm_options['wp_mail_email_id'];
							$site_url = site_url();
							$headers = 'Content-type: text/html'."\r\n"."From:$site_url <$adminemail>"."\r\n".'Reply-To: '.$adminemail . "\r\n".'X-Mailer: PHP/' . phpversion();
							$subject = 'Site Live Notification ';
							$message = 'Hi '.$f_name.' '.$l_name.', <br/>';
							$message .= '<p>Our Site is lived.</p><br/><p>Now, You can visit on our site URL : ' .$site_url.'</p><br><p>Regards</p><p><a href="'.$plugin_site_url.'">'.$wl_rcsm_options['page_meta_title'].'</a></p>';
							$wp_mails = wp_mail( $subscriber_email, $subject, $message, $headers);
							if($wp_mails){
								$user_search_result = $wpdb->get_row("SELECT * FROM `$table_name` WHERE `email` LIKE '$subscriber_email' AND `flag` LIKE '$flag_act'");
								if(count($user_search_result)) {
									// check user is already subscribed
									if($user_search_result->flag != 2) {
										$wpdb->query("UPDATE `$table_name` SET `flag` = '2' WHERE `email` = '$subscriber_email'");
									}
								}
							}
						}
					if($wp_mails){
						$wl_rcsm_options = get_option('weblizar_rcsm_options');
						$wl_rcsm_options['layout_status']= 'deactivate';
						update_option('weblizar_rcsm_options', $wl_rcsm_options);
					}

				}
			   }
			}
		}
	   }
	}
	 // compare condition to show the Coming soon page
	if($current_status == 'coming_soon_switch') {

		//if user not login page is redirect on coming soon template page
		if (!is_user_logged_in()) {
			include 'themes/index.php';
			exit();
		}

		$user_values_as = $wl_rcsm_options['user_value'];

		if (is_array($user_values_as)) {
			foreach($user_values_as as $value_users) {
				//if user is log-in then we check role.
				if (is_user_logged_in() ) {
					//get logined in user role
					global $current_user;
					wp_get_current_user();
					$LoggedInUserID = $current_user->ID;
					$UserData = get_userdata( $LoggedInUserID );

					//Compare condition to get ture value of home or front page of site ( First get site has home page or front page )
					//	(and then Compare : saved user id with login user id )
					//  ( or Compare  : saved user role with login user role )
					$is_fornt_page_condition = $LoggedInUserID == $value_users || $UserData->roles[0] == strtolower($value_users) && is_front_page();
					$is_home_page_condition = $LoggedInUserID == $value_users || $UserData->roles[0] == strtolower($value_users) && is_home();

					//Get page and post id if selected from backend and compare condition to redirect or not on message page template
					if(!empty($is_fornt_page_condition) || !empty($is_home_page_condition)) {
						include 'themes/index.php';
						exit();
					}
				}
			}

		}
	} elseif($current_status=='service_unavailable_switch') {

		// If condition is true : site is going to 503 service unavailable mode
		if(!is_home() || is_home()) {
			//if user not login page is redirect on 503 service unavailable mode
			if ( !is_user_logged_in() ) {
				$site_url_link = get_site_url();
				wp_redirect($site_url_link,503);
				exit();
			}
		}
		$user_values_as = $wl_rcsm_options['user_value'];
		if (is_array($user_values_as)) {
			foreach($user_values_as as $value_users) {
				//if user is log-in then we check role.
				if (is_user_logged_in()) {
					//get loged-in user role
					global $current_user;
					wp_get_current_user();
					$LoggedInUserID = $current_user->ID;
					$UserData = get_userdata( $LoggedInUserID );

					// if user role not 'administrator' redirect him to a defined URL page
					// Compare condition to get ture value of home or front page of site ( First get site has home page or front page )
					// (and then Compare : saved user id with login user id )
					// ( or Compare  : saved user role with login user role )
					$is_fornt_page_condition = $LoggedInUserID == $value_users || $UserData->roles[0] == strtolower($value_users) && is_front_page();
					$is_home_page_condition = $LoggedInUserID == $value_users || $UserData->roles[0] == strtolower($value_users) && is_home();

					//Get page and post id if selected from backend and compare condition to redirect or not on message page template
					if($is_fornt_page_condition || $is_home_page_condition) {
						if(!is_home() || is_home()) {
							$site_url_link = get_site_url();
							wp_redirect($site_url_link,503);
							exit();
						}
					}
				}
			}
		}
    }
}
//add action to call function maintenance_template_redirect
add_action( 'template_redirect', 'rcsm_maintenance_template_redirect' );
