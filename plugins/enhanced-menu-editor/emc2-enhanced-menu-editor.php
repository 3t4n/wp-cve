<?php

/*
Plugin Name: Enhanced Menu Editor
Plugin URI: http://www.marcuspope.com/wordpress/
Description: Enable enhanced menu editing options to the built-in WordPress Menus page like copying entire menus, and synchronizing menus with pages 
Author: Marcus E. Pope, marcuspope
Author URI: http://www.marcuspope.com
Version: 1.1

Copyright 2011 Marcus E. Pope (email : me@marcuspope.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

add_action('plugins_loaded', array('emc2_enhanced_menu_editor', 'init'), 1);

add_action('admin_print_scripts-nav-menus.php', array(
    'emc2_enhanced_menu_editor',
    'admin_init'
));

if (is_admin()) {
    add_action('wp_ajax_emc2eme_copy_menu', array('emc2_enhanced_menu_editor', 'copy_new_menu'));
    add_action('wp_ajax_emc2eme_sync_pages', array('emc2_enhanced_menu_editor', 'sync_page_hierarchy'));
}

class emc2_enhanced_menu_editor {

    public static function admin_init() {
        //Add dom reference for associated javascript module
        wp_enqueue_script('emc2-enhanced-edit-admin-js', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__))."admin.js");
        
        //provide nonces for ajax calls
        wp_localize_script('emc2-enhanced-edit-admin-js', 'emc2eme', array(
            'new_menu_nonce' => wp_create_nonce('emc2eme_copy_menu'),
            'sync_menu_nonce' => wp_create_nonce('emc2eme_sync_menu')
        ));
    }

    public static function init() {
        global $pagenow;
 
        if ($pagenow == "nav-menus.php") {
            //Alter pages query for menu editor
            add_action('pre_get_posts', array('emc2_enhanced_menu_editor', 'show_all_pages'), 10);
        } 
    }
    
    public static function copy_new_menu() {
        //verify nonce - is this really effective considering it's ajax?
        if (wp_verify_nonce($_GET['nonce'], "emc2eme_copy_menu")) {
            //create the new menu
            $newmenu = wp_create_nav_menu($_GET['menu_name']);
            
            if (is_wp_error($newmenu)) {
                //typically a duplicate menu name
                if (isset($newmenu->errors['menu_exists'][0])) {
                    die(strip_tags($newmenu->errors['menu_exists'][0]));
                }
                else {
                    die("Unknown Error. Could not create new menu '" . $_GET['menu_name'] . "'");
                }
            }
            else {
                //Now copy all of the menu over to the new menu id $newmenu
                self::copy_menu($_GET['menu'], $newmenu);
                
                //report back numeric menu id to confirm success
                die($newmenu);
            }
        }
        else {
            die('Access Denied');
        }
    }
    
    public static function show_all_pages(&$args) {
        //Disable pagination on "View All" pages section for the menu-editor
        //Why the fsk there's pagination on the "View **ALL**" pages tag is beyond me!!
        if ($args->query['post_type'] == 'page' &&
            $args->query['posts_per_page'] == 50) { //only modify the View All pages query, there is also a View Recent that queries for 15 pages
            $args->query['posts_per_page'] = -1; //limitless, query performance might be an issue, but still a one time cost
            $args->query_vars['posts_per_page'] = -1; //duplicate variable in object structure
            return;
        }
    }
    
    static function get_nav_menu_struct($id) {
        global $wpdb;
    
        $dat = array();
    
        //Get all object id's associated with this nav menu $id
        $nav_ids = $wpdb->get_col($wpdb->prepare("SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $id ));
    
        //Get all nav_menu_item objects for the above nav_ids
        $mitems = get_posts(array(
            'post_type' => 'nav_menu_item',
            'include' => $nav_ids,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ));
    
        //Restructure by item ID and only keep the important keys
        foreach ($mitems as $itm) {
            $dat[$itm->ID] = array(
                'menu_order' => $itm->menu_order,
                'post_parent' => $itm->post_parent,
                'description' => trim($itm->post_content)
            );
        }
    
        //Query for extended nav item meta info which contains the target page ID and the parent menu item
        $meta_info = $wpdb->get_results(
            "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta
             WHERE  (meta_key = '_menu_item_object_id' OR meta_key = '_menu_item_menu_item_parent' OR meta_key = '_menu_item_classes') AND
                    post_id in (" . join(",", $nav_ids) . ")");
    
        $post_ids = array();
    
        //merge the resulting meta info into the original dat array
        foreach ($meta_info as $info) {
            if ($info->meta_key == "_menu_item_object_id") {
                $post_ids[] = $dat[$info->post_id]['post_id'] = $info->meta_value;
            }
            else if ($info->meta_key == "_menu_item_menu_item_parent") {
                $dat[$info->post_id]['menu_parent'] = $info->meta_value;
            }
            else if ($info->meta_key == "_menu_item_classes") {
                $dat[$info->post_id]['classes'] = maybe_unserialize($info->meta_value);
            }
        }
    
        //Now we get the post title for easier debugging and post menu_order to sync sorting as well as hierarchy
        $post_h = $wpdb->get_results("SELECT ID, post_title, menu_order FROM $wpdb->posts WHERE ID in (" . join(",",$post_ids) . ")");
        $post_titles = array();
        $post_orders = array();
        foreach ($post_h as $p) {
            $post_titles[$p->ID] = $p->post_title;
            $post_orders[$p->ID] = $p->menu_order;
        }
        foreach ($dat as $k => $data) {
            $dat[$k]['post_title'] = $post_titles[$data['post_id']];
            $dat[$k]['post_order'] = $post_orders[$data['post_id']];
        }
    
        return $dat;
    }    
    
    public static function sync_page_hierarchy() {
        
        global $wpdb;

        //FYI: Menus are stored as terms in the database
        //$terms = get_terms( 'nav_menu', array(
        //    'slug' => 'menu-slug',
        //    'taxonomy' => 'nav_menu'
        //));
        
        if (wp_verify_nonce(@$_GET['nonce'], "emc2eme_sync_menu")) {
            
            $dat = emc2_enhanced_menu_editor::get_nav_menu_struct($_GET['menu']);

            //Now let's enforce the menu hierarchy on the page hierarchy
            foreach ($dat as $d) {
                if ($d['menu_parent'] == 0) {
                    //Edge case: for top level nodes, since they don't have parents to reference
                    if ($d['post_parent'] != 0) {
                        //update posts set post_parent = 0 where post_id = $d['post_id']
                        wp_update_post(array(
                            'ID' => $d['post_id'],
                            'post_parent' => 0
                        ));
                    }
                }
                else if (
                    isset($dat[$d['menu_parent']]) &&
                    $dat[$d['menu_parent']]['post_id'] != $d['post_parent']) {
                    
                    //Typical case: post_parent doesn't match menu_parent's associated post_id
                    wp_update_post(array(
                        'ID' => $d['post_id'],
                        'post_parent' => $dat[$d['menu_parent']]['post_id']
                    ));
                }

                if ($d['menu_order'] != $d['post_order']) {
                    //Match page sort order to menu sort order as well
                    wp_update_post(array(
                        'ID' => $d['post_id'],
                        'menu_order' => $d['menu_order']
                    ));
                }
            }
            
            die("Page Synchronization Complete");
        }
    }

    public static function copy_menu($source, $target) {
        //create a new walker class to dupe the menu        
        $w = new emc2_enhanced_menu_walker($target);
        
        //"render" the source menu which will invoke the walker subclass
        wp_nav_menu( array(
            'container' => false,
            'menu' => $source,
            'echo' => false, //but don't really render it ;)
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'depth' => 0,
            'walker' => $w)
        );
    }
}

class emc2_enhanced_menu_walker extends Walker_Nav_Menu {
    
    private $target;
    private $assoc = array();
    
    public function __construct($target_menu) {
        $this->target = $target_menu;
    }

    function start_el(&$output, $item, $depth, $args) {
        $this->assoc[$item->ID] = wp_update_nav_menu_item(
            $this->target, //new menu target
            0,
            array(
                'menu-item-object-id' => @$item->object_id,
                'menu-item-type' => @$item->type,
                'menu-item-status' => @$item->post_status,
                'menu-item-parent-id' => @$this->assoc[@$item->menu_item_parent],
                'menu-item-position' => @$item->menu_order,
                'menu-item-object' => @$item->object,
                'menu-item-title' => @$item->title,
                'menu-item-url' => @$item->url,
                'menu-item-description' => @$item->description,
                'menu-item-attr-title' => @$item->attr_title,
                'menu-item-target' => @$item->target,
                'menu-item-classes' => @$item->classes[0],
                'menu-item-xfn' => @$item->xfn
            )
        );
    }
}