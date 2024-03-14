<?php
/**
 * Plugin Name: TraceMyIP.org Plugin
 * Plugin URI: https://www.tracemyip.org
*/
define("UVAR",":-v-:");				// tools URL var separator
if (!function_exists('tmip_link')) {function tmip_link($u,$n,$t='_blank'){return '<a href="'.$u.'" target="'.$t.'">'.$n.'</a>';}}

define("tmip_service_Nname", 		'TraceMyIP');
define("tmip_service_Dname", 		'TraceMyIP.org');

define("tmip_domain_prot", 			'https');
define("tmip_subdomain", 			'www');
define("tmip_domain_name", 			'tracemyip.org');  #  tracemyip
define("tmip_service_url", 			tmip_domain_prot.'://'.tmip_subdomain.'.'.tmip_domain_name);

define("tmip_learnbl_url", 			tmip_service_url.'/learn/');
define("tmip_support_url", 			tmip_service_url.'/contact.htm');
define("tmip_acc_upgr_url", 		
	   				tmip_service_url.'/members/index.php?rnDs=1&page=spm_checkout&type=ssub&stp=acup&wplk_pro_upgrade=20423014510');

// URLS
define("tmip_support_link", 		tmip_link(tmip_support_url,'<b>'.tmip_support_url.'</b>'));
define("tmip_lrn_invtrk_url", 		tmip_learnbl_url.'how-to-make-a-visitor-tracker-invisible-91/');

// FORM STATEMENTS
define("tmip_lang_visitor_tr_code", 'Visitor Tracker Code');
define("tmip_lang_visitr_track_ic", 'Visitor Tracker Icon');
define("tmip_lang_tracker_icon_ps", 'Tracker Icon Position');
define("tmip_lang_page_tr_code", 	'Page Tracker Code');
define("tmip_lang_update_settings", 'Update Settings');

// WORDS
define("tmip_alert_box_message", 	'ALERT');
define("tmip_invisible_tracker", 	'invisible tracker');

// MAIN WP MENU
define("tmip_menu_name", 			tmip_service_Nname);
define("tmip_submenu_reports", 		'reports');
define("tmip_submenu_settings", 	'settings');
define("tmip_submenu_unlock_frt", 	'unlock features');
define("tmip_submenu_my_ipv46_adr", 'my current IP');
define("tmip_submenu_ip_tools", 	'IP Tools');
define("tmip_submenu_rate_service", 'rate '.tmip_service_Nname);

// SECTION TITLES
define("tmip_sectl_what_is_plugin", 'what is '.tmip_service_Nname.'?');
define("tmip_sectl_easy_steps_set", '5 <b class="tmip_multicolor_text">Easy Steps</b> to Setup');

// INTERNAL CODES USAGE STATS
define("tmip_stats_receive_trdata", 'receiving data');
define("tmip_stats_pending_trdata", 'ready');
define("tmip_stats_activet_pgdata", 'active');
define("tmip_stats_pending_pgdata", 'ready');
define("tmip_stats_status_pagntra", 'status');
define("tmip_stats_activi_pagntra", 'activity');
define("tmip_stats_optimi_pagntra", 'optimized');
define("tmip_stats_used_since_unx", 'started');


// PHRASES
define("tmip_lang_please_rate_us", 	'Rate');

define("tmip_settings_hv_updated", 	'Settings have been updated!');

define("tmip_upgrade_to_pro_vers", 	'<b>Upgrade to PRO</b>');

define("tmip_settings_no_changes", 	'No changes detected');
define("tmip_settings_reman_same", 	'The settings have remained the same.');

define("tmip_prov_trackerc_valid", 	'The <b>Visitor Tracker</b> code has been installed');

define("tmip_trk_code_inst_refrm", 	'The <b>Visitor Tracker</b> code you have used has been installed and re-formatted to produce the best tracking results for your WordPress version.');

define("tmip_prov_trk_code_notva", 	'The tracker code you have provided is not valid. Please generate a new tracker code at '.tmip_service_Dname.'. If you already have an account, login to your account, and click on the [tracker code] link located next to your project name. Error [%ERR_NUM%]');

