<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Plugin Directory URI.
 */
define('WOO_DISCOUNT_URI', untrailingslashit(plugin_dir_url(__FILE__)));

Class WDRV1Deprecated {

    /**
     * Init events
     * */
    public function init(){
        add_action('admin_menu', array(__CLASS__, 'adminMenu'));
    }

    /**
     * Load admin menu
     * */
    public static function adminMenu(){
        if (!is_admin()) return;
        global $submenu;
        if (isset($submenu['woocommerce'])) {
            add_submenu_page(
                'woocommerce',
                __('Discount Rules', 'woo-discount-rules'),
                __('Discount Rules', 'woo-discount-rules'),
                'manage_woocommerce', 'woo_discount_rules',
                array(__CLASS__, 'loadWDRV1DeprecatedHTML')
            );
        }
    }

    /**
     * Load HTML content
     * */
    public static function loadWDRV1DeprecatedHTML(){
        include_once(__DIR__ . "/menu-html.php");
    }

    /**
     * Create nonce for v1
     * @param int $action
     * @return mixed
     */
    public static function createNonce($action = -1){
        return wp_create_nonce($action);
    }

    /**
     * Verify nonce
     * @param $nonce
     * @param int $action
     * @return bool
     */
    protected static function verifyNonce($nonce, $action = -1 ){
        if (wp_verify_nonce($nonce, $action)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * check valid nonce for v1
     * @param $method
     * @param null $wdr_nonce
     * @return bool
     */
    public static function validateRequest($method, $wdr_nonce = null){
        if($wdr_nonce === null){
            if(isset($_REQUEST['wdr_nonce']) && !empty($_REQUEST['wdr_nonce'])){
                if(self::verifyNonce(wp_unslash($_REQUEST['wdr_nonce']), $method)){
                    return true;
                }
            }
        } else {
            if(self::verifyNonce(wp_unslash($wdr_nonce), $method)){
                return true;
            }
        }

        die(__('Invalid token', 'woo-discount-rules'));
    }
}

(new WDRV1Deprecated())->init();