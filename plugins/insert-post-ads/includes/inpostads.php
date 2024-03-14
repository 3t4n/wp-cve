<?php
/* Begin Add Card in Admin Panel */
add_action('insert_ads_plugin_card', 'insert_ads_inpostads_plugin_card', 10);
function insert_ads_inpostads_plugin_card() {
	echo '<div class="plugin-card">';
		echo '<h3 class="hndle">In-Post Ads</h3>';
		echo '<div class="plugin-card-bottom">';
			echo '<p class="aslabel">Ads shown</p>';
			//echo '<div class="vi-ct"><p><a id="insert_ads_inpostads_above" href="javascript:;">Ad - Above Post Content</a></p>';
			//echo '<p><a id="insert_ads_inpostads_middle" href="javascript:;">Ad - Middle of Post Content</a></p>';
			//echo '<p><a id="insert_ads_inpostads_below" href="javascript:;">Ad - Below Post Content</a></p></div>';
			//echo '<div class="vi-ct"><p><a id="insert_ads_inpostads_left" href="javascript:;">Ad - Left of Post Content</a></p>';
			//echo '<p><a id="insert_ads_inpostads_right" href="javascript:;">Ad - Right of Post Content</a></p></div>';
			echo '<p id="cur-pos-sel">';
			echo '<input type="radio" checked name="cur-pos-rad" value="above" id="cur-pos-above"><label for="cur-pos-above" style="margin-right: 15px;">Ad - Above Post Content</label>';
			echo '<input type="radio" name="cur-pos-rad" value="middle" id="cur-pos-middle"><label for="cur-pos-middle">Ad - Middle of Post Content</label>';
			echo '</p>';
			echo '<div style="clear:both;"></div>';
			insert_ads_inpostads_form_get_content('above');
			insert_ads_inpostads_form_get_content('middle');
		echo '</div>';
	echo '</div>';
	echo '<input type="hidden" id="insert_ads_admin_ajax" name="insert_ads_admin_ajax" value="' . admin_url('admin-ajax.php') . '" /><input type="hidden" id="insert_ads_nonce" name="insert_ads_nonce" value="' . wp_create_nonce('insert-ads') . '" />';
}
/* End Add Card in Admin Panel */
 
/* Begin Ad Above Post Content */
add_action('wp_ajax_insert_ads_inpostads_above_form_get_content', 'insert_ads_inpostads_above_form_get_content');
function insert_ads_inpostads_above_form_get_content() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	insert_ads_inpostads_form_get_content('above');
	die();
}

add_action('wp_ajax_insert_ads_inpostads_above_form_save_action', 'insert_ads_inpostads_above_form_save_action');
function insert_ads_inpostads_above_form_save_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');	
	insert_ads_inpostads_form_save_action('above');
	die();
}
/* End Ad Above Post Content */

/* Begin Ad Middle Post Content */
add_action('wp_ajax_insert_ads_inpostads_middle_form_get_content', 'insert_ads_inpostads_middle_form_get_content');
function insert_ads_inpostads_middle_form_get_content() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	insert_ads_inpostads_form_get_content('middle');
	die();
}

add_action('wp_ajax_insert_ads_inpostads_middle_form_save_action', 'insert_ads_inpostads_middle_form_save_action');
function insert_ads_inpostads_middle_form_save_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');	
	insert_ads_inpostads_form_save_action('middle');
	die();
}
/* End Ad Middle Post Content */

/* Begin Ad Below Post Content */
add_action('wp_ajax_insert_ads_inpostads_below_form_get_content', 'insert_ads_inpostads_below_form_get_content');
function insert_ads_inpostads_below_form_get_content() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	insert_ads_inpostads_form_get_content('below');
	die();
}

add_action('wp_ajax_insert_ads_inpostads_below_form_save_action', 'insert_ads_inpostads_below_form_save_action');
function insert_ads_inpostads_below_form_save_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');	
	insert_ads_inpostads_form_save_action('below');
	die();
}
/* End Ad Below Post Content */

/* Begin Ad Left Post Content */
add_action('wp_ajax_insert_ads_inpostads_left_form_get_content', 'insert_ads_inpostads_left_form_get_content');
function insert_ads_inpostads_left_form_get_content() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	insert_ads_inpostads_form_get_content('left');
	die();
}

add_action('wp_ajax_insert_ads_inpostads_left_form_save_action', 'insert_ads_inpostads_left_form_save_action');
function insert_ads_inpostads_left_form_save_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');	
	insert_ads_inpostads_form_save_action('left');
	die();
}
/* End Ad Left Post Content */

/* Begin Ad Right Post Content */
add_action('wp_ajax_insert_ads_inpostads_right_form_get_content', 'insert_ads_inpostads_right_form_get_content');
function insert_ads_inpostads_right_form_get_content() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');
	insert_ads_inpostads_form_get_content('right');
	die();
}

add_action('wp_ajax_insert_ads_inpostads_right_form_save_action', 'insert_ads_inpostads_right_form_save_action');
function insert_ads_inpostads_right_form_save_action() {
	check_ajax_referer('insert-ads', 'insert_ads_nonce');	
	insert_ads_inpostads_form_save_action('right');
	die();
}
/* End Ad Right Post Content */





