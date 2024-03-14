<?php
if( !defined('ABSPATH') ) exit;
    
    // is active plugins check
    function skb_cife_is_plugins_active( $pl_file_path = NULL ){
        $installed_plugins_list = get_plugins();
        return isset( $installed_plugins_list[$pl_file_path] );
    }

    /**
     * Get the value of a settings field
     *
     * @param string $option settings field name
     * @param string $section the section name this field belongs to
     * @param string $default default text if it's not found
     *
     * @return mixed
     */
    function skb_cife_get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }    



