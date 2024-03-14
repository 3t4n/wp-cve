<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

// MATOMO
require_once dirname(__FILE__) . '/options-matomo.php';

// Clarity
require_once dirname(__FILE__) . '/options-clarity.php';

// Google Analytics
function siteseo_cookies_user_consent_html(){
	if ('' != siteseo_get_service('GoogleAnalyticsOption')->getOptOutMsg()) {
		$msg = siteseo_get_service('GoogleAnalyticsOption')->getOptOutMsg();
	} elseif (get_option('wp_page_for_privacy_policy')) {
		$msg = __('By visiting our site, you agree to our privacy policy regarding cookies, tracking statistics, etc.&nbsp;<a href="[siteseo_privacy_page]">Read more</a>', 'siteseo');
	} else {
		$msg = __('By visiting our site, you agree to our privacy policy regarding cookies, tracking statistics, etc.', 'siteseo');
	}

	if (get_option('wp_page_for_privacy_policy') && '' != $msg) {
		$siteseo_privacy_page = esc_url(get_permalink(get_option('wp_page_for_privacy_policy')));
		$msg = str_replace('[siteseo_privacy_page]', $siteseo_privacy_page, $msg);
	}

	$msg = apply_filters('siteseo_cookie_message', $msg);

	$consent_btn = siteseo_get_service('GoogleAnalyticsOption')->getOptOutMessageOk();
	if(empty($consent_btn) || !$consent_btn){
		$consent_btn = __('Accept', 'siteseo');
	}

	$close_btn = siteseo_get_service('GoogleAnalyticsOption')->getOptOutMessageClose();
	if(empty($close_btn) || !$close_btn){
		$close_btn = __('X', 'siteseo');
	}

	$user_msg = '<div data-nosnippet class="siteseo-user-consent siteseo-user-message siteseo-user-consent-hide">
		<p>' . wp_kses_post($msg) . '</p>
		<p>
			<button id="siteseo-user-consent-accept" type="button">' . esc_html($consent_btn) . '</button>
			<button type="button" id="siteseo-user-consent-close">' . esc_html($close_btn) . '</button>
		</p>
	</div>';

	$backdrop = '<div class="siteseo-user-consent-backdrop siteseo-user-consent-hide"></div>';

	$user_msg = apply_filters('siteseo_rgpd_full_message', $user_msg, $msg, $consent_btn, $close_btn, $backdrop);

	echo wp_kses($user_msg, ['div' => ['data-nosnippet' => true, 'class' => true], 'a' => ['href' => true], 'p' => true, 'button' => ['id' => true, 'type' => true]]) . wp_kses_post($backdrop);
}

function siteseo_cookies_edit_choice_html(){
	$optOutEditChoice = siteseo_get_service('GoogleAnalyticsOption')->getOptOutEditChoice();
	if ('1' !== $optOutEditChoice) {
		return;
	}

	$edit_cookie_btn = siteseo_get_service('GoogleAnalyticsOption')->getOptOutMessageEdit();
	if (empty($edit_cookie_btn) || !$edit_cookie_btn) {
		$edit_cookie_btn = __('Manage cookies', 'siteseo');
	}

	$user_msg = '<div data-nosnippet class="siteseo-user-consent siteseo-edit-choice">
		<p>
			<button id="siteseo-user-consent-edit" type="button">' . esc_html($edit_cookie_btn) . '</button>
		</p>
	</div>';

	$user_msg = apply_filters('siteseo_rgpd_full_message', $user_msg, $edit_cookie_btn);

	echo wp_kses($user_msg, ['div' => ['class' => true, 'data-nosnippet' => true], 'p' => true, 'button' => ['id' => true, 'type' => true]]);
}

