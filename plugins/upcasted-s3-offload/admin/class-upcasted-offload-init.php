<?php

/**
 * Class Upcasted_S3_Offload
 */
class Upcasted_S3_Offload_Init
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Upcasted_S3_Offload_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    protected  $cloudApplication ;
    /**
     * @var null|Upcasted_S3_Offload_Init
     */
    private static  $instance = null ;
    /**
     * @return Upcasted_S3_Offload_Init
     */
    public static function getInstance() : Upcasted_S3_Offload_Init
    {
        if ( self::$instance == null ) {
            self::$instance = new Upcasted_S3_Offload_Init();
        }
        return self::$instance;
    }
    
    /**
     * Upcasted_S3_Offload_Init constructor.
     */
    public function __construct()
    {
        $this->load_dependencies();
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Upcasted_S3_Offload_Loader. Orchestrates the hooks of the plugin.
     * - Upcasted_S3_Offload_i18n. Defines internationalization functionality.
     * - Upcasted_S3_Offload_Admin. Defines all hooks for the admin area.
     * - Upcasted_S3_Offload_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        $this->loader = new Upcasted_S3_Offload_Loader();
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    public function define_admin_hooks()
    {
        try {
            $this->cloudApplication = CloudApplication::getInstance();
            
            if ( null !== $this->cloudApplication ) {
                $this->loader->add_filter(
                    'wp_generate_attachment_metadata',
                    $this->cloudApplication,
                    'upcasted_generate_attachment_metadata',
                    10,
                    3
                );
                $this->loader->add_filter(
                    'wp_update_attachment_metadata',
                    $this->cloudApplication,
                    'upcasted_update_attachment_metadata',
                    10,
                    2
                );
                $this->loader->add_filter( 'image_make_intermediate_size', $this->cloudApplication, 'upcasted_image_make_intermediate_size' );
                $this->loader->add_filter(
                    'sanitize_file_name',
                    $this->cloudApplication,
                    'upcasted_append_duplicate_name',
                    10
                );
                $this->loader->add_filter(
                    'get_attached_file',
                    $this->cloudApplication,
                    'upcasted_get_attached_file',
                    10,
                    2
                );
                $this->loader->add_filter(
                    'wp_get_attachment_url',
                    $this->cloudApplication,
                    'upcasted_get_attachment_url',
                    99,
                    2
                );
                $this->loader->add_filter(
                    'wp_get_attachment_thumb_url',
                    $this->cloudApplication,
                    'upcasted_get_attachment_url',
                    99,
                    2
                );
                $this->loader->add_filter(
                    'wp_calculate_image_srcset',
                    $this->cloudApplication,
                    'upcasted_calculate_image_srcset',
                    9,
                    5
                );
                $this->loader->add_action( 'delete_attachment', $this->cloudApplication, 'upcasted_delete_attachment' );
                $this->loader->run();
            }
        
        } catch ( Exception $exception ) {
            echo  $exception->getMessage() ;
        }
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

}