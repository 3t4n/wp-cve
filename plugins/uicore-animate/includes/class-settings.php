<?php
namespace UiCoreAnimate;

defined('ABSPATH') || exit();

/**
 * UiCore Utils Functions
 */
class Settings
{

    private static $instance;
    private static $module_name = 'uicore_animate_options';


    public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


    /**
     * Constructor function to initialize hooks
     *
     * @return void
     */
    public function __construct()
    {
        \add_filter('uicore_extra_settings', [$this, 'extra_settings']);
    }


    /**
     * Adds extra settings to the given list of options.
     *
     * @param array $list The list of options to add the extra settings to.
     * @return array The updated list of options with the extra settings.
     */
    function extra_settings($list)
    {
        return \wp_parse_args(
            $list, 
            [
                self::$module_name => self::get_default_settings(),
            ]
        );
    }

    /**
     * Retrieves the default settings for the UI Core Animate plugin.
     *
     * @param string|null $key The specific setting key to retrieve. If null, returns the entire settings list.
     * @return mixed The value of the specified setting key, or the entire settings list if $key is null.
     */
    static function get_default_settings($key = null)
    {
        $list = [		
            'uianim_style'              => [
                'value'     => 'style1',
                'name'      => 'Creative',
            ],
            'uianim_scroll'             => 'false',
            'uianim_disable'            => 'false',
            'animations_page'		    => 'none',
            'animations_page_duration' 	=> 'normal',
			'animations_page_color' 	=> 'Primary',
        ];

        if($key){
            return isset($list[$key]) ? $list[$key] : '';
        }

        return $list;
    }


    /**
     * Retrieves the value of a specific option from the uicore_animate_options array.
     *
     * @param string $option_name The name of the option to retrieve.
     * @return mixed The value of the option if it exists, otherwise the default setting for the option.
     */
    static function get_option($option_name)
    {
        $options = \get_option(self::$module_name, []);
        return isset($options[$option_name]) ? $options[$option_name] : self::get_default_settings($option_name);
    }

    /**
     * Update the specified option with the given value.
     *
     * @param string $option_name The name of the option to update.
     * @param mixed $value The new value for the option.
     * @return void
     */
    static function update_option($option_name, $value)
    {
        $options = \get_option(self::$module_name, []);

        //check if the value is different from default
        if($value == self::get_default_settings($option_name)){
            unset($options[$option_name]);
        } else {
            $options[$option_name] = $value;
        }
        \update_option(self::$module_name, $options);
    }

}
new Settings();