<?php

class UBHTTP
{
    public static $form_confirmation_url_regex = '/(.+)\/[a-z]+-form_confirmation\.html/i';
    public static $lightbox_url_regex = '/(.+)\/[a-z]+-[0-9]+-lightbox\.html/i';
    public static $variant_url_regex = '/(.+)\/[a-z]+\.html/i';
    public static $pie_htc_url = '/PIE.htc';
    public static $location_header_regex = '/^(?:Location):/i';

    public static function is_public_ip_address($ip_address)
    {
        return filter_var(
            $ip_address,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE + FILTER_FLAG_NO_RES_RANGE
        );
    }

    // Removes last public IP address from an array of IP addresses, along with any private IP
    // addresses that come after it, as long as there is at least one remaining public IP address.
    private static function remove_last_public_ip_address($ip_addresses)
    {
        $public_ip_address_indexes = array();
        foreach ($ip_addresses as $index => $ip_address) {
            if (UBHTTP::is_public_ip_address($ip_address)) {
                $public_ip_address_indexes[] = $index;
            }
        }

        if (count($public_ip_address_indexes) < 2) {
            return $ip_addresses;
        }

        return array_slice(
            $ip_addresses,
            0,
            $public_ip_address_indexes[count($public_ip_address_indexes) - 1]
        );
    }

    private static function get_last_public_ip_address($ip_addresses)
    {
        for ($i = count($ip_addresses) - 1; $i >= 0; $i--) {
            if (UBHTTP::is_public_ip_address($ip_addresses[$i])) {
                return $ip_addresses[$i];
            }
        }
        return null;
    }

    private static function split_header_values($values)
    {
        return array_filter(array_map('trim', explode(',', $values ?: '')));
    }

    public static function cookie_string_from_array($cookies)
    {
        $join_cookie_values = function ($k, $v) {
            return $k . '=' . $v;
        };
        $cookie_strings = array_map(
            $join_cookie_values,
            array_keys($cookies),
            $cookies
        );
        return join('; ', $cookie_strings);
    }

    public static function get_forwarded_headers(
        $domain,
        $current_protocol,
        $current_forwarded_for,
        $current_forwarded_proto,
        $current_remote_ip
    ) {
        // X-Forwarded-For: preserve existing values so Page Server etc can see the full chain and
        // choose whether to use the leftmost (closest to first client) or rightmost (trusted) IP.
        // If this plugin has been configured to trust a proxy in front of it with a public IP
        // address, remove the last public IP address from the right, so the new rightmost value
        // will become the rightmost one set by that proxy.
        $current_forwarded_for = UBHTTP::split_header_values($current_forwarded_for);
        $target_forwarded_for = $current_forwarded_for;
        // In case the last X-Forwarded-For value matches the remote IP address, avoid appending a
        // duplicate value. It seems that this shouldn't be possible but it was observed with
        // Bluehost.
        if (UBUtil::array_fetch($current_forwarded_for, count($current_forwarded_for) - 1) !== $current_remote_ip) {
            $target_forwarded_for = array_merge($current_forwarded_for, array($current_remote_ip));
        }
        if (UBConfig::allow_public_address_x_forwarded_for()) {
            $target_forwarded_for = UBHTTP::remove_last_public_ip_address($target_forwarded_for);
        }

        // X-Forwarded-Host: remove existing values so Page Server etc only see the configured
        // domain of this WordPress installation regardless of any proxies in front of it, because
        // this plugin should only be used to fetch content for that domain.
        $target_forwarded_host = $domain;

        // X-Forwarded-Proto: preserve existing values so Page Server etc can see entire chain and
        // likely choose to use the leftmost value (closest to first client).
        $target_forwarded_proto = ($current_forwarded_proto ? $current_forwarded_proto . ', ' : '') .
            $current_protocol;

        // X-Proxied-For: legacy header intended to contain a single trusted IP of the first client.
        $target_proxied_for = UBHTTP::get_last_public_ip_address($target_forwarded_for)
            ?: $target_forwarded_for[0];

        return array(
            'x-forwarded-for' => implode(', ', $target_forwarded_for),
            'x-forwarded-host' => $target_forwarded_host,
            'x-forwarded-proto' => $target_forwarded_proto,
            'x-proxied-for' => $target_proxied_for
        );
    }

