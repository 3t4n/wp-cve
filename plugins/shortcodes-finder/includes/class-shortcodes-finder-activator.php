<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    shortcodes-finder
 * @subpackage shortcodes-finder/includes
 * @author     Scribit <wordpress@scribit.it>
 */
class Shortcodes_Finder_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        update_option(SHORTCODES_FINDER_OPTION_VERSION, SHORTCODES_FINDER_VERSION);
		
		// To redirect to plugin page, after its activation, the plugin use an option that will be deleted on admin_init hook.
		add_option( 'activated_plugin', SHORTCODES_FINDER_PLUGIN_SLUG );
    }
}
