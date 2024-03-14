<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    SCFW_Size_Chart_For_Woocommerce
 * @subpackage SCFW_Size_Chart_For_Woocommerce/admin
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SCFW_Size_Chart_For_Woocommerce
 * @subpackage SCFW_Size_Chart_For_Woocommerce/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class SCFW_Size_Chart_For_Woocommerce_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * The post_type_name of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $post_type_name The current post_type_name of this plugin.
     */
    private  $post_type_name ;
    /**
     * The size chart settings.
     * @var
     */
    public  $size_chart_settings ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param string $post_type_name The post type name of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version, $post_type_name )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->post_type_name = $post_type_name;
    }
    
    /**
     * Get the plugin name.
     * @return string
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * Plugin dash name.
     * @return string
     */
    public function get_plugin_dash_name()
    {
        return sanitize_title_with_dashes( $this->get_plugin_name() );
    }
    
    /**
     * Get the plugin version.
     * @return string
     */
    public function get_plugin_version()
    {
        return $this->version;
    }
    
    /**
     * Get the plugin version.
     * @return string
     */
    public function get_plugin_post_type_name()
    {
        return $this->post_type_name;
    }
    
    /**
     * Register the Style and JavaScript for the admin area.
     * Enqueue style and JavaScript for admin area.
     * Add inline Style and JavaScript for selected pages.
     *
     * @param string $hook_suffix the current page hook suffix.
     *
     * @since    1.0.0
     */
    public function scfw_enqueue_styles_scripts_callback( $hook_suffix )
    {
        global  $post ;
        /**
         * Register, Enqueue and Add inline styles.
         */
        $screen = get_current_screen();
        
        if ( false !== strpos( $hook_suffix, '_page_size-chart' ) || false !== strpos( $hook_suffix, '_page_scfw' ) || 'size-chart' === $screen->post_type || 'product' === $screen->post_type ) {
            // Register styles.
            wp_register_style(
                $this->get_plugin_dash_name() . '-jquery-editable-style',
                plugin_dir_url( __FILE__ ) . 'css/jquery.edittable.css',
                array(),
                $this->get_plugin_version(),
                'all'
            );
            wp_register_style(
                $this->get_plugin_dash_name() . '-font-awesome-style',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->get_plugin_version(),
                'all'
            );
            wp_register_style(
                $this->get_plugin_dash_name() . '-select2-style',
                plugin_dir_url( __FILE__ ) . 'css/select2.css',
                array(),
                $this->get_plugin_version(),
                'all'
            );
            wp_register_style(
                $this->get_plugin_dash_name(),
                plugin_dir_url( __FILE__ ) . 'css/size-chart-for-woocommerce-admin.css',
                array(),
                $this->get_plugin_version(),
                'all'
            );
            wp_register_style(
                $this->get_plugin_dash_name() . '-main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                $this->get_plugin_version(),
                'all'
            );
            if ( 'product' !== $screen->post_type ) {
                wp_register_style(
                    $this->get_plugin_dash_name() . '-plugin-new-style',
                    plugin_dir_url( __FILE__ ) . 'css/plugin-new-style.css',
                    array(),
                    $this->get_plugin_version(),
                    'all'
                );
            }
            wp_register_style(
                $this->get_plugin_dash_name() . '-plugin-setup-wizard',
                plugin_dir_url( __FILE__ ) . 'css/plugin-setup-wizard.css',
                array(),
                $this->get_plugin_version(),
                'all'
            );
            
            if ( !(scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code()) ) {
                wp_register_style(
                    $this->get_plugin_dash_name() . '-upgrade-dashboard-style',
                    plugin_dir_url( __FILE__ ) . 'css/upgrade-dashboard.css',
                    array(),
                    $this->get_plugin_version(),
                    'all'
                );
                wp_enqueue_style( $this->get_plugin_dash_name() . '-upgrade-dashboard-style' );
            }
            
            // Enqueue styles.
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'wp-jquery-ui-dialog' );
            wp_enqueue_style( $this->get_plugin_dash_name() . '-jquery-editable-style' );
            wp_enqueue_style( $this->get_plugin_dash_name() . '-font-awesome-style' );
            wp_enqueue_style( $this->get_plugin_dash_name() . '-select2-style' );
            wp_enqueue_style( $this->get_plugin_dash_name() . '-main-style' );
            if ( 'product' !== $screen->post_type ) {
                wp_enqueue_style( $this->get_plugin_dash_name() . '-plugin-new-style' );
            }
            wp_enqueue_style( $this->get_plugin_dash_name() . '-plugin-setup-wizard' );
            wp_enqueue_style( $this->get_plugin_dash_name() );
            // Add inline style.
            wp_add_inline_style( $this->get_plugin_dash_name(), 'body{}' );
            
            if ( isset( $post ) ) {
                $post_status = get_post_meta( $post->ID, 'post_status', true );
                if ( $this->get_plugin_post_type_name() === $post->post_type && isset( $post_status ) && 'default' === $post_status ) {
                    // Add inline style.
                    wp_add_inline_style( 'wp-jquery-ui-dialog', "#delete-action, #duplicate-action, #misc-publishing-actions, #minor-publishing-actions{display:none;}" );
                }
            }
            
            /**
             * Register, Enqueue and Add inline JavaScripts.
             */
            // Register scripts.
            wp_register_script(
                $this->get_plugin_dash_name() . '-jquery-editable-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.edittable.js',
                array( 'jquery' ),
                $this->get_plugin_version(),
                false
            );
            wp_register_script(
                $this->get_plugin_dash_name(),
                plugin_dir_url( __FILE__ ) . 'js/size-chart-for-woocommerce-admin.js',
                array( 'jquery', 'wp-color-picker', 'selectWoo' ),
                $this->get_plugin_version(),
                false
            );
            $chart_table_style = array(
                'default-style' => array(
                'header_bg_color'   => '#000',
                'even_row_bg_color' => '#ebe9eb',
                'odd_row_bg_color'  => '#fff',
                'text_color'        => '#fff',
                'even_text_color'   => '#000',
                'odd_text_color'    => '#000',
                'border_color'      => '#d1d1d1',
                'border_hb_style'   => 'solid',
                'border_hw'         => 1,
                'border_vb_style'   => 'solid',
                'border_vw'         => 1,
            ),
                'minimalistic'  => array(
                'header_bg_color'   => '#fff',
                'even_row_bg_color' => '#fff',
                'odd_row_bg_color'  => '#fff',
                'text_color'        => '#000',
                'even_text_color'   => '#000',
                'odd_text_color'    => '#000',
                'border_color'      => '#d1d1d1',
                'border_hb_style'   => 'solid',
                'border_hw'         => 1,
                'border_vb_style'   => 'solid',
                'border_vw'         => 1,
            ),
                'classic'       => array(
                'header_bg_color'   => '#000',
                'even_row_bg_color' => '#fff',
                'odd_row_bg_color'  => '#fff',
                'text_color'        => '#fff',
                'even_text_color'   => '#000',
                'odd_text_color'    => '#000',
                'border_color'      => '#d1d1d1',
                'border_hb_style'   => 'solid',
                'border_hw'         => 1,
                'border_vb_style'   => 'solid',
                'border_vw'         => 1,
            ),
                'modern'        => array(
                'header_bg_color'   => '#ebe9eb',
                'even_row_bg_color' => '#ebe9eb',
                'odd_row_bg_color'  => '#fff',
                'text_color'        => '#000',
                'even_text_color'   => '#000',
                'odd_text_color'    => '#000',
                'border_color'      => 'transparent',
                'border_hb_style'   => 'none',
                'border_hw'         => 0,
                'border_vb_style'   => 'none',
                'border_vw'         => 0,
            ),
            );
            if ( scfw_fs()->is__premium_only() & scfw_fs()->can_use_premium_code() ) {
                $chart_table_style['custom-style'] = array(
                    'header_bg_color'   => scfw_size_chart_get_table_head_color(),
                    'even_row_bg_color' => scfw_size_chart_get_table_row_even_color(),
                    'odd_row_bg_color'  => scfw_size_chart_get_table_row_odd_color(),
                    'text_color'        => scfw_size_chart_get_table_head_font_color(),
                    'even_text_color'   => '#000',
                    'odd_text_color'    => '#000',
                    'border_color'      => '#d1d1d1',
                    'border_hb_style'   => 'solid',
                    'border_hw'         => 1,
                    'border_vb_style'   => 'solid',
                    'border_vw'         => 1,
                );
            }
            // Localize script.
            $size_chart_localize_script_args = array(
                'size_chart_admin_url'             => admin_url( 'admin-ajax.php' ),
                'size_chart_nonce'                 => wp_create_nonce( 'size_chart_for_wooocmmerce_nonoce' ),
                'size_chart_post_title_required'   => __( 'Title is required.', 'size-chart-for-woocommerce' ),
                'size_chart_post_type_name'        => 'size-chart',
                'size_chart_current_screen_id'     => ( isset( $screen->id ) ? $screen->id : '' ),
                'size_chart_get_started_page_slug' => 'size-chart-get-started',
                'size_chart_plugin_dash_name'      => $this->get_plugin_dash_name(),
                'size_chart_plugin_name'           => $this->get_plugin_name(),
                'size_chart_no_product_assigned'   => __( 'No product assigned', 'size-chart-for-woocommerce' ),
                'size_chart_chart_table_style'     => $chart_table_style,
                'size_chart_field_validation'      => __( 'Invalid character "Quotation mark ( " ) or Backslash ( \\ )" detected. Please remove and use "INCH" or "IN" instead and try again.', 'size-chart-for-woocommerce' ),
                'dpb_api_url'                      => SCFW_STORE_URL,
                'setup_wizard_ajax_nonce'          => wp_create_nonce( 'wizard_ajax_nonce' ),
                'select_category'                  => __( 'Select a category', 'size-chart-for-woocommerce' ),
                'select_tag'                       => __( 'Select a tag', 'size-chart-for-woocommerce' ),
                'select_attribute'                 => __( 'Select an attribute', 'size-chart-for-woocommerce' ),
                'select_country'                   => __( 'Select a country', 'size-chart-for-woocommerce' ),
                'select_user_role'                 => __( 'Select a user role', 'size-chart-for-woocommerce' ),
                'remove_product_confirm'           => __( 'Are you sure you want to remove the product from the chart?', 'size-chart-for-woocommerce' ),
                'export_chart_confirm'             => __( 'Are you sure you want to export size chart data?', 'size-chart-for-woocommerce' ),
            );
            if ( scfw_fs()->is_plan( 'free', true ) ) {
                $size_chart_localize_script_args['size_chart_plugin_menu_url'] = 'edit.php?post_type=' . $this->get_plugin_post_type_name();
            }
            wp_localize_script( $this->get_plugin_dash_name(), 'sizeChartScriptObject', $size_chart_localize_script_args );
            // Enqueue scripts.
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script( 'jquery-blockui' );
            wp_enqueue_script( $this->get_plugin_dash_name() . '-jquery-editable-js' );
            wp_enqueue_script( $this->get_plugin_dash_name() );
            wp_enqueue_script(
                $this->get_plugin_dash_name() . '-select2_js',
                plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js?ver=4.0.3',
                array( 'jquery' ),
                '4.0.3',
                false
            );
            // Disable the publish button on size chart.
            
            if ( 'post.php' === $hook_suffix ) {
                $post_status = get_post_meta( $post->ID, 'post_status', true );
                if ( $this->get_plugin_post_type_name() === $post->post_type && isset( $post_status ) && 'default' === $post_status ) {
                    // Add inline script.
                    wp_add_inline_script( $this->get_plugin_dash_name(), "window.onload = function() {jQuery('#title').prop('disabled', true);};" );
                }
            }
        
        }
    
    }
    
    /**
     * Register a new post type called chart.
     *
     * @since    1.0.0
     */
    public function scfw_size_chart_register_post_type_chart_callback()
    {
        $labels = array(
            'name'               => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'singular_name'      => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'menu_name'          => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'name_admin_bar'     => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'add_new'            => __( 'Add New', 'size-chart-for-woocommerce' ),
            'add_new_item'       => __( 'Add New Size Charts', 'size-chart-for-woocommerce' ),
            'new_item'           => __( 'New Size Charts', 'size-chart-for-woocommerce' ),
            'edit_item'          => __( 'Edit Size Charts', 'size-chart-for-woocommerce' ),
            'view_item'          => __( 'View Size Charts', 'size-chart-for-woocommerce' ),
            'all_items'          => __( 'All Size Charts', 'size-chart-for-woocommerce' ),
            'search_items'       => __( 'Search', 'size-chart-for-woocommerce' ),
            'parent_item_colon'  => __( 'Parent Size Charts:', 'size-chart-for-woocommerce' ),
            'not_found'          => __( 'No chart found.', 'size-chart-for-woocommerce' ),
            'not_found_in_trash' => __( 'No charts found in Trash.', 'size-chart-for-woocommerce' ),
        );
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'size-chart-for-woocommerce' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => plugin_dir_url( __FILE__ ) . 'images/thedotstore-images/menu-icon.png',
            'supports'           => array( 'title', 'editor' ),
        );
        register_post_type( $this->get_plugin_post_type_name(), $args );
    }
    
    /**
     * This function call for welcome screen in size chart plugin and Default size chart create hook.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_welcome_screen_and_default_posts_callback()
    {
        /**
         * Remove Default Template.
         * Remove option for check default size chart.
         */
        $default_size_chart_option = get_option( 'default_size_chart' );
        
        if ( empty($default_size_chart_option) ) {
            $default_size_chart_posts = array(
                'tshirt-shirt'    => __( "Men's T-Shirts & Polo Shirts Size Chart", "size-chart-for-woocommerce" ),
                'womens-tshirt'   => __( "Women's T-shirt / Tops size chart", "size-chart-for-woocommerce" ),
                'mens-shirts'     => __( "Men's Shirts Size Chart", "size-chart-for-woocommerce" ),
                'womens-dress'    => __( "Women's Dress Size Chart ", "size-chart-for-woocommerce" ),
                'jeans-trouser'   => __( "Men's Jeans & Trousers Size Chart", "size-chart-for-woocommerce" ),
                'womens-jeans'    => __( "Women's Jeans And Jeggings Size Chart", "size-chart-for-woocommerce" ),
                'mens-waistcoats' => __( "Men's Waistcoats Size Chart", "size-chart-for-woocommerce" ),
                'women-cloth'     => __( "Women's Cloth size chart", "size-chart-for-woocommerce" ),
                'men-shoes'       => __( "Men's Shoes Size Chart", "size-chart-for-woocommerce" ),
                'women-shoes'     => __( "Women's Shoes Size Chart", "size-chart-for-woocommerce" ),
            );
            // Get current user to assign a post.
            $user_id = get_current_user_id();
            $default_size_chart_posts_ids = array();
            foreach ( $default_size_chart_posts as $default_post_size_chart_key => $default_post_size_chart_value ) {
                $size_chart_content_html = $this->scfw_size_chart_cloth_template_html_content( $default_post_size_chart_key );
                $size_chart_post_arg = array(
                    'post_author'  => $user_id,
                    'post_content' => $size_chart_content_html,
                    'post_type'    => $this->get_plugin_post_type_name(),
                    'post_status'  => 'publish',
                    'post_title'   => $default_post_size_chart_value,
                );
                $post_id = wp_insert_post( $size_chart_post_arg );
                $default_size_chart_posts_ids[] = $post_id;
                if ( 0 !== $post_id ) {
                    $this->scfw_size_chart_add_post_meta( $post_id, $default_post_size_chart_key );
                }
            }
            update_option( 'default_size_chart', 'default_size_chart_set' );
            scfw_size_chart_update_default_post_ids( $default_size_chart_posts_ids );
        }
        
        /**
         * Size chart welcome page.
         */
        // If no activation redirect.
        if ( !get_transient( '_welcome_screen_activation_redirect_size_chart' ) ) {
            return;
        }
        // Delete the redirect transient.
        delete_transient( '_welcome_screen_activation_redirect_size_chart' );
        // If activating from network, or bulk.
        // Sanitize user input.
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to size chart welcome page.
        wp_safe_redirect( admin_url( "admin.php?page=size-chart-get-started" ) );
        exit;
    }
    
    /**
     * Register admin menu and add size chart dashboard page for the plugin.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_welcome_page_screen_and_menu_callback()
    {
        global  $GLOBALS ;
        $size_chart_setting = get_option( 'size_chart_settings' );
        
        if ( !empty($size_chart_setting) ) {
            $setting = json_decode( $size_chart_setting );
            $user_arr = ( isset( $setting->user_roles ) ? $setting->user_roles : '' );
        } else {
            $user_arr = '';
        }
        
        $current_user = wp_get_current_user();
        $current_user_role = $current_user->roles;
        
        if ( isset( $user_arr ) && !empty($user_arr) ) {
            $matches = array_intersect( $current_user_role, $user_arr );
        } else {
            $matches = '';
        }
        
        
        if ( !empty($matches) ) {
            $cs_capability = 'manage_woocommerce';
        } else {
            $cs_capability = 'manage_options';
        }
        
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                __( 'DotStore Plugins', 'size-chart-for-woocommerce' ),
                __( 'DotStore Plugins', 'size-chart-for-woocommerce' ),
                'NULL',
                'dots_store',
                array( $this, 'scfw_size_chart_get_started_page' ),
                'dashicons-marker',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            $this->get_plugin_name(),
            $this->get_plugin_name(),
            $cs_capability,
            'size-chart-get-started',
            array( $this, 'scfw_size_chart_get_started_page' )
        );
        add_submenu_page(
            'dots_store',
            __( 'Introduction', 'size-chart-for-woocommerce' ),
            __( 'Introduction', 'size-chart-for-woocommerce' ),
            $cs_capability,
            'size-chart-information',
            array( $this, 'scfw_size_chart_information_page' )
        );
        add_submenu_page(
            'dots_store',
            __( 'Get Started', 'size-chart-for-woocommerce' ),
            __( 'Get Started', 'size-chart-for-woocommerce' ),
            $cs_capability,
            'size-chart-get-started-page',
            array( $this, 'scfw_size_chart_get_started_page' )
        );
        if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
            add_submenu_page(
                'dots_store',
                __( 'Import / Export', 'size-chart-for-woocommerce' ),
                __( 'Import / Export', 'size-chart-for-woocommerce' ),
                $cs_capability,
                'size-chart-import-export',
                array( $this, 'scfw_size_chart_import_export_page' )
            );
        }
        $settings = add_submenu_page(
            'edit.php?post_type=size-chart',
            __( 'Settings', 'size-chart-for-woocommerce' ),
            __( 'Settings', 'size-chart-for-woocommerce' ),
            $cs_capability,
            'size-chart-setting-page',
            array( $this, 'scfw_size_chart_settings_form_callback' )
        );
        add_submenu_page(
            'dots_store',
            $this->get_plugin_name(),
            $this->get_plugin_name(),
            $cs_capability,
            'scfw-upgrade-dashboard',
            array( $this, 'scfw_free_user_upgrade_page' )
        );
        add_action( "load-{$settings}", array( $this, 'scfw_size_chart_settings_page_callback' ) );
    }
    
    /**
     * Add custom css for dotstore icon in admin area
     *
     * @since  1.1.3
     *
     */
    public function scfw_size_chart_custom_styles_and_scripts()
    {
        global  $current_screen ;
        echo  '<style>
	    .toplevel_page_dots_store .dashicons-marker::after{content:"";border:3px solid;position:absolute;top:14px;left:15px;border-radius:50%;opacity: 0.6;}
	    li.toplevel_page_dots_store:hover .dashicons-marker::after,li.toplevel_page_dots_store.current .dashicons-marker::after{opacity: 1;}
	    @media only screen and (max-width: 960px){
	    	.toplevel_page_dots_store .dashicons-marker::after{left:14px;}
	    }
	  	</style>' ;
        $placeholder = __( 'Size chart title', 'size-chart-for-woocommerce' );
        
        if ( $current_screen->post_type === 'size-chart' ) {
            ?>
	        <script type="text/javascript">
	            document.addEventListener('DOMContentLoaded', function() {
	            	// Remove hr tag from size chart post type to fix twice notice issue
	                var hrElement = document.querySelector('.wrap .wp-header-end');
	                if (hrElement) {
	                    hrElement.parentNode.removeChild(hrElement);
	                }

	                // Add placeholder in search box
	                var inputElement = document.getElementById('post-search-input');
	                if (inputElement) {
	                	inputElement.setAttribute('placeholder', '<?php 
            echo  esc_attr( $placeholder ) ;
            ?>');
	                }
	            });
	        </script>
	        <?php 
        }
    
    }
    
    /**
     * Size chart get started page.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_get_started_page()
    {
        $file_dir_path = 'partials/size-chart-get-started-page.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     * Size chart information page.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_information_page()
    {
        $file_dir_path = 'partials/size-chart-information-page.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     * Size chart import/export page.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_import_export_page()
    {
        $file_dir_path = 'partials/size-chart-import-export-page.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     * Size chart settings and redirection.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_settings_page_callback()
    {
        // Sanitize user input.
        $size_chart_submit = filter_input( INPUT_POST, 'size_chart_submit', FILTER_SANITIZE_SPECIAL_CHARS );
        $get_page_name = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        
        if ( isset( $size_chart_submit ) && isset( $get_page_name ) && 'size-chart-setting-page' === $get_page_name ) {
            $this->size_chart_settings = array();
            // Sanitize user input. (Free)
            $size_chart_tab_label = filter_input( INPUT_POST, 'size-chart-tab-label', FILTER_SANITIZE_SPECIAL_CHARS );
            $size_chart_popup_label = filter_input( INPUT_POST, 'size-chart-popup-label', FILTER_SANITIZE_SPECIAL_CHARS );
            $size_chart_popup_type = filter_input( INPUT_POST, 'size-chart-popup-type', FILTER_SANITIZE_SPECIAL_CHARS );
            $size_chart_sub_title_text = filter_input( INPUT_POST, 'size-chart-sub-title-text', FILTER_SANITIZE_SPECIAL_CHARS );
            // Assign the user input data in size chart settings object.
            $this->size_chart_settings['size-chart-tab-label'] = $size_chart_tab_label;
            $this->size_chart_settings['size-chart-popup-label'] = $size_chart_popup_label;
            $this->size_chart_settings['size-chart-popup-type'] = ( $size_chart_popup_type ? $size_chart_popup_type : 'text' );
            $this->size_chart_settings['size-chart-sub-title-text'] = $size_chart_sub_title_text;
            $size_chart_settings_json_encode = wp_json_encode( $this->size_chart_settings );
            // Update size chart settings option.
            update_option( "size_chart_settings", $size_chart_settings_json_encode );
            ?>
            <div class="updated">
                <p><strong><?php 
            esc_html_e( 'Settings saved.', 'size-chart-for-woocommerce' );
            ?></strong></p>
            </div>
			<?php 
        }
    
    }
    
    /**
     * Removed the submenu page.
     * 
     * @since      1.0.0
     */
    public function scfw_welcome_screen_remove_menus_callback()
    {
        remove_submenu_page( 'dots_store', 'dots_store' );
        remove_submenu_page( 'dots_store', 'size-chart-information' );
        remove_submenu_page( 'dots_store', 'size-chart-get-started-page' );
        remove_submenu_page( 'dots_store', 'size-chart-import-export' );
        remove_submenu_page( 'dots_store', 'scfw-upgrade-dashboard' );
    }
    
    /**
     * Size chart and product page in save the size chart meta.
     *
     * @param int $post_id The ID of the post being saved.
     *
     * @return bool
     */
    public function scfw_size_chart_product_and_size_chart_save_callback( $post_id )
    {
        // If this is an autosave, our form has not been submitted,
        // so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // Sanitize user input.
        $post_type_name = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_SPECIAL_CHARS );
        // Check the user's permissions.
        
        if ( 'product' === $post_type_name || $this->get_plugin_post_type_name() === $post_type_name ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        // Save the meta when the chart post is saved.
        // Sanitize the user input.
        $size_chart_select_nonce = filter_input( INPUT_POST, 'size_chart_select_custom_box', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        // Verify that the nonce is valid.
        
        if ( isset( $size_chart_select_nonce ) && wp_verify_nonce( $size_chart_select_nonce, 'size_chart_select_custom_box' ) ) {
            $args = array(
                'prod-chart' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $post_chart_product = filter_input_array( INPUT_POST, $args );
            $chart_product = ( isset( $post_chart_product['prod-chart'] ) && array_filter( $post_chart_product['prod-chart'] ) ? $post_chart_product['prod-chart'] : array() );
            $chart_product = wp_json_encode( $chart_product );
            
            if ( isset( $chart_product ) && !empty($chart_product) ) {
                update_post_meta( $post_id, 'prod-chart', $chart_product );
                return true;
            } else {
                delete_post_meta( $post_id, 'prod-chart' );
            }
        
        }
        
        // Save the meta when the chart post is saved.
        // Sanitize the user input.
        $size_chart_inner_nonce = filter_input( INPUT_POST, 'size_chart_inner_custom_box', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( isset( $size_chart_inner_nonce ) && wp_verify_nonce( $size_chart_inner_nonce, 'size_chart_inner_custom_box' ) ) {
            // Sanitize the user input.
            $chart_label = filter_input( INPUT_POST, 'label', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_sub_title = filter_input( INPUT_POST, 'size-chart-sub-title', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_tab_label = filter_input( INPUT_POST, 'chart-tab-label', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_popup_label = filter_input( INPUT_POST, 'chart-popup-label', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_popup_type = filter_input( INPUT_POST, 'chart-popup-type', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_popup_icon = filter_input( INPUT_POST, 'chart-popup-icon', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_img = filter_input( INPUT_POST, 'primary-chart-image', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_position = filter_input( INPUT_POST, 'position', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_table = filter_input(
                INPUT_POST,
                'chart-table',
                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                FILTER_FLAG_NO_ENCODE_QUOTES
            );
            $table_style = filter_input( INPUT_POST, 'table-style', FILTER_SANITIZE_SPECIAL_CHARS );
            $chart_popup_note = filter_input( INPUT_POST, 'chart-popup-note', FILTER_SANITIZE_SPECIAL_CHARS );
            $size_chart_style = filter_input( INPUT_POST, 'size-chart-style', FILTER_SANITIZE_SPECIAL_CHARS );
            // Sanitize the user input array values.
            $args = array(
                'chart-categories' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $post_chart_categories = filter_input_array( INPUT_POST, $args );
            $chart_categories = ( isset( $post_chart_categories['chart-categories'] ) && array_filter( $post_chart_categories['chart-categories'] ) ? $post_chart_categories['chart-categories'] : array() );
            $chart_categories = wp_json_encode( $chart_categories );
            // Sanitize the user input array values.
            $args = array(
                'chart-tags' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $post_chart_tags = filter_input_array( INPUT_POST, $args );
            $chart_tags = ( isset( $post_chart_tags['chart-tags'] ) && array_filter( $post_chart_tags['chart-tags'] ) ? $post_chart_tags['chart-tags'] : array() );
            $chart_tags = wp_json_encode( $chart_tags );
            // Sanitize the user input array values.
            $args = array(
                'chart-attributes' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $post_chart_attributes = filter_input_array( INPUT_POST, $args );
            $chart_attributes = ( isset( $post_chart_attributes['chart-attributes'] ) && array_filter( $post_chart_attributes['chart-attributes'] ) ? $post_chart_attributes['chart-attributes'] : array() );
            $chart_attributes = wp_json_encode( $chart_attributes );
            // Sanitize Color Data
            
            if ( scfw_fs()->is__premium_only() & scfw_fs()->can_use_premium_code() ) {
                $scfw_header_bg_color = filter_input( INPUT_POST, 'scfw_header_bg_color', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_even_row_bg_color = filter_input( INPUT_POST, 'scfw_even_row_bg_color', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_odd_row_bg_color = filter_input( INPUT_POST, 'scfw_odd_row_bg_color', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_text_color = filter_input( INPUT_POST, 'scfw_text_color', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_even_text_color = filter_input( INPUT_POST, 'scfw_even_text_color', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_odd_text_color = filter_input( INPUT_POST, 'scfw_odd_text_color', FILTER_SANITIZE_SPECIAL_CHARS );
            } else {
                $scfw_header_bg_color = '';
                $scfw_even_row_bg_color = '';
                $scfw_odd_row_bg_color = '';
                $scfw_text_color = '';
                $scfw_even_text_color = '';
                $scfw_odd_text_color = '';
            }
            
            //Sanitize Border Data
            
            if ( scfw_fs()->is__premium_only() & scfw_fs()->can_use_premium_code() ) {
                $scfw_border_hb_style = filter_input( INPUT_POST, 'scfw_border_hb_style', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_border_hw = filter_input( INPUT_POST, 'scfw_border_hw', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_border_vb_style = filter_input( INPUT_POST, 'scfw_border_vb_style', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_border_vw = filter_input( INPUT_POST, 'scfw_border_vw', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_border_color = filter_input( INPUT_POST, 'scfw_border_color', FILTER_SANITIZE_SPECIAL_CHARS );
            } else {
                $scfw_border_hb_style = '';
                $scfw_border_hw = '';
                $scfw_border_vb_style = '';
                $scfw_border_vw = '';
                $scfw_border_color = '';
            }
            
            //Sanitize chart country
            
            if ( scfw_fs()->is__premium_only() & scfw_fs()->can_use_premium_code() ) {
                $args = array(
                    'chart-country' => array(
                    'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                    'flags'  => FILTER_REQUIRE_ARRAY,
                ),
                );
                $post_chart_country = filter_input_array( INPUT_POST, $args );
                $chart_country = ( isset( $post_chart_country['chart-country'] ) && array_filter( $post_chart_country['chart-country'] ) ? $post_chart_country['chart-country'] : array() );
            } else {
                $chart_country = array();
            }
            
            // Sanitize other premium settings
            
            if ( scfw_fs()->is__premium_only() & scfw_fs()->can_use_premium_code() ) {
                $popup_position = filter_input( INPUT_POST, 'popup-position', FILTER_SANITIZE_SPECIAL_CHARS );
                $chart_table_font_size = filter_input( INPUT_POST, 'chart-table-font-size', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_enable_table_hover = filter_input( INPUT_POST, 'scfw_enable_table_hover', FILTER_SANITIZE_SPECIAL_CHARS );
                $scfw_table_hover_bg_color = filter_input( INPUT_POST, 'scfw_table_hover_bg_color', FILTER_SANITIZE_SPECIAL_CHARS );
            } else {
                $popup_position = '';
                $chart_table_font_size = '';
                $scfw_enable_table_hover = '';
                $scfw_table_hover_bg_color = '';
            }
            
            // Save the data in post meta.
            update_post_meta( $post_id, 'label', $chart_label );
            update_post_meta( $post_id, 'size-chart-sub-title', $chart_sub_title );
            update_post_meta( $post_id, 'chart-tab-label', $chart_tab_label );
            update_post_meta( $post_id, 'chart-popup-label', $chart_popup_label );
            update_post_meta( $post_id, 'chart-popup-type', $chart_popup_type );
            update_post_meta( $post_id, 'chart-popup-icon', $chart_popup_icon );
            update_post_meta( $post_id, 'primary-chart-image', $chart_img );
            update_post_meta( $post_id, 'position', $chart_position );
            update_post_meta( $post_id, 'chart-categories', $chart_categories );
            update_post_meta( $post_id, 'chart-tags', $chart_tags );
            update_post_meta( $post_id, 'chart-attributes', $chart_attributes );
            update_post_meta( $post_id, 'chart-table', $chart_table );
            update_post_meta( $post_id, 'table-style', $table_style );
            update_post_meta( $post_id, 'chart-popup-note', $chart_popup_note );
            update_post_meta( $post_id, 'scfw_header_bg_color', $scfw_header_bg_color );
            update_post_meta( $post_id, 'scfw_even_row_bg_color', $scfw_even_row_bg_color );
            update_post_meta( $post_id, 'scfw_odd_row_bg_color', $scfw_odd_row_bg_color );
            update_post_meta( $post_id, 'scfw_text_color', $scfw_text_color );
            update_post_meta( $post_id, 'scfw_even_text_color', $scfw_even_text_color );
            update_post_meta( $post_id, 'scfw_odd_text_color', $scfw_odd_text_color );
            update_post_meta( $post_id, 'scfw_border_hb_style', $scfw_border_hb_style );
            update_post_meta( $post_id, 'scfw_border_hw', $scfw_border_hw );
            update_post_meta( $post_id, 'scfw_border_vb_style', $scfw_border_vb_style );
            update_post_meta( $post_id, 'scfw_border_vw', $scfw_border_vw );
            update_post_meta( $post_id, 'scfw_border_color', $scfw_border_color );
            update_post_meta( $post_id, 'chart_country', $chart_country );
            update_post_meta( $post_id, 'popup-position', $popup_position );
            update_post_meta( $post_id, 'size-chart-style', $size_chart_style );
            update_post_meta( $post_id, 'chart-table-font-size', $chart_table_font_size );
            update_post_meta( $post_id, 'scfw_enable_table_hover', $scfw_enable_table_hover );
            update_post_meta( $post_id, 'scfw_table_hover_bg_color', $scfw_table_hover_bg_color );
        }
        
        // Save the meta when the chart post is saved.
        // Sanitize the user input.
        $size_chart_search_product_nonce = filter_input( INPUT_POST, 'size_chart_search_product_custom_box', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( isset( $size_chart_search_product_nonce ) && wp_verify_nonce( $size_chart_search_product_nonce, 'size_chart_search_product_custom_box' ) ) {
            $args = array(
                'product-item' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $product_ids = filter_input_array( INPUT_POST, $args );
            if ( isset( $product_ids['product-item'] ) && !empty($product_ids['product-item']) && array_filter( $product_ids['product-item'] ) ) {
                foreach ( $product_ids['product-item'] as $product_id ) {
                    //Check if multiple size chart ids or not
                    $assigned_chart_ids = json_decode( get_post_meta( $product_id, 'prod-chart', true ) );
                    //If already assinged size charts previously then merge the ids
                    
                    if ( is_array( $assigned_chart_ids ) && !in_array( $post_id, $assigned_chart_ids, true ) ) {
                        $assigned_chart_ids[] = $post_id;
                        update_post_meta( $product_id, 'prod-chart', wp_json_encode( $assigned_chart_ids ) );
                    } else {
                        //Check if no size chart assigned then add the size chart ID
                        if ( !is_array( $assigned_chart_ids ) ) {
                            update_post_meta( $product_id, 'prod-chart', $post_id );
                        }
                    }
                
                }
            }
        }
        
        return true;
    }
    
    /**
     * Size chart post update messages.
     *
     * See /wp-admin/edit-form-advanced.php
     *
     * @param array $messages Existing post update messages.
     *
     * @return array Amended post update messages with new CPT update messages.
     */
    public function scfw_post_updated_messages( $messages )
    {
        $size_chart_post = $this->get_plugin_post_type_name();
        $messages[$size_chart_post][1] = __( 'Size chart updated successfully.', 'size-chart-for-woocommerce' );
        $messages[$size_chart_post][6] = __( 'Size chart published successfully.', 'size-chart-for-woocommerce' );
        //return the new messages
        return $messages;
    }
    
    /**
     * Adds the meta box container.
     *
     * @param string $post_type The post type name.
     *
     * @since    1.0.0
     */
    public function scfw_size_chart_add_meta_box_callback( $post_type )
    {
        // Limit meta box to chart post type.
        $post_types_chart = array( $this->get_plugin_post_type_name(), 'product' );
        
        if ( in_array( $post_type, $post_types_chart, true ) ) {
            if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
                // Meta box for size chart shortcode
                add_meta_box(
                    'size-chart-shortcode',
                    __( 'Shortcode', 'size-chart-for-woocommerce' ),
                    array( $this, 'scfw_size_chart_shortcode_callback__premium_only' ),
                    $this->get_plugin_post_type_name(),
                    'side',
                    'default'
                );
            }
            // Chart setting meta box.
            add_meta_box(
                'chart-settings',
                __( 'Size Chart Settings', 'size-chart-for-woocommerce' ),
                array( $this, 'scfw_size_chart_meta_box_content_callback' ),
                $this->get_plugin_post_type_name(),
                'advanced',
                'high'
            );
            // Meta box to select chart in product page.
            add_meta_box(
                'additional-chart',
                __( 'Search/Select Size Chart', 'size-chart-for-woocommerce' ),
                array( $this, 'scfw_size_chart_select_chart_callback' ),
                'product',
                'side',
                'default'
            );
            // Meta box to List of assign category.
            add_meta_box(
                'chart-assign-category',
                __( 'Assign Category', 'size-chart-for-woocommerce' ),
                array( $this, 'scfw_size_chart_assign_category_callback' ),
                $this->get_plugin_post_type_name(),
                'side',
                'default'
            );
            // Meta box to List of assign tag.
            add_meta_box(
                'chart-assign-tag',
                __( 'Assign Tag', 'size-chart-for-woocommerce' ),
                array( $this, 'scfw_size_chart_assign_tag_callback' ),
                $this->get_plugin_post_type_name(),
                'side',
                'default'
            );
            // Meta box to List of assign tag.
            add_meta_box(
                'chart-assign-attributes',
                __( 'Assign Attributes', 'size-chart-for-woocommerce' ),
                array( $this, 'scfw_size_chart_assign_attributes_callback' ),
                $this->get_plugin_post_type_name(),
                'side',
                'default'
            );
            // Meta box to List of assign Product
            add_meta_box(
                'chart-assign-product',
                __( 'Assign Product', 'size-chart-for-woocommerce' ),
                array( $this, 'scfw_size_chart_assign_product_callback' ),
                $this->get_plugin_post_type_name(),
                'side',
                'default'
            );
        }
    
    }
    
    /**
     * Meta Box content.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_meta_box_content_callback()
    {
        $file_dir_path = 'partials/size-chart-meta-box-content-form.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to select chart on product page.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_select_chart_callback()
    {
        $file_dir_path = 'partials/size-chart-select-chart-form.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to assign category list.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_assign_category_callback()
    {
        $file_dir_path = 'partials/size-chart-assign-category.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to assign tag list.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_assign_tag_callback()
    {
        $file_dir_path = 'partials/size-chart-assign-tag.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to assign product attributes list.
     *
     * @since      2.4.0
     */
    public function scfw_size_chart_assign_attributes_callback()
    {
        $file_dir_path = 'partials/size-chart-assign-attributes.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to assign product list.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_assign_product_callback()
    {
        $file_dir_path = 'partials/size-chart-assign-product.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Size chart settings form.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_settings_form_callback()
    {
        $file_dir_path = 'partials/size-chart-settings-form.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            include_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     * Premium version info page
     *
     */
    public function scfw_free_user_upgrade_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/dots-upgrade-dashboard.php';
    }
    
    /**
     * Duplicate post create as a draft and redirects then to the edit post screen.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_duplicate_post_callback()
    {
        // Sanitize user input.
        $get_request_get = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_SPECIAL_CHARS );
        $size_chart_id = ( isset( $get_request_get ) ? absint( $get_request_get ) : 0 );
        
        if ( isset( $get_request_get ) && !empty($get_request_get) ) {
            $clone_post_id = $this->scfw_size_chart_duplicate( $size_chart_id );
            wp_redirect( admin_url( 'post.php?action=edit&post=' . $clone_post_id ) );
            exit;
        } else {
            wp_die( esc_html__( 'could not find post: ' . $size_chart_id, 'size-chart-for-woocommerce' ) );
        }
    
    }
    
    /**
     * Creates size chart post preview.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_preview_post_callback()
    {
        $result = array();
        $result['success'] = 0;
        $result['msg'] = esc_html__( 'Something went wrong.', 'size-chart-for-woocommerce' );
        // Sanitize user input.
        $nonce = filter_input( INPUT_GET, 'security', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        // Verify the nonce.
        
        if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'size_chart_for_wooocmmerce_nonoce' ) ) {
            $result['msg'] = esc_html__( 'Security check failed.', 'size-chart-for-woocommerce' );
            echo  wp_json_encode( $result ) ;
            wp_die();
        }
        
        // Sanitize user input.
        $chart_id = filter_input( INPUT_GET, 'chartID', FILTER_SANITIZE_NUMBER_INT );
        $table_style = filter_input( INPUT_GET, 'popup_style', FILTER_SANITIZE_SPECIAL_CHARS );
        $chart_table_font_size = filter_input( INPUT_GET, 'table_font_size', FILTER_SANITIZE_SPECIAL_CHARS );
        $size_chart_style = filter_input( INPUT_GET, 'size_chart_style', FILTER_SANITIZE_SPECIAL_CHARS );
        $popup_position = filter_input( INPUT_GET, 'popup_position', FILTER_SANITIZE_SPECIAL_CHARS );
        
        if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
            if ( !isset( $chart_table_font_size ) || empty($chart_table_font_size) ) {
                $chart_table_font_size = scfw_size_chart_table_font_size__premium_only( $chart_id );
            }
            if ( !isset( $popup_position ) || empty($popup_position) ) {
                $popup_position = scfw_get_popup_postition__premium_only( $chart_id );
            }
        }
        
        if ( !isset( $size_chart_style ) || empty($size_chart_style) ) {
            $size_chart_style = scfw_size_chart_style_value_by_chart_id( $chart_id );
        }
        
        if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
            $filter = array(
                'data'         => array(
                'filter' => array( FILTER_VALIDATE_INT, FILTER_SANITIZE_SPECIAL_CHARS ),
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
                'chart_color'  => array(
                'filter' => array( FILTER_VALIDATE_INT, FILTER_SANITIZE_SPECIAL_CHARS ),
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
                'chart_border' => array(
                'filter' => array( FILTER_VALIDATE_INT, FILTER_SANITIZE_SPECIAL_CHARS ),
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $data = filter_input_array( INPUT_GET, $filter );
            $data = ( !empty($data) ? $data['data'] : array() );
            $chart_color = filter_input_array( INPUT_GET, $filter );
            $chart_color = ( !empty($chart_color) ? $chart_color['chart_color'] : array() );
            $chart_border = filter_input_array( INPUT_GET, $filter );
            $chart_border = ( !empty($chart_border) ? $chart_border['chart_border'] : array() );
        }
        
        
        if ( isset( $chart_id ) && !empty($chart_id) ) {
            $chart_label = scfw_size_chart_get_label_by_chart_id( $chart_id );
            
            if ( isset( $chart_label ) && !empty($chart_label) ) {
                
                if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
                    
                    if ( 'advance-style' !== $table_style ) {
                        $result['css'] = scfw_size_chart_get_inline_styles_by_post_id( $chart_id, $table_style, $chart_table_font_size );
                    } else {
                        $result['css'] = "table#size-chart.advance-style tr th {background: {$chart_color['scfw_header_bg_color']};color: {$chart_color['scfw_text_color']};} \n                            #size-chart.advance-style tr:nth-child(even) {background: {$chart_color['scfw_odd_row_bg_color']};color: {$chart_color['scfw_odd_text_color']};}\n                            #size-chart.advance-style td:nth-child(even) {background: transparent;}\n\n                            #size-chart.advance-style tr:nth-child(odd) {background: {$chart_color['scfw_even_row_bg_color']};color: {$chart_color['scfw_even_text_color']};}\n                            #size-chart.advance-style td:nth-child(odd) {background: transparent;}\n\n                            table#size-chart.advance-style tr td, #size-chart.advance-style tr th { \n                                border-top:{$chart_border['scfw_border_hw']}px {$chart_border['scfw_border_hb_style']} {$chart_border['scfw_border_color']};\n                                border-bottom:{$chart_border['scfw_border_hw']}px {$chart_border['scfw_border_hb_style']} {$chart_border['scfw_border_color']};\n                                border-left:{$chart_border['scfw_border_vw']}px {$chart_border['scfw_border_vb_style']} {$chart_border['scfw_border_color']};\n                                border-right:{$chart_border['scfw_border_vw']}px {$chart_border['scfw_border_vb_style']} {$chart_border['scfw_border_color']};\n                            }";
                        
                        if ( isset( $chart_table_font_size ) && !empty($chart_table_font_size) ) {
                            
                            if ( 'm' === $chart_table_font_size ) {
                                $table_font_size = '16px';
                            } elseif ( 'l' === $chart_table_font_size ) {
                                $table_font_size = '18px';
                            } elseif ( 'xl' === $chart_table_font_size ) {
                                $table_font_size = '20px';
                            } elseif ( 'xxl' === $chart_table_font_size ) {
                                $table_font_size = '22px';
                            } else {
                                $table_font_size = '14px';
                            }
                        
                        } else {
                            $table_font_size = '14px';
                        }
                        
                        $result['css'] .= "table#size-chart tr th, table#size-chart tr td{font-size:{$table_font_size}}";
                        
                        if ( 'yes' === $chart_color['scfw_enable_table_hover'] && 'advance-style' === $table_style ) {
                            $table_hover_bg_color = $chart_color['scfw_table_hover_bg_color'];
                            $opacity = 0.5;
                            // Convert hex color code to RGB components
                            $red = hexdec( substr( $table_hover_bg_color, 1, 2 ) );
                            $green = hexdec( substr( $table_hover_bg_color, 3, 2 ) );
                            $blue = hexdec( substr( $table_hover_bg_color, 5, 2 ) );
                            // Create the RGBA color string
                            $rgbaColor = "rgba({$red}, {$green}, {$blue}, {$opacity})";
                            $result['css'] .= ".scfw-chart-table tr:hover td, .scfw-chart-table tr:hover th{background-color: {$rgbaColor}!important;}\n\t\t\t\t\t\t\t\t.scfw-chart-table tr td.col-highlight, .scfw-chart-table tr th.col-highlight{background-color: {$rgbaColor}!important;}\n\t\t\t\t\t\t\t\t.scfw-chart-table tr:hover td:hover, .scfw-chart-table tr:hover th:hover{background-color: {$table_hover_bg_color}!important;}\n\t\t\t\t\t\t\t\t.scfw-chart-table tr:hover td:hover~td, .scfw-chart-table tr:hover th:hover~th{background-color: inherit!important;}";
                        }
                    
                    }
                
                } else {
                    $result['css'] = scfw_size_chart_get_inline_styles_by_post_id( $chart_id, $table_style, $table_font_size );
                }
                
                ob_start();
                ?>
				<div id="md-size-chart-modal" class="md-size-chart-modal scfw-size-chart-modal <?php 
                echo  esc_attr( 'scfw-size-chart-popup-' . $popup_position ) ;
                ?>" chart-data-id="chart-<?php 
                echo  esc_attr( $chart_id ) ;
                ?>">
	                <div class="md-size-chart-modal-content">
            			<div class="md-size-chart-overlay"></div>
	                    <div class="md-size-chart-modal-body <?php 
                echo  esc_attr( scfw_size_chart_get_size() ) ;
                ?> <?php 
                echo  ( !empty($size_chart_style) && 'tab_style' === $size_chart_style ? esc_attr( 'scfw_tab_style' ) : '' ) ;
                ?>" id="md-poup">
	                        <?php 
                $file_dir_path = 'includes/common-files/size-chart-contents.php';
                if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                    include plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                }
                ?>
	                    </div>
	                </div>
	            </div>
				<?php 
                $file_dir_path = 'includes/common-files/size-chart-contents.php';
                if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                }
                $result['html'] = ob_get_clean();
                $result['success'] = 1;
                $result['msg'] = esc_html__( 'Successfully...', 'size-chart-for-woocommerce' );
            } else {
                $result['msg'] = esc_html__( 'No data found...', 'size-chart-for-woocommerce' );
            }
        
        } else {
            $result['msg'] = esc_html__( 'No result found...', 'size-chart-for-woocommerce' );
        }
        
        echo  wp_json_encode( $result ) ;
        wp_die();
    }
    
    /**
     * Size chart preview dialog box html.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_preview_dialog_box_callback()
    {
        ?>
        <div id="wait" style="display:none;">
            <img src="<?php 
        echo  esc_url( plugins_url( 'admin/images/loader.gif', dirname( __FILE__ ) ) ) ;
        ?>" width="64" height="64" alt="<?php 
        esc_attr_e( 'loader', 'size-chart-for-woocommerce' );
        ?>"/>
            <br><?php 
        esc_html_e( 'Loading...', 'size-chart-for-woocommerce' );
        ?>
        </div>

        <div id="scfw-size-chart-preview-modal">
            <!-- Modal content -->
        </div>
		<?php 
    }
    
    /**
     * Manage size chart type and action.
     *
     * @param array $columns existing columns array.
     *
     * @return array updated columns array.
     */
    public function scfw_size_chart_column_callback( $columns )
    {
        $new_columns = ( is_array( $columns ) ? $columns : array() );
        unset( $new_columns['date'] );
        $new_columns['size-chart-type'] = __( 'Size Chart Type', 'size-chart-for-woocommerce' );
        $new_columns['product_assign'] = __( 'Product assign', 'size-chart-for-woocommerce' );
        $new_columns['category_assign'] = __( 'Category assign', 'size-chart-for-woocommerce' );
        $new_columns['tag_assign'] = __( 'Tag assign', 'size-chart-for-woocommerce' );
        if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
            $new_columns['size_chart_shortcode'] = __( 'Shortcode', 'size-chart-for-woocommerce' );
        }
        return $new_columns;
    }
    
    /**
     * Manage size chart column.
     *
     * @param string $column column name passing.
     */
    public function scfw_size_chart_manage_column_callback( $column )
    {
        global  $post ;
        switch ( $column ) {
            case 'product_assign':
                $meta_query_args = $this->scfw_size_chart_product_meta_query_argument();
                $wp_posts_query = new WP_Query( $meta_query_args );
                $assigned_product_ids = array();
                if ( !empty($wp_posts_query->posts) ) {
                    foreach ( $wp_posts_query->posts as $product_id ) {
                        $data_size_chart = json_decode( get_post_meta( $product_id, 'prod-chart', true ) );
                        $valid_product = false;
                        
                        if ( is_array( $data_size_chart ) ) {
                            $valid_product = in_array( $post->ID, $data_size_chart, true );
                        } else {
                            $valid_product = ( $post->ID === $data_size_chart ? true : false );
                        }
                        
                        if ( true === $valid_product ) {
                            $assigned_product_ids[] = $product_id;
                        }
                    }
                }
                $sep = '';
                
                if ( isset( $assigned_product_ids ) && !empty($assigned_product_ids) ) {
                    foreach ( $assigned_product_ids as $assigned_product_id ) {
                        printf( wp_kses_post( "{$sep}<a href='%s'>%s</a>" ), esc_url( get_edit_post_link( $assigned_product_id ) ), wp_kses_post( get_the_title( $assigned_product_id ) ) );
                        $sep = ', ';
                    }
                } else {
                    esc_html_e( 'No product assign', 'size-chart-for-woocommerce' );
                }
                
                break;
            case 'category_assign':
                $size_chart_cat_id = scfw_size_chart_get_categories( $post->ID );
                
                if ( !empty($size_chart_cat_id) ) {
                    $sep = '';
                    foreach ( $size_chart_cat_id as $size_chart_cat ) {
                        $data = get_term_by( 'id', $size_chart_cat, 'product_cat' );
                        echo  wp_kses_post( $sep . esc_html( $data->name ) ) ;
                        $sep = ', ';
                    }
                } else {
                    esc_html_e( 'No category assign', 'size-chart-for-woocommerce' );
                }
                
                break;
            case 'tag_assign':
                $size_chart_tag_id = scfw_size_chart_get_tags( $post->ID );
                
                if ( !empty($size_chart_tag_id) ) {
                    $sep = '';
                    foreach ( $size_chart_tag_id as $size_chart_tag ) {
                        $data = get_term_by( 'id', $size_chart_tag, 'product_tag' );
                        echo  wp_kses_post( $sep . esc_html( $data->name ) ) ;
                        $sep = ', ';
                    }
                } else {
                    esc_html_e( 'No tag assign', 'size-chart-for-woocommerce' );
                }
                
                break;
            case 'size-chart-type':
                $size_chart_type = get_post_meta( $post->ID, 'post_status', true );
                
                if ( isset( $size_chart_type ) && 'default' === $size_chart_type ) {
                    esc_html_e( 'Default Template', 'size-chart-for-woocommerce' );
                } else {
                    esc_html_e( 'Custom Template', 'size-chart-for-woocommerce' );
                }
                
                break;
            case 'action':
                if ( isset( $post ) && !empty($post) ) {
                    if ( '' !== $post->post_title ) {
                        echo  sprintf(
                            "<a href='%s' class='clone-chart' title='%s' rel='permalink'></a><a id='%s' href='javascript:void(0);' class='preview_chart' title='%s' rel='permalink'></a>",
                            esc_url( wp_nonce_url( add_query_arg( array(
                            'action' => 'size_chart_duplicate_post',
                            'post'   => $post->ID,
                        ), admin_url( 'admin.php' ) ), 'scfw_size_chart_duplicate_post_callback' ) ),
                            esc_attr__( 'Clone', 'size-chart-for-woocommerce' ),
                            esc_attr( $post->ID ),
                            esc_attr__( 'Preview', 'size-chart-for-woocommerce' )
                        ) ;
                    }
                }
                break;
            case 'size_chart_shortcode':
                ?>
				<input type="text" class="scfw-copy-shortcode" value='[scfw_size_chart id="<?php 
                echo  esc_attr( $post->ID ) ;
                ?>"]' readonly="readonly">
				<div class="scfw-after-copy-text">
					<span class="dashicons dashicons-yes-alt"></span><?php 
                esc_html_e( 'Shortcode  Copied to Clipboard!', 'size-chart-for-woocommerce' );
                ?>
				</div>
				<?php 
                break;
                wp_reset_postdata();
        }
    }
    
    /**
     * Remove size chart status menu from listings.
     *
     * @since 2.4.3
     */
    public function scfw_size_chart_view_edit_callback( $views )
    {
        $views = array();
        return $views;
    }
    
    /**
     * Remove the row actions for default size chart.
     *
     * @param array $actions post table row actions array.
     *
     * @return mixed post table row actions.
     */
    public function scfw_size_chart_remove_row_actions_callback( $actions )
    {
        global  $post ;
        
        if ( $this->get_plugin_post_type_name() === $post->post_type ) {
            $post_status = get_post_meta( $post->ID, 'post_status', true );
            if ( isset( $post_status ) && 'default' === $post_status ) {
                unset( $actions['inline hide-if-no-js'] );
            }
            $actions['previewing'] = sprintf( '<a id="%s" href="javascript:void(0);" class="preview_chart" rel="permalink">%s</a>', esc_attr( $post->ID ), esc_attr__( 'Preview', 'size-chart-for-woocommerce' ) );
            $actions['clone'] = sprintf( '<a href="%s" rel="permalink">%s</a>', esc_url( wp_nonce_url( add_query_arg( array(
                'action' => 'size_chart_duplicate_post',
                'post'   => $post->ID,
            ), admin_url( 'admin.php' ) ), 'scfw_size_chart_duplicate_post_callback' ) ), esc_attr__( 'Clone', 'size-chart-for-woocommerce' ) );
            $trash_post_status = get_post_status( $post->ID );
            
            if ( isset( $trash_post_status ) && 'trash' === $trash_post_status ) {
                unset( $actions['inline hide-if-no-js'] );
                unset( $actions['previewing'] );
                unset( $actions['clone'] );
            }
        
        }
        
        return apply_filters( 'size_chart_add_row_actions', $actions );
    }
    
    /**
     * Size chart default template.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_filter_default_template_callback()
    {
        global  $typenow ;
        // Sanitize user input.
        $current = filter_input( INPUT_GET, 'default_template', FILTER_SANITIZE_SPECIAL_CHARS );
        
        if ( $this->get_plugin_post_type_name() === $typenow ) {
            ?>
            <select name="default_template" id="issue">
                <option value=""><?php 
            esc_html_e( 'Show All Template', 'size-chart-for-woocommerce' );
            ?></option>
                <option value="hide-default" <?php 
            selected( 'hide-default', $current, true );
            ?>><?php 
            esc_html_e( 'Hide Default Template', 'size-chart-for-woocommerce' );
            ?></option>
            </select>
			<?php 
        }
    
    }
    
    /**
     * Set size chart default template.
     *
     * @since      1.0.0
     */
    public function scfw_size_chart_filter_default_template_query_callback()
    {
        global  $pagenow ;
        // Sanitize user input.
        $default_template = filter_input( INPUT_GET, 'default_template', FILTER_SANITIZE_SPECIAL_CHARS );
        $post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_SPECIAL_CHARS );
        $show_default_template = apply_filters( 'show_default_template', true );
        if ( is_admin() && 'edit.php' === $pagenow && isset( $post_type ) && $this->get_plugin_post_type_name() === $post_type && isset( $default_template ) && !empty($default_template) || false === $show_default_template ) {
            set_query_var( 'meta_query', array( array(
                'key'     => 'post_status',
                'value'   => 'default',
                'compare' => 'NOT EXISTS',
            ) ) );
        }
    }
    
    /**
     * Size chart selected chart deleted.
     *
     * @param int $post_id size chart id.
     */
    public function scfw_size_chart_selected_chart_delete_callback( $post_id )
    {
        
        if ( isset( $post_id ) && !empty($post_id) && $this->get_plugin_post_type_name() === get_post_type( $post_id ) ) {
            $post_ids_result = $this->scfw_get_post_id_by_meta_key_and_value( 'prod-chart', $post_id );
            if ( false !== $post_ids_result ) {
                foreach ( $post_ids_result as $product_id ) {
                    delete_post_meta( $product_id, 'prod-chart', $post_id );
                }
            }
        }
    
    }
    
    /**
     * Get post id from meta key and value.
     *
     * @param string $key post meta kay
     * @param mixed $value post meta value for matching.
     *
     * @return array|bool
     */
    public function scfw_get_post_id_by_meta_key_and_value( $key, $value )
    {
        global  $wpdb ;
        $cache_key = 'size_chart_meta_key_and_value_' . $key . '_' . $value;
        $meta_object = wp_cache_get( $cache_key );
        
        if ( false === $meta_object ) {
            // Ignore custom query warnings in phpcs. If convert this query to WP_Query then we can face a slow meta query issue.
            // phpcs:disable
            $meta_object = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key=%s AND meta_value=%s", $key, $value ) );
            // phpcs:enable
            wp_cache_set( $cache_key, $meta_object );
        }
        
        
        if ( isset( $meta_object ) && !empty($meta_object) && is_array( $meta_object ) && array_filter( $meta_object ) ) {
            return $meta_object;
        } else {
            return false;
        }
    
    }
    
    /**
     * Size chart pro plugin admin review notice.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_admin_notice_review_callback()
    {
        global  $typenow ;
        $get_post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( isset( $get_post_type ) && $get_post_type === 'size-chart' || $this->get_plugin_post_type_name() === $typenow ) {
            include_once plugin_dir_path( __FILE__ ) . 'partials/header/plugin-header.php';
        }
    }
    
    /**
     * Ajax search the size chart form product meta search box in product page.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_search_chart_callback()
    {
        ob_start();
        check_ajax_referer( 'size_chart_search_nonce', 'security' );
        // Sanitized user input.
        $search_query_parameter = filter_input( INPUT_GET, 'searchQueryParameter', FILTER_SANITIZE_SPECIAL_CHARS );
        $searched_term = ( isset( $search_query_parameter ) ? (string) wc_clean( wp_unslash( $search_query_parameter ) ) : '' );
        if ( empty($searched_term) ) {
            wp_die( -1 );
        }
        $search_query_parameter = str_replace( "’", "'", $search_query_parameter );
        // Search argument.
        $size_chart_search_args = array(
            'post_type'              => $this->get_plugin_post_type_name(),
            's'                      => html_entity_decode( $search_query_parameter, ENT_QUOTES, 'UTF-8' ),
            'post_status'            => 'publish',
            'posts_per_page'         => 80,
            'orderby'                => 'title',
            'order'                  => 'ASC',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );
        $size_chart_search_wp_query = new WP_Query( $size_chart_search_args );
        $found_chart = array();
        if ( $size_chart_search_wp_query->have_posts() ) {
            foreach ( $size_chart_search_wp_query->posts as $size_chart_search_chart ) {
                $found_chart[$size_chart_search_chart->ID] = sprintf( esc_html__( '%1$s (#%2$s)', 'size-chart-for-woocommerce' ), $size_chart_search_chart->post_title, $size_chart_search_chart->ID );
            }
        }
        wp_send_json( $found_chart );
    }
    
    /**
     * Size chart product assign ajax callback function.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_product_assign_callback()
    {
        ob_start();
        check_ajax_referer( 'size-chart-pagination', 'security' );
        // Sanitize user input.
        $post_id = filter_input( INPUT_GET, 'postID', FILTER_SANITIZE_NUMBER_INT );
        $post_per_page = filter_input( INPUT_GET, 'postPerPage', FILTER_SANITIZE_NUMBER_INT );
        if ( empty($post_id) ) {
            wp_die( -1 );
        }
        $response_array = array(
            'success'        => false,
            'msg'            => __( 'Something went wrong.', 'size-chart-for-woocommerce' ),
            'found_products' => array(),
        );
        // Meta_query argument.
        $meta_query_args = $this->scfw_size_chart_product_meta_query_argument();
        $wp_posts_query = new WP_Query( $meta_query_args );
        
        if ( !empty($wp_posts_query->posts) ) {
            foreach ( $wp_posts_query->posts as $product_id ) {
                $response_array['found_products'][$product_id] = array(
                    'href'  => esc_url( get_edit_post_link( $product_id ) ),
                    'title' => get_the_title( $product_id ),
                );
            }
            wp_reset_postdata();
        }
        
        
        if ( 0 !== count( $response_array['found_products'] ) ) {
            $response_array['success'] = true;
            $response_array['msg'] = __( 'Response successfully.', 'size-chart-for-woocommerce' );
            $response_array['load_pagination'] = scfw_size_chart_pagination_html(
                $wp_posts_query,
                $post_id,
                $post_per_page,
                false
            );
        }
        
        wp_send_json( $response_array );
        wp_die();
    }
    
    /**
     * Size chart product meta query argument created.
     *
     * @return array return the array of argument.
     */
    public function scfw_size_chart_product_meta_query_argument()
    {
        // Meta_query argument.
        // phpcs:disable
        $args = array(
            'post_type'      => 'product',
            'meta_query'     => array( array(
            'key'     => 'prod-chart',
            'compare' => 'EXISTS',
        ) ),
            'fields'         => 'ids',
            'posts_per_page' => -1,
        );
        // phpcs:enable
        return $args;
    }
    
    /**
     * Quick search products ajax callback function.
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_quick_search_products_callback()
    {
        ob_start();
        check_ajax_referer( 'size_chart_quick_search_nonoce', 'security' );
        // Sanitize user input.
        $quick_search_parameter = filter_input( INPUT_GET, 'searchQueryParameter', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $quick_search_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS );
        $quick_search_post_type = filter_input( INPUT_GET, 'postType', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( empty($quick_search_parameter) ) {
            wp_die( -1 );
        }
        if ( 'quick-search-posttype-size-chart' !== $quick_search_type ) {
            wp_die( -1 );
        }
        if ( 'product' !== $quick_search_post_type ) {
            wp_die( -1 );
        }
        $response_array = array(
            'success'        => false,
            'msg'            => __( 'Something went wrong.', 'size-chart-for-woocommerce' ),
            'found_products' => array(),
        );
        $quick_search_parameter = str_replace( "’", "'", $quick_search_parameter );
        $size_chart_search_args = array(
            'post_type'              => 'product',
            's'                      => html_entity_decode( $quick_search_parameter, ENT_QUOTES, 'UTF-8' ),
            'post_status'            => 'publish',
            'posts_per_page'         => 80,
            'orderby'                => 'title',
            'order'                  => 'ASC',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );
        $size_chart_search_wp_query = new WP_Query( $size_chart_search_args );
        if ( $size_chart_search_wp_query->have_posts() ) {
            foreach ( $size_chart_search_wp_query->posts as $size_chart_search_product ) {
                $response_array['found_products'][$size_chart_search_product->ID] = array(
                    'title' => $size_chart_search_product->post_title,
                    'id'    => $size_chart_search_product->ID,
                );
            }
        }
        
        if ( 0 !== count( $response_array['found_products'] ) ) {
            $response_array['success'] = true;
            $response_array['msg'] = __( 'Response successfully.', 'size-chart-for-woocommerce' );
        } else {
            $response_array['msg'] = __( 'No matching products found.', 'size-chart-for-woocommerce' );
        }
        
        wp_send_json( $response_array );
        wp_die();
    }
    
    /**
     * Duplicate size chart.
     *
     * @param int $size_chart_id existing size chart id.
     *
     * @return int|WP_Error duplicated size chart id or get error.
     */
    public function scfw_size_chart_duplicate( $size_chart_id )
    {
        $size_chart_title = get_the_title( $size_chart_id );
        $size_chart_content = scfw_size_chart_get_the_content( $size_chart_id );
        $new_size_chart_id = 0;
        
        if ( $this->get_plugin_post_type_name() === get_post_type( $size_chart_id ) ) {
            // Get number for current clone id.
            $clone_count = get_post_meta( $size_chart_id, 'clone-cnt', true );
            
            if ( isset( $clone_count ) && '' !== $clone_count ) {
                $cnt = $clone_count + 1;
            } else {
                $cnt = 0;
            }
            
            update_post_meta( $size_chart_id, 'clone-cnt', $cnt );
            $count_clone = get_post_meta( $size_chart_id, 'clone-cnt', true );
            $current_user = wp_get_current_user();
            $clone_post_author = $current_user->ID;
            $count = ( isset( $count_clone ) && $count_clone !== 0 ? '(' . $count_clone . ')' : '' );
            $size_chart_new_post = array(
                'post_title'   => $size_chart_title . ' - Copy' . $count,
                'post_status'  => 'draft',
                'post_type'    => $this->get_plugin_post_type_name(),
                'post_content' => $size_chart_content,
                'post_author'  => $clone_post_author,
            );
            $new_size_chart_id = wp_insert_post( $size_chart_new_post );
            // Copy post metadata.
            $default_size_chart_meta_data = get_post_meta( $size_chart_id );
            foreach ( $default_size_chart_meta_data as $key => $values ) {
                if ( 'clone-cnt' === $key ) {
                    continue;
                }
                foreach ( $values as $value ) {
                    if ( 'default' === $value ) {
                        continue;
                    }
                    add_post_meta( $new_size_chart_id, $key, $value );
                }
            }
        }
        
        return $new_size_chart_id;
    }
    
    /**
     * Default Chart Content HTML.
     *
     * @param string $template selected template name.
     *
     * @return false|string HTML of the size chart.
     */
    public function scfw_size_chart_cloth_template_html_content( $template )
    {
        ob_start();
        ?>
        <div class="chart-container">
            <div class="chart-content">
                <div class="chart-content-list">
					<?php 
        
        if ( 'tshirt-shirt' === $template ) {
            ?>
                        <p><?php 
            esc_html_e( 'To choose the correct size for you, measure your body as follows:', 'size-chart-for-woocommerce' );
            ?></p>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'womens-tshirt' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure under your arms, around the fullest part of the your chest.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around your natural waistline, keeping the tape a bit loose.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'mens-shirts' === $template ) {
            ?>
                        <p><?php 
            esc_html_e( 'To choose the correct size for you, measure your body as follows:', 'size-chart-for-woocommerce' );
            ?></p>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'womens-dress' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure under your arms, around the fullest part of the your chest.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around your natural waistline, keeping the tape a bit loose.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Hips : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part of your body at the top of your leg.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'jeans-trouser' === $template ) {
            ?>
                        <p><?php 
            esc_html_e( 'To choose the correct size for you, measure your body as follows:', 'size-chart-for-woocommerce' );
            ?></p>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around natural waistline.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Inside leg : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure from top of inside leg at crotch to ankle bone.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'womens-jeans' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around your natural waistline,keeping the tape bit loose.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Hips : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part of your body at the top of your leg.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Inseam : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Wearing pants that fit well, measure from the crotch seam to the bottom of the leg.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'mens-waistcoats' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'women-cloth' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part of the bust, keeping the tape parallel to the floor.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the narrowest point, keeping the tape parallel to the floor.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Hip : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Stand with feet together and measure around the fullest point of the hip, keep the tape parallel to the floor.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Inseam : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure inside length of leg from your crotch to the bottom of ankle.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        }
        
        ?>
                </div>
                <div class="chart-content-image">
					<?php 
        
        if ( 'tshirt-shirt' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-tshirts-and-polo-shirts.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'tshirt-shirt-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'womens-tshirt' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-t-shirt-top.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-tshirt', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'mens-shirts' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-shirts.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'mens-shirts', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'womens-dress' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-dress-size-chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-dress-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'jeans-trouser' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-jeans-and-trousers.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'jeans-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'womens-jeans' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-jeans-size-chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-jeans-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'mens-waistcoats' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-waistcoats.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'mens-waistcoats', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'women-cloth' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/cloth_size_chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'cloth-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'men-shoes' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-shoes-size-chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'mens-shoe-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'women-shoes' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-shoes-size-image.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-shoe-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        }
        
        ?>
                </div>
            </div>
        </div>

		<?php 
        return ob_get_clean();
    }
    
    /**
     * Default size chart add post meta.
     *
     * @param int $post_id Size chart id.
     * @param string $template selected template name.
     */
    public function scfw_size_chart_add_post_meta( $post_id, $template )
    {
        
        if ( 'tshirt-shirt' === $template ) {
            $label = "T-Shirts & Polo Shirts";
            $chart_table = stripcslashes( '[["TO FIT CHEST SIZE","INCHES","CM"],["XXXS","30-32","76-81"],["XXS","32-34","81-86"],["XS","34-36","86-91"],["S","36-38","91-96"],["M","38-40","96-101"],["L","40-42","101-106"],["XL","42-44","106-111"],["XXL","44-46","111-116"],["XXXL","46-48","116-121"]]' );
        } elseif ( 'womens-tshirt' === $template ) {
            $label = "Women's Sizes";
            $chart_table = stripcslashes( '[["UK SIZE","BUST","BUST","WAIST","WAIST","HIPS","HIPS"],["","INCHES","CM","INCHES","CM","INCHES","CM"],["4","31","78","24","60","33","83.5"],["6","32","80.5","25","62.5","34","86"],["8","33","83","26","65","35","88.5"],["10","35","88","28","70","37","93.5"],["12","37","93","30","75","39","98.5"],["14","39","98","31","80","41","103.5"],["16","41","103","33","85","43","108.5"],["18","44","110.5","36","92.5","46","116"]]' );
        } elseif ( 'mens-shirts' === $template ) {
            $label = "Men's Shirts";
            $chart_table = stripcslashes( '[["TO FIT CHEST SIZE","INCHES","CM","TO FIT NECK SIZE","INCHES","CM"],["XXXS","30-32","76-81","XXXS","14","36"],["XXS","32-34","81-86","XXS","14.5","37.5"],["XS","34-36","86-91","XS","15","38.5"],["S","36-38","91-96","S","15.5","39.5"],["M","38-40","96-101","M","16","41.5"],["L","40-42","101-106","L","17","43.5"],["XL","42-44","106-111","XL","17.5","45.5"],["XXL","44-46","111-116","XXL","18.5","47.5"],["XXXL","46-48","116-121","XXXL","19.5","49.5"]]' );
        } elseif ( 'womens-dress' === $template ) {
            $label = "Women's Dress Sizes";
            $chart_table = stripcslashes( '[["SIZE","NUMERIC SIZE","BUST","WAIST","HIP"],["XXXS","000","30","23","33"],["XXS","00","31.5","24","34"],["XS","0","32.5","25","35"],["XS","2","33.5","26","36"],["S","4","34.5","27","37"],["S","6","35.5","28","38"],["M","8","36.5","29","39"],["M","10","37.5","30","40"],["L","12","39","31.5","41.5"],["L","14","40.5","33","43"],["XL","16","42","34.5","44.5"],["XL","18","44","36","46.5"],["XXL","20","46","37.5","48.5"]]' );
        } elseif ( 'jeans-trouser' === $template ) {
            $label = "Men's Jeans & Trousers Size";
            $chart_table = stripcslashes( '[["TO FIT WAIST SIZE","INCHES","CM"],["26","26","66"],["28","28","71"],["29","29","73.5"],["30","30","76"],["31","31","78.5"],["32","32","81"],["33","33","83.5"],["34","34","86"],["36","36","91"],["38","38","96"],["40","40","101"],["","",""],["TO FIT INSIDE LEG LENGTH","INCHES","CM"],["Short","30","76"],["Regular","32","81"],["Long","34","86"]]' );
        } elseif ( 'womens-jeans' === $template ) {
            $label = "Women's Jeans Sizes";
            $chart_table = stripcslashes( '[["Size","Waist","Hip"],["24","24","35"],["25","25","36"],["26","26","37"],["27","27","38"],["28","28","39"],["29","29","40"],["30","30","41"],["31","31","42"],["32","32","43"],["33","33","44"],["34","34","45"]]' );
        } elseif ( 'mens-waistcoats' === $template ) {
            $label = "Men's Waistcoats";
            $chart_table = stripcslashes( '[["CHEST MEASUREMENT","INCHES","CM"],["32","32","81"],["34","34","86"],["36","36","91"],["38","38","96"],["40","40","101"],["42","42","106"],["44","44","111"],["46","46","116"]]' );
        } elseif ( 'women-cloth' === $template ) {
            $label = "Women's Sizes";
            $chart_table = stripcslashes( '[["UK SIZE","BUST","BUST","WAIST","WAIST","HIPS","HIPS"],["","INCHES","CM","INCHES","CM","INCHES","CM"],["4","31","78","24","60","33","83.5"],["6","32","80.5","25","62.5","34","86"],["8","33","83","26","65","35","88.5"],["10","35","88","28","70","37","93.5"],["12","37","93","30","75","39","98.5"],["14","39","98","31","80","41","103.5"],["16","41","103","33","85","43","108.5"],["18","44","110.5","36","92.5","46","116"]]' );
        } elseif ( 'men-shoes' === $template ) {
            $label = "Men's Shoes Size";
            $chart_table = stripcslashes( '[["US","Euro","UK","Inches","CM"],["6","39","5.5","9.25","23.5"],["6.5","39","6","9.5","24.1"],["7","40","6.5","9.625","24.4"],["7.5","40-41","7","9.75","24.8"],["8","41","7.5","9.9375","25.4"],["8.5","41-42","8","10.125","25.7"],["9","42","8.5","10.25","26"],["9.5","42-43","9","10.4375","26.7"],["10","43","9.5","10.5625","27"],["10.5","43-44","10","10.75","27.3"],["11","44","10.5","10.9375","27.9"],["11.5","44-45","11","11.125","28.3"],["12","45","11.5","11.25","28.6"],["13","46","12.5","11.5625","29.4"],["14","47","13.5","11.875","30.2"],["15","48","14.5","12.1875","31"],["16","49","15.5","12.5","31.8"]]' );
        } elseif ( 'women-shoes' === $template ) {
            $label = "Women's Sizes";
            $chart_table = stripcslashes( '[["US","Euro","UK","Inches","CM"],["4","35","2","8.1875","20.8"],["4.5","35","2.5","8.375","21.3"],["5","35-36","3","8.5","21.6"],["5.5","36","3.5","8.75","22.2"],["6","36-37","4","8.875","22.5"],["6.5","37","4.5","9.0625","23"],["7","37-38","5","9.25","23.5"],["7.5","38","5.5","9.375","23.8"],["8","38-39","6","9.5","24.1"],["8.5","39","6.5","9.6875","24.6"],["9","39-40","7","9.875","25.1"],["9.5","40","7.5","10","25.4"],["10","40-41","8","10.1875","25.9"],["10.5","41","8.5","10.3125","26.2"],["11","41-42","9","10.5","26.7"],["11.5","42","9.5","10.6875","27.1"],["12","42-43","10","10.875","27.6"]]' );
        }
        
        update_post_meta( $post_id, 'label', $label );
        update_post_meta( $post_id, 'position', 'popup' );
        update_post_meta( $post_id, 'post_status', 'default' );
        update_post_meta( $post_id, 'chart-table', $chart_table );
    }
    
    /**
     * Get the menu active class.
     *
     * @param string $current current menu
     * @param string|array $action get actions.
     *
     * @return string class name.
     */
    public function scfw_get_menu_active_class( $current, $action )
    {
        
        if ( is_array( $action ) && array_filter( $action ) ) {
            $action = implode( '-', $action );
            if ( $current === $action ) {
                return 'active';
            }
        } else {
            if ( $current === $action ) {
                return 'active';
            }
        }
        
        return '';
    }
    
    /**
     * Unassign the product
     * 
     * @since      1.0.0
     */
    public function scfw_size_chart_unassign_product_callback()
    {
        $result = array();
        $result['success'] = 0;
        $result['msg'] = esc_html__( 'Something went wrong.', 'size-chart-for-woocommerce' );
        // Sanitize user input.
        $nonce = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        // Verify nonce.
        
        if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'size_chart_for_wooocmmerce_nonoce' ) ) {
            $result['success'] = 0;
            $result['msg'] = esc_html__( 'Security check failed.', 'size-chart-for-woocommerce' );
            echo  wp_json_encode( $result ) ;
            wp_die();
        }
        
        // Sanitize user input.
        $product_id = filter_input( INPUT_POST, 'postID', FILTER_SANITIZE_NUMBER_INT );
        $chart_id = filter_input( INPUT_POST, 'chartID', FILTER_SANITIZE_NUMBER_INT );
        
        if ( isset( $product_id ) && !empty($product_id) ) {
            //Check if ids saved in array then only one product id will remove
            $saved_ids = json_decode( get_post_meta( $product_id, 'prod-chart', true ) );
            
            if ( is_array( $saved_ids ) ) {
                //remove the current product id
                $array_without_product_id = array_diff( $saved_ids, array( $chart_id ) );
                update_post_meta( $product_id, 'prod-chart', wp_json_encode( $array_without_product_id ) );
            } else {
                delete_post_meta( $product_id, 'prod-chart', $chart_id );
            }
            
            $result['msg'] = esc_html__( 'Product successfully removed from chart', 'size-chart-for-woocommerce' );
            $result['success'] = 1;
        }
        
        echo  wp_json_encode( $result ) ;
        wp_die();
    }
    
    /**
     * Export size chart data.
     * 
     * @since 1.0.0
     */
    public function scfw_size_chart_export_data_callback()
    {
        check_ajax_referer( 'size_chart_for_wooocmmerce_nonoce', 'security' );
        $chart_id = filter_input( INPUT_POST, 'chartID', FILTER_SANITIZE_NUMBER_INT );
        $json_data = get_post_meta( $chart_id, 'chart-table', true );
        $file_name = 'scfw_chart_data_export_' . time() . '.json';
        $path_data = wp_get_upload_dir();
        $save_path = $path_data['basedir'] . '/size-chart-for-woocommerce/';
        $download_path = $path_data['baseurl'] . '/size-chart-for-woocommerce/';
        //Create new directory for plugin JSON files store
        if ( !is_dir( $save_path ) ) {
            wp_mkdir_p( $save_path );
        }
        //Remove all previous files
        $files = glob( "{$save_path}/*.json" );
        foreach ( $files as $csv_file ) {
            unlink( $csv_file );
            // phpcs:ignore
        }
        //Save new data to JSON file
        file_put_contents( $save_path . $file_name, $json_data );
        // phpcs:ignore
        return wp_send_json_success( array(
            'message'       => esc_html__( 'Data has been Exported!', 'size-chart-for-woocommerce' ),
            'download_path' => $download_path . $file_name,
        ) );
    }
    
    /**
     * Import size chart data.
     * 
     * @since 1.0.0
     */
    public function scfw_size_chart_import_data_callback()
    {
        check_ajax_referer( 'size_chart_for_wooocmmerce_nonoce', 'security' );
        $file_import_file_args = array(
            'import_file' => array(
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags'  => FILTER_FORCE_ARRAY,
        ),
        );
        $attached_import_files_arr = filter_var_array( $_FILES, $file_import_file_args );
        $chart_id = filter_input( INPUT_POST, 'chartID', FILTER_SANITIZE_NUMBER_INT );
        $import_file = $attached_import_files_arr['import_file']['tmp_name'];
        if ( empty($import_file) ) {
            wp_send_json_error( array(
                'message' => esc_html__( 'Please upload a file to import', 'size-chart-for-woocommerce' ),
            ) );
        }
        $attached_import_files__arr_explode = explode( '.', $attached_import_files_arr['import_file']['name'] );
        $extension = end( $attached_import_files__arr_explode );
        if ( $extension !== 'json' ) {
            wp_send_json_error( array(
                'message' => esc_html__( 'Please upload a valid .json file', 'size-chart-for-woocommerce' ),
            ) );
        }
        WP_Filesystem();
        global  $wp_filesystem ;
        $file_data = $wp_filesystem->get_contents( $import_file );
        if ( !empty($file_data) ) {
            update_post_meta( $chart_id, 'chart-table', $file_data );
        }
        return wp_send_json_success( array(
            'chart_data' => $file_data,
            'message'    => esc_html__( 'Data has been Imported! Reload page in moment.', 'size-chart-for-woocommerce' ),
        ) );
    }
    
    /**
     * Get dynamic promotional bar of plugin
     *
     * @param   String  $plugin_slug  slug of the plugin added in the site option
     * @since    3.9.3
     * 
     * @return  null
     */
    public function scfw_get_promotional_bar( $plugin_slug = '' )
    {
        $promotional_bar_upi_url = SCFW_STORE_URL . 'wp-json/dpb-promotional-banner/v2/dpb-promotional-banner?' . wp_rand();
        $promotional_banner_request = wp_remote_get( $promotional_bar_upi_url );
        //phpcs:ignore
        
        if ( empty($promotional_banner_request->errors) ) {
            $promotional_banner_request_body = $promotional_banner_request['body'];
            $promotional_banner_request_body = json_decode( $promotional_banner_request_body, true );
            echo  '<div class="dynamicbar_wrapper">' ;
            if ( !empty($promotional_banner_request_body) && is_array( $promotional_banner_request_body ) ) {
                foreach ( $promotional_banner_request_body as $promotional_banner_request_body_data ) {
                    $promotional_banner_id = $promotional_banner_request_body_data['promotional_banner_id'];
                    $promotional_banner_cookie = $promotional_banner_request_body_data['promotional_banner_cookie'];
                    $promotional_banner_image = $promotional_banner_request_body_data['promotional_banner_image'];
                    $promotional_banner_description = $promotional_banner_request_body_data['promotional_banner_description'];
                    $promotional_banner_button_group = $promotional_banner_request_body_data['promotional_banner_button_group'];
                    $dpb_schedule_campaign_type = $promotional_banner_request_body_data['dpb_schedule_campaign_type'];
                    $promotional_banner_target_audience = $promotional_banner_request_body_data['promotional_banner_target_audience'];
                    
                    if ( !empty($promotional_banner_target_audience) ) {
                        $plugin_keys = array();
                        
                        if ( is_array( $promotional_banner_target_audience ) ) {
                            foreach ( $promotional_banner_target_audience as $list ) {
                                $plugin_keys[] = $list['value'];
                            }
                        } else {
                            $plugin_keys[] = $promotional_banner_target_audience['value'];
                        }
                        
                        $display_banner_flag = false;
                        if ( in_array( 'all_customers', $plugin_keys, true ) || in_array( $plugin_slug, $plugin_keys, true ) ) {
                            $display_banner_flag = true;
                        }
                    }
                    
                    if ( true === $display_banner_flag ) {
                        
                        if ( 'default' === $dpb_schedule_campaign_type ) {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            
                            if ( empty($banner_cookie_show) && empty($banner_cookie_visible_once) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes', time() + 86400 * 7 );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            
                            if ( !empty($banner_cookie_show) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                
                                if ( empty($banner_cookie) && 'yes' !== $banner_cookie ) {
                                    ?>
                            	<div class="dpb-popup <?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>">
                                    <?php 
                                    
                                    if ( !empty($promotional_banner_image) ) {
                                        ?>
                                        <img src="<?php 
                                        echo  esc_url( $promotional_banner_image ) ;
                                        ?>"/>
                                        <?php 
                                    }
                                    
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo  wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) ) ;
                                    if ( !empty($promotional_banner_button_group) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo  esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ) ;
                                            ?>" target="_blank"><?php 
                                            echo  esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ) ;
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                    	</p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo  esc_attr( $promotional_banner_id ) ;
                                    ?>" data-popup-name="<?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_attr( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            
                            }
                        
                        } else {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            
                            if ( empty($banner_cookie_show) && empty($banner_cookie_visible_once) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            
                            if ( !empty($banner_cookie_show) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                
                                if ( empty($banner_cookie) && 'yes' !== $banner_cookie ) {
                                    ?>
                    			<div class="dpb-popup <?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>">
                                    <?php 
                                    
                                    if ( !empty($promotional_banner_image) ) {
                                        ?>
                                            <img src="<?php 
                                        echo  esc_url( $promotional_banner_image ) ;
                                        ?>"/>
                                        <?php 
                                    }
                                    
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo  wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) ) ;
                                    if ( !empty($promotional_banner_button_group) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo  esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ) ;
                                            ?>" target="_blank"><?php 
                                            echo  esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ) ;
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                        </p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo  esc_attr( $promotional_banner_id ) ;
                                    ?>" data-popup-name="<?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            
                            }
                        
                        }
                    
                    }
                }
            }
            echo  '</div>' ;
        }
    
    }
    
    /**
     * Get and save plugin setup wizard data
     * 
     * @since    3.9.3
     * 
     */
    public function scfw_plugin_setup_wizard_submit()
    {
        check_ajax_referer( 'wizard_ajax_nonce', 'nonce' );
        $survey_list = filter_input( INPUT_GET, 'survey_list', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !empty($survey_list) && 'Select One' !== $survey_list ) {
            update_option( 'scfw_where_hear_about_us', $survey_list );
        }
        wp_die();
    }
    
    /**
     * Send setup wizard data to sendinblue
     * 
     * @since    3.9.3
     * 
     */
    public function scfw_send_wizard_data_after_plugin_activation()
    {
        $send_wizard_data = filter_input( INPUT_GET, 'send-wizard-data', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( isset( $send_wizard_data ) && !empty($send_wizard_data) ) {
            
            if ( !get_option( 'scfw_data_submited_in_sendiblue' ) ) {
                $scfw_where_hear = get_option( 'scfw_where_hear_about_us' );
                $get_user = scfw_fs()->get_user();
                $data_insert_array = array();
                if ( isset( $get_user ) && !empty($get_user) ) {
                    $data_insert_array = array(
                        'user_email'              => $get_user->email,
                        'ACQUISITION_SURVEY_LIST' => $scfw_where_hear,
                    );
                }
                $feedback_api_url = SCFW_STORE_URL . 'wp-json/dotstore-sendinblue-data/v2/dotstore-sendinblue-data?' . wp_rand();
                $query_url = $feedback_api_url . '&' . http_build_query( $data_insert_array );
                
                if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
                    $response = vip_safe_wp_remote_get(
                        $query_url,
                        3,
                        1,
                        20
                    );
                } else {
                    $response = wp_remote_get( $query_url );
                }
                
                
                if ( !is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
                    update_option( 'scfw_data_submited_in_sendiblue', '1' );
                    delete_option( 'scfw_where_hear_about_us' );
                }
            
            }
        
        }
    }

}