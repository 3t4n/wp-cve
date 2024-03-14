<?php

/**
 *
 * Class CLP Dynamic JS
 *
 * Extending A5 Dynamic Files
 *
 * Prints the Javascript of the A5 Custom Login Page to the options page
 *
 */

class CLP_DynamicJS extends A5_DynamicFiles {
	
	function __construct() {
		
		$eol = "\n";
		
		$link = wp_login_url().'?TB_iframe=true&width=800&height=600&sandbox=""';
		
		$this->a5_scripts('admin', array('toplevel_page_clp-settings'), true);
		
		parent::$admin_scripts .= $eol.'jQuery(document).ready(function(){jQuery(\'#prev\').attr(\'href\', \''.$link.'\');});'.$eol;
		
	}
	
} // CLP_Dynamic CSS

?>