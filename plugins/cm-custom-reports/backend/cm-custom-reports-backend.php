<?php
if( !defined('ABSPATH') )
{
    exit;
}

class CM_Custom_Reports_Backend
{
    protected static $instance = NULL;
    public static $classPath = NULL;
    public static $cssPath = NULL;
    public static $jsPath = NULL;
    public static $viewsPath = NULL;
    public static $imagesPath = NULL;
    public static $reportsPath = NULL;
    public static $libsPath = NULL;
    protected static $currentReport = NULL;

    const PAGE_YEARLY_OFFER = 'https://www.cminds.com/wordpress-plugins-library/cm-wordpress-plugins-yearly-membership/';

    public static function instance()
    {
        $class = __CLASS__;
        if( !isset(self::$instance) && !( self::$instance instanceof $class ) )
        {
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function __construct()
    {
        self::$classPath = CMCR_PLUGIN_DIR . 'backend/classes/';
        self::$cssPath = CMCR_PLUGIN_URL . 'backend/assets/css/';
        self::$imagesPath = CMCR_PLUGIN_URL . 'backend/assets/images/';
        self::$jsPath = CMCR_PLUGIN_URL . 'backend/assets/js/';
        self::$viewsPath = CMCR_PLUGIN_DIR . 'backend/views/';
        self::$reportsPath = CMCR_PLUGIN_DIR . 'backend/reports/';
        self::$libsPath = CMCR_PLUGIN_URL . 'backend/libs/';

        add_action('admin_init', array(__CLASS__, 'save'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'cmcr_enqeue_scripts'));
        add_action('admin_menu', array(__CLASS__, 'cmcr_admin_menu'));
        add_filter('CMCR_admin_settings', array(__CLASS__, 'addAdminSettings'));

        /*
         * Preview
         */
        add_action('wp_ajax_cm_custom_reports_gateway', array(__CLASS__, 'outputReport'));

        self::loadCoreFiles();
        self::loadReportFiles();
        self::loadReports();
        self::loadModuleFiles();
    }

    public static function getScriptsAndStyles($atts = array())
    {
        global $post;
        $postId = empty($post->ID) ? '' : $post->ID;
        self::cmcr_enqeue_scripts();
    }

    /**
     * Outputs the preview
     */
    public static function outputReport()
    {
        $reportKey = filter_input(INPUT_GET, 'cmcr_report_key');

        if( $reportKey )
        {

            $savedReport = CMCR_Schedule_Log_List_Table::getLogEntry($reportKey);
            if( !is_object($savedReport) )
            {
                echo 'Report does not exist!';
                die();
            }
        }
        else
        {
            echo '';
            die();
        }

        self::getScriptsAndStyles();

        $linkPath = CMCR_PLUGIN_DIR . 'backend/views/preview.phtml';
        $cssPath = self::$cssPath;

        if( file_exists($linkPath) )
        {
            $reportOptions = self::getReportOptions($savedReport->report);
            $report = new $reportOptions['class'];
            self::setCurrentReport($report);

            $args['data'] = $savedReport->data;
            $args['data_args'] = $savedReport->data_args;

            if( !empty($savedReport->data_args['date_query']) )
            {
                $args['name_filter_args'] = array(
                    'date_from' => $savedReport->data_args['date_query']['after'],
                    'date_to'   => $savedReport->data_args['date_query']['before'],
                );
            }

            remove_all_filters('cmcr_graph_tab_controls_output');
            $reportContent = CMCR_Graph_Module::displayGraph($args);

            ob_start();
            require $linkPath;
            $content = ob_get_clean();
            echo $content;
        }
        die();
    }

    public static function save()
    {

    }

    /**
     *
     * @return CMCR_Report_Base
     */
    public static function getCurrentReport()
    {
        return self::$currentReport;
    }

    /**
     * Sets the current report
     * @param type $report
     */
    public static function setCurrentReport($report)
    {
        self::$currentReport = $report;
    }

    public static function loadReports()
    {
        static $reports = array();
        if( $reports === array() )
        {
            $reports = apply_filters('cmcr_loaded_reports', $reports);
        }
        return $reports;
    }

    public static function getReportNameBySlug($slug)
    {
        $reports = self::loadReports();
        $reportName = !empty($reports[$slug]) ? $reports[$slug]['name'] : NULL;
        return $reportName;
    }

    public static function getReportUriBySlug($slug)
    {
        $reportUri = esc_url(add_query_arg(array('cmcr_report' => $slug), self::getReportListUrl()));
        return $reportUri;
    }

    public static function getReportListUrl()
    {
        $reportUri = esc_url(add_query_arg(array('page' => 'cm-custom-reports'), admin_url('admin.php')));
        return $reportUri;
    }

    public static function getReportOptions($reportToLoad = null)
    {
        $reports = self::loadReports();

        if( $reportToLoad && is_string($reportToLoad) )
        {
            if( isset($reports[$reportToLoad]) )
            {
                return $reports[$reportToLoad];
            }
            else
            {
                /*
                 * Report not found
                 */
                return NULL;
            }
        }

        return $reports;
    }

    public static function getReport()
    {
        static $report = NULL;

        if( empty($report) )
        {
            $reportToLoad = filter_input(INPUT_GET, 'cmcr_report');

            if( !empty($reportToLoad) )
            {
                $reportOptions = self::getReportOptions($reportToLoad);
                if( class_exists($reportOptions['class']) )
                {
                    $report = new $reportOptions['class'];
                }
            }
        }
        return $report;
    }

    public static function cmcr_enqeue_scripts()
    {
        $currentScreen = get_current_screen();

        $path = self::$jsPath . 'cmcr_admin_scripts.js';

        wp_enqueue_script('cmcr-admin-scripts', self::$jsPath . 'cmcr_admin_scripts.js', array('jquery', 'jquery-ui-datepicker', 'wp-color-picker', 'jquery-ui-tabs'), false, true);

        wp_enqueue_script('cmcr-jquery-foldable', self::$libsPath . 'foldable/jquery.foldable.js', array('jquery'), false, true);
        wp_enqueue_style('cmcr-jquery-foldable-css', self::$libsPath . 'foldable/jquery.foldable.css');

        wp_enqueue_style('jquery-ui-custom-css', self::$cssPath . 'jquery-ui-custom.css');
        wp_enqueue_style('cmcr-admin-styles', self::$cssPath . 'cmcr_admin_styles.css');

        if( isset($currentScreen) && $currentScreen->id == 'cm-custom-reports-settings' )
        {

        }
    }

    public static function cmcr_admin_menu()
    {
        add_menu_page(CMCR_NAME, CMCR_NAME, 'manage_options', CMCR_SLUG_NAME, array(__CLASS__, 'cmcr_display_page'));

        add_submenu_page(CMCR_SLUG_NAME, 'Reports', __('Reports', CMCR_SLUG_NAME), 'delete_others_posts', CMCR_SLUG_NAME, array(__CLASS__, 'cmcr_display_page'));
        add_submenu_page(CMCR_SLUG_NAME, 'Settings', __('Settings', CMCR_SLUG_NAME), 'manage_options', CMCR_SLUG_NAME . '-settings', array(__CLASS__, 'cmcr_display_page'));
//        add_submenu_page(CMCR_SLUG_NAME, 'Help', __('Help', CMCR_SLUG_NAME), 'manage_options', CMCR_SLUG_NAME . '-help', array(__CLASS__, 'cmcr_display_page'));

    }

    /**
     * Function loads all of the files from the reports directory
     */
    public static function loadCoreFiles()
    {
        include_once CMCR_PLUGIN_DIR . '/backend/classes/EventLogListTable.php';
    }

    /**
     * Function loads all of the files from the reports directory
     */
    public static function loadReportFiles()
    {
        include_once self::$classPath . 'ReportBase.php';
        $pattern = self::$reportsPath . '*.php';

        foreach(glob($pattern) as $filename)
        {
            include_once $filename;
        }
    }

    /**
     * Function loads all of the files from the reports directory
     */
    public static function loadModuleFiles()
    {
        $pattern = self::$classPath . 'modules/*.php';

        foreach(glob($pattern) as $filename)
        {
            include_once $filename;
        }
    }

    public static function changeFavorites()
    {
        $inputGet = filter_input_array(INPUT_GET);
        if( !empty($inputGet['fav']) )
        {
            $reportOptions = self::getReportOptions($inputGet['fav']);
            if( $reportOptions['class'] )
            {
                $report = new $reportOptions['class'];
                $report->addToFavorites();
            }
        }
        if( !empty($inputGet['unfav']) )
        {
            $reportOptions = self::getReportOptions($inputGet['unfav']);
            if( $reportOptions['class'] )
            {
                $report = new $reportOptions['class'];
                $report->removeFromFavorites();
            }
        }
    }

    /**
     * Outputs the main report dashboard
     * @return type
     */
    public static function displayReportsDashboard()
    {
        self::changeFavorites();

        ob_start();
        include_once self::$viewsPath . 'reports.phtml';
        $content = ob_get_clean();

        return $content;
    }

    public static function displayReportView()
    {
        $report = self::getReport();
        self::setCurrentReport($report);
        if( !empty($report) )
        {
            /*
             * Output the selected report
             */
            $content = $report->displayReport();
        }
        else
        {
            /*
             * Output the Reports Dashboard (no report selected)
             */
            $content = self::displayReportsDashboard();
        }

        return $content;
    }

    public static function displayEventsDashboard($params = array())
    {
        $params = apply_filters('CMCR_events_params', $params);
        extract($params);

        ob_start();
        include_once self::$viewsPath . 'events.phtml';
        $content = ob_get_clean();
        return $content;
    }

    public static function cmcr_display_page()
    {
        $pageId = filter_input(INPUT_GET, 'page');
        $getParams = filter_input_array(INPUT_GET);

        switch($pageId)
        {
            case CMCR_SLUG_NAME:
                {
                    $content = self::displayReportView();
                    break;
                }
            case CMCR_SLUG_NAME . '-settings':
                {
                    $params = apply_filters('CMCR_admin_settings', array());
                    extract($params);

                    ob_start();
                    include_once self::$viewsPath . 'settings.phtml';
                    $content = ob_get_clean();
                    break;
                }
            case CMCR_SLUG_NAME . '-about':
                {
                    $iframeURL = 'https://www.cminds.com/wordpress-plugins/';
                    ob_start();
                    include_once self::$viewsPath . 'about.phtml';
                    $content = ob_get_clean();
                    break;
                }
            case CMCR_SLUG_NAME . '-pro':
                {
                    $iframeURL = 'https://www.cminds.com/wordpress-plugins/';
                    ob_start();
                    include_once self::$viewsPath . 'pro.phtml';
                    $content = ob_get_clean();
                    break;
                }
            case CMCR_SLUG_NAME . '-userguide':
                {
                    wp_redirect('https://www.cminds.com/wordpress-plugins-library/purchase-cm-custom-reports-plugin-for-wordpress/');
                    exit;
                    break;
                }
        }

        self::displayAdminPage($content);
    }

    public static function addAdminSettings($params = array())
    {
        if( self::_isPost() )
        {
            $params = CMCR_Settings::processPostRequest();

            // Labels
            $labels = CMCR_Labels::getLabels();
            foreach($labels as $labelKey => $label)
            {
                if( isset($_POST['label_' . $labelKey]) )
                {
                    CMCR_Labels::setLabel($labelKey, stripslashes($_POST['label_' . $labelKey]));
                }
            }

            if( filter_input(INPUT_POST, 'cmcr_pluginCleanup') )
            {
                self::_cleanup();
            }
        }

        return $params;
    }

    public static function displayAdminPage($content)
    {
        $nav = self::getAdminNav();
        include_once self::$viewsPath . 'template.phtml';
    }

    public static function getAdminNav()
    {
        global $self, $parent_file, $submenu_file, $plugin_page, $typenow, $submenu;
        ob_start();
        $submenus = array();

        $menuItem = CMCR_SLUG_NAME;

        if( isset($submenu[$menuItem]) )
        {
            $thisMenu = $submenu[$menuItem];

            foreach($thisMenu as $sub_item)
            {
                $slug = $sub_item[2];

                // Handle current for post_type=post|page|foo pages, which won't match $self.
                $self_type = !empty($typenow) ? $self . '?post_type=' . $typenow : 'nothing';

                $isCurrent = FALSE;
                $subpageUrl = get_admin_url('', 'admin.php?page=' . $slug);

                if(
                        (!isset($plugin_page) && $self == $slug ) ||
                        ( isset($plugin_page) && $plugin_page == $slug && ( $menuItem == $self_type || $menuItem == $self || file_exists($menuItem) === false ) )
                )
                {
                    $isCurrent = TRUE;
                }

                $url = (strpos($slug, '.php') !== false || strpos($slug, 'http') !== false) ? $slug : $subpageUrl;
                $isExternalPage = strpos($slug, 'http') !== FALSE;
                $submenus[] = array(
                    'link'    => $url,
                    'title'   => $sub_item[0],
                    'current' => $isCurrent,
                    'target'  => $isExternalPage ? '_blank' : ''
                );
            }
            include self::$viewsPath . 'nav.phtml';
        }
        $nav = ob_get_clean();
        return $nav;
    }

    public static function getDateFormat($for = 'date')
    {
        $format = CMCR_Settings::getOption(CMCR_Settings::OPTION_DATE_FORMAT);

        switch($format)
        {
            default:
            case 'us':
                $formatArr = array(
                    'date'       => 'm/d/Y',
                    'flot'       => '%m/%d/%Y',
                    'datepicker' => 'mm/dd/yy',
                );
                break;
            case 'eur':
                $formatArr = array(
                    'date'       => 'd-m-Y',
                    'flot'       => '%d-%m-%Y',
                    'datepicker' => 'dd-mm-yy',
                );
                break;
        }

        $result = $formatArr[$for];
        return $result;
    }

    public static function getDate($time = null)
    {
        $timestamp = !empty($time) ? $time : time();
        $result = date(self::getDateFormat(), $timestamp);
        return $result;
    }

    public static function getTheOptionNames($k)
    {
        return strpos($k, 'cmcr_') === 0;
    }

    protected static function _isPost()
    {
        return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
    }

    /**
     * Function cleans up the plugin, removing the terms, resetting the options etc.
     *
     * @return string
     */
    protected static function _cleanup($force = true)
    {
        CMCR_Settings::deleteAllOptions();
    }

}