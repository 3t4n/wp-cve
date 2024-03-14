<?php
namespace PDFPro\Base;

use PDFPro\Helper\Functions as Utils;

class EnqueueAssets{

    public function register(){
        add_action("wp_enqueue_scripts", [$this, 'publicAssets']);
        add_action( 'admin_enqueue_scripts', [$this, 'adminAssets'] );
        // Media button
        add_action('wp_enqueue_media', [$this, 'pdfp_media_button_js_file']);
        add_action('script_loader_tag', [$this, 'script_loader_tag'], 10, 3);
    }

    /**
     * Enqueue public assets
     */
    public function publicAssets(){
        // wp_enqueue_script('jquery');
        wp_enqueue_style( 'pdfp-public',  PDFPRO_PLUGIN_DIR. 'dist/public.css', array(), PDFPRO_VER );
        wp_register_script('adobe-viewer', 'https://documentcloud.adobe.com/view-sdk/viewer.js', array(), PDFPRO_VER);
        wp_register_script( 'pdfp-public', PDFPRO_PLUGIN_DIR. 'dist/public.js', array(),PDFPRO_VER);
        wp_register_script('dropbox-picker', 'https://www.dropbox.com/static/api/2/dropins.js');

        $option = get_option('fpdf_option', []);

        wp_localize_script('pdfp-public', 'pdfp', [
            'adobeClientKey' => Utils::scramble('encode', Utils::isset($option, 'adobe_client_key', ''))
        ]);
    }

    public function script_loader_tag($tag, $handle, $src){
        if($handle === 'adobe-viewer'){
            return "<script src='https://documentcloud.adobe.com/view-sdk/viewer.js'></script>";
        }
        return $tag;
    }

    /**
     * enqueue admin assets
     **/    
    function adminAssets($hook) {
		$option = get_option('fpdf_option');
        $postType = get_post_type();
        if(in_array($hook, ['admin_page_pdf-poster-pricing-manual', 'pdfposter_page_fpdf-support', 'pdfposter_page_fpdf-settings', 'post.php', 'post-new.php']) || $postType === 'pdfposter'){
            wp_enqueue_script('adobe-viewer', 'https://documentcloud.adobe.com/view-sdk/viewer.js', array(), PDFPRO_VER);
        }
        
        wp_enqueue_script('pdfp-admin', PDFPRO_PLUGIN_DIR.'dist/admin.js', array( 'jquery', 'wp-element' ), PDFPRO_VER, false);
        wp_enqueue_style('pdfp-admin', PDFPRO_PLUGIN_DIR.'dist/admin.css', array(), PDFPRO_VER);


        $current_screen = get_current_screen();
        if ('settings_page_pdf_poster_settings'==$hook){
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
            wp_localize_script('jquery', 'cm_settings', $cm_settings);
            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');
            wp_enqueue_script('pdfp-codemirror', PDFPRO_PLUGIN_DIR.'admin/js/codemirror-init.js', array('jquery'), PDFPRO_VER, true); 
        }

        wp_localize_script('pdfp-admin', 'fpdfAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'gAppId' => Utils::isset($option, 'google_project_number'),
            'gClientId' => Utils::isset($option, 'google_client_id'),
            'gDeveloperKey' => Utils::isset($option, 'google_apikey'),
            'adobeClientKey' => Utils::isset($option, 'adobe_client_key', ''),
            'isPipe' => pdfp_fs()->can_use_premium_code()
        ));
    }

    public function pdfp_media_button_js_file(){
        wp_enqueue_script('pdfp-direct', PDFPRO_PLUGIN_DIR.'admin/js/pdf_button.js', array('jquery'), PDFPRO_VER, true);
    }
}