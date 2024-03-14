<?php
/**
 * Plugin class logic goes here
 * Copyright 2015 SEEDPROD LLC (email : john@seedprod.com, twitter : @seedprod)
 */
class SEED_S404F{

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

	private $landing_page_rendered = false;

	function __construct(){

			global $seed_s404f;
			extract($seed_s404f);

            // Actions & Filters if the landing page is active or being previewed
            if(((!empty($status) && $status === '1') || (!empty($status) && $status === '2'))  || (isset($_GET['seed_s404f_preview']) && $_GET['seed_s404f_preview'] == 'true')){
            	if(function_exists('bp_is_active')){
                    add_action( 'template_redirect', array(&$this,'render_landing_page'),9);
                }else{
                    add_action( 'template_redirect', array(&$this,'render_landing_page'));
                    //add_action( 'init', array(&$this,'clear_cache') );
                }
            }
    }



    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
    * Get Font Family
    */
   public static function get_font_family($font){
       $fonts                    = array();
       $fonts['_arial']          = 'Helvetica, Arial, sans-serif';
       $fonts['_arial_black']    = 'Arial Black, Arial Black, Gadget, sans-serif';
       $fonts['_georgia']        = 'Georgia,serif';
       $fonts['_helvetica_neue'] = '"Helvetica Neue", Helvetica, Arial, sans-serif';
       $fonts['_impact']         = 'Charcoal,Impact,sans-serif';
       $fonts['_lucida']         = 'Lucida Grande,Lucida Sans Unicode, sans-serif';
       $fonts['_palatino']       = 'Palatino,Palatino Linotype, Book Antiqua, serif';
       $fonts['_tahoma']         = 'Geneva,Tahoma,sans-serif';
       $fonts['_times']          = 'Times,Times New Roman, serif';
       $fonts['_trebuchet']      = 'Trebuchet MS, sans-serif';
       $fonts['_verdana']        = 'Verdana, Geneva, sans-serif';

       if(!empty($fonts[$font])){
           $font_family = $fonts[$font];
       }else{
           $font_family = 'Helvetica Neue, Arial, sans-serif';
       }

       echo $font_family;
   }


    /**
     * Display the default template
     */
    static function get_default_template(){
        $file = file_get_contents(SEED_S404F_PLUGIN_PATH.'/themes/default/index.php');
        return $file;
    }



    /**
     * Display the coming soon page
     */
    function render_landing_page() {
        // Get Settings
        global $seed_s404f;
        extract($seed_s404f);
        $o = $seed_s404f;



        // Check if Preview
        $is_preview = false;
        if ((isset($_GET['seed_s404f_preview']) && $_GET['seed_s404f_preview'] == 'true')) {
            //show_admin_bar( false );
            $is_preview = true;
        }

        if($is_preview == false){
            if(!is_404()){
                return false;
            }
        }



        if(empty($_GET['seed_s404f_preview'])){
            $_GET['seed_s404f_preview'] = false;
        }


        // Set Headers
        if($status == '2'){
            if(!empty($redirect_url)){
                wp_redirect( $redirect_url, 301 );
                exit;
            }
        }else{
            header("HTTP/1.0 404 Not Found");
        }

        // Use 404.php
        $s404f_404_file = WP_CONTENT_DIR."/404.php";
        if(!empty($enable_404_php) and file_exists($s404f_404_file)){
            return $s404f_404_file;
        }

        do_action('seed_s404f_pre_render');


        // Render Landing Page
        if ( empty($template) ) {
                $templates = new Seed_S404F_Template_Loader;
                if(!empty($theme) && $theme != 'default' ){
                    if(file_exists(apply_filters('seed_s404f_themes_path',SEED_S404F_PLUGIN_PATH).'index.php')){
                         include(apply_filters('seed_s404f_themes_path',SEED_S404F_PLUGIN_PATH).'index.php');
                         exit();
                    }else{
                        $templates->get_template_part( 'default/index' );
                        exit();
                    }

                }else{
                    $templates->get_template_part( 'default/index' );
                    exit();
                }
        } else {
            echo do_shortcode($template);
            exit();
        }
    }
}
