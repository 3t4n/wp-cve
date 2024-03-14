<?php
/*
Plugin Name: Smartslider
Plugin URI: http://www.horttcore.de/wordpress/smartslider
Description: Slide your HTML Elements
Version: 1.0.1
Author: Ralf Hortt
Author URI: http://www.horttcore.de/
*/

//======================================
// Description: Beschreibung
// Require: 
// Param: 
Function ss_head(){?>
	<script type="text/javascript" src="<?php echo get_option('siteurl').'/'.PLUGINDIR ?>/smartslider/mootools.js"></script>
	<script type="text/javascript" src="<?php echo get_option('siteurl').'/'.PLUGINDIR ?>/smartslider/smartslider.js"></script><?php
}

add_action('wp_head','ss_head');
?>