<?php


class ConveyThisCache
{

    public function __construct()
    {

    }

    public static function checkCachePlugin()
    {
        $installCachePlugin = false;
        if (function_exists('w3tc_flush_all')
            || class_exists('LiteSpeed_Cache')
            || function_exists('wp_cache_clear_cache')
            || function_exists('wpfc_clear_all_cache')
            || function_exists('rocket_clean_domain')
            || function_exists('hyper_cache_clean')
            || function_exists('sc_cache_flush')
            || class_exists('Cache_Enabler') || has_action('cachify_flush_cache')) {
            $installCachePlugin = true;
        }
        return $installCachePlugin;
    }

    /*
     * Clearing page cache by installed plugin
     */
    public static function clearPageCache($url = '', $page_id = null)
    {

        if (strlen($url) > 0) {
            if (function_exists('flush_url')) {
                flush_url($url);
            }

            if (class_exists('LiteSpeed_Cache')) {
                LiteSpeed_Cache::plugin()->purge_url($url);
            }

            if (function_exists('wp_cache_clear_cache') && !!$page_id) {
                wp_cache_clear_cache($page_id);
            }

            if (function_exists('wpfc_clear_all_cache') && !!$page_id) {
                wpfc_clear_post_cache($page_id);
            }

            if (function_exists('rocket_clean_files')) {
                rocket_clean_files($url);
            }

            if (function_exists('hyper_cache_clean') && !!$page_id) {
                hyper_cache_invalidate_post($page_id);
            }

            if (function_exists('sc_cache_flush') && !!$page_id) {

            }

            if (class_exists('Cache_Enabler') && method_exists('Cache_Enabler', 'clear_total_cache') && !!$page_id) {
                Cache_Enabler::clear_post_cache($page_id);
            }

            if (has_action('cachify_flush_cache') && !!$page_id) {
                do_action('cachify_flush_cache', $page_id);
            }

        }
    }

    public function clearAllCache()
    {
        if ($_POST['conveythis_clear_all_cache'] == true && $_POST['api_key'] == $this->variables->api_key) {
            self::flush_cache_on_activate();
        }
        die( json_encode (['clear' => true]));
    }

