<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
namespace Remove_Footer_Links\Inc\Options;
class Initialize{

    public function __construct(){
    	$this->init();
    }

    public function init(){
        $this->loader();
    }

    public function loader(){
        if( is_admin() )
            new Settings();
        }

}
