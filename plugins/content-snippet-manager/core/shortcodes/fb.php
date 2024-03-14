<?php

if (!function_exists('csm_fb_pixel_function')) {

	function csm_fb_pixel_function($atts,  $content = null) {
		
		extract(shortcode_atts(array(
			'pixelid' => '123456789',
			'value' => '1',
			'currency' => 'USD',
		), $atts));
		
		$html = "<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; n.queue=[];t=b.createElement(e);t.async=!0; t.src=v;s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window, document,'script', 'https://connect.facebook.net/en_US/fbevents.js'); fbq('init', '" . esc_attr($pixelid) . "'); fbq('track', 'PageView');";

		if ( 
			isset($value) && 
			isset($currency)
		) :
		
			$html .= "fbq('track', 'Purchase', {value: '" . esc_attr($value) . "', currency: '" . esc_attr($currency) . "'});";
		
		endif;
		
		$html .= "</script><noscript> <img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=" . esc_attr($pixelid) . "&ev=PageView&noscript=1'/></noscript>";
		
		return $html;
		
	}
	
	add_shortcode('csm_fb_pixel','csm_fb_pixel_function');

}

?>