/* Begin Shared UI Functions */
function insert_ads_inpostads_form_get_content($position) {
	$inpostads = get_option('insert_ads_inpostads');
	echo '<div class="insert_ads_popup_content_wrapper">';
		$posts_per_page = get_option('posts_per_page');
		$instances = array();
		for($i = 1; $i <= $posts_per_page; $i++) {
			$instances[] = array('text' => 'Hide on '.insert_ads_add_ordinal_number_suffix($i).' Post', 'value' => $i);
		}
		$control = new smartlogix(array('optionIdentifier' => 'insert_ads_inpostads['.$position.']', 'values' => $inpostads[$position]));
		$control->add_control(array('type' => 'ipCheckbox', 'optionName' => 'status'));
		echo '<div id="insert_ads_inpostads_'.$position.'_accordion">';
			echo '<h3>Ad Code</h3>';
			echo '<div>';
				$abtestingMode = get_option('insert_ads_abtesting_mode');
				
				if($position == 'above' || $position == 'middle') {
					$adTypes = array(
						array('text' => 'Use Generic / Custom Ad Code', 'value' => 'generic'),
						array('text' => 'vi stories', 'value' => 'vicode'),
					);
					echo '<span class="aslabel" style="padding: 11px 0;">Activate vi stories</span>';
					$control->add_control(array('type' => 'select', 'label' => 'Ad Type', 'optionName' => 'primary_ad_code_type', 'options' => $adTypes));
					echo $control->HTML;
					$control->clear_controls();
					//echo '<p data-pos="'.$position.'" class="notify-'.$position.' funcnot"></p>';
					
					/*$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'primary_ad_code'));
					$control->create_section('<span id="primary_ad_code_type_generic" class="isSelectedIndicator"></span>Generic / Custom Ad Code (Primary Network)');
					$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div>');
					echo $control->HTML;
					$control->clear_controls();*/
					
					/*$IsVILoggedin = insert_ads_vi_api_is_loggedin();
					$isJSTagGenerated = ((insert_ads_vi_api_get_vi_code() === false)?false:true);
					$isVIDisabled = false;
					$viMessage = '';
					if(!$IsVILoggedin && !$isJSTagGenerated) {
						$isVIDisabled = true;
						$viMessage = '<p>Introducing <b>vi stories</b> – the video content and advertising player.</p>';
						$viMessage .= '<p>Before you can use <b>vi stories</b>, you must configure it. Once you’ve signed up, in the <i>video intelligence</i> panel, click <i>Sign in</i> then click <i>Configure</i></p>';
					} else if($IsVILoggedin && !$isJSTagGenerated) {
						$isVIDisabled = true;
						$viMessage .= '<p>Before you can use <b>vi stories</b>, you must configure it. In the <i>video intelligence</i> panel, click <i>Configure</i></p>';
						//$viMessage .= '<p><a id="insert_ads_inpostads_vi_customize_adcode" href="javascript:;" class="button button-primary aligncenter">Configure vi Code</a></p>'; /*Button being temporarily removed to avoid confusion for users*/
					/*} else if(!$IsVILoggedin && $isJSTagGenerated) {
						$isVIDisabled = false;
						$viMessage = '<p>Before you can use <b>vi stories</b>, you must configure it. Once you’ve signed up, in the <i>video intelligence</i> panel, click <i>Sign in</i> then click <i>Configure</i></p>';
					} else {
						$isVIDisabled = false;
						$viMessage = insert_ads_vi_customize_adcode_get_settings();
						$viMessage .= '<p>To configure <b>vi stories</b>, go to the <i>video intelligence</i> panel, click <i>Configure</i></p>';
						//$viMessage .= '<p><a id="insert_ads_inpostads_vi_customize_adcode" href="javascript:;" class="button button-primary aligncenter">Configure vi Code</a></p>'; /*Button being temporarily removed to avoid confusion for users*/
					//}
					
					/*$control->HTML .= $viMessage;
					$control->create_section('<span id="primary_ad_code_type_vicode" class="isSelectedIndicator '.(($isVIDisabled)?'disabled':'').'"></span>vi stories (Primary Network)');
					$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
					echo $control->HTML;
					$control->clear_controls();*/
				} else {
					/*$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'primary_ad_code'));
					$control->create_section('Ad Code (Primary Network)');
					echo $control->HTML;
					$control->clear_controls();*/
				}
				
				/*$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'secondary_ad_code'));
				$control->create_section('Ad Code (Secondary Network)');
				if($abtestingMode != '2' && $abtestingMode != '3') {	
					$control->set_HTML('<div style="display: none;">'.$control->HTML.'</div>');
				}
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'tertiary_ad_code'));
				$control->create_section('Ad Code (Tertiary Network)');
				if($abtestingMode != '3') {	
					$control->set_HTML('<div style="display: none;">'.$control->HTML.'</div>');
				}
				echo $control->HTML;
				$control->clear_controls();*/
			echo '</div>';
			/*echo '<h3>Rules</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_loggedin'));
				$control->create_section('Logged in Users');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_mobile_devices'));
				$control->create_section('Mobile Devices');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_404'));
				$control->create_section('404 Pages');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_home'));
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_home_instances', 'options' => $instances));
				$control->create_section('Home');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_archives'));
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_archives_instances', 'options' => $instances));
				$control->create_section('Archives');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_search'));
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_search_instances', 'options' => $instances));
				$control->create_section('Search Results');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_page'));
				$control->add_control(array('type' => 'pages-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_page_exceptions'));
				$control->create_section('Single Pages');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_post'));
				$control->add_control(array('type' => 'posts-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_post_exceptions'));
				$control->add_control(array('type' => 'categories-chosen-multiselect', 'label' => 'Category Exceptions', 'optionName' => 'rules_post_categories_exceptions'));
				$control->create_section('Single Posts');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_categories'));
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_categories_instances', 'options' => $instances));
				$control->add_control(array('type' => 'categories-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_categories_exceptions'));
				$control->create_section('Category Archives');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
			echo '</div>';
			echo '<h3>Geo Targeting</h3>';
			echo '<div>';
				echo '<p>';
					echo 'A Geo Targeted Ads have a higher priority than Ads configured via Multiple Ad Networks / A-B Testing.<br />';
					echo 'If a Geo Targeting match is found all other Ads (Primary, Secondary and Tertiary Networks) will be ignored.<br />';
				echo '</p>';
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Countries', 'optionName' => 'geo_group1_countries', 'options' => insert_ads_get_countries()));
				$control->add_control(array('type' => 'textarea', 'label' => 'Ad Code', 'style' => 'height: 220px;', 'optionName' => 'geo_group1_adcode'));
				$control->create_section('Group 1');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Countries', 'optionName' => 'geo_group2_countries', 'options' => insert_ads_get_countries()));
				$control->add_control(array('type' => 'textarea', 'label' => 'Ad Code', 'style' => 'height: 220px;', 'optionName' => 'geo_group2_adcode'));
				$control->create_section('Group 2');
				$control->set_HTML('<div class="insert_ads_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				echo '<p>';
					echo 'This feature uses the Free Geo ip service from <a href="http://freegeoip.net/">freegeoip.net</a>, if you find this feature useful please consider donating to the project at <a href="http://freegeoip.net/">freegeoip.net</a>';
				echo '</p>';
			echo '</div>';
			echo '<h3>Styles</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'styles'));
				$control->create_section('Styles');
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Notes</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'notes'));
				$control->create_section('Notes');
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';*/
			/*if($position == 'middle') {
				echo '<h3>Positioning</h3>';
				echo '<div>';
					$control->add_control(array('type' => 'text', 'label' => 'Minimum Character Count', 'optionName' => 'minimum_character_count', 'helpText' => 'Show the ad only if the Content meets the minimum character count. If this parameter is set to 0 (or empty) minimum character count check will be deactivated.'));
					$control->add_control(array('type' => 'text', 'label' => 'Paragraph Buffer Count', 'optionName' => 'paragraph_buffer_count', 'helpText' => 'Shows the ad after X number of Paragraphs. If this parameter is set to 0 (or empty) the ad will appear in the middle of the content.'));
					$control->create_section('Positioning');
					echo $control->HTML;
					$control->clear_controls();
				echo '</div>';
			}*/
		//echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			//echo 'jQuery("#insert_ads_inpostads_'.$position.'_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "auto" });';
			//echo 'primary_ad_code_type_change();';
			//echo 'insert_ads_inpostads_vi_customize_adcode();';
		echo '</script>';
	echo '</div></div>';
}

