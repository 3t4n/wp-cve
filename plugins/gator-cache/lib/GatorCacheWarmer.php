<?php
/**
 * CacheWarmer
 *
 * Cache warming class for GatorCache.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class GatorCacheWarmer
{
    protected $siteUrl;
    protected $group;
    protected $args = array(
        'timeout'     => 1,
        'redirection' => 3,
        'httpversion' => '1.0',
        'user-agent'  => '',
        'blocking'    => true,
        'headers'     => array(),
        'cookies'     => array(),
        'body'        => null,
        'compress'    => false,
        'decompress'  => true,
        'sslverify'   => false,
        'stream'      => false,
        'filename'    => null
    );

    private $mobileAgent;
    private static $debug = false;

    public function __construct($url, array $args = null)
    {
        $this->args['user-agent'] = 'GatorWarmer/' . WpGatorCache::VERSION . '; ' . ($this->siteUrl = $url);
        if (isset($args)) {
            $this->args = $args + $this->args;
        }
        $this->mobileAgent = 'Mozilla/5.0 (iPhone; GatorWarmerCriOS/' . WpGatorCache::VERSION . ')';
    }

    public function warmUri($uri, $mobile = false)
    {
        return $this->warmUrl($this->siteUrl . $uri, $mobile);
    }

    public function warmUrl($url, $mobile = false)
    {
        if(self::$debug){
            return wp_remote_get($url, $mobile ? array('user-agent' => $this->mobileAgent) + $this->args : $this->args);
        }
        wp_remote_get($url, $mobile ? array('user-agent' => $this->mobileAgent) + $this->args : $this->args);
    }

    /**
     * multiPing
     * 
     * Warm alot of files at once, experimental.
     * 
     * Behaves differently for certain versions of php. Not used in the plugin, but included for developer use.
     */ 
    public function multiPing($urls)
    {
        if (!extension_loaded('curl')) {
            return false;
        }
        $handles = array();
        $options = array(
            CURLOPT_USERAGENT      => $this->args['user-agent'],
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE        => 0,
            CURLOPT_TIMEOUT        => 1,
        ); 
        $multiHandle = curl_multi_init();
        foreach($urls as $key => $url) {
            $handles[$key] = curl_init();
            curl_setopt_array($handles[$key], $options);
            curl_setopt($handles[$key], CURLOPT_URL, $url);
            curl_multi_add_handle($multiHandle , $handles[$key]);
        }
        do {
            $mrc = curl_multi_exec($multiHandle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        while ($active && $mrc == CURLM_OK) {
            if (-1 != curl_multi_select($multiHandle)) {
                do {
                    $mrc = curl_multi_exec($multiHandle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        return true;
        $running = null;
        do {
            curl_multi_exec($multiHandle , $running);
        }
        while ($running > 0);

        foreach ($handles as $key => $val) {
            //$results[$key] = curl_multi_getcontent($val);
            curl_multi_remove_handle($multiHandle , $val);
        }

        curl_multi_close($multiHandle );
        return true;
        //return $results;
    }

    public static function setDebug($debug = true)
    {
        if (true === $debug) {
            self::$debug = true;
            return;
        }
        self::$debug = false;
    }
}
