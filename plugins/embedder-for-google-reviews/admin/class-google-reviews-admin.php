<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       test
 * @since      1.0.0
 *
 * @package    Google_Reviews
 * @subpackage Google_Reviews/admin
 */
class GRWP_Google_Reviews_Admin
{
    private  $google_reviews_options ;
    private  $plugin_name ;
    private  $version ;
    private  $dir ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->google_reviews_options = get_option( 'google_reviews_option_name' );
        $this->dir = plugin_dir_path( __FILE__ );
        add_action( 'admin_menu', array( $this, 'gr_add_plugin_pages' ) );
        add_action( 'admin_init', array( $this, 'google_reviews_page_init' ) );
        new GRWP_Free_API_Service();
        new GRWP_Free_API_Service_Advanced();
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        require_once $this->dir . '../public/includes/allowed-html.php';
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            'admin-' . $this->plugin_name,
            GR_PLUGIN_DIR_URL . 'dist/css/google-reviews-admin.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'admin-' . $this->plugin_name,
            GR_PLUGIN_DIR_URL . 'dist/js/admin-bundle.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        
        if ( isset( $this->google_reviews_options['reviews_language_3'] ) ) {
            wp_localize_script( 'admin-' . $this->plugin_name, 'js_global', array(
                'wp_ajax_url' => admin_url( 'admin-ajax.php' ),
                'language'    => $this->google_reviews_options['reviews_language_3'],
            ) );
        } else {
            wp_localize_script( 'admin-' . $this->plugin_name, 'js_global', array(
                'wp_ajax_url' => admin_url( 'admin-ajax.php' ),
                'language'    => 'en',
            ) );
        }
        
        // Set wp base url for script calls in local environments
        add_action( 'admin_footer', [ $this, 'echo_js_wp_url' ] );
    }
    
    public function echo_js_wp_url()
    {
        ?>
        <script>
            window.wp_base_url = "<?php 
        echo  get_site_url() ;
        ?>";
        </script>
        <?php 
    }
    
    /**
     * Add menu pages to backend
     */
    public function gr_add_plugin_pages()
    {
        // Parent for free and pro version
        new GRWP_Global_Menu_Pages();
        new GRWP_Free_Menu_Pages();
    }
    
    /**
     * Register settings, sections and option fields
     */
    public function google_reviews_page_init()
    {
        // Global settings
        new GRWP_Global_Settings();
        new GRWP_Free_Settings();
    }

}