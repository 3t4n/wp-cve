<?php
/*
Plugin Name: Counter-Hits
Plugin URI: wpgear.xyz/counter-hits/
Description: A simple, easy, fast, adaptive, local, objective counter to visit your site.
Version: 1.8
Author: WPGear
License: GPLv2
*/

	$Counter_Hits = do_Counter_Hits (0);
	
	function do_Counter_Hits ($Counter_Base = 0) {
		// Считаем, даже если неотображаем на фронтенде.
		if (is_admin()) {
			// В Админке не считаем.
			return;
		}
		
		$AntiFlood_RU = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field($_SERVER['REQUEST_URI']) : 'AntiFlood_RU';
		$AntiFlood_RA = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : 'AntiFlood_RA';
		$AntiFlood_RT = isset($_SERVER['REQUEST_TIME_FLOAT']) ? sanitize_text_field($_SERVER['REQUEST_TIME_FLOAT']) : 'AntiFlood_RT';		
					
		$Counter_Hits 	= get_option('wpgear_counter_hits', 1);
		$AntiFlood 		= get_option('wpgear_counter_hits_sign', 'nothing');		
		
		if ($AntiFlood == $AntiFlood_RU .$AntiFlood_RA .$AntiFlood_RT) {
			return $Counter_Hits + $Counter_Base;
		} else {
			$AntiFlood = $AntiFlood_RU .$AntiFlood_RA .$AntiFlood_RT;			
		}

		$Counter_Hits = $Counter_Hits + 1;
		
		update_option('wpgear_counter_hits', $Counter_Hits);
		update_option('wpgear_counter_hits_sign', $AntiFlood);

		return $Counter_Hits;
	}
	
	function get_Counter_Hits ($Counter_Base = 0) {
		global $Counter_Hits;
		
		return $Counter_Hits + $Counter_Base;
	}

	// ShortCod [Get_Counter_Hits]
	add_shortcode('Get_Counter_Hits', 'wpgear_get_Counter_Hits');
	function wpgear_get_Counter_Hits($atts, $shortcode_content = null) {
		$Counter_Base = isset($atts['base']) ? intval($atts['base']) : 0;
		$Counter_Hits = get_Counter_Hits ($Counter_Base);		
		
		ob_start();
		?>	
		<span class='wpgear_counter_hits'><?php echo $Counter_Hits;?></span>
		<?php		
		$content = ob_get_clean();
						
		return $content;
	}