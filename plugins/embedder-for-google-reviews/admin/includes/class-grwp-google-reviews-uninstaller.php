<?php
/**
 * Fired during plugin deletion
 *
 * @link       test
 * @since      1.0.0
 *
 * @package    Google_Reviews
 * @subpackage Google_Reviews/includes
 */

/**
 * Fired during plugin deletion.
 *
 * This class defines all code necessary to run during the plugin's deletion.
 *
 * @since      1.3.7
 * @package    Google_Reviews
 * @subpackage Google_Reviews/includes
 * @author     David Maucher <hallo@maucher-online.com>
 */
class GRWP_Google_Reviews_Uninstaller {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function uninstall() {

        if (get_option('gr_latest_results')) {
            delete_option('gr_latest_results');
        }

        if (get_option('gr_latest_results_free')) {
            delete_option('gr_latest_results_free');
        }

    }

}
