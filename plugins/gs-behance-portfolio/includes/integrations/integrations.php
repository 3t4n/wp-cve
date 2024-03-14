<?php

namespace GSBEH;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

final class Integrations {

    public function __construct() {

        // Elementor
        if ( apply_filters( 'gs_beh_integration_elementor', true ) ) $this->integration_with_elementor();

        // WP Bakery Visual Composer
        if ( apply_filters( 'gs_beh_integration_wpb_vc', true ) ) $this->integration_with_wpbakery_vc();

        // Gutenberg
        if ( apply_filters( 'gs_beh_integration_gutenberg', true ) ) $this->integration_with_gutenberg();

        // Divi
        if ( apply_filters( 'gs_beh_integration_divi', true ) ) $this->integration_with_divi();

        // Beaver
        if ( apply_filters( 'gs_beh_integration_beaver', true ) ) $this->integration_with_beaver();

        // // Oxygen
        if ( apply_filters( 'gs_beh_integration_oxygen', true ) ) $this->integration_with_oxygen();
        
    }

    public function integration_with_elementor() {
        require_once GSBEH_PLUGIN_DIR . 'includes/integrations/integration-elementor.php';
        Integration_Elementor::get_instance();
    }
    
    public function integration_with_wpbakery_vc() {
        require_once GSBEH_PLUGIN_DIR . 'includes/integrations/integration-wpb-vc.php';
        Integration_WPB_VC::get_instance();
    }

    public function integration_with_gutenberg() {
        require_once GSBEH_PLUGIN_DIR . 'includes/integrations/integration-gutenberg.php';
        Integration_Gutenberg::get_instance();
    }

    public function integration_with_divi() {
        require_once GSBEH_PLUGIN_DIR . 'includes/integrations/integration-divi.php';
        Integration_Divi::get_instance();
    }

    public function integration_with_beaver() {
        require_once GSBEH_PLUGIN_DIR . 'includes/integrations/integration-beaver.php';
        Integration_Beaver::get_instance();
    }

    public function integration_with_oxygen() {
        require_once GSBEH_PLUGIN_DIR . 'includes/integrations/integration-oxygen.php';
        Integration_Oxygen::get_instance();
    }

    public function is_builder_preview() {

        $render = false;

        // For VC
        if ( ! empty( $_GET['vc_editable'] ) ) return true;
    
        // For Elementor
        if ( ( !empty($_GET['action']) && $_GET['action'] == 'elementor' ) || ( !empty($_POST['action']) && $_POST['action'] == 'elementor_ajax' ) ) return true;
    
        // For gutenberg
        if ( !empty($_GET['context']) && $_GET['context'] == 'edit' ) return true;
    
        // Beaver Builder
        if ( isset($_GET['fl_builder_ui_iframe']) || !empty($_POST['fl_builder_data']) ) return true;
    
        // Oxygen Builder
        if ( !empty($_GET['action']) && $_GET['action'] == 'oxy_render_oxy-gs_behance' ) return true;
    
        // Divi Builder
        if ( is_divi_editor() ) return true;
    
        return $render;

    }

}