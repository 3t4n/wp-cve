<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

//Build Custom Matomo
function siteseo_matomo_js($echo){
	if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoId() !='' && siteseo_get_service('GoogleAnalyticsOption')->getMatomoSiteId() !='') {
		//Init
		$siteseo_matomo_config = [];
		$siteseo_matomo_event = [];

		$siteseo_matomo_html = "\n";
		$siteseo_matomo_html .="<script async>
var _paq = window._paq || [];\n";

		//subdomains
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoSubdomains() =='1') {
			$parse_url = wp_parse_url(get_home_url());
			if ( ! empty($parse_url['host'])) {
				$siteseo_matomo_config['subdomains'] = "_paq.push(['setCookieDomain', '*.".$parse_url['host']."']);\n";
				$siteseo_matomo_config['subdomains'] = apply_filters('siteseo_matomo_cookie_domain', $siteseo_matomo_config['subdomains']);
			}
		}

		//site domain
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoSiteDomain() =='1') {
			$siteseo_matomo_config['site_domain'] = "_paq.push(['setDocumentTitle', document.domain + '/' + document.title]);\n";
			$siteseo_matomo_config['site_domain'] = apply_filters('siteseo_matomo_site_domain', $siteseo_matomo_config['site_domain']);
		}

		//DNT
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoDnt() =='1') {
			$siteseo_matomo_config['dnt'] = "_paq.push(['setDoNotTrack', true]);\n";
			$siteseo_matomo_config['dnt'] = apply_filters('siteseo_matomo_dnt', $siteseo_matomo_config['dnt']);
		}

		//disable cookies
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoNoCookies() =='1') {
			$siteseo_matomo_config['no_cookies'] = "_paq.push(['disableCookies']);\n";
			$siteseo_matomo_config['no_cookies'] = apply_filters('siteseo_matomo_disable_cookies', $siteseo_matomo_config['no_cookies']);
		}

		//cross domains
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoCrossDomain() =='1' && siteseo_get_service('GoogleAnalyticsOption')->getMatomoCrossDomainSites()) {

			$domains = array_map('trim',array_filter(explode(',',siteseo_get_service('GoogleAnalyticsOption')->getMatomoCrossDomainSites())));

			if (!empty($domains)) {
				$domains_count = count($domains);

				$link_domains = '';

				foreach ($domains as $key => $domain) {
					$link_domains .= "'".$domain."'";
					if ( $key < $domains_count -1){
						$link_domains .= ',';
					}
				}
				$siteseo_matomo_config['set_domains'] = "_paq.push(['setDomains', [".$link_domains."]]);\n_paq.push(['enableCrossDomainLinking']);\n";
				$siteseo_matomo_config['set_domains'] = apply_filters('siteseo_matomo_linker', $siteseo_matomo_config['set_domains']);
			}
		}

		//link tracking
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoLinkTracking() =='1') {
			$siteseo_matomo_config['link_tracking'] = "_paq.push(['enableLinkTracking']);\n";
			$siteseo_matomo_config['link_tracking'] = apply_filters('siteseo_matomo_link_tracking', $siteseo_matomo_config['link_tracking']);
		}

		//no heatmaps
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoNoHeatmaps() =='1') {
			$siteseo_matomo_config['no_heatmaps'] = "_paq.push(['HeatmapSessionRecording::disable']);\n";
			$siteseo_matomo_config['no_heatmaps'] = apply_filters('siteseo_matomo_no_heatmaps', $siteseo_matomo_config['no_heatmaps']);
		}

		//dimensions
		$cdAuthorOption = siteseo_get_service('GoogleAnalyticsOption')->getCdAuthor();
		if (!empty($cdAuthorOption) && $cdAuthorOption != 'none') {
			if (is_singular()) {
				$siteseo_matomo_event['cd_author'] = "_paq.push(['setCustomVariable', '".substr($cdAuthorOption,-1)."', '".__('Authors','siteseo')."', '".get_the_author()."', 'visit']);\n";
				$siteseo_matomo_event['cd_author'] = apply_filters('siteseo_matomo_cd_author_ev', $siteseo_matomo_event['cd_author']);
			}
		}

		$cdCategoryOption = siteseo_get_service('GoogleAnalyticsOption')->getCdCategory();
		if (!empty($cdCategoryOption) && $cdAuthorOption != 'none') {
			if (is_single() && has_category()) {
				$categories = get_the_category();

				if ( ! empty( $categories ) ) {
					$get_first_category = esc_html( $categories[0]->name );
				}
				$siteseo_matomo_event['cd_categories'] = "_paq.push(['setCustomVariable', '".substr($cdCategoryOption,-1)."', '".__('Categories','siteseo')."', '".$get_first_category."', 'visit']);\n";
				$siteseo_matomo_event['cd_categories'] = apply_filters('siteseo_matomo_cd_categories_ev', $siteseo_matomo_event['cd_categories']);
			}
		}

		$cdTagOption = siteseo_get_service('GoogleAnalyticsOption')->getCdTag();
		if (!empty($cdTagOption) && $cdTagOption !='none') {
			if (is_single() && has_tag()) {
				$tags = get_the_tags();
				if ( ! empty( $tags ) ) {
					$siteseo_comma_count = count($tags);
					$get_tags = '';
					foreach ($tags as $key => $value) {
						$get_tags .= esc_html( $value->name );
						if ( $key < $siteseo_comma_count -1){
							$get_tags .= ', ';
						}
					}
				}
				$siteseo_matomo_event['cd_tags'] = "_paq.push(['setCustomVariable', '".substr($cdTagOption,-1)."', '".__('Tags','siteseo')."', '".$get_tags."', 'visit']);\n";
				$siteseo_matomo_event['cd_tags'] = apply_filters('siteseo_matomo_cd_tags_ev', $siteseo_matomo_event['cd_tags']);
			}
		}

		$cdPostTypeOption = siteseo_get_service('GoogleAnalyticsOption')->getCdPostType();
		if (!empty($cdPostTypeOption) && $cdPostTypeOption !='none') {
			if (is_single()) {
				$siteseo_matomo_event['cd_cpt'] = "_paq.push(['setCustomVariable', '".substr($cdPostTypeOption,-1)."', '".__('Post types','siteseo')."', '".get_post_type()."', 'visit']);\n";
				$siteseo_matomo_event['cd_cpt'] = apply_filters('siteseo_matomo_cd_cpt_ev', $siteseo_matomo_event['cd_cpt']);
			}
		}

		$cdLoggedInUserOption = siteseo_get_service('GoogleAnalyticsOption')->getCdLoggedInUser();
		if (!empty($cdLoggedInUserOption) && $cdLoggedInUserOption !='none') {
			if (wp_get_current_user()->ID) {
				$siteseo_matomo_event['cd_logged_in'] = "_paq.push(['setCustomVariable', '".substr($cdLoggedInUserOption,-1)."', '".__('Connected users','siteseo')."', '".wp_get_current_user()->ID."', 'visit']);\n";
				$siteseo_matomo_event['cd_logged_in'] = apply_filters('siteseo_matomo_cd_logged_in_ev', $siteseo_matomo_event['cd_logged_in']);
			}
		}

		//send data config
		if (!empty($siteseo_matomo_config)) {
			foreach($siteseo_matomo_config as $key => $value) {
				$siteseo_matomo_html .= $value;
			}
		}

		//send data dimensions
		if (!empty($siteseo_matomo_event)) {
			foreach($siteseo_matomo_event as $key => $value) {
				$siteseo_matomo_html .= $value;
			}
		}

		$siteseo_matomo_src = "cdn.matomo.cloud/".siteseo_get_service('GoogleAnalyticsOption')->getMatomoId();

		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoSelfHosted() === '1') {
			$siteseo_matomo_src = siteseo_get_service('GoogleAnalyticsOption')->getMatomoId();
		}

		$siteseo_matomo_html .= "_paq.push(['trackPageView']);
