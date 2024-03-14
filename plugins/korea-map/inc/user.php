<?php
	add_action('init', 'kims_init');
	add_shortcode( 'korea_map', 'kims_shortcode' );
	
	function kims_init(){
		global $g_arKimsOptions;
		
		wp_enqueue_style( 'kimsmap_css', plugins_url('../css/style.css', __FILE__), null, '0.0.3' );
		wp_enqueue_script('jquery');
		
		if( isset($g_arKimsOptions['api_key']) ){
			if( strlen($g_arKimsOptions['api_key']) == 32 ){
				wp_enqueue_script('kakomapssdk', '//dapi.kakao.com/v2/maps/sdk.js?appkey='.$g_arKimsOptions['api_key'].'&libraries=services', null, '1.0', true);
			}
		}
		wp_enqueue_script('kimsmap_js', plugins_url('../js/kimsmap.js', __FILE__), null, '0.0.7', true);
	}
	
	function kims_shortcode( $arParam ){
		global $g_arKimsOptions;
		
		if( !isset($g_arKimsOptions['api_key']) ) return __('Error').__(' : ').__('App Key is empty.');
		if( !isset($arParam['title']) ) $arParam['title'] = '';
		if( !isset($arParam['address']) ) return __('Error').__(' : ').__('Address is empty.');
		if( !isset($arParam['marker']) ) $arParam['marker'] = 1;
		if( !isset($arParam['width']) ) $arParam['width'] = '100%';
		if( !isset($arParam['height']) ) $arParam['height'] = '640px';
		if( strlen($g_arKimsOptions['api_key']) != 32 ) return __('Error').__(' : ').__('App Key is 32 character.', KIMS_TEXT_DOMAIN);;
		if( $arParam['address'] == '' ) return __('Error : Address is empty.');
		if( $arParam['marker'] < 1 || $arParam['marker'] > 6 ) $arParam['marker'] = 1;
		if( $arParam['width'] == '' ) $arParam['width'] = '100%';
		if( $arParam['height'] == '' ) $arParam['height'] = '640px';
		
		$strImageUrl = sprintf("../images/m%02d.png", $arParam['marker']);
		$arParam['marker_url'] = plugins_url($strImageUrl, __FILE__);
		$content = '<div id="korea-map" style="width:'.$arParam['width'].';height:'.$arParam['height'].'"></div>';
		$content .= '<script> if (typeof g_arKimsParams == "undefined") var g_arKimsParams = '.json_encode($arParam).'; </script>';
		
		return $content;
	}
