<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/15/18
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data;

use Buzz\Browser;
use Buzz\Client\Curl;

class CommonHelper
{
    // @codingStandardsIgnoreStart
    /**
     * Create browser
     * @return Browser
     */
    public static function createBrowser()
    {
        $client = new Curl();
        $client->setTimeout(10);

        return new Browser($client);
    }

    /**
     * [IMPORTANT] Just works on Wordpress
     * Retrieves a modified URL query string. (borrow from WP)
     *
     * You can rebuild the URL and append query variables to the URL query by using this function.
     * There are two ways to use this function; either a single key and value, or an associative array.
     *
     * Using a single key and value:
     *
     *     add_query_arg( 'key', 'value', 'http://example.com' );
     *
     * Using an associative array:
     *
     *     add_query_arg( array(
     *         'key1' => 'value1',
     *         'key2' => 'value2',
     *     ), 'http://example.com' );
     *
     * Omitting the URL from either use results in the current URL being used
     * (the value of `$_SERVER['REQUEST_URI']`).
     *
     * Values are expected to be encoded appropriately with urlencode() or rawurlencode().
     *
     * Setting any query variable's value to boolean false removes the key (see remove_query_arg()).
     *
     * Important: The return value of add_query_arg() is not escaped by default. Output should be
     * late-escaped with esc_url() or similar to help prevent vulnerability to cross-site scripting
     * (XSS) attacks.
     *
     * @since 1.5.0
     *
     * @param string|array $key Either a query variable key, or an associative array of query variables.
     * @param string $value Optional. Either a query variable value, or a URL to act upon.
     * @param string $url Optional. A URL to act upon.
     * @return string New URL query string (unescaped).
     */
    public static function addQueryArg()
    {
        $args = func_get_args();

        $urlParameters = http_build_query(
            filter_input_array(
                INPUT_GET,
                FILTER_SANITIZE_URL
            )
        );
        $requestUri = filter_input(INPUT_SERVER, 'SCRIPT_URL', FILTER_SANITIZE_URL) . ($urlParameters ? "?{$urlParameters}" : "");

        if (is_array($args[0])) {
            if (count($args) < 2 || false === $args[1]) {
                $uri = $requestUri;
            } else {
                $uri = $args[1];
            }
        } else {
            if (count($args) < 3 || false === $args[2]) {
                $uri = $requestUri;
            } else {
                $uri = $args[2];
            }
        }

        if ($frag = strstr($uri, '#')) {
            $uri = substr($uri, 0, -strlen($frag));
        } else {
            $frag = '';
        }

        if (0 === stripos($uri, 'http://')) {
            $protocol = 'http://';
            $uri = substr($uri, 7);
        } elseif (0 === stripos($uri, 'https://')) {
            $protocol = 'https://';
            $uri = substr($uri, 8);
        } else {
            $protocol = '';
        }

        if (strpos($uri, '?') !== false) {
            list($base, $query) = explode('?', $uri, 2);
            $base .= '?';
        } elseif ($protocol || strpos($uri, '=') === false) {
            $base = $uri . '?';
            $query = '';
        } else {
            $base = '';
            $query = $uri;
        }

        wp_parse_str($query, $qs);
        $qs = urlencode_deep($qs); // this re-URL-encodes things that were already in the query string
        if (is_array($args[0])) {
            foreach ($args[0] as $k => $v) {
                $qs[$k] = $v;
            }
        } else {
            $qs[$args[0]] = $args[1];
        }

        foreach ($qs as $k => $v) {
            if ($v === false) {
                unset($qs[$k]);
            }
        }

        $ret = build_query($qs);
        $ret = trim($ret, '?');
        $ret = preg_replace('#=(&|$)#', '$1', $ret);
        $ret = $protocol . $base . $ret . $frag;
        $ret = rtrim($ret, '?');
        return $ret;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Make sure absolute path is right format
     * Some case the hosting will redirect 302 if missing `/` at end of absolute path
     * @param $url string
     * @return string
     */
    public static function parseAbsolutePath($url)
    {
        if (substr($url, -1) != '/') {
            $url = $url . '/';
        }
        return $url;
    }

    /**
     * Common func return timestamp with timezone UTC
     * @param $datetime
     * @param \DateTimeZone $inputTimeZone
     * @return string
     * @throws \Exception
     */
    public static function formatDate($datetime, $inputTimeZone = null)
    {
        // Fixed output timezone to UTC
        $outputTimeZone = new \DateTimeZone("UTC");

        // Set default input timezone
        // Almost platforms store datetime in UTC
        if (!$inputTimeZone) {
            $inputTimeZone = new \DateTimeZone("UTC");
        }

        try {
            if (is_numeric($datetime)) {
                $date = new \DateTime('@{$datetime}', $inputTimeZone);
            } elseif (is_string($datetime)) {
                $date = new \DateTime($datetime, $inputTimeZone);
            } elseif ($datetime instanceof \DateTime) {
                $date = $datetime;
            } else {
                $date = new \DateTime('now', $inputTimeZone);
            }
        } catch (\Exception $e) {
            $date = new \DateTime('now', $inputTimeZone);
        }

        // Make sure output always UTC timezone
        $date->setTimezone($outputTimeZone);

        return $date->format(DATE_RFC2822);
    }

    /**
     * [IMPORTANT] Just works on Wordpress
     * Get shop URL
     * It just works. I don't know why. I copy this function from old plugin
     * @return string
     */
    public static function getShopURL()
    {
        $site_url = get_home_url();
        $url_parsed = parse_url($site_url);
        $host = isset($url_parsed['host']) ? $url_parsed['host'] : '';
        $www = get_query_var('www', null);

        if ($www !== null) {
            if (in_array($www, array(0, false))) {
                $host = preg_replace('/^www\./', '', $host);
            } elseif (!preg_match('/^www\./', $host) && in_array($www, array(1, true))) {
                $host = 'www.' . $host;
            }
        }

        return $host;
    }
}
