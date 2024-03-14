<?php

/**
 * iNET Webkit setup
 * @since 1.0.0
 */

class INET_WK_Plugin
{
    public $version = '1.1.6';
    protected static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * iNET Webkit Constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
    }

    private function init_hooks()
    {
        add_action('admin_enqueue_scripts', array($this, 'inetwk_admin_customs_scripts'));
    }

    private function define_constants()
    {
        $this->define('INET_WK_ABSPATH', dirname(INET_WK_FILE) . '/');
        $this->define('INET_HP_BASENAME', plugin_basename(INET_WK_ABSPATH));
        $this->define('INET_WK_URL', plugins_url('/', INET_WK_FILE));
        $this->define('INET_HP_VERSION', $this->version);
    }

    public function includes()
    {
        include_once INET_WK_ABSPATH . 'inc/core/codestar-framework/codestar-framework.php';
        include_once INET_WK_ABSPATH . 'inc/functions/function-ultility.php';
        include_once INET_WK_ABSPATH . 'inc/functions/inet-webkit-options.php';

        include_once INET_WK_ABSPATH . 'inc/frontend/inet-auto-resize-image.php';
        include_once INET_WK_ABSPATH . 'inc/frontend/inet-auto-save-images.php';
        include_once INET_WK_ABSPATH . 'inc/frontend/inet-webkit-header-footer.php';
        include_once INET_WK_ABSPATH . 'inc/frontend/inet-webkit-customer-care-channel.php';
        include_once INET_WK_ABSPATH . 'inc/frontend/inet-webkit-smtp-custom.php';
        include_once INET_WK_ABSPATH . 'inc/frontend/inet-webkit-security.php';
        include_once INET_WK_ABSPATH . 'inc/frontend/inet-webkit-extensions.php';

        include_once INET_WK_ABSPATH . 'inc/functions/inet-webkit-install-button.php';
    }

    /**
     * @return void
     */
    function inetwk_admin_customs_scripts()
    {
        wp_enqueue_style('inet-webkit-style-admin', INET_WK_URL . 'assets/css/admin/inet-webkit-admin.css', array(), $this->version);
        wp_enqueue_script('inet-webkit-script-admin', INET_WK_URL . 'assets/js/admin.js', array('jquery'), '', true);
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }
}
