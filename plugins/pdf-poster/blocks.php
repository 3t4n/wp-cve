<?php
namespace PDFPro\Block;
if(!defined('ABSPATH')) {
    return;
}
use PDFPro\Helper\DefaultArgs;
use PDFPro\Helper\Pipe;
use PDFPro\Model\AdvanceSystem;
use PDFPro\Services\PDFTemplate;


class RegisterBlock{
    protected static $_instance = null;

    function __construct(){
        add_action('init', [$this, 'enqueue_script']);
        add_action('wp_ajax_block_validator', [$this, 'block_validator']);
    }

     /**
     * Create Instance
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function enqueue_script(){
        wp_register_script(	'pdfp-editor', PDFPRO_PLUGIN_DIR.'dist/editor.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'jquery'  ), PDFPRO_VER, true );

        wp_register_style( 'pdfp-editor', PDFPRO_PLUGIN_DIR. 'dist/editor.css' , array(), PDFPRO_VER );

        register_block_type(PDFPRO_PATH.'blocks/pdfposter',  array(
            'editor_script' => 'pdfp-editor',
            'editor_style' => 'pdfp-editor',
            'render_callback' => function($atts){
                // return 'notghin to thide';
                $data = DefaultArgs::parseArgs(AdvanceSystem::getData($atts));
                return PDFTemplate::html($data);
            }
        ));

    
        register_block_type('meta-box/document-embedder', array(
            'editor_script' => 'pdfp-editor',
            'editor_style' => 'pdfp-editor',
            'render_callback' => function($attr, $content){
                ob_start();
                if(isset($attr['selected'])){
                    echo do_shortcode("[pdf id=".esc_attr($attr['selected'])."]");
                }else if(isset($attr['data']['tringle_text'])){
                    echo do_shortcode("[pdf id=".esc_attr($attr['data']['tringle_text'])."]");
                }
                return ob_get_clean();
            }
        ));

        global $pdfp_bs;

        wp_localize_script('pdfp-editor', 'pdfp', [
            'siteUrl' => home_url(),
            'dir' => PDFPRO_PLUGIN_DIR,
            'pipe' => pdfp_fs()->can_use_premium_code(),
            'placeholder' => PDFPRO_PLUGIN_DIR.'img/placeholder.pdf',
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_ajax')
        ]);

        load_plugin_textdomain( 'pdfp', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n' );
        wp_set_script_translations( 'pdfp-editor', 'pdfp', plugin_dir_path( __FILE__ ) . '/i18n' );
    }


    function block_validator(){
        // if()
    }


}

RegisterBlock::instance();

