<?php
/* Begin Add Card in Admin Panel */
add_action('insert_ads_plugin_card', 'insert_ads_vi_plugin_card', 5);
function insert_ads_vi_plugin_card() {
	echo '<div class="plugin-card vi-card">';
			if(insert_ads_vi_api_is_loggedin()) {
				insert_ads_vi_plugin_card_content(true);				
			} else {
				insert_ads_vi_plugin_card_content(false);
			}
	echo '</div>';
}

function insert_ads_vi_plugin_card_content($isLoggedin = false, $isAjaxRequest = false) {
	if(!$isLoggedin) {
		echo '<div class="plugin-card-top">';
			echo '<div class="plugin-card-top-header">';
				echo '<h4>Start earning with vi stories</h4>';
			echo '</div>';
			echo '<div class="plugin-card-top-content" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
				echo '<p>Advertisers pay more for video advertising when it’s matched with video content. With vi stories you’ll see video content that is matched to your sites keywords straight away. It increases time on site, and commands a higher CPM than display advertising. A few days after activation you’ll begin to receive revenue from advertising served before this video content.</p>';	
				echo '<ul>';
					echo '<li>The set up takes only a few minutes</li>';
					echo '<li>Up to 10x higher CPM than traditional display advertising</li>';
					echo '<li>Users spend longer on your site thanks to professional video content</li>';
					echo '<li>The video player is customizable to match your site</li>';
				echo '</ul>';
				echo '<p>Sign up now to increase time-on-page, and your revenue thanks to high CPMs.</p>';
			echo '</div>';
		echo '</div>';
		echo '<div class="plugin-card-bottom" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
			echo '<span>By clicking sign up you agree to send your current domain, email and affiliate ID to video intelligence & Insert Post Ads.</span>';
			echo '<a id="insert_ads_vi_login" href="javascript:;" class="button button-secondary">Log In</a>';
			echo '<a id="insert_ads_vi_signup" href="javascript:;" class="button button-primary">Sign Up</a>';
		echo '</div>';
		echo '<input type="hidden" id="insert_ads_admin_ajax" name="insert_ads_admin_ajax" value="' . admin_url('admin-ajax.php') . '" /><input type="hidden" id="insert_ads_nonce" name="insert_ads_nonce" value="' . wp_create_nonce('insert-ads') . '" />';
	} else {
		$dashboardURL = insert_ads_vi_api_get_dashboardurl();
		echo '<div class="plugin-card-top">';
			echo '<div class="plugin-card-top-header">';
				echo '<h4>Monetization with vi stories</h4>';
			echo '</div>';
			echo '<div class="plugin-card-top-content" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
				echo '<p>Below you can see your current revenues. <span class="pl-right">Don’t see anything? Consult the <a target="_blank" href="https://www.vi.ai/frequently-asked-questions-vi-stories-for-wordpress/?utm_source=WordPress&utm_medium=Plugin%20FAQ&utm_campaign=WP%20insertpostads">FAQs</a>.</span></p>';
				echo '<div id="insert_ads_vi_earnings_wrapper">';
					echo '<div class="insert_ads_ajaxloader"></div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="plugin-card-bottom" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
		echo '<input type="hidden" id="insert_ads_admin_ajax" name="insert_ads_admin_ajax" value="' . admin_url('admin-ajax.php') . '" /><input type="hidden" id="insert_ads_nonce" name="insert_ads_nonce" value="' . wp_create_nonce('insert-ads') . '" />';
			echo '<a id="insert_ads_vi_dashboard" href="'.$dashboardURL.'" target="_blank" class="button button-primary alignleft">Publisher Dashboard</a>';
			//echo '<a id="insert_ads_vi_customize_adcode" href="javascript:;" class="button button-primary alignleft">Configure vi code</a>';
			echo '<a id="insert_ads_vi_logout" href="javascript:;" class="button button-secondary">Log Out</a>';					
		echo '</div>';
	}
}

add_action('wp_ajax_insert_ads_vi_get_chart', 'insert_ads_vi_get_chart');
function insert_ads_vi_get_chart() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	$revenueData = insert_ads_vi_api_get_revenue_data();
	if(isset($revenueData) && is_array($revenueData)) {
		echo '###SUCCESS###';
		echo '<div id="insert_ads_vi_earnings">';
			echo '<span id="insert_ads_vi_earnings_label">Total Earnings</span>';
			echo '<span id="insert_ads_vi_earnings_value">$'.$revenueData['netRevenue'].'</span>';
		echo '</div>';
		echo '<div id="insert_ads_vi_chart_wrapper">';
			echo '<canvas id="insert_ads_vi_chart" width="348" height="139"></canvas>';
			echo '<textarea id="insert_ads_vi_chart_data" style="display: none;">[';
			if(isset($revenueData['mtdReport']) && is_array($revenueData['mtdReport']) & (count($revenueData['mtdReport']) > 0)) {
				$isFirstItem = true;
				foreach($revenueData['mtdReport'] as $reportData) {
					if(!$isFirstItem) {
						echo ',';
					}
					$date = DateTime::createFromFormat('d-m-Y', $reportData['date']);
					echo '{"x": "'.$date->format('m/d/Y').'", "y": "'.$reportData['revenue'].'"}';
					$isFirstItem = false;;
				}
			} else {
				echo '{"x": "'.date('m/d/Y').'", "y": "0.00"}';
			}				
			echo ']</textarea>';
		echo '</div>';
		echo '<div class="clear"></div>';
	} else {
		echo '<p class="viError">There was an error processing your request, our team was notified.<br />Please try again later.</p>';
		echo '<div id="insert_ads_vi_earnings_wrapper">';
			echo '<div id="insert_ads_vi_earnings">';
				echo '<span id="insert_ads_vi_earnings_label">Total Earnings</span>';
				echo '<span id="insert_ads_vi_earnings_value"><img src="'.WP_INSADS_URL.'images/vi-no-data.jpg?'.WP_INSADS_VERSION.'"></span>';
			echo '</div>';
			echo '<div id="insert_ads_vi_chart_wrapper">';
				echo '<img width="348" height="139" src="'.WP_INSADS_URL.'images/vi-empty-graph.jpg?'.WP_INSADS_VERSION.'">';
			echo '</div>';
			echo '<div class="clear"></div>';
		echo '</div>';
	}
	die();
}
/* End Add Card in Admin Panel */

/* Begin Signup Form */
add_action('wp_ajax_insert_ads_vi_signup_form_get_content', 'insert_ads_vi_signup_form_get_content');
function insert_ads_vi_signup_form_get_content() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	$signupURL = insert_ads_vi_api_get_signupurl();
	if(true) {
		echo '<div class="insert_ads_popup_content_wrapper">';	
			echo '<iframe src="https://www.vi.ai/publisher-registration/?email='.get_bloginfo('admin_email').'&domain='.get_bloginfo('url').'&aid=WP_insertpostads&utm_source=Wordpress&utm_medium=wp_insertpostads&utm_campaign=white&utm_content=Wp_insertpostads" style="width: 100%; max-width: 870px; min-height: 554px;"></iframe>';
			echo '<script type="text/javascript">';
				echo 'jQuery(".ui-dialog-buttonset").find("button").first().remove();';
				echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(2)").hide().after("<span class=\'ui-button-text\'>Close</span>");';
				echo "";
			echo '</script>';
		echo '</div>';
	} else {
		echo '<div class="insert_ads_popup_content_wrapper">';
			echo '<p> There was an error processing your request. Please try again later. </p>';
		echo '</div>';
	}
	die();
}
/* End Signup Form */

/* Begin Login Form */
add_action('wp_ajax_insert_ads_vi_login_form_get_content', 'insert_ads_vi_login_form_get_content');
function insert_ads_vi_login_form_get_content() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	echo '<div class="insert_ads_popup_content_wrapper">';
		echo '<div class="insert_ads_vi_loginform_wrapper">';
			insert_ads_vi_login_form_get_controls();
		echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(2)").hide().after("<span class=\'ui-button-text\'>Login</span>");';
			echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(1)").attr("class", "ui-button-icon-primary ui-icon ui-icon-key");';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_insert_ads_vi_login_form_save_action', 'insert_ads_vi_login_form_save_action');
function insert_ads_vi_login_form_save_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	if(isset($_POST['insert_ads_vi_login_username']) && ($_POST['insert_ads_vi_login_username'] != '') && isset($_POST['insert_ads_vi_login_password']) && ($_POST['insert_ads_vi_login_password'] != '')) {
		$token = insert_ads_vi_api_login($_POST['insert_ads_vi_login_username'], $_POST['insert_ads_vi_login_password']);
		if(is_array($token) && (isset($token['status'])) && ($token['status'] == 'error')) {
			insert_ads_vi_login_form_get_controls();
			if($token['errorCode'] == 'WIVI008') {
				echo '<p class="insert_ads_vi_login_error">'.$token['message'].'</p>';
			} else {
				echo '<p class="insert_ads_vi_login_error">Error Code: '.$token['errorCode'].'<br />Please contact support or try again later!'.'</p>';
			}
		} else {
			echo '###SUCCESS###';
			insert_ads_vi_plugin_card_content(true, true);
		}		
	}
	die();
}

