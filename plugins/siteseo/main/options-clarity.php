<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

// Clarity Project ID
function siteseo_google_analytics_clarity_project_id_option() {
	$options = get_option("siteseo_google_analytics_option_name");
	
	if( ! empty ( $options ) && isset($options['google_analytics_clarity_project_id'])) {
		return $options['google_analytics_clarity_project_id'];
	}
}

add_action('siteseo_clarity_html', 'siteseo_clarity_js', 10, 1);
// Build Clarity Tracking Code
function siteseo_clarity_js($echo){
	if (siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_project_id') != '' && siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_enable') === '1') {
		//Init
		$js = "\n<script>";
		$js .= '(function(c,l,a,r,i,t,y){
			c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
			t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i+"?ref=siteseo";
			y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		})(window, document, "clarity", "script", "'.siteseo_get_service('GoogleAnalyticsOption')->searchOptionByKey('google_analytics_clarity_project_id').'");';
		$js .= "</script>\n";

		$js = apply_filters('siteseo_clarity_tracking_js', $js);

		if ($echo == true) {
			echo wp_kses($js, ['script' => ['type' => true, 'async' => true, 'defer' => true]]);
		} else {
			return $js;
		}
	}
}

function siteseo_clarity_js_arguments(){
	do_action('siteseo_clarity_html', true);
}
