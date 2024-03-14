<?php

	function fd_shift_calendar_shortcode($atts){
		
		extract(shortcode_atts(array(
		  'type' => 'yearly'
		), $atts));
		
		return fd_shift_calendar_generate($type);
		
	}
	add_shortcode('fd_shift_calendar', 'fd_shift_calendar_shortcode');
	
?>