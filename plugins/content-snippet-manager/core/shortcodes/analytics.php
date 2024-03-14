<?php

if (!function_exists('csm_google_analytics_function')) {

	function csm_google_analytics_function($atts,  $content = null) {
		
		extract(shortcode_atts(array(
			'ua' => 'UA-XXXXX-Y',
			'anonymizeIP' => 'true',
		), $atts));
		
		$html = "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', '" . esc_attr($ua) . "', 'auto');";

		if ( $anonymizeIP == 'true' ) :
		
			$html .= "ga('set','anonymizeIP',true);";
		
		endif;
		
		$html .= "ga('send', 'pageview');</script>";
		
		return $html;
		
	}
	
	add_shortcode('csm_google_analytics','csm_google_analytics_function');

}

?>
