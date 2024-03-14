<?php

class RabbitLoader_21_Tab_Init extends RabbitLoader_21_Admin
{

    public static function echoPluginPage()
    {
        if (!current_user_can('manage_options')) {
            #the use is not authorized to manage options
            return;
        }

        // check if the user have submitted the settings
        //if (RabbitLoader_21_Util_Core::get_param('settings-updated') ) {
        // add settings saved message with the class of "updated"
        //add_settings_error( 'rabbitloader_messages', 'rabbitloader_message', __( 'Settings Saved', 'rl' ), 'updated' );
        //}

        // show error/update messages
        //settings_errors( 'wporg_messages' );
        /*
        add_filter( 'style_loader_tag', function($html, $handle){
            if ( 'rabbitloader-bootstrap' === $handle ) {
                return str_replace( "media='all'", "media='all' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'", $html );
            }
            return $html;
        }, 10, 2 );
        wp_enqueue_style( 'rabbitloader-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' ); */
        //wp_enqueue_style( 'rabbitloader-bootstrap', RABBITLOADER_PLUG_URL . 'admin/css/bootstrap.v5.1.3.min.css' );

        wp_enqueue_style('rabbitloader-inter', 'https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap');
        //wp_enqueue_style( 'rabbitloader-css', RABBITLOADER_PLUG_URL . 'admin/css/style.css', [], RABBITLOADER_PLUG_VERSION);

?>

        <div class="wrap">
            <?php
            try {
                $tab = self::decideTabToShow($isConnected);
                if ($isConnected) {
                    echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';

                    self::echoTabBar($tab);

                    if ($tab == 'settings') {
                        RabbitLoader_21_Tab_Settings::init();
                        RabbitLoader_21_Tab_Settings::echoMainContent();
                    } else if ($tab == 'help') {
                        RabbitLoader_21_Tab_Help::init();
                        RabbitLoader_21_Tab_Help::echoMainContent();
                    } else if ($tab == 'log') {
                        RabbitLoader_21_Tab_Log::init();
                        RabbitLoader_21_Tab_Log::echoMainContent();
                    } else if ($tab == 'urls') {
                        RabbitLoader_21_Tab_Urls::init();
                        RabbitLoader_21_Tab_Urls::echoMainContent();
                    } else if ($tab == 'usage') {
                        RabbitLoader_21_Tab_Usage::init();
                        RabbitLoader_21_Tab_Usage::echoMainContent();
                    } else if ($tab == 'images') {
                        RabbitLoader_21_Tab_Images::init();
                        RabbitLoader_21_Tab_Images::echoMainContent();
                    } else if ($tab == 'css') {
                        RabbitLoader_21_Tab_Css::init();
                        RabbitLoader_21_Tab_Css::echoMainContent();
                    } else {
                        //anything not defined will show the home tab
                        RabbitLoader_21_Tab_Home::init();
                        RabbitLoader_21_Tab_Home::echoMainContent();
                    }
                } else {
                    RabbitLoader_21_Tab_Settings::init();
                    RabbitLoader_21_Tab_Settings::echoMainContent();
                }
            } catch (Throwable $e) {
                RabbitLoader_21_Core::on_exception($e);
            }
            ?>
        </div>
    <?php
    }
    /**
     * Echo the tabs in our plugin's admin page
     */
    private static function echoTabBar($activeTab = 'home')
    {

        self::get_warnings($messages_count, true);

        $page = RabbitLoader_21_Util_Core::get_param('page');
        $tabs = [
            'home' => 'Home',
            'urls' => 'URLs',
            //'images'=>'Images',
            'css' => 'Critical CSS',
            'usage' => 'Usage',
            'settings' => 'Settings',
            'log' => 'Log Messages',
            'help' => 'Help'
        ];
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach ($tabs as $tab => $name) {
            $url = add_query_arg(array('tab' => $tab, 'page' => $page), null);
            $class = ($tab == $activeTab) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab $class' href='$url'>";
            RL21UtilWP::_e($name);
            echo "</a>";
        }
        echo '</h2>';
        //self::pass_keys_to_js();
    }

