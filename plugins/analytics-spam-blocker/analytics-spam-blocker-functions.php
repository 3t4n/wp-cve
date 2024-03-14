<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2016-2023 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

/*-------------------------------------------------------------
 Name:      asb_dashboard_scripts
 Since:		3.0
-------------------------------------------------------------*/
function spamblocker_dashboard_scripts() {
	$page = (isset($_GET['page'])) ? $_GET['page'] : '';
    if(strpos($page, 'analytics-spam-blocker') !== false) {
		wp_enqueue_script('goosebox', plugins_url('/library/goosebox.js', __FILE__));
	}
}

/*-------------------------------------------------------------
 Name:      asb_dashboard_styles
 Since:		2.0
-------------------------------------------------------------*/
function spamblocker_dashboard_styles() {
	wp_enqueue_style('asb-admin-stylesheet', plugins_url('library/dashboard.css', __FILE__));
}

/*-------------------------------------------------------------
 Name:      asb_activate
 Since:		2.0
-------------------------------------------------------------*/
function asb_activate() {
	// Defaults
	add_option('ajdg_spamblocker_user', '');
	add_option('ajdg_spamblocker_version', ASB_DATABASE);
	add_option('ajdg_spamblocker_domains', array('updated' => 0, 'domain_count' => 0, 'domains' => array()));
	add_option('ajdg_spamblocker_stats', array('active_subscribers' => 'n/a', 'reported_sites' => 'n/a', 'reports_submitted' => 'n/a', 'reports_user' => 'n/a'));
	add_option('ajdg_spamblocker_updates', 'Y');
	update_option('ajdg_spamblocker_hide_review', current_time('timestamp'));
	update_option('ajdg_spamblocker_hide_birthday', current_time('timestamp'));

	wp_schedule_event(time() + 120, 'daily', 'ajdg_api_stats_update');

	// Set up htaccess
	if((!file_exists(ABSPATH.'.htaccess') AND is_writable(ABSPATH)) OR is_writable(ABSPATH.'.htaccess')) {
		// Edit htaccess
		asb_edit_htaccess();
	} else {
		// Not writable
		wp_die('Analytics Spam Blocker can not add the referral spam rules to your .htaccess file. Make sure it is writable. Contact your hosting provider if you are not sure how to resolve this issue.<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to dashboard</a>.');
	}
}

/*-------------------------------------------------------------
 Name:      asb_deactivate
 Since:		2.0
-------------------------------------------------------------*/
function asb_deactivate() {
	// Delete data
	delete_option('ajdg_spamblocker_user');
	delete_option('ajdg_spamblocker_domains');
	delete_option('ajdg_spamblocker_stats');
	delete_option('ajdg_spamblocker_hide_review');
	delete_option('ajdg_spamblocker_hide_birthday');
	delete_option('ajdg_spamblocker_updates');
	delete_option('ajdg_activate_analytics-spam-blocker'); // Obsolete

	wp_clear_scheduled_hook('ajdg_api_stats_update');

	delete_option('ajdg_spamblocker_error'); // Obsolete
	delete_option('ajdg_spamblocker_subscription'); // Obsolete
	delete_option('ajdg_spamblocker_reports'); // Obsolete
	delete_option('ajdg_spamblocker_hide_register'); // Obsolete

	// Clean .htaccess
	if(is_writable(ABSPATH.'.htaccess')) {
		asb_clean_htaccess('# Analytics Spam Blocker - Start', '# Analytics Spam Blocker - End');
	} else {
		// Not writable
		wp_die(_e('Your .htaccess file is not writable!'));
	}
}

/*-------------------------------------------------------------
 Name:      asb_check_config
 Purpose:   Verify or reset settings
 Since:		2.5
-------------------------------------------------------------*/
function asb_check_config() {
	$two_days = current_time('timestamp') + (2 * 86400);

	$user = get_option('ajdg_spamblocker_user');
	if(!$user) update_option('ajdg_spamblocker_user', '');

	$domains = get_option('ajdg_spamblocker_domains');
	if(!$domains) update_option('ajdg_spamblocker_domains', array('updated' => 0, 'domain_count' => 0, 'domains' => array()));

	$stats = get_option('ajdg_spamblocker_stats');
	if(!$stats) update_option('ajdg_spamblocker_stats', array('active_subscribers' => 'n/a', 'reported_sites' => 'n/a', 'reports_submitted' => 'n/a', 'reports_user' => 'n/a'));

	$review = get_option('ajdg_spamblocker_hide_review');
	if(!$review) update_option('ajdg_spamblocker_hide_review', $two_days);

	$birthday = get_option('ajdg_spamblocker_hide_birthday');
	if(!$birthday) update_option('ajdg_spamblocker_hide_birthday', current_time('timestamp'));
}

