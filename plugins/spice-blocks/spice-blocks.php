<?php 
/**
* Plugin Name:          Spice Blocks
* Plugin URI:           https://spiceblocks.com/
* Description:          Spice Blocks Plugin is a block plugin that is compatible with all WordPress themes. In plugin block controls are given, that's help to develop a beautiful WordPress theme.
* Version:              1.3.2
* Requires at least:    5.3
* Requires PHP:         5.2
* Tested up to:         6.4.2
* Author:               Spicethemes
* Author URI:           https://spicethemes.com
* License:              GPLv2 or later
* License URI:          https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:          spice-blocks
* Domain Path:          /languages
*/

if ( ! function_exists( 'sb_fs' ) ) {
    // Create a helper function for easy SDK access.
    function sb_fs() {
        global $sb_fs;

        if ( ! isset( $sb_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $sb_fs = fs_dynamic_init( array(
                'id'                  => '11560',
                'slug'                => 'spice-blocks',
                'type'                => 'plugin',
                'public_key'          => 'pk_cc4dac906a3ad63fe8d670b7c85eb',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'spice-blocks',
                    'account'        => false,
                ),
            ) );
        }

        return $sb_fs;
    }

    // Init Freemius.
    sb_fs();
    // Signal that SDK was initiated.
    do_action( 'sb_fs_loaded' );
}

if( !defined( 'ABSPATH' ) ) {exit(); }
define('SPICE_BLOCKS_VERSION', 'self::VERSION');
define('SPICE_BLOCKS_PLUGIN_PATH',trailingslashit(plugin_dir_path(__FILE__)));
define('SPICE_BLOCKS_PLUGIN_URL',trailingslashit(plugins_url('/',__FILE__)));
define('SPICE_BLOCKS_PLUGIN_UPLOAD',trailingslashit( wp_upload_dir()['basedir'] ) );
require_once SPICE_BLOCKS_PLUGIN_PATH.'/inc/block-import.php';

add_action( 'admin_menu', 'spice_blocks_options_page',999 );
if(!function_exists('spice_blocks_options_page')){
    function spice_blocks_options_page() {
        add_menu_page(
            esc_html__( 'Spice Blocks', 'spice-blocks' ),
            esc_html__( 'Spice Blocks', 'spice-blocks' ),
            'manage_options',
            'spice-blocks',
            function() { require_once SPICE_BLOCKS_PLUGIN_PATH.'/admin/view.php'; },
            'dashicons-groups',
            20
        );
        add_submenu_page(
            'spice-blocks',
            esc_html__( 'Spice Blocks Panel', 'spice-blocks' ),
            esc_html__( 'Spice Blocks Panel', 'spice-blocks' ),
            'manage_options',
            'spice-blocks',
            function() { require_once SPICE_BLOCKS_PLUGIN_PATH.'/admin/view.php'; },
            1
        );
    }
}

//Enqueue Style & Script for admin

add_action('admin_enqueue_scripts','spice_blocks_style_script');

if(!function_exists('spice_blocks_style_script')){
    function spice_blocks_style_script(){
        $id = $GLOBALS['hook_suffix'];
        if('toplevel_page_spice-blocks'==$id){
            wp_enqueue_style( 'spice-blocks-about-css', SPICE_BLOCKS_PLUGIN_URL . 'admin/assets/css/about.css' );
            wp_enqueue_style( 'spice-blocks-all-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css');
        }
    }
}

final class Spice_Blocks{

    /** 
     * Construct Function
     */
    private  function __construct(){
        add_action('plugins_loaded',[$this,'init_plugin']);
    }

    /**
     * Singletone Instance
     */
    public static function init(){
        static $instance=false;
        if(!$instance){
            $instance=new self();
        }
        return $instance;

    }

    /**
     * Plugin Init
     */
    public function init_plugin(){
       $this->enqueue_scripts();
    }

    /**
     * Enqueue Script
     */
    public function enqueue_scripts(){
        add_action('enqueue_block_editor_assets',[$this,'register_block_editor_assets']);
        add_action('enqueue_block_assets',[$this,'register_block_assets']);
        add_action('admin_enqueue_scripts',[$this,'register_admin_scripts']);
        add_action('init',[$this,'register_block']);        
        add_action('init',[$this,'spice_blocks_load_plugin_textdomain']);
    }