    private static function decideTabToShow(&$isConnected)
    {
        $tab = RabbitLoader_21_Util_Core::get_param('tab');

        if (!self::isPluginActivated()) {
            $isConnected = false;
            if ($tab != 'help') {
                $tab = 'settings';
            }
        } else {
            $isConnected = true;
        }
        if (empty($tab)) {
            $tab = 'home';
        }
        return $tab;
    }

    protected static function getTabUrl($tab_key)
    {
        $page = RabbitLoader_21_Util_Core::get_param('page');
        return add_query_arg(array('tab' => $tab_key, 'page' => $page), null);
    }

    protected static function &getOverviewData(&$apiError, &$apiMessage)
    {
        $overview = get_transient('rabbitloader_trans_overview_data');
        if (!empty($overview)) {
            return $overview;
        }

        $overview = [
            'score_circle_best' => 0,
            'score_circle_avg' => 0,
            'canonical_url_count' => 0,
            'optimized_url_count' => 0,
            'optimized_url_per' => 0,
            'pv_used' => 0,
            'pv_quota' => 0,
            'pv_remaining' => 0,
            'pp_used' => 0,
            'plan_title' => 0,
            'plan_end_date' => 0,
            'image_comp_avg_cp' => 0,
            'css_size_pp' => 0
        ];

        $http = RabbitLoader_21_Core::callGETAPI('report/overview', $apiError, $apiMessage);
        if ($apiError) {
            return $overview;
        }

        if (!empty($http['body']['data']['domain_details']['host'])) {
            //CODENAME#401041
            $dbHost = parse_url(get_site_url())['host'];
            $remoteHost = $http['body']['data']['domain_details']['host'];
            if ((strcmp($dbHost, $remoteHost) !== 0) && (strcmp($dbHost, "www." . $remoteHost) !== 0)) {
                RabbitLoader_21_Core::update_api_tokens('', '', '', "dbHost $dbHost when remoteHost $remoteHost");
                $apiError = "Invalid registration";
                $apiMessage = "INVALID_DOMAIN";
                return $overview;
            }
        }

        $expected_url_count = RabbitLoader_21_Core::get_published_count();

        if (!empty($http['body']['data']['speed_score']['max_score'])) {
            $overview['score_circle_best'] = intval($http['body']['data']['speed_score']['max_score'] * 100);
        }

        if (!empty($http['body']['data']['speed_score']['avg_score'])) {
            $overview['score_circle_avg'] = intval($http['body']['data']['speed_score']['avg_score'] * 100);
        }

        if (!empty($http['body']['data']['speed_score']['canonical_url_count'])) {
            $canonical_url_count = intval($http['body']['data']['speed_score']['canonical_url_count']);
            $overview['canonical_url_count'] = max($canonical_url_count, $expected_url_count);
        }

        if (!empty($http['body']['data']['speed_score']['optimized_url_count'])) {
            $overview['optimized_url_count'] = 0;
        }

        if (!empty($http['body']['data']['bill']['end_date'])) {
            $overview['plan_end_date'] = $http['body']['data']['bill']['end_date'];
        }
        if (!empty($http['body']['data']['bill']['usage']['pageviews_ctr'])) {
            $overview['pv_used'] = $http['body']['data']['bill']['usage']['pageviews_ctr'];
        }
        if (!empty($http['body']['data']['plan_limits']['pageviews_ctr'])) {
            $overview['pv_quota'] = round($http['body']['data']['plan_limits']['pageviews_ctr'], 0);
        }
        if (!empty($http['body']['data']['plan_details']['title'])) {
            $overview['plan_title'] = $http['body']['data']['plan_details']['title'];
        }
        if (!empty($http['body']['data']['css_size_pp'])) {
            $overview['css_size_pp'] = round($http['body']['data']['css_size_pp'], 1);
        }
        if (!empty($http['body']['data']['image_comp_avg_cp'])) {
            $overview['image_comp_avg_cp'] = round($http['body']['data']['image_comp_avg_cp'], 1);
        }
        $overview['pv_remaining'] = $overview['pv_quota'] - $overview['pv_used'];
        $overview['pp_used'] = $overview['pv_quota'] > 0 ? round(($overview['pv_used'] / $overview['pv_quota']) * 100, 0) : 0;

        $overview['optimized_url_count'] = RabbitLoader_21_Core::getCacheCount();
        if ($overview['optimized_url_count'] > $overview['canonical_url_count']) {
            //local cache might have removed URLs
            $overview['optimized_url_count'] = $overview['canonical_url_count'];
        }
        $optimized_url_per = empty($overview['canonical_url_count']) ? 0 : ($overview['optimized_url_count'] / $overview['canonical_url_count']) * 100;
        $overview['optimized_url_per'] = round($optimized_url_per, 1);


        RabbitLoader_21_Core::getWpOption($rl_wp_options);
        if (!empty($http['body']['data']['rl_latest_plugin_v'])) {
            $rl_wp_options['rl_latest_plugin_v'] = $http['body']['data']['rl_latest_plugin_v'];
        }
        if (empty($rl_wp_options['rl_varnish'])) {
            $rl_wp_options['rl_varnish'] = self::check_varnish(2) ? 1 : -1;
        }
        RabbitLoader_21_Core::updateWpOption($rl_wp_options);

        set_transient('rabbitloader_trans_overview_data', $overview, 60);
        return $overview;
    }

