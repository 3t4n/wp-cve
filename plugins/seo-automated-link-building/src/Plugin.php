<?php
/**
 * Internal Links Manager
 * Copyright (C) 2021 webraketen GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You can read the GNU General Public License here: <https://www.gnu.org/licenses/>.
 * For questions related to this program contact post@webraketen-media.de
 */

namespace SeoAutomatedLinkBuilding;


class Plugin
{
    private $name;
    public static $domain = 'seo-automated-link-building';
    private $dbVersion = '1.4.1';

    protected $editData = null;
    protected $statisticData = null;

    protected function getEditData()
    {
        if($this->editData) {
            return $this->editData;
        }
        $page = null;
        $link = null;
        $id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
        if($id && !isset($_GET['action'])) {
            $link = Link::get($id);
            if($link && $link->page_id) {
                $page = Post::query()
                    ->select('ID as id, post_type as type, post_title as title')
                    ->where('ID', $link->page_id)
                    ->get_row();
            }
        }
        $this->editData = new \stdclass();
        $this->editData->link = $link;
        $this->editData->page = $page;
        return $this->editData;
    }

    protected function getStatisticData()
    {
        if($this->statisticData) {
            return $this->statisticData;
        }

        $list = new Statistic_List('seo-automated-link-building');
        $list->preDisplay();

        $maxDays = 28;

        $dtNow = new \DateTime();
        $dtNow->modify('today');
        $dtNow->modify('-' . ($maxDays-1) . ' days');

        $data = Statistic::query()
            ->select('count(*) as cnt, YEAR(created_at) as y, MONTH(created_at) as m, DAYOFMONTH(created_at) as d')
            ->where('created_at', '>', $dtNow->format('Y-m-d H:i:s e'))
            ->group_by(["YEAR(created_at), MONTH(created_at), DAYOFMONTH(created_at)"])
            ->get_results();

        $linkTable = Link::get_table_name();

        $bestResults = Statistic::query()
            ->select("count(*) as cnt, link_id, $linkTable.title as title")
            ->join($linkTable, 'link_id', 'id')
            ->group_by(['link_id'])
            ->order_by('cnt', 'desc')
            ->get_results();

        $best = [];
        foreach($bestResults as $index => $result) {
            $best[] = ['x' => $index, 'y' => (int)$result['cnt'], 'label' => "{$result['title']} (#{$result['link_id']})"];
        }

        $countsIndex = [];
        foreach($data as $d) {
            $countsIndex[$d['y'] . '-' . str_pad($d['m'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($d['d'], 2, '0', STR_PAD_LEFT)] = (int)$d['cnt'];
        }

        $counts = [];
        for($i = 0; $i < 28; $i++) {
            $key = $dtNow->format('Y-m-d');
            $counts[] = [
                'x' => $key,
                'y' => array_key_exists($key, $countsIndex) ? $countsIndex[$key] : 0,
            ];
            $dtNow->modify('+1 day');
        }

        $this->statisticData = new \stdclass();
        $this->statisticData->list = $list;
        $this->statisticData->data = $data;
        $this->statisticData->counts = $counts;
        $this->statisticData->best = $best;
        return $this->statisticData;
    }

    protected function getPostData($strip = array())
    {
        if(empty($strip)) {
            return $_POST;
        }
        $stripIndex = array_flip($strip);
        // we dont want to have magic quotes, so removed them form input, but don't change $_POST directly
        // like described here https://developer.wordpress.org/reference/functions/stripslashes_deep/
        // in addition all data returned by this function will be sanitized and/or validated to ensure security
        // ActiveRecord implementation will quote text for safe database entries
        $data = array();
        foreach($_POST as $key => $value) {
            $data[$key] = array_key_exists($key, $stripIndex) ? stripslashes_deep($value) : $value;
        }
        return $data;
    }

    public function __construct($name)
    {
        $this->name = $name;
        add_action( 'init', [$this, 'init'] );
        if(is_admin()) {
            add_filter("plugin_action_links_$name", [$this, 'addActionLinks']);
            add_filter('set-screen-option', [$this, 'setCMIOptions'], 10, 3);
            add_action('admin_menu', [$this, 'addMenuItems']);
            register_activation_hook( $name, [$this, 'updateTable']);
            register_uninstall_hook( $name, [get_called_class(), 'removeTable']);
            add_action( 'plugins_loaded', [$this, 'updateTable'] );
            add_action( 'admin_post_seo_automated_link_building_add_link', [$this, 'addLink']);
            add_action( 'admin_post_seo_automated_link_building_import_links', [$this, 'importLinks']);
            add_action( 'admin_post_seo_automated_link_building_settings', [$this, 'updateSettings']);
            add_action( 'admin_post_seo_automated_link_building_edit_link', [$this, 'editLink']);
            add_action( 'wp_ajax_seo_automated_link_building_find_pages', [$this, 'findPages'] );
            add_action( 'wp_ajax_seo_automated_link_building_export_links', [$this, 'exportLinks']);
            add_action( 'admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
            add_action( 'wp_ajax_seo_automated_link_building_track_link', [$this, 'trackLink'] );
        }

        add_action( 'wp_ajax_nopriv_seo_automated_link_building_track_link', [$this, 'trackLink'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueueScripts'] );
        add_filter('the_content', [$this, 'changeContent'], 99);
    }

    public function init()
    {
        Settings::init();
        if(is_admin()) {
            load_plugin_textdomain('seo-automated-link-building', false, 'seo-automated-link-building/lang/');
        }
    }

    public function enqueueAdminScripts($hook_suffix)
    {
        // edit/creation page
        if($hook_suffix === 'internal-links-manager_page_seo-automated-link-building-add-link' || $hook_suffix === 'toplevel_page_seo-automated-link-building-all-links' && $this->getEditData()->link) {
            // taggle (https://sean.is/poppin/tags)
            wp_enqueue_script( 'taggle', plugins_url( '/js/external/taggle.min.js', $this->name ), array() );
            wp_enqueue_style( 'taggle', plugins_url( '/css/external/taggle.min.css', $this->name ), array() );
            // jquery auto-complete
            wp_enqueue_script( 'jquery.auto-complete', plugins_url( '/js/external/jquery.auto-complete.min.js', $this->name ), array('jquery') );
            wp_enqueue_style( 'jquery.auto-complete', plugins_url( '/css/external/jquery.auto-complete.min.css', $this->name ), array() );
            // page specific
            wp_enqueue_style( 'seo-automated-link-building-edit', plugins_url( '/css/edit.css', $this->name ), array() );
            wp_enqueue_script( 'seo-automated-link-building-edit', plugins_url( '/js/edit.js', $this->name ), array('taggle', 'jquery.auto-complete') );
            $editData = $this->getEditData();
            $link = $editData->link;
            $page = $editData->page;
            wp_localize_script( 'seo-automated-link-building-edit', 'seoAutomatedLinkBuildingEdit', array(
                'titleWarning' => __('Please provide an internal title', 'seo-automated-link-building'),
                'urlWarning' => __('Please provide an URL', 'seo-automated-link-building'),
                'pageWarning' => __('Please provide a page', 'seo-automated-link-building'),
                'keywordsWarning' => __('Please provide keywords', 'seo-automated-link-building'),
                'adminAjax' => admin_url( 'admin-ajax.php' ),
                'replaceHint' => __('Words to replace...', 'seo-automated-link-building'),
                'keywords' => $link ? json_decode($link->keywords) : [],
                'page' => $page,
            ));

            return;
        }
        if($hook_suffix === 'toplevel_page_seo-automated-link-building-all-links') {
            $action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';
            $ids = isset($_REQUEST['id']) ? wp_parse_id_list($_REQUEST['id']) : null;
            if(is_null($ids) || empty($ids)) {
                return;
            }
            if($action === 'export') {
                // hack: some html has to be written that exportLinks will work
                print '<span></span>';
                $export = ImportExport::exportLinks($ids);
                $export = str_replace("`", "\\`", $export);
                wp_enqueue_script( 'download.js', plugins_url( '/js/external/download.min.js', $this->name ), array() );
                wp_enqueue_script( 'seo-automated-link-building-export', plugins_url( '/js/export.js', $this->name ), array('download.js') );
                wp_localize_script( 'seo-automated-link-building-export', 'seoAutomatedLinkBuildingExport', array(
                    'export' => $export,
                ));
            }

            return;
        }
        if($hook_suffix === 'internal-links-manager_page_seo-automated-link-building-settings') {
            // page specific
            wp_enqueue_style( 'seo-automated-link-building-settings', plugins_url( '/css/settings.css', $this->name ), array() );
            wp_enqueue_script( 'seo-automated-link-building-settings', plugins_url( '/js/settings.js', $this->name ), array('jquery') );

            return;
        }
        if($hook_suffix === 'internal-links-manager_page_seo-automated-link-building-import-links') {
            wp_enqueue_script( 'download.js', plugins_url( '/js/external/download.min.js', $this->name ), array() );
            wp_enqueue_script( 'dropzone.js', plugins_url( '/js/external/dropzone.min.js', $this->name ), array() );

            // page specific
            wp_enqueue_style( 'seo-automated-link-building-import', plugins_url( '/css/import.css', $this->name ), array() );
            wp_enqueue_script( 'seo-automated-link-building-import', plugins_url( '/js/import.js', $this->name ), array('jquery', 'download.js', 'dropzone.js') );
            wp_localize_script( 'seo-automated-link-building-import', 'seoAutomatedLinkBuildingImport', array(
                'importHint' => __('Drop files here to import', 'seo-automated-link-building'),
                'redirectUrl' => admin_url( "admin.php?page=seo-automated-link-building-all-links" ),
                'adminAjax' => admin_url( 'admin-ajax.php' ),
            ));

            return;
        }
        if($hook_suffix === 'internal-links-manager_page_seo-automated-link-building-statistic') {
            wp_enqueue_script( 'chart.js', plugins_url( '/js/external/Chart.bundle.min.js', $this->name ), array() );

            // page specific
            wp_enqueue_script( 'seo-automated-link-building-statistic', plugins_url( '/js/statistic.js', $this->name ), array('jquery', 'chart.js') );
            wp_enqueue_style( 'seo-automated-link-building-statistic', plugins_url( '/css/statistic.css', $this->name ), array() );
            $data = $this->getStatisticData()->counts;
            $best = $this->getStatisticData()->best;
            wp_localize_script( 'seo-automated-link-building-statistic', 'seoAutomatedLinkBuildingStatistic', array(
                'data' => $data,
                'label' => __('Clicks', 'seo-automated-link-building'),
                'tooltipFormat' => __('MM/DD/YYYY', 'seo-automated-link-building'),
                'date' => __('Date', 'seo-automated-link-building'),
                'best' => $best,
                'title' => __('Title'),
            ));

            return;
        }
    }

    public function enqueueScripts()
    {
        $activate_tracking = true;
        $user = wp_get_current_user();

        if(Settings::get()['disableStatistics'] || ($user->get('ID') > 0 && Settings::get()['disableAdminTracking'])) {
            $activate_tracking = false;
        }

        if($activate_tracking === true) {
            wp_enqueue_script( 'seo-automated-link-building', plugins_url( '/js/seo-automated-link-building.js', $this->name ), array('jquery') );
            wp_localize_script( 'seo-automated-link-building', 'seoAutomatedLinkBuilding', ['ajaxUrl' => admin_url( 'admin-ajax.php' )]);
        }
    }

    public function createTable()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name_links = $wpdb->prefix . str_replace('-', '_', 'seo-automated-link-building');
        $table_name_statistic = $wpdb->prefix . str_replace('-', '_', 'seo-automated-link-building') . '_statistic';

        $sqlForLinks = "CREATE TABLE $table_name_links (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            page_id mediumint(9),
            title varchar(255) NOT NULL,
            keywords text NOT NULL,
            url varchar(255) NOT NULL,
            num smallint(5) NOT NULL DEFAULT 1,
            target varchar(255) NOT NULL default '_self',
            nofollow tinyint(1) NOT NULL default 0,
            notitle tinyint(1) NOT NULL default 0,
            active tinyint(1) NOT NULL default 1,
            partly_match tinyint(1) NOT NULL default 0,
            case_sensitive tinyint(1) NOT NULL default 0,
            titleattr varchar(255),
            priority mediumint(9) NOT NULL DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        $sqlForStatistic = "CREATE TABLE $table_name_statistic (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            link_id mediumint(9) NOT NULL,
            title varchar(255) NOT NULL,
            source_url varchar(255) NOT NULL,
            destination_url varchar(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta([
            $sqlForLinks,
            $sqlForStatistic,
        ]);

        $domain = static::$domain;

        // transform keywords to json
        if(version_compare(get_option( "{$domain}_db_version" ), '1.0.6') === -1) {
            $links = Link::get();
            foreach($links as $link) {
                $link->keywords = json_encode(array_map(function($item) {
                    return trim($item);
                }, explode(',', $link->keywords)), JSON_UNESCAPED_UNICODE);
                $link->save();
            }
        }

        update_option( "{$domain}_db_version", $this->dbVersion );
    }

    public function updateTable()
    {
        $domain = static::$domain;
        $installed_ver = get_option( "{$domain}_db_version" );
        if($installed_ver !== $this->dbVersion) {
            $this->createTable();
        }
    }

    public static function removeTable()
    {
        global $wpdb;

        $table_name_links = $wpdb->prefix . str_replace('-', '_', 'seo-automated-link-building');
        $table_name_statistic = $wpdb->prefix . str_replace('-', '_', 'seo-automated-link-building') . '_statistic';
        // delete tables in reverse order
        $wpdb->query("DROP TABLE IF EXISTS $table_name_statistic");
        $wpdb->query("DROP TABLE IF EXISTS $table_name_links");

        $domain = static::$domain;
        delete_option("{$domain}_db_version");
    }

    public function addActionLinks($links)
    {
        $actionLinks = [
            '<a href="' . admin_url( "admin.php?page=seo-automated-link-building-add-link" ) . '">' . __('Add New Link', 'seo-automated-link-building') . '</a>',
            '<a href="' . admin_url( "admin.php?page=seo-automated-link-building-all-links" ) . '">' . __('All Links', 'seo-automated-link-building') . '</a>',
            '<a href="' . admin_url( "admin.php?page=seo-automated-link-building-settings" ) . '">' . __('Settings') . '</a>',
            '<a href="' . admin_url( "admin.php?page=seo-automated-link-building-statistic" ) . '">' . __('Statistic', 'seo-automated-link-building') . '</a>',
        ];
        return array_merge( $actionLinks, $links );
    }

    public function addMenuItems()
    {
        add_menu_page(
            'Internal Links Manager',
            'Internal Links Manager',
            'manage_options',
            'seo-automated-link-building-all-links',
            [$this, 'renderList'],
            plugin_dir_url( $this->name ) . 'images/webraketen-icon-gruen.png'
        );

        $hook = add_submenu_page(
            'seo-automated-link-building-all-links',
            __('All Links', 'seo-automated-link-building'),
            __('All Links', 'seo-automated-link-building'),
            'manage_options',
            'seo-automated-link-building-all-links',
            [$this, 'renderList']
        );
        add_action( "load-$hook", [$this, 'addCMILinks'] );

        add_submenu_page(
            'seo-automated-link-building-all-links',
            __('Add New Link', 'seo-automated-link-building'),
            __('Add New Link', 'seo-automated-link-building'),
            'manage_options',
            'seo-automated-link-building-add-link',
            [$this, 'renderAddItem']
        );

        add_submenu_page(
            'seo-automated-link-building-all-links',
            __('Statistic', 'seo-automated-link-building'),
            __('Statistic', 'seo-automated-link-building'),
            'manage_options',
            'seo-automated-link-building-statistic',
            [$this, 'renderStatistic']
        );

        add_submenu_page(
            'seo-automated-link-building-all-links',
            __('Import + Export', 'seo-automated-link-building'),
            __('Import + Export', 'seo-automated-link-building'),
            'manage_options',
            'seo-automated-link-building-import-links',
            [$this, 'renderImport']
        );

        add_submenu_page(
            'seo-automated-link-building-all-links',
            __('Settings'),
            __('Settings'),
            'manage_options',
            'seo-automated-link-building-settings',
            [$this, 'renderSettings']
        );
    }

    public function setCMIOptions($status, $option, $value)
    {
        if ( $option === 'cmi_links_per_page' ) {
            return $value;
        }

        return $status;
    }

    public function renderList()
    {
        $editData = $this->getEditData();
        $link = $editData->link;
        if($link) {
            $page = $editData->page;
            $title = __('Edit Link', 'seo-automated-link-building');
            $this->renderEdit($link, $page, $title);
            return;
        }

        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option('per_page', 'option');
        $linksPerPage = get_user_meta($user, $option, true);
        if ( empty($linksPerPage) || $linksPerPage < 1 ) {
            $linksPerPage = $screen->get_option('per_page', 'default');
        }

        $list = new Links_List('seo-automated-link-building');
        $list->setLimit($linksPerPage);

        // logic
        $hasActiveFlag = isset($_REQUEST['active']);
        $onlyActive = $hasActiveFlag && $_REQUEST['active'] === '1';
        $onlyInactive = $hasActiveFlag && $_REQUEST['active'] === '0';

        // translations
        $linksHeadline = __('All Links', 'seo-automated-link-building');
        $addNewHeadline = __('Add New', 'seo-automated-link-building');
        $allTitle = __('All');
        $activeTitle = __('Active', 'seo-automated-link-building');
        $inactiveTitle = __('Deactivated', 'seo-automated-link-building');

        // data
        $linksCount = Link::query()->select("count(*)")->get_var();
        $activeLinksCount = Link::query()->select("count(*)")->where('active', true)->get_var();
        $inactiveLinksCount = Link::query()->select("count(*)")->where('active', false)->get_var();

        include __DIR__ . '/templates/list.php';
    }

    public function addCMILinks()
    {
        $option = 'per_page';

        $args = array(
            'label' => __('Links per page', 'seo-automated-link-building'),
            'default' => 50,
            'option' => 'cmi_links_per_page',
        );

        add_screen_option( $option, $args );
    }

    public function renderAddItem()
    {
        $link = new Link();
        $link->nofollow = false;
        $link->notitle = false;
        $link->partly_match = false;
        $link->num = 1;
        $link->priority = 0;
        $link->target = '_self';
        $link->keywords = '[]';
        $title = __('Add New Link', 'seo-automated-link-building');
        $page = null;
        $this->renderEdit($link, $page, $title);
    }

    protected function renderEdit(Link $link, $page, $title)
    {
        $adminPostUrl = admin_url( 'admin-post.php' );

        // logic
        $shouldDisplayPageInput = !empty((int)$link->page_id) || empty((string)$link->url);

        // translations
        $internalTitleHeadline = __('Internal title', 'seo-automated-link-building');
        $internalTitleDescription = __('The title for your internal structure', 'seo-automated-link-building');
        $pageHeadline = __('Page', 'seo-automated-link-building');
        $pageDescription = __('Which page would you like to link to?', 'seo-automated-link-building');
        $urlSwitch = __('Use custom url', 'seo-automated-link-building');
        $urlHeadline = __('URL', 'seo-automated-link-building');
        $urlDescription = __('Which page would you like to link to?', 'seo-automated-link-building');
        $pageSwitch = __('Use website page', 'seo-automated-link-building');
        $keywordsHeadline = __('Keywords', 'seo-automated-link-building');
        $keywordsDescription = __('What terms should be replaced? Use enter or tab to finish one term.', 'seo-automated-link-building');
        $settingsHeadline = __('Settings (optional)', 'seo-automated-link-building');
        $titleattrHeadline = __('Link title', 'seo-automated-link-building');
        $titleattrDescription = __('Which title should be set for the title HTML Attribute? If no title is set, internal title is set.', 'seo-automated-link-building');
        $notitleDescription = __("Don't use a link-title (not recommended)", 'seo-automated-link-building');
        $priorityHeadline = __('Priority', 'seo-automated-link-building');
        $priorityDescription = __('With which priority should the entry handled? The higher the value, the ealier the keywords of the entry will be replaced. If the priority of two entries is equal, the longer one wins.', 'seo-automated-link-building');
        $numberOfLinksHeadline = __('Number of links', 'seo-automated-link-building');
        $unlimitiedHint = __('Unlimited', 'seo-automated-link-building');
        $numberOfLinksDescription = __('How often should the link appear on a page? Choose -1 for unlimited links.', 'seo-automated-link-building');
        $followHeadline = __('Let searchengines follow this link', 'seo-automated-link-building');
        $followDescription = __('Should searchengines follow this link? If no, rel="nofollow" will be set.', 'seo-automated-link-building');
        $targetHeadline = __('Link target', 'seo-automated-link-building');
        $targetSameTabDescription = __('Open in same tab', 'seo-automated-link-building');
        $targetNewTabDescription = __('Open in new tab', 'seo-automated-link-building');
        $partialReplacementHeadline = __('Partial replacement', 'seo-automated-link-building');
        $partialReplacementDescription = __('Allow partial replacement of words. (e.g. success in successful)', 'seo-automated-link-building');
        $caseSensitiveHeadline = __('Case sensitive', 'seo-automated-link-building');
        $caseSensitiveDescription = __('Should the keywords be case sensitive?', 'seo-automated-link-building');
        $saveTitle = __('Save');

        // data
        $pageTitle = $link->title;
        $id = esc_attr($link->id);
        $title = esc_attr($link->title);
        $url = esc_attr($link->url);
        $pageId = esc_attr($link->page_id ? $link->page_id : '');
        $titleattr = esc_attr($link->titleattr ? $link->titleattr : '');
        $num = esc_attr($link->num);
        $priority = esc_attr($link->priority);
        $notitle = $link->notitle;
        $follow = !$link->nofollow;
        $partlyMatch = $link->partly_match;
        $target = $link->target;
        $caseSensitive = $link->case_sensitive;

        include __DIR__ . '/templates/edit.php';
    }

    public function renderSettings()
    {
        $settings = Settings::getRaw();

        $adminPostUrl = admin_url( 'admin-post.php' );

        // translations
        $settingsHeadline = __('Settings');
        $whitelistHeadline = __('Whitelist', 'seo-automated-link-building');
        $whitelistDescription = __('Only these pages should be changed. One url per line. Optional.', 'seo-automated-link-building');
        $inputDescription = __('You can use * as wildcard between slashes and ** including slashes.', 'seo-automated-link-building');
        $blacklistHeadline = __('Blacklist', 'seo-automated-link-building');
        $blacklistDescription = __('Provide pages which shouldn\'t be changed. One url per line. Optional.', 'seo-automated-link-building');
        $postTypesHeadline = __('Post types', 'seo-automated-link-building');
        $postTypesLabel = __('Choose from', 'seo-automated-link-building');
        $postTypesDescription = __('Provide posttypes for which links should be set. One Post type per line. Optional.', 'seo-automated-link-building');
        $excludeHeadline = __('Excluded html elements', 'seo-automated-link-building');
        $excludeExample = __("#example-id\n.example-class", 'seo-automated-link-building');
        $excludeDescription = __('Provide html selectors for which no links should be set. One selector per line. Optional.', 'seo-automated-link-building');
        $disableAdminTrackingHeadline = __("Disable Tracking when in Admin-Mode", 'seo-automated-link-building');
        $disableAdminTrackingDescription = __('Do not track link clicks when logged in.', 'seo-automated-link-building');
        $disableStatisticsHeadline = __("Disable Statistics", 'seo-automated-link-building');
        $disableStatisticsDescription = __('Do not track link clicks.', 'seo-automated-link-building');
        $saveTitle = __('Save');

        // data
        $whitelist = esc_html($settings['whitelist']);
        $blacklist = esc_html($settings['blacklist']);
        $postTypes = esc_html($settings['posttypes']);
        $exclude = esc_html($settings['exclude']);
        $disableAdminTracking = (bool) $settings['disableAdminTracking'] ?? false;
        $disableStatistics = (bool) $settings['disableStatistics'] ?? false;
        $availablePostTypes = array_keys(get_post_types(['public' => true]));

        include __DIR__ . '/templates/settings.php';
    }

    public function renderImport()
    {
        $adminPostUrl = admin_url( 'admin-post.php' );

        // translations
        $importHeadline = __('Import', 'seo-automated-link-building');
        $exportHeadline = __('Export', 'seo-automated-link-building');
        $addMissingDescription = __('Only add missing items (compared by ID)', 'seo-automated-link-building');
        $addMissingAndUpdateDescription = __('Add missing items (compared by ID) and update, if already available', 'seo-automated-link-building');
        $addAlwaysDescription = __('Add all items (new ID will be generated)', 'seo-automated-link-building');
        $exportDescription = __('Export all links', 'seo-automated-link-building');

        include __DIR__ . '/templates/import.php';
    }

    public function renderStatistic()
    {
        $list = $this->getStatisticData()->list;
        $counts = $this->getStatisticData()->counts;

        // logic
        $hasEntries = count($counts) > 0;

        include __DIR__ . '/templates/statistic.php';
    }

    public function addLink()
    {
        if (
            ! isset( $_POST['nonce'] )
            || ! wp_verify_nonce( $_POST['nonce'], 'seo_automated_link_building_add_link' )
        ) {
            wp_nonce_ays('not allowed');
        }

        $data = $this->getPostData(array('title', 'titleattr', 'keywords'));

        $id = isset($data['id']) && ctype_digit($data['id']) ? (int)$data['id'] : null;
        if($id) {
            $link = Link::get($id);
        } else {
            $link = new Link();
        }
        $link->title = sanitize_text_field($data['title']);
        $link->keywords = json_encode(array_map(function($item) {
            return sanitize_text_field($item);
        }, $data['keywords']), JSON_UNESCAPED_UNICODE);
        $link->url = esc_url_raw($data['url']);
        $link->page_id = isset($data['page']) && !empty($data['page']) ? (int)$data['page'] : null;
        $link->titleattr = isset($data['titleattr']) && !empty($data['titleattr']) ? sanitize_text_field($data['titleattr']) : null;
        $link->num = ctype_digit($data['num']) ? (int)$data['num'] : -1;
        $link->priority = isset($data['priority']) ? (int)$data['priority'] : 0;
        $link->nofollow = array_key_exists('follow', $data) && $data['follow'] === 'on' ? 0 : 1;
        $link->case_sensitive = array_key_exists('case_sensitive', $data) && $data['case_sensitive'] === 'on' ? 1 : 0;
        $link->notitle = array_key_exists('notitle', $data) && $data['notitle'] === 'on' ? 1 : 0;
        $link->partly_match = array_key_exists('partly_match', $data) && $data['partly_match'] === 'on' ? 1 : 0;
        $link->target = $data['target'] === '_self' ? '_self' : '_blank';
        $link->save();

        wp_redirect( admin_url( "admin.php?page=seo-automated-link-building-all-links" ) );
        exit;
    }

    public function importLinks()
    {
        if (
            ! isset( $_POST['nonce'] )
            || ! wp_verify_nonce( $_POST['nonce'], 'seo_automated_link_building_import_links' )
        ) {
            wp_nonce_ays('not allowed');
        }

        $filePath = $_FILES['file']['tmp_name'];
        if(!current_user_can('upload_files')) {
            return;
        }
        $ext = array_pop(explode('.', basename($_FILES['file']['name'])));
        $isCsv = $ext === 'csv';
        $isJson = $ext === 'json';
        if (!$isCsv && !$isJson) {
           return;
        }

        $data = $this->getPostData();
        $fileContent = file_get_contents($filePath);
        $mode = sanitize_text_field($data['mode']);

        if($isCsv) {
            ImportExport::importCsv($fileContent, $mode);
        } elseif($isJson) {
            ImportExport::importJson($fileContent, $mode);
        }
    }

    public function exportLinks()
    {
        $data = $this->getPostData();
        $ext = sanitize_text_field($data['ext']);
        if($ext === 'csv') {
            header('Content-Disposition: attachment; filename=internal-link-manager.csv');
            header('Content-Type: text/csv; charset=utf-8');
            print ImportExport::exportAllLinksAsCsv();
        } elseif($ext === 'json') {
            header('Content-Disposition: attachment; filename=internal-link-manager.json');
            header('Content-Type: text/csv; charset=utf-8');
            print ImportExport::exportAllLinksAsJson();
        }
        die();
    }

    public function updateSettings()
    {
        if (
            ! isset( $_POST['nonce'] )
            || ! wp_verify_nonce( $_POST['nonce'], 'seo_automated_link_building_settings' )
        ) {
            wp_nonce_ays('not allowed');
        }

        $data = $this->getPostData(array('whitelist', 'blacklist','posttypes', 'exclude'));

        $disableStatistics = $data['disableStatistics'] ?? false;
        $disableAdminTracking = $data['disableAdminTracking'] ?? false;

        Settings::save(array(
            'whitelist' => implode("\n", array_map(function($text) {
               return esc_url_raw($text);
            }, explode("\n", $data['whitelist']))),
            'blacklist' => implode("\n", array_map(function($text) {
               return esc_url_raw($text);
            }, explode("\n", $data['blacklist']))),
            'posttypes' => sanitize_textarea_field($data['posttypes']),
            'exclude' => sanitize_textarea_field($data['exclude']),
            'disableStatistics' => (bool) $disableStatistics,
            'disableAdminTracking' => (bool) $disableAdminTracking,
        ));

        wp_redirect( admin_url( "admin.php?page=seo-automated-link-building-settings" ) );
        exit;
    }

    public function trackLink()
    {
        ignore_user_abort(true);

        $data = $this->getPostData(array('title', 'source_url', 'destination_url'));

        // get id and validate it
        $id = $data['link_id'];
        $id = is_int($id) || ctype_digit($id) ? (int)$id : null;
        if(!$id) {
            // if not valid, return
            wp_die();
            return;
        }

        Statistic::create([
            'link_id' => $id,
            'title' => sanitize_text_field($data['title']),
            'source_url' => esc_url_raw($data['source_url']),
            'destination_url' => esc_url_raw($data['destination_url']),
        ]);
        wp_die();
    }

    private function normalizeUrl($url) {
        return strtolower(trailingslashit($url));
    }

    private function areUrlsIdentical($required, $actual) {
        $regex = str_replace('**', '@@', $required);
        $regex = str_replace('*', '@', $regex);
        $regex = preg_quote($regex);
        $regex = str_replace('@@', '.*', $regex);
        $regex = str_replace('@', '[^/]*', $regex);
        $pattern = '#^' . $this->normalizeUrl($regex) . '$#';
        return preg_match($pattern, $this->normalizeUrl($actual)) === 1;
    }

    public function findPages()
    {
        $data = $this->getPostData(array('search'));
        $posts = Post::query()
            ->select('ID as id, post_title as title, post_type as type')
            ->where('post_type', 'in', [array_values(get_post_types(['public' => true]))])
            ->where('post_title', 'like', '%' . Post::wpdb()->esc_like($data['search']) . '%')
            ->where('post_status', 'publish')
            ->order_by('post_modified', 'desc')
            ->limit(25)
            ->get_results();
        wp_die(json_encode($posts, JSON_UNESCAPED_UNICODE));
    }

    private function getUrlPath($url)
    {
        if($url) {
            $parsedUrl = parse_url($url);
            if($parsedUrl && array_key_exists('path', $parsedUrl)) {
                $path = $parsedUrl['path'];
                $query = array_key_exists('query', $parsedUrl) ? '?' . $parsedUrl['query'] : '';
                return $path . $query;
            }
        }
        return null;
    }

    public function changeContent($content)
    {
        $requestedUrl = $_SERVER['REQUEST_URI'];
        $hostname = $_SERVER['SERVER_NAME'];
        $settings = Settings::get();

        global $wp;
        $globalUrl = $this->getUrlPath(home_url( add_query_arg( [], $wp->request)));
        $postUrl = $this->getUrlPath(get_permalink(get_queried_object_id()));
        $possibleUrls = array_filter([$globalUrl, $postUrl, $requestedUrl], function($item) {
            return !empty($item);
        });

        // check whitelist
        if(!empty($settings['whitelist'])) {
            $matched = false;

            foreach($settings['whitelist'] as $whitelistUrl) {
                $path = $this->getUrlPath($whitelistUrl);
                if(!$path) {
                    continue;
                }
                foreach($possibleUrls as $url) {
                    if($this->areUrlsIdentical($path, $url)) {
                        $matched = true;
                        break;
                    }
                }
            }

            if(!$matched) {
                // not in whitelist => return content without modification
                return $content;
            }
        }

        // check blacklist
        foreach ($settings['blacklist'] as $blacklistUrl) {
            $path = $this->getUrlPath($blacklistUrl);
            if(!$path) {
                continue;
            }
            foreach($possibleUrls as $url) {
                if($this->areUrlsIdentical($path, $url)) {
                    return $content;
                }
            }
        }

        // check posttypes
        if(!empty($settings['posttypes']) && !in_array(get_post_type(), $settings['posttypes'])) {
            return $content;
        }

        $links = Link::query()->where('active', true)->order_by('priority', 'desc')->get();
        global $post;
        $links = array_filter($links, function(Link $link) use($possibleUrls, $hostname, $post) {
            if($link->page_id) {
                // link uses post id
                return !$post || $post->ID != $link->page_id;
            }
            $parsedUrl = parse_url($link->url);
            if(!$parsedUrl || !array_key_exists('path', $parsedUrl)) {
                return true;
            }
            $path = $parsedUrl['path'];
            $host = array_key_exists('host', $parsedUrl) ? $parsedUrl['host'] : $hostname;
            if(strtolower($host) !== strtolower($hostname)) {
                return true;
            }
            foreach($possibleUrls as $url) {
                if($this->areUrlsIdentical($path, $url)) {
                    return false;
                }
            }
            return true;
        });

        return (new TextConverter($content))
            ->addLinks($links)
            ->getText();
    }
}