    /**
     * Register Block Editor Assets
     */   
    public function register_block_editor_assets(){
        wp_enqueue_script(
            'prefix-spice-blocks',
            SPICE_BLOCKS_PLUGIN_URL.'/build/index.js',
            [
                'wp-blocks',
                'wp-editor',
                'wp-i18n',
                'wp-element',
                'wp-components',
                'wp-data',

            ]
        );
    }
    public function register_block_assets(){

        wp_enqueue_style(
           'spice-blocks-editor-css',
           SPICE_BLOCKS_PLUGIN_URL.'assets/css/editor.css',
           false,
           'all'
        );
        
        wp_enqueue_style(
            'spice-blocks-animate',
            SPICE_BLOCKS_PLUGIN_URL.'assets/css/animate.css'
        );  
        wp_enqueue_script(
            'spice-blocks-accordion',
            SPICE_BLOCKS_PLUGIN_URL.'assets/js/accordion.js',array('jquery')
        );
        wp_enqueue_script(
            'spice-blocks-fontawesome',
            SPICE_BLOCKS_PLUGIN_URL.'assets/js/fontawesome.js'
        );
        if( ! is_admin() ){
            wp_enqueue_style(
                'spice-blocks-img-compare',
                SPICE_BLOCKS_PLUGIN_URL.'assets/css/image-compare-viewer.css'
            );
            wp_enqueue_script(
                'spice-blocks-image-compare-viewer',
                SPICE_BLOCKS_PLUGIN_URL.'assets/js/image-compare-viewer-min.js',array('jquery'), rand(), true
            );
            wp_enqueue_script(
                'spice-blocks-image-compare-custom',
                SPICE_BLOCKS_PLUGIN_URL.'assets/js/image-compare-custom.js',array('jquery'), rand(), true
            );
        }
        wp_enqueue_style(
           'spice-blocks-style',
           SPICE_BLOCKS_PLUGIN_URL.'assets/css/style.css',
           [],
           false,
           'all'
        );
       
        wp_enqueue_script('animate-js', SPICE_BLOCKS_PLUGIN_URL . 'assets/js/animation/animate.js');
        wp_enqueue_script('wow-js', SPICE_BLOCKS_PLUGIN_URL . 'assets/js/animation/wow.min.js');
    }
    
    /**
     * Register Admin Scripts
     */   
    public function register_admin_scripts(){  

        wp_enqueue_script(
           'spice-blocks-editor-js',
           SPICE_BLOCKS_PLUGIN_URL.'assets/js/editor.js',
           array('jquery'),
           rand(),
           true
        );
        wp_localize_script('spice-blocks-editor-js','plugin',['pluginpath' => SPICE_BLOCKS_PLUGIN_URL,'plugindir' => SPICE_BLOCKS_PLUGIN_UPLOAD ]);
        wp_enqueue_script('spice-blocks-editor-js');         
        
        wp_enqueue_style(
           'spice-blocks-fonticonpicker-material',
           SPICE_BLOCKS_PLUGIN_URL.'assets/css/fonticonpicker/fonticonpicker.material-theme.react.css',
           ['wp-edit-blocks'],
               false,
               'all'
        );
        wp_enqueue_style(
           'spice-blocks-fonticonpicker-base',
           SPICE_BLOCKS_PLUGIN_URL.'assets/css/fonticonpicker/fonticonpicker.base-theme.react.css',
           ['wp-edit-blocks'],
               false,
               'all'
        );
    }


   /**
    * Register Blocks
    */
    public function register_block(){      
        register_block_type('spice-blocks/spice-heading',[
           'editor_style'=>'spice-blocks-editor-css',
        ]);

        register_block_type('spice-blocks/spice-text-editor',[
           'editor_style'=>'spice-blocks-editor-css',
        ]);

        register_block_type('spice-blocks/spice-divider',[
           'editor_style'=>'spice-blocks-editor-css',
        ]);

        register_block_type('spice-blocks/spice-spacer',[
           'editor_style'=>'spice-blocks-editor-css',
        ]);

        register_block_type('spice-blocks/spice-button',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'spice-blocks-editor',
        ]);