    protected static function quota_used_box(&$overview, $show_arrow)
    {
    ?>
        <div class="bg-white rounded p-4" title="<?php RL21UtilWP::_e(sprintf('You have consumed %s page-views out of %s page-views monthly quota available in your current plan.', intval($overview['pv_used']), $overview['pv_quota'])); ?>">
            <h4 class="<?php echo $overview['pp_used'] >= 99 ? "text-danger" : ""; ?>">
                <?php echo intval($overview['pv_used']); ?>/<small style="font-size:14px;"><?php echo $overview['pv_quota']; ?> </small>
            </h4>
            <a class="rl-dash-link" href="<?php echo self::getTabUrl('usage'); ?>">
                <span class="text-secondary mt-2"><?php RL21UtilWP::_e(sprintf('PageViews Used (%s Plan)', $overview['plan_title'])); ?>
                    <span class="dashicons dashicons-arrow-right-alt mt-05 <?php echo !$show_arrow ? 'd-none' : ''; ?>"></span>
                </span>
            </a>
        </div>
    <?php
    }

    protected static function quota_remaining_box(&$overview)
    {
        $gb_remaining_nz = $overview['pv_remaining'] < 0 ? 0 : intval($overview['pv_remaining']);
    ?>
        <div class="bg-white rounded p-4" title="<?php RL21UtilWP::_e(sprintf('You have %s page-views available out of %s page-views monthly quota in the current billing cycle.', $gb_remaining_nz, $overview['pv_quota'])); ?>">
            <h4 class="<?php echo $overview['pp_used'] >= 99 ? "text-danger" : ""; ?>"><?php echo $gb_remaining_nz; ?>/<small style="font-size:14px;"><?php echo $overview['pv_quota']; ?> </small></h4>
            <span class="text-secondary mt-2"><?php RL21UtilWP::_e('PageViews Remaining'); ?></span> <a target="_blank" href="<?php echo self::getUpgradeLink('quota_remaining', $overview['plan_title']); ?>" class="badge rl-bg-primary text-white" style="text-decoration: none;"><?php RL21UtilWP::_e('GET MORE'); ?></a>
        </div>
    <?php
    }