function insert_ads_vi_login_form_get_controls() {
	$control = new smartlogix();
	$control->HTML .= '<p>Please log in with the received credentials to complete the integration:</p>';
	$control->add_control(array('type' => 'text', 'id' => 'insert_ads_vi_login_username', 'name' => 'insert_ads_vi_login_username', 'label' => 'Email', 'value' => ''));
	$control->add_control(array('type' => 'password', 'id' => 'insert_ads_vi_login_password', 'name' => 'insert_ads_vi_login_password', 'label' => 'Password', 'value' => ''));
	$control->create_section('Login');
	echo $control->HTML;
}

add_action('wp_ajax_insert_ads_vi_update_adstxt', 'insert_ads_vi_update_adstxt');
function insert_ads_vi_update_adstxt() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	
	$adstxtContent = insert_ads_adstxt_get_content();
	$adstxtContentData = array_filter(explode("\n", trim($adstxtContent)), 'trim');
	$viEntry = insert_ads_vi_api_get_adstxt_content();
	if(strpos(str_replace(array("\r", "\n", " "), '', $adstxtContent), str_replace(array("\r", "\n", " "), '', $viEntry)) !== false) {
		die();
	} else {
		$updatedAdstxtContent = '';
		if(strpos($adstxtContent, '# 41b5eef6') !== false) {
			foreach($adstxtContentData as $line) {
				if(strpos($line, '# 41b5eef6') !== false) {
					
				} else {
					$updatedAdstxtContent .= str_replace(array("\r", "\n", " "), '', $line)."\r\n";
				}
			}
			$updatedAdstxtContent .= $viEntry;
		} else {
			$updatedAdstxtContent .= $adstxtContent."\r\n".$viEntry;
		}
		
		if(insert_ads_adstxt_update_content($updatedAdstxtContent)) {
			echo '###SUCCESS###';
			echo '<div class="notice notice-success insert_ads_adsstxt_notice is-dismissible" style="padding: 5px 15px;">';
				echo '<div style="float: left; max-width: 875px; font-size: 14px; font-family: Arial; line-height: 18px; color: #232323;">';
					echo '<p><b>ADS.TXT has been added</b></p>';
					echo '<p>Insert Post ads has updated your ads.txt file with lines that declare video intelligence as a legitimate seller of your inventory and enables you to make more money through video intelligence. Read the <a target="_blank" href="https://www.vi.ai/frequently-asked-questions-vi-stories-for-wordpress/?utm_source=WordPress&utm_medium=Plugin%20FAQ&utm_campaign=WP%20Insert">FAQ</a>.</p>';
				echo '</div>';
				echo '<img style="float: right; margin-right: 20px; margin-top: 13px;" src="'.WP_INSADS_URL.'images/logo-svg.svg?'.WP_INSADS_VERSION.'" />';
				echo '<div class="clear"></div>';
				echo '<button type="button" class="notice-dismiss" onclick="javascript:jQuery(this).parent().remove()"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			echo '</div>';
		} else {
			echo '###FAIL###';
			echo '<div class="notice notice-error insert_ads_adsstxt_notice is-dismissible" style="padding: 5px 15px;">';
				echo '<div style="float: left; max-width: 875px; font-size: 14px; font-family: Arial; line-height: 18px; color: #232323;">';
					echo '<p><b>WARNING!</b></p>';
					echo '<p><b>ads.txt couldn’t be added</b></p>';
					echo '<p>Insert Post Ads hasn\'t been able to update your ads.txt file.</p>';
					echo '<p>Please, make sure that you enter the following lines manually:</p>';
					echo '<p><code style="display: block;">'.trim(str_replace(array("\r\n", "\r", "\n"), "<br />", $viEntry)).'</code><br />Only by doing so, you\'ll be able to make more money through video intelligence (vi.ai).</p>';
				echo '</div>';
				echo '<img style="float: right; margin-right: 20px; margin-top: 13px;" src="'.WP_INSADS_URL.'images/vi-big-logo.png?'.WP_INSADS_VERSION.'" />';
				echo '<div class="clear"></div>';
				echo '<button type="button" class="notice-dismiss" onclick="javascript:jQuery(this).parent().remove()"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			echo '</div>';
		}
	}
	die();
} 
/* End Login Form */

/* Begin Logout */
add_action('wp_ajax_insert_ads_vi_logout_action', 'insert_ads_vi_logout_action');
function insert_ads_vi_logout_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	insert_ads_vi_api_logout();
	echo '###SUCCESS###';
	insert_ads_vi_plugin_card_content(false, true);
	die();
}
/* End Logout */