function siteseo_cookies_user_consent_styles() {
	$styles = '.siteseo-user-consent {left: 50%;position: fixed;z-index: 8000;padding: 20px;display: inline-flex;justify-content: center;border: 1px solid #CCC;max-width:100%;';

	//Width
	$width = siteseo_get_service('GoogleAnalyticsOption')->getCbWidth();
	if (!empty($width)) {
		$needle = '%';

		if (false !== strpos($width, $needle)) {
			$unit = '';
		} else {
			$unit = 'px';
		}

		$styles .= 'width: ' . $width . $unit . ';';
	} else {
		$styles .= 'width:100%;';
	}

	//Position
	$position = siteseo_get_service('GoogleAnalyticsOption')->getCbPos();
	if ('top' === $position) {
		$styles .= 'top:0;';
		$styles .= 'transform: translate(-50%, 0%);';
	} elseif ('center' === $position) {
		$styles .= 'top:45%;';
		$styles .= 'transform: translate(-50%, -50%);';
	} else {
		$styles .= 'bottom:0;';
		$styles .= 'transform: translate(-50%, 0);';
	}

	//Text alignment
	$txtAlign = siteseo_get_service('GoogleAnalyticsOption')->getCbTxtAlign();
	if ('left' === $txtAlign) {
		$styles .= 'text-align:left;';
	} elseif ('right' === $position) {
		$styles .= 'text-align:right;';
	} else {
		$styles .= 'text-align:center;';
	}

	//Background color
	$bgColor = siteseo_get_service('GoogleAnalyticsOption')->getCbBg();
	if (!empty($bgColor)) {
		$styles .= 'background:' . $bgColor . ';';
	} else {
		$styles .= 'background:#F1F1F1;';
	}

	$styles .= '}@media (max-width: 782px) {.siteseo-user-consent {display: block;}}.siteseo-user-consent.siteseo-user-message p:first-child {margin-right:20px}.siteseo-user-consent p {margin: 0;font-size: 0.8em;align-self: center;';

	//Text color
	$txtColor = siteseo_get_service('GoogleAnalyticsOption')->getCbTxtCol();
	if (!empty($txtColor)) {
		$styles .= 'color:' . $txtColor . ';';
	}

	$styles .= '}.siteseo-user-consent button {vertical-align: middle;margin: 0;font-size: 14px;';

	//Btn background color
	$btnBgColor = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnBg();
	if (!empty($btnBgColor)) {
		$styles .= 'background:' . $btnBgColor . ';';
	}

	//Btn text color
	$btnTxtColor = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnCol();
	if (!empty($btnTxtColor)) {
		$styles .= 'color:' . $btnTxtColor . ';';
	}

	$styles .= '}.siteseo-user-consent button:hover{';

	//Background hover color
	$bgHovercolor = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnBgHov();
	if (!empty($bgHoverColor)) {
		$styles .= 'background:' . $bgHoverColor . ';';
	}

	//Text hover color
	$txtHovercolor = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnColHov();
	if (!empty($txtHoverColor)) {
		$styles .= 'color:' . $txtHoverColor . ';';
	}

	$styles .= '}#siteseo-user-consent-close{margin: 0;position: relative;font-weight: bold;border: 1px solid #ccc;';

	//Background secondary button
	$bgSecondaryBtn = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnSecBg();
	if (!empty($bgSecondaryBtn)) {
		$styles .= 'background:' . $bgSecondaryBtn . ';';
	} else {
		$styles .= 'background:none;';
	}

	//Color secondary button
	$colorSecondaryBtn = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnSecCol();
	if (!empty($colorSecondaryBtn)) {
		$styles .= 'color:' . $colorSecondaryBtn . ';';
	} else {
		$styles .= 'color:inherit;';
	}

	$styles .= '}#siteseo-user-consent-close:hover{cursor:pointer;';

	//Background secondary button hover
	$bgSecondaryBtnHover = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnSecBgHov();
	if (!empty($bgSecondaryBtnHover)) {
		$styles .= 'background:' . $bgSecondaryBtnHover . ';';
	} else {
		$styles .= 'background:#222;';
	}

	//Color secondary button hover
	$colorSecondaryBtnHover = siteseo_get_service('GoogleAnalyticsOption')->getCbBtnSecColHov();
	if (!empty($colorSecondaryBtnHover)) {
		$styles .= 'color:' . $colorSecondaryBtnHover . ';';
	} else {
		$styles .= 'color:#fff;';
	}

	$styles .= '}';

	//Link color
	$linkColor = siteseo_get_service('GoogleAnalyticsOption')->getCbLkCol();
	if (!empty($linkColor)) {
		$styles .= '.siteseo-user-consent a{';
		$styles .= 'color:' . $linkColor;
		$styles .= '}';
	}

	$styles .= '.siteseo-user-consent-hide{display:none;}';

	$cbBackdrop = siteseo_get_service('GoogleAnalyticsOption')->getCbBackdrop();
	if (!empty($cbBackdrop)) {
		$bg_backdrop = siteseo_get_service('GoogleAnalyticsOption')->getCbBackdropBg();
		if (empty($bg_backdrop) || !$bg_backdrop) {
			$bg_backdrop = 'rgba(0,0,0,.65)';
		}

		$styles .= '.siteseo-user-consent-backdrop{-webkit-box-align: center;
			-webkit-align-items: center;
			-ms-flex-align: center;
			align-items: center;
			background: ' . esc_attr($bg_backdrop) . ';
			bottom: 0;
			-webkit-box-orient: vertical;
			-webkit-box-direction: normal;
			-webkit-flex-direction: column;
			-ms-flex-direction: column;
			flex-direction: column;
			left: 0;
			-webkit-overflow-scrolling: touch;
			overflow-y: auto;
			position: fixed;
			right: 0;
			-webkit-tap-highlight-color: transparent;
			top: 0;
			z-index: 100;}';
	}

	$styles .= '.siteseo-edit-choice{
		background: none;
		justify-content: start;
		z-index: 7500;
		border: none;
		width: inherit;
		transform: none;
		left: inherit;
		bottom: 0;
		top: inherit;
	}';

	$styles = apply_filters('siteseo_rgpd_full_message_styles', $styles);

	echo '<style>'. esc_html($styles) . '</style>'; 
}

