<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_General_Options
{

    public function __construct()
    {
        add_action('uxgallery_save_general_options', array($this, 'save_options'));
    }

    /**
     * Loads General options page
     */
    public function load_page()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'Options_gallery_styles') {
            if (isset($_GET['task'])) {
                if ($_GET['task'] == 'save') {
                    do_action('uxgallery_save_general_options');
                }
            } else {
                $this->show_page();
            }
        }
    }

    /**
     * Shows General options page
     */
    public function show_page()
    {
        $gallery_default_params = uxgallery_get_general_options();
        $uxgallery_get_option = array();
        foreach ( $gallery_default_params as $name => $value ) {
            if( strpos( $name, 'uxgallery_' ) === false ){
                $uxgallery_get_option[ 'uxgallery_'.$name ] = get_option( 'uxgallery_'.$name );
            }
            else {
                $uxgallery_get_option[ $name ] = get_option( $name );
            }
        }
        require(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'general-options-html.php');
    }

    /**
     * Save General Options
     */
    public function save_options()
    {
        if ( !isset( $_REQUEST['gen_options_nonce'] ) || ! wp_verify_nonce( $_REQUEST['gen_options_nonce'], 'uxgallery_nonce_save_gen_options' ) ) {
            wp_die( 'Security check fail' );
        }
        if (isset($_POST['params'])) {
            $params = $_POST['params'];
            foreach ($params as $name => $value) {
                update_option($name, wp_unslash(sanitize_text_field($value)));
            }
            ?>
            <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
            <?php
        }
        $this->show_page();
    }
}