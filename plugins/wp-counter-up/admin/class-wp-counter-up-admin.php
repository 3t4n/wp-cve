<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://logichunt.com
 * @since      1.0.0
 *
 * @package    Wp_Counter_Up
 * @subpackage Wp_Counter_Up/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Counter_Up
 * @subpackage Wp_Counter_Up/admin
 * @author     LogicHunt <logichunt.info@gmail.com>
 */
class Wp_Counter_Up_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

        /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $meta_form;




    /**
     * The plugin plugin_base_file of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string plugin_base_file The plugin plugin_base_file of the plugin.
     */
    protected $plugin_base_file;



    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

      //  $this->settings_api = new WP_Counter_Up_Settings_API($plugin_name, $version);

        $this->init_meta_form();


        $this->plugin_base_file = plugin_basename(plugin_dir_path(__FILE__).'../' . $this->plugin_name . '.php');

    }

    
    /**
     *
     * Initialized Dynamic Meta field 
     *
     */
    private function init_meta_form() {
        //wp_die( trailingslashit( dirname(  ) )  );
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/LgxMetaForm.php';
        $this->meta_form = new ClassWPCounterUpMetaForm();
    }

     /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_links_admin_plugin_page_title( $links ) {

        return array_merge( array(
            'create' => '<a href="' . admin_url( 'post-new.php?post_type=lgx_wcu_generator' ) . '" >' . esc_html__( 'Add New', $this->plugin_name) . '</a>',           
           // 'docs'    => '<a href="' .esc_url('https://docs.logichunt.com/wp-counter-up') . '" target="_blank">' . esc_html__( 'Docs', $this->plugin_name) . '</a>',
           'get_pro' => '<a style="color:#11b916; font-weight: bold;" href="https://logichunt.com/product/wordpress-counter-up">' . esc_html__( 'Get Pro!', $this->plugin_name) . '</a>',

           // 'support' => '<a style="color:#00a500;" target="_blank" href="' .esc_url('https://logichunt.com/support/') . '" target="_blank">' . esc_html__( 'Support', $this->plugin_name) . '</a>',
            
            
        ), $links );


    }//end plugin_listing_setting_link


    /**
     * Filters the columns displayed in the Posts list table for a specific post type.
     *
     * apply_filters( "manage_{$post_type}_posts_columns", string[] $post_columns )
     * Deafult Value : cb, title, taxonomy-logosliderwpcat, date
     * @param $default_columns
     */
    public function add_new_column_head_for_lgx_counter($default_columns) {

        // unset( $default_columns['date'] );

        $new_columns['lgx_counter_icon']    = __( 'Counter Icon', $this->plugin_name );
      //  $new_columns['lgx_counter_category']     = __( 'Categories', $this->plugin_name );

        return array_slice( $default_columns, 0, 2, true ) + $new_columns + array_slice( $default_columns, 1, null, true );

    }

    

    /**
     * Fires for each custom column of a specific post type in the Posts list table.
     * do_action( "manage_{$post->post_type}_posts_custom_column", string $column_name, int $post_id )]
     *
     * @param $column
     * @param $post_id
     */
    public function define_admin_column_value_for_lgx_counter($column, $post_id) {
        switch ($column) {
            case 'lgx_counter_category': 

                    $lgx_counter_categories = get_the_terms( $post_id, 'lgxcountercat' );

                    if ( ! empty( $lgx_counter_categories ) && ! is_wp_error( $lgx_counter_categories ) ) {

                        $lgx_counter_categories = wp_list_pluck( $lgx_counter_categories, 'name' );
                
                        foreach ($lgx_counter_categories as $lgx_cat_name) {
                            echo '<span class="button button-secondary" style="margin: 0 2px 2px 0; border-color:#a5adc3; color:#2c3338">' . $lgx_cat_name . '</span>';
                          }

                    }
                    break;

            case 'lgx_counter_icon':

                if( has_post_thumbnail( $post_id) ){
                    $post_thumbnail_id = get_post_thumbnail_id($post_id);
                    $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
                    if(!empty($post_thumbnail_img)) {
                        $post_thumbnail_img= $post_thumbnail_img[0];
                        echo '<img src="' . $post_thumbnail_img . '" />';
                    } else {
                        echo '-';
                    }
                }
                else{
                    echo 'No icon added.';
                }

                break;

            default:
                break;
        }
    }



    /**
     * Add support link to plugin description in /wp-admin/plugins.php
     *
     * @param  array  $plugin_meta
     * @param  string $plugin_file
     *
     * @return array
     */
    public function add_links_admin_plugin_page_description($plugin_meta, $plugin_file) {

        if ($this->plugin_base_file == $plugin_file) {
            $plugin_meta[] = sprintf(
                '<a href="%s">%s</a>', 'https://logichunt.com/support/', __('Get Support', $this->plugin_name)
            );
        }

        return $plugin_meta;
    }


    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    2.0.0
     */
    public function add_plugin_admin_menu() {

        $this->plugin_screen_hook_suffix  = add_submenu_page('edit.php?post_type=lgx_counter', __('Usage & Help', 'wp-counter-up'), __('Usage & Help', $this->plugin_name), 'manage_options', 'lgx_counter_help_usage', array($this, 'display_plugin_admin_usage_help'));

    }


    function display_plugin_admin_usage_help() {
        global $wpdb;

        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_base_file);

        include('partials/admin-usage-help.php');
    }



    

    /**
     * Change Feature image input Position
     * new: changing_meta_box_position_of_icon
     *  Since 2.0.0
     */
    public  function changing_meta_box_position_of_featured_image(){
        remove_meta_box( 'postimagediv', 'lgx_counter', 'side' );
        add_meta_box('postimagediv', __('Icon Image'), 'post_thumbnail_meta_box', 'lgx_counter', 'normal', 'high');
    }

    /**
     * Ensure post thumbnail support is turned on.
     * Since 1.1.0
     */
    public function add_thumbnail_support() {
        if ( ! current_theme_supports( 'post-thumbnails' ) ) {
            add_theme_support( 'post-thumbnails' );
        }
        add_post_type_support( 'lgx_counter', 'thumbnail' );
    }


    /**
     * Add support link to plugin description in /wp-admin/plugins.php
     *
     * @param  array  $plugin_meta
     * @param  string $plugin_file
     *
     * @return array
     */
    public function support_link($plugin_meta, $plugin_file) {

        if ($this->plugin_base_file == $plugin_file) {
            $plugin_meta[] = sprintf(
                '<a href="%s">%s</a>', 'http://logichunt.com/support', __('Support',  $this->plugin_name)
            );
        }

        return $plugin_meta;
    }


    
    /**
     * Modified get post for post type order
     *
     */
    public function modify_query_get_posts($query) {

        if ( ! is_admin() && ( isset( $query->query_vars['post_type'] ) &&  ( is_array( $query->query_vars['post_type'] ) && in_array( 'lgx_counter', $query->query_vars['post_type'] ) ) ) ) {

            //$order  =   isset( $query->query_vars['order'] )  ?  $query->query_vars['order'] : '';

            //var_dump( '<pre>', $query );
            //wp_die(  );

           // $query->set( 'orderby', 'menu_order' ); // hided from v3.2.0
           // $query->set( 'order' , 'ASC' ); // hided from v3.2.0
           

        } elseif ( is_admin() ) {
            if ( $query->is_main_query() ) {
                $currentScreen = get_current_screen();
                if ( is_object( $currentScreen ) && $currentScreen->id == 'edit-lgx_counter' && $currentScreen->post_type == 'lgx_counter' ) {
                    $query->set( 'post_type', 'lgx_counter' );
                    $query->set( 'orderby', 'menu_order' );
                    $query->set( 'order' , 'ASC' );
                }
            }
        }
    }



     /**
     *  Save post for re ordering
     * @since    2.3.0
     */

    public function save_post_reorder_for_lgx_counter() {
        global $wpdb;
        $result = array(
            'type' => 'error',
            'message' => 'Action required.',
        );

        $result_json = json_encode( $result );

        if ( ! wp_verify_nonce( $_REQUEST['nonce'], "save_lgx_counter_nonce")) {
            $result['type'] = 'error';
            $result['message'] = 'WP nonce verification failed.';
        }

        try {
            parse_str( stripslashes_deep( $_POST['post_id_serialize'] ), $post_data );
            //$wpdb->queries( 'START TRANSACTION' );

            if ( ! is_array( $post_data ) || count( $post_data ) < 1 ) {
                $result['message'] = 'Available data not found.';
            } else {
                foreach ( $post_data['post'] as $menu_order => $post_id ) {
                    $wpdb->update( $wpdb->posts, array( 'menu_order' => (int)$menu_order ), array( 'ID' => (int)$post_id ) );
                }
            }

            //$wpdb->queries( 'COMMIT' );
            $result['type'] = 'success';
            $result['message'] = 'Reorder has been successful';
        } catch (Exception $exception) {
            //$wpdb->queries( 'ROLLBACK' );
            $result['message'] = $exception->getMessage();
        }

        $result_json = json_encode( $result );
        echo $result_json;
        wp_die();
    }

    



    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Counter_Up_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Counter_Up_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-counter-up-admin.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name . '-admin-icon', plugin_dir_url( __FILE__ ) . 'css/lgx-icon.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . '-admin-reset', plugin_dir_url( __FILE__ ) . 'css/lgx-admin-reset.min.css', array(), $this->version, 'all' );

        $currentScreen = get_current_screen();

        if( ( $currentScreen->post_type == 'lgx_counter' ) || ( $currentScreen->post_type == 'lgx_wcu_generator' ) ) {

            wp_enqueue_style( $this->plugin_name . '-alertify', plugin_dir_url( __FILE__ ) . 'css/alertify.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name . '-admin-counter', plugin_dir_url( __FILE__ ) . 'css/wp-counter-up-admin.min.css', array( 'wp-color-picker' ), $this->version, 'all' );

        }
    
    
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Counter_Up_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Counter_Up_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-counter-up-admin.js', array( 'jquery' ), $this->version, false );

        $currentScreen = get_current_screen();
        /*   echo '<pre>';
           print_r($currentScreen);
           echo '</pre>';*/
        if( ( $currentScreen->post_type == 'lgx_counter' ) || ( $currentScreen->post_type == 'lgx_wcu_generator' ) ) {

            $translation_array = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'check_nonce' => wp_create_nonce('save_lgx_counter_nonce'),
            );


            wp_register_script($this->plugin_name . '-alertify', plugin_dir_url( __FILE__ ) . 'js/alertify.min.js', array(), $this->version, true );
            wp_register_script($this->plugin_name . '-wp-color-picker-alpha' , plugin_dir_url( __FILE__ ) . 'js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), $this->version, true );
            wp_register_script($this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/wp-counter-up-admin.js', array( 'jquery', 'jquery-ui-sortable', $this->plugin_name . '-wp-color-picker-alpha', $this->plugin_name . '-alertify' ), $this->version, true );

            wp_localize_script($this->plugin_name . '-admin', 'wpnpaddon', $translation_array);

            wp_enqueue_script( $this->plugin_name . '-admin' );

            if ( ! did_action( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }


        }


    }

    

    /**
     * Register Custom Post Type
     *
     * @since    1.0.0
     */
    public function register_post_type_for_lgx_counter() {

        $labels_post = array(
            'name'               => _x( 'Counter Up', 'Counter Up', $this->plugin_name ),
            'singular_name'      => _x( 'Counter Up', 'Counter Up', $this->plugin_name ),
            'menu_name'          => __( 'Counter Up', $this->plugin_name ),
            'parent_item_colon'  => __( 'Parent Item:', $this->plugin_name ),
            'all_items'          => __( 'All Item', $this->plugin_name ),
            'view_item'          => __( 'View Item', $this->plugin_name ),
            'add_new_item'       => __( 'Add New Item', $this->plugin_name ),
            'add_new'            => __( 'Add Item', $this->plugin_name ),
            'edit_item'          => __( 'Edit Item', $this->plugin_name ),
            'update_item'        => __( 'Update Item', $this->plugin_name ),
            'search_items'       => __( 'Search Item', $this->plugin_name ),
            'not_found'          => __( 'Not found', $this->plugin_name ),
            'not_found_in_trash' => __( 'Not found in Trash', $this->plugin_name ),
        );

        $args_post   = array(
            'labels'              => $labels_post,
            'supports'            => array( 'title','thumbnail' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,// set false from v2.0.0
            'menu_position'       => 80, //
            'menu_icon'				=> 'dashicons-awards',
            'capability_type'     => 'post',
        );
        register_post_type( 'lgx_counter', $args_post );


        // Register Taxonomy
        $cat_args = array(
            'hierarchical'   => true,
            'label'          => __('Categories', $this->plugin_name),
            'show_ui'        => true,
            'query_var'      => true,
            'show_admin_column' => true,
            'singular_label' => __('Counter Category', $this->plugin_name),
        );


        register_taxonomy('lgxcountercat', array('lgx_counter'), $cat_args);

    }

    /**
     * Add metabox for custom post type
     *
     * @since    1.0.0
     */
    public function adding_meta_boxes_for_lgx_counter() {

        // meta box
        add_meta_box(
            'metabox_milestone', __( 'Counter Information', $this->plugin_name ), array(
            $this,
            'meta_fields_display_for_lgx_counter'
        ), 'lgx_counter', 'normal', 'high'
        );
    }


    /**
     * Render Metabox under counter
     *
     *  meta field
     *
     * @param $post
     *
     * @since 1.0
     *
     */

    public function meta_fields_display_for_lgx_counter( $post ) {

        require_once plugin_dir_path( __FILE__ ) . 'partials/meta_fields_display_for_post_lgx_counter.php';

    }


    /**
     * Determines whether or not the current user has the ability to save meta data associated with this post.
     *
     * Save portfoliopro Meta Field / Old : save_post_metabox_lgx_milestone
     *
     * @param        int $post_id //The ID of the post being save
     * @param         bool //Whether or not the user has the ability to save this post.
     */
    public function save_post_metadata_of_lgx_counter( $post_id, $post ) {

        $post_type = 'lgx_counter';

        // If this isn't a 'book' post, don't update it.
        if ( $post_type != $post->post_type ) {
            return;
        }

        
   

        if ( ! empty( $_POST['metaboxlgxmilestone'] ) ) {

            $postData = $_POST['metaboxlgxmilestone'];

            $saveableData = array();

         

            if ( $this->user_can_save_for_lgx_counter_meta( $post_id, 'metaboxlgxmilestone', $postData['nonce'] ) ) {

                $saveableData['counter_number']        = sanitize_text_field( $postData['counter_number'] );
                $saveableData['counter_desc']          = sanitize_textarea_field( $postData['counter_desc'] );

                update_post_meta( $post_id, '_lgxmilestonemeta', $saveableData );
            }
        }
    }// End  Meta Save





    /**
     * Determines whether or not the current user has the ability to save meta data associated with this post.
     *
     * user_can_save
     *
     * @param        int $post_id // The ID of the post being save
     * @param        bool /Whether or not the user has the ability to save this post.
     *
     * @since 1.0
     */
    public function user_can_save_for_lgx_counter_meta( $post_id, $action, $nonce ) {

        $is_autosave    = wp_is_post_autosave( $post_id );
        $is_revision    = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $nonce ) && wp_verify_nonce( $nonce, $action ) );

        // Return true if the user is able to save; otherwise, false.
        return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;

    }


    

    /**
     * For checking the pro version of plugin is activated or not
     * @param        string $plugin // slug of free version
     * @param        string $network_activation // network activation
     */

    public function pro_version_activation_checking_admin_init($plugin, $network_activation) {
        $plugin_pro = 'wp-counter-up-pro/wp-counter-up-pro.php';
        set_transient( 'lgx_counter_plugin_clicked', $plugin );

        if ( is_plugin_active( $plugin_pro ) ) {
            set_transient( 'lgx_counter_pro_active', true );
        }

    }

    public function pro_version_activation_checking_notice_warning() {
        $plugin_base = LGX_WCU_PLUGIN_BASE;
        $plugin_free = 'wp-counter-up/wp-counter-up.php';
        $plugin_pro = 'wp-counter-up-pro/wp-counter-up-pro.php';
        $lswp_pro_active = get_transient( 'lgx_counter_pro_active' );
        $lswp_plugin_clicked = get_transient( 'lgx_counter_plugin_clicked' );
        delete_transient( 'lgx_counter_pro_active' );
        delete_transient( 'lgx_counter_plugin_clicked' );

        if ( true == $lswp_pro_active && $lswp_plugin_clicked == $plugin_pro ) {
            deactivate_plugins( $plugin_free );
            remove_filter('plugin_action_links_' . $plugin_base, array( $this, 'add_links_admin_plugin_page_title' ) );
        } elseif ( true == $lswp_pro_active && $lswp_plugin_clicked == $plugin_free ) {
            deactivate_plugins( $plugin_free );
            remove_filter('plugin_action_links_' . $plugin_base, array( $this, 'add_links_admin_plugin_page_title' ) );

            unset( $_GET['activate'] );
            $class = 'notice notice-warning is-dismissible';
            $message = __( 'Counter Up Pro version already activated. For more please contact our support at <a href="https://logichunt.com/support/" target="_blank">LogicHunt.com.</a>', $this->plugin_name );

            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
        }


    }

    
    /*************************************************************************
     *
     *
     *
     *  Newly Added: 2022 : Counter Up Shortcode Generator 
     *
     *
     *
     * **************************************************************************/


    /**
     * Register post type for shortcode Counter Generator
     *
     *
     */
    public function register_post_type_for_lgx_counter_generator() {
    

        $labels = array(
            'name'               => _x( 'All Counter Showcase', 'Counter Showcase', $this->plugin_name ),
            'singular_name'      => _x( 'Counter Showcase', 'Showcase Items', $this->plugin_name ),
            'menu_name'          => __( 'Shortcode Generator', $this->plugin_name ),
            'view_item'          => __( 'View Items', $this->plugin_name ),
            'add_new_item'       => __( 'Add New Counter Showcase', $this->plugin_name ),
            'add_new'            => __( 'Add New Counter Showcase', $this->plugin_name ),
            'edit_item'          => __( 'Edit Item', $this->plugin_name ),
            'update_item'        => __( 'Update Item', $this->plugin_name ),
            'search_items'       => __( 'Search In Item', $this->plugin_name ),
            'not_found'          => __( 'No Showcase found', $this->plugin_name ),
            'not_found_in_trash' => __( 'No Showcase found in trash', $this->plugin_name )
        );

        $args   = array(
            'label'               => __( 'Counter Showcase Shortcode', $this->plugin_name ),
            'description'         => __( 'Generate Shortcode for Counter Showcase', $this->plugin_name ),
            'labels'              => $labels,
            'public'             => false,
            'show_ui'           => true,
             'show_in_menu'    => 'edit.php?post_type=lgx_counter',
            'hierarchical'    => false,
            'query_var'       => false,
            'supports'        => array( 'title' ),
            'capability_type' => 'post',
        );

        register_post_type( 'lgx_wcu_generator', $args);
    }



    /**
     * Add meta box for custom post type
     *
     * @since    2.0.0
     */
    public function adding_meta_boxes_for_lgx_counter_generator() {
        add_meta_box(
            'lgx_counter_generator_meta_box_panel',
            __( 'Logo Slider Shortcode Meta Field Panel', $this->plugin_name),
            array(
                $this,
                'meta_fields_display_for_lgx_wcu_generator' //Pattern --> meta_box_panel_display_for_{post_type}
            ),
            'lgx_wcu_generator',
            'normal',
            'high'
        );
    }



    /**
     * Render Meta Box under logosliderwp
     *
     * logosliderwp meta field
     *
     * @param $post
     *
     * @since 1.0
     *
     */
    public function meta_fields_display_for_lgx_wcu_generator( $post ) {

        require_once plugin_dir_path( __FILE__ ) . 'partials/shortcode_meta_display/meta_fields_display_for_post_lgx_counter_generator.php';

    }


    /**
     * Determines whether or not the current user has the ability to save meta data associated with this post.
     *
     * Save lgx_lsp_shortcodes Meta Field
     *
     * @param        int $post_id //The ID of the post being save
     * @param         bool //Whether or not the user has the ability to save this post.
     */
    public function save_post_metadata_of_lgx_counter_generator( $post_id, $post ) {


        $post_type = 'lgx_wcu_generator';

        // If this isn't a 'book' post, don't update it.
        if ( $post_type != $post->post_type ) {
            return;
        }

        if ( ! empty( $_POST['post_meta_lgx_counter_generator'] ) ) {

            $postData = $_POST['post_meta_lgx_counter_generator'];

                 //echo '<pre>';  print_r($postData); echo '</pre>'; wp_die();

            $savable_Data = array();


            if ( $this->user_can_save_for_lgx_counter_meta( $post_id, 'post_meta_lgx_counter_generator', $postData['nonce'] ) ) {

                $savable_Data['lgx_counter_showcase_type']      = sanitize_text_field( $postData['lgx_counter_showcase_type'] );

                // Basic Settings : ok
                $savable_Data['lgx_item_icon_en']              = (( isset($postData['lgx_item_icon_en'])) ? 'yes' : 'no');
                $savable_Data['lgx_item_title_en']              = (( isset($postData['lgx_item_title_en'])) ? 'yes' : 'no');
                $savable_Data['lgx_item_desc_en']              = (( isset($postData['lgx_item_desc_en'])) ? 'yes' : 'no');
                $savable_Data['lgx_counter_duration']           = (( isset($postData['lgx_counter_duration'])) ? sanitize_text_field( $postData['lgx_counter_duration']) : 2000);
                $savable_Data['lgx_counter_delay']              = (( isset($postData['lgx_counter_delay'])) ? sanitize_text_field( $postData['lgx_counter_delay']) : 16);
          
                     
                $savable_Data['lgx_item_single_height']           = (( isset($postData['lgx_item_single_height'])) ? sanitize_text_field( $postData['lgx_item_single_height']) : 'auto');
                $savable_Data['lgx_item_single_property_height']   = (( isset($postData['lgx_item_single_property_height'])) ? sanitize_text_field( $postData['lgx_item_single_property_height']) : 'max-height');
               
                
                $savable_Data['lgx_item_icon_height']           = (( isset($postData['lgx_item_icon_height'])) ? sanitize_text_field( $postData['lgx_item_icon_height']) : 'auto');
                $savable_Data['lgx_item_icon_property_height']   = (( isset($postData['lgx_item_icon_property_height'])) ? sanitize_text_field( $postData['lgx_item_icon_property_height']) : 'max-height');
               
                $savable_Data['lgx_item_icon_width']            = (( isset($postData['lgx_item_icon_width'])) ? sanitize_text_field( $postData['lgx_item_icon_width'])  : '100%');
                $savable_Data['lgx_item_icon_property_width']   = (( isset($postData['lgx_item_icon_property_width'])) ? sanitize_text_field( $postData['lgx_item_icon_property_width'])  : 'max-width');
               
                $savable_Data['lgx_item_content_order']         = (( isset($postData['lgx_item_content_order'])) ? sanitize_text_field( $postData['lgx_item_content_order'])  : 'i_n_t_d');
                $savable_Data['lgx_item_info_align']            = (( isset($postData['lgx_item_info_align'])) ? sanitize_text_field( $postData['lgx_item_info_align'])  : 'center');
                
                $savable_Data['lgx_from_category']              = (( isset($postData['lgx_from_category'])) ? sanitize_text_field( $postData['lgx_from_category']) : 'all');
                $savable_Data['lgx_item_limit']                 = (( isset($postData['lgx_item_limit'])) ? sanitize_text_field( $postData['lgx_item_limit']) : 0); 
                $savable_Data['lgx_item_sort_order']            = (( isset($postData['lgx_item_sort_order'])) ? sanitize_text_field( $postData['lgx_item_sort_order']) : 'ASC');
                $savable_Data['lgx_item_sort_order_by']         = (( isset($postData['lgx_item_sort_order_by'])) ? sanitize_text_field( $postData['lgx_item_sort_order_by']) :'menu_order');
                $savable_Data['lgx_preloader_en']               = (( isset($postData['lgx_preloader_en'])) ? 'yes' : 'no');
                $savable_Data['lgx_preloader_bg_color']         = (( isset($postData['lgx_preloader_bg_color'])) ? sanitize_text_field( $postData['lgx_preloader_bg_color'])  : '#ffffff');
                $savable_Data['lgx_preloader_icon']             = (( isset($postData['lgx_preloader_icon'])) ? sanitize_text_field( $postData['lgx_preloader_icon'])  : '');
            
                //Grid : OK
                $savable_Data['lgx_grid_column_gap']            = (( isset($postData['lgx_grid_column_gap'])) ? sanitize_text_field( $postData['lgx_grid_column_gap'] ) : '15px');
                $savable_Data['lgx_grid_row_gap']               = (( isset($postData['lgx_grid_row_gap'])) ? sanitize_text_field( $postData['lgx_grid_row_gap'] ) : '15px');
           
                //Flex Box
                $savable_Data['lgx_flexbox_column_gap']            = (( isset($postData['lgx_flexbox_column_gap'])) ? sanitize_text_field( $postData['lgx_flexbox_column_gap'] ) : '15px');
                $savable_Data['lgx_flexbox_row_gap']            = (( isset($postData['lgx_flexbox_row_gap'])) ? sanitize_text_field( $postData['lgx_flexbox_row_gap'] ) : '15px');
                $savable_Data['lgx_flexbox_align_items']        = (( isset($postData['lgx_flexbox_align_items'])) ? sanitize_text_field( $postData['lgx_flexbox_align_items'] ) : 'flex-start');
                $savable_Data['lgx_flexbox_justify_content']    = (( isset($postData['lgx_flexbox_justify_content'])) ? sanitize_text_field( $postData['lgx_flexbox_justify_content'] ) : 'flex-start');
                $savable_Data['lgx_flexbox_wrap']               = (( isset($postData['lgx_flexbox_wrap'])) ? sanitize_text_field( $postData['lgx_flexbox_wrap'] ) : 'wrap');
                $savable_Data['lgx_flexbox_direction']               = (( isset($postData['lgx_flexbox_direction'])) ? sanitize_text_field( $postData['lgx_flexbox_direction'] ) : 'row');


            
                // Responsive Settings : ok
                $savable_Data['lgx_large_desktop_item']   =  (( isset($postData['lgx_large_desktop_item'])) ? sanitize_text_field( $postData['lgx_large_desktop_item'] ): 4);
                $savable_Data['lgx_desktop_item']         =  (( isset($postData['lgx_desktop_item'])) ? sanitize_text_field( $postData['lgx_desktop_item'] ) : 4);
                $savable_Data['lgx_tablet_item']          =  (( isset($postData['lgx_tablet_item'])) ? sanitize_text_field( $postData['lgx_tablet_item'] ) : 2);
                $savable_Data['lgx_mobile_item']          =  (( isset($postData['lgx_mobile_item'])) ? sanitize_text_field( $postData['lgx_mobile_item'] ) : 2);


                // Style Settings
                $savable_Data['lgx_item_hover_effect']           = (( isset($postData['lgx_item_hover_effect'])) ? sanitize_text_field( $postData['lgx_item_hover_effect'] ) : 'none');
                $savable_Data['lgx_item_floating']                  = (( isset($postData['lgx_item_floating'])) ? sanitize_text_field( $postData['lgx_item_floating'] ) : 'none');
                $savable_Data['lgx_item_hover_anim']             = (( isset($postData['lgx_item_hover_anim'])) ? sanitize_text_field( $postData['lgx_item_hover_anim'] ) : 'default');
               
                $savable_Data['lgx_item_value_color']             = (( isset($postData['lgx_item_value_color'])) ? sanitize_text_field( $postData['lgx_item_value_color'] ) : '#111111');;
                $savable_Data['lgx_item_value_font_size']         = (( isset($postData['lgx_item_value_font_size'])) ? sanitize_text_field( $postData['lgx_item_value_font_size'] ) : '16px');
                $savable_Data['lgx_item_value_font_weight']       = (( isset($postData['lgx_item_value_font_weight'])) ? sanitize_text_field( $postData['lgx_item_value_font_weight'] ) : '600');

                $savable_Data['lgx_item_top_margin_value']      =(( isset($postData['lgx_item_top_margin_value'])) ? sanitize_text_field( $postData['lgx_item_top_margin_value'] ) : '0px');
                $savable_Data['lgx_item_bottom_margin_value']   =(( isset($postData['lgx_item_bottom_margin_value'])) ? sanitize_text_field( $postData['lgx_item_bottom_margin_value'] ) : '0px');
               
                $savable_Data['lgx_value_width']                =(( isset($postData['lgx_value_width'])) ? sanitize_text_field( $postData['lgx_value_width'] ) : 'auto');
                $savable_Data['lgx_value_height']               =(( isset($postData['lgx_value_height'])) ? sanitize_text_field( $postData['lgx_value_height'] ) : 'auto');

                $savable_Data['lgx_value_border_color_en']        = ((isset($postData['lgx_value_border_color_en'])) ? 'yes' : 'no');

                $savable_Data['lgx_value_border_color']           = (( isset($postData['lgx_value_border_color'])) ? sanitize_text_field( $postData['lgx_value_border_color'] ) : '#F9f9f9');
                $savable_Data['lgx_value_border_color_hover']     = (( isset($postData['lgx_value_border_color_hover'])) ? sanitize_text_field( $postData['lgx_value_border_color_hover'] ) : '#F9f9f9');
                $savable_Data['lgx_value_border_width']           = (( isset($postData['lgx_value_border_width'])) ? sanitize_text_field( $postData['lgx_value_border_width'] ) : '1px');
                $savable_Data['lgx_value_border_radius']          = (( isset($postData['lgx_img_border_radius'])) ? sanitize_text_field( $postData['lgx_value_border_radius'] ) : '100px');


                 $savable_Data['lgx_item_title_color']             = (( isset($postData['lgx_item_title_color'])) ? sanitize_text_field( $postData['lgx_item_title_color'] ) : '#111111');;
                $savable_Data['lgx_item_title_font_size']           = (( isset($postData['lgx_item_title_font_size'])) ? sanitize_text_field( $postData['lgx_item_title_font_size'] ) : '18px');
                $savable_Data['lgx_item_title_font_weight']    = (( isset($postData['lgx_item_title_font_weight'])) ? sanitize_text_field( $postData['lgx_item_title_font_weight'] ) : '600');


                $savable_Data['lgx_item_desc_color']            = (( isset($postData['lgx_item_desc_color'])) ? sanitize_text_field( $postData['lgx_item_desc_color'] ) : '#555555');
                $savable_Data['lgx_item_desc_font_size']        = (( isset($postData['lgx_item_desc_font_size'])) ? sanitize_text_field( $postData['lgx_item_desc_font_size'] ) : '14px');
                $savable_Data['lgx_item_desc_font_weight']      = (( isset($postData['lgx_item_desc_font_weight'])) ? sanitize_text_field( $postData['lgx_item_desc_font_weight'] ) : '400');

                $savable_Data['lgx_img_border_color_en']        = ((isset($postData['lgx_img_border_color_en'])) ? 'yes' : 'no');

                $savable_Data['lgx_img_border_color']           = (( isset($postData['lgx_img_border_color'])) ? sanitize_text_field( $postData['lgx_img_border_color'] ) : '#FF5151');
                $savable_Data['lgx_img_border_color_hover']     = (( isset($postData['lgx_img_border_color_hover'])) ? sanitize_text_field( $postData['lgx_img_border_color_hover'] ) : '#FF9B6A');
                $savable_Data['lgx_img_border_width']           = (( isset($postData['lgx_img_border_width'])) ? sanitize_text_field( $postData['lgx_img_border_width'] ) : '1px');
                $savable_Data['lgx_img_border_radius']          = (( isset($postData['lgx_img_border_radius'])) ? sanitize_text_field( $postData['lgx_img_border_radius'] ) : '4px');

                $savable_Data['lgx_border_color_en']            = ((isset($postData['lgx_border_color_en'])) ? 'yes' : 'no');

                $savable_Data['lgx_item_border_color']          = (( isset($postData['lgx_item_border_color'])) ? sanitize_text_field( $postData['lgx_item_border_color'] ) : '#161E54');
                $savable_Data['lgx_item_border_color_hover']    = (( isset($postData['lgx_item_border_color_hover'])) ? sanitize_text_field( $postData['lgx_item_border_color_hover'] ) : '#161E54');
                $savable_Data['lgx_item_border_width']          = (( isset($postData['lgx_item_border_width'])) ? sanitize_text_field( $postData['lgx_item_border_width'] ) : '4px');
                $savable_Data['lgx_item_border_radius']         = (( isset($postData['lgx_item_border_radius'])) ? sanitize_text_field( $postData['lgx_item_border_radius'] ) : '100');

                $savable_Data['lgx_icon_bg_color_en']           = ((isset($postData['lgx_icon_bg_color_en'])) ? 'yes' : 'no');

                $savable_Data['lgx_icon_bg_color']              = (( isset($postData['lgx_icon_bg_color'])) ? sanitize_text_field( $postData['lgx_icon_bg_color'] ) : '#f1f1f1');
                $savable_Data['lgx_icon_bg_color_hover']        = (( isset($postData['lgx_icon_bg_color_hover'])) ? sanitize_text_field( $postData['lgx_icon_bg_color_hover'] ) : '#f1f1f1');

                $savable_Data['lgx_item_bg_color_en']           = ((isset($postData['lgx_item_bg_color_en'])) ? 'yes' : 'no');

                $savable_Data['lgx_item_bg_color']              = (( isset($postData['lgx_item_bg_color'])) ? sanitize_text_field( $postData['lgx_item_bg_color'] ) : '#f1f1f1');
                $savable_Data['lgx_item_bg_color_hover']        = (( isset($postData['lgx_item_bg_color_hover'])) ? sanitize_text_field( $postData['lgx_item_bg_color_hover'] ) : '#f1f1f1');

                $savable_Data['lgx_item_padding']               = (( isset($postData['lgx_item_padding'])) ? sanitize_text_field( $postData['lgx_item_padding'] ) : '0px');
                $savable_Data['lgx_item_margin']                =(( isset($postData['lgx_item_margin'])) ? sanitize_text_field( $postData['lgx_item_margin'] ) : '0px');
             
                $savable_Data['lgx_icon_padding']                =(( isset($postData['lgx_icon_padding'])) ? sanitize_text_field( $postData['lgx_icon_padding'] ) : '0px');

                $savable_Data['lgx_item_top_margin_title']      =(( isset($postData['lgx_item_top_margin_title'])) ? sanitize_text_field( $postData['lgx_item_top_margin_title'] ) : '5px');
                $savable_Data['lgx_item_bottom_margin_title']   =(( isset($postData['lgx_item_bottom_margin_title'])) ? sanitize_text_field( $postData['lgx_item_bottom_margin_title'] ) : '5px');
               
                $savable_Data['lgx_item_top_margin_desc']       =(( isset($postData['lgx_item_top_margin_desc'])) ? sanitize_text_field( $postData['lgx_item_top_margin_desc'] ) : '0px');
                $savable_Data['lgx_item_bottom_margin_desc']    =(( isset($postData['lgx_item_bottom_margin_desc'])) ? sanitize_text_field( $postData['lgx_item_bottom_margin_desc'] ) : '0px');


                //Section Settings  : ok
                $savable_Data['lgx_section_bg_img_en']          = ((isset($postData['lgx_section_bg_img_en'])) ? 'yes' : 'no');
                $savable_Data['lgx_section_bg_color_en']        = ((isset($postData['lgx_section_bg_color_en'])) ? 'yes' : 'no');
                $savable_Data['lgx_section_width']              = (( isset($postData['lgx_section_width'])) ? sanitize_text_field( $postData['lgx_section_width'] ) : '100%');
                $savable_Data['lgx_section_container']          = (( isset($postData['lgx_section_container'])) ? sanitize_text_field( $postData['lgx_section_container'] ) : 'container-fluid');
                $savable_Data['lgx_section_bg_img']             = (( isset($postData['lgx_section_bg_img'])) ? sanitize_text_field( $postData['lgx_section_bg_img'] ) : '');
                $savable_Data['lgx_section_bg_img_attachment']  = (( isset($postData['lgx_section_bg_img_attachment'])) ? sanitize_text_field( $postData['lgx_section_bg_img_attachment'] ) : 'initial');
                $savable_Data['lgx_section_bg_img_size']        = (( isset($postData['lgx_section_bg_img_size'])) ? sanitize_text_field( $postData['lgx_section_bg_img_size'] ) : 'cover');
                $savable_Data['lgx_section_bg_color']           = (( isset($postData['lgx_section_bg_color'])) ? sanitize_text_field( $postData['lgx_section_bg_color'] ) : '#b56969');
                $savable_Data['lgx_section_top_margin']         = (( isset($postData['lgx_section_top_margin'])) ? sanitize_text_field( $postData['lgx_section_top_margin'] ) : '0px');
                $savable_Data['lgx_section_bottom_margin']      = (( isset($postData['lgx_section_bottom_margin'])) ? sanitize_text_field( $postData['lgx_section_bottom_margin'] ) : '0px');
                $savable_Data['lgx_section_top_padding']        = (( isset($postData['lgx_section_top_padding'])) ? sanitize_text_field( $postData['lgx_section_top_padding'] ) : '0px');
                $savable_Data['lgx_section_bottom_padding']     = (( isset($postData['lgx_section_bottom_padding'])) ? sanitize_text_field( $postData['lgx_section_bottom_padding'] ) : '0px');
                $savable_Data['lgx_section_left_padding']        = (( isset($postData['lgx_section_left_padding'])) ? sanitize_text_field( $postData['lgx_section_left_padding'] ) : '0px');
                $savable_Data['lgx_section_right_padding']     = (( isset($postData['lgx_section_right_padding'])) ? sanitize_text_field( $postData['lgx_section_right_padding'] ) : '0px');
            



                //Header Settings : Ok
                $savable_Data['lgx_header_en']                             = ((isset($postData['lgx_header_en'])) ? 'yes' : 'no');
                $savable_Data['lgx_header_align']                         = (( isset($postData['lgx_header_align'])) ? sanitize_text_field( $postData['lgx_header_align'] ): 'center');
                $savable_Data['lgx_header_title']                         = (( isset($postData['lgx_header_title'])) ? sanitize_text_field( $postData['lgx_header_title'] ): '');
                $savable_Data['lgx_header_title_font_size']               = (( isset($postData['lgx_header_title_font_size'])) ? sanitize_text_field( $postData['lgx_header_title_font_size'] ): '42px');
                $savable_Data['lgx_header_title_color']                   = (( isset($postData['lgx_header_title_color'])) ? sanitize_text_field( $postData['lgx_header_title_color'] ): '#2e2841cc');
                $savable_Data['lgx_header_title_font_weight']             = (( isset($postData['lgx_header_title_font_weight'])) ? sanitize_text_field( $postData['lgx_header_title_font_weight'] ): '500');
                $savable_Data['lgx_header_title_bottom_margin']           = (( isset($postData['lgx_header_title_bottom_margin'])) ? sanitize_text_field( $postData['lgx_header_title_bottom_margin'] ): '10px');
                $savable_Data['lgx_header_subtitle']                      = (( isset($postData['lgx_header_subtitle'])) ? sanitize_text_field( $postData['lgx_header_subtitle'] ): '');
                $savable_Data['lgx_header_subtitle_font_size']            = (( isset($postData['lgx_header_subtitle_font_size'])) ? sanitize_text_field( $postData['lgx_header_subtitle_font_size'] ): '16px');
                $savable_Data['lgx_header_subtitle_color']                = (( isset($postData['lgx_header_subtitle_color'])) ? sanitize_text_field( $postData['lgx_header_subtitle_color'] ): '#888888');
                $savable_Data['lgx_header_subtitle_font_weight']          = (( isset($postData['lgx_header_subtitle_font_weight'])) ? sanitize_text_field( $postData['lgx_header_subtitle_font_weight'] ): '400');
                $savable_Data['lgx_header_subtitle_bottom_margin']        = (( isset($postData['lgx_header_subtitle_bottom_margin'])) ? sanitize_text_field( $postData['lgx_header_subtitle_bottom_margin'] ): '45px');

                
               //  echo '<pre>';  print_r($savable_Data); echo '</pre>'; wp_die();

                update_post_meta( $post_id, '_save_meta_lgx_counter_generator', $savable_Data );
            }
        }
    }// End  Meta Save


    /**
     * @param array $classes
     * @return array|mixed
     */


    public function add_meta_box_css_class_for_lgx_counter_generator($classes = array()) {

        $add_classes = array( 'lgx_logo_slider_meta_box_postbox', 'lgx_logo_slider_meta_box_postbox_free' );

        foreach ( $add_classes as $class ) {
            if ( ! in_array( $class, $classes ) ) {
                $classes[] = sanitize_html_class( $class );
            }
        }

        return $classes;
    }



    public function add_new_column_head_for_lgx_counter_generator($default_columns) {
        unset( $default_columns['date'] );

        $default_columns['title']            = __( 'Title', 'lgx-logo-showcase-wp' );
        $default_columns['shortcode']        = __( 'Shortcode', 'lgx-logo-showcase-wp' );
        //   $default_columns['php_shortcode']    = __( 'Theme or Plugin Code', 'lgx-logo-showcase-wp' );
        $default_columns['date']             = __( 'Date', 'lgx-logo-showcase-wp' );

        return $default_columns;
    }

    public function define_admin_column_value_for_lgx_counter_generator($column, $post_id) {
        if(!empty($post_id)) {
            switch ($column) {
                case 'shortcode':
                    echo '<input type="text" class="lgx_logo_slider_list_copy_input"  readonly="readonly" value="[lgxcounterup id=&quot;' . $post_id . '&quot;]">';
                    // echo '<div>Click on shortcode to copy</div>';
                    break;

                case 'php_shortcode':
                    echo '<input type="text" class="lgx_logo_slider_list_copy_input" style="width: 360px; text-align: center;" readonly="readonly" value="<?php echo do_shortcode( \'[lgxcounterup id=&quot;' . $post_id . '&quot;]\' ); ?>">';
                    // echo '<div>Click on theme or plugin code to copy</div>';
                    break;

                default:
                    break;
            }
        }
    }

 
}