    public static function stream_headers_function($dynamic_config)
    {
        $header_filter = UBHTTP::create_curl_response_header_filter($dynamic_config);

        return function ($curl, $header_line) use ($header_filter) {
            if ($header_filter($header_line)) {
                // false means don't replace the exsisting header
                header($header_line, false);
            }

            // We must show curl that we've processed every byte of the input header
            return strlen($header_line);
        };
    }

    public static function stream_response_function()
    {
        return function ($curl, $string) {
            // Stream the body to the client
            echo $string;

            // We must show curl that we've processed every byte of the input string
            return strlen($string);
        };
    }

    // Get protocol of current request from client to WordPress
    public static function get_current_protocol($server_global, $wp_is_ssl)
    {
        // Wordpress' is_ssl() may return the correct boolean for http/https if the site was set up
        // properly.
        $https = UBUtil::array_fetch($server_global, 'HTTPS', 'off');
        if ($wp_is_ssl || !is_null($https) && $https !== 'off') {
            return 'https';
        }

        // Next use REQUEST_SCHEME, if it is available. This is the recommended way to get the
        // protocol, but it is not available on all hosts.
        $request_scheme = UBUtil::array_fetch($server_global, 'REQUEST_SCHEME');
        if (UBHTTP::is_valid_protocol($request_scheme)) {
            return $request_scheme;
        }

        // Next try to pull it out of the SCRIPT_URI. This is also not always available.
        $script_uri = UBUtil::array_fetch($server_global, 'SCRIPT_URI');
        $script_uri_scheme = parse_url($script_uri, PHP_URL_SCHEME);
        if (UBHTTP::is_valid_protocol($script_uri_scheme)) {
            return $script_uri_scheme;
        }

        // We default to http as most HTTPS sites will also have HTTP available.
        return 'http';
    }

    // Determine protocol to use for request from WordPress to Page Server
    public static function determine_protocol($server_global, $wp_is_ssl)
    {
        $forwarded_proto = UBUtil::array_fetch($server_global, 'HTTP_X_FORWARDED_PROTO');
        $first_valid_forwarded_proto = UBUtil::array_fetch(
            array_values(
                array_filter(
                    UBHTTP::split_header_values($forwarded_proto),
                    array('UBHTTP', 'is_valid_protocol')
                )
            ),
            0
        );

        $request_scheme = UBUtil::array_fetch($server_global, 'REQUEST_SCHEME');
        $script_uri = UBUtil::array_fetch($server_global, 'SCRIPT_URI');
        $script_uri_scheme = parse_url($script_uri, PHP_URL_SCHEME);
        $https = UBUtil::array_fetch($server_global, 'HTTPS', 'off');

        UBLogger::debug_var('UBHTTP::forwarded_proto', $forwarded_proto);
        UBLogger::debug_var('UBHTTP::first_valid_forwarded_proto', $first_valid_forwarded_proto);
        UBLogger::debug_var('UBHTTP::request_scheme', $request_scheme);
        UBLogger::debug_var('UBHTTP::script_uri', $script_uri);
        UBLogger::debug_var('UBHTTP::script_uri_scheme', $script_uri_scheme);
        UBLogger::debug_var('UBHTTP::https', $https);

        // X-Forwarded-Proto should be respected first, as it is what the end
        // user will see (if Wordpress is behind a load balancer).
        if ($first_valid_forwarded_proto) {
            return $first_valid_forwarded_proto;
        }

        return UBHTTP::get_current_protocol($server_global, $wp_is_ssl);
    }

    private static function is_valid_protocol($protocol)
    {
        return $protocol === 'http' || $protocol === 'https';
    }

    // taken from: http://stackoverflow.com/a/13036310/322727
    public static function convert_headers_to_curl($headers)
    {
        // map to curl-friendly format
        $req_headers = array();
        array_walk($headers, function (&$v, $k) use (&$req_headers) {
            $req_headers[] = $k . ": " . $v;
        });

        return $req_headers;
    }

    public static function stream_request(
        $target_method,
        $target_url,
        $target_user_agent,
        $current_headers,
        $current_protocol,
        $domain
    ) {
        // Always add this header to responses to show it comes from our plugin.
        header("X-Unbounce-Plugin: 1", false);
        $dynamic_config = UBConfig::read_unbounce_dynamic_config($domain);

        if (UBConfig::use_curl()) {
            return UBHTTP::stream_request_curl(
                $target_method,
                $target_url,
                $target_user_agent,
                $current_headers,
                $current_protocol,
                $domain,
                $dynamic_config
            );
        } else {
            return UBHTTP::stream_request_wp_remote(
                $target_method,
                $target_url,
                $target_user_agent,
                $current_headers,
                $current_protocol,
                $domain,
                $dynamic_config
            );
        }
    }