function siteseo_cookies_user_consent_render() {
	$hook = siteseo_get_service('GoogleAnalyticsOption')->getHook();
	if (empty($hook) || !$hook) {
		$hook = 'wp_head';
	}

	add_action($hook, 'siteseo_cookies_user_consent_html');
	add_action($hook, 'siteseo_cookies_edit_choice_html');
	add_action($hook, 'siteseo_cookies_user_consent_styles');
}

if ('1' == siteseo_get_service('GoogleAnalyticsOption')->getDisable()){
	if (is_user_logged_in()) {
		global $wp_roles;

		//Get current user role
		if (isset(wp_get_current_user()->roles[0])) {
			$siteseo_user_role = wp_get_current_user()->roles[0];
			//If current user role matchs values from SiteSEO GA settings then apply
			if ('1' == siteseo_get_toggle_option('google-analytics') && '' != siteseo_get_service('GoogleAnalyticsOption')->getRoles()) {
				if (array_key_exists($siteseo_user_role, siteseo_get_service('GoogleAnalyticsOption')->getRoles())) {
					//do nothing
				} else {
					siteseo_cookies_user_consent_render();
				}
			} else {
				siteseo_cookies_user_consent_render();
			}
		} else {
			siteseo_cookies_user_consent_render();
		}
	} else {
		siteseo_cookies_user_consent_render();
	}
}

// Optimize
function siteseo_google_analytics_optimize_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getOptimize();
}

// Ads
function siteseo_google_analytics_ads_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getAds();
}

// Additional tracking code - head
function siteseo_google_analytics_other_tracking_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getOtherTracking();
}

// Additional tracking code - body
function siteseo_google_analytics_other_tracking_body_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getOtherTrackingBody();
}

// Additional tracking code - footer
function siteseo_google_analytics_other_tracking_footer_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getOtherTrackingFooter();
}

// Remarketing
function siteseo_google_analytics_remarketing_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getRemarketing();
}

// IP Anonymization
function siteseo_google_analytics_ip_anonymization_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getIpAnonymization();
}

// Link attribution
function siteseo_google_analytics_link_attribution_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getLinkAttribution();
}

// Cross Domain Enable
function siteseo_google_analytics_cross_enable_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getCrossEnable();
}

// Cross Domain
function siteseo_google_analytics_cross_domain_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getCrossDomain();
}

// Events external links tracking Enable
function siteseo_google_analytics_link_tracking_enable_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getLinkTrackingEnable();
}

// Events downloads tracking Enable
function siteseo_google_analytics_download_tracking_enable_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getDownloadTrackingEnable();
}

// Events tracking file types
function siteseo_google_analytics_download_tracking_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getDownloadTracking();
}

// Events affiliate links tracking Enable
function siteseo_google_analytics_affiliate_tracking_enable_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getAffiliateTrackingEnable();
}

// Events tracking affiliate match
function siteseo_google_analytics_affiliate_tracking_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getAffiliateTracking();
}

// Events Phone tracking Enable
function siteseo_google_analytics_phone_tracking_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getPhoneTracking();
}

// Custom Dimension Author
function siteseo_google_analytics_cd_author_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getCdAuthor();
}

// Custom Dimension Category
function siteseo_google_analytics_cd_category_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getCdCategory();
}

// Custom Dimension Tag
function siteseo_google_analytics_cd_tag_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getCdTag();
}

// Custom Dimension Post Type
function siteseo_google_analytics_cd_post_type_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getCdPostType();
}

// Custom Dimension Logged In
function siteseo_google_analytics_cd_logged_in_user_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getCdLoggedInUser();
}

// Get option for "Measure purchases"
function siteseo_google_analytics_purchases_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getPurchases();
}

// Get option for "Add to cart event"
function siteseo_google_analytics_add_to_cart_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getAddToCart();
}

// Get option for "Remove from cart event"
function siteseo_google_analytics_remove_from_cart_option() {
	return siteseo_get_service('GoogleAnalyticsOption')->getRemoveToCart();
}