    public static function flush_cache_on_activate(){
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
        }
        // LiteSpeed
        if (class_exists('LiteSpeed_Cache')) {
            LiteSpeed_Cache::plugin()->purge_all();
        }
        /*if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
            LiteSpeed_Cache_API::purge_all();
        }*/
        // WP Fastest Cache
        if (function_exists('wpfc_clear_all_cache')) {
            wpfc_clear_all_cache();
        }
        // WP Fastest Cache
        if (function_exists('wpfc_clear_all_cache')) {
            wpfc_clear_all_cache();
        }
        // WP Rocket
        if (function_exists('rocket_clean_domain')) {
            rocket_clean_domain();
        }
        if (function_exists('hyper_cache_clean')) {
            hyper_cache_clean();
        }
        // Simple Cache
        if (function_exists('sc_cache_flush')) {
            sc_cache_flush();
        }
        // W3 Total Cache : w3tc
        if (function_exists('w3tc_pgcache_flush')) {
            w3tc_pgcache_flush();
        }
        // WP Super Cache : wp-super-cache
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
        // WP Fastest Cache
        if (function_exists('wpfc_clear_all_cache')) {
            wpfc_clear_all_cache(true);
        }
        // WPEngine
        if (class_exists('WpeCommon') && method_exists('WpeCommon', 'purge_varnish_cache')) {
            WpeCommon::purge_memcached();
            WpeCommon::clear_maxcdn_cache();
            WpeCommon::purge_varnish_cache();
        }
        // SG Optimizer by Siteground
        if (function_exists('sg_cachepress_purge_cache')) {
            sg_cachepress_purge_cache();
        }
        // Cache Enabler
        if (class_exists('Cache_Enabler') && method_exists('Cache_Enabler', 'clear_total_cache')) {
            Cache_Enabler::clear_total_cache();
        }
        // Comet cache
        if (class_exists('comet_cache') && method_exists('comet_cache', 'clear')) {
            comet_cache::clear();
        }
        // Pagely
        if (class_exists('PagelyCachePurge') && method_exists('PagelyCachePurge', 'purgeAll')) {
            PagelyCachePurge::purgeAll();
        }
        // Autoptimize
        if (class_exists('autoptimizeCache') && method_exists('autoptimizeCache', 'clearall')) {
            autoptimizeCache::clearall();
        }
        // Hummingbird Cache
        if (class_exists('\Hummingbird\WP_Hummingbird') && method_exists('\Hummingbird\WP_Hummingbird', 'flush_cache')) {
            \Hummingbird\WP_Hummingbird::flush_cache();
        }
        // Autoptimize
        if (has_action('cachify_flush_cache')) {
            do_action('cachify_flush_cache');
        }
    }

    public function flush_cache($option){

        if ($option == 'target_languages_translations') {

            if (function_exists('w3tc_flush_all')) {
                w3tc_flush_all();
            }
            if (class_exists('LiteSpeed_Cache')) {
                LiteSpeed_Cache::plugin()->purge_all();
            }
            if (function_exists('wp_cache_clear_cache')) {
                wp_cache_clear_cache();
            }
            if (function_exists('wpfc_clear_all_cache')) {
                wpfc_clear_all_cache();
            }
            if ( function_exists( 'rocket_clean_domain' ) ) {
                rocket_clean_domain();
            }
            if (function_exists('hyper_cache_clean')) {
                hyper_cache_clean();
            }
            if (function_exists('sc_cache_flush')) {
                sc_cache_flush();
            }
            if (class_exists('Cache_Enabler') && method_exists('Cache_Enabler', 'clear_total_cache')) {
                Cache_Enabler::clear_total_cache();
            }
            if ( has_action('cachify_flush_cache') ) {
                do_action('cachify_flush_cache');
            }

        }

    }

    public function dismissAllCacheMessages()
    {
        if ($_POST['dismiss']) {
            $this->dismissNotice('all_cache_notice');
        }
    }

    public function save_cached_slug( $slug, $source_language, $target_language, $value ) {

        if ( !file_exists( CONVEYTHIS_CACHE_PATH ) ) {
            mkdir( CONVEYTHIS_CACHE_PATH, 0777, true );
            $slug_list = array();
        }else {
            $slug_list = file_exists( CONVEYTHIS_CACHE_SLUG_PATH ) ? json_decode( file_get_contents( CONVEYTHIS_CACHE_SLUG_PATH ), true ) : [];
            if ( empty($slug_list) ) {
                $slug_list = array();
            }
        }
        if ( !isset($slug_list[$source_language]) ) {
            $slug_list[$source_language] = array();
        }
        if ( !isset($slug_list[$source_language][$slug]) ) {
            $slug_list[$source_language][$slug] = array();
        }
        $slug_list[$source_language][$slug][$target_language] = $value;
        file_put_contents(CONVEYTHIS_CACHE_SLUG_PATH, json_encode($slug_list) );
    }

    public function get_cached_translations( $source_language, $target_language, $path, $cacheKey ) {
        $file = CONVEYTHIS_CACHE_TRANSLATIONS_PATH. $source_language. '_'. $target_language. '/'. md5($path). '.json';
        $cacheContent = [];
        if (file_exists($file)) {
            // If cache has been created/modified more than 3 days ago, delete it
            if (time() - filemtime($file) > 259200) {
                @unlink($file);
                return $cacheContent;
            }
            $fileContents = json_decode(file_get_contents($file), true);
            if (isset($fileContents[$cacheKey]) && $fileContents[$cacheKey]) {
                $cacheContent = $fileContents[$cacheKey];
            }
        }
        return $cacheContent;
    }

    public function save_cached_translations($sourceLanguage, $targetLanguage, $path, $data, $cacheKey = 'cache_key')
    {
        $langDir = $this->getCacheLangDir($sourceLanguage, $targetLanguage);
        $cachePath = $langDir . $this->getCacheFileName($path);
        $cacheData[$cacheKey] = $data;
        if ($data) {
            if (!file_exists($langDir)) {
                mkdir($langDir, 0777, true);
            }
            file_put_contents($cachePath, json_encode($cacheData));
        } elseif (file_exists($cachePath)) {
            unlink($cachePath);
        }
    }

    private function clearDir($dir = '')
    {
        $clearResult = false;

        if (strlen($dir) > 0) {
            $dir_iterator = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);

            foreach (new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::CHILD_FIRST) as $name => $item) {

                if (is_dir($name)) {
                    rmdir($name);
                } else {
                    unlink($name);
                }
            }
            $clearResult = rmdir($dir);
        }
        return $clearResult;
    }
    public function clear_cached_translations($all = false, $path = '', $sourceLanguage = '', $targetLanguage = '')
    {

        $result = false;
        if ($all) {
            if (file_exists(CONVEYTHIS_CACHE_TRANSLATIONS_PATH) && is_dir(CONVEYTHIS_CACHE_TRANSLATIONS_PATH)) {
                $result = $this->clearDir(CONVEYTHIS_CACHE_TRANSLATIONS_PATH);
            }
        } else {
            if (strlen($path) > 0) {
                $cachePath = $this->getCacheLangDir($sourceLanguage, $targetLanguage) . $this->getCacheFileName($path);
                if(file_exists($cachePath)) {
                    $result = unlink($cachePath);
                }
            }
        }

        return $result;
    }

    private function getCacheLangDir($sourceLanguage = '', $targetLanguage = '')
    {
        return CONVEYTHIS_CACHE_TRANSLATIONS_PATH . $sourceLanguage . '_' . $targetLanguage;
    }

    private function getCacheFileName($path)
    {
        return '/' . md5($path) . '.json';
    }

    public function get_cached_slug( $slug, $source_language, $target_language ) {
        if (file_exists( CONVEYTHIS_CACHE_SLUG_PATH )) {
            $slug_list = json_decode( file_get_contents(CONVEYTHIS_CACHE_SLUG_PATH), true );

            if ( !empty($slug_list) ) {
                if ( isset($slug_list[$source_language][$slug][$target_language]) ) {
                    return $slug_list[$source_language][$slug][$target_language];
                }
            }
        }
        return false;
    }

    public function clearCacheTime() {



    }

//    public function getCacheSize()
//    {
//        if ($this->variables->cacheTranslateSize == 0) {
//            $iterator = new RecursiveIteratorIterator(
//                new RecursiveDirectoryIterator(CONVEYTHIS_CACHE_TRANSLATIONS_PATH, FilesystemIterator::SKIP_DOTS)
//            );
//            $size = 0;
//            foreach ($iterator as $file) {
//                $size += $file->getSize();
//            }
//            if ($size > 0) {
//                $size = (round($size / 1048576 * 100) / 100);//MB
//            }
//            $this->variables->cacheTranslateSize = $size;
//        }
//        return $this->variables->cacheTranslateSize;
//    }
}