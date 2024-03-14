<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2020-2023 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

/*-------------------------------------------------------------
 Name:      ajdg_matomo_dashboard_styles
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_dashboard_styles() {
	wp_enqueue_style('ajdg-matomo-admin-stylesheet', plugins_url('library/dashboard.css', __FILE__));
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_activate
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_activate() {
	// Defaults
	update_option('ajdg_matomo_siteid', '');
	update_option('ajdg_matomo_siteurl', '');
	update_option('ajdg_matomo_active', 'no');
	update_option('ajdg_matomo_track_feed_clicks', 'no');
	update_option('ajdg_matomo_track_feed_impressions', 'no');
	update_option('ajdg_matomo_track_error_pages', 'no');
	update_option('ajdg_matomo_track_incognito', 'no');
	update_option('ajdg_matomo_heartbeat_enable', 'no');
	update_option('ajdg_matomo_wc_downloads', 'no');
	update_option('ajdg_matomo_high_accuracy', 'no');
	update_option('ajdg_matomo_hide_review', current_time('timestamp'));
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_deactivate
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_deactivate() {
	// Delete data
	delete_option('ajdg_matomo_siteid');
	delete_option('ajdg_matomo_siteurl');
	delete_option('ajdg_matomo_active');
	delete_option('ajdg_matomo_track_feed_clicks');
	delete_option('ajdg_matomo_track_feed_impressions');
	delete_option('ajdg_matomo_track_error_pages');
	delete_option('ajdg_matomo_track_incognito');
	delete_option('ajdg_matomo_heartbeat_enable');
	delete_option('ajdg_matomo_wc_downloads');
	delete_option('ajdg_matomo_high_accuracy');
	delete_option('ajdg_matomo_hide_review');
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_save_settings
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_save_settings() {
	if(wp_verify_nonce($_POST['matomo_nonce'],'matomo_nonce')) {
		$siteid = $siteurl = $track_active = $track_feed = $track_error_pages = $track_heartbeat = $track_high_accuracy = '';
		if(isset($_POST['matomo_siteid'])) $siteid = sanitize_text_field(trim($_POST['matomo_siteid'], "\t\n "));
		if(isset($_POST['matomo_siteurl'])) $siteurl = filter_var(trim($_POST['matomo_siteurl'], "\t\n "), FILTER_SANITIZE_URL);
		if(isset($_POST['matomo_tracker_active'])) $track_active = sanitize_key($_POST['matomo_tracker_active']);
		if(isset($_POST['matomo_feed_clicks'])) $track_feed_clicks = sanitize_key($_POST['matomo_feed_clicks']);
		if(isset($_POST['matomo_error_pages'])) $track_error_pages = sanitize_key($_POST['matomo_error_pages']);
		if(isset($_POST['matomo_incognito'])) $track_incognito = sanitize_key($_POST['matomo_incognito']);
		if(isset($_POST['matomo_heartbeat'])) $track_heartbeat = sanitize_key($_POST['matomo_heartbeat']);
		if(isset($_POST['matomo_wc_downloads'])) $track_wc_downloads = sanitize_key($_POST['matomo_wc_downloads']);
		if(isset($_POST['matomo_feed_impressions'])) $track_feed_impressions = sanitize_key($_POST['matomo_feed_impressions']);
		if(isset($_POST['matomo_accuracy'])) $track_high_accuracy = sanitize_key($_POST['matomo_accuracy']);

		// Cleanup
		$siteid = (is_numeric($siteid)) ? $siteid : '';

		$siteurl = str_ireplace(array('/index.php', '/matomo.php', '/piwik.php'), '', strtolower($siteurl)); // Remove files
		$siteurl = preg_replace('/\?idsite=\d/i', '', $siteurl); // Remove idsite parameter
		$siteurl = trim($siteurl, "\/"); // Remove trailing slashes
		$siteurl = (strlen($siteurl) > 0) ? $siteurl : '';

		$track_active = ($track_active == 'yes') ? 'yes' : 'no';
		$track_feed_clicks = ($track_feed_clicks == 'yes') ? 'yes' : 'no';
		$track_error_pages = ($track_error_pages == 'yes') ? 'yes' : 'no';
		$track_incognito = ($track_incognito == 'yes') ? 'yes' : 'no';
		$track_heartbeat = ($track_heartbeat == 'yes') ? 'yes' : 'no';
		$track_wc_downloads = ($track_wc_downloads == 'yes') ? 'yes' : 'no';
		$track_feed_impressions = ($track_feed_impressions == 'yes') ? 'yes' : 'no';
		$track_high_accuracy = ($track_high_accuracy == 'yes') ? 'yes' : 'no';

		// Process and response
		update_option('ajdg_matomo_siteid', $siteid);
		update_option('ajdg_matomo_siteurl', $siteurl);
		update_option('ajdg_matomo_active', $track_active);
		update_option('ajdg_matomo_track_feed_clicks', $track_feed_clicks);
		update_option('ajdg_matomo_track_error_pages', $track_error_pages);
		update_option('ajdg_matomo_track_incognito', $track_incognito);
		update_option('ajdg_matomo_heartbeat_enable', $track_heartbeat);
		update_option('ajdg_matomo_wc_downloads', $track_wc_downloads);
		update_option('ajdg_matomo_track_feed_impressions', $track_feed_impressions);
		update_option('ajdg_matomo_high_accuracy', $track_high_accuracy);

		ajdg_matomo_return('matomo-tracker', 100);
		exit;
	} else {
		ajdg_matomo_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_check_config
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_check_config() {
	$siteid = get_option('ajdg_matomo_siteid');
	if(!$siteid) update_option('ajdg_matomo_siteid', '');

	$siteurl = get_option('ajdg_matomo_siteurl');
	if(!$siteurl) update_option('ajdg_matomo_siteurl', '');

	$track_active = get_option('ajdg_matomo_active');
	if(!$track_active) update_option('ajdg_matomo_active', 'no');

	$track_feed_click = get_option('ajdg_matomo_track_feed_clicks');
	if(!$track_feed_click) update_option('ajdg_matomo_track_feed_clicks', 'yes');

	$track_error_pages = get_option('ajdg_matomo_track_error_pages');
	if(!$track_error_pages) update_option('ajdg_matomo_track_error_pages', 'yes');

	$track_incognito = get_option('ajdg_matomo_track_incognito');
	if(!$track_incognito) update_option('ajdg_matomo_track_incognito', 'no');

	$heartbeat_enable = get_option('ajdg_matomo_heartbeat_enable');
	if(!$heartbeat_enable) update_option('ajdg_matomo_heartbeat_enable', 'no');

	$track_wc_downloads = get_option('ajdg_matomo_wc_downloads');
	if(!$track_wc_downloads) update_option('ajdg_matomo_wc_downloads', 'no');

	$track_feed_impressions = get_option('ajdg_matomo_track_feed_impressions');
	if(!$track_feed_impressions) update_option('ajdg_matomo_track_feed_impressions', 'no');

	$high_accuracy = get_option('ajdg_matomo_high_accuracy');
	if(!$high_accuracy) update_option('ajdg_matomo_high_accuracy', 'no');
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_notifications_dashboard
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_notifications_dashboard() {
	global $current_user;

	$displayname = (strlen($current_user->user_firstname) > 0) ? $current_user->user_firstname : $current_user->display_name;

	if(isset($_GET['hide'])) {
		if($_GET['hide'] == 1) update_option('ajdg_matomo_hide_review', 1);
	}

	$review_banner = get_option('ajdg_matomo_hide_review');
	if($review_banner != 1 AND $review_banner < (current_time('timestamp') - (7 * DAY_IN_SECONDS))) {
		echo '<div class="ajdg-notification notice" style="">';
		echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
		echo '	<div class="ajdg-notification-message">Hey there <strong>'.$displayname.'</strong>! You have been using <strong>AJdG Matomo Tracker</strong> for a few days.<br />If you like the plugin, please <strong>write a review</strong>.<br />If you have questions, complaints or something else that does not belong in a review, please use the <a href="https://wordpress.org/support/plugin/matomo-analytics/">support forum</a>!</div>';
		echo '	<div class="ajdg-notification-cta">';
		echo '		<a href="https://wordpress.org/support/plugin/matomo-analytics/reviews?rate=5#postform" class="ajdg-notification-act button-primary">Write a Review</a>';
		echo '		<a href="tools.php?page=matomo-tracker&hide=1" class="ajdg-notification-dismiss">Maybe later</a>';
		echo '	</div>';
		echo '</div>';
	}

	$has_error = ajdg_matomo_has_error();
	if($has_error) {
		echo '<div class="ajdg-notification notice" style="">';
		echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
		echo '	<div class="ajdg-notification-message"><strong>Analytics for Matomo</strong> has detected '._n('one issue that requires', 'several issues that require', count($has_error), 'ajdg-matomo-tracker').' '.__('your attention:', 'ajdg-matomo-tracker').'<br />';
		foreach($has_error as $error => $message) {
			echo '&raquo; '.$message.'<br />';
		}
		echo '	<br /><a href="'.admin_url('/tools.php?page=matomo-tracker').'">'.__('Check your settings', 'ajdg-matomo-tracker').'</a>!';
		echo '	</div>';
		echo '</div>';
	}
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_has_error
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_has_error() {
	$siteid = get_option('ajdg_matomo_siteid');
	$siteurl = get_option('ajdg_matomo_siteurl');
	$track_active = get_option('ajdg_matomo_active');

	if($track_active == 'yes' AND (empty($siteid) OR empty($siteurl))) {
		$error['matomo_site_details'] = __('You activated the tracker but the Site ID and/or Site URL is empty.', 'ajdg-matomo-tracker');
	}

	$error = (isset($error) AND is_array($error)) ? $error : false;

	return $error;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_action_links
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_action_links($links) {
	$links['ajdg-matomo-settings'] = sprintf('<a href="%s">%s</a>', admin_url('tools.php?page=matomo-tracker'), 'Settings');
	$links['ajdg-matomo-help'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/forums/forum/matomo-tracker/?mtm_campaign=matomo_tracker', 'Support');
	$links['ajdg-matomo-ajdg'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/?mtm_campaign=matomo_tracker', 'ajdg.solutions');

	return $links;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_return
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_return($page, $status, $args = null) {

	if(strlen($page) > 0 AND ($status > 0 AND $status < 1000)) {
		$defaults = array(
			'status' => $status
		);
		$arguments = wp_parse_args($args, $defaults);
		$redirect = 'tools.php?page=' . $page . '&'.http_build_query($arguments);
	} else {
		$redirect = 'tools.php?page=matomo-tracker';
	}

	wp_redirect($redirect);
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_status
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_status($status) {

	switch($status) {
		case '100' :
			echo '<div class="updated"><p>'.__('Settings saved', 'ajdg-matomo-tracker').'</p></div>';
		break;

		default :
			echo '<div class="error"><p>'.__('Unexpected error', 'ajdg-matomo-tracker').'</p></div>';
		break;
	}
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_nonce_error
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_nonce_error() {
	echo '	<h2 style="text-align: center;">'.__('Oh no! Something went wrong!', 'ajdg-matomo-tracker').'</h2>';
	echo '	<p style="text-align: center;">'.__('WordPress was unable to verify the authenticity of the url you have clicked. Verify if the url used is valid or log in via your browser.', 'ajdg-matomo-tracker').'</p>';
	echo '	<p style="text-align: center;">'.__('If you have received the url you want to visit via email, you are being tricked!', 'ajdg-matomo-tracker').'</p>';
	echo '	<p style="text-align: center;">'.__('Contact support if the issue persists:', 'ajdg-matomo-tracker').' <a href="https://ajdg.solutions/forums/forum/matomo-tracker/?mtm_campaign=matomo_tracker" title="AJdG Solutions Support" target="_blank">Support forum</a>.</p>';
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_useragent_filter
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_useragent_filter($user_agent) {
	$blocked_user_agents = array(
		'bot', 'crawler', 'spider',
		'exabot', 'alexa', 'findlinks', 'ia_archiver', 'inktomi',
		'slurp', 'YahooSeeker', 'yahoo',
		'adsbot-google', 'googlebot', 'googleproducer', 'google-site-verification', 'google-test', 'mediapartners-google', 'feedfetcher-google',
		'baidu', 'yandex', 'yandex', 'YandexImages',
		'bingbot', 'bingpreview', 'msnbot',
		'duckduckgo', 'aolbuild',
		'sosospider', 'sosoimagespider', 'sogou', 'teoma',
		'facebookexternalhit', 'facebook',
		'TECNOSEEK', 'TechnoratiSnoop'
	);

	// You're a bot
	if(preg_match('/'.implode('|', $blocked_user_agents).'/i', $user_agent)) return false;

	// Or not...
	return true;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_feed_impressions
 Since:		1.1
-------------------------------------------------------------*/
function ajdg_matomo_feed_impressions($content) {
	global $post;
	if(is_feed()) {
		$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_USER_AGENT']))) : 'n/a';

		if(ajdg_matomo_useragent_filter($user_agent)) {
			$siteid = get_option('ajdg_matomo_siteid');
			$siteurl = get_option('ajdg_matomo_siteurl');

			$protocol = (is_ssl()) ? 'https://' : 'http://';
			$http_host = (isset($_SERVER['HTTP_HOST'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_HOST']))) : 'n/a';
			$request_url = (isset($_SERVER['REQUEST_URI'])) ? esc_url_raw($protocol.$http_host.$_SERVER['REQUEST_URI']) : 'n/a';

			$title = $post->post_name;
			$posturl = get_permalink($post->ID);

			if(strpos($request_url, get_bloginfo('rss2_url')) !== false) {
				$feed_type = get_bloginfo('rss2_url');
			} elseif(strpos($request_url, get_bloginfo('atom_url')) !== false) {
				$feed_type = get_bloginfo('atom_url');
			} else {
				$feed_type = home_url().'feed/';
			}

			$content .= '<img src="'.$siteurl.'/matomo.php?idsite='.$siteid.'&rec=1&url='.$posturl.'&action_name='.$title.'&urlref='.$feed_type.'&_rcn=feed_impression&_rck='.$title.'" style="border:0;width:0;height:0" width="0" height="0" alt="" />';
		}
	}

	return $content;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_feed_campaign
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_feed_clicks($permalink) {
	global $post;

	if(is_feed()) {
		$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_USER_AGENT']))) : 'n/a';

		if(ajdg_matomo_useragent_filter($user_agent)) {
			$protocol = (is_ssl()) ? 'https://' : 'http://';
			$http_host = (isset($_SERVER['HTTP_HOST'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_HOST']))) : 'n/a';
			$request_url = (isset($_SERVER['REQUEST_URI'])) ? esc_url_raw($protocol.$http_host.$_SERVER['REQUEST_URI']) : 'n/a';

			if(strpos($request_url, get_bloginfo('rss2_url')) !== false) {
				$feed_type = 'RSS2';
			} elseif(strpos($request_url, get_bloginfo('atom_url')) !== false) {
				$feed_type = 'Atom';
			} elseif(strpos($request_url, get_bloginfo('comments_rss2_url')) !== false) {
				$feed_type = 'Comment';
			} elseif(strpos($request_url, get_bloginfo('comments_atom_url')) !== false) {
				$feed_type = 'Comment';
			} else {
				$feed_type = 'Other';
			}

			$separator = (strpos($permalink, '?') === false) ? '?' : '&';
			$permalink .= $separator.'mtm_campaign=feed_click&mtm_kwd='.$post->post_name.'&mtm_source='.$feed_type.'&mtm_medium=feed';
		}
	}

	return $permalink;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_tracker
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_matomo_tracker() {
	$siteid = get_option('ajdg_matomo_siteid');
	$siteurl = get_option('ajdg_matomo_siteurl');

	$track_error_pages = get_option('ajdg_matomo_track_error_pages');
	$track_incognito = get_option('ajdg_matomo_track_incognito');
	$track_heartbeat = get_option('ajdg_matomo_heartbeat_enable');
	$track_wc_downloads = get_option('ajdg_matomo_wc_downloads');
	$track_high_accuracy = get_option('ajdg_matomo_high_accuracy');

	echo "<!-- Matomo -->\n";
	echo "<script type=\"text/javascript\">\n";
	echo "var _paq = window._paq || [];\n";
	if(is_404() AND $track_error_pages == 'yes') echo "_paq.push(['setDocumentTitle', '404/URL = ' + encodeURIComponent(document.location.pathname + document.location.search) + ' Referrer = ' + encodeURIComponent(document.referrer)]);\n";
	if($track_incognito == 'yes') echo "_paq.push(['setDoNotTrack', true]);\n";
	if($track_heartbeat == 'yes') echo "_paq.push(['enableHeartBeatTimer']);\n";
	if($track_wc_downloads == 'yes') echo "_paq.push(['setDownloadClasses', 'woocommerce-MyAccount-downloads-file']);\n";
	if($track_high_accuracy == 'yes') echo "_paq.push(['alwaysUseSendBeacon']);\n";
	echo "_paq.push(['trackPageView']);\n";
	echo "_paq.push(['enableLinkTracking']);\n";
	echo "(function() {\n";
	echo "\t_paq.push(['setTrackerUrl', '$siteurl/matomo.php']);\n";
	echo "\t_paq.push(['setSiteId', '$siteid']);\n";
	echo "\tvar d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];\n";
	echo "\tg.type='text/javascript'; g.async=true; g.defer=true; g.src='$siteurl/matomo.js'; s.parentNode.insertBefore(g,s);\n";
	echo "})();\n";
	echo "</script>\n";
	echo "<noscript><img src=\"$siteurl/matomo.php?idsite=$siteid&amp;rec=1\" style=\"border:0\" alt=\"\" /></noscript>\n";
	echo "<!-- /Matomo -->\n\n";
}
?>