// Build Custom GA
function siteseo_google_analytics_js($echo) {
	
	$google_analytics_option = siteseo_get_service('GoogleAnalyticsOption');
	
	if ('' != $google_analytics_option->getGA4() && '1' == $google_analytics_option->getEnableOption()){
		//Init
		$tracking_id = $google_analytics_option->getGA4();
		$siteseo_google_analytics_config = [];
		$siteseo_google_analytics_event  = [];

		$siteseo_google_analytics_html = "\n";
		$siteseo_google_analytics_html .=
		"<script async src='https://www.googletagmanager.com/gtag/js?id=" . $tracking_id . "'></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}";
		$siteseo_google_analytics_html .= "gtag('js', new Date());\n";

		// Cross domains
		$crossDomains = $google_analytics_option->getCrossDomain();
		if ('1' == $google_analytics_option->getCrossEnable() && $crossDomains) {
			$domains = array_map('trim', array_filter(explode(',', $crossDomains)));

			if ( ! empty($domains)) {
				$domains_count = count($domains);

				$link_domains = '';

				foreach ($domains as $key => $domain) {
					$link_domains .= "'" . $domain . "'";
					if ($key < $domains_count - 1) {
						$link_domains .= ',';
					}
				}
				$siteseo_google_analytics_config['linker'] = "'linker': {'domains': [" . $link_domains . ']},';
				$siteseo_google_analytics_config['linker'] = apply_filters('siteseo_gtag_linker', $siteseo_google_analytics_config['linker']);
			}
		}

		// Remarketing
		$remarketingOption = $google_analytics_option->getRemarketing();
		if ('1' != $remarketingOption) {
			$siteseo_google_analytics_config['allow_display_features'] = "'allow_display_features': false,";
			$siteseo_google_analytics_config['allow_display_features'] = apply_filters('siteseo_gtag_allow_display_features', $siteseo_google_analytics_config['allow_display_features']);
		}

		// Link attribution
		if ('1' == $google_analytics_option->getLinkAttribution()) {
			$siteseo_google_analytics_config['link_attribution'] = "'link_attribution': true,";
			$siteseo_google_analytics_config['link_attribution'] = apply_filters('siteseo_gtag_link_attribution', $siteseo_google_analytics_config['link_attribution']);
		}

		// Dimensions
		$siteseo_google_analytics_config['cd']['cd_hook'] = apply_filters('siteseo_gtag_cd_hook_cf', isset($siteseo_google_analytics_config['cd']['cd_hook']));
		if ( ! has_filter('siteseo_gtag_cd_hook_cf')) {
			unset($siteseo_google_analytics_config['cd']['cd_hook']);
		}

		$siteseo_google_analytics_event['cd_hook'] = apply_filters('siteseo_gtag_cd_hook_ev', isset($siteseo_google_analytics_event['cd_hook']));
		if ( ! has_filter('siteseo_gtag_cd_hook_ev')) {
			unset($siteseo_google_analytics_config['cd']['cd_hook']);
		}

		$cdAuthorOption = $google_analytics_option->getCdAuthor();
		$cdCategoryOption = $google_analytics_option->getCdCategory();
		$cdTagOption = $google_analytics_option->getCdTag();
		$cdPostTypeOption = $google_analytics_option->getCdPostType();
		$cdLoggedInUserOption = $google_analytics_option->getCdLoggedInUser();
		if ((!empty($cdAuthorOption) && 'none' != $cdAuthorOption)
				|| (!empty($cdCategoryOption) && 'none' != $cdCategoryOption)
				|| (!empty($cdTagOption) && 'none' != $cdTagOption)
				|| (!empty($cdPostTypeOption) && 'none' != $cdPostTypeOption)
				|| (!empty($cdLoggedInUserOption) && 'none' != $cdLoggedInUserOption)
				|| ('' != isset($siteseo_google_analytics_config['cd']['cd_hook']) && '' != isset($siteseo_google_analytics_event['cd_hook']))
			) {
			$siteseo_google_analytics_config['cd']['cd_start'] = '{';
		} else {
			unset($siteseo_google_analytics_config['cd']);
		}

		if (!empty($cdAuthorOption)) {
			if ('none' != $cdAuthorOption) {
				if (is_singular()) {
					$siteseo_google_analytics_config['cd']['cd_author'] = "'" . $cdAuthorOption . "': 'cd_author',";

					$siteseo_google_analytics_event['cd_author'] = "gtag('event', '" . __('Authors', 'siteseo') . "', {'cd_author': '" . get_the_author() . "', 'non_interaction': true});";

					$siteseo_google_analytics_config['cd']['cd_author'] = apply_filters('siteseo_gtag_cd_author_cf', $siteseo_google_analytics_config['cd']['cd_author']);

					$siteseo_google_analytics_event['cd_author'] = apply_filters('siteseo_gtag_cd_author_ev', $siteseo_google_analytics_event['cd_author']);
				}
			}
		}
		if (!empty($cdCategoryOption)) {
			if ('none' != $cdCategoryOption) {
				if (is_single() && has_category()) {
					$categories = get_the_category();

					if ( ! empty($categories)) {
						$get_first_category = esc_html($categories[0]->name);
					}

					$siteseo_google_analytics_config['cd']['cd_categories'] = "'" . $cdCategoryOption . "': 'cd_categories',";

					$siteseo_google_analytics_event['cd_categories'] = "gtag('event', '" . __('Categories', 'siteseo') . "', {'cd_categories': '" . $get_first_category . "', 'non_interaction': true});";

					$siteseo_google_analytics_config['cd']['cd_categories'] = apply_filters('siteseo_gtag_cd_categories_cf', $siteseo_google_analytics_config['cd']['cd_categories']);

					$siteseo_google_analytics_event['cd_categories'] = apply_filters('siteseo_gtag_cd_categories_ev', $siteseo_google_analytics_event['cd_categories']);
				}
			}
		}

		if (!empty($cdTagOption) && 'none' != $cdTagOption) {
			if (is_single() && has_tag()) {
				$tags = get_the_tags();
				if ( ! empty($tags)) {
					$siteseo_comma_count = count($tags);
					$get_tags			 = '';
					foreach ($tags as $key => $value) {
						$get_tags .= esc_html($value->name);
						if ($key < $siteseo_comma_count - 1) {
							$get_tags .= ', ';
						}
					}
				}

				$siteseo_google_analytics_config['cd']['cd_tags'] = "'" . $cdTagOption . "': 'cd_tags',";

				$siteseo_google_analytics_event['cd_tags'] = "gtag('event', '" . __('Tags', 'siteseo') . "', {'cd_tags': '" . $get_tags . "', 'non_interaction': true});";

				$siteseo_google_analytics_config['cd']['cd_tags'] = apply_filters('siteseo_gtag_cd_tags_cf', $siteseo_google_analytics_config['cd']['cd_tags']);

				$siteseo_google_analytics_event['cd_tags'] = apply_filters('siteseo_gtag_cd_tags_ev', $siteseo_google_analytics_event['cd_tags']);
			}
		}

		if (!empty($cdPostTypeOption) && 'none' != $cdPostTypeOption) {
			if (is_single()) {
				$siteseo_google_analytics_config['cd']['cd_cpt'] = "'" . $cdPostTypeOption . "': 'cd_cpt',";

				$siteseo_google_analytics_event['cd_cpt'] = "gtag('event', '" . __('Post types', 'siteseo') . "', {'cd_cpt': '" . get_post_type() . "', 'non_interaction': true});";

				$siteseo_google_analytics_config['cd']['cd_cpt'] = apply_filters('siteseo_gtag_cd_cpt_cf', $siteseo_google_analytics_config['cd']['cd_cpt']);

				$siteseo_google_analytics_event['cd_cpt'] = apply_filters('siteseo_gtag_cd_cpt_ev', $siteseo_google_analytics_event['cd_cpt']);
			}
		}

		if (!empty($cdLoggedInUserOption) && 'none' != $cdLoggedInUserOption) {
			if (wp_get_current_user()->ID) {
				$siteseo_google_analytics_config['cd']['cd_logged_in'] = "'" . $cdLoggedInUserOption . "': 'cd_logged_in',";

				$siteseo_google_analytics_event['cd_logged_in'] = "gtag('event', '" . __('Connected users', 'siteseo') . "', {'cd_logged_in': '" . wp_get_current_user()->ID . "', 'non_interaction': true});";

				$siteseo_google_analytics_config['cd']['cd_logged_in'] = apply_filters('siteseo_gtag_cd_logged_in_cf', $siteseo_google_analytics_config['cd']['cd_logged_in']);

				$siteseo_google_analytics_event['cd_logged_in'] = apply_filters('siteseo_gtag_cd_logged_in_ev', $siteseo_google_analytics_event['cd_logged_in']);
			}
		}

		if ( ! empty($siteseo_google_analytics_config['cd']['cd_logged_in']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_cpt']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_tags']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_categories']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_author']) ||
				( ! empty($siteseo_google_analytics_config['cd']['cd_hook']) && ! empty($siteseo_google_analytics_event['cd_hook']))) {
			$siteseo_google_analytics_config['cd']['cd_end'] = '}, ';
		} else {
			$siteseo_google_analytics_config['cd']['cd_start'] = '';
		}

		//External links
		if (!empty($google_analytics_option->getLinkTrackingEnable())) {
			$siteseo_google_analytics_click_event['link_tracking'] =
"window.addEventListener('load', function () {
var links = document.querySelectorAll('a');
for (let i = 0; i < links.length; i++) {
	links[i].addEventListener('click', function(e) {
		var n = this.href.includes('" . wp_parse_url(get_home_url(), PHP_URL_HOST) . "');
		if (n == false) {
			gtag('event', 'click', {'event_category': 'external links','event_label' : this.href});
		}
	});
	}
});
";
			$siteseo_google_analytics_click_event['link_tracking'] = apply_filters('siteseo_gtag_link_tracking_ev', $siteseo_google_analytics_click_event['link_tracking']);
			$siteseo_google_analytics_html .= $siteseo_google_analytics_click_event['link_tracking'];
		}

		//Downloads tracking
		if (!empty($google_analytics_option->getDownloadTrackingEnable())) {
			$downloadTrackingOption = $google_analytics_option->getDownloadTracking();
			if (!empty($downloadTrackingOption)) {
				$siteseo_google_analytics_click_event['download_tracking'] =
"window.addEventListener('load', function () {
	var donwload_links = document.querySelectorAll('a');
	for (let j = 0; j < donwload_links.length; j++) {
		donwload_links[j].addEventListener('click', function(e) {
			var down = this.href.match(/.*\.(" . $downloadTrackingOption . ")(\?.*)?$/);
			if (down != null) {
				gtag('event', 'click', {'event_category': 'downloads','event_label' : this.href});
			}
		});
		}
	});
