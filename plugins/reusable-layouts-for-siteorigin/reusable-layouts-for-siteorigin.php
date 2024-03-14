<?php
/*
Plugin Name:	Reusable Layouts for SiteOrigin
Description: 	SiteOrigin layouts your can build once and use anywhere.
Version: 		1.0.4
Author: 		Echelon
License: 		GPL3
License URI: 	https://www.gnu.org/licenses/gpl-3.0.txt
*/

if (!class_exists('ReusableLayoutsForSiteOrigin')) {
    
    class ReusableLayoutsForSiteOrigin {
        
        public function __construct() {
            add_action( 'init', array($this, 'cpt_tax'));
            add_filter( 'plugins_loaded', array($this, 'plugins_loaded') );
        }
        
        /*
        *
        *	Plugin Text Domain
        *
        */
        
        public function plugin_text_domain() {
            return  'reusable-layouts-for-siteorigin';
        }
        
        /*
        *
        *	Register CPT and Taxes
        *
        */
        
        public function cpt_tax() {
            
            register_taxonomy(
                'echelonso_layout_cat',
                'echelonso_layout',
                array(
                    'hierarchical' => true,
                    'label' => __('Categories', $this->plugin_text_domain()),
                    'query_var' => true,
                    'has_archive' => false,
                    'show_in_nav_menus' => false,
                    'show_admin_column' => true
                )
            );
            
            register_taxonomy(
                'echelonso_layout_tag',
                'echelonso_layout',
                array(
                    'hierarchical' => false,
                    'label' => __('Tags', $this->plugin_text_domain()),
                    'query_var' => true,
                    'has_archive' => false,
                    'show_in_nav_menus' => false,
                    'show_admin_column' => true
                )
            );
            
            register_post_type( 'echelonso_layout', array(
                'label'  => __('Reusable Layouts', $this->plugin_text_domain()),
                'public' => true,
                'has_archive' => false,
                'show_in_nav_menus' => false,
                'show_in_menu' => 'themes.php',
                'exclude_from_search' => true
            ));
        }
        
        /*
        *
        *	Plugins Loaded
        *
        */
        
        public function plugins_loaded() {
            
            add_filter( 'siteorigin_widgets_widget_folders', array($this, 'widget_folders') );
            
        }
        
        /*
        *
        *	Widget folders
        *
        */
        
        public function widget_folders($folders) {
            $folders['echelonso_reusable_layouts_widgets'] = plugin_dir_path(__FILE__) . 'widgets/';
            return $folders;
        }
        
        /*
        *
        *	Get echelonso_layout names
        *
        */
        
        function get_layout_select_options() {
            
            $args = array(
                'post_type'=> 'echelonso_layout',
                'posts_per_page' => -1,
                'orderby' => 'post_title',
                'order' => 'ASC'
            );
            
            $the_query = new WP_Query( $args );
            
            $options = array();
            
            $options[0] = __('None', $this->plugin_text_domain());
            
            if ( $the_query->have_posts() ) {
                foreach ($the_query->posts as $k => $v) {
                    $options[$v->ID] = $v->post_title;
                }
            }
            
            return $options;
        }
        
    }
    global $esorl;
    $esorl = new ReusableLayoutsForSiteOrigin();
}
