<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    XT_WOOATC
 * @subpackage XT_WOOATC/Admin
 * @author     XplodedThemes
 */
class XT_WOOATC_Admin {

    /**
     * Core class reference.
     *
     * @since    1.0.0
     * @access   private
     * @var      XT_WOOATC $core Core Class
     */
    protected $core;

    protected $already_installed = false;

    /**
     * Initialize the class and set its properties.
     *
     * @param XT_WOOATC $core Plugin core class.
     * @since    1.0.0
     */
    public function __construct($core)
    {

        $this->core = $core;

        // Init modules
        add_filter($this->core->plugin_prefix('modules'), array( $this, 'modules'), 1, 1);

        // Init customizer options
        add_filter( $this->core->plugin_prefix( 'customizer_fields' ), array( $this, 'customizer_fields'), 1, 2 );

        add_filter('xtfw_modules_set_shared_by', array($this, 'module_set_shared_by'), 10, 3);

        add_action('init', array($this, 'already_installed_notice'));
    }

    public function modules($modules) {

        $modules[] = 'add-to-cart';
        $modules[] = 'add-to-cart2';
        return $modules;
    }

    public function customizer_fields( $fields, XT_Framework_Customizer $customizer ) {

        return $fields;
    }

    public function module_set_shared_by($set_shared_by, $module_id, $plugin_slug) {

        if($this->core->plugin_slug() === $plugin_slug && $module_id === 'add-to-cart') {
            return false;
        }

        return $set_shared_by;
    }

    public function already_installed() {

        if($this->already_installed) {
            return true;
        }

        if($this->core->has_modules()) {

            $this->already_installed = !empty($this->core->modules()->get('add-to-cart')->shared_by());
        }

        return $this->already_installed;
    }

    public function already_installed_notice() {

        if($this->already_installed()) {

            $shared_by = $this->core->modules()->get('add-to-cart')->shared_by();
            $plugin_list = array();

            foreach($shared_by as $plugin_slug => $plugin_name) {

                if($plugin_slug !== $this->core->plugin_slug()) {

                    $plugin_list[] = '<strong>'.$plugin_name.'</strong>';
                }
            }

            $plugin_list = implode(", ", $plugin_list);
            // replace last comma with &
            $plugin_list = preg_replace('~(.*)' . preg_quote(',', '~') . '~', '$1' . ' &', $plugin_list, 1);

            $message = sprintf(esc_html__("{title}: This plugin can be disabled since it is already loaded by %s", 'xt-woo-ajax-add-to-cart'), $plugin_list);

            $this->core->plugin_notices()->add_warning_message($message);

        }
    }
}
