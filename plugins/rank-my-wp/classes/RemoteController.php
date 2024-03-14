<?php

class RKMW_Classes_RemoteController {

    public static $cache = array();
    public static $apimethod = 'get';
    public static $checkin = false;
    /**
     * Call the Cloud Server
     * @param string $module
     * @param array $args
     * @param array $options
     * @return string
     */
    public static function apiCall($module, $args = array(), $options = array()) {
        $parameters = "";

        //predefined options
        $options = array_merge(
            array(
                'method' => self::$apimethod,
                'sslverify' => RKMW_CHECK_SSL,
                'timeout' => 15,
                'headers' => array(
                    'USER-TOKEN' => RKMW_Classes_Helpers_Tools::getOption('api'),
                    'USER-URL' => apply_filters('rkmw_homeurl', get_bloginfo('url')),
                    'LANG' => apply_filters('rkmw_language', get_bloginfo('language')),
                    'VERSQ' => (int)str_replace('.', '', RKMW_VERSION)
                )
            ),
            $options);

        try {
            if (!empty($args)) {
                foreach ($args as $key => $value) {
                    if ($value <> '') {
                        $parameters .= ($parameters == "" ? "" : "&") . $key . "=" . urlencode($value);
                    }
                }
            }

            //call it with http to prevent curl issues with ssls
            $url = self::cleanUrl(RKMW_API_URL . $module . "?" . $parameters);

            if (!isset(self::$cache[md5($url)])) {
                if ($options['method'] == 'post') {
                    $options['body'] = $args;
                }

                self::$cache[md5($url)] = self::rkmw_wpcall($url, $options);
            }

            return self::$cache[md5($url)];


        } catch (Exception $e) {
            return '';
        }

    }

    /**
     * Clear the url before the call
     * @param string $url
     * @return string
     */
    private static function cleanUrl($url) {
        return str_replace(array(' '), array('+'), $url);
    }