define("tmip_check_trk_code_inst", 	"<b>Verify that the tracker image now appears on <u>all</u> of your pages</b>.
<br>If you need a hidden version of the tracker, login to your '.tmip_service_Dname.' account. Click on the [edit] link located to the right of your project name on [My Projects] page, scroll down and enable the [Invisible Tracker] option.

<hr>* <b>If you're using a page cache plugin or optimization plugins</b>, consider purging the cache to ensure the tracking program loads correctly. If the tracker image remains absent on your pages, you might need to temporarily disable your cache or optimization plugins to diagnose the issue. Once identified, adjust the configuration settings within those plugins to allow the tracker code to load properly.");

define("tmip_prov_trackerp_valid", 	'The <b>Page Tracker</b> code has been installed');

define("tmip_pagetr_into_vistrak", 	'You have placed a [<b>'.tmip_lang_page_tr_code.'</b>] into the [<b>'.tmip_lang_visitor_tr_code.'</b>] input box. Please paste the code into the ['.tmip_lang_page_tr_code.'] input box');

define("tmip_vistrk_into_pagetrk", 	'You have placed a [<b>'.tmip_lang_visitor_tr_code.'</b>] into the [<b>'.tmip_lang_page_tr_code.'</b>] input box. Please paste the code into the ['.tmip_lang_visitor_tr_code.'] input box');

define("tmip_pagetr_code_notjava", 	'The [<b>'.tmip_lang_page_tr_code.'</b>] you have entered is not a <b>JavaScript</b> version of the '.tmip_lang_page_tr_code.'. Use a <b>JavaScript</b> version of the <b>'.tmip_lang_page_tr_code.'</b> and place it into the '.tmip_lang_page_tr_code.' input box.');

define("tmip_pagetr_cd_not_valid", 	'The [<b>'.tmip_lang_page_tr_code.'</b>] you have entered is not a valid '.tmip_service_Dname.' code.');
define("tmip_pagetr_generate_npl", 	'Generate a <b>JavaScript</b> version of the <b>'.tmip_lang_page_tr_code.'</b> and place it into the '.tmip_lang_page_tr_code.' input box.');

define("tmip_vistr_cd_not_valid", 	'The [<b>'.tmip_lang_visitor_tr_code.'</b>] has been removed');
define("tmip_pagtr_cd_not_valid", 	'The [<b>'.tmip_lang_page_tr_code.'</b>] has been removed.');

define("tmip_vistr_set_to_headA", 	'The [<b>'.tmip_lang_visitor_tr_code.'</b>] has been assigned to <b>header</b>.');
define("tmip_vistr_set_to_footA", 	'The [<b>'.tmip_lang_visitor_tr_code.'</b>] has been assigned to <b>footer</b>.');

define("tmip_tracker_code_ent_nv", 	'The [<b>'.tmip_lang_visitor_tr_code.'</b>] you have entered is not a valid '.tmip_service_Dname.' code');

define("tmip_no_code_entered_alr", 	'Your '.tmip_service_Dname.' plugin is ready but the <b>['.tmip_lang_visitor_tr_code.'</b>] first needs to be placed into the ['.tmip_lang_visitor_tr_code.'] input box. Please refer to [<b>'.tmip_sectl_easy_steps_set.'</b>] instructions below.');

define("tmip_pagetr_no_vis_tralrt", 'You have installed the ['.tmip_lang_page_tr_code.'] but to make it work, you need to have the ['.tmip_lang_visitor_tr_code.'] installed.');


define("tmip_vis_trk_inp_placehl", 	'Follow these 3 steps for easy setup:
	   
1. Go to '.tmip_domain_name.' and register an account and follow the setup steps.
2. Copy and paste the provided visitor tracker code here, click on the update button and check that the tracker icon appears on all pages of your site
3. Login to your '.tmip_service_Nname.' account to immediately see your website traffic activity
');
define("tmip_pag_trk_inp_placehl", 	'To setup a Page Tracker, install a ['.tmip_lang_visitor_tr_code.'] first, then:

1. Login to your '.tmip_service_Dname.' account
2. Go to [My Projects] and click on the Page Tracker button for your visitor tracker project
3. At the bottom of the page, click on [Add New Page Tracker] link and follow the setup wizard
');

define("tmip_iframe_javascript_n", 	'Your browser does not support iframes, which is required by '.tmip_domain_name.' to display data within your WordPress dashboard.');


define("tmip_invalid_nonce_check", 	'Invalid request [ER-05222007]. Please contact support at '.tmip_support_link);

// ELEMENTS
$fa_check_mark='';
define("tmip_fa_checkmark_lg", 			'<i class="fa fa-check fa-lg" style="opacity: 0.8;"></i>');
define("tmip_fa_excl_triangle_lg", 		'<i class="fa fa-exclamation-triangle fa-lg" style="color: opacity: 0.8;"></i>');
define("tmip_fa__hand_point_right_lg", 	'<i class="fa fa-hand-point-right fa-lg" style="color: opacity: 0.8;"></i>');
?>