";
				$siteseo_google_analytics_click_event['download_tracking'] = apply_filters('siteseo_gtag_download_tracking_ev', $siteseo_google_analytics_click_event['download_tracking']);
				$siteseo_google_analytics_html .= $siteseo_google_analytics_click_event['download_tracking'];
			}
		}

		//Affiliate tracking
		if (!empty($google_analytics_option->getAffiliateTrackingEnable())) {
			$affiliateTrackingOption = $google_analytics_option->getAffiliateTracking();
			if (!empty($affiliateTrackingOption)) {
				$siteseo_google_analytics_click_event['outbound_tracking'] =
"window.addEventListener('load', function () {
	var outbound_links = document.querySelectorAll('a');
	for (let k = 0; k < outbound_links.length; k++) {
		outbound_links[k].addEventListener('click', function(e) {
			var out = this.href.match(/(?:\/" . $affiliateTrackingOption . "\/)/gi);
			if (out != null) {
				gtag('event', 'click', {'event_category': 'outbound/affiliate','event_label' : this.href});
			}
		});
		}
	});";
				$siteseo_google_analytics_click_event['outbound_tracking'] = apply_filters('siteseo_gtag_outbound_tracking_ev', $siteseo_google_analytics_click_event['outbound_tracking']);
				$siteseo_google_analytics_html .= $siteseo_google_analytics_click_event['outbound_tracking'];
			}
		}

		//Phone tracking
		if (!empty($google_analytics_option->getPhoneTracking())) {
			$siteseo_google_analytics_click_event['phone_tracking'] =
"window.addEventListener('load', function () {
	var links = document.querySelectorAll('a');
	for (let i = 0; i < links.length; i++) {
		links[i].addEventListener('click', function(e) {
			var n = this.href.includes('tel:');
			if (n === true) {
				gtag('event', 'click', {'event_category': 'phone','event_label' : this.href.slice(4)});
			}
		});
	}
});";
			$siteseo_google_analytics_click_event['phone_tracking'] = apply_filters('siteseo_gtag_phone_tracking_ev', $siteseo_google_analytics_click_event['phone_tracking']);
			$siteseo_google_analytics_html .= $siteseo_google_analytics_click_event['phone_tracking'];
		}

		// Google Enhanced Ecommerce
		require_once dirname(__FILE__) . '/options-google-ecommerce.php';

		//Anonymize IP
		$ipAnonymize = siteseo_get_service('GoogleAnalyticsOption')->getIpAnonymization();
		if ('1' == $ipAnonymize) {
			$siteseo_google_analytics_config['anonymize_ip'] = "'anonymize_ip': true,";
			$siteseo_google_analytics_config['anonymize_ip'] = apply_filters('siteseo_gtag_anonymize_ip', $siteseo_google_analytics_config['anonymize_ip']);
		}

		// Send data
		$features = '';
		if ( ! empty($siteseo_google_analytics_config['cd']['cd_logged_in']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_cpt']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_tags']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_categories']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_author']) ||
				! empty($siteseo_google_analytics_config['cd']['cd_hook'])) {
			$siteseo_google_analytics_config['cd']['cd_start'] = "'custom_map': {";
		}
		if ( ! empty($siteseo_google_analytics_config)) {
			if ( ! empty($siteseo_google_analytics_config['cd']['cd_start'])) {
				array_unshift($siteseo_google_analytics_config['cd'], $siteseo_google_analytics_config['cd']['cd_start']);
				unset($siteseo_google_analytics_config['cd']['cd_start']);
			}
			$features = ', {';
			foreach ($siteseo_google_analytics_config as $key => $feature) {
				if ('cd' == $key) {
					foreach ($feature as $_key => $cd) {
						$features .= $cd;
					}
				} else {
					$features .= $feature;
				}
			}
			$features .= '}';
		}

		// Measurement ID
		if ('' != $google_analytics_option->getGA4()) {
			$siteseo_gtag_ga4 = "gtag('config', '" . $google_analytics_option->getGA4() . "' " . $features . ');';
			$siteseo_gtag_ga4 = apply_filters('siteseo_gtag_ga4', $siteseo_gtag_ga4);
			$siteseo_google_analytics_html .= $siteseo_gtag_ga4;
			$siteseo_google_analytics_html .= "\n";
		}

		// Ads
		$adsOptions = $google_analytics_option->getAds();
		if (!empty($adsOptions)) {
			$siteseo_gtag_ads = "gtag('config', '" . $adsOptions . "');";
			$siteseo_gtag_ads = apply_filters('siteseo_gtag_ads', $siteseo_gtag_ads);
			$siteseo_google_analytics_html .= $siteseo_gtag_ads;
			$siteseo_google_analytics_html .= "\n";
		}

		$events = '';
		if ( ! empty($siteseo_google_analytics_event)) {
			foreach ($siteseo_google_analytics_event as $event) {
				$siteseo_google_analytics_html .= $event;
				$siteseo_google_analytics_html .= "\n";
			}
		}

		// E-commerce
		if (isset($siteseo_google_analytics_click_event['purchase_tracking'])) {
			$siteseo_google_analytics_html .= $siteseo_google_analytics_click_event['purchase_tracking'];
		}

		$siteseo_google_analytics_html .= '</script>';
		$siteseo_google_analytics_html .= "\n";

		// Optimize
		$optimizeOption = $google_analytics_option->getOptimize();
		if (!empty($optimizeOption)) {
			$siteseo_google_analytics_html .= '<script async src="https://www.googleoptimize.com/optimize.js?id='.$optimizeOption.'"></script>';
			$siteseo_google_analytics_html .= "\n";
		}

		$siteseo_google_analytics_html = apply_filters('siteseo_gtag_html', $siteseo_google_analytics_html);

		if (true == $echo) {
			echo wp_kses($siteseo_google_analytics_html, ['script' => ['async' => true, 'src' => true, 'defer' => true, 'crossorigin' => true, 'type' => true]]);
		} else {
			return $siteseo_google_analytics_html;
		}
	}
}
add_action('siteseo_google_analytics_html', 'siteseo_google_analytics_js', 10, 1);

