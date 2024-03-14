<?php
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Class WpmsHandlerWizard
 */
class WpmsHandlerWizard
{
    /**
     * WpmsHandlerWizard constructor.
     */
    public function __construct()
    {
    }

    /**
     * Save Environment handle
     *
     * @param string $current_step Current step
     *
     * @return void
     */
    public static function saveEvironment($current_step)
    {
        check_admin_referer('wpms-setup-wizard', 'wizard_nonce');
        /*
         * Do no thing
         */
        $wizard = new WpmsInstallWizard();
        wp_safe_redirect(esc_url_raw($wizard->getNextLink($current_step)));
        exit;
    }

    /**
     * Save social
     *
     * @param string $current_step Current step
     *
     * @return void
     */
    public static function saveSocial($current_step)
    {
        check_admin_referer('wpms-setup-wizard', 'wizard_nonce');

        $options = array(
            'metaseo_showfacebook' => '',
            'metaseo_showfbappid'  => '',
            'metaseo_showtwitter'  => '',
            'metaseo_twitter_card' => 'summary'
        );

        foreach ($options as $name => $value) {
            if (isset($_POST[$name])) {
                wpmsSetOption($name, $_POST[$name]);
            }
        }
        $wizard = new WpmsInstallWizard();
        wp_safe_redirect(esc_url_raw($wizard->getNextLink($current_step)));
        exit;
    }

    /**
     * Save home meta
     *
     * @param string $current_step Current step
     *
     * @return void
     */
    public static function saveMetaInfos($current_step)
    {
        check_admin_referer('wpms-setup-wizard', 'wizard_nonce');
        $options = array(
            'home_meta_active'       => 1,
            'metaseo_showtmetablock' => 1,
            'metaseo_title_home'     => '',
            'metaseo_desc_home'      => ''
        );

        foreach ($options as $name => $value) {
            if (isset($_POST[$name])) {
                wpmsSetOption($name, $_POST[$name]);
            }
        }
        $wizard = new WpmsInstallWizard();
        wp_safe_redirect(esc_url_raw($wizard->getNextLink($current_step)));
        exit;
    }

    /**
     * Save Google Analytics
     *
     * @param string $current_step Current step
     *
     * @return void
     */
    public static function saveGoogleAnalytics($current_step)
    {
        check_admin_referer('wpms-setup-wizard', 'wizard_nonce');
        if (!empty($_POST['wpms_ga_code'])) {
            $wpms_ga_code  = $_POST['wpms_ga_code'];
            $midnight      = strtotime('tomorrow 00:00:00'); // UTC midnight
            $midnight      = $midnight + 8 * 3600; // UTC 8 AM
            $error_timeout = $midnight - time();

            require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
            $client = WpmsGaTools::initClient(WPMS_CLIENTID, WPMS_CLIENTSECRET);
            $service          = new WPMSGoogle_Service_Analytics($client);
            $google_alanytics = array();
            if (!stripos('x' . $wpms_ga_code, 'UA-', 1)) {
                WpmsGaTools::deleteCache('gapi_errors');
                WpmsGaTools::deleteCache('last_error');
                WpmsGaTools::clearCache();
                try {
                    $client->authenticate($wpms_ga_code);
                    $getAccessToken = $client->getAccessToken();
                    if ($getAccessToken) {
                        try {
                            $client->setAccessToken($getAccessToken);
                            $google_alanytics['googleCredentials']
                                = $client->getAccessToken();
                        } catch (WPMSGoogle\Service\Exception $e) {
                            WpmsGaTools::setCache(
                                'wpmsga_dash_lasterror',
                                date('Y-m-d H:i:s') . ': ' . esc_html('(' . $e->getCode() . ') ' . $e->getMessage()),
                                $error_timeout
                            );
                            WpmsGaTools::setCache(
                                'wpmsga_dash_gapi_errors',
                                $e->getCode(),
                                $error_timeout
                            );
                        } catch (Exception $e) {
                            WpmsGaTools::setCache(
                                'wpmsga_dash_lasterror',
                                date('Y-m-d H:i:s') . ': ' . esc_html($e),
                                $error_timeout
                            );
                        }
                    }

                    if (!empty($google_alanytics['profile_list'])) {
                        $profiles = $google_alanytics['profile_list'];
                    } else {
                        $profiles = WpmsGaTools::refreshProfiles($service, $getAccessToken['access_token'], $error_timeout);
                    }

                    $google_alanytics['code']              = $wpms_ga_code;
                    $google_alanytics['googleCredentials'] = $getAccessToken;
                    $google_alanytics['profile_list']      = $profiles;
                    update_option('wpms_google_alanytics', $google_alanytics);
                } catch (WPMSGoogle\Service\Exception $e) {
                    echo '';
                } catch (Exception $e) {
                    echo '';
                }
            } else {
                echo '<div class="error"><p>' . esc_html__('The access code is 
<strong>NOT</strong> your <strong>Tracking ID</strong>
 (UA-XXXXX-X). Try again, and use the red link to get your access code', 'wp-meta-seo') . '.</p></div>';
            }

            update_option('wpms_google_alanytics', $google_alanytics);
        }

        if (isset($_POST['wpms_ga_uax_reference'])) {
            $opts                          = get_option('_metaseo_ggtracking_disconnect_settings');
            $opts['wpms_ga_uax_reference'] = $_POST['wpms_ga_uax_reference'];
            update_option(
                '_metaseo_ggtracking_disconnect_settings',
                $opts
            );
        }

        $wizard = new WpmsInstallWizard();
        wp_safe_redirect(esc_url_raw($wizard->getNextLink($current_step)));
        exit;
    }
}