    private static function stream_request_wp_remote(
        $target_method,
        $target_url,
        $target_user_agent,
        $current_headers,
        $current_protocol,
        $domain,
        $dynamic_config
    ) {
        $args = array(
            'method' => $target_method,
            'user-agent' => $target_user_agent,
            'redirection' => 0,
            'timeout' => 30,
            'headers' => array_merge(
                UBHTTP::prepare_request_headers($current_headers, $current_protocol, $domain, $dynamic_config),
                array(
                    'x-ub-wordpress-remote-request' => '1',
                    'accept-encoding' => null
                )
            )
        );
        if ($target_method == 'POST') {
            $args['body'] = file_get_contents('php://input');
        }

        $resp = wp_remote_request($target_url, $args);
        if (is_wp_error($resp)) {
            $message = "Error proxying to '" . $target_url . "': " . $resp->get_error_message();
            UBLogger::warning($message);
            http_response_code(500);
            return array(false, $message);
        } else {
            http_response_code($resp['response']['code']);
            $response_headers = $resp['headers'];
            UBHTTP::set_response_headers($response_headers, $dynamic_config);
            echo $resp['body'];
            return array(true, null);
        }
    }

    public static function prepare_request_headers($current_headers, $current_protocol, $domain, $dynamic_config)
    {
        
        $request_header_allow = UBUtil::array_fetch($dynamic_config, 'request_header_allow', UBConfig::UB_DEFAULT_REQUEST_HEADER_ALLOW);
        $request_header_add = UBUtil::array_fetch($dynamic_config, 'request_header_add', UBConfig::UB_DEFAULT_REQUEST_HEADER_ADD);
        $request_cookie_allow = UBUtil::array_fetch($dynamic_config, 'request_cookie_allow', UBConfig::UB_DEFAULT_REQUEST_COOKIE_ALLOW);

        $current_forwarded_for = UBUtil::array_fetch($current_headers, 'x-forwarded-for');
        $current_forwarded_proto = UBUtil::array_fetch($current_headers, 'x-forwarded-proto');
        $current_remote_ip = UBUtil::array_fetch($_SERVER, 'REMOTE_ADDR');

        // remove current host header as we will be setting it to the target domain
        unset($current_headers['host']);
    
        $merged_headers = array_merge(
            UBHTTP::sanitize_cookies($current_headers, $request_cookie_allow),
            UBHTTP::get_forwarded_headers(
                $domain,
                $current_protocol,
                $current_forwarded_for,
                $current_forwarded_proto,
                $current_remote_ip
            ),
            UBHTTP::get_common_headers()
        );
        
        $target_headers = array();
        array_walk($merged_headers, function ($v, $k) use (&$target_headers, $request_header_allow) {
            if (preg_match($request_header_allow, $k)) {
                $target_headers[$k] = $v;
            }
        });

        foreach ($request_header_add as $key => $value) {
            $target_headers[$key] = $value;
        }
        return $target_headers;
    }

    private static function get_common_headers()
    {
        $headers = array(
            'host' => UBConfig::page_server_domain(),
            'x-ub-wordpress-plugin-version' => '1.1.2'
        );

        try {
            // OS info:
            // - 's': Operating system name. eg. Linux
            // - 'r': Release name. eg. 5.4.39-linuxkit
            // - 'm': Machine type. eg. x86_64
            $os_info = implode(' ', array_map('php_uname', array('s', 'r', 'm')));
            $curl_version = curl_version();
            $headers = array_merge($headers, array(
                'x-ub-wordpress-wordpress-version' => UBDiagnostics::wordpress_version(),
                'x-ub-wordpress-php-version' => phpversion(),
                'x-ub-wordpress-curl-version' => $curl_version['version'],
                'x-ub-wordpress-ssl-version' => $curl_version['ssl_version'],
                'x-ub-wordpress-allow-public-addr-xff' => UBConfig::allow_public_address_x_forwarded_for() ? '1' : '0',
                'x-ub-wordpress-os' => $os_info,
            ));
        } catch (Throwable $e) {
            UBLogger::warning('Failed to build diagnostic headers: ' . $e);
        }

        try {
            $headers['x-ub-wordpress-sni-support'] = UBDiagnostics::has_sni() ? '1' : '0';
        } catch (Throwable $e) {
            UBLogger::warning('Failed to build SNI diagnostic header: ' . $e);
        }

        return $headers;
    }

