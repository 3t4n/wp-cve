<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 28/06/2015
 * Time: 14:16
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {

    if ( !defined(__NAMESPACE__ . '\MY_PLUGIN_SETTINGS_PATH'))
        define( __NAMESPACE__ . '\MY_PLUGIN_SETTINGS_INC_PATH', dirname( __FILE__ ) . '/inc' );

    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-settings-base.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-settings-public.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-consts.php');


    abstract class My_Plugin_Public
    {
        /**
         * This class's name
         * @var string
         */
        public static $NAME = __CLASS__;

        protected $settings;

        function __construct(My_Plugin_Settings_Public $public_settings)
        {
            //Loads the settings
            $this->settings = $public_settings;

            //Initialises the class and loads any scripts
            $this->init();
        }

        /**
         * Basic init function to register all required hooks to:
         *  - add the options page
         *  - scripts
         *  - activate/deactivate/uninstall etc.
         */
        function init()
        {
            //Add Scripts
            add_action('wp_enqueue_scripts', array($this, 'my_plugin_enqueue_public_scripts'));

        }

        /**
         * Add each provided value, as long as it exists as a property - i.e ignore others!
         * @param array $arr_values
         */
        private function fill_values(array $arr_values)
        {
            foreach ($arr_values as $label => $value) {
                if (property_exists($this::$NAME, $label))
                    if (isset($arr_values[$label]))
                        $this->$label = $arr_values[$label];
            }
        }

        public function my_plugin_enqueue_public_scripts()
        {
            if (method_exists($this, 'additional_theme_scripts'))
                $this->additional_theme_scripts();

        }
    }
}