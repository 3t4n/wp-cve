<?php

class RKMW_Models_Menu {

    /** @var array with the menu content
     *
     * $page_title (string) (required) The text to be displayed in the title tags of the page when the menu is selected
     * $menu_title (string) (required) The on-screen name text for the menu
     * $capability (string) (required) The capability required for this menu to be displayed to the user. User levels are deprecated and should not be used here!
     * $menu_slug (string) (required) The slug name to refer to this menu by (should be unique for this menu). Prior to Version 3.0 this was called the file (or handle) parameter. If the function parameter is omitted, the menu_slug should be the PHP file that handles the display of the menu page content.
     * $function The function that displays the page content for the menu page. Technically, the function parameter is optional, but if it is not supplied, then WordPress will basically assume that including the PHP file will generate the administration screen, without calling a function. Most plugin authors choose to put the page-generating code in a function within their main plugin file.:In the event that the function parameter is specified, it is possible to use any string for the file parameter. This allows usage of pages such as ?page=my_super_plugin_page instead of ?page=my-super-plugin/admin-options.php.
     * $icon_url (string) (optional) The url to the icon to be used for this menu. This parameter is optional. Icons should be fairly small, around 16 x 16 pixels for best results. You can use the plugin_dir_url( __FILE__ ) function to get the URL of your plugin directory and then add the image filename to it. You can set $icon_url to "div" to have wordpress generate <br> tag instead of <img>. This can be used for more advanced formating via CSS, such as changing icon on hover.
     * $position (integer) (optional) The position in the menu order this menu should appear. By default, if this parameter is omitted, the menu will appear at the bottom of the menu structure. The higher the number, the lower its position in the menu. WARNING: if 2 menu items use the same position attribute, one of the items may be overwritten so that only one item displays!
     *
     * */
    public $menu = array();

    /** @var array with the menu content
     * $id (string) (required) HTML 'id' attribute of the edit screen section
     * $title (string) (required) Title of the edit screen section, visible to user
     * $callback (callback) (required) Function that prints out the HTML for the edit screen section. Pass function name as a string. Within a class, you can instead pass an array to call one of the class's methods. See the second example under Example below.
     * $post_type (string) (required) The type of Write screen on which to show the edit screen section ('post', 'page', 'link', or 'custom_post_type' where custom_post_type is the custom post type slug)
     * $context (string) (optional) The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). (Note that 'side' doesn't exist before 2.7)
     * $priority (string) (optional) The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
     * $callback_args (array) (optional) Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable.
     *
     * */
    public $meta = array();

    public function __construct() {

    }

    /**
     * Add a menu in WP admin page
     *
     * @param array $param
     *
     * @return void
     */
    public function addMenu($param = null) {
        if ($param)
            $this->menu = $param;

        if (is_array($this->menu)) {
            if ($this->menu[0] <> '' && $this->menu[1] <> '') {
                /* add the translation */
                if (!isset($this->menu[5]))
                    $this->menu[5] = null;
                if (!isset($this->menu[6]))
                    $this->menu[6] = null;

                /* add the menu with WP */
                add_menu_page($this->menu[0], $this->menu[1], $this->menu[2], $this->menu[3], $this->menu[4], $this->menu[5], $this->menu[6]);
            }
        }
    }

    /**
     * Add a submenumenu in WP admin page
     *
     * @param array $param
     *
     * @return void
     */
    public function addSubmenu($param = null) {
        if ($param)
            $this->menu = $param;

        if (is_array($this->menu)) {

            if ($this->menu[0] <> '' && $this->menu[1] <> '') {
                if (!isset($this->menu[5]))
                    $this->menu[5] = null;

                /* add the menu with WP */
                add_submenu_page($this->menu[0], $this->menu[1], $this->menu[2], $this->menu[3], $this->menu[4], $this->menu[5]);
            }
        }
    }

    /**
     * Add a box Meta in WP
     *
     * @param array $param
     *
     * @return void
     */
    public function addMeta($param = null) {
        if ($param)
            $this->meta = $param;


        if (is_array($this->meta)) {

            if ($this->meta[0] <> '' && $this->meta[1] <> '') {
                if (!isset($this->meta[5]))
                    $this->meta[5] = null;
                if (!isset($this->meta[6]))
                    $this->meta[6] = null;
                /* add the box content with WP */
                add_meta_box($this->meta[0], $this->meta[1], $this->meta[2], $this->meta[3], $this->meta[4], $this->meta[5]);
            }
        }
    }

