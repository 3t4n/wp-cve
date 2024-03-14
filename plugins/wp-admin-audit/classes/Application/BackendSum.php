<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_BackendSum
{
    protected $apiEndpoint;
    public $lastCode = null;
    public $lastError = null;
    public $lastMessage = null;
    protected static $productUniqueId = 'WADA-FREE';
    public static $transientIdForExtensionRepo = 'wada_sum_extension_repo';

    public function __construct(){
        $apiHost = 'https://wpadminaudit.com';
        if('false' === 'true'
            && 'false' === 'true'){
            $apiHost = 'http://wpadminaudit.local';
        }
        $this->apiEndpoint = $apiHost.'/sum/';
    }

    protected static function getDomain(){
        return str_replace(array ("https://" , "http://"), "", trim(network_site_url(), '/'));
    }

    protected function getCallParameters($key=null){
        global $wp_version;
        if(is_null($key)){
            $key = WADA_Settings::getLicenseKey();
        }
        return array(
            'key'       => $key,
            'd'         => self::getDomain(),
            'p'         => self::$productUniqueId,
            'v'         => '1.2.9',
            'php'       => phpversion(),
            'wp'        => $wp_version
        );
    }

    protected static function getInstalledPluginInfoForSumApi(){
        global $wpdb;
        $query = 'SELECT * FROM '.WADA_Database::tbl_extensions();
        $extensions = $wpdb->get_results($query);
        $extensionArr = array();
        foreach($extensions AS $extension){
            $extensionArr[$extension->plugin_folder] = $extension;
        }

        $plugins = array();
        $installedPlugins = WADA_PluginUtils::getAllPlugins();
        foreach($installedPlugins AS $plgFolder => $installedPlugin){
            $plg = new stdClass();
            $plg->name = $installedPlugin->Name;
            $plg->folder = $plgFolder;
            $plg->isWadaExtension = array_key_exists($plgFolder, $extensionArr);
            $plg->wadaExtensionId = $plg->isWadaExtension ? $extensionArr[$plgFolder]->id : 0;
            $plg->version = $installedPlugin->Version;
            $plg->active = $installedPlugin->active;
            $plugins[] = $plg;
        }
        return $plugins;
    }

    public function getRequestUrl($action, $searchTerm = null){
        $baseUrl = $this->apiEndpoint.urlencode($action);
        $args = $this->getCallParameters();
        if($searchTerm){
            $args['s'] = urlencode(sanitize_text_field($searchTerm));
        }
        return add_query_arg($args, $baseUrl);
    }

    /**
     * Intercept plugins_api() calls that request information about our plugin
     *
     * @see plugins_api()
     *
     * @param mixed $result
     * @param string $action
     * @param array|object $args
     * @return mixed
     */
    public static function injectPluginInfos($result, $action = null, $args = null){
        $relevantOne = ($action == 'plugin_information') && isset($args->slug) && !is_object($result);
        if ( !$relevantOne ) {
            return $result; // do not interfere with result!
        }
        WADA_Log::debug('injectPluginInfos action: '.$action.', args: '.print_r($args, true).', result before: '.print_r($result, true));

        $cachedWadaExtensions = self::getExtensionRepoCache();
        $cachedWadaExtensions = $cachedWadaExtensions ? self::ungroupExtensionRepositoryObj($cachedWadaExtensions) : array();
        $wadaExtension = null;
        foreach($cachedWadaExtensions AS $cachedWadaExtension){
            if($cachedWadaExtension->wada_slug === $args->slug){
                $wadaExtension = $cachedWadaExtension;
                break;
            }
        }

        if ( !$wadaExtension ) {
            WADA_Log::debug('injectPluginInfos bail two');
            return $result; // do not interfere with result!
        }
        WADA_Log::debug('injectPluginInfos WILL OVERRIDE action: '.$action.', args: '.print_r($args, true).', result before: '.print_r($result, true));

        $info = new stdClass();
        $info->name = $wadaExtension->name;
        $info->slug = $wadaExtension->wada_slug;
        $info->upgrade_notice = $wadaExtension->upgrade_notice;
        $info->version = $wadaExtension->current_release_number;
        $info->download_url = $wadaExtension->download_link;

        $info->homepage = $wadaExtension->homepage;
        $info->requires = $wadaExtension->requires_wp;
        $info->tested = $wadaExtension->tested_wp;
        $info->requires_php = $wadaExtension->requires_php;
        $info->last_updated = $wadaExtension->last_updated;

        $info->sections = array('description' => $wadaExtension->description, 'changelog' => $wadaExtension->changelog);

        WADA_Log::debug('injectPluginInfos DONE overriding action: '.$action.', args: '.print_r($args, true).', result after: '.print_r($info, true));

        return $info;
    }

    // whenever any plugin gets installed/updated/activated/deactivated, we want to reset/purge our extension cache
    public static function autoResetExtensionCacheOnPluginLifecycleActivities(){
        add_action('upgrader_process_complete', array(__CLASS__, 'resetExtensionRepoCache'));
        add_action('activated_plugin', array(__CLASS__, 'resetExtensionRepoCache'));
        add_action('deactivated_plugin', array(__CLASS__, 'resetExtensionRepoCache'));
        add_action('deleted_plugin', array(__CLASS__, 'resetExtensionRepoCache'));
        add_action('automatic_updates_complete', array(__CLASS__, 'resetExtensionRepoCache'));
    }

    public static function resetExtensionRepoCache(){
        WADA_Log::debug('resetExtensionRepoCache');
        delete_transient(self::$transientIdForExtensionRepo);
    }

    public static function getExtensionRepoCache(){
        WADA_Log::debug('getExtensionRepoCache');
        return get_transient(self::$transientIdForExtensionRepo);
    }

    // default = expires after one hour (at the latest)
    public static function setExtensionRepoCache($extensionRepo, $expiration = HOUR_IN_SECONDS){
        return set_transient(self::$transientIdForExtensionRepo, $extensionRepo, $expiration);
    }

    protected static function ungroupExtensionRepositoryObj($extensionRepo){
        $ungrouped = array();
        $groups = array('ready', 'issue', 'installed');
        foreach($groups AS $group){
            if(property_exists($extensionRepo, $group) && is_array($extensionRepo->$group) && count($extensionRepo->$group) > 0){
                foreach($extensionRepo->$group AS $plugin){
                    $ungrouped[$plugin->wada_plugin_path] = $plugin;
                    $ungrouped[$plugin->wada_plugin_path]->result_group = $group;
                }
            }
        }
        WADA_Log::debug('ungrouped: '.print_r($ungrouped, true));
        return $ungrouped;
    }

    public function getExtensionRepository($forceReload = false, $returnUngrouped = false){
        $result = self::getExtensionRepoCache();
        if($result === false || $forceReload) {
            $requestUrl = $this->getRequestUrl('extension-repo');
            WADA_Log::debug('getExtensionRepository requestUrl: ' . $requestUrl);
            $installedPlugins = self::getInstalledPluginInfoForSumApi();
            WADA_Log::debug('getExtensionRepository #installedPlugins: ' . count($installedPlugins));
            //WADA_Log::debug('getExtensionRepository installedPlugins: '.print_r($installedPlugins, true));
            $jsonData = json_encode($installedPlugins);

            list($result, $httpCode, $curlError) = self::sendRequestToSumApi($requestUrl, $jsonData);

            if ($result && intval($httpCode) >= 200 && intval($httpCode) < 300 && !$curlError) {
                self::setExtensionRepoCache($result);
            }
        }else{
            WADA_Log::debug('getExtensionRepository result from transient: '.print_r($result, true));
        }
        if($returnUngrouped && $result){
            return self::ungroupExtensionRepositoryObj($result);
        }else {
            return $result;
        }
    }

    protected static function sendRequestToSumApi($requestUrl, $jsonData = '')
    {
        WADA_Log::debug( 'sendRequestToSumApi requestUrl: ' . $requestUrl);
        WADA_Log::debug( 'sendRequestToSumApi jsonData: ' . $jsonData);
        WADA_Log::debug( 'sendRequestToSumApi HTTP Host: ' . $_SERVER['HTTP_HOST']);
        $result = $httpCode = $curlError = null;

        $curl = curl_init($requestUrl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // TODO MAKE SSL VERIFICATION A SETTING THAT CAN BE TURNED ON/OFF
        if ('false' === 'true'
            || ($_SERVER['HTTP_HOST'] == 'wordpress4x.local')
            || ($_SERVER['HTTP_HOST'] == 'wordpress5x.local')
            || ($_SERVER['HTTP_HOST'] == 'wordpress6x.local')
            || ($_SERVER['HTTP_HOST'] == 'wordpress7x.local')
        ) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        $result = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        WADA_Log::errorIfFalse($result, 'sendRequestToSumApi requestUrl: ' . $requestUrl);
        WADA_Log::errorIfFalse($result, 'sendRequestToSumApi jsonData: ' . $jsonData);
        WADA_Log::debugOrError($result, 'sendRequestToSumApi curlError: ' . $curlError);
        WADA_Log::debugOrError($result, 'sendRequestToSumApi httpCode: ' . $httpCode);
        WADA_Log::debugOrError($result, 'sendRequestToSumApi result: ' . $result);
        $resultObj = json_decode($result, false);
        WADA_Log::debugOrError($result, 'sendRequestToSumApi resultObj: ' . print_r($resultObj, true));

        return array($resultObj, $httpCode, $curlError);
    }

}