    public static function generatePassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $password;
    }

    /**
     * Get My Cloud Link
     *
     * @param $path
     * @return string
     */
    public static function getCloudLink($path) {
        if (RKMW_Classes_Helpers_Tools::getMenuVisible('panel') && current_user_can('rkmw_manage_settings')) {
            return RKMW_DASH_URL . 'login/?token=' . RKMW_Classes_Helpers_Tools::getOption('api') . '&user_url=' . apply_filters('rkmw_homeurl', get_bloginfo('url')) . '&redirect_to=' . RKMW_DASH_URL . 'user/' . $path;
        } else {
            return RKMW_DASH_URL;
        }
    }

    /**
     * Get API Link
     *
     * @param string $path
     * @param integer $version
     * @return string
     */
    public static function getApiLink($path) {
        return RKMW_API_URL . $path . '?token=' . RKMW_Classes_Helpers_Tools::getOption('api') . '&url=' . apply_filters('rkmw_homeurl', get_bloginfo('url'));
    }

    /**
     * Use the WP remote call
     *
     * @param $url
     * @param $options
     * @return array|bool|string|WP_Error
     */
    public static function rkmw_wpcall($url, $options) {
        $method = $options['method'];

        switch ($method) {
            case 'get':
                //not accepted as option
                unset($options['method']);

                $response = wp_remote_get($url, $options);
                break;
            case 'post':
                //not accepted as option
                unset($options['method']);

                $response = wp_remote_post($url, $options);
                break;
            default:
                $response = wp_remote_request($url, $options);
                break;
        }

        if (is_wp_error($response)) {
            RKMW_Classes_Error::setError($response->get_error_message(), 'rkmw_error');
            return false;
        }

        $response = self::cleanResponce(wp_remote_retrieve_body($response)); //clear and get the body

        return $response;
    }

    /**
     * Get the Json from responce if any
     * @param string $response
     * @return string
     */
    private static function cleanResponce($response) {
        return trim($response, '()');
    }

    /**********************  USER ******************************/
    /**
     * @param array $args
     * @return array|mixed|object|WP_Error
     */
    public static function connect($args = array()) {
        self::$apimethod = 'post'; //call method
        $json = json_decode(self::apiCall('api/user/connect', $args));

        if (isset($json->error) && $json->error <> '') {

            if ($json->error == 'invalid_token') {
                RKMW_Classes_Helpers_Tools::saveOptions('api', false);
            }
            if ($json->error == 'disconnected') {
                RKMW_Classes_Helpers_Tools::saveOptions('api', false);
            }
            if ($json->error == 'banned') {
                RKMW_Classes_Helpers_Tools::saveOptions('api', false);
            }
            return (new WP_Error('api_error', $json->error));
        }

        return $json;
    }

    /**
     * Login user to API
     *
     * @param array $args
     * @return bool|WP_Error
     */
    public static function login($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/user/login', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * Register user to API
     *
     * @param array $args
     * @return bool|WP_Error
     */
    public static function register($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/user/register', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * User Checkin
     *
     * @param array $args
     * @return bool|WP_Error
     */
    public static function checkin($args = array()) {
        self::$apimethod = 'get'; //call method

        if(self::$checkin){
            return self::$checkin;
        }

        $json = json_decode(self::apiCall('api/user/checkin', $args));

        if (isset($json->error) && $json->error <> '') {

            //prevent throttling on API
            if ($json->error == 'too_many_requests') {
                RKMW_Classes_Error::setError(esc_html__("Too many API attempts, please slow down the request.", RKMW_PLUGIN_NAME));
                RKMW_Classes_Error::hookNotices();
                return (new WP_Error('api_error', $json->error));
            } elseif ($json->error == 'maintenance') {
                RKMW_Classes_Error::setError(esc_html__("Rank My WP Cloud is down for a bit of maintenance right now. But we'll be back in a minute.", RKMW_PLUGIN_NAME));
                RKMW_Classes_Error::hookNotices();
                return (new WP_Error('maintenance', $json->error));
            }

            self::connect(); //connect the website
            return (new WP_Error('api_error', $json->error));

        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        self::$checkin = $json->data;

        return $json->data;
    }

    /******************************** BRIEFCASE *********************/
    public static function getBriefcase($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/briefcase/get', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    public static function addBriefcaseKeyword($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/briefcase/add', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    public static function removeBriefcaseKeyword($args = array()) {
        self::$apimethod = 'post'; //call method

        if ($json = json_decode(self::apiCall('api/briefcase/hide', $args))) {
            return $json;
        }

        return false;
    }

    public static function getBriefcaseStats($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/briefcase/stats', $args));

        if (isset($json->error) && $json->error <> '') {
            self::connect(); //connect the website

            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    public static function saveBriefcaseKeywordLabel($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/briefcase/label/keyword', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }


    public static function addBriefcaseLabel($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/briefcase/label/add', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    public static function saveBriefcaseLabel($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/briefcase/label/save', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    public static function removeBriefcaseLabel($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/briefcase/label/delete', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }


    /******************************** KEYWORD RESEARCH ****************/

    /**
     * Get KR Countries
     * @param array $args
     * @return bool|WP_Error
     */
    public static function getKrCountries($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/kr/countries', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    public static function getKROthers($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/kr/other', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * Set Keyword Research
     *
     * @param array $args
     * @return array|bool|mixed|object|WP_Error
     */
    public static function setKRSuggestion($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/kr/suggestion', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    public static function getKRSuggestion($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/kr/suggestion', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /******************************** KEYWORD HISTORY & FOUND  ****************/

    /**
     * Get Keyword Research History
     * @param array $args
     * @return array|bool|mixed|object|WP_Error
     */
    public static function getKRHistory($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/kr/history', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * Get the Kr Found by API
     * @param array $args
     * @return bool|WP_Error
     */
    public static function getKrFound($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/kr/found', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /** Remove Keyword from Suggestions
     * @param array $args
     * @return bool|WP_Error
     */
    public static function removeKrFound($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/kr/found/delete', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }


    /******************** RANKINGS ***************************/

    /**
     * Add a keyword in Rank Checker
     * @param array $args
     * @return bool|WP_Error
     */
    public static function addSerpKeyword($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/briefcase/serp', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * Delete a keyword from Rank Checker
     * @param array $args
     * @return bool|WP_Error
     */
    public static function deleteSerpKeyword($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/briefcase/serp-delete', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * Get the Ranks for this blog
     * @param array $args
     * @return bool|WP_Error
     */
    public static function getRanksStats($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/serp/stats', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * Get the Ranks for this blog
     * @param array $args
     * @return bool|WP_Error
     */
    public static function getRanks($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/serp/get-ranks', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**
     * Refresh the rank for a page/post
     *
     * @param array $args
     * @return bool|WP_Error
     */
    public static function checkPostRank($args = array()) {
        self::$apimethod = 'get'; //call method

        $json = json_decode(self::apiCall('api/serp/refresh', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }

    /**************************************** CONNECTIONS */
    /**
     * Disconnect Google Search Console account
     *
     * @return bool|WP_Error
     */
    public static function revokeGscConnection() {
        self::$apimethod = 'get'; //post call

        $json = json_decode(self::apiCall('api/gsc/revoke'));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;

    }

    public static function syncGSC($args = array()) {
        self::$apimethod = 'get'; //post call

        $json = json_decode(self::apiCall('api/gsc/sync/kr', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;

    }

    /******************** OTHERS *****************************/
    public static function saveOptions($args) {
        self::$apimethod = 'post'; //call method

        self::apiCall('api/user/settings', array('settings' => wp_json_encode($args)));
    }

    public static function saveFeedback($args = array()) {
        self::$apimethod = 'post'; //call method

        $json = json_decode(self::apiCall('api/user/feedback', $args));

        if (isset($json->error) && $json->error <> '') {
            return (new WP_Error('api_error', $json->error));
        } elseif (!isset($json->data)) {
            return (new WP_Error('api_error', 'no_data'));
        }

        if (!empty($json->data)) {
            return $json->data;
        }

        return false;
    }
}