    public function getMainMenu() {
        $menu = array(
            'rkmw_dashboard' => array(
                'title' => ((RKMW_Classes_Helpers_Tools::getOption('api') == '') ? esc_html__("First Step", RKMW_PLUGIN_NAME) : esc_html__("Overview", RKMW_PLUGIN_NAME)),
                'description' => ucfirst(RKMW_NAME) . ' ' . esc_html__("Overview", RKMW_PLUGIN_NAME),
                'parent' => 'rkmw_dashboard',
                'capability' => 'edit_posts',
                'function' => array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Overview'), 'init'),
                'icon' => '',
                'topmenu' => 'dashboard',
                'leftmenu' => true
            ),
            'rkmw_research' => array(
                'title' => esc_html__("Research", RKMW_PLUGIN_NAME),
                'description' => ucfirst(RKMW_NAME) . ' ' . esc_html__("Research", RKMW_PLUGIN_NAME),
                'parent' => 'rkmw_dashboard',
                'capability' => 'rkmw_manage_research',
                'function' => array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Research'), 'init'),
                'icon' => '',
                'topmenu' => 'research/research',
                'leftmenu' => esc_html__("Keyword Research", RKMW_PLUGIN_NAME)
            ),
            'rkmw_research/briefcase' => array(
                'title' => esc_html__("Briefcase", RKMW_PLUGIN_NAME),
                'description' => esc_html__("save the best Keywords", RKMW_PLUGIN_NAME),
                'parent' => 'rkmw_dashboard',
                'capability' => 'rkmw_manage_research',
                'function' => array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Research'), 'init'),
                'icon' => '',
                'topmenu' => false,
                'leftmenu' => esc_html__("Keywords Briefcase", RKMW_PLUGIN_NAME)
            ),
            'rkmw_research/suggested' => array(
                'title' => esc_html__("Suggestions", RKMW_PLUGIN_NAME),
                'description' => esc_html__("get keyword suggestions", RKMW_PLUGIN_NAME),
                'parent' => 'rkmw_dashboard',
                'capability' => 'rkmw_manage_research',
                'function' => array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Research'), 'init'),
                'icon' => '',
                'topmenu' => false,
                'leftmenu' => esc_html__("Keywords Suggestion", RKMW_PLUGIN_NAME)
            ),
            'rkmw_rankings' => array(
                'title' => esc_html__("Rankings", RKMW_PLUGIN_NAME),
                'description' => ucfirst(RKMW_NAME) . ' ' . esc_html__("Rankings", RKMW_PLUGIN_NAME),
                'parent' => 'rkmw_dashboard',
                'capability' => 'rkmw_manage_research',
                'function' => array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Ranking'), 'init'),
                'icon' => '',
                'topmenu' => 'rankings/rankings',
                'leftmenu' => esc_html__("Google Rankings", RKMW_PLUGIN_NAME)
            ),
            'rkmw_rankings/gscsync' => array(
                'title' => esc_html__("GSC Keywords", RKMW_PLUGIN_NAME),
                'description' => esc_html__("Sync Keywords from GSC", RKMW_PLUGIN_NAME),
                'parent' => 'rkmw_dashboard',
                'capability' => 'rkmw_manage_research',
                'function' => array(RKMW_Classes_ObjController::getClass('RKMW_Controllers_Ranking'), 'init'),
                'icon' => '',
                'topmenu' => false,
                'leftmenu' => esc_html__("Sync Keywords", RKMW_PLUGIN_NAME)
            ),
        );

        //for PHP 7.3.1 version
        $menu = array_filter($menu);

        return apply_filters('rkmw_menu', $menu);
    }

