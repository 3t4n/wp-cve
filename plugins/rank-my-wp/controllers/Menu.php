<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Controllers_Menu extends RKMW_Classes_FrontController {

    /** @var array snippet */
    public $post_type;
    /** @var array snippet */
    var $options = array();

    public function __construct() {
        parent::__construct();

        if (!is_network_admin()) {
            add_action('admin_bar_menu', array($this, 'hookTopmenuDashboard'), 10);
            add_action('admin_bar_menu', array($this, 'hookTopMenu'), 91);

            //run compatibility check on Plugin settings
            if (RKMW_Classes_Helpers_Tools::getIsset('page')) {
                $menus = $this->model->getMainMenu();
                $page = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', ''));

                if (in_array($page, array_keys($menus))) {
                    add_action('admin_enqueue_scripts', array(RKMW_Classes_ObjController::getClass('RKMW_Models_Compatibility'), 'fixEnqueueErrors'), PHP_INT_MAX);
                }
            }

        }

        add_action('current_screen', function () {
            if (in_array(get_current_screen()->id, array('plugins', 'plugins-network'))) {
                RKMW_Classes_ObjController::getClass('RKMW_Controllers_Uninstall');
            }
        });

        //Check Compatibility with other plugins
        RKMW_Classes_ObjController::getClass('RKMW_Models_Compatibility');

    }

    /**
     * Hook the Admin load
     */
    public function hookInit() {
        //in case the token is not set
        $menus = $this->model->getMainMenu();
        if (RKMW_Classes_Helpers_Tools::getOption('api') == '') {
            add_filter('rkmw_seo_errors', function (){return 1;});

            if (RKMW_Classes_Helpers_Tools::getIsset('page')) {
                $page = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', ''));

                if ($page <> 'rkmw_dashboard' && in_array($page, array_keys($menus))) {
                    //redirect to dashboard to login
                    wp_redirect(RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_dashboard'));
                    exit();
                }
            }
        }

        //Check if help page is selected
        if (RKMW_Classes_Helpers_Tools::getIsset('page')) {
            if (RKMW_Classes_Helpers_Tools::getValue('page') == 'rkmw_help') {
                wp_redirect(RKMW_HOWTO_URL);
                die();
            }
        }

        //Check if account page is selected
        if (RKMW_Classes_Helpers_Tools::getIsset('page')) {
            if (RKMW_Classes_Helpers_Tools::getValue('page') == 'rkmw_cloud') {
                wp_redirect(RKMW_Classes_RemoteController::getCloudLink('dashboard'));
                die();
            }
        }

        //Show Admin Toolbar
        add_filter('admin_body_class', array($this, 'addSettingsClass'));
    }

    /**
     * Show the Menu in toolbar
     * @param $wp_admin_bar
     * @return bool
     */
    public function hookTopMenu($wp_admin_bar) {
        if (!is_admin()) {
            return false;
        }

        if (current_user_can('edit_posts')) {
            //Get count local SEO errors
            $errors = apply_filters('rkmw_seo_errors', 0);

            $wp_admin_bar->add_node(array(
                'id' => 'rkmw_toolbar',
                'title' => '<span class="rkmw_logo" style="margin-right: 3px"></span>' . RKMW_NAME . (($errors) ? '<span class="rkmw_errorcount">' . $errors . '</span>' : ''),
                'href' => RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_dashboard'),
                'parent' => false
            ));

            $mainmenu = $this->model->getMainMenu();
            if (!empty($mainmenu)) {
                foreach ($mainmenu as $menuid => $item) {
                    if(isset($item['topmenu']) && !$item['topmenu']){
                        continue;
                    }

                    if (!RKMW_Classes_Helpers_Tools::getMenuVisible(str_replace(strtolower(RKMW_NAMESPACE) . '_', '', $menuid))) {
                        //continue;
                    } elseif (!isset($item['parent'])) {
                        continue;
                    }


                    //make sure the user has the capabilities
                    if (isset($item['capability']) && current_user_can($item['capability'])) {

                        $wp_admin_bar->add_node(array(
                            'id' => $menuid,
                            'title' => $item['title'],
                            'href' => RKMW_Classes_Helpers_Tools::getAdminUrl($menuid),
                            'parent' => 'rkmw_toolbar'
                        ));

                        $tabs = $this->model->getTabs($menuid);
                        if (!empty($tabs)) {
                            foreach ($tabs as $id => $tab) {
                                $array_id = explode('/', $id);
                                if (count((array)$array_id) == 2) {
                                    $wp_admin_bar->add_node(array(
                                        'id' => $menuid . str_replace('/', '_', $id),
                                        'title' => $tab['title'],
                                        'href' => RKMW_Classes_Helpers_Tools::getAdminUrl($array_id[0], $array_id[1]),
                                        'parent' => $menuid
                                    ));
                                }
                            }
                        }
                    }
                }
            }

        }

        return $wp_admin_bar;
    }

    /**
     * Show the Dashboard link when Full Screen
     * @param $wp_admin_bar
     * @return mixed
     */
    public function hookTopmenuDashboard($wp_admin_bar) {
        global $rkmw_fullscreen;

        if (!is_user_logged_in()) {
            return false;
        }

        if (isset($rkmw_fullscreen) && $rkmw_fullscreen) {
            $wp_admin_bar->add_node(array(
                'parent' => 'site-name',
                'id' => 'dashboard',
                'title' => esc_html__("Dashboard"),
                'href' => admin_url(),
            ));
        }

        return $wp_admin_bar;
    }

    /**
     * Creates the Setting menu in Wordpress
     */
    public function hookMenu() {
        //Get all the post types
        $this->post_type = RKMW_Classes_Helpers_Tools::getOption('post_types');

        //Get count local SEO errors
        $errors = apply_filters('rkmw_seo_errors', 0);

        ///////////////
        $this->model->addMenu(array(ucfirst(RKMW_NAME),
            RKMW_NAME . (($errors) ? '<span class="rkmw_errorcount">' . $errors . '</span>' : ''),
            'edit_posts',
            'rkmw_dashboard',
            null,
            RKMW_ASSETS_URL . 'img/logos/menu_icon_16.png'
        ));

        $mainmenu = $this->model->getMainMenu();
        foreach ($mainmenu as $name => $item) {

            if(isset($item['leftmenu']) && !$item['leftmenu']){
                continue;
            }elseif(isset($item['leftmenu']) && is_string($item['leftmenu']) && $item['leftmenu'] <> ''){
                $item['title'] = $item['leftmenu'];
            }

            if (!RKMW_Classes_Helpers_Tools::getMenuVisible(str_replace(strtolower(RKMW_NAMESPACE) . '_', '', $name))) {
                if(isset($item['parent'])) {
                    $this->model->addSubmenu(array($name,
                        $item['description'],
                        $item['title'],
                        $item['capability'],
                        $name,
                        $item['function'],
                    ));
                }
                continue;

            } elseif (!isset($item['parent'])) {
                continue;
            }

            $this->model->addSubmenu(array($item['parent'],
                $item['description'],
                $item['title'],
                $item['capability'],
                $name,
                $item['function'],
            ));

        }

        $this->model->addSubmenu(array('rkmw_dashboard',
            esc_html__("How To & Support", RKMW_PLUGIN_NAME),
            esc_html__("Help & Support", RKMW_PLUGIN_NAME),
            'edit_posts',
            'rkmw_help',
            array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Help'), 'init')
        ));

        $this->model->addSubmenu(array('rkmw_dashboard',
            esc_html__("Go To RMW Cloud", RKMW_PLUGIN_NAME),
            esc_html__("Go To RMW Cloud", RKMW_PLUGIN_NAME),
            'edit_posts',
            'rkmw_cloud',
            array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Account'), 'init')
        ));

    }

    public function addSettingsClass($classes) {
        if (RKMW_Classes_Helpers_Tools::getIsset('page')) {
            $menus = $this->model->getMainMenu();
            $page = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', ''));

            if (in_array($page, array_keys($menus))) {
                $classes = "$classes rank-my-wp-settings";
            }

        }

        return $classes;
    }

    public function hookHead() {
        global $rkmw_fullscreen;
        if (RKMW_Classes_Helpers_Tools::getIsset('page')) {
            $menus = $this->model->getMainMenu();
            $page = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', ''));

            if (in_array($page, array_keys($menus))) {
                echo '<script type="text/javascript" src="//www.google.com/jsapi"></script>';
                echo '<script>google.load("visualization", "1.0", {packages: ["corechart"]});</script>';
                echo '<div id="rkmw_preloader" class="rkmw_loading"></div>';

                if ($page <> 'rkmw_dashboard') {
                    $rkmw_fullscreen = true;
                    RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('fullwidth', array('trigger' => true, 'media' => 'all'));
                }
            }

        }

        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('logo');

    }

}
