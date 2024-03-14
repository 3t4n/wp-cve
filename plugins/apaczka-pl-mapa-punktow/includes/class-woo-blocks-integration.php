<?php
/**
 * Apaczka.pl Mapa Punktów
 *
 * @package Apaczka Mapa Punktów
 * @author  InspireLabs
 * @link    https://inspirelabs.pl/
 */

namespace Apaczka_Points_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Integration with WooCommerce Checkout Block.
 */
class ApaczkaMP_Woo_Blocks_Integration implements IntegrationInterface
{

    /**
     * The name of the integration.
     *
     * @return string
     */
    public function get_name()
    {
        return 'apaczka-mapa-punktow-woo-blocks';
    }

    /**
     * When called invokes any initialization/setup for the integration.
     */
    public function initialize()
    {


        $script_url = APACZKA_POINTS_MAP_DIR_URL . 'public/js/blocks/woo-blocks-integration.js';

        //$style_url = $plugin_data->getPluginCss() . 'woo-blocks-integration.css';

        $dep = array('dependencies' => array('wp-blocks', 'wp-components', 'wp-data', 'wp-element'), 'version' => '1.3.3');

        $script_asset = $dep;

        wp_register_script(
            'apaczka-mapa-punktow-wc-blocks-integration',
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );

    }

    /**
     * Returns an array of script handles to enqueue in the frontend context.
     *
     * @return string[]
     */
    public function get_script_handles()
    {
        return array('apaczka-mapa-punktow-wc-blocks-integration');
    }

    /**
     * Returns an array of script handles to enqueue in the editor context.
     *
     * @return string[]
     */
    public function get_editor_script_handles()
    {
        return array('apaczka-mapa-punktow-wc-blocks-integration');
    }

    /**
     * An array of key, value pairs of data made available to the block on the client side.
     *
     * @return array
     */
    public function get_script_data()
    {
        return [
            'apaczka-mapa-punktow_test' => ''
        ];
    }

    /**
     * Get the file modified time as a cache buster if we're in dev mode.
     *
     * @param string $file Local path to the file.
     * @return string The cache buster value to use for the given file.
     */
    protected function get_file_version($file)
    {
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG && file_exists($file)) {
            return filemtime($file);
        }

        return '1.3.3';
    }


}
