<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	
	directorypress_display_template('partials/terms/taxonomy/parts/terms.php', array('instance' => $instance, 'terms' => $terms));
	
	