/*-------------------------------------------------------------
 Name:      asb_check_upgrade
 Purpose:   Checks if the plugin needs to upgrade stuff
-------------------------------------------------------------*/
function asb_check_upgrade() {
	if(version_compare(PHP_VERSION, '5.6.0', '<') == -1) {
		deactivate_plugins(plugin_basename('analytics-spam-blocker/analytics-spam-blocker.php'));
		wp_die('Analytics Spam Blocker 3.0 and newer requires PHP 5.6 or higher. Your server reports version '.PHP_VERSION.'. Contact your hosting provider about upgrading your server!<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to dashboard</a>.');
	} else {
		$asb_version = get_option('ajdg_spamblocker_version', 0);
		if($asb_version < ASB_DATABASE) {
			asb_edit_htaccess();
			update_option('ajdg_spamblocker_version', ASB_DATABASE);
		}
	}
}

/*-------------------------------------------------------------
 Name:      asb_updates
 Purpose:   Switches between update servers
-------------------------------------------------------------*/
function asb_updates() {
	$update_server = get_option("ajdg_spamblocker_updates", "Y");

	if($update_server == 'N') {
		update_option('ajdg_spamblocker_updates', 'Y');
	}
	if($update_server == 'Y') {
		update_option('ajdg_spamblocker_updates', 'N');
	}
}

/*-------------------------------------------------------------
 Name:      asb_schedule_updates
 Since:		2.0
-------------------------------------------------------------*/
function asb_schedule_updates() {
	if(!wp_next_scheduled('ajdg_spamblocker_blocklist_update')) wp_schedule_event(time() + 120, 'daily', 'ajdg_spamblocker_blocklist_update');
}

