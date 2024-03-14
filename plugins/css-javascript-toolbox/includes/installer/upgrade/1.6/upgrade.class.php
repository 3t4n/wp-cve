<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTV16Upgrade {
	
	/**
	* put your comment there...
	* 
	*/
	public function database() {
        
        global $wpdb;
        
        $query = "  ALTER TABLE {$wpdb->prefix}cjtoolbox_blocks
                    CHANGE COLUMN `location` `location` VARCHAR(70) NOT NULL DEFAULT 'header';";
        
        $wpdb->query($query);
        
		// Chaining.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function finalize() {
        
		// Upgrade database internal version number using
		// installer class.
		cssJSToolbox::import('includes:installer:installer:installer.class.php');
		CJTInstaller::getInstance()->finalize();
        
		// Chaining.
		return $this;
	}

} // End class.