    public static function sanitize_cookies($headers, $request_cookie_allow)
    {
        $cookie_key = "Cookie";
        if (!array_key_exists($cookie_key, $headers)) {
            $cookie_key = "cookie"; // fallback to trying lowercase
            if (!array_key_exists($cookie_key, $headers)) {
                return $headers;
            }
        }

        $cookies_to_forward = UBUtil::array_select_by_key(
            UBHTTP::cookie_array_from_string($headers[$cookie_key]),
            $request_cookie_allow
        );

        $headers[$cookie_key] = UBHTTP::cookie_string_from_array($cookies_to_forward);
        return $headers;
    }

    public static function cookie_array_from_string($cookie_string)
    {
        $cookie_kv_array = array();
        $cookie_flat_array = explode('; ', $cookie_string);
        foreach ($cookie_flat_array as $itm) {
            list($key, $val) = explode('=', $itm, 2);
            $cookie_kv_array[$key] = $val;
        }
        return $cookie_kv_array;
    }

    public static function set_response_headers($headers, $dynamic_config)
    {
        $header_filter = UBHTTP::create_response_header_filter($dynamic_config);

        foreach ($headers as $h_key => $h_value) {
            if ($header_filter($h_key)) {
                if (is_array($h_value)) {
                    foreach ($h_value as $header_item) {
                        header($h_key . ': ' . $header_item, false);
                    }
                } else {
                    header($h_key . ': ' . $h_value, false);
                }
            }
        }
    }

    private static function stream_request_curl(
        $target_method,
        $target_url,
        $target_user_agent,
        $current_headers,
        $current_protocol,
        $domain,
        $dynamic_config
    ) {
        $base_response_headers = headers_list();

        $target_headers = UBHTTP::prepare_request_headers($current_headers, $current_protocol, $domain, $dynamic_config);
        $target_headers = UBHTTP::convert_headers_to_curl($target_headers);

        UBLogger::debug_var('target_url', $target_url);
        UBLogger::debug_var('current_headers', print_r($current_headers, true));
        UBLogger::debug_var('target_headers', print_r($target_headers, true));

        $stream_headers = UBHTTP::stream_headers_function($dynamic_config);
        $stream_body = UBHTTP::stream_response_function();
        $curl = curl_init();
        // http://php.net/manual/en/function.curl-setopt.php
        $curl_options = array(
        CURLOPT_URL => $target_url,
        CURLOPT_POST => $target_method == "POST",
        CURLOPT_CUSTOMREQUEST => $target_method,
        CURLOPT_USERAGENT => $target_user_agent,
        CURLOPT_HTTPHEADER => $target_headers,
        CURLOPT_HEADERFUNCTION => $stream_headers,
        CURLOPT_WRITEFUNCTION => $stream_body,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 30
        );

        if ($target_method == "POST") {
            // Use raw post body to allow the same post key to occur more than once
            $curl_options[CURLOPT_POSTFIELDS] = file_get_contents('php://input');
        }

        curl_setopt_array($curl, $curl_options);
        $resp = curl_exec($curl);
        if (!$resp) {
            $message = "Error proxying to '" . $target_url . "': " . curl_error($curl) . " - Code: " . curl_errno($curl);
            UBLogger::warning($message);
            if (UBHTTP::is_location_response_header_set()) {
                UBLogger::debug("The location header was set despite the cURL error. Assuming it's safe to let the response flow back");
                $result = array(true, null);
            } else {
                http_response_code(500);
                $result = array(false, $message);
            }
        } else {
            $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            http_response_code($http_status_code);
            $result = array(true, null);
        }

        curl_close($curl);

        return $result;
    }

    private static function is_location_response_header_set()
    {
        $resp_headers = headers_list();
        foreach ($resp_headers as $value) { //headers at this point are raw strings, not K -> V
            if (preg_match(UBHTTP::$location_header_regex, $value)) {
                return true;
            }
        }
        return false;
    }