function siteseo_google_analytics_js_arguments() {
	$echo = true;
	do_action('siteseo_google_analytics_html', $echo);
}

function siteseo_custom_tracking_hook() {
	$data['custom'] = '';
	$data['custom'] = apply_filters('siteseo_custom_tracking', $data['custom']);
	echo wp_kses($data['custom'], ['script' => ['async' => true, 'src' => true, 'defer' => true, 'crossorigin' => true, 'type' => true]]);
}

//Build custom code after body tag opening
function siteseo_google_analytics_body_code($echo) {
	$siteseo_html_body = siteseo_get_service('GoogleAnalyticsOption')->getOtherTrackingBody();
	if (empty($siteseo_html_body) || !$siteseo_html_body) {
		return;
	}

	$siteseo_html_body = apply_filters('siteseo_custom_body_tracking', $siteseo_html_body);
	if (true == $echo) {
		echo "\n" . wp_kses($siteseo_html_body, ['script' => ['async' => true, 'src' => true, 'defer' => true, 'crossorigin' => true, 'type' => true]]);
	} else {
		return "\n" . $siteseo_html_body;
	}
}
add_action('siteseo_custom_body_tracking_html', 'siteseo_google_analytics_body_code', 10, 1);

function siteseo_custom_tracking_body_hook() {
	$echo = true;
	do_action('siteseo_custom_body_tracking_html', $echo);
}