        register_block_type('spice-blocks/spice-icon',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'spice-blocks-editor',
        ]);

        register_block_type('spice-blocks/spice-section',[
           'style'=> 'wp-block-columns',
           'editor_style'=>'wp-block-columns-editor',
        ]); 

        register_block_type('spice-blocks/spice-image',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'wp-block-columns-editor',
        ]);

        register_block_type('spice-blocks/spice-blockquote',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'wp-block-columns-editor',
        ]);

        register_block_type('spice-blocks/spice-cta',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'wp-block-columns-editor',
        ]);

        register_block_type('spice-blocks/spice-timeline',[
            'style'=> 'spice-blocks-public',
            'editor_style'=>'spice-blocks-editor-css',
         ]);

         register_block_type('spice-blocks/spice-accordion',[
            'style'=> 'spice-blocks-public',
            'editor_style'=>'spice-blocks-editor-css',
         ]);

         register_block_type('spice-block/spice-icon-list',[
            'style'=> 'spice-blocks-public',
            'editor_style'=>'spice-blocks-editor-css',
         ]);

         register_block_type('spice-block-plus/img-comparison',[
            'style'=> 'spice-blocks-public',
            'editor_style'=>'spice-blocks-editor-css',
         ]);
         
         register_block_type('spice-blocks/spice-gallery',[
            'style'=> 'spice-blocks-public',
            'editor_style'=>'spice-blocks-editor-css',
         ]);

         register_block_type('spice-blocks/spice-img-accordion',[
            'style'=> 'spice-blocks-public',
            'editor_style'=>'spice-blocks-editor-css',
         ]);

         register_block_type('spice-blocks/spice-progress-bar',[
            'style'=> 'spice-blocks-public',
            'editor_style'=>'spice-blocks-editor-css',
         ]);

         register_block_type('spice-blocks/spice-service-box',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'wp-block-columns-editor',
        ]);

        register_block_type('spice-blocks/spice-social-icon',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'spice-blocks-editor-css',
        ]);

        register_block_type('spice-block/spice-socials',[
           'style'=> 'spice-blocks-style',
           'editor_style'=>'spice-blocks-editor-css',
        ]);  
    }      

    /**
     * Load the localisation file.
     */
    public function spice_blocks_load_plugin_textdomain() {
        load_plugin_textdomain( 'spice-blocks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

}

/** 
 * Init
 */

function spice_blocks_run_plugin(){
   return Spice_Blocks::init();

}
spice_blocks_run_plugin();

//Add Category 
function spice_blocks_custom_block_category( $spice_blocks_categories ) {
    return array_merge(
        array(
            array(
                'slug' => 'spice-blocks',
                'title' => __( 'Spice Blocks', 'spice-blocks' ),
            ),
        ),
        $spice_blocks_categories
    );
}

if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
    add_filter( 'block_categories_all', 'spice_blocks_custom_block_category' );
} else {
    add_filter( 'block_categories', 'spice_blocks_custom_block_category', 10, 2 );
}

add_filter( 'body_class', 'spice_block_body_class' );
function spice_block_body_class( $classes ) {
        $classes[] = 'spice-block';
    return $classes;
}


if(isset($_POST['download_page'])){
    function spice_blocks_new($id){        
        if(empty($_GET['post'])){
            echo '<script type="text/javascript">
                    window.onload = function () { alert("Saved Page Firstly"); }
                </script>';
            return 0;
        }else{
            $post_id = $_GET['post'];
        }
        error_reporting(0);  
        $query = new WP_Query(  array( 'page_id' => $post_id ) );
        while( $query->have_posts() ) : $query->the_post();        
        $posts= [
                '__file'=> "Spice_Blocks_Export",
                'version'=> 2,
                'content' => get_the_content()
            ];
        endwhile;
        

        $data=json_encode($posts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $filename='WP-POST-'.$post_id.'.json';
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Pragma: no-cache');
        echo $data;
        exit();
    }
    add_action( 'init', 'spice_blocks_new' );
}

function spice_blocks_custom_template_replace() {
    if(!file_exists(get_template_directory().'/theme.json')){
        $plugin_dir_left = plugin_dir_path( __FILE__ ) . 'inc/template/spice-blocks-full-width-template.php';
        $theme_dir_left = get_stylesheet_directory() . '/spice-blocks-full-width-template.php';
        if (!copy($plugin_dir_left, $theme_dir_left)) {
            echo "failed to copy $plugin_dir_left to $theme_dir_left...\n";
        }
    }
}
add_action( 'wp_head', 'spice_blocks_custom_template_replace' );
