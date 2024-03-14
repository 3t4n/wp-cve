<?php

namespace LaStudioKitExtensions\MegaMenu;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use LaStudioKitExtensions\Module_Base;

class Module extends Module_Base {

    /**
     * Module version.
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module directory path.
     *
     * @since 1.0.0
     * @access protected
     * @var string $path
     */
    protected $path;

    /**
     * Module directory URL.
     *
     * @since 1.0.0
     * @access protected
     * @var string $url.
     */
    protected $url;

    /**
     * Menu settings page
     *
     * @var string
     */
    protected $meta_key = 'lakit_menu_settings';

    /**
     * Holder for current menu ID
     * @var integer
     */
    protected $current_menu_id = null;

    public static function is_active(){
        if( lastudio_kit()->get_theme_support('elementor::mega-menu') ){
            return true;
        }
        return false;
    }

    public function __construct()
    {
        $this->path = lastudio_kit()->plugin_path('includes/extensions/mega-menu/');
        $this->url  = lastudio_kit()->plugin_url('includes/extensions/mega-menu/');

        add_action( 'elementor/init',                   [$this, 'add_support']  );
        add_action( 'elementor/document/after_save',    [$this, 'save_posts']   );
        add_filter( 'pre_get_posts',                     [$this, 'pre_get_posts'], 10 );
        add_filter( 'elementor/document/urls/wp_preview',[$this, 'add_preview'], 10, 2 );
        add_filter( 'wp_insert_post_data',               [$this, 'revision_autosave'], 10 );

        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 99 );
        add_action( 'admin_footer', array( $this, 'print_menu_settings_vue_template' ), 10 );

        add_action( 'wp_ajax_lakit_get_nav_item_settings', array( $this, 'get_nav_item_settings' ) );
        add_action( 'wp_ajax_lakit_save_nav_item_settings', array( $this, 'save_nav_item_settings' ) );

        add_action( 'init', array( $this, 'edit_redirect' ), 0 );