(function() {
	var u='https://".siteseo_get_service('GoogleAnalyticsOption')->getMatomoId()."/';
	_paq.push(['setTrackerUrl', u+'matomo.php']);
	_paq.push(['setSiteId', '".siteseo_get_service('GoogleAnalyticsOption')->getMatomoSiteId()."']);
	var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	g.type='text/javascript'; g.async=true; g.defer=true; g.src='https://".untrailingslashit($siteseo_matomo_src)."/matomo.js'; s.parentNode.insertBefore(g,s);
	})();\n";

		$siteseo_matomo_html .= "</script>\n";

		//no JS
		$no_js = NULL;
		if (siteseo_get_service('GoogleAnalyticsOption')->getMatomoNoJS() =='1') {
			$no_js = '<noscript><p><img src="https://'.siteseo_get_service('GoogleAnalyticsOption')->getMatomoId().'/matomo.php?idsite='.siteseo_get_service('GoogleAnalyticsOption')->getMatomoSiteId().'&amp;rec=1" style="border:0;" alt="" /></p></noscript>';
			$no_js = apply_filters('siteseo_matomo_no_js', $no_js);
		}

		if ($no_js) {
			$siteseo_matomo_html .= $no_js;
		}

		$siteseo_matomo_html = apply_filters('siteseo_matomo_tracking_html', $siteseo_matomo_html);

		if ($echo == true) {
			echo wp_kses($siteseo_matomo_html, ['script' => ['async' => true, 'src' => true, 'defer' => true, 'crossorigin' => true, 'type' => true]]);
		} else {
			return $siteseo_matomo_html;
		}
	}
}
add_action('siteseo_matomo_html', 'siteseo_matomo_js', 10, 1);

function siteseo_matomo_js_arguments() {
	$echo = true;
	do_action('siteseo_matomo_html', $echo);
}
