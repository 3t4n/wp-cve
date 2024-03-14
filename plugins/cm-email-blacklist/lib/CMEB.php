<?php

include_once CMEB_PATH . '/lib/models/Validate.php';
include_once CMEB_PATH . '/backend/classes/Settings.php';

class CMEB {

    public static $cssPath = null;
    public static $jsPath = null;
    public static $class = null;

    const MENU = 'cmeb_menu';
    const MENU_ABOUT = 'cmeb_menu_about';
    const MENU_SETTINGS = 'cmeb_menu_settings';
    const MENU_LICENSE = 'cmeb_menu_license';
    const MENU_UPGRADE = 'cmeb_menu_upgrade';
    const MENU_MEMBERSHIP = 'cmeb_menu_membership';
    const PAGE_YEARLY_OFFER = 'https://www.cminds.com/wordpress-plugins-library/cm-wordpress-plugins-yearly-membership/';

    public static $isLicenseOK = NULL;

    public static function init() {
		include_once CMEB_PATH . '/package/cminds-free.php';
        CMEB_Validate::init();
        self::$class = __CLASS__;
        self::$cssPath = CMEB_URL . '/backend/assets/css/';
        self::$jsPath = CMEB_URL . '/backend/assets/js/';
        add_action('admin_enqueue_scripts', array(self::$class, 'cmeb_settings_style'));
        add_action('admin_menu', array(self::$class, 'registerAdminPages'));
    }

    public static function install() {
        include_once CMEB_PATH . '/lib/models/Validate.php';
        CMEB_Validate::install();
        include_once CMEB_PATH . '/lib/models/WhiteList.php';
        CMEB_WhiteList::install();
        include_once CMEB_PATH . '/lib/models/EmailBlacklist.php';
        CMEB_EmailBlacklist::install();
    }

    public static function uninstall() {
        include_once CMEB_PATH . '/lib/models/Validate.php';
        CMEB_Validate::uninstall();
    }

    public static function registerAdminPages() {
        global $submenu;
        add_menu_page('Settings', 'CM E-Mail Registration Blacklist', 'manage_options', CMEB_MENU_ITEM, array(get_class(), 'displayAdminOptions'), '');
        do_action('cmeb_admin_pages');
        //add_submenu_page(CMEB_MENU_ITEM, 'CM Email Blacklist About', 'Settings', 'manage_options', self::MENU_SETTINGS, array(get_class(), 'displayAdminOptions'));
    }

    public static function getAdminNav() {
        global $submenu, $plugin_page, $pagenow;
        ob_start();
        $submenus = array();
        if (isset($submenu[CMEB_MENU_ITEM])) {
            $thisMenu = $submenu[CMEB_MENU_ITEM];
            foreach ($thisMenu as $item) {
                $slug = $item[2];
                $slugParts = explode('?', $slug);
                $name = '';
                if (count($slugParts) > 1)
                    $name = $slugParts[0];
                $isCurrent = ($slug == $plugin_page || (!empty($name) && $name === $pagenow));
                $url = (strpos($item[2], '.php') !== false) ? $slug : get_admin_url('', 'admin.php?page=' . $slug);
                $submenus[] = array(
                    'link' => $url,
                    'title' => $item[0],
                    'current' => $isCurrent
                );
            }

            require(CMEB_PATH . '/backend/views/nav.phtml');
        }
        $nav = ob_get_contents();
        ob_end_clean();
        return $nav;
    }

    public static function displayMembershipPage($content) {
        wp_redirect('https://www.cminds.com/wordpress-plugins-library/cm-wordpress-plugins-yearly-membership/');
        exit();
    }

    public static function displayAdminPage($content) {
        $nav = self::getAdminNav();
        require CMEB_PATH . '/backend/views/template.phtml';
    }

    public static function displayAdminOptions() {
        $content = '';

        $params = apply_filters('cmeb_admin_settings', array());
        extract($params);
        ob_start();
        require(CMEB_PATH . '/backend/views/settings.phtml');
        $content = ob_get_contents();
        ob_end_clean();

        self::displayAdminPage($content);
    }

    public static function cmeb_settings_style() {
        if (!empty($_GET['page'])) {
            $page = sanitize_text_field($_GET['page']);
            if ($page === 'cmeb_menu_settings' || $page === 'cmeb_menu') {
                wp_enqueue_style('jquery-ui-tabs-css', self::$cssPath . 'jquery-ui-tabs.css');
                wp_enqueue_script('jquery-ui-tabs', false, array(), false, true);
                wp_enqueue_script('backend', self::$jsPath . 'backend.js', array(), false, true);
            }
        }
    }

    public static function displayAboutPage() {
        ob_start();
        require(CMEB_PATH . '/backend/views/about.phtml');
        $content = ob_get_contents();
        ob_end_clean();
        self::displayAdminPage($content);
    }

    public static function displayUpgradePage() {
        wp_enqueue_style('cmbl-upgrade', self::$cssPath . 'cmbl-get-pro.css');
        ob_start();
        require(CMEB_PATH . '/backend/views/upgrade.phtml');
        $content = ob_get_contents();
        ob_end_clean();
        self::displayAdminPage($content);
    }

}
?>