//Build custom code before body tag closing
function siteseo_google_analytics_footer_code($echo) {
	$siteseo_html_footer = siteseo_get_service('GoogleAnalyticsOption')->getOtherTrackingFooter();
	if(empty($siteseo_html_footer) || !$siteseo_html_footer) {
		return;
	}

	$siteseo_html_footer = apply_filters('siteseo_custom_footer_tracking', $siteseo_html_footer);
	if (true == $echo) {
		echo "\n" . wp_kses($siteseo_html_footer, ['script' => ['async' => true, 'src' => true, 'defer' => true, 'crossorigin' => true, 'type' => true]]);
	} else {
		return "\n" . $siteseo_html_footer;
	}
}
add_action('siteseo_custom_footer_tracking_html', 'siteseo_google_analytics_footer_code', 10, 1);

function siteseo_custom_tracking_footer_hook() {
	$echo = true;
	do_action('siteseo_custom_footer_tracking_html', $echo);
}

//Build custom code in head
function siteseo_google_analytics_head_code($echo) {
	$siteseo_html_head = siteseo_get_service('GoogleAnalyticsOption')->getOtherTracking();
	if (empty($siteseo_html_head) || !$siteseo_html_head) {
		return;
	}

	$siteseo_html_head = apply_filters('siteseo_gtag_after_additional_tracking_html', $siteseo_html_head);

	if (true == $echo) {
		echo "\n" . wp_kses($siteseo_html_head, ['script' => ['async' => true, 'src' => true, 'defer' => true, 'crossorigin' => true, 'type' => true]]);
	} else {
		return "\n" . $siteseo_html_head;
	}
}
add_action('siteseo_custom_head_tracking_html', 'siteseo_google_analytics_head_code', 10, 1);

function siteseo_custom_tracking_head_hook() {
	$echo = true;
	do_action('siteseo_custom_head_tracking_html', $echo);
}