/*-------------------------------------------------------------
 Name:      asb_domain_list
 Since:		3.0
-------------------------------------------------------------*/
function asb_domain_list() {
	return array('1-best-seo.com', '1-do-now.com', '100-reasons-for-seo.com', '100dollars-seo.com', '12-reasons-for-seo.com', '15-reasons-for-seo.com', '16-reasons-for-seo.com', '17-reasons-for-seo.com', '2-go-now.xyz', '4webmasters.org', '500-good-starts.xyz', '7-best-seo.com', '7makemoneyonline.com', '9-best-seo.com', '9-reasons-for-seo.com', '99-reasons-for-seo.com', 'a-hau.mk', 'accept-bitcoinser-plugin.info', 'acceptof-bitcoins-plugin.info', 'account-my1.xyz', 'adcash.com', 'ads-autoseo.com', 'ads-seo-manager.com', 'ads-seo-services.com', 'ads-seoservices.com', 'ads-services-seo.com', 'advert-seo.com', 'advertising-seo.com', 'adviceforum.info', 'affordable-link-building.com', 'ai-seo-services.com', 'amanda-porn.ga', 'anal-acrobats.hol.es', 'analytics-for-seo.com', 'ann-advert1.xyz', 'anticrawler.org', 'auto-seo-service.com', 'auto-seo-service.org', 'autoseo-b2b-seo-service.com', 'autoseo-b2b-seo-services.com', 'autoseo-b2b-services.com', 'autoseo-expert.com', 'autoseo-service.org', 'autoseo-trial-for-1-dollar.com', 'autoseo-trial-for-1.com', 'autoseoservice.org', 'autoseotips.com', 'backgroundpictures.net', 'backlinks-fast-top.com', 'baixar-musicas-gratis.comsavetubevideo.com', 'best-deal-hdd.pro', 'best-seo-offer.com', 'best-seo-solution.com', 'bestwebsiteawards.com', 'bestwebsitesawards.com', 'better-seo-promotion.com', 'big-seo-deal.com', 'blackhatworth.com', 'buttons-for-website.com', 'buttons-for-your-website.com', 'buy-cheap-online.info', 'cambridgeshire.libnet.info', 'cenokos.ru', 'cenoval.ru', 'cfsrating.sonicwall.com', 'check-pro1.xyz', 'chicago-daily-newsoo.info', 'chicago-dailyity-news.info', 'cityadspix.com', 'coverage-my.com', 'cyprusbuyproperties.com', 'dailyrank.net', 'darodar.com', 'depositfiles-porn.ga', 'descargar-musica-gratis.net', 'descargar-musicas-gratis.com', 'display-your-ads-hereti.info', 'display-your-ads-herez.info', 'dombestofferhdd.info', 'domination.ml', 'earnoo-money.info', 'econom.co', 'edakgfvwql.ru', 'embedle.com', 'enarchea.com', 'enter-url-1.xyz', 'extener.com', 'extener.org', 'fast-seo-links.com', 'fbdownloader.com', 'fbfreegifts.com', 'feedouble.com', 'feedouble.net', 'for-marketerser.info', 'forum69.info', 'free-seo-consultation.com', 'free-seo-help.org', 'free-share-buttons.co', 'free-share-buttons.com', 'free-social-buttons.com', 'free-stat1.xyz', 'generalporn.org', 'get-here-web.com', 'get-links-seo.com', 'get-more-freeen-visitors.info', 'get-seo-help.com', 'getity-more-free-visitors.info', 'gobongo.info', 'googlsucks.com', 'gotovim-doma.ru', 'growth-hackinger.info', 'guardlink.org', 'howblog.top', 'howtostopreferralspam.eu', 'hulfingtonpost.com', 'humanorightswatch.org', 'ilovevitaly.co', 'ilovevitaly.com', 'ilovevitaly.ru', 'iskalko.ru', 'ivegetadsincome.info', 'japfm.com', 'joinandplay.me', 'joingames.org', 'kakablog.net', 'kambasoft.com', 'krumble-adsist.info', 'krumble-adsive.info', 'krumblede-ads.info', 'krumblely-advertising.info', 'lapik1.xyz', 'lavasoft.gosearchresults.com', 'lets-go-now.com', 'local-seo-for-multiple-locations.com', 'lomb.co', 'lombia.co', 'luxup.ru', 'meendo-free-traffic.ga', 'musicprojectfoundation.com', 'my-seo-promotion.com', 'myftpupload.com', 'myprintscreen.com', 'netvibes.com', 'notify.bluecoat.com', 'nubuildered.info', 'nubuilderify.info', 'nubuilderist.info', 'o-o-6-o-o.com', 'o-o-8-o-o.com', 'offers.bycontext.com', 'one-a-plus.xyz', 'openfrost.com', 'openfrost.net', 'openmediasoft.com', 'oqowsjujpjgqgrlzac.com', 'perform-like-alibabaty.info', 'pingl.net', 'pornhub-forum.ga', 'pornhubforum.tk', 'powitania.pl', 'priceg.com', 'prodvigator.ua', 'promotion-for99.com', 'ranksonic.info', 'ranksonic.org', 'rapidgator-porn.ga', 'redtube-talk.ga', 'resell-seo-services.com', 'resellerclub.com', 's.click.aliexpress.com', 'sanjosestartups.com', 'screentoolkit.com', 'sdrlj.enarchea.com', 'search.tb.ask.com', 'secret-promotion.com', 'securesuite.co.uk', 'semalt.com', 'semaltmedia.com', 'seo-b2b-analytics.com', 'seo-b2b.com', 'seo-for-b2b.com', 'seo-helper.org', 'seo-on-auto.com', 'seo-services-ads.com', 'seo-services-b2b.com', 'seo-services-with-results.com', 'seo-services-wordpress.com', 'seoexperimenty.ru', 'seogoodhelper.com', 'serw.clicksor.com', 'sexyteens.hol.es', 'sharebutton.net', 'sharebutton.org', 'simple-share-buttons.com', 'site.ru', 'sitevaluation.org', 'slftsdybbg.ru', 'smailik.org', 'social-buttons.com', 'socialseet.ru', 'softomix.com', 'softomix.net', 'softomix.ru', 'soundfrost.org', 'srecorder.com', 'star61.de', 'start.otgmanagement.com', 'success-seo.com', 'super-seo-guru.com', 'superiends.org', 'symbaloo.com', 'synerity.com', 'tasteidea.com', 'theguardlan.com', 'torontoplumbinggroup.com', 'torture.ml', 'traffic-paradise.org', 'trafficmonetize.org', 'vapmedia.org', 'viandpet.com', 'videofrost.com', 'videos-for-your-business.com', 'view.contextualyield.com', 'vodkoved.ru', 'wakeupseoconsultant.com', 'webcrawler.com', 'webmaster-traffic.com', 'webmonetizer.net', 'website-errors-scanner.com', 'websocial.me', 'worldwide-seo-services.com', 'www.event-tracking.com', 'www.get-free-traffic-now.com', 'www.kabbalah-red-bracelets.com', 'www1.free-share-buttons.top', 'yes-do-now.com', 'yesgood-now.com', 'ykecwqlixx.ru', 'yougetsignal.com', 'youporn-forum.ga', 'youporn-forum.uni.me', 'your-good-links.com', 'your-seo-promotion-service.com', 'youtubedownload.org', 'zazagames.org', 'checkthat.de', 'malta1877.startdedicated.de', 'malta2080.startdedicated.de', 'malta2265.startdedicated.de', 'startdedicated.de', 'great-n1.xyz', 'muscleswow.xyz', 'tusdstore.xyz');
}
/*-------------------------------------------------------------
 Name:      asb_edit_htaccess
 Since:		2.0
-------------------------------------------------------------*/
function asb_edit_htaccess() {
	if(file_exists(ABSPATH.'.htaccess')){
		$file_content = $new_content = array();

		// Create domain array
		$standard_domains = asb_domain_list();
		$custom_domains = get_option('ajdg_spamblocker_domains', array());
		$rules = array_unique(array_merge($standard_domains, $custom_domains['domains']));

		// Update domain count
		$custom_domains['domain_count'] = count($rules);
		update_option('ajdg_spamblocker_domains', $custom_domains);

		// Remove current rules
		asb_clean_htaccess('# Analytics Spam Blocker - Start', '# Analytics Spam Blocker - End');

		$new_content[] = "\n\n# Analytics Spam Blocker - Start\n";
		foreach($rules as $rule) {
			$new_content[] = "SetEnvIfNoCase Referer $rule spambot=yes\n";
		}
		$new_content[] = "Order allow,deny\n";
		$new_content[] = "Allow from all\n";
		$new_content[] = "Deny from env=spambot\n";
		$new_content[] = "# Analytics Spam Blocker - End\n";

		// Read current file without rules
		$file_content = file(ABSPATH.'.htaccess');
		$file_content = array_merge($file_content, $new_content);

		// Write new rules
		$fp = fopen(ABSPATH.'.htaccess', 'w');
		foreach($file_content as $line){
			fwrite($fp, "$line");
		}
		fclose($fp);
		unset($standard_domains, $custom_domains, $rules, $fp);
	}
}