    public static function is_extract_url_proxyable(
        $proxyable_url_set,
        $extract_regex,
        $match_position,
        $url
    ) {
        $matches = array();
        $does_match = preg_match(
            $extract_regex,
            $url,
            $matches
        );

        return $does_match && in_array($matches[1], $proxyable_url_set);
    }

    public static function is_confirmation_dialog($proxyable_url_set, $url_without_protocol)
    {
        return UBHTTP::is_extract_url_proxyable(
            $proxyable_url_set,
            UBHTTP::$form_confirmation_url_regex,
            1,
            $url_without_protocol
        );
    }

    public static function is_lightbox($proxyable_url_set, $url_without_protocol)
    {
        return UBHTTP::is_extract_url_proxyable(
            $proxyable_url_set,
            UBHTTP::$lightbox_url_regex,
            1,
            $url_without_protocol
        );
    }

    public static function is_variant($proxyable_url_set, $url_without_protocol)
    {
        return UBHTTP::is_extract_url_proxyable(
            $proxyable_url_set,
            UBHTTP::$variant_url_regex,
            1,
            $url_without_protocol
        );
    }

    public static function is_tracking_link($proxyable_url_set, $url_without_protocol)
    {
        return UBHTTP::is_extract_url_proxyable(
            $proxyable_url_set,
            "/^(.+)?\/(clkn|clkg)\/?/",
            1,
            $url_without_protocol
        );
    }

    public static function get_url_purpose($proxyable_url_set, $http_method, $url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        $path = rtrim(parse_url($url, PHP_URL_PATH), '/');
        $url_without_protocol = $host . $path;

        UBLogger::debug_var('get_url_purpose $host', $host);
        UBLogger::debug_var('get_url_purpose $path', $path);
        UBLogger::debug_var('get_url_purpose $url_without_protocol', $url_without_protocol);

        if ($http_method == 'GET' && $path == '/_ubhc') {
            return 'HealthCheck';
        } elseif (preg_match("/^\/_ub\/[\w.-]/", $path)) {
            return "GenericProxyableRequest";
        } elseif ($http_method == "POST" &&
        preg_match("/^\/(fsn|fsg|fs)\/?$/", $path)) {
            return "SubmitLead";
        } elseif ($http_method == "GET" &&
              UBHTTP::is_tracking_link($proxyable_url_set, $url_without_protocol)) {
            return "TrackClick";
        } elseif (($http_method == "GET" || $http_method == "POST") &&
               (in_array($url_without_protocol, $proxyable_url_set) ||
                UBHTTP::is_confirmation_dialog($proxyable_url_set, $url_without_protocol) ||
                UBHTTP::is_lightbox($proxyable_url_set, $url_without_protocol) ||
                UBHTTP::is_variant($proxyable_url_set, $url_without_protocol))) {
            return "ViewLandingPage";
        } elseif ($http_method == "GET" && $path == UBHTTP::$pie_htc_url) {
            // proxy PIE.htc
            return "ViewLandingPage";
        } else {
            return null;
        }
    }

    private static function create_curl_response_header_filter($dynamic_config)
    {
        $blocklist_regex = '/^connection:/i';
        $config_headers_forwarded = UBConfig::response_headers_forwarded();

        if ($config_headers_forwarded === array('*')) {
            return function ($header) use ($blocklist_regex) {
                return !preg_match($blocklist_regex, $header);
            };
        }

        $allowlist = array_merge($config_headers_forwarded, UBUtil::array_fetch($dynamic_config, 'response_header_allow', array()));
        $allowlist_regex = '/^('.implode('|', $allowlist).'):/i';
        return function ($header) use ($blocklist_regex, $allowlist_regex) {
            return preg_match($allowlist_regex, $header) && !preg_match($blocklist_regex, $header);
        };
    }

    private static function create_response_header_filter($dynamic_config)
    {
        $config_headers_forwarded = UBConfig::response_headers_forwarded();

        if ($config_headers_forwarded === array('*')) {
            return function ($header) {
                return strcasecmp($header, 'connection') !== 0;
            };
        }

        $allowlist = array_merge($config_headers_forwarded, UBUtil::array_fetch($dynamic_config, 'response_header_allow', array()));

        return function ($header) use ($allowlist) {
            // headers in the allow list are lowercase
            $header = strtolower($header);
            return $header !== 'connection' && in_array($header, $allowlist);
        };
    }
}