    /**
     * Get the admin Menu Tabs
     * @param string $category
     * @return array
     */
    public function getTabs($category) {
        $tabs = array();
        $tabs['rkmw_research'] = array(
            'rkmw_research/research' => array(
                'title' => esc_html__("Find Keywords", RKMW_PLUGIN_NAME),
                'description' => esc_html__("do a keyword research", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_research',
                'icon' => 'kr_92.png'
            ),
            'rkmw_research/briefcase' => array(
                'title' => esc_html__("Briefcase", RKMW_PLUGIN_NAME),
                'description' => esc_html__("save the best Keywords", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_research',
                'icon' => 'briefcase_92.png'
            ),
            'rkmw_research/labels' => array(
                'title' => esc_html__("Labels", RKMW_PLUGIN_NAME),
                'description' => esc_html__("group keywords", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_research',
                'icon' => 'labels_92.png'
            ),
            'rkmw_research/suggested' => array(
                'title' => esc_html__("Suggested", RKMW_PLUGIN_NAME),
                'description' => esc_html__("better keywords found", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_research',
                'icon' => 'suggested_92.png'
            ),
            'rkmw_research/history' => array(
                'title' => esc_html__("History", RKMW_PLUGIN_NAME),
                'description' => esc_html__("keyword research history", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_research',
                'icon' => 'history_92.png'
            ),
        );
        $tabs['rkmw_rankings'] = array(
            'rkmw_rankings/rankings' => array(
                'title' => esc_html__("Rankings", RKMW_PLUGIN_NAME),
                'description' => esc_html__("See Google ranking", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_research',
                'icon' => 'ranking_92.png'
            ),
            'rkmw_research/briefcase' => array(
                'title' => esc_html__("Add Keywords", RKMW_PLUGIN_NAME),
                'description' => esc_html__("Add briefcase keywords", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_rankings',
                'icon' => 'addpage_92.png'
            ),
            'rkmw_rankings/gscsync' => array(
                'title' => esc_html__("Sync Keywords", RKMW_PLUGIN_NAME),
                'description' => esc_html__("with Google Search Console", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_rankings',
                'icon' => 'addpage_92.png'
            ),
            'rkmw_rankings/settings' => array(
                'title' => esc_html__("Settings", RKMW_PLUGIN_NAME),
                'description' => esc_html__("Ranking settings", RKMW_PLUGIN_NAME),
                'capability' => 'rkmw_manage_settings',
                'icon' => 'settings_92.png'
            ),

        );

        //for PHP 7.3.1 version
        $tabs = array_filter($tabs);

        if (isset($tabs[$category])) {
            return apply_filters('rkmw_menu_' . $category, $tabs[$category]);

        }

        return array();
    }

    public function getAuditTabs() {
        $tabs = $this->getTabs('rkmw_audit');
        $content = '';
        $content .= '<div class="rkmw_nav d-flex flex-column bg-nav mb-3 sticky">';

        foreach ($tabs as $location => $row) {
            $content .= '<a class="m-0 p-4 font-dark rkmw_nav_item ' . $location . '" data-id="' . $location . '" href="javascript:void(0);" >
                <img class="rkmw_nav_item_icon" src="' . RKMW_THEME_URL . 'assets/img/logos/' . $row['icon'] . '">
                <span class="rkmw_nav_item_title">' . $row['title'] . '</span>
                <span class="rkmw_nav_item_description">' . $row['description'] . '</span>
            </a>';
        }

        $content .= '</div>';
        return $content;
    }

    public function getVisitedMenu() {
        $menu_visited = RKMW_Classes_Helpers_Tools::getOption('menu_visited');
        $menuid = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', false));

        if (!in_array($menuid, $menu_visited)) {
            array_push($menu_visited, $menuid);
        }

        RKMW_Classes_Helpers_Tools::saveOptions('menu_visited', $menu_visited);

        return RKMW_Classes_Helpers_Tools::getOption('menu_visited');
    }

    /**
     * Get the plugin admin menu based on selected category
     * @param null $current
     * @param string $category
     * @return string
     */
    public function getAdminTabs($current = null, $category = 'rkmw_research') {
        //Add the Menu Tabs in variable if not set before
        $tabs = $this->getTabs($category);

        $content = '';
        $content .= '<div class="rkmw_nav d-flex flex-column bg-nav mb-3">';

        if (!empty($tabs)) {
            foreach ($tabs as $location => $row) {
                if (RKMW_Classes_Helpers_Tools::menuOptionExists(str_replace(strtolower(RKMW_NAMESPACE) . '_', '', $location)) && !RKMW_Classes_Helpers_Tools::getMenuVisible(str_replace(strtolower(RKMW_NAMESPACE) . '_', '', $location))) {
                    continue;
                } elseif ($location == 'rkmw_research/labels' && !RKMW_Classes_Helpers_Tools::getMenuVisible('research/briefcase')) {
                    continue;
                } elseif (!current_user_can($row['capability'])) {
                    continue;
                }

                if ($current == $location || $current == substr($location, strpos($location, '/') + 1)) {
                    $class = 'active';
                } else {
                    $class = '';
                }

                $tab = null;
                if (strpos($location, '/')) {
                    list($location, $tab) = explode('/', $location);
                }

                $content .= '<a class="m-0 p-4 font-dark rkmw_nav_item ' . $class . '" href="' . RKMW_Classes_Helpers_Tools::getAdminUrl($location, $tab) . '">
                <img class="rkmw_nav_item_icon" src="' . RKMW_THEME_URL . 'assets/img/logos/' . $row['icon'] . '">
                <span class="rkmw_nav_item_title">' . $row['title'] . '</span>
                <span class="rkmw_nav_item_description">' . $row['description'] . '</span>
            </a>';
            }
        }

        $content .= '</div>';

        //return the menu
        return $content;
    }

}