/*-------------------------------------------------------------
 Name:      asb_clean_htaccess
 Since:		2.0
-------------------------------------------------------------*/
function asb_clean_htaccess($start, $end) {
	$file_content = file_get_contents(ABSPATH.'.htaccess');
	$beginning_position = strpos($file_content, $start);
	$ending_position = strpos($file_content, $end);

	if($beginning_position !== false AND $ending_position !== false) {
		$delete_old = substr($file_content, $beginning_position, ($ending_position + strlen($end)) - $beginning_position); // Determine what to delete

		$clean_content = str_replace($delete_old, '', $file_content); // Remove current/old rules
		$clean_content = trim($clean_content, " \t\n\r"); // Remove stray crap at the end of the file

		$fp = fopen(ABSPATH.'.htaccess', 'w');
		fwrite($fp, $clean_content);
		fclose($fp);

		unset($delete_old, $clean_content, $fp);
	}
	unset($file_content);
}

/*-------------------------------------------------------------
 Name:      asb_notifications_dashboard
 Since:		2.1
-------------------------------------------------------------*/
function spamblocker_notifications_dashboard() {
	global $current_user;

	if(isset($_GET['hide'])) {
		if($_GET['hide'] == 1) update_option('ajdg_spamblocker_hide_review', 1);
		if($_GET['hide'] == 2) update_option('ajdg_spamblocker_hide_birthday', current_time('timestamp') + (10 * MONTH_IN_SECONDS));
	}

	$displayname = (strlen($current_user->user_firstname) > 0) ? $current_user->user_firstname : $current_user->display_name;

	// Updates
	$asb_version = get_option('ajdg_spamblocker_version', 0);
	if($asb_version < ASB_DATABASE) {
		echo '<div class="ajdg-spamblocker-notification notice" style="">';
		echo '	<div class="ajdg-spamblocker-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
		echo '	<div class="ajdg-spamblocker-notification-message">Thanks for updating <strong>'.$displayname.'</strong>! You have almost completed updating <strong>Analytics Spam Blocker</strong>!<br />To complete the update <strong>click the button on the right</strong>. This may take a few seconds to complete!<br />For an overview of what has changed take a look at the <a href="https://ajdg.solutions/product/analytics-spam-blocker-for-wordpress/?mtm_campaign=spamblocker&mtm_keyword=finish_update_notification" target="_blank">development page</a> and usually there is an article on <a href="https://ajdg.solutions/blog/?mtm_campaign=spamblocker&mtm_keyword=finish_update_notification" target="_blank">the blog</a> with more information as well.</div>';
		echo '	<div class="ajdg-spamblocker-notification-cta">';
		echo '		<a href="tools.php?page=analytics-spam-blocker&action=update-db" class="ajdg-spamblocker-notification-act button-primary update-button">Finish update</a>';
		echo '	</div>';
		echo '</div>';
	}

	// Review
	$review_banner = get_option('ajdg_spamblocker_hide_review');
	if($review_banner != 1 AND $review_banner < (current_time('timestamp') - 1214600)) {
		echo '<div class="ajdg-spamblocker-notification notice" style="">';
		echo '	<div class="ajdg-spamblocker-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
		echo '	<div class="ajdg-spamblocker-notification-message">If you like <strong>Analytics Spam Blocker</strong> let everyone know that you do. Thanks for your support!<br /><span>If you have questions, suggestions or something else that doesn\'t belong in a review, please <a href="https://wordpress.org/support/plugin/analytics-spam-blocker/" target="_blank">get in touch</a>!</span></div>';
		echo '	<div class="ajdg-spamblocker-notification-cta">';
		echo '		<a href="https://wordpress.org/support/plugin/analytics-spam-blocker/reviews?rate=5#postform" class="ajdg-notification-act button-primary">Write Review</a>';
		echo '		<a href="tools.php?page=analytics-spam-blocker&hide=1" class="ajdg-notification-dismiss">Maybe later</a>';
		echo '	</div>';
		echo '</div>';
	}

	// Birthday
	$birthday_banner = get_option('ajdg_spamblocker_hide_birthday');
	if($birthday_banner < current_time('timestamp') AND date('M', current_time('timestamp')) == 'Feb') {
		echo '<div class="ajdg-spamblocker-notification notice" style="">';
		echo '	<div class="ajdg-spamblocker-notification-logo" style="background-image: url(\''.plugins_url('/images/birthday.png', __FILE__).'\');"><span></span></div>';
		echo '	<div class="ajdg-spamblocker-notification-message">Hey <strong>'.$displayname.'</strong>! Did you know it is Arnan his birtyday this month? February 9th to be exact. Wish him a happy birthday via Telegram!<br />Who is Arnan? He made Analytics Spam Blocker for you - Check out his <a href="https://www.arnan.me/?mtm_campaign=spamblocker&mtm_keyword=birthday_banner" target="_blank">website</a> or <a href="https://www.arnan.me/donate.html?mtm_campaign=spamblocker&mtm_keyword=birthday_banner" target="_blank">send a gift</a>.</div>';
		echo '	<div class="ajdg-spamblocker-notification-cta">';
		echo '		<a href="https://t.me/arnandegans" target="_blank" class="ajdg-spamblocker-notification-act button-primary goosebox"><i class="icn-tg"></i>Wish Happy Birthday</a>';
		echo '		<a href="tools.php?page=analytics-spam-blocker&hide=2" class="ajdg-spamblocker-notification-dismiss">Done it</a>';
		echo '	</div>';
		echo '</div>';
	}
}

