<?php 
function insert_ads_adstxt_file_exists() {
	if(file_exists(trailingslashit(get_home_path()).'ads.txt')) {
		return true;
	}
	return false;
}

function insert_ads_adstxt_get_content() {
	if(insert_ads_adstxt_file_exists()) {
		return @file_get_contents(trailingslashit(get_home_path()).'ads.txt');
	}
	return '';
}

function insert_ads_adstxt_update_content($content) {
	if(get_filesystem_method() === 'direct') {
		$creds = request_filesystem_credentials(site_url().'/wp-admin/', '', false, false, array());
		if(!WP_Filesystem($creds)) {
			return false;
		}
		global $wp_filesystem;
		if(!$wp_filesystem->put_contents(trailingslashit(get_home_path()).'ads.txt', $content, FS_CHMOD_FILE)) {
			return false;
		}
	} else {
		return false;
	}
	return true;
}

function insert_ads_adstxt_updation_failed_message($content) {
	$output = '<div class="insert_ads_popup_content_wrapper">';
		$output .= '<p>Auto Creation / Updation of ads.txt failed due to access permission restrictions on the server.</p>';
		$output .= '<p>You have to manually upload the file using your Host\'s File manager or your favourite FTP program</p>';
		$output .= '<p>ads.txt should be located in the root of your server. After manually uploading the file click <a href="'.site_url().'/ads.txt">here</a> to check if its accessible from the correct location</p>';
		$output .= '<textarea style="display: none;" id="insert_ads_adstxt_content">'.$content.'</textarea>';
		$output .= '<p><a onclick="insert_ads_adstxt_content_download()" class="button button-primary" href="javascript:;">Download ads.txt</a></p>';
	$output .= '</div>';
	return $output;
}
?>