/* Begin Configure vi Code */
add_action('wp_ajax_insert_ads_vi_customize_adcode_form_get_content', 'insert_ads_vi_customize_adcode_form_get_content');
function insert_ads_vi_customize_adcode_form_get_content() {
	//check_ajax_referer('insert-ads', 'insert_ads_nonce');
	$vicodeSettings = get_option('insert_ads_vi_code_settings');
	$control = new smartlogix(array('optionIdentifier' => 'insert_ads_vi_code_settings', 'values' => $vicodeSettings));
	$control->HTML = '<div class="insert_ads_popup_content_wrapper">';
		$control->HTML .= '<p>Use this form to customize the look of the video unit. Use the same parameters as your WordPress theme for a natural look on your site</p>';
		$control->HTML .= '<div class="insert_ads_vi_popup_right_column">';
			$control->HTML .= '<img style="width: 60%;max-width:587px;margin: 0 auto; display: block;" src="'.WP_INSADS_URL.'images/advertisement-preview.png?'.WP_INSADS_VERSION.'" />';
		
			
		$control->HTML .= '</div>';
		$control->HTML .= '<div class="insert_ads_vi_popup_left_column">';
			$control->HTML .= '<p id="insert_ads_vi_customize_adcode_keywords_required_error" style="display: none;" class="viError">Keywords contains invalid characters, Some required fields are missing</p>';
			$control->HTML .= '<p id="insert_ads_vi_customize_adcode_keywords_error" style="display: none;" class="viError">Keywords contains invalid characters</p>';
			$control->HTML .= '<p id="insert_ads_vi_customize_adcode_required_error" style="display: none;" class="viError">Some required fields are missing</p>';
			$adUnitOptions = array(
				/*array('text' => 'Select Ad Unit', 'value' => 'select'),*/
				array('text' => 'vi stories', 'value' => 'NATIVE_VIDEO_UNIT'),
				/*array('text' => 'Outstream', 'value' => 'FLOATING_OUTSTREAM')*/
			);
			$control->add_control(array('type' => 'select', 'label' => ' Ad Unit*', 'optionName' => 'ad_unit_type', 'helpText' => '</small><span class="tooltipWrapper"><span class="tooltip">- vi stories (video advertising + video content)</span></span><small>', 'options' => $adUnitOptions));/*<br />- out-stream (video advertising)*/
			$control->add_control(array('type' => 'textarea', 'label' => 'Keywords', 'optionName' => 'keywords', 'helpText' => '</small><span class="tooltipWrapper"><span class="tooltip">Comma separated values describing the content of the page e.g. \'cooking, grilling, pulled pork\'</span></span><small>'));
			$control->HTML .= '<p><span class="keys-desc">Comma separated values describing the content of the page e.g. \'cooking, grilling, pulled pork\'</span><small></small></p>';
			$IABParentCategories = array(
				//array('text' => 'Select tier 1 category', 'value' => 'select'),
				array('text' => 'Arts & Entertainment', 'value' => 'IAB1'),
				array('text' => 'Automotive', 'value' => 'IAB2'),
				array('text' => 'Business', 'value' => 'IAB3'),
				array('text' => 'Careers', 'value' => 'IAB4'),
				array('text' => 'Education', 'value' => 'IAB5'),
				array('text' => 'Family & Parenting', 'value' => 'IAB6'),
				array('text' => 'Health & Fitness', 'value' => 'IAB7'),
				array('text' => 'Food & Drink', 'value' => 'IAB8'),
				array('text' => 'Hobbies & Interests', 'value' => 'IAB9'),
				array('text' => 'Home & Garden', 'value' => 'IAB10'),
				array('text' => 'Law, Gov’t & Politics', 'value' => 'IAB11'),
				array('text' => 'News', 'value' => 'IAB12'),
				array('text' => 'Personal Finance', 'value' => 'IAB13'),
				array('text' => 'Society', 'value' => 'IAB14'),
				array('text' => 'Science', 'value' => 'IAB15'),
				array('text' => 'Pets', 'value' => 'IAB16'),
				array('text' => 'Sports', 'value' => 'IAB17'),
				array('text' => 'Style & Fashion', 'value' => 'IAB18'),
				array('text' => 'Technology & Computing', 'value' => 'IAB19'),
				array('text' => 'Travel', 'value' => 'IAB20'),
				array('text' => 'Real Estate', 'value' => 'IAB21'),
				array('text' => 'Shopping', 'value' => 'IAB22'),
				array('text' => 'Religion & Spirituality', 'value' => 'IAB23'),
				array('text' => 'Uncategorized', 'value' => 'IAB24'),
				array('text' => 'Non-Standard Content', 'value' => 'IAB25'),
				array('text' => 'Illegal Content', 'value' => 'IAB26')
			);
			$control->add_control(array('type' => 'select', 'label' => 'IAB Category*', 'optionName' => 'iab_category_parent', 'options' => $IABParentCategories));
			$IABChildCategories = array(
				//array('text' => 'Select tier 2 category', 'value' => 'select'),
				array('text' => 'Books & Literature', 'value' => 'IAB1-1', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Celebrity Fan/Gossip', 'value' => 'IAB1-2', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Fine Art', 'value' => 'IAB1-3', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Humor', 'value' => 'IAB1-4', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Movies', 'value' => 'IAB1-5', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Music', 'value' => 'IAB1-6', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Television', 'value' => 'IAB1-7', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Auto Parts', 'value' => 'IAB2-1', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Auto Repair', 'value' => 'IAB2-2', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Buying/Selling Cars', 'value' => 'IAB2-3', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Car Culture', 'value' => 'IAB2-4', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Certified Pre-Owned', 'value' => 'IAB2-5', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Convertible', 'value' => 'IAB2-6', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Coupe', 'value' => 'IAB2-7', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Crossover', 'value' => 'IAB2-8', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Diesel', 'value' => 'IAB2-9', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Electric Vehicle', 'value' => 'IAB2-10', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Hatchback', 'value' => 'IAB2-11', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Hybrid', 'value' => 'IAB2-12', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Luxury', 'value' => 'IAB2-13', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'MiniVan', 'value' => 'IAB2-14', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Mororcycles', 'value' => 'IAB2-15', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Off-Road Vehicles', 'value' => 'IAB2-16', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Performance Vehicles', 'value' => 'IAB2-17', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Pickup', 'value' => 'IAB2-18', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Road-Side Assistance', 'value' => 'IAB2-19', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Sedan', 'value' => 'IAB2-20', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Trucks & Accessories', 'value' => 'IAB2-21', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Vintage Cars', 'value' => 'IAB2-22', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Wagon', 'value' => 'IAB2-23', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Advertising', 'value' => 'IAB3-1', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Agriculture', 'value' => 'IAB3-2', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Biotech/Biomedical', 'value' => 'IAB3-3', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Business Software', 'value' => 'IAB3-4', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Construction', 'value' => 'IAB3-5', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Forestry', 'value' => 'IAB3-6', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Government', 'value' => 'IAB3-7', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Green Solutions', 'value' => 'IAB3-8', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Human Resources', 'value' => 'IAB3-9', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Logistics', 'value' => 'IAB3-10', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Marketing', 'value' => 'IAB3-11', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Metals', 'value' => 'IAB3-12', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Career Planning', 'value' => 'IAB4-1', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'College', 'value' => 'IAB4-2', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Financial Aid', 'value' => 'IAB4-3', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Job Fairs', 'value' => 'IAB4-4', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Job Search', 'value' => 'IAB4-5', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Resume Writing/Advice', 'value' => 'IAB4-6', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Nursing', 'value' => 'IAB4-7', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Scholarships', 'value' => 'IAB4-8', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Telecommuting', 'value' => 'IAB4-9', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'U.S. Military', 'value' => 'IAB4-10', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Career Advice', 'value' => 'IAB4-11', 'metadata' => array('parent' => 'IAB4')),
				array('text' => '7-12 Education', 'value' => 'IAB5-1', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Adult Education', 'value' => 'IAB5-2', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Art History', 'value' => 'IAB5-3', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Colledge Administration', 'value' => 'IAB5-4', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'College Life', 'value' => 'IAB5-5', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Distance Learning', 'value' => 'IAB5-6', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'English as a 2nd Language', 'value' => 'IAB5-7', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Language Learning', 'value' => 'IAB5-8', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Graduate School', 'value' => 'IAB5-9', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Homeschooling', 'value' => 'IAB5-10', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Homework/Study Tips', 'value' => 'IAB5-11', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'K-6 Educators', 'value' => 'IAB5-12', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Private School', 'value' => 'IAB5-13', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Special Education', 'value' => 'IAB5-14', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Studying Business', 'value' => 'IAB5-15', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Adoption', 'value' => 'IAB6-1', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Babies & Toddlers', 'value' => 'IAB6-2', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Daycare/Pre School', 'value' => 'IAB6-3', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Family Internet', 'value' => 'IAB6-4', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Parenting – K-6 Kids', 'value' => 'IAB6-5', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Parenting teens', 'value' => 'IAB6-6', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Pregnancy', 'value' => 'IAB6-7', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Special Needs Kids', 'value' => 'IAB6-8', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Eldercare', 'value' => 'IAB6-9', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Exercise', 'value' => 'IAB7-1', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'A.D.D.', 'value' => 'IAB7-2', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'AIDS/HIV', 'value' => 'IAB7-3', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Allergies', 'value' => 'IAB7-4', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Alternative Medicine', 'value' => 'IAB7-5', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Arthritis', 'value' => 'IAB7-6', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Asthma', 'value' => 'IAB7-7', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Autism/PDD', 'value' => 'IAB7-8', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Bipolar Disorder', 'value' => 'IAB7-9', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Brain Tumor', 'value' => 'IAB7-10', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Cancer', 'value' => 'IAB7-11', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Cholesterol', 'value' => 'IAB7-12', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Chronic Fatigue Syndrome', 'value' => 'IAB7-13', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Chronic Pain', 'value' => 'IAB7-14', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Cold & Flu', 'value' => 'IAB7-15', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Deafness', 'value' => 'IAB7-16', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Dental Care', 'value' => 'IAB7-17', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Depression', 'value' => 'IAB7-18', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Dermatology', 'value' => 'IAB7-19', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Diabetes', 'value' => 'IAB7-20', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Epilepsy', 'value' => 'IAB7-21', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'GERD/Acid Reflux', 'value' => 'IAB7-22', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Headaches/Migraines', 'value' => 'IAB7-23', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Heart Disease', 'value' => 'IAB7-24', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Herbs for Health', 'value' => 'IAB7-25', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Holistic Healing', 'value' => 'IAB7-26', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'IBS/Crohn’s Disease', 'value' => 'IAB7-27', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Incest/Abuse Support', 'value' => 'IAB7-28', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Incontinence', 'value' => 'IAB7-29', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Infertility', 'value' => 'IAB7-30', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Men’s Health', 'value' => 'IAB7-31', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Nutrition', 'value' => 'IAB7-32', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Orthopedics', 'value' => 'IAB7-33', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Panic/Anxiety Disorders', 'value' => 'IAB7-34', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Pediatrics', 'value' => 'IAB7-35', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Physical Therapy', 'value' => 'IAB7-36', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Psychology/Psychiatry', 'value' => 'IAB7-37', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Senor Health', 'value' => 'IAB7-38', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Sexuality', 'value' => 'IAB7-39', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Sleep Disorders', 'value' => 'IAB7-40', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Smoking Cessation', 'value' => 'IAB7-41', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Substance Abuse', 'value' => 'IAB7-42', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Thyroid Disease', 'value' => 'IAB7-43', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Weight Loss', 'value' => 'IAB7-44', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Women’s Health', 'value' => 'IAB7-45', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'American Cuisine', 'value' => 'IAB8-1', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Barbecues & Grilling', 'value' => 'IAB8-2', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Cajun/Creole', 'value' => 'IAB8-3', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Chinese Cuisine', 'value' => 'IAB8-4', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Cocktails/Beer', 'value' => 'IAB8-5', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Coffee/Tea', 'value' => 'IAB8-6', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Cuisine-Specific', 'value' => 'IAB8-7', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Desserts & Baking', 'value' => 'IAB8-8', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Dining Out', 'value' => 'IAB8-9', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Food Allergies', 'value' => 'IAB8-10', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'French Cuisine', 'value' => 'IAB8-11', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Health/Lowfat Cooking', 'value' => 'IAB8-12', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Italian Cuisine', 'value' => 'IAB8-13', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Japanese Cuisine', 'value' => 'IAB8-14', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Mexican Cuisine', 'value' => 'IAB8-15', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Vegan', 'value' => 'IAB8-16', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Vegetarian', 'value' => 'IAB8-17', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Wine', 'value' => 'IAB8-18', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Art/Technology', 'value' => 'IAB9-1', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Arts & Crafts', 'value' => 'IAB9-2', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Beadwork', 'value' => 'IAB9-3', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Birdwatching', 'value' => 'IAB9-4', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Board Games/Puzzles', 'value' => 'IAB9-5', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Candle & Soap Making', 'value' => 'IAB9-6', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Card Games', 'value' => 'IAB9-7', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Chess', 'value' => 'IAB9-8', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Cigars', 'value' => 'IAB9-9', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Collecting', 'value' => 'IAB9-10', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Comic Books', 'value' => 'IAB9-11', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Drawing/Sketching', 'value' => 'IAB9-12', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Freelance Writing', 'value' => 'IAB9-13', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Genealogy', 'value' => 'IAB9-14', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Getting Published', 'value' => 'IAB9-15', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Guitar', 'value' => 'IAB9-16', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Home Recording', 'value' => 'IAB9-17', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Investors & Patents', 'value' => 'IAB9-18', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Jewelry Making', 'value' => 'IAB9-19', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Magic & Illusion', 'value' => 'IAB9-20', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Needlework', 'value' => 'IAB9-21', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Painting', 'value' => 'IAB9-22', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Photography', 'value' => 'IAB9-23', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Radio', 'value' => 'IAB9-24', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Roleplaying Games', 'value' => 'IAB9-25', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Sci-Fi & Fantasy', 'value' => 'IAB9-26', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Scrapbooking', 'value' => 'IAB9-27', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Screenwriting', 'value' => 'IAB9-28', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Stamps & Coins', 'value' => 'IAB9-29', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Video & Computer Games', 'value' => 'IAB9-30', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Woodworking', 'value' => 'IAB9-31', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Appliances', 'value' => 'IAB10-1', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Entertaining', 'value' => 'IAB10-2', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Environmental Safety', 'value' => 'IAB10-3', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Gardening', 'value' => 'IAB10-4', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Home Repair', 'value' => 'IAB10-5', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Home Theater', 'value' => 'IAB10-6', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Interior Decorating', 'value' => 'IAB10-7', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Landscaping', 'value' => 'IAB10-8', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Remodeling & Construction', 'value' => 'IAB10-9', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Immigration', 'value' => 'IAB11-1', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'Legal Issues', 'value' => 'IAB11-2', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'U.S. Government Resources', 'value' => 'IAB11-3', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'Politics', 'value' => 'IAB11-4', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'Commentary', 'value' => 'IAB11-5', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'International News', 'value' => 'IAB12-1', 'metadata' => array('parent' => 'IAB12')),
				array('text' => 'National News', 'value' => 'IAB12-2', 'metadata' => array('parent' => 'IAB12')),
				array('text' => 'Local News', 'value' => 'IAB12-3', 'metadata' => array('parent' => 'IAB12')),
				array('text' => 'Beginning Investing', 'value' => 'IAB13-1', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Credit/Debt & Loans', 'value' => 'IAB13-2', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Financial News', 'value' => 'IAB13-3', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Financial Planning', 'value' => 'IAB13-4', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Hedge Fund', 'value' => 'IAB13-5', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Insurance', 'value' => 'IAB13-6', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Investing', 'value' => 'IAB13-7', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Mutual Funds', 'value' => 'IAB13-8', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Options', 'value' => 'IAB13-9', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Retirement Planning', 'value' => 'IAB13-10', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Stocks', 'value' => 'IAB13-11', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Tax Planning', 'value' => 'IAB13-12', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Dating', 'value' => 'IAB14-1', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Divorce Support', 'value' => 'IAB14-2', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Gay Life', 'value' => 'IAB14-3', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Marriage', 'value' => 'IAB14-4', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Senior Living', 'value' => 'IAB14-5', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Teens', 'value' => 'IAB14-6', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Weddings', 'value' => 'IAB14-7', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Ethnic Specific', 'value' => 'IAB14-8', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Astrology', 'value' => 'IAB15-1', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Biology', 'value' => 'IAB15-2', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Chemistry', 'value' => 'IAB15-3', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Geology', 'value' => 'IAB15-4', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Paranormal Phenomena', 'value' => 'IAB15-5', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Physics', 'value' => 'IAB15-6', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Space/Astronomy', 'value' => 'IAB15-7', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Geography', 'value' => 'IAB15-8', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Botany', 'value' => 'IAB15-9', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Weather', 'value' => 'IAB15-10', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Aquariums', 'value' => 'IAB16-1', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Birds', 'value' => 'IAB16-2', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Cats', 'value' => 'IAB16-3', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Dogs', 'value' => 'IAB16-4', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Large Animals', 'value' => 'IAB16-5', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Reptiles', 'value' => 'IAB16-6', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Veterinary Medicine', 'value' => 'IAB16-7', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Auto Racing', 'value' => 'IAB17-1', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Baseball', 'value' => 'IAB17-2', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Bicycling', 'value' => 'IAB17-3', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Bodybuilding', 'value' => 'IAB17-4', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Boxing', 'value' => 'IAB17-5', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Canoeing/Kayaking', 'value' => 'IAB17-6', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Cheerleading', 'value' => 'IAB17-7', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Climbing', 'value' => 'IAB17-8', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Cricket', 'value' => 'IAB17-9', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Figure Skating', 'value' => 'IAB17-10', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Fly Fishing', 'value' => 'IAB17-11', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Football', 'value' => 'IAB17-12', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Freshwater Fishing', 'value' => 'IAB17-13', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Game & Fish', 'value' => 'IAB17-14', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Golf', 'value' => 'IAB17-15', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Horse Racing', 'value' => 'IAB17-16', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Horses', 'value' => 'IAB17-17', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Hunting/Shooting', 'value' => 'IAB17-18', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Inline Skating', 'value' => 'IAB17-19', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Martial Arts', 'value' => 'IAB17-20', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Mountain Biking', 'value' => 'IAB17-21', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'NASCAR Racing', 'value' => 'IAB17-22', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Olympics', 'value' => 'IAB17-23', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Paintball', 'value' => 'IAB17-24', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Power & Motorcycles', 'value' => 'IAB17-25', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Pro Basketball', 'value' => 'IAB17-26', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Pro Ice Hockey', 'value' => 'IAB17-27', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Rodeo', 'value' => 'IAB17-28', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Rugby', 'value' => 'IAB17-29', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Running/Jogging', 'value' => 'IAB17-30', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Sailing', 'value' => 'IAB17-31', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Saltwater Fishing', 'value' => 'IAB17-32', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Scuba Diving', 'value' => 'IAB17-33', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Skateboarding', 'value' => 'IAB17-34', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Skiing', 'value' => 'IAB17-35', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Snowboarding', 'value' => 'IAB17-36', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Surfing/Bodyboarding', 'value' => 'IAB17-37', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Swimming', 'value' => 'IAB17-38', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Table Tennis/Ping-Pong', 'value' => 'IAB17-39', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Tennis', 'value' => 'IAB17-40', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Volleyball', 'value' => 'IAB17-41', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Walking', 'value' => 'IAB17-42', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Waterski/Wakeboard', 'value' => 'IAB17-43', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'World Soccer', 'value' => 'IAB17-44', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Beauty', 'value' => 'IAB18-1', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Body Art', 'value' => 'IAB18-2', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Fashion', 'value' => 'IAB18-3', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Jewelry', 'value' => 'IAB18-4', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Clothing', 'value' => 'IAB18-5', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Accessories', 'value' => 'IAB18-6', 'metadata' => array('parent' => 'IAB18')),
				array('text' => '3-D Graphics', 'value' => 'IAB19-1', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Animation', 'value' => 'IAB19-2', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Antivirus Software', 'value' => 'IAB19-3', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'C/C++', 'value' => 'IAB19-4', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Cameras & Camcorders', 'value' => 'IAB19-5', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Cell Phones', 'value' => 'IAB19-6', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Certification', 'value' => 'IAB19-7', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Networking', 'value' => 'IAB19-8', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Peripherals', 'value' => 'IAB19-9', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Reviews', 'value' => 'IAB19-10', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Data Centers', 'value' => 'IAB19-11', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Databases', 'value' => 'IAB19-12', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Desktop Publishing', 'value' => 'IAB19-13', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Desktop Video', 'value' => 'IAB19-14', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Email', 'value' => 'IAB19-15', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Graphics Software', 'value' => 'IAB19-16', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Home Video/DVD', 'value' => 'IAB19-17', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Internet Technology', 'value' => 'IAB19-18', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Java', 'value' => 'IAB19-19', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'JavaScript', 'value' => 'IAB19-20', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Mac Support', 'value' => 'IAB19-21', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'MP3/MIDI', 'value' => 'IAB19-22', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Net Conferencing', 'value' => 'IAB19-23', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Net for Beginners', 'value' => 'IAB19-24', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Network Security', 'value' => 'IAB19-25', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Palmtops/PDAs', 'value' => 'IAB19-26', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'PC Support', 'value' => 'IAB19-27', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Portable', 'value' => 'IAB19-28', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Entertainment', 'value' => 'IAB19-29', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Shareware/Freeware', 'value' => 'IAB19-30', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Unix', 'value' => 'IAB19-31', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Visual Basic', 'value' => 'IAB19-32', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Web Clip Art', 'value' => 'IAB19-33', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Web Design/HTML', 'value' => 'IAB19-34', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Web Search', 'value' => 'IAB19-35', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Windows', 'value' => 'IAB19-36', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Adventure Travel', 'value' => 'IAB20-1', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Africa', 'value' => 'IAB20-2', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Air Travel', 'value' => 'IAB20-3', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Australia & New Zealand', 'value' => 'IAB20-4', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Bed & Breakfasts', 'value' => 'IAB20-5', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Budget Travel', 'value' => 'IAB20-6', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Business Travel', 'value' => 'IAB20-7', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'By US Locale', 'value' => 'IAB20-8', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Camping', 'value' => 'IAB20-9', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Canada', 'value' => 'IAB20-10', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Caribbean', 'value' => 'IAB20-11', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Cruises', 'value' => 'IAB20-12', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Eastern Europe', 'value' => 'IAB20-13', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Europe', 'value' => 'IAB20-14', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'France', 'value' => 'IAB20-15', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Greece', 'value' => 'IAB20-16', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Honeymoons/Getaways', 'value' => 'IAB20-17', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Hotels', 'value' => 'IAB20-18', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Italy', 'value' => 'IAB20-19', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Japan', 'value' => 'IAB20-20', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Mexico & Central America', 'value' => 'IAB20-21', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'National Parks', 'value' => 'IAB20-22', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'South America', 'value' => 'IAB20-23', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Spas', 'value' => 'IAB20-24', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Theme Parks', 'value' => 'IAB20-25', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Traveling with Kids', 'value' => 'IAB20-26', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'United Kingdom', 'value' => 'IAB20-27', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Apartments', 'value' => 'IAB21-1', 'metadata' => array('parent' => 'IAB21')),
				array('text' => 'Architects', 'value' => 'IAB21-2', 'metadata' => array('parent' => 'IAB21')),
				array('text' => 'Buying/Selling Homes', 'value' => 'IAB21-3', 'metadata' => array('parent' => 'IAB21')),
				array('text' => 'Contests & Freebies', 'value' => 'IAB22-1', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Couponing', 'value' => 'IAB22-2', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Comparison', 'value' => 'IAB22-3', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Engines', 'value' => 'IAB22-4', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Alternative Religions', 'value' => 'IAB23-1', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Atheism/Agnosticism', 'value' => 'IAB23-2', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Buddhism', 'value' => 'IAB23-3', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Catholicism', 'value' => 'IAB23-4', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Christianity', 'value' => 'IAB23-5', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Hinduism', 'value' => 'IAB23-6', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Islam', 'value' => 'IAB23-7', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Judaism', 'value' => 'IAB23-8', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Latter-Day Saints', 'value' => 'IAB23-9', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Pagan/Wiccan', 'value' => 'IAB23-10', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Unmoderated UGC', 'value' => 'IAB25-1', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Extreme Graphic/Explicit Violence', 'value' => 'IAB25-2', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Pornography', 'value' => 'IAB25-3', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Profane Content', 'value' => 'IAB25-4', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Hate Content', 'value' => 'IAB25-5', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Under Construction', 'value' => 'IAB25-6', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Incentivized', 'value' => 'IAB25-7', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Illegal Content', 'value' => 'IAB26-1', 'metadata' => array('parent' => 'IAB26')),
				array('text' => 'Warez', 'value' => 'IAB26-2', 'metadata' => array('parent' => 'IAB26')),
				array('text' => 'Spyware/Malware', 'value' => 'IAB26-3', 'metadata' => array('parent' => 'IAB26')),
				array('text' => 'Copyright Infringement', 'value' => 'IAB26-4', 'metadata' => array('parent' => 'IAB26'))
			);
			$control->add_control(array('type' => 'select', 'label' => '&nbsp;', 'optionName' => 'iab_category_child', 'helpText' => '&nbsp;', 'options' => $IABChildCategories));
			$languages = insert_ads_vi_api_get_languages();			
			$languageOptions = array(
				//array('text' => 'Select language', 'value' => 'select'),
			);
			if($languages != false) {
				foreach($languages as $key => $value) {
					$languageOptions[] = array('text' => $value, 'value' => $key);
				}
			}
			$control->add_control(array('type' => 'select', 'label' => 'Language*', 'optionName' => 'language', 'helpText' => '&nbsp;', 'options' => $languageOptions));
			$control->add_control(array('type' => 'minicolors', 'label' => 'Native Background color', 'optionName' => 'native_bg_color', 'helpText' => '&nbsp;'));
			$control->add_control(array('type' => 'minicolors', 'label' => 'Native Text color', 'optionName' => 'native_text_color', 'helpText' => '&nbsp;'));
			$fontFamily =array(
				array('text' => 'Select font family', 'value' => 'select'),
				array('text' => 'Georgia', 'value' => 'Georgia'),
				array('text' => 'Palatino Linotype', 'value' => 'Palatino Linotype'),
				array('text' => 'Times New Roman', 'value' => 'Times New Roman'),
				array('text' => 'Arial', 'value' => 'Arial'),
				array('text' => 'Arial Black', 'value' => 'Arial Black'),
				array('text' => 'Comic Sans MS', 'value' => 'Comic Sans MS'),
				array('text' => 'Impact', 'value' => 'Impact'),
				array('text' => 'Lucida Sans Unicode', 'value' => 'Lucida Sans Unicode'),
				array('text' => 'Tahoma', 'value' => 'Tahoma'),
				array('text' => 'Trebuchet MS', 'value' => 'Trebuchet MS'),
				array('text' => 'Verdana', 'value' => 'Verdana'),
				array('text' => 'Courier New', 'value' => 'Courier New'),
				array('text' => 'Lucida Console', 'value' => 'Lucida Console')
			);			
			$control->add_control(array('type' => 'select', 'label' => ' Native Text Font Family', 'optionName' => 'font_family', 'helpText' => '&nbsp;', 'options' => $fontFamily));
			$fontSize =array(
				array('text' => 'Select font size', 'value' => 'select'),
				array('text' => '8px', 'value' => '8'),
				array('text' => '9px', 'value' => '9'),
				array('text' => '10px', 'value' => '10'),
				array('text' => '11px', 'value' => '11'),
				array('text' => '12px', 'value' => '12'),
				array('text' => '14px', 'value' => '14'),
				array('text' => '16px', 'value' => '16'),
				array('text' => '18px', 'value' => '18'),
				array('text' => '20px', 'value' => '20'),
				array('text' => '22px', 'value' => '22'),
				array('text' => '24px', 'value' => '24'),
				array('text' => '26px', 'value' => '26'),
				array('text' => '28px', 'value' => '28'),
				array('text' => '36px', 'value' => '36')
			);			
			$control->add_control(array('type' => 'select', 'label' => 'Native Text Font Size', 'optionName' => 'font_size', 'helpText' => '&nbsp;', 'options' => $fontSize));
			$control->add_control(array('type' => 'textarea', 'label' => 'Optional 1', 'optionName' => 'optional_1', 'helpText' => '&nbsp;'));
			$control->add_control(array('type' => 'textarea', 'label' => 'Optional 2', 'optionName' => 'optional_2', 'helpText' => '&nbsp;'));
			$control->add_control(array('type' => 'textarea', 'label' => 'Optional 3', 'optionName' => 'optional_3', 'helpText' => '&nbsp;'));
			//$control->HTML .= '<p class="insert_ads_vi_delay_notice">vi Ad Changes might take some time to take into effect</p>';
			$control->HTML .= '<div class="clear"></div>';
			$control->HTML .= '<p><span class="keys-desc">vi Ad Changes might take some time to take into effect</span><small></small></p>';
			$control->HTML .= '<p><button class="button button-primary" id="set-update">Update</button></p>';
		$control->HTML .= '</div>';
		$control->HTML .= '<div class="clear"></div>';
	$control->HTML .= '</div>';
	$control->create_section(' vi Stories Settings ');
	echo $control->HTML;
    $control->clear_controls();

    $control->HTML .= '<p>Enable GDPR Compliance confirmation notice on your site for visitors from EU.<br />If you disable this option make sure you are using a data usage authorization system on your website to remain GDPR complaint.</p>';
    $control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Do not Show GDPR Authorization Popup', 'checkedLabel' => 'Status : Show GDPR Authorization Popup', 'uncheckedLabel' => 'Status : Do not Show GDPR Authorization Popup', 'optionName' => 'show_gdpr_authorization'));
    $control->create_section(' vi stories: GDPR Compliance ');
    echo $control->HTML;
	echo '<script type="text/javascript">';
		echo $control->JS;
		echo 'insert_ads_vi_code_iab_category_parent_change();';
	echo '</script>';
	//die();
}

add_action('wp_ajax_insert_ads_vi_customize_adcode_form_save_action', 'insert_ads_vi_customize_adcode_form_save_action');
function insert_ads_vi_customize_adcode_form_save_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');	
	$vicodeSettings = array();
	$vicodeSettings['ad_unit_type'] = ((isset($_POST['insert_ads_vi_code_settings_ad_unit_type']))?$_POST['insert_ads_vi_code_settings_ad_unit_type']:'');
	$vicodeSettings['keywords'] = ((isset($_POST['insert_ads_vi_code_settings_keywords']))?$_POST['insert_ads_vi_code_settings_keywords']:'');
	$vicodeSettings['iab_category_parent'] = ((isset($_POST['insert_ads_vi_code_settings_iab_category_parent']))?$_POST['insert_ads_vi_code_settings_iab_category_parent']:'');
	$vicodeSettings['iab_category_child'] = ((isset($_POST['insert_ads_vi_code_settings_iab_category_child']))?$_POST['insert_ads_vi_code_settings_iab_category_child']:'');
	$vicodeSettings['language'] = ((isset($_POST['insert_ads_vi_code_settings_language']))?$_POST['insert_ads_vi_code_settings_language']:'');
	$vicodeSettings['native_bg_color'] = ((isset($_POST['insert_ads_vi_code_settings_native_bg_color']))?$_POST['insert_ads_vi_code_settings_native_bg_color']:'#fff');
	$vicodeSettings['native_text_color'] = ((isset($_POST['insert_ads_vi_code_settings_native_text_color']))?$_POST['insert_ads_vi_code_settings_native_text_color']:'#000');
	$vicodeSettings['font_family'] = ((isset($_POST['insert_ads_vi_code_settings_font_family']))?$_POST['insert_ads_vi_code_settings_font_family']:'');
	$vicodeSettings['font_size'] = ((isset($_POST['insert_ads_vi_code_settings_font_size']))?$_POST['insert_ads_vi_code_settings_font_size']:'');
	$vicodeSettings['optional_1'] = ((isset($_POST['insert_ads_vi_code_settings_optional_1']))?$_POST['insert_ads_vi_code_settings_optional_1']:'');
	$vicodeSettings['optional_2'] = ((isset($_POST['insert_ads_vi_code_settings_optional_2']))?$_POST['insert_ads_vi_code_settings_optional_2']:'');
	$vicodeSettings['optional_3'] = ((isset($_POST['insert_ads_vi_code_settings_optional_3']))?$_POST['insert_ads_vi_code_settings_optional_3']:'');

    $vicodeSettings['show_gdpr_authorization'] = ((isset($_POST['insert_ads_vi_code_settings_show_gdpr_authorization']))?$_POST['insert_ads_vi_code_settings_show_gdpr_authorization']:'');
	update_option('insert_ads_vi_code_settings', $vicodeSettings);
	$viCodeStatus = insert_ads_vi_api_set_vi_code($vicodeSettings);
	if(is_array($viCodeStatus) && (isset($viCodeStatus['status'])) && ($viCodeStatus['status'] == 'error')) {
		if($viCodeStatus['errorCode'] == 'WIVI108') {
			echo '###FAIL###';
			echo '<p class="viError">'.$viCodeStatus['message'].'</p>';
			var_dump($viCodeStatus['message']);
		} else {
			echo '###FAIL###';
			echo '<p class="viError">There was an error processing your request, our team was notified.<br />Please try again later.</p>';
		}
	} else {
		echo '###SUCCESS###';
		echo 'Success!';
	}
	die();
}

function insert_ads_vi_customize_adcode_get_settings() {
	$vicodeSettings = get_option('insert_ads_vi_code_settings');
	
	$output = '';
	if(isset($vicodeSettings) && is_array($vicodeSettings)) {
		$output .= '<p class="insert_ads_vi_code_data_wrapper">';
		if(isset($vicodeSettings['ad_unit_type']) && ($vicodeSettings['ad_unit_type'] != '') && ($vicodeSettings['ad_unit_type'] != 'select')) {
			$output .= '<label>Ad Unit:</label><b>vi stories</b>';
		}
		
		if(isset($vicodeSettings['keywords']) && ($vicodeSettings['keywords'] != '')) {
			$output .= '<label>Keywords:</label><b>'.$vicodeSettings['keywords'].'</b>';
		}
		
		if(isset($vicodeSettings['iab_category_child']) && ($vicodeSettings['iab_category_child'] != '') && ($vicodeSettings['iab_category_child'] != 'select')) {
			$IABChildCategories = array(
				array('text' => 'Select', 'value' => 'select'),
				array('text' => 'Books & Literature', 'value' => 'IAB1-1', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Celebrity Fan/Gossip', 'value' => 'IAB1-2', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Fine Art', 'value' => 'IAB1-3', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Humor', 'value' => 'IAB1-4', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Movies', 'value' => 'IAB1-5', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Music', 'value' => 'IAB1-6', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Television', 'value' => 'IAB1-7', 'metadata' => array('parent' => 'IAB1')),
				array('text' => 'Auto Parts', 'value' => 'IAB2-1', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Auto Repair', 'value' => 'IAB2-2', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Buying/Selling Cars', 'value' => 'IAB2-3', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Car Culture', 'value' => 'IAB2-4', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Certified Pre-Owned', 'value' => 'IAB2-5', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Convertible', 'value' => 'IAB2-6', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Coupe', 'value' => 'IAB2-7', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Crossover', 'value' => 'IAB2-8', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Diesel', 'value' => 'IAB2-9', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Electric Vehicle', 'value' => 'IAB2-10', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Hatchback', 'value' => 'IAB2-11', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Hybrid', 'value' => 'IAB2-12', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Luxury', 'value' => 'IAB2-13', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'MiniVan', 'value' => 'IAB2-14', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Mororcycles', 'value' => 'IAB2-15', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Off-Road Vehicles', 'value' => 'IAB2-16', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Performance Vehicles', 'value' => 'IAB2-17', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Pickup', 'value' => 'IAB2-18', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Road-Side Assistance', 'value' => 'IAB2-19', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Sedan', 'value' => 'IAB2-20', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Trucks & Accessories', 'value' => 'IAB2-21', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Vintage Cars', 'value' => 'IAB2-22', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Wagon', 'value' => 'IAB2-23', 'metadata' => array('parent' => 'IAB2')),
				array('text' => 'Advertising', 'value' => 'IAB3-1', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Agriculture', 'value' => 'IAB3-2', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Biotech/Biomedical', 'value' => 'IAB3-3', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Business Software', 'value' => 'IAB3-4', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Construction', 'value' => 'IAB3-5', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Forestry', 'value' => 'IAB3-6', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Government', 'value' => 'IAB3-7', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Green Solutions', 'value' => 'IAB3-8', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Human Resources', 'value' => 'IAB3-9', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Logistics', 'value' => 'IAB3-10', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Marketing', 'value' => 'IAB3-11', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Metals', 'value' => 'IAB3-12', 'metadata' => array('parent' => 'IAB3')),
				array('text' => 'Career Planning', 'value' => 'IAB4-1', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'College', 'value' => 'IAB4-2', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Financial Aid', 'value' => 'IAB4-3', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Job Fairs', 'value' => 'IAB4-4', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Job Search', 'value' => 'IAB4-5', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Resume Writing/Advice', 'value' => 'IAB4-6', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Nursing', 'value' => 'IAB4-7', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Scholarships', 'value' => 'IAB4-8', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Telecommuting', 'value' => 'IAB4-9', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'U.S. Military', 'value' => 'IAB4-10', 'metadata' => array('parent' => 'IAB4')),
				array('text' => 'Career Advice', 'value' => 'IAB4-11', 'metadata' => array('parent' => 'IAB4')),
				array('text' => '7-12 Education', 'value' => 'IAB5-1', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Adult Education', 'value' => 'IAB5-2', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Art History', 'value' => 'IAB5-3', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Colledge Administration', 'value' => 'IAB5-4', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'College Life', 'value' => 'IAB5-5', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Distance Learning', 'value' => 'IAB5-6', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'English as a 2nd Language', 'value' => 'IAB5-7', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Language Learning', 'value' => 'IAB5-8', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Graduate School', 'value' => 'IAB5-9', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Homeschooling', 'value' => 'IAB5-10', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Homework/Study Tips', 'value' => 'IAB5-11', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'K-6 Educators', 'value' => 'IAB5-12', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Private School', 'value' => 'IAB5-13', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Special Education', 'value' => 'IAB5-14', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Studying Business', 'value' => 'IAB5-15', 'metadata' => array('parent' => 'IAB5')),
				array('text' => 'Adoption', 'value' => 'IAB6-1', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Babies & Toddlers', 'value' => 'IAB6-2', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Daycare/Pre School', 'value' => 'IAB6-3', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Family Internet', 'value' => 'IAB6-4', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Parenting – K-6 Kids', 'value' => 'IAB6-5', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Parenting teens', 'value' => 'IAB6-6', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Pregnancy', 'value' => 'IAB6-7', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Special Needs Kids', 'value' => 'IAB6-8', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Eldercare', 'value' => 'IAB6-9', 'metadata' => array('parent' => 'IAB6')),
				array('text' => 'Exercise', 'value' => 'IAB7-1', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'A.D.D.', 'value' => 'IAB7-2', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'AIDS/HIV', 'value' => 'IAB7-3', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Allergies', 'value' => 'IAB7-4', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Alternative Medicine', 'value' => 'IAB7-5', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Arthritis', 'value' => 'IAB7-6', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Asthma', 'value' => 'IAB7-7', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Autism/PDD', 'value' => 'IAB7-8', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Bipolar Disorder', 'value' => 'IAB7-9', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Brain Tumor', 'value' => 'IAB7-10', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Cancer', 'value' => 'IAB7-11', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Cholesterol', 'value' => 'IAB7-12', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Chronic Fatigue Syndrome', 'value' => 'IAB7-13', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Chronic Pain', 'value' => 'IAB7-14', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Cold & Flu', 'value' => 'IAB7-15', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Deafness', 'value' => 'IAB7-16', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Dental Care', 'value' => 'IAB7-17', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Depression', 'value' => 'IAB7-18', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Dermatology', 'value' => 'IAB7-19', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Diabetes', 'value' => 'IAB7-20', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Epilepsy', 'value' => 'IAB7-21', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'GERD/Acid Reflux', 'value' => 'IAB7-22', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Headaches/Migraines', 'value' => 'IAB7-23', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Heart Disease', 'value' => 'IAB7-24', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Herbs for Health', 'value' => 'IAB7-25', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Holistic Healing', 'value' => 'IAB7-26', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'IBS/Crohn’s Disease', 'value' => 'IAB7-27', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Incest/Abuse Support', 'value' => 'IAB7-28', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Incontinence', 'value' => 'IAB7-29', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Infertility', 'value' => 'IAB7-30', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Men’s Health', 'value' => 'IAB7-31', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Nutrition', 'value' => 'IAB7-32', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Orthopedics', 'value' => 'IAB7-33', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Panic/Anxiety Disorders', 'value' => 'IAB7-34', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Pediatrics', 'value' => 'IAB7-35', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Physical Therapy', 'value' => 'IAB7-36', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Psychology/Psychiatry', 'value' => 'IAB7-37', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Senor Health', 'value' => 'IAB7-38', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Sexuality', 'value' => 'IAB7-39', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Sleep Disorders', 'value' => 'IAB7-40', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Smoking Cessation', 'value' => 'IAB7-41', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Substance Abuse', 'value' => 'IAB7-42', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Thyroid Disease', 'value' => 'IAB7-43', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Weight Loss', 'value' => 'IAB7-44', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'Women’s Health', 'value' => 'IAB7-45', 'metadata' => array('parent' => 'IAB7')),
				array('text' => 'American Cuisine', 'value' => 'IAB8-1', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Barbecues & Grilling', 'value' => 'IAB8-2', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Cajun/Creole', 'value' => 'IAB8-3', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Chinese Cuisine', 'value' => 'IAB8-4', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Cocktails/Beer', 'value' => 'IAB8-5', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Coffee/Tea', 'value' => 'IAB8-6', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Cuisine-Specific', 'value' => 'IAB8-7', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Desserts & Baking', 'value' => 'IAB8-8', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Dining Out', 'value' => 'IAB8-9', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Food Allergies', 'value' => 'IAB8-10', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'French Cuisine', 'value' => 'IAB8-11', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Health/Lowfat Cooking', 'value' => 'IAB8-12', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Italian Cuisine', 'value' => 'IAB8-13', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Japanese Cuisine', 'value' => 'IAB8-14', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Mexican Cuisine', 'value' => 'IAB8-15', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Vegan', 'value' => 'IAB8-16', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Vegetarian', 'value' => 'IAB8-17', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Wine', 'value' => 'IAB8-18', 'metadata' => array('parent' => 'IAB8')),
				array('text' => 'Art/Technology', 'value' => 'IAB9-1', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Arts & Crafts', 'value' => 'IAB9-2', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Beadwork', 'value' => 'IAB9-3', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Birdwatching', 'value' => 'IAB9-4', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Board Games/Puzzles', 'value' => 'IAB9-5', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Candle & Soap Making', 'value' => 'IAB9-6', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Card Games', 'value' => 'IAB9-7', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Chess', 'value' => 'IAB9-8', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Cigars', 'value' => 'IAB9-9', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Collecting', 'value' => 'IAB9-10', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Comic Books', 'value' => 'IAB9-11', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Drawing/Sketching', 'value' => 'IAB9-12', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Freelance Writing', 'value' => 'IAB9-13', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Genealogy', 'value' => 'IAB9-14', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Getting Published', 'value' => 'IAB9-15', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Guitar', 'value' => 'IAB9-16', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Home Recording', 'value' => 'IAB9-17', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Investors & Patents', 'value' => 'IAB9-18', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Jewelry Making', 'value' => 'IAB9-19', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Magic & Illusion', 'value' => 'IAB9-20', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Needlework', 'value' => 'IAB9-21', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Painting', 'value' => 'IAB9-22', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Photography', 'value' => 'IAB9-23', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Radio', 'value' => 'IAB9-24', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Roleplaying Games', 'value' => 'IAB9-25', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Sci-Fi & Fantasy', 'value' => 'IAB9-26', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Scrapbooking', 'value' => 'IAB9-27', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Screenwriting', 'value' => 'IAB9-28', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Stamps & Coins', 'value' => 'IAB9-29', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Video & Computer Games', 'value' => 'IAB9-30', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Woodworking', 'value' => 'IAB9-31', 'metadata' => array('parent' => 'IAB9')),
				array('text' => 'Appliances', 'value' => 'IAB10-1', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Entertaining', 'value' => 'IAB10-2', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Environmental Safety', 'value' => 'IAB10-3', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Gardening', 'value' => 'IAB10-4', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Home Repair', 'value' => 'IAB10-5', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Home Theater', 'value' => 'IAB10-6', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Interior Decorating', 'value' => 'IAB10-7', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Landscaping', 'value' => 'IAB10-8', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Remodeling & Construction', 'value' => 'IAB10-9', 'metadata' => array('parent' => 'IAB10')),
				array('text' => 'Immigration', 'value' => 'IAB11-1', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'Legal Issues', 'value' => 'IAB11-2', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'U.S. Government Resources', 'value' => 'IAB11-3', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'Politics', 'value' => 'IAB11-4', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'Commentary', 'value' => 'IAB11-5', 'metadata' => array('parent' => 'IAB11')),
				array('text' => 'International News', 'value' => 'IAB12-1', 'metadata' => array('parent' => 'IAB12')),
				array('text' => 'National News', 'value' => 'IAB12-2', 'metadata' => array('parent' => 'IAB12')),
				array('text' => 'Local News', 'value' => 'IAB12-3', 'metadata' => array('parent' => 'IAB12')),
				array('text' => 'Beginning Investing', 'value' => 'IAB13-1', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Credit/Debt & Loans', 'value' => 'IAB13-2', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Financial News', 'value' => 'IAB13-3', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Financial Planning', 'value' => 'IAB13-4', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Hedge Fund', 'value' => 'IAB13-5', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Insurance', 'value' => 'IAB13-6', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Investing', 'value' => 'IAB13-7', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Mutual Funds', 'value' => 'IAB13-8', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Options', 'value' => 'IAB13-9', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Retirement Planning', 'value' => 'IAB13-10', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Stocks', 'value' => 'IAB13-11', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Tax Planning', 'value' => 'IAB13-12', 'metadata' => array('parent' => 'IAB13')),
				array('text' => 'Dating', 'value' => 'IAB14-1', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Divorce Support', 'value' => 'IAB14-2', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Gay Life', 'value' => 'IAB14-3', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Marriage', 'value' => 'IAB14-4', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Senior Living', 'value' => 'IAB14-5', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Teens', 'value' => 'IAB14-6', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Weddings', 'value' => 'IAB14-7', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Ethnic Specific', 'value' => 'IAB14-8', 'metadata' => array('parent' => 'IAB14')),
				array('text' => 'Astrology', 'value' => 'IAB15-1', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Biology', 'value' => 'IAB15-2', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Chemistry', 'value' => 'IAB15-3', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Geology', 'value' => 'IAB15-4', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Paranormal Phenomena', 'value' => 'IAB15-5', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Physics', 'value' => 'IAB15-6', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Space/Astronomy', 'value' => 'IAB15-7', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Geography', 'value' => 'IAB15-8', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Botany', 'value' => 'IAB15-9', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Weather', 'value' => 'IAB15-10', 'metadata' => array('parent' => 'IAB15')),
				array('text' => 'Aquariums', 'value' => 'IAB16-1', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Birds', 'value' => 'IAB16-2', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Cats', 'value' => 'IAB16-3', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Dogs', 'value' => 'IAB16-4', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Large Animals', 'value' => 'IAB16-5', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Reptiles', 'value' => 'IAB16-6', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Veterinary Medicine', 'value' => 'IAB16-7', 'metadata' => array('parent' => 'IAB16')),
				array('text' => 'Auto Racing', 'value' => 'IAB17-1', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Baseball', 'value' => 'IAB17-2', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Bicycling', 'value' => 'IAB17-3', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Bodybuilding', 'value' => 'IAB17-4', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Boxing', 'value' => 'IAB17-5', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Canoeing/Kayaking', 'value' => 'IAB17-6', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Cheerleading', 'value' => 'IAB17-7', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Climbing', 'value' => 'IAB17-8', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Cricket', 'value' => 'IAB17-9', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Figure Skating', 'value' => 'IAB17-10', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Fly Fishing', 'value' => 'IAB17-11', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Football', 'value' => 'IAB17-12', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Freshwater Fishing', 'value' => 'IAB17-13', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Game & Fish', 'value' => 'IAB17-14', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Golf', 'value' => 'IAB17-15', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Horse Racing', 'value' => 'IAB17-16', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Horses', 'value' => 'IAB17-17', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Hunting/Shooting', 'value' => 'IAB17-18', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Inline Skating', 'value' => 'IAB17-19', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Martial Arts', 'value' => 'IAB17-20', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Mountain Biking', 'value' => 'IAB17-21', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'NASCAR Racing', 'value' => 'IAB17-22', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Olympics', 'value' => 'IAB17-23', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Paintball', 'value' => 'IAB17-24', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Power & Motorcycles', 'value' => 'IAB17-25', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Pro Basketball', 'value' => 'IAB17-26', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Pro Ice Hockey', 'value' => 'IAB17-27', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Rodeo', 'value' => 'IAB17-28', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Rugby', 'value' => 'IAB17-29', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Running/Jogging', 'value' => 'IAB17-30', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Sailing', 'value' => 'IAB17-31', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Saltwater Fishing', 'value' => 'IAB17-32', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Scuba Diving', 'value' => 'IAB17-33', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Skateboarding', 'value' => 'IAB17-34', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Skiing', 'value' => 'IAB17-35', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Snowboarding', 'value' => 'IAB17-36', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Surfing/Bodyboarding', 'value' => 'IAB17-37', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Swimming', 'value' => 'IAB17-38', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Table Tennis/Ping-Pong', 'value' => 'IAB17-39', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Tennis', 'value' => 'IAB17-40', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Volleyball', 'value' => 'IAB17-41', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Walking', 'value' => 'IAB17-42', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Waterski/Wakeboard', 'value' => 'IAB17-43', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'World Soccer', 'value' => 'IAB17-44', 'metadata' => array('parent' => 'IAB17')),
				array('text' => 'Beauty', 'value' => 'IAB18-1', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Body Art', 'value' => 'IAB18-2', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Fashion', 'value' => 'IAB18-3', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Jewelry', 'value' => 'IAB18-4', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Clothing', 'value' => 'IAB18-5', 'metadata' => array('parent' => 'IAB18')),
				array('text' => 'Accessories', 'value' => 'IAB18-6', 'metadata' => array('parent' => 'IAB18')),
				array('text' => '3-D Graphics', 'value' => 'IAB19-1', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Animation', 'value' => 'IAB19-2', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Antivirus Software', 'value' => 'IAB19-3', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'C/C++', 'value' => 'IAB19-4', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Cameras & Camcorders', 'value' => 'IAB19-5', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Cell Phones', 'value' => 'IAB19-6', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Certification', 'value' => 'IAB19-7', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Networking', 'value' => 'IAB19-8', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Peripherals', 'value' => 'IAB19-9', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Computer Reviews', 'value' => 'IAB19-10', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Data Centers', 'value' => 'IAB19-11', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Databases', 'value' => 'IAB19-12', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Desktop Publishing', 'value' => 'IAB19-13', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Desktop Video', 'value' => 'IAB19-14', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Email', 'value' => 'IAB19-15', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Graphics Software', 'value' => 'IAB19-16', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Home Video/DVD', 'value' => 'IAB19-17', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Internet Technology', 'value' => 'IAB19-18', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Java', 'value' => 'IAB19-19', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'JavaScript', 'value' => 'IAB19-20', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Mac Support', 'value' => 'IAB19-21', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'MP3/MIDI', 'value' => 'IAB19-22', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Net Conferencing', 'value' => 'IAB19-23', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Net for Beginners', 'value' => 'IAB19-24', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Network Security', 'value' => 'IAB19-25', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Palmtops/PDAs', 'value' => 'IAB19-26', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'PC Support', 'value' => 'IAB19-27', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Portable', 'value' => 'IAB19-28', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Entertainment', 'value' => 'IAB19-29', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Shareware/Freeware', 'value' => 'IAB19-30', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Unix', 'value' => 'IAB19-31', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Visual Basic', 'value' => 'IAB19-32', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Web Clip Art', 'value' => 'IAB19-33', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Web Design/HTML', 'value' => 'IAB19-34', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Web Search', 'value' => 'IAB19-35', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Windows', 'value' => 'IAB19-36', 'metadata' => array('parent' => 'IAB19')),
				array('text' => 'Adventure Travel', 'value' => 'IAB20-1', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Africa', 'value' => 'IAB20-2', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Air Travel', 'value' => 'IAB20-3', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Australia & New Zealand', 'value' => 'IAB20-4', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Bed & Breakfasts', 'value' => 'IAB20-5', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Budget Travel', 'value' => 'IAB20-6', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Business Travel', 'value' => 'IAB20-7', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'By US Locale', 'value' => 'IAB20-8', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Camping', 'value' => 'IAB20-9', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Canada', 'value' => 'IAB20-10', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Caribbean', 'value' => 'IAB20-11', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Cruises', 'value' => 'IAB20-12', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Eastern Europe', 'value' => 'IAB20-13', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Europe', 'value' => 'IAB20-14', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'France', 'value' => 'IAB20-15', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Greece', 'value' => 'IAB20-16', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Honeymoons/Getaways', 'value' => 'IAB20-17', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Hotels', 'value' => 'IAB20-18', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Italy', 'value' => 'IAB20-19', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Japan', 'value' => 'IAB20-20', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Mexico & Central America', 'value' => 'IAB20-21', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'National Parks', 'value' => 'IAB20-22', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'South America', 'value' => 'IAB20-23', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Spas', 'value' => 'IAB20-24', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Theme Parks', 'value' => 'IAB20-25', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Traveling with Kids', 'value' => 'IAB20-26', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'United Kingdom', 'value' => 'IAB20-27', 'metadata' => array('parent' => 'IAB20')),
				array('text' => 'Apartments', 'value' => 'IAB21-1', 'metadata' => array('parent' => 'IAB21')),
				array('text' => 'Architects', 'value' => 'IAB21-2', 'metadata' => array('parent' => 'IAB21')),
				array('text' => 'Buying/Selling Homes', 'value' => 'IAB21-3', 'metadata' => array('parent' => 'IAB21')),
				array('text' => 'Contests & Freebies', 'value' => 'IAB22-1', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Couponing', 'value' => 'IAB22-2', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Comparison', 'value' => 'IAB22-3', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Engines', 'value' => 'IAB22-4', 'metadata' => array('parent' => 'IAB22')),
				array('text' => 'Alternative Religions', 'value' => 'IAB23-1', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Atheism/Agnosticism', 'value' => 'IAB23-2', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Buddhism', 'value' => 'IAB23-3', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Catholicism', 'value' => 'IAB23-4', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Christianity', 'value' => 'IAB23-5', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Hinduism', 'value' => 'IAB23-6', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Islam', 'value' => 'IAB23-7', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Judaism', 'value' => 'IAB23-8', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Latter-Day Saints', 'value' => 'IAB23-9', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Pagan/Wiccan', 'value' => 'IAB23-10', 'metadata' => array('parent' => 'IAB23')),
				array('text' => 'Unmoderated UGC', 'value' => 'IAB25-1', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Extreme Graphic/Explicit Violence', 'value' => 'IAB25-2', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Pornography', 'value' => 'IAB25-3', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Profane Content', 'value' => 'IAB25-4', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Hate Content', 'value' => 'IAB25-5', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Under Construction', 'value' => 'IAB25-6', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Incentivized', 'value' => 'IAB25-7', 'metadata' => array('parent' => 'IAB25')),
				array('text' => 'Illegal Content', 'value' => 'IAB26-1', 'metadata' => array('parent' => 'IAB26')),
				array('text' => 'Warez', 'value' => 'IAB26-2', 'metadata' => array('parent' => 'IAB26')),
				array('text' => 'Spyware/Malware', 'value' => 'IAB26-3', 'metadata' => array('parent' => 'IAB26')),
				array('text' => 'Copyright Infringement', 'value' => 'IAB26-4', 'metadata' => array('parent' => 'IAB26'))
			);
			foreach($IABChildCategories as $IABChildCategoryItem) {
				if($vicodeSettings['iab_category_child'] == $IABChildCategoryItem['value']) {
					$output .= '<label>IAB Category:</label><b>'.$IABChildCategoryItem['text'].'</b>';
				}
			}
		}

		$languages = insert_ads_vi_api_get_languages();
		if(isset($vicodeSettings['language']) && ($vicodeSettings['language'] != '') && ($vicodeSettings['language'] != 'select')) {
			if($languages != false) {
				foreach($languages as $key => $value) {
					if($vicodeSettings['language'] == $key) {
						$output .= '<label>Language:</label><b>'.$value.'</b>';
					}
				}
			}
		}
		
		if(isset($vicodeSettings['native_bg_color']) && ($vicodeSettings['native_bg_color'] != '')) {
			$output .= '<label>Native Background color:</label><b>'.$vicodeSettings['native_bg_color'].'</b>';
		}
		
		if(isset($vicodeSettings['native_text_color']) && ($vicodeSettings['native_text_color'] != '')) {
			$output .= '<label>Native Text color:</label><b>'.$vicodeSettings['native_text_color'].'</b>';
		}
		
		if(isset($vicodeSettings['font_family']) && ($vicodeSettings['font_family'] != '') && ($vicodeSettings['font_family'] != 'select')) {
			$fontFamily =array(
				array('text' => 'Select', 'value' => 'select'),
				array('text' => 'Georgia', 'value' => 'Georgia'),
				array('text' => 'Palatino Linotype', 'value' => 'Palatino Linotype'),
				array('text' => 'Times New Roman', 'value' => 'Times New Roman'),
				array('text' => 'Arial', 'value' => 'Arial'),
				array('text' => 'Arial Black', 'value' => 'Arial Black'),
				array('text' => 'Comic Sans MS', 'value' => 'Comic Sans MS'),
				array('text' => 'Impact', 'value' => 'Impact'),
				array('text' => 'Lucida Sans Unicode', 'value' => 'Lucida Sans Unicode'),
				array('text' => 'Tahoma', 'value' => 'Tahoma'),
				array('text' => 'Trebuchet MS', 'value' => 'Trebuchet MS'),
				array('text' => 'Verdana', 'value' => 'Verdana'),
				array('text' => 'Courier New', 'value' => 'Courier New'),
				array('text' => 'Lucida Console', 'value' => 'Lucida Console')
			);
			foreach($fontFamily as $fontFamilyItem) {
				if($vicodeSettings['font_family'] == $fontFamilyItem['value']) {
					$output .= '<label>Native Text Font Family:</label><b>'.$fontFamilyItem['text'].'</b>';
				}
			}
		}
		
		if(isset($vicodeSettings['font_size']) && ($vicodeSettings['font_size'] != '') && ($vicodeSettings['font_size'] != 'select')) {
			$fontSize =array(
				array('text' => 'Select', 'value' => 'select'),
				array('text' => '8px', 'value' => '8'),
				array('text' => '9px', 'value' => '9'),
				array('text' => '10px', 'value' => '10'),
				array('text' => '11px', 'value' => '11'),
				array('text' => '12px', 'value' => '12'),
				array('text' => '14px', 'value' => '14'),
				array('text' => '16px', 'value' => '16'),
				array('text' => '18px', 'value' => '18'),
				array('text' => '20px', 'value' => '20'),
				array('text' => '22px', 'value' => '22'),
				array('text' => '24px', 'value' => '24'),
				array('text' => '26px', 'value' => '26'),
				array('text' => '28px', 'value' => '28'),
				array('text' => '36px', 'value' => '36')
			);	
			foreach($fontSize as $fontSizeItem) {
				if($vicodeSettings['font_size'] == $fontSizeItem['value']) {
					$output .= '<label>Native Text Font Size:</label><b>'.$fontSizeItem['text'].'</b>';
				}
			}
		}
		
		if(isset($vicodeSettings['optional_1']) && ($vicodeSettings['optional_1'] != '')) {
			$output .= '<label>Optional 1:</label><b>'.$vicodeSettings['optional_1'].'</b>';
		}
		if(isset($vicodeSettings['optional_2']) && ($vicodeSettings['optional_2'] != '')) {
			$output .= '<label>Optional 2:</label><b>'.$vicodeSettings['optional_1'].'</b>';
		}
		if(isset($vicodeSettings['optional_3']) && ($vicodeSettings['optional_3'] != '')) {
			$output .= '<label>Optional 3:</label><b>'.$vicodeSettings['optional_1'].'</b>';
		}
		$output .= '</p>';
	}
	return $output;
}
/* End Configure vi Code */
?>