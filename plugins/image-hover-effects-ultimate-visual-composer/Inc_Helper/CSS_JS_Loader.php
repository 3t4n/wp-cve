<?php

namespace OXI_FLIP_BOX_PLUGINS\Inc_Helper;

/**
 *
 * @author biplo
 */
trait CSS_JS_Loader
{



    public function loader_font_familly_validation($data = [])
    {
        foreach ($data as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
    }

   

    public function admin_home()
    {
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery.dataTables.min', OXI_FLIP_BOX_URL . 'asset/backend/js/jquery.dataTables.min.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('dataTables.bootstrap.min', OXI_FLIP_BOX_URL . 'asset/backend/js/dataTables.bootstrap.min.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
    }
    public function str_replace_first($from, $to, $content)
    {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $content, 1);
    }

    public function admin_css_loader()
    {
        $this->admin_css();
        $this->admin_js();
    }

    public function admin_css()
    {
        $this->loader_font_familly_validation(['Bree+Serif', 'Source+Sans+Pro']);
        wp_enqueue_style('oxilab-flip-box-bootstrap', OXI_FLIP_BOX_URL . 'asset/backend/css/bootstrap.min.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_style('font-awsome.min', OXI_FLIP_BOX_URL . 'asset/frontend/css/font-awsome.min.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_style('oxilab-admin-css', OXI_FLIP_BOX_URL . 'asset/backend/css/admin.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
    }
   

    public function admin_elements_frontend_loader()
    {
        $this->admin_css_loader();
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_style('jquery.minicolors', OXI_FLIP_BOX_URL . 'asset/backend/css/minicolors.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('jquery.minicolors', OXI_FLIP_BOX_URL . 'asset/backend/js/minicolors.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_style('fontawesome-iconpicker', OXI_FLIP_BOX_URL . 'asset/backend/css/fontawesome-iconpicker.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('fontawesome-iconpicker', OXI_FLIP_BOX_URL . 'asset/backend/js/fontawesome-iconpicker.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_style('jquery.fontselect', OXI_FLIP_BOX_URL . 'asset/backend/css/jquery.fontselect.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('jquery.bootstrap-growl', OXI_FLIP_BOX_URL . 'asset/backend/js/jquery.bootstrap-growl.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('oxi-flip-box-addons-vendor', OXI_FLIP_BOX_URL . 'asset/backend/js/vendor.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        $this->admin_media_scripts();
    }
     /**
     * Admin Media Scripts.
     * Most of time using into Style Editing Page
     *
     * @since 2.0.0
     */
    public function admin_media_scripts()
    {
        wp_enqueue_media();
        wp_register_script('oxi-flip-box_media_scripts', OXI_FLIP_BOX_URL . 'asset/backend/js/media-uploader.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('oxi-flip-box_media_scripts');
    }
    public function admin_js()
    {
        wp_enqueue_script("jquery");
        wp_enqueue_script('oxilab-popper', OXI_FLIP_BOX_URL . 'asset/backend/js/popper.min.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('oxilab-bootstrap', OXI_FLIP_BOX_URL . 'asset/backend/js/bootstrap.min.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
    }
}