$google_analytics_option = siteseo_get_service('GoogleAnalyticsOption');
if ('1' == $google_analytics_option->getHalfDisable() || (((isset($_COOKIE['siteseo-user-consent-accept']) && '1' == $_COOKIE['siteseo-user-consent-accept']) && '1' == $google_analytics_option->getDisable()) || ('1' != $google_analytics_option->getDisable()))) { //User consent cookie OK

	$addToCartOption = $google_analytics_option->getAddToCart();
	$removeFromCartOption = $google_analytics_option->getRemoveFromCart();

	if (is_user_logged_in()) {
		global $wp_roles;

		//Get current user role
		if (isset(wp_get_current_user()->roles[0])) {
			$siteseo_user_role = wp_get_current_user()->roles[0];
			//If current user role matchs values from SiteSEO GA settings then apply
			if ('1' == siteseo_get_toggle_option('google-analytics') && '' != siteseo_get_service('GoogleAnalyticsOption')->getRoles()) {
				if (array_key_exists($siteseo_user_role, siteseo_get_service('GoogleAnalyticsOption')->getRoles())) {
					//do nothing
				} else {
					if ('1' == $google_analytics_option->getEnableOption() && '' != $google_analytics_option->getGA4()) {
						add_action('wp_head', 'siteseo_google_analytics_js_arguments', 929, 1);
						add_action('wp_head', 'siteseo_custom_tracking_hook', 900, 1);
					}
					if ('1' == siteseo_get_service('GoogleAnalyticsOption')->getMatomoEnable() && '' != siteseo_get_service('GoogleAnalyticsOption')->getMatomoId() && '' != siteseo_get_service('GoogleAnalyticsOption')->getMatomoSiteId()) {
						add_action('wp_head', 'siteseo_matomo_js_arguments', 960, 1);
					}
					if ('1' == siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_enable') && '' != siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_project_id')) {
						add_action('wp_head', 'siteseo_clarity_js_arguments', 970, 1);
					}
					add_action('wp_head', 'siteseo_custom_tracking_head_hook', 980, 1);
					add_action('wp_body_open', 'siteseo_custom_tracking_body_hook', 1020, 1);
					add_action('wp_footer', 'siteseo_custom_tracking_footer_hook', 1030, 1);

					//ecommerce
					$purchasesOptions = siteseo_get_service('GoogleAnalyticsOption')->getPurchases();
					if ('1' == $purchasesOptions || '1' == $addToCartOption || '1' == $removeFromCartOption) {
						add_action('wp_enqueue_scripts', 'siteseo_google_analytics_ecommerce_js', 20, 1);
					}
				}
			} else {
				if ('1' == $google_analytics_option->getEnableOption() && '' != $google_analytics_option->getGA4()) {
					add_action('wp_head', 'siteseo_google_analytics_js_arguments', 929, 1);
					add_action('wp_head', 'siteseo_custom_tracking_hook', 900, 1);
				}
				if ('1' == siteseo_get_service('GoogleAnalyticsOption')->getMatomoEnable() && '' != siteseo_get_service('GoogleAnalyticsOption')->getMatomoId() && '' != siteseo_get_service('GoogleAnalyticsOption')->getMatomoSiteId()) {
					add_action('wp_head', 'siteseo_matomo_js_arguments', 960, 1);
				}
				if ('1' == siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_enable') && '' != siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_project_id')) {
					add_action('wp_head', 'siteseo_clarity_js_arguments', 970, 1);
				}
				add_action('wp_head', 'siteseo_custom_tracking_head_hook', 980, 1); //Oxygen: if prioriry >= 990, nothing will be outputed
				add_action('wp_body_open', 'siteseo_custom_tracking_body_hook', 1020, 1);
				add_action('wp_footer', 'siteseo_custom_tracking_footer_hook', 1030, 1);

				//ecommerce
				$purchasesOptions = siteseo_get_service('GoogleAnalyticsOption')->getPurchases();
				if ('1' == $purchasesOptions || '1' == $addToCartOption || '1' == $removeFromCartOption) {
					add_action('wp_enqueue_scripts', 'siteseo_google_analytics_ecommerce_js', 20, 1);
				}
			}
		}
	}else{
		if ('1' == $google_analytics_option->getEnableOption() && '' != $google_analytics_option->getGA4()) {
			add_action('wp_head', 'siteseo_google_analytics_js_arguments', 929, 1);
			add_action('wp_head', 'siteseo_custom_tracking_hook', 900, 1);
		}
		if ('1' == siteseo_get_service('GoogleAnalyticsOption')->getMatomoEnable() && '' != siteseo_get_service('GoogleAnalyticsOption')->getMatomoId() && '' != siteseo_get_service('GoogleAnalyticsOption')->getMatomoSiteId()) {
			add_action('wp_head', 'siteseo_matomo_js_arguments', 960, 1);
		}
		if ('1' == siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_enable') && '' != siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_project_id')) {
			add_action('wp_head', 'siteseo_clarity_js_arguments', 970, 1);
		}
		add_action('wp_head', 'siteseo_custom_tracking_head_hook', 980, 1);
		add_action('wp_body_open', 'siteseo_custom_tracking_body_hook', 1020, 1);
		add_action('wp_footer', 'siteseo_custom_tracking_footer_hook', 1030, 1);

		//ecommerce
		$purchasesOptions = siteseo_get_service('GoogleAnalyticsOption')->getPurchases();
		if ('1' == $purchasesOptions || '1' == $addToCartOption || '1' == $removeFromCartOption) {
			add_action('wp_enqueue_scripts', 'siteseo_google_analytics_ecommerce_js', 20, 1);
		}
	}
}
