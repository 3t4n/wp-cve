<?php
/*
* Plugin Name: DevVN Local Store
* Version: 1.0.8
* Description: Find a Local Store by DevVN
* Author: Le Van Toan
* Author URI: http://levantoan.com
* Plugin URI: http://levantoan.com/find-a-local-store-by-devvn
* Text Domain: devvn-local-store
* Domain Path: /languages
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl-3.0

DevVN Local Store
Copyright (C) 2017 Le Van Toan - www.levantoan.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !class_exists( 'DevVN_Local_Store_Class' ) ) {
    add_action('plugins_loaded', array('DevVN_Local_Store_Class', 'init'));

    class DevVN_Local_Store_Class
    {
        protected static $instance;

        public $_version = '1.0.8';
        public $_optionName = 'dvls_options';
        public $_optionGroup = 'dvls-options-group';
        public $_defaultOptions = array(
            'maps_api' 	    =>	'',
            'lat_default' 	=>	'21.020799',
            'lng_default' 	=>	'105.809476',
            'maps_zoom'     =>  17,
            'marker_icon'   =>  '',
            'number_post'   =>  '20',
            'radius'       =>  '20',
            'disallow_labels'   =>  'Allow to access your location to load store near you',
            'get_directions'    =>  'Get Directions',
            'text_open'         =>  'Open',
            'text_phone'        =>  'Phone',
            'text_hotline'      =>  'Hotline',
            'text_email'        =>  'Email',
        );
        public $_defaultData = array(
            'name'  => '',
            'address'  => '',
            'city'  => '',
            'district'  => '',
            'phone1'  => '',
            'phone2'  => '',
            'hotline1'  => '',
            'hotline2'  => '',
            'email'  => '',
            'open'  => '',
            'marker'  => '',
            'maps_lat'  => '',
            'maps_lng'  => '',
            'maps_address'  => '',
        );

        public static function init()
        {
            is_null(self::$instance) AND self::$instance = new self;
            return self::$instance;
        }

        public function __construct()
        {
            $this->define_constants();

            global $dvls_settings;

            $dvls_settings  = $this->get_dvlsoptions();

            # INIT the plugin: Hook your callbacks
            add_action('init', array($this, 'cpt_local_store_func'), 0);
            add_action('init', array($this, 'local_category_func'), 0);

            add_filter( 'manage_edit-local-store_columns', array($this,'dvls_localstore_edit_orders_columns') ) ;
            add_action( 'manage_local-store_posts_custom_column', array($this,'dvls_localstore_manage_orders_columns'), 10, 2 );

            $this->dvls_load_textdomain();

            //remove metabox bawpvc plugin
            add_action('add_meta_boxes_local-store', array($this, 'remove_taxonomies_metaboxes'), 999);

            add_filter( 'plugin_action_links_' . DEVVN_LS_BASENAME, array( $this, 'add_action_links' ), 10, 2 );
            add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );

            add_action( 'add_meta_boxes', array($this,'local_store_meta_box') );
            add_action( 'edit_form_after_title', array($this,'edit_form_after_title') );
            add_action( 'save_post', array($this,'dvls_save_meta_box_data') );

            add_action( 'wp_enqueue_scripts', array($this, 'load_plugins_scripts') );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_init', array( $this, 'dvls_register_mysettings') );

            add_shortcode( 'devvn_local_stores', array( $this, 'devvn_local_stores_shortcode') );

            add_action( 'wp_ajax_dvls_load_localstores', array( $this, 'dvls_load_localstores_func') );
            add_action( 'wp_ajax_nopriv_dvls_load_localstores', array( $this, 'dvls_load_localstores_func') );

            add_action( 'wp_ajax_dvls_loadlastest_store', array( $this, 'dvls_loadlastest_store_func') );
            add_action( 'wp_ajax_nopriv_dvls_loadlastest_store', array( $this, 'dvls_loadlastest_store_func') );

            add_action( 'save_post', array($this, 'dvls_delete_all_transient'), 10, 1 );
            add_action( 'wp_insert_post', array($this, 'dvls_delete_all_transient'), 10, 1 );
            add_action( 'publish_post', array($this, 'dvls_delete_all_transient'), 10, 1 );

            add_action( 'admin_notices', array($this,'dvls_admin_notice') );

        }

        public function define_constants() {

            if ( !defined( 'DEVVN_LS_VERSION_NUM' ) )
                define( 'DEVVN_LS_VERSION_NUM', $this->_version );

            if ( !defined( 'DEVVN_LS_URL' ) )
                define( 'DEVVN_LS_URL', plugin_dir_url( __FILE__ ) );

            if ( !defined( 'DEVVN_LS_BASENAME' ) )
                define( 'DEVVN_LS_BASENAME', plugin_basename( __FILE__ ) );

            if ( !defined( 'DEVVN_LS_PLUGIN_DIR' ) )
                define( 'DEVVN_LS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }

        public function add_action_links( $links, $file ) {
            if ( strpos( $file, 'devvn-local-store.php' ) !== false ) {
                $settings_link = '<a href="' . admin_url( 'edit.php?post_type=local-store&page=devvnls_settings' ) . '" title="View DevVN Local Store Settings">' . __( 'Settings', 'devvn-local-store' ) . '</a>';
                array_unshift( $links, $settings_link );
            }
            return $links;
        }

        function admin_menu() {
            add_submenu_page(
                'edit.php?post_type=local-store',
                __( 'Find a local store Setting', 'devvn-local-store' ),
                __( 'Settings', 'devvn-local-store' ),
                'manage_options',
                'devvnls_settings',
                array($this,'dvls_flra_setting')
            );
        }

        function dvls_register_mysettings() {
            register_setting( $this->_optionGroup, $this->_optionName );
        }

        function  dvls_flra_setting() {
            include DEVVN_LS_PLUGIN_DIR . 'inc/dvls-optionpage.php';
        }

        function get_dvlsoptions(){
            return wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);
        }

        function load_plugins_scripts()
        {
            global $dvls_settings;
            wp_register_style('devvn-localstore-style', plugins_url('assets/css/devvn-localstore.css', __FILE__), array(), $this->_version, 'all');
            wp_register_script('dvls-google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$dvls_settings['maps_api'], array(), $this->_version, true);
            wp_register_script('devvn-localstore-script', plugins_url('assets/js/devvn-localstore-jquery.js', __FILE__), array('jquery'), $this->_version, true);
            $array = array(
                'ajaxurl'       => admin_url('admin-ajax.php'),
                'siteurl'       => home_url(),
                'local_address' =>  $this->get_local_json(),
                'maps_zoom'     =>  $dvls_settings['maps_zoom'],
                'select_text'   =>  __('Select district','devvn-local-store'),
                'lat_default'   =>  $dvls_settings['lat_default'],
                'lng_default'   =>  $dvls_settings['lng_default'],
                'close_icon'    =>  DEVVN_LS_URL.'assets/images/close-btn.png',
                'marker_default'   =>  ($dvls_settings['marker_icon']) ? wp_get_attachment_image_src($dvls_settings['marker_icon'],'full') : '',
                'labels'    => array(
                    'disallow_labels'   =>  $dvls_settings['disallow_labels'],
                    'get_directions'    =>  $dvls_settings['get_directions'],
                    'text_open'         =>  $dvls_settings['text_open'],
                    'text_phone'        =>  $dvls_settings['text_phone'],
                    'text_hotline'      =>  $dvls_settings['text_hotline'],
                    'text_email'        =>  $dvls_settings['text_email'],
                )
            );
            wp_localize_script('devvn-localstore-script', 'devvn_localstore_array', $array);
        }

        public function admin_enqueue_scripts() {
            $current_screen = get_current_screen();
            global $dvls_settings;
            if ( isset( $current_screen->post_type ) && $current_screen->post_type == 'local-store' ) {
                wp_enqueue_style('devvn-localstore-admin-styles', plugins_url('/assets/css/admin-style.css', __FILE__), array(), $this->_version, 'all');
                wp_enqueue_script('dvls-google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$dvls_settings['maps_api'].'&libraries=places', array(), $this->_version, true);
                wp_enqueue_script('devvn-localstore-admin-js', plugins_url('/assets/js/admin-ls-jquery.js', __FILE__), array('jquery'), $this->_version, true);
                wp_localize_script('devvn-localstore-admin-js', 'dvls_admin', array(
                    'delete_box_nonce'  =>  wp_create_nonce("delete-box"),
                    'local_address'     =>  $this->get_local_json(),
                    'maps_zoom'         =>  $dvls_settings['maps_zoom']
                ));
            }
        }

        public function admin_footer_text( $text ) {
            $current_screen = get_current_screen();
            if ( isset( $current_screen->post_type ) && $current_screen->post_type == 'local-store' ) {
                $text = sprintf( __( 'Developed by %sLê Văn Toản%s.', 'devvn-local-store' ), '<a href="http://levantoan.com" target="_blank"><strong>', '</strong></a>' );
            }
            return $text;
        }

        function cpt_local_store_func()
        {

            $labels = array(
                'name' => _x('Stores', 'Post Type General Name', 'devvn-local-store'),
                'singular_name' => _x('Store', 'Post Type Singular Name', 'devvn-local-store'),
                'menu_name' => __('Stores', 'devvn-local-store'),
                'name_admin_bar' => __('Store', 'devvn-local-store'),
                'archives' => __('Stores', 'devvn-local-store'),
                'attributes' => __('Store Attributes', 'devvn-local-store'),
                'parent_item_colon' => __('Parent Store:', 'devvn-local-store'),
                'all_items' => __('All Stores', 'devvn-local-store'),
                'add_new_item' => __('Add New Store', 'devvn-local-store'),
                'add_new' => __('Add New', 'devvn-local-store'),
                'new_item' => __('New Store', 'devvn-local-store'),
                'edit_item' => __('Edit Store', 'devvn-local-store'),
                'update_item' => __('Update Store', 'devvn-local-store'),
                'view_item' => __('View Store', 'devvn-local-store'),
                'view_items' => __('View Stores', 'devvn-local-store'),
                'search_items' => __('Search Store', 'devvn-local-store'),
                'not_found' => __('Not found', 'devvn-local-store'),
                'not_found_in_trash' => __('Not found in Trash', 'devvn-local-store'),
                'featured_image' => __('Featured Image', 'devvn-local-store'),
                'set_featured_image' => __('Set featured image', 'devvn-local-store'),
                'remove_featured_image' => __('Remove featured image', 'devvn-local-store'),
                'use_featured_image' => __('Use as featured image', 'devvn-local-store'),
                'insert_into_item' => __('Insert into item', 'devvn-local-store'),
                'uploaded_to_this_item' => __('Uploaded to this item', 'devvn-local-store'),
                'items_list' => __('Items list', 'devvn-local-store'),
                'items_list_navigation' => __('Items list navigation', 'devvn-local-store'),
                'filter_items_list' => __('Filter items list', 'devvn-local-store'),
            );
            $args = array(
                'label' => __('Store', 'devvn-local-store'),
                'description' => __('Find a local store', 'devvn-local-store'),
                'labels' => $labels,
                'supports' => array('title', 'editor', 'excerpt', 'thumbnail',),
                'taxonomies' => array('local_category'),
                'hierarchical' => false,
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-store',
                'show_in_admin_bar' => false,
                'show_in_nav_menus' => false,
                'can_export' => true,
                'has_archive' => false,
                'exclude_from_search' => true,
                'publicly_queryable' => false,
                'capability_type' => 'page',
            );
            register_post_type('local-store', $args);
        }

        function local_category_func()
        {

            $labels = array(
                'name' => _x('Local Address', 'Taxonomy General Name', 'devvn-local-store'),
                'singular_name' => _x('Local Address', 'Taxonomy Singular Name', 'devvn-local-store'),
                'menu_name' => __('Local Address', 'devvn-local-store'),
                'all_items' => __('Local Address', 'devvn-local-store'),
                'parent_item' => __('Parent Item', 'devvn-local-store'),
                'parent_item_colon' => __('Parent Item:', 'devvn-local-store'),
                'new_item_name' => __('New Item Name', 'devvn-local-store'),
                'add_new_item' => __('Add New Item', 'devvn-local-store'),
                'edit_item' => __('Edit Item', 'devvn-local-store'),
                'update_item' => __('Update Item', 'devvn-local-store'),
                'view_item' => __('View Item', 'devvn-local-store'),
                'separate_items_with_commas' => __('Separate items with commas', 'devvn-local-store'),
                'add_or_remove_items' => __('Add or remove items', 'devvn-local-store'),
                'choose_from_most_used' => __('Choose from the most used', 'devvn-local-store'),
                'popular_items' => __('Popular Items', 'devvn-local-store'),
                'search_items' => __('Search Items', 'devvn-local-store'),
                'not_found' => __('Not Found', 'devvn-local-store'),
                'no_terms' => __('No items', 'devvn-local-store'),
                'items_list' => __('Items list', 'devvn-local-store'),
                'items_list_navigation' => __('Items list navigation', 'devvn-local-store'),
            );
            $args = array(
                'labels' => $labels,
                'hierarchical' => true,
                'public' => false,
                'show_ui' => true,
                'show_admin_column' => false,
                'show_in_nav_menus' => false,
                'show_tagcloud' => false,
                'show_in_quick_edit' => false,
                //'meta_box_cb' => false, //Ẩn hiện box taxonomy trong mục bài viết
            );
            register_taxonomy('local_category', array('local-store'), $args);
        }

        function remove_taxonomies_metaboxes()
        {
            remove_meta_box('bawpvc_meta_box', 'local-store', 'side');
        }

        function local_store_meta_box() {
            add_meta_box(
                'devvn_local_store_meta',
                __( 'Store information', 'devvn-local-store' ),
                array($this,'local_store_meta_box_callback'),
                'local-store',
                'after_title',
                'high'
            );
        }

        function local_store_meta_box_callback($post){
            require_once ( DEVVN_LS_PLUGIN_DIR . 'inc/dvls-metabox.php' );
        }

        function edit_form_after_title() {
            global $post, $wp_meta_boxes;

            do_meta_boxes( get_current_screen(), 'after_title', $post );

            unset( $wp_meta_boxes['local-store']['after_title'] );
        }

        function get_level($category, $level = 0){
            if ($category->parent == 0) {
                return $level;
            }else{
                $level++;
                $category = get_term($category->parent);
                return $this->get_level($category, $level);
            }
        }

        function display_cat_level( $taxonomy = 'category', $level = 0 , $parent = NULL){
            $output = array();
            $catArgs = array( 'hide_empty' => false);
            if( $parent != NULL ){
                $catArgs['child_of'] = $parent;
            }
            $catArgs['taxonomy'] = $taxonomy;

            $cats = get_terms( $catArgs );

            if( $cats && !is_wp_error($cats) ){
                $stt = 0;
                foreach($cats as $cat){
                    $current_cat_level = $this->get_level($cat);
                    if( $current_cat_level == $level ){
                        $output[$stt]['name'] = $cat->name;
                        //$output[$stt]['link'] = get_term_link($cat->term_id);
                        $output[$stt]['id'] = $cat->term_id;
                        $stt++;
                    }
                }
            }
            return $output;
        }

        function get_local_json(){
            $cities = $this->display_cat_level('local_category');
            if($cities && is_array($cities)){
                foreach ($cities as $k=>$city){
                    $districts = $this->display_cat_level('local_category',1,$city['id']);
                    if($districts && is_array($districts)) {
                        $cities[$k]['district'] = $districts;
                    }
                }
            }
            return json_encode($cities);
        }

        function dvls_save_meta_box_data( $post_id ) {

            if ( ! isset( $_POST['dvls_meta_box_nonce'] ) ) {
                return;
            }
            if ( ! wp_verify_nonce( $_POST['dvls_meta_box_nonce'], 'dvls_save_meta_box_data' ) ) {
                return;
            }
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }
            if ( isset( $_POST['post_type'] ) && 'local-store' == $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return;
                }
            } else {
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            }
            if ( ! isset( $_POST['dvls'] ) ) {
                return;
            }

            $dvls_data = array_map('sanitize_text_field',$_POST['dvls']);

            $city = isset($dvls_data['city'])?intval($dvls_data['city']):0;
            $district = isset($dvls_data['district'])?intval($dvls_data['district']):0;

            $terms = array($city,$district);

            if($dvls_data && is_array($dvls_data)){
                update_post_meta($post_id,'dvls_data',$dvls_data);
                //wp_set_post_terms( $post_id, $terms, 'local_category', false );
            }
        }

        function devvn_local_stores_shortcode( $atts ){
            $atts = shortcode_atts( array(
                'city' => '',
            ), $atts, 'devvn_local_stores' );

            $city = (isset($atts['city']) && $atts['city']) ? intval($atts['city']) : '';
            wp_enqueue_style('devvn-localstore-style');
            wp_enqueue_script('dvls-google-maps');
            wp_enqueue_script('devvn-localstore-script');
            ob_start();
            include DEVVN_LS_PLUGIN_DIR . 'inc/dvls-shortcode.php';
            return ob_get_clean();
        }

        function dvls_get_store_infor($storeid = null){
            if(!$storeid || !is_numeric($storeid)) return false;
            $output = array();
            $store_data = wp_parse_args(get_post_meta($storeid,'dvls_data','true'),$this->_defaultData);
            $marker = (isset($store_data['marker']) && $store_data['marker']) ? wp_get_attachment_image_src($store_data['marker'],'full') : '';
            //if($marker) $marker = $marker[0];
            $output['id'] = $storeid;
            $output['title'] = get_the_title($storeid);
            $output['name'] = $store_data['name'];
            $output['thumb'] = get_the_post_thumbnail_url($storeid,'full');
            $output['address'] = $store_data['address'];
            $output['city'] = $store_data['city'];
            $output['district'] = $store_data['district'];
            $output['phone1'] = $store_data['phone1'];
            $output['phone2'] = $store_data['phone2'];
            $output['hotline1'] = $store_data['hotline1'];
            $output['hotline2'] = $store_data['hotline2'];
            $output['email'] = $store_data['email'];
            $output['open'] = $store_data['open'];
            $output['marker'] = $marker;
            $output['maps_lat'] = $store_data['maps_lat'];
            $output['maps_lng'] = $store_data['maps_lng'];
            return $output;
        }

        function dvls_load_localstores_func(){
            /*if ( !wp_verify_nonce( $_REQUEST['nonce'], "dvls_nonce_action")) {
                wp_send_json_error();
            }*/
            global $dvls_settings;
            $cityid = (isset($_POST['cityid']) && intval($_POST['cityid']) > 0) ? intval($_POST['cityid']) : '';
            $districtid = (isset($_POST['districtid']) && intval($_POST['districtid']) > 0) ? intval($_POST['districtid']) : '';
            $number_post = ($dvls_settings['number_post']) ? intval($dvls_settings['number_post']) : intval($this->_defaultOptions['number_post']);

            $args = array(
                'post_type' =>  'local-store',
            );

            if(!$cityid && !$districtid){
                $args['posts_per_page'] = $number_post;
            }else {
                $args['posts_per_page'] = -1;
            }
            if($cityid || $districtid) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                );
                if ($cityid) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'local_category',
                        'field' => 'term_id',
                        'terms' => array($cityid),
                    );
                }
                if ($districtid) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'local_category',
                        'field' => 'term_id',
                        'terms' => array($districtid),
                    );
                }
            }
            $storesList = array();
            $stores = get_posts($args);
            if($stores && !is_wp_error($stores)){
                foreach ($stores as $store){
                    $storesList[] = $this->dvls_get_store_infor($store->ID);
                }
                wp_send_json_success($storesList);
            }
            wp_send_json_error();
            die();
        }

        function dvls_distance($lat1, $lon1, $lat2, $lon2) {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515 * 1.609344;
            return $miles;
        }
        function dvls_loadnear_store($lat = '', $lng = '', $radius = 20){
            if(!$lat || !$lng) return false;
            $storesList = array();
            $transientName = 'dvls_cache_query_allpost';
            if ( false === ( $near_store = get_transient( $transientName ) ) ) {
                $args = array(
                    'post_type' => 'local-store',
                    'posts_per_page' => -1,
                );
                $near_store = get_posts($args);
                set_transient( $transientName, $near_store );
            }
            if($near_store && !is_wp_error($near_store)){
                foreach ($near_store as $store){
                    $store_data = get_post_meta($store->ID,'dvls_data','true');
                    $latPost = (isset($store_data['maps_lat']) && $store_data['maps_lat']) ? (float) $store_data['maps_lat'] : '';
                    $lngPost = (isset($store_data['maps_lng']) && $store_data['maps_lng']) ? (float) $store_data['maps_lng'] : '';
                    if($latPost && $lngPost && $lat && $lng)
                    $store_radius = $this->dvls_distance($lat,$lng,$latPost,$lngPost);
                    if($store_radius <= $radius) {
                        $storesList[] = $store->ID;
                    }
                }
            }
            return $storesList;
        }

        function dvls_loadlastest_store_func(){
            /*if ( !wp_verify_nonce( $_REQUEST['nonce'], "dvls_nonce_action")) {
                wp_send_json_error();
            }*/
            global $dvls_settings;
            $near = (isset($_POST['near']) && $_POST['near']) ? true : false;
            $lat = (isset($_POST['lat']) && $_POST['lat']) ? (float) $_POST['lat'] : '';
            $lng = (isset($_POST['lng']) && $_POST['lng']) ? (float) $_POST['lng'] : '';

            $number_post = ($dvls_settings['number_post']) ? intval($dvls_settings['number_post']) : intval($this->_defaultOptions['number_post']);
            $args = array(
                'post_type' =>  'local-store',
            );

            if(!$near){
                $args['posts_per_page'] = $number_post;
            }else{
                $args['posts_per_page'] = -1;
                if($lat && $lng){
                    $posts_in = $this->dvls_loadnear_store($lat, $lng, $dvls_settings['radius']);
                    $args['post__in'] = $posts_in;
                }
            }
            $storesList = array();
            $stores = get_posts($args);
            if($stores && !is_wp_error($stores)){
                foreach ($stores as $store){
                    $storesList[] = $this->dvls_get_store_infor($store->ID);
                }
                wp_send_json_success($storesList);
            }
            wp_send_json_error();
            die();
        }
        function dvls_delete_all_transient($post_id) {
            global $wpdb;
            if(get_post_type($post_id) == 'local-store') {
                $menus = $wpdb->get_col('SELECT option_name FROM ' . $wpdb->prefix . 'options WHERE option_name LIKE "_transient_dvls_cache_query_%" ');
                foreach ($menus as $menu) {
                    $key = str_replace('_transient_', '', $menu);
                    delete_transient($key);
                }
                wp_cache_flush();
            }
        }

        function dvls_localstore_edit_orders_columns( $columns ) {
            $columns = array(
                'cb' 			=> '<input type="checkbox" />',
                'title' 		=> __( 'Name','devvn-local-store' ),
                'address'       =>  __('Address','devvn-local-store'),
                'open'          =>  __('Open','devvn-local-store'),
                'thumbnail'     =>  __('Thumbnail','devvn-local-store'),
                'date'          => __('Date','devvn')
            );
            return $columns;
        }

        function dvls_storedata($post_id = ''){
            if(!$post_id) return false;
            $dvls_data = get_post_meta($post_id,'dvls_data',true);
            $dvls_data = wp_parse_args($dvls_data,$this->_defaultData);
            return $dvls_data;
        }

        function dvls_localstore_manage_orders_columns( $column, $post_id ) {
            $storeData = $this->dvls_storedata($post_id);
            switch( $column ) {
                case 'thumbnail' :
                    if(has_post_thumbnail($post_id)){
                        echo get_the_post_thumbnail($post_id,array(80,80));
                    }
                    break;
                case 'address' :
                    echo $storeData['address'];
                    break;
                case 'open' :
                    echo $storeData['open'];
                    break;
                default :
                    break;
            }
        }

        function dvls_load_textdomain() {
			load_textdomain('devvn-local-store', dirname(__FILE__) . '/languages/devvn-local-store-' . get_locale() . '.mo');
            load_plugin_textdomain( 'devvn-local-store', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
        }

        function dvls_admin_notice() {
            global $dvls_settings;
            $current_screen = get_current_screen();
            if ( isset( $current_screen->post_type ) && $current_screen->post_type == 'local-store' && !$dvls_settings['maps_api'] ) {
                echo '<div class="error"><p><strong>' . sprintf( __('You haven\'t set Google Maps Api yet. Please go to the <a href="%s">plugin admin page</a> to set.', 'devvn-local-store' ), admin_url( 'edit.php?post_type=local-store&page=devvnls_settings' ) ) . '</strong></p></div>';
            }
        }

    }
}