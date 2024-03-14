<?php


//Cache actions

add_action( 'wp_ajax_aal_cache_set', 'aal_cache_set_func' );
add_action( 'wp_ajax_nopriv_aal_cache_set', 'aal_cache_set_func' );


function aal_cache_set_func() {
	
	check_ajax_referer( 'aalcachesetnonce', 'cachesetnonce' ); 
	
	
	if(isset($_POST['aalpostid']) && $_POST['aalpostid'] && is_numeric(substr($_POST['aalpostid'], 5)) ) {
		
		$postidnr = substr(sanitize_text_field($_POST['aalpostid']), 5);
		$links = array();
		//$links = array_map( 'sanitize_text_field', $_POST['aalcachelinks'] );
		//print_r($_POST['aalcachelinks']);
		foreach($_POST['aalcachelinks'] as $l) {
			$lob = new stdClass();
			$lob->url = sanitize_text_field($l['url']);
			$lob->key = sanitize_text_field($l['key']);
			$links[] = $lob;
		
		}
		//$links = $_POST['aalcachelinks'];
		
		$awidgets = '';
		if(isset($_POST['aalcacheawidget']) && is_array($_POST['aalcacheawidget'])) {
			$awidgets = array();
			foreach($_POST['aalcacheawidget'] as $w) {
				
				$wob = new stdClass();
				$wob->price = sanitize_text_field($w['price']);
				$wob->url = sanitize_text_field($w['url']);	
				$wob->image = sanitize_text_field($w['image']);	
				$wob->title = sanitize_text_field($w['title']);				
				$awidgets[] = $wob;
			}		
		
		
		}
		
		
		$response = new stdClass();
		$response->links = $links;
		if(is_array($awidgets)) $response->amazonwidget = $awidgets;
		$response->updated = time();
		$jsonlinks = json_encode($response);
		
		if(get_post_status($postidnr)) { 
		
			
		
		
			$return = update_post_meta($postidnr, 'aal_cache_links', $jsonlinks);
		
		
			//echo $return;
		}
		else {
			echo 'nopost';		
		}
	}
	
	else {
		echo 'failed';
	}
	
	exit();
	die();
}


add_action( 'wp_ajax_aal_cache_get', 'aal_cache_get_func' );
add_action( 'wp_ajax_nopriv_aal_cache_get', 'aal_cache_get_func' );


function aal_cache_get_func() {
	
	check_ajax_referer( 'aalcachegetnonce', 'cachegetnonce' ); 
	
	
	if(isset($_POST['aalpostid']) && $_POST['aalpostid'] && is_numeric(substr($_POST['aalpostid'], 5)) ) {
		
		$postidnr = substr(sanitize_text_field($_POST['aalpostid']), 5);
		$now = time();
		
		if(get_post_status($postidnr)) { 
		
		
		if(get_post_meta($postidnr,'aal_cache_links', true)) {
		
				$pmt = json_decode(get_post_meta($postidnr,'aal_cache_links', true));		
				
				if(is_array($pmt) || is_object($pmt)) {
				
					$links = array();
					$links = $pmt->links;
					$updated = $pmt->updated;
					$now = time();
					
					//echo $updated;
					//echo get_post_modified_time('U',false,$postidnr);
			
					if(($now - $updated) < (6*60*60)) {
						
						if($updated > get_option('aal_settings_updated')) {
						
							if($updated > get_post_modified_time('U',false,$postidnr)) {
							
								//print_r($links);
								
								echo get_post_meta($postidnr,'aal_cache_links', true);			
								
								//echo $return;
							
							}
							else {
								echo 'post updated';			
							}	
							
						}
						else {
							echo 'settings updated';		
						}			 
							
					}
					else {
						echo 'expired';			
					}
				}
				else {
					echo 'nometa';				
				}
			}
			else {
				echo 'nometa';			
			}

		}
		else {
			echo 'nopost';		
		}
	}
	
	else {
		echo 'failed';
	}
	
	exit();
	die();
}