function insert_ads_inpostads_form_save_action($position) {
	$inpostAds = get_option('insert_ads_inpostads');
	$inpostAds[$position]['status'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_status']) && ($_POST['insert_ads_inpostads_'.$position.'_status'] == 'true'))?'1':'');
	
	if($position == 'above' || $position == 'middle') {
		$inpostAds[$position]['primary_ad_code_type'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_primary_ad_code_type']))?$_POST['insert_ads_inpostads_'.$position.'_primary_ad_code_type']:'');
	}
	$inpostAds[$position]['primary_ad_code'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_primary_ad_code']))?$_POST['insert_ads_inpostads_'.$position.'_primary_ad_code']:'');
	$inpostAds[$position]['secondary_ad_code'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_secondary_ad_code']))?$_POST['insert_ads_inpostads_'.$position.'_secondary_ad_code']:'');
	$inpostAds[$position]['tertiary_ad_code'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_tertiary_ad_code']))?$_POST['insert_ads_inpostads_'.$position.'_tertiary_ad_code']:'');
	
	$inpostAds[$position]['rules_exclude_loggedin'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_loggedin']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_loggedin']:'');
	$inpostAds[$position]['rules_exclude_mobile_devices'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_mobile_devices']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_mobile_devices']:'');
	$inpostAds[$position]['rules_exclude_404'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_404']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_404']:'');
	$inpostAds[$position]['rules_exclude_home'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_home']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_home']:'');
	$inpostAds[$position]['rules_home_instances'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_home_instances']))?$_POST['insert_ads_inpostads_'.$position.'_rules_home_instances']:'');
	$inpostAds[$position]['rules_exclude_archives'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_archives']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_archives']:'');
	$inpostAds[$position]['rules_archives_instances'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_archives_instances']))?$_POST['insert_ads_inpostads_'.$position.'_rules_archives_instances']:'');
	$inpostAds[$position]['rules_exclude_search'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_search']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_search']:'');
	$inpostAds[$position]['rules_search_instances'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_search_instances']))?$_POST['insert_ads_inpostads_'.$position.'_rules_search_instances']:'');
	$inpostAds[$position]['rules_exclude_page'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_page']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_page']:'');
	$inpostAds[$position]['rules_page_exceptions'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_page_exceptions']))?$_POST['insert_ads_inpostads_'.$position.'_rules_page_exceptions']:'');
	$inpostAds[$position]['rules_exclude_post'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_post']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_post']:'');
	$inpostAds[$position]['rules_post_exceptions'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_post_exceptions']))?$_POST['insert_ads_inpostads_'.$position.'_rules_post_exceptions']:'');
	$inpostAds[$position]['rules_post_categories_exceptions'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_post_categories_exceptions']))?$_POST['insert_ads_inpostads_'.$position.'_rules_post_categories_exceptions']:'');
	$inpostAds[$position]['rules_exclude_categories'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_exclude_categories']))?$_POST['insert_ads_inpostads_'.$position.'_rules_exclude_categories']:'');
	$inpostAds[$position]['rules_categories_instances'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_categories_instances']))?$_POST['insert_ads_inpostads_'.$position.'_rules_categories_instances']:'');
	$inpostAds[$position]['rules_categories_exceptions'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_rules_categories_exceptions']))?$_POST['insert_ads_inpostads_'.$position.'_rules_categories_exceptions']:'');
	
	$inpostAds[$position]['geo_group1_countries'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_geo_group1_countries']))?$_POST['insert_ads_inpostads_'.$position.'_geo_group1_countries']:'');
	$inpostAds[$position]['geo_group1_adcode'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_geo_group1_adcode']))?$_POST['insert_ads_inpostads_'.$position.'_geo_group1_adcode']:'');
	$inpostAds[$position]['geo_group2_countries'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_geo_group2_countries']))?$_POST['insert_ads_inpostads_'.$position.'_geo_group2_countries']:'');
	$inpostAds[$position]['geo_group2_adcode'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_geo_group2_adcode']))?$_POST['insert_ads_inpostads_'.$position.'_geo_group2_adcode']:'');
	
	$inpostAds[$position]['styles'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_styles']))?$_POST['insert_ads_inpostads_'.$position.'_styles']:'');
	
	$inpostAds[$position]['notes'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_notes']))?$_POST['insert_ads_inpostads_'.$position.'_notes']:'');
	
	if($position == 'middle') {
		$inpostAds[$position]['minimum_character_count'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_minimum_character_count']))?$_POST['insert_ads_inpostads_'.$position.'_minimum_character_count']:'');
		$inpostAds[$position]['paragraph_buffer_count'] = ((isset($_POST['insert_ads_inpostads_'.$position.'_paragraph_buffer_count']))?$_POST['insert_ads_inpostads_'.$position.'_paragraph_buffer_count']:'');
	}
	update_option('insert_ads_inpostads', $inpostAds);
	
	echo $inpostAds['above']['status'];
}
/* End Shared UI Functions */

/* Begin Database Upgrade */
add_action('insert_ads_upgrade_database', 'insert_ads_inpostads_upgrade_database');
function insert_ads_inpostads_upgrade_database() {	
	if(!get_option('insert_ads_inpostads')) {
		$oldValues = get_option('insert_ads_inpostads_options');
		$newValues = array(
			'above' => array (
				'status' => ((isset($oldValues['above']['status']) && $oldValues['above']['status'] == true)?'1':''),
				'primary_ad_code' => ((isset($oldValues['above']['ad_code_1']))?$oldValues['above']['ad_code_1']:''),
				'secondary_ad_code' => ((isset($oldValues['above']['ad_code_2']))?$oldValues['above']['ad_code_2']:''),
				'tertiary_ad_code' => ((isset($oldValues['above']['ad_code_3']))?$oldValues['above']['ad_code_3']:''),
				'rules_exclude_loggedin' => ((isset($oldValues['above']['rules_exclude_loggedin']))?true:false),
				'rules_exclude_mobile_devices' => ((isset($oldValues['above']['rules_exclude_mobile_devices']))?true:false),
				'rules_exclude_home' => ((isset($oldValues['above']['rules_exclude_home']))?true:false),
				'rules_home_instances' => ((isset($options['above']['rules_home_instances']) && ($options['above']['rules_home_instances'] != ''))?explode(',', $options['above']['rules_home_instances']):array()),
				'rules_exclude_archives' => ((isset($oldValues['above']['rules_exclude_archives']))?true:false),
				'rules_archives_instances' => ((isset($options['above']['rules_archives_instances']) && ($options['above']['rules_archives_instances'] != ''))?explode(',', $options['above']['rules_archives_instances']):array()),
				'rules_exclude_search' => ((isset($oldValues['above']['rules_exclude_search']))?true:false),
				'rules_search_instances' => ((isset($options['above']['rules_search_instances']) && ($options['above']['rules_search_instances'] != ''))?explode(',', $options['above']['rules_search_instances']):array()),
				'rules_exclude_page' => ((isset($oldValues['above']['rules_exclude_page']))?true:false),
				'rules_page_exceptions' => ((isset($options['above']['rules_page_exceptions']) && ($options['above']['rules_page_exceptions'] != ''))?explode(',', $options['above']['rules_page_exceptions']):array()),
				'rules_exclude_post' => ((isset($oldValues['above']['rules_exclude_post']))?true:false),
				'rules_post_exceptions' => ((isset($options['above']['rules_post_exceptions']) && ($options['above']['rules_post_exceptions'] != ''))?explode(',', $options['above']['rules_post_exceptions']):array()),
				'rules_post_categories_exceptions' => ((isset($options['above']['rules_post_categories_exceptions']) && ($options['above']['rules_post_categories_exceptions'] != ''))?explode(',', $options['above']['rules_post_categories_exceptions']):array()),
				'rules_exclude_categories' => ((isset($oldValues['above']['rules_exclude_categories']))?true:false),
				'rules_categories_instances' => ((isset($options['above']['rules_categories_instances']) && ($options['above']['rules_categories_instances'] != ''))?explode(',', $options['above']['rules_categories_instances']):array()),
				'rules_categories_exceptions' => ((isset($options['above']['rules_categories_exceptions']) && ($options['above']['rules_categories_exceptions'] != ''))?explode(',', $options['above']['rules_categories_exceptions']):array()),
				'geo_group1_countries' => ((isset($options['above']['country_1']) && ($options['above']['country_1'] != ''))?explode(',', $options['above']['country_1']):array()),
				'geo_group1_adcode' => ((isset($oldValues['above']['country_code_1']))?$oldValues['above']['country_code_1']:''),
				'geo_group2_countries' => array(),
				'geo_group2_adcode' => '',
				'styles' => ((isset($oldValues['above']['styles']))?$oldValues['above']['styles']:'margin: 5px; padding: 0px;'),
				'notes' => ((isset($oldValues['above']['notes']))?$oldValues['above']['notes']:''),
			),
			'middle' => array (
				'status' => ((isset($oldValues['middle']['status']) && $oldValues['middle']['status'] == true)?'1':''),
				'primary_ad_code' => ((isset($oldValues['middle']['ad_code_1']))?$oldValues['middle']['ad_code_1']:''),
				'secondary_ad_code' => ((isset($oldValues['middle']['ad_code_2']))?$oldValues['middle']['ad_code_2']:''),
				'tertiary_ad_code' => ((isset($oldValues['middle']['ad_code_3']))?$oldValues['middle']['ad_code_3']:''),
				'rules_exclude_loggedin' => ((isset($oldValues['middle']['rules_exclude_loggedin']))?true:false),
				'rules_exclude_mobile_devices' => ((isset($oldValues['middle']['rules_exclude_mobile_devices']))?true:false),
				'rules_exclude_home' => ((isset($oldValues['middle']['rules_exclude_home']))?true:false),
				'rules_home_instances' => ((isset($options['middle']['rules_home_instances']) && ($options['middle']['rules_home_instances'] != ''))?explode(',', $options['middle']['rules_home_instances']):array()),
				'rules_exclude_archives' => ((isset($oldValues['middle']['rules_exclude_archives']))?true:false),
				'rules_archives_instances' => ((isset($options['middle']['rules_archives_instances']) && ($options['middle']['rules_archives_instances'] != ''))?explode(',', $options['middle']['rules_archives_instances']):array()),
				'rules_exclude_search' => ((isset($oldValues['middle']['rules_exclude_search']))?true:false),
				'rules_search_instances' => ((isset($options['middle']['rules_search_instances']) && ($options['middle']['rules_search_instances'] != ''))?explode(',', $options['middle']['rules_search_instances']):array()),
				'rules_exclude_page' => ((isset($oldValues['middle']['rules_exclude_page']))?true:false),
				'rules_page_exceptions' => ((isset($options['middle']['rules_page_exceptions']) && ($options['middle']['rules_page_exceptions'] != ''))?explode(',', $options['middle']['rules_page_exceptions']):array()),
				'rules_exclude_post' => ((isset($oldValues['middle']['rules_exclude_post']))?true:false),
				'rules_post_exceptions' => ((isset($options['middle']['rules_post_exceptions']) && ($options['middle']['rules_post_exceptions'] != ''))?explode(',', $options['middle']['rules_post_exceptions']):array()),
				'rules_post_categories_exceptions' => ((isset($options['middle']['rules_post_categories_exceptions']) && ($options['middle']['rules_post_categories_exceptions'] != ''))?explode(',', $options['middle']['rules_post_categories_exceptions']):array()),
				'rules_exclude_categories' => ((isset($oldValues['middle']['rules_exclude_categories']))?true:false),
				'rules_categories_instances' => ((isset($options['middle']['rules_categories_instances']) && ($options['middle']['rules_categories_instances'] != ''))?explode(',', $options['middle']['rules_categories_instances']):array()),
				'rules_categories_exceptions' => ((isset($options['middle']['rules_categories_exceptions']) && ($options['middle']['rules_categories_exceptions'] != ''))?explode(',', $options['middle']['rules_categories_exceptions']):array()),
				'geo_group1_countries' => ((isset($options['middle']['country_1']) && ($options['middle']['country_1'] != ''))?explode(',', $options['middle']['country_1']):array()),
				'geo_group1_adcode' => ((isset($oldValues['middle']['country_code_1']))?$oldValues['middle']['country_code_1']:''),
				'geo_group2_countries' => array(),
				'geo_group2_adcode' => '',
				'styles' => ((isset($oldValues['middle']['styles']))?$oldValues['middle']['styles']:'margin: 5px; padding: 0px;'),
				'notes' => ((isset($oldValues['middle']['notes']))?$oldValues['middle']['notes']:''),
				'minimum_character_count' => ((isset($oldValues['middle']['minimum_character_count']))?$oldValues['middle']['minimum_character_count']:'500'),
				'paragraph_buffer_count' => ((isset($oldValues['middle']['paragraph_buffer_count']))?$oldValues['middle']['paragraph_buffer_count']:''),
			),
			'below' => array (
				'status' => ((isset($oldValues['below']['status']) && $oldValues['below']['status'] == true)?'1':''),
				'primary_ad_code' => ((isset($oldValues['below']['ad_code_1']))?$oldValues['below']['ad_code_1']:''),
				'secondary_ad_code' => ((isset($oldValues['below']['ad_code_2']))?$oldValues['below']['ad_code_2']:''),
				'tertiary_ad_code' => ((isset($oldValues['below']['ad_code_3']))?$oldValues['below']['ad_code_3']:''),
				'rules_exclude_loggedin' => ((isset($oldValues['below']['rules_exclude_loggedin']))?true:false),
				'rules_exclude_mobile_devices' => ((isset($oldValues['below']['rules_exclude_mobile_devices']))?true:false),
				'rules_exclude_home' => ((isset($oldValues['below']['rules_exclude_home']))?true:false),
				'rules_home_instances' => ((isset($options['below']['rules_home_instances']) && ($options['below']['rules_home_instances'] != ''))?explode(',', $options['below']['rules_home_instances']):array()),
				'rules_exclude_archives' => ((isset($oldValues['below']['rules_exclude_archives']))?true:false),
				'rules_archives_instances' => ((isset($options['below']['rules_archives_instances']) && ($options['below']['rules_archives_instances'] != ''))?explode(',', $options['below']['rules_archives_instances']):array()),
				'rules_exclude_search' => ((isset($oldValues['below']['rules_exclude_search']))?true:false),
				'rules_search_instances' => ((isset($options['below']['rules_search_instances']) && ($options['below']['rules_search_instances'] != ''))?explode(',', $options['below']['rules_search_instances']):array()),
				'rules_exclude_page' => ((isset($oldValues['below']['rules_exclude_page']))?true:false),
				'rules_page_exceptions' => ((isset($options['below']['rules_page_exceptions']) && ($options['below']['rules_page_exceptions'] != ''))?explode(',', $options['below']['rules_page_exceptions']):array()),
				'rules_exclude_post' => ((isset($oldValues['below']['rules_exclude_post']))?true:false),
				'rules_post_exceptions' => ((isset($options['below']['rules_post_exceptions']) && ($options['below']['rules_post_exceptions'] != ''))?explode(',', $options['below']['rules_post_exceptions']):array()),
				'rules_post_categories_exceptions' => ((isset($options['below']['rules_post_categories_exceptions']) && ($options['below']['rules_post_categories_exceptions'] != ''))?explode(',', $options['below']['rules_post_categories_exceptions']):array()),
				'rules_exclude_categories' => ((isset($oldValues['below']['rules_exclude_categories']))?true:false),
				'rules_categories_instances' => ((isset($options['below']['rules_categories_instances']) && ($options['below']['rules_categories_instances'] != ''))?explode(',', $options['below']['rules_categories_instances']):array()),
				'rules_categories_exceptions' => ((isset($options['below']['rules_categories_exceptions']) && ($options['below']['rules_categories_exceptions'] != ''))?explode(',', $options['below']['rules_categories_exceptions']):array()),
				'geo_group1_countries' => ((isset($options['below']['country_1']) && ($options['below']['country_1'] != ''))?explode(',', $options['below']['country_1']):array()),
				'geo_group1_adcode' => ((isset($oldValues['below']['country_code_1']))?$oldValues['below']['country_code_1']:''),
				'geo_group2_countries' => array(),
				'geo_group2_adcode' => '',
				'styles' => ((isset($oldValues['below']['styles']))?$oldValues['below']['styles']:'margin: 5px; padding: 0px;'),
				'notes' => ((isset($oldValues['below']['notes']))?$oldValues['below']['notes']:''),
			),
			'left' => array (
				'status' => ((isset($oldValues['left']['status']) && $oldValues['left']['status'] == true)?'1':''),
				'primary_ad_code' => ((isset($oldValues['left']['ad_code_1']))?$oldValues['left']['ad_code_1']:''),
				'secondary_ad_code' => ((isset($oldValues['left']['ad_code_2']))?$oldValues['left']['ad_code_2']:''),
				'tertiary_ad_code' => ((isset($oldValues['left']['ad_code_3']))?$oldValues['left']['ad_code_3']:''),
				'rules_exclude_loggedin' => ((isset($oldValues['left']['rules_exclude_loggedin']))?true:false),
				'rules_exclude_mobile_devices' => ((isset($oldValues['left']['rules_exclude_mobile_devices']))?true:false),
				'rules_exclude_home' => ((isset($oldValues['left']['rules_exclude_home']))?true:false),
				'rules_home_instances' => ((isset($options['left']['rules_home_instances']) && ($options['left']['rules_home_instances'] != ''))?explode(',', $options['left']['rules_home_instances']):array()),
				'rules_exclude_archives' => ((isset($oldValues['left']['rules_exclude_archives']))?true:false),
				'rules_archives_instances' => ((isset($options['left']['rules_archives_instances']) && ($options['left']['rules_archives_instances'] != ''))?explode(',', $options['left']['rules_archives_instances']):array()),
				'rules_exclude_search' => ((isset($oldValues['left']['rules_exclude_search']))?true:false),
				'rules_search_instances' => ((isset($options['left']['rules_search_instances']) && ($options['left']['rules_search_instances'] != ''))?explode(',', $options['left']['rules_search_instances']):array()),
				'rules_exclude_page' => ((isset($oldValues['left']['rules_exclude_page']))?true:false),
				'rules_page_exceptions' => ((isset($options['left']['rules_page_exceptions']) && ($options['left']['rules_page_exceptions'] != ''))?explode(',', $options['left']['rules_page_exceptions']):array()),
				'rules_exclude_post' => ((isset($oldValues['left']['rules_exclude_post']))?true:false),
				'rules_post_exceptions' => ((isset($options['left']['rules_post_exceptions']) && ($options['left']['rules_post_exceptions'] != ''))?explode(',', $options['left']['rules_post_exceptions']):array()),
				'rules_post_categories_exceptions' => ((isset($options['left']['rules_post_categories_exceptions']) && ($options['left']['rules_post_categories_exceptions'] != ''))?explode(',', $options['left']['rules_post_categories_exceptions']):array()),
				'rules_exclude_categories' => ((isset($oldValues['left']['rules_exclude_categories']))?true:false),
				'rules_categories_instances' => ((isset($options['left']['rules_categories_instances']) && ($options['left']['rules_categories_instances'] != ''))?explode(',', $options['left']['rules_categories_instances']):array()),
				'rules_categories_exceptions' => ((isset($options['left']['rules_categories_exceptions']) && ($options['left']['rules_categories_exceptions'] != ''))?explode(',', $options['left']['rules_categories_exceptions']):array()),
				'geo_group1_countries' => ((isset($options['left']['country_1']) && ($options['left']['country_1'] != ''))?explode(',', $options['left']['country_1']):array()),
				'geo_group1_adcode' => ((isset($oldValues['left']['country_code_1']))?$oldValues['left']['country_code_1']:''),
				'geo_group2_countries' => array(),
				'geo_group2_adcode' => '',
				'styles' => ((isset($oldValues['left']['styles']))?$oldValues['left']['styles']:'margin: 5px; padding: 0px;'),
				'notes' => ((isset($oldValues['left']['notes']))?$oldValues['left']['notes']:''),
			),
			'right' => array (
				'status' => ((isset($oldValues['right']['status']) && $oldValues['right']['status'] == true)?'1':''),
				'primary_ad_code' => ((isset($oldValues['right']['ad_code_1']))?$oldValues['right']['ad_code_1']:''),
				'secondary_ad_code' => ((isset($oldValues['right']['ad_code_2']))?$oldValues['right']['ad_code_2']:''),
				'tertiary_ad_code' => ((isset($oldValues['right']['ad_code_3']))?$oldValues['right']['ad_code_3']:''),
				'rules_exclude_loggedin' => ((isset($oldValues['right']['rules_exclude_loggedin']))?true:false),
				'rules_exclude_mobile_devices' => ((isset($oldValues['right']['rules_exclude_mobile_devices']))?true:false),
				'rules_exclude_home' => ((isset($oldValues['right']['rules_exclude_home']))?true:false),
				'rules_home_instances' => ((isset($options['right']['rules_home_instances']) && ($options['right']['rules_home_instances'] != ''))?explode(',', $options['right']['rules_home_instances']):array()),
				'rules_exclude_archives' => ((isset($oldValues['right']['rules_exclude_archives']))?true:false),
				'rules_archives_instances' => ((isset($options['right']['rules_archives_instances']) && ($options['right']['rules_archives_instances'] != ''))?explode(',', $options['right']['rules_archives_instances']):array()),
				'rules_exclude_search' => ((isset($oldValues['right']['rules_exclude_search']))?true:false),
				'rules_search_instances' => ((isset($options['right']['rules_search_instances']) && ($options['right']['rules_search_instances'] != ''))?explode(',', $options['right']['rules_search_instances']):array()),
				'rules_exclude_page' => ((isset($oldValues['right']['rules_exclude_page']))?true:false),
				'rules_page_exceptions' => ((isset($options['right']['rules_page_exceptions']) && ($options['right']['rules_page_exceptions'] != ''))?explode(',', $options['right']['rules_page_exceptions']):array()),
				'rules_exclude_post' => ((isset($oldValues['right']['rules_exclude_post']))?true:false),
				'rules_post_exceptions' => ((isset($options['right']['rules_post_exceptions']) && ($options['right']['rules_post_exceptions'] != ''))?explode(',', $options['right']['rules_post_exceptions']):array()),
				'rules_post_categories_exceptions' => ((isset($options['right']['rules_post_categories_exceptions']) && ($options['right']['rules_post_categories_exceptions'] != ''))?explode(',', $options['right']['rules_post_categories_exceptions']):array()),
				'rules_exclude_categories' => ((isset($oldValues['right']['rules_exclude_categories']))?true:false),
				'rules_categories_instances' => ((isset($options['right']['rules_categories_instances']) && ($options['right']['rules_categories_instances'] != ''))?explode(',', $options['right']['rules_categories_instances']):array()),
				'rules_categories_exceptions' => ((isset($options['right']['rules_categories_exceptions']) && ($options['right']['rules_categories_exceptions'] != ''))?explode(',', $options['right']['rules_categories_exceptions']):array()),
				'geo_group1_countries' => ((isset($options['right']['country_1']) && ($options['right']['country_1'] != ''))?explode(',', $options['right']['country_1']):array()),
				'geo_group1_adcode' => ((isset($oldValues['right']['country_code_1']))?$oldValues['right']['country_code_1']:''),
				'geo_group2_countries' => array(),
				'geo_group2_adcode' => '',
				'styles' => ((isset($oldValues['right']['styles']))?$oldValues['right']['styles']:'margin: 5px; padding: 0px;'),
				'notes' => ((isset($oldValues['right']['notes']))?$oldValues['right']['notes']:''),
			)
		);
		update_option('insert_ads_inpostads', $newValues);
	}
}
/* End Database Upgrade */

/* Begin Ad Insertion */
add_filter('the_content', 'insert_ads_inpostads_the_content', 100);
function insert_ads_inpostads_the_content($content) {
	if(!is_feed() && is_main_query()) {
		$inpostAds = get_option('insert_ads_inpostads');

		if(insert_ads_get_ad_status($inpostAds['left'])) {
			$content = '<div class="wpInsert wpInsertInPostAd wpInsertLeft" style="float: left; '.(($inpostAds['left']['styles'] != '')?$inpostAds['left']['styles']:'').'">'.insert_ads_get_geotargeted_adcode($inpostAds['left']).'</div>'.$content;
		}
		if(insert_ads_get_ad_status($inpostAds['right'])) {
			$content = '<div class="wpInsert wpInsertInPostAd wpInsertRight" style="float: right; '.(($inpostAds['right']['styles'] != '')?$inpostAds['right']['styles']:'').'">'.insert_ads_get_geotargeted_adcode($inpostAds['right']).'</div>'.$content;
		}
		if(insert_ads_get_ad_status($inpostAds['above'])) {
			$content = '<div class="wpInsert wpInsertInPostAd wpInsertAbove"'.(($inpostAds['above']['styles'] != '')?' style="'.$inpostAds['above']['styles'].'"':'').'>'.insert_ads_get_geotargeted_adcode($inpostAds['above']).'</div>'.$content;
		}
		if(insert_ads_get_ad_status($inpostAds['middle'])) {
			$paragraphCount = insert_ads_inpostads_get_paragraph_count($content);
			if($paragraphCount > 1) {
				if(($inpostAds['middle']['paragraph_buffer_count'] == 0) || ($inpostAds['middle']['paragraph_buffer_count'] == '')) {
					$position = insert_ads_inpostads_get_midpoint('/p>', $content, round($paragraphCount / 2));
				} else {			
					$position = insert_ads_inpostads_get_midpoint('/p>', $content, $inpostAds['middle']['paragraph_buffer_count']);
				}
				if($position) {
					if(($inpostAds['middle']['minimum_character_count'] == 0) || ($inpostAds['middle']['minimum_character_count'] == '')) {
						$content = substr_replace($content, '/p>'.'<div class="wpInsert wpInsertInPostAd wpInsertMiddle"'.(($inpostAds['middle']['styles'] != '')?' style="'.$inpostAds['middle']['styles'].'"':'').'>'.insert_ads_get_geotargeted_adcode($inpostAds['middle']).'</div>', $position, 3);
					} else {
						if(strlen(strip_tags($content)) > $inpostAds['middle']['minimum_character_count']) {
							$content = substr_replace($content, '/p>'.'<div class="wpInsert wpInsertInPostAd wpInsertMiddle"'.(($inpostAds['middle']['styles'] != '')?' style="'.$inpostAds['middle']['styles'].'"':'').'>'.insert_ads_get_geotargeted_adcode($inpostAds['middle']).'</div>', $position, 3);
						}
					}
				}
			}
		}
		if(insert_ads_get_ad_status($inpostAds['below'])) {
			$content = $content.'<div class="wpInsert wpInsertInPostAd wpInsertBelow"'.(($inpostAds['below']['styles'] != '')?' style="'.$inpostAds['below']['styles'].'"':'').'>'.insert_ads_get_geotargeted_adcode($inpostAds['below']).'</div>';
		}
	}
	return $content;
}

function insert_ads_inpostads_get_paragraph_count($content) {
	$paragraphs = explode('/p>', $content);
	$paragraphCount = 0;
	if(is_array($paragraphs)) {
		foreach($paragraphs as $paragraph) {
			if(strlen($paragraph) > 1) {
				$paragraphCount++;
			}
		}
	}
	return $paragraphCount;
}

function insert_ads_inpostads_get_midpoint($search, $string, $offset) {
    $arr = explode($search, $string);
    switch($offset) {
        case $offset == 0:
			return false;
			break;
        case $offset > max(array_keys($arr)):
			return false;
			break;
        default:
			return strlen(implode($search, array_slice($arr, 0, $offset)));
			break;
    }
}
/* End Ad Insertion */
?>