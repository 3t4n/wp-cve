<?php
namespace PDFPro\Model;

class Metabox{

    protected static $_instance = null;

    /**
     * construct function
     */
    public function __construct(){
        if(is_admin()){
            add_action( 'add_meta_boxes', [$this, 'myplugin_add_meta_box'] );
            // add_action( 'wp_dashboard_setup', [$this, 'pdfp_add_dashboard_widgets'] );
        }
    }

    /**
     * Create instance function
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * register metabox
     */
    function myplugin_add_meta_box() {
        add_meta_box(
            'Shortcode',
            __( 'New Feature ! Quick Embed', 'pdfp' ),
            [$this, 'pdfp_pro_shortcode_wid'],
            'pdfposter',
            'side',
            'default'
        );
    }

    function pdfp_pro_shortcode_wid(){
        $shortcode="[pdf_embed url='your_file_url']";
        echo 'Now you can embed pdf without listing ! just use the Embed shortCode below, and start saving your time.';
        echo '<br/><br/><input type="text" style="font-size: 12px; border: none; box-shadow: none; padding: 4px 8px; width:100%; background:#1e8cbe; color:white;"  onfocus="this.select();" readonly="readonly"  value="'.esc_attr($shortcode).'" /><br/><br/>';
        echo '<p><a class="button button-primary button-large" href="options-general.php?page=pdf_poster_settings" target="_blank">ShortCode Global Settings</a></p>';
    }
}
Metabox::instance();