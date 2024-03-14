<?php 
/* Begin Add Card in Admin Panel */
add_action('insert_ads_plugin_card', 'insert_ads_adstxt_plugin_card', 70);
function insert_ads_adstxt_plugin_card() {
	echo '<div class="plugin-card adstxt-card">';
		echo '<div class="plugin-card-top">';
			echo '<h4>Authorized Digital Sellers / ads.txt</h4>';
			echo '<p>Authorized Digital Sellers, or ads.txt, is an <a href="https://iabtechlab.com/">IAB</a> initiative to improve transparency in programmatic advertising.</p>';
			echo '<p>You can easily manage your ads.txt from within Wp-Insert, providing confidence to brands they are buying authentic publisher inventory, protect you from counterfiet inventory and might even lead to higher monetization for your ad invertory.</p>';
		echo '</div>';
		echo '<div class="plugin-card-bottom">';
			if(insert_ads_adstxt_file_exists()) {
				echo '<a id="insert_ads_adstxt_generate" href="javascript:;" class="button button-primary">Modify ads.txt</a>';
			} else {
				echo '<a id="insert_ads_adstxt_generate" href="javascript:;" class="button button-primary">Generate ads.txt</a>';
			}
		echo '</div>';
	echo '</div>';
}
/* End Add Card in Admin Panel */

/* Begin Create Ads.txt */
add_action('wp_ajax_insert_ads_adstxt_generate_form_get_content', 'insert_ads_adstxt_generate_form_get_content');
function insert_ads_adstxt_generate_form_get_content() {
	check_ajax_referer('wp-insert', 'insert_ads_nonce');
	echo '<div class="insert_ads_popup_content_wrapper">';
		echo '<div id="insert_ads_adstxt_accordion">';
			$control = new smartlogixControls();
			echo '<h3>ads.txt Content</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'textarea', 'id' => 'insert_ads_adstxt_content', 'name' => 'insert_ads_adstxt_content', 'style' => 'height: 220px;', 'value' => insert_ads_adstxt_get_content(), 'helpText' => 'You can directly edit the entries here or you can use the entry generator below to quickly create new entries'));
				$control->create_section('ads.txt Content');
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Entry Generator</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'text', 'id' => 'insert_ads_adstxt_new_entry_domain', 'name' => 'insert_ads_adstxt_new_entry_domain', 'label' => 'Domain name of the advertising system <small style="font-size: 10px;">(Required)</small>', 'value' => '', 'helpText' => 'For Google Adsense Use "google.com"; for other networks, contact your service provider for values.'));
				$control->add_control(array('type' => 'text', 'id' => 'insert_ads_adstxt_new_entry_pid', 'name' => 'insert_ads_adstxt_new_entry_pid', 'label' => 'Publisher’s Account ID <small style="font-size: 10px;">(Required)</small>', 'value' => '', 'helpText' => 'For Google Adsense Use your Publisher ID "pub-xxxxxxxxxxxxxxxx"; for other networks, contact your service provider for values.'));
				$control->add_control(array('type' => 'select', 'id' => 'insert_ads_adstxt_new_entry_type', 'name' => 'insert_ads_adstxt_new_entry_type', 'label' => 'Type of Account / Relationship <small style="font-size: 10px;">(Required)</small>', 'value' => '', 'options' => array(array('text' => 'Direct', 'value' => 'DIRECT'), array('text' => 'Reseller', 'value' => 'RESELLER')), 'helpText' => 'For Google Adsense select "Reseller"; for other networks, contact your service provider for values.'));
				$control->add_control(array('type' => 'text', 'id' => 'insert_ads_adstxt_new_entry_certauthority', 'name' => 'insert_ads_adstxt_new_entry_certauthority', 'label' => 'Certification Authority ID', 'value' => '', 'helpText' => 'Contact your service provider for values.'));
				$control->HTML .= '<p><input id="insert_ads_adstxt_add_entry" onclick="insert_ads_adstxt_add_entry()" type="button" value="Add Entry" class="button button-primary" /></p>';
				$control->create_section('Entry Generator');
				echo $control->HTML;
			echo '</div>';
		echo '</div>';
		echo '<script type="text/javascript">';
		echo $control->JS;
		echo 'jQuery("#insert_ads_adstxt_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "fill" });';
		//echo 'jQuery(".ui-dialog-buttonset").find("button").first().remove();';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_insert_ads_adstxt_generate_form_save_action', 'insert_ads_adstxt_generate_form_save_action');
function insert_ads_adstxt_generate_form_save_action() {
	check_ajax_referer('wp-insert', 'insert_ads_nonce');
	$content = ((isset($_POST['insert_ads_adstxt_content']))?$_POST['insert_ads_adstxt_content']:'');
	$output = insert_ads_adstxt_updation_failed_message($content);
	$output .= '<script type="text/javascript">';
		$output .= 'jQuery(".ui-dialog-buttonset").find("button").first().hide();';
	$output .= '</script>';

	if(insert_ads_adstxt_update_content($content)) {
		echo '###SUCCESS###';
	} else {
		echo $output;
	}
	die();
}
/* End Create Ads.txt */
?>