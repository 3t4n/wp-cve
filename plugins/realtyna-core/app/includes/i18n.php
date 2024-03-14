<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('RTCORE_i18n')):

/**
 * RTCORE i18n Class.
 *
 * @class RTCORE_i18n
 * @version	1.0.0
 */
class RTCORE_i18n extends RTCORE_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function init()
    {
        // Register Language Files
        add_action('plugins_loaded', array($this, 'load_languages'));
	}

    public function load_languages()
    {
        // RTCORE File library
        $file = new RTCORE_File();

        // Get current locale
        $locale = apply_filters('plugin_locale', get_locale(), 'realtyna-core');

        // WordPress language directory /wp-content/languages/realtyna-core-en_US.mo
        $language_filepath = WP_LANG_DIR.'/realtyna-core-'.$locale.'.mo';

        // If language file exists on WordPress language directory use it
        if($file->exists($language_filepath))
        {
            load_textdomain('realtyna-core', $language_filepath);
        }
        // Otherwise use RTCORE plugin directory /path/to/plugin/languages/realtyna-core-en_US.mo
        else
        {
            load_plugin_textdomain('realtyna-core', false, dirname(RTCORE_BASENAME).'/i18n/languages/');
        }
    }
}

endif;