<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Base;

require_once \dirname(__FILE__) . '/class-basecontroller.php';

class SettingsLink extends BaseController
{
    public function ede_register()
    {
        add_filter( 'plugin_action_links_'.$this->plugin_name, array($this,'settingsLinkUrl'), 10, 2 );
    }

    public function settingsLinkUrl( $links )
    {
        $url = esc_url(add_query_arg( array(
                    'post_type' => 'ede_embedder',
                    'page' => 'easy_embedder_about',
                ),get_admin_url() . 'edit.php' ));
        
                $settings_link = '<a href="'.esc_attr( $url ).'">'.esc_html__( 'About', 'easy-document-embedder' ).'</>';
                array_unshift($links,$settings_link);
        return $links;
    }
}