    protected static function urls_detected_box(&$overview, $show_arrow)
    {
        $title = RL21UtilWP::__(sprintf('Cache exists for %d hot URL(s) out of total %d URL(s) detected',  $overview['optimized_url_count'], $overview['canonical_url_count']));
    ?>
        <div class="bg-white rounded p-4 tpopup" title="<?php echo $title; ?>">
            <h4 class=""><?php echo $overview['optimized_url_count']; ?>/<small style="font-size:14px;"><?php echo $overview['canonical_url_count']; ?></small></h4>
            <a class="rl-dash-link" href="<?php echo self::getTabUrl('urls'); ?>">
                <span class="text-secondary mt-2"><?php RL21UtilWP::_e('Hot URLs Cache'); ?>
                    <span class="dashicons dashicons-arrow-right-alt mt-05 <?php echo !$show_arrow ? 'd-none' : ''; ?>"></span>
                </span>
            </a>
        </div>
    <?php
    }

    protected static function optimization_image_home(&$overview, $show_arrow)
    {
    ?>
        <div class="bg-white rounded p-4 tpopup" title="<?php RL21UtilWP::_e(sprintf('Images on the website are converted to WebP and resulted in to %s%% lesser size.', round($overview['image_comp_avg_cp'], 2))); ?>">
            <h4 class="">
                <?php echo round($overview['image_comp_avg_cp'], 2); ?><small style="font-size:14px;">%</small>
            </h4>
            <a class="rl-dash-link" href="<?php echo self::getTabUrl('images'); ?>">
                <span class="text-secondary mt-2"><?php RL21UtilWP::_e('Image Compression'); ?>
                    <span class="dashicons dashicons-arrow-right-alt mt-05 <?php echo !$show_arrow ? 'd-none' : ''; ?>"></span>
                </span>
            </a>
        </div>
    <?php
    }

    protected static function optimization_css_home(&$overview, $show_arrow)
    {
    ?>
        <div class="bg-white rounded p-4 tpopup" title="<?php RL21UtilWP::_e(sprintf('%s%% reduction in CSS is achieved in form of Critical CSS that is required for initial page rendering.', round($overview['css_size_pp'], 2))); ?>">
            <h4 class="">
                <?php echo round($overview['css_size_pp'], 2); ?><small style="font-size:14px;">%</small>
            </h4>
            <a class="rl-dash-link" href="<?php echo self::getTabUrl('css'); ?>">
                <span class="text-secondary mt-2"><?php RL21UtilWP::_e('CSS Reduction'); ?>
                    <span class="dashicons dashicons-arrow-right-alt mt-05 <?php echo !$show_arrow ? 'd-none' : ''; ?>"></span>
                </span>
            </a>
        </div>
<?php
    }

    protected static function addDtDependencies()
    {
        wp_enqueue_script('rabbitloader-datatable-js', '//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', ['jquery']);
        wp_enqueue_style('rabbitloader-datatable-css', '//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
    }

    protected static function getUpgradeLink($utm_term, $plan_title)
    {
        return RabbitLoader_21_Core::getRLDomain() . "pricing/?utm_source=wordpress&utm_medium=plugin&utm_term=$utm_term#domain=" . urlencode(get_home_url()) . "/";
    }

    private static function check_varnish($attempts)
    {
        $httpcode = 0;
        try {
            $url_id = home_url() . '/';
            $url_parts = parse_url($url_id);
            $port = (empty($url_parts['scheme']) || $url_parts['scheme'] == 'https') ? '443' : '80';
            $ch = curl_init($url_id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PURGE");
            curl_setopt($ch, CURLOPT_RESOLVE, array($url_parts['host'] . ":$port:127.0.0.1"));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_exec($ch);
            //$curl_error = curl_error($ch);
            $httpcode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
            curl_close($ch);
        } catch (Throwable $e) {
        }
        if ($httpcode == 200) {
            return true;
        } else if ($attempts > 0) {
            $attempts--;
            if ($attempts == 0) {
                return false;
            }
            return self::check_varnish($attempts);
        }
    }
}
?>