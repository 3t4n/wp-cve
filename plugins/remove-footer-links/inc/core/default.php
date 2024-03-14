<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */

 if(!function_exists('remove_footer_links_default')):
    
    function remove_footer_links_default(){

        $default = array(
            
            'auto_remove_links'       => 1,
            'remove_data_uninstall'     => 0,
            
        );

        return $default;

    }

endif;