/*-------------------------------------------------------------
 Name:      asb_action_links
 Since:		2.7.1
-------------------------------------------------------------*/
function asb_action_links($links) {
	$links['asb-settings'] = sprintf('<a href="%s">%s</a>', admin_url('tools.php?page=analytics-spam-blocker'), 'Settings');
	$links['asb-help'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/forums/forum/analytics-spam-blocker/?mtm_campaign=spamblocker', 'Support');
	$links['asb-plugins'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/plugins/?mtm_campaign=spamblocker', 'More plugins');

	return $links;
}

/*-------------------------------------------------------------
 Name:      asb_return
 Since:		2.0
-------------------------------------------------------------*/
function asb_return($status, $args = null) {

	if($status > 0 AND $status < 1000) {
		$defaults = array(
			'status' => $status
		);
		$arguments = wp_parse_args($args, $defaults);
		$redirect = 'tools.php?page=analytics-spam-blocker&'.http_build_query($arguments);
	} else {
		$redirect = 'tools.php?page=analytics-spam-blocker';
	}

	wp_redirect($redirect);
}

/*-------------------------------------------------------------
 Name:      asb_status
 Since:		2.0
-------------------------------------------------------------*/
function asb_status($status, $args = null) {
	switch($status) {
		case '200' :
			echo '<div class="ajdg-spamblocker-notification notice" style="">';
			echo '	<div class="ajdg-spamblocker-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
			echo '	<div class="ajdg-spamblocker-notification-message"><strong>'.__('Thank you for your report!!', 'analytics-spam-blocker').'</strong><br /><span>'.__('The domain has been added to your custom block list. When the domain gets a few reports it will be included in the standard blocklist so everyone will benefit from your efforts.', 'analytics-spam-blocker').'</span></div>';
			echo '</div>';
		break;

		case '201' :
			echo '<div class="ajdg-spamblocker-notification notice" style="">';
			echo '	<div class="ajdg-spamblocker-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
			echo '	<div class="ajdg-spamblocker-notification-message"><strong>'.__('Report received!!', 'analytics-spam-blocker').'</strong><br /><span>'.__('The report has been received but did not meet the criteria for submission. Commonly this happens if a invalid domainname was submitted or information was missing from the form. Please try again later, or contact support if the issue persists.', 'analytics-spam-blocker').'</span></div>';
			echo '</div>';
		break;

		// (all) Error messages
		case '400' :
			echo '<div id="message" class="error"><p>'.__('Your name, email address and a referral domain name are required. Please try again.', 'analytics-spam-blocker').'</p></div>';
		break;

		case '401' :
			echo '<div id="message" class="error"><p>'.__('The domain you are trying to report is not valid. Please try again.', 'analytics-spam-blocker').'</p></div>';
		break;

		case '402' :
			echo '<div id="message" class="error"><p>'.__('Please use your valid email address', 'analytics-spam-blocker').'</p></div>';
		break;

		case '500' :
			echo '<div id="message" class="error"><p>'. __('The server responded with an error.', 'adrotate-pro') .'<br />'.$args['error'].'</p></div>';
		break;

		default :
			echo '<div id="message" class="error"><p>'.__('Unexpected error', 'analytics-spam-blocker').'</p></div>';
		break;
	}

	unset($args);
}

/*-------------------------------------------------------------
 Name:      asb_nonce_error
 Purpose:   Display a formatted error if Nonce fails
 Since:		2.0
-------------------------------------------------------------*/
function asb_nonce_error() {
	echo '	<h2 style="text-align: center;">'.__('Oh no! Something went wrong!', 'analytics-spam-blocker').'</h2>';
	echo '	<p style="text-align: center;">'.__('WordPress was unable to verify the authenticity of the url you have clicked. Verify if the url used is valid or log in via your browser.', 'analytics-spam-blocker').'</p>';
	echo '	<p style="text-align: center;">'.__('If you have received the url you want to visit via email, you are probably being tricked!', 'analytics-spam-blocker').'</p>';
	echo '	<p style="text-align: center;">'.__('Contact support if the issue persists:', 'analytics-spam-blocker').' <a href="https://wordpress.org/support/plugin/analytics-spam-blocker/" title="AJdG Solutions Support" target="_blank">AJdG Solutions Support</a>.</p>';
}
?>
