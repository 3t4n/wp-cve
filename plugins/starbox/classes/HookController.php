<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

/**
 * The class handles the actions in WP
 */
class ABH_Classes_HookController {

    /** @var array the WP actions list from admin */
    private $admin_hooks = array();
    private $front_hooks = array();
    private $block_hooks = array();
    private static $shortCodesSet = false;

    public function __construct() {
        $this->admin_hooks = array(
            'init' => 'admin_init',
            'head' => 'admin_head',
            'footer' => 'admin_footer',
            // --
            'wmenu' => '_admin_menu',
            'menu' => 'admin_menu',
            'submenu' => 'add_submenu_page',
            'loaded' => 'plugins_loaded',
            'scripts' => 'admin_enqueue_scripts',
            'notices' => 'admin_notices',
        );
        $this->front_hooks = array(
            // --
            'frontinit' => 'init',
            'fronthead' => 'wp_head',
            'frontcontent' => 'the_content',
            'frontwidget' => 'widget_text',
            'frontfooter' => 'wp_footer',
        );
        $this->block_hooks = array('getContent' => 'getContent');
    }

    /**
     * Calls the specified action in WP
     * @param oject $instance The parent class instance
     *
     * @return void
     */
    public function setHooks($instance) {
        if (is_admin()) {
            $this->setAdminHooks($instance);
        } else {
            $this->setFrontHooks($instance);
        }
    }

    /**
     * Calls the specified action in WP
     * @param oject $instance The parent class instance
     *
     * @return void
     */
    public function setAdminHooks($instance) {
        if (!is_admin())
            return;
        /* for each admin action check if is defined in class and call it */
        foreach ($this->admin_hooks as $hook => $value) {

            if (is_callable(array($instance, 'hook' . ucfirst($hook)))) {
                //call the WP add_action function
                add_action($value, array($instance, 'hook' . ucfirst($hook)));
            }
        }
    }

    /**
     * Calls the specified action in WP
     * @param oject $instance The parent class instance
     *
     * @return void
     */
    public function setFrontHooks($instance) {

        /* for each admin action check if is defined in class and call it */
        foreach ($this->front_hooks as $hook => $value) {

            if (is_callable(array($instance, 'hook' . ucfirst($hook)))) {
                //call the WP add_action function
                add_action($value, array($instance, 'hook' . ucfirst($hook)));
            }
        }
    }

    /**
     * Calls the specified action in WP
     * @param string $action
     * @param array $callback Contains the class name or object and the callback function
     *
     * @return void
     */
    public function setAction($action, $obj, $callback) {

        /* calls the custom action function from WP */
        add_action($action, array($obj, $callback), 10);
    }

    /**
     * Calls the specified action in WP
     * @param oject $instance The parent class instance
     *
     * @return void
     */
    public function setBlockHooks($instance) {
        $param_arr = array();

        /* for each admin action check if is defined in class and call it */
        foreach ($this->block_hooks as $hook => $value)
            if (is_callable(array($instance, 'hook' . ucfirst($hook))))
                call_user_func_array(array($instance, 'hook' . ucfirst($hook)), $param_arr);
    }

    /**
     * Get all core classes from config.xml in core directory
     *
     */
    public function getShortcodes() {
        if (self::$shortCodesSet == true)
            return;

        //If the user doesn't use shortcodes

        if (ABH_Classes_Tools::getOption('abh_shortcode') == 0)
            return;

        self::$shortCodesSet = true;
        /* if config allready in cache */
        if (!isset(ABH_Classes_ObjController::$config)) {
            $config_file = _ABH_ROOT_DIR_ . 'config.json';
            if (!file_exists($config_file))
                return;

            /* load configuration blocks data from core config files */
            ABH_Classes_ObjController::$config = json_decode(file_get_contents($config_file), 1);
        }

        if (is_array(ABH_Classes_ObjController::$config))
            foreach (ABH_Classes_ObjController::$config['blocks']['block'] as $block) {
                if (isset($block['name'])) {
                    if (isset($block['active']) && $block['active'] == 1)
                        if (isset($block['shortcodes']['shortcode'])) {
                            $instance = ABH_Classes_ObjController::getController($block['name']);
                            if (!is_array($block['shortcodes']['shortcode'])) {

                                if (is_callable(array($instance, 'hookShortWidget' . ucfirst($block['shortcodes']['shortcode'])))) {
                                    add_action('widget_text', array($instance, 'hookShortWidget' . ucfirst($block['shortcodes']['shortcode'])), 10, 1);
                                }
                                add_shortcode($block['shortcodes']['shortcode'], array($instance, 'hookShort' . ucfirst($block['shortcodes']['shortcode'])));
                            } else {
                                foreach ($block['shortcodes']['shortcode'] as $shortcode) {
                                    if (is_callable(array($instance, 'hookShortWidget' . ucfirst($shortcode)))) {
                                        add_action('widget_text', array($instance, 'hookShortWidget' . ucfirst($shortcode)), 10, 1);
                                    }
                                    add_shortcode($shortcode, array($instance, 'hookShort' . ucfirst($shortcode)));
                                }
                            }
                        }
                }
            }
    }

}