        add_action( 'template_include', array( $this, 'set_post_type_template' ), 9999 );
    }

    public function admin_scripts(){

        $screen = get_current_screen();

        if ( 'nav-menus' !== $screen->base ) {
            return;
        }

        $module_data = lastudio_kit()->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );
        $ui          = new \CX_Vue_UI( $module_data );

        $ui->enqueue_assets();

        wp_enqueue_style(
            'lastudio-kit-menu-css',
            $this->url . 'assets/css/menu-admin.css',
            null,
            $this->version
        );

        wp_enqueue_script(
            'lastudio-kit-menu-js',
            $this->url . 'assets/js/menu-admin.js',
            array( 'cx-vue-ui' ),
            $this->version,
            true
        );

        wp_localize_script(
            'lastudio-kit-menu-js',
            'LaStudioKitMenuConfig',
            apply_filters( 'lastudio-kit/module/menu/admin/nav-settings-config', array(
                'labels'        => array(
                    'itemTriggerLabel'    => '<span class="dashicons dashicons-admin-generic"></span>' . __( 'Settings', 'lastudio-kit' ),
                    'itemMegaEnableLabel' => '<span class="dashicons dashicons-saved"></span>' . __( 'Mega Activated', 'lastudio-kit' ),
                ),
                'editURL'       => add_query_arg(
                    array(
                        'lakit-open-editor' => 1,
                        'item'            => '%id%',
                        'menu'            => '%menuid%',
                    ),
                    esc_url( admin_url( '/' ) )
                ),
                'currentMenuId' => $this->get_selected_menu_id(),
                'controlData'      => $this->default_nav_item_controls_data(),
                'iconsFetchJson'   => lastudio_kit()->plugin_url( 'includes/extensions/elementor/assets/fonts/LaStudioIcons.json' ),
                'itemsSettings'    => $this->get_menu_items_settings(),
            ) )
        );

    }

    public function add_support(){
        add_post_type_support( 'nav_menu_item', 'elementor' );
    }

    public function pre_get_posts( $query ){
        if ( is_admin() || ! $query->is_main_query() ) {
            return;
        }
        if((isset($_GET['elementor-preview']) || isset($_GET['preview_id'])) && current_user_can('edit_theme_options') ){
            $current_id = 0;
            if(isset($_GET['elementor-preview'])){
                $current_id = absint($_GET['elementor-preview']);
            }
            if(isset($_GET['preview_id'])){
                $current_id = absint($_GET['preview_id']);
            }
            if( 'nav_menu_item' == get_post_type($current_id) ) {
                $query->set('post_type', 'nav_menu_item');
            }
        }
    }

    public function add_preview( $url, $instance ){
        if(empty($url)){
            $main_post_id = $instance->get_main_id();
            $preview_link = set_url_scheme( get_permalink( $main_post_id ) );
            $preview_link = add_query_arg(
                [
                    'preview_id' => $main_post_id,
                    'preview_nonce' => wp_create_nonce( 'post_preview_' . $main_post_id ),
                    'preview' => 'true'
                ],
                $preview_link
            );
            $url = $preview_link;
        }
        return $url;
    }

    public function save_posts( $instance ){
        $post = $instance->get_post();
        $post_id = $instance->get_main_id();
        $old_content = $post->post_content;

        if($post->post_type == 'nav_menu_item'){
            wp_update_post([
                'ID' => $post_id,
                'post_content' => $old_content,
            ]);
            lastudio_kit()->elementor()->files_manager->clear_cache();
        }
    }

    public function revision_autosave( $data ){
        if(strpos($data['post_content'], '<!-- Created With Elementor -->') !== false ){
            $data['post_content'] = '<!-- Created With Elementor --><!-- ' . current_time('timestamp') . ' -->';
        }
        return $data;
    }

    public function default_nav_item_controls_data(){
        return array(
            'enabled' => array(
                'value' => false,
            ),
            'custom_mega_menu_position' => array(
                'value'   => 'default',
                'options' => array(
                    array(
                        'label' => esc_html__( 'Default', 'lastudio-kit' ),
                        'value' => 'default',
                    ),
                    array(
                        'label' => esc_html__( 'Relative item', 'lastudio-kit' ),
                        'value' => 'relative-item',
                    )
                ),
            ),
            'custom_mega_menu_width' => array(
                'value' => '',
            ),
            'menu_icon_type' => array(
                'value'   => 'icon',
                'options' => array(
                    array(
                        'label' => esc_html__( 'Icon', 'lastudio-kit' ),
                        'value' => 'icon',
                    ),
                    array(
                        'label' => esc_html__( 'SVG', 'lastudio-kit' ),
                        'value' => 'svg',
                    )
                ),
            ),
            'menu_icon' => array(
                'value' => '',
            ),
            'menu_svg' => array(
                'value' => '',
            ),
            'icon_color' => array(
                'value' => '',
            ),
            'icon_size' => array(
                'value' => '',
            ),
            'menu_badge' => array(
                'value' => '',
            ),
            'badge_color' => array(
                'value' => '',
            ),
            'badge_bg_color' => array(
                'value' => '',
            ),
            'hide_item_text' => array(
                'value' => '',
            ),
        );
    }

    /**
     * Print tabs templates
     *
     * @return void
     */
    public function print_menu_settings_vue_template() {

        $screen = get_current_screen();

        if ( 'nav-menus' !== $screen->base ) {
            return;
        }

        include lastudio_kit()->get_template( 'admin-templates/menu/menu-settings-nav.php' );
    }

    /**
     * [get_nav_item_settings description]
     * @return [type] [description]
     */
    public function get_nav_item_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array(
                'message' => esc_html__( 'You are not allowed to do this', 'lastudio-kit' ),
            ) );
        }

        $data = isset( $_POST['data'] ) ? $_POST['data'] : false;

        if ( ! $data ) {
            wp_send_json_error( array(
                'message' => esc_html__( 'Incorrect input data', 'lastudio-kit' ),
            ) );
        }

        $default_settings = array();

        foreach ( $this->default_nav_item_controls_data() as $key => $value ) {
            $default_settings[ $key ] = $value['value'];
        }

        $current_settings = $this->get_item_settings( absint( $data['itemId'] ) );

        $current_settings = wp_parse_args( $current_settings, $default_settings );

        wp_send_json_success( array(
            'message'  => esc_html__( 'Success!', 'lastudio-kit' ),
            'settings' => $current_settings,
        ) );
    }

    /**
     * [save_nav_item_settings description]
     * @return [type] [description]
     */
    public function save_nav_item_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array(
                'message' => esc_html__( 'You are not allowed to do this', 'lastudio-kit' ),
            ) );
        }

        $data = isset( $_POST['data'] ) ? $_POST['data'] : false;

        if ( ! $data ) {
            wp_send_json_error( array(
                'message' => esc_html__( 'Incorrect input data', 'lastudio-kit' ),
            ) );
        }

        $item_id = $data['itemId'];
        $settings = $data['itemSettings'];

        $sanitized_settings = array();

        foreach ( $settings as $key => $value ) {
            $sanitized_settings[ $key ] = $this->sanitize_field( $key, $value );
        }

        $current_settings = $this->get_item_settings( $item_id );

        $new_settings = array_merge( $current_settings, $sanitized_settings );

        $this->set_item_settings( $item_id, $new_settings );

        do_action( 'lastudio-kit/module/menu/item-settings/save', $item_id );

        lastudio_kit()->elementor()->files_manager->clear_cache();

        wp_send_json_success( array(
            'message' => esc_html__( 'Item settings have been saved', 'lastudio-kit' ),
        ) );
    }

    /**
     * Returns menu item settings
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function get_item_settings( $id ) {
        $settings = get_post_meta( $id, $this->meta_key, true );

        return ! empty( $settings ) ? $settings : array();
    }

    /**
     * Update menu item settings
     *
     * @param integer $id       [description]
     * @param array   $settings [description]
     */
    public function set_item_settings( $id = 0, $settings = array() ) {
        update_post_meta( $id, $this->meta_key, $settings );
    }

    /**
     * Sanitize field
     *
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function sanitize_field( $key, $value ) {

        $specific_callbacks = apply_filters( 'lastudio-kit/module/menu/nav-item-settings/sanitize-callbacks', array(
            'icon_size'    => 'absint',
            'menu_badge'   => 'wp_kses_post',
        ) );

        $callback = isset( $specific_callbacks[ $key ] ) ? $specific_callbacks[ $key ] : false;

        if ( ! $callback ) {
            return $value;
        }

        return call_user_func( $callback, $value );
    }

    /**
     * Get the current menu ID.
     *
     * @author Tom Hemsley (https://wordpress.org/plugins/megamenu/)
     * @return int
     */
    public function get_selected_menu_id() {

        if ( null !== $this->current_menu_id ) {
            return $this->current_menu_id;
        }

        $nav_menus            = wp_get_nav_menus( array('orderby' => 'name') );
        $menu_count           = count( $nav_menus );
        $nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;
        $add_new_screen       = ( isset( $_GET['menu'] ) && 0 == $_GET['menu'] ) ? true : false;

        $this->current_menu_id = $nav_menu_selected_id;

        // If we have one theme location, and zero menus, we take them right into editing their first menu
        $page_count = wp_count_posts( 'page' );
        $one_theme_location_no_menus = ( 1 == count( get_registered_nav_menus() ) && ! $add_new_screen && empty( $nav_menus ) && ! empty( $page_count->publish ) ) ? true : false;

        // Get recently edited nav menu
        $recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
        if ( empty( $recently_edited ) && is_nav_menu( $this->current_menu_id ) ) {
            $recently_edited = $this->current_menu_id;
        }

        // Use $recently_edited if none are selected
        if ( empty( $this->current_menu_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {
            $this->current_menu_id = $recently_edited;
        }

        // On deletion of menu, if another menu exists, show it
        if ( ! $add_new_screen && 0 < $menu_count && isset( $_GET['action'] ) && 'delete' == $_GET['action'] ) {
            $this->current_menu_id = $nav_menus[0]->term_id;
        }

        // Set $this->current_menu_id to 0 if no menus
        if ( $one_theme_location_no_menus ) {
            $this->current_menu_id = 0;
        } elseif ( empty( $this->current_menu_id ) && ! empty( $nav_menus ) && ! $add_new_screen ) {
            // if we have no selection yet, and we have menus, set to the first one in the list
            $this->current_menu_id = $nav_menus[0]->term_id;
        }

        return $this->current_menu_id;

    }

    /**
     * @return mixed
     */
    public function get_menu_items_settings() {
        $menu_items = $this->get_menu_items_object_data( $this->get_selected_menu_id() );

        $settings = [];

        if ( ! $menu_items ) {
            return $settings;
        }

        foreach ( $menu_items as $key => $item_obj ) {
            $item_id = $item_obj->ID;

            $settings[ $item_id ] = $this->get_item_settings( $item_id );
        }

        return $settings;
    }

    /**
     * [get_menu_items_object_data description]
     * @param  boolean $menu_id [description]
     * @return [type]           [description]
     */
    public function get_menu_items_object_data( $menu_id = false ) {

        if ( ! $menu_id ) {
            return false;
        }

        $menu = wp_get_nav_menu_object( $menu_id );

        $menu_items = wp_get_nav_menu_items( $menu );

        if ( ! $menu_items ) {
            return false;
        }

        return $menu_items;
    }

    /**
     * Edit redirect
     *
     * @return void
     */
    public function edit_redirect(){

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( empty( $_REQUEST['lakit-open-editor'] ) ) {
            return;
        }

        if ( empty( $_REQUEST['item'] ) ) {
            return;
        }

        if ( empty( $_REQUEST['menu'] ) ) {
            return;
        }

        $menu_id      = intval( $_REQUEST['menu'] );
        $menu_item_id = intval( $_REQUEST['item'] );

        $edit_link = add_query_arg(
            array(
                'post'        => $menu_item_id,
                'action'      => 'elementor',
                'context'     => 'lakit-menu',
                'parent_menu' => $menu_id,
            ),
            admin_url( 'post.php' )
        );

        wp_redirect( $edit_link );

        die();
    }

    /**
     * Set blank template for editor
     */
    public function set_post_type_template( $template ) {

        $found = false;

        if ( is_singular( 'nav_menu_item' ) ) {
            $found    = true;
            $template = lastudio_kit()->plugin_path( 'templates/admin-templates/menu/blank.php' );
        }

        if ( $found ) {
            do_action( 'lastudio-kit/template-include/found' );
        }

        return $template;

    }
}