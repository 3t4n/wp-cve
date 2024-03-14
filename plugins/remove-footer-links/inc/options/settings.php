<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
namespace Remove_Footer_Links\Inc\Options;
use Remove_Footer_Links\Inc\Classes\Setting;
class Settings extends Setting{

    public function fields(){

        $default = remove_footer_links_default();
        $fields = array(
            'heading' => esc_html__( 'Remove Footer Links Settings', 'remove-footer-links' ),
            'title' => esc_html__( 'Remove Footer Links Options', 'remove-footer-links' ),
            'menu' => esc_html__( 'Footer Links', 'remove-footer-links' ),
            'slug' => 'remove-footer-links',
            'settings' => array(
                array(
                    'name' => 'remove_footer_links',
                    'option' => 'remove_footer_links',
                    'title' => esc_html__( 'General Settings', 'remove-footer-links' ),
                    'description' => esc_html__( 'Hello Description', 'remove-footer-links' ),
                    'fields' => array(
                        array(
                            'name' => 'auto_remove_links',
                            'default' => $default['auto_remove_links'],
                            'title' => esc_html__( 'Auto Remove Credit Links', 'remove-footer-links' ),
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'remove_data_uninstall',
                            'default' => $default['remove_data_uninstall'],
                            'title' => esc_html__( 'Remove data on uninstall plugin', 'remove-footer-links' ),
                            'type' => 'toggle',
                        ),
                    )
                )
            )
        );

        $this->fields = $fields;

    }
}