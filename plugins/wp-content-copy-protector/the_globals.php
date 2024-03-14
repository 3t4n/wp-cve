<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//Get The Plugins URL as http://www.yrsite.com/dir/subdir
	$pluginsurl = plugins_url( '', __FILE__ );
	if(is_ssl())
	{
		$pluginsurl = str_replace("https:", "http:", $pluginsurl);// just to make sure that there is no https there
		$pluginsurl = str_replace("http:", "https:", $pluginsurl);
	}
?>