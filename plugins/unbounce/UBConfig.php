<?php

class UBConfig
{
    const DYNAMIC_CONFIG_DEFAULT_TIMEOUT = 86400;
    const DYNAMIC_CONFIG_FAILURE_TIMEOUT = 1200;

    const UB_PLUGIN_NAME           = 'ub-wordpress';
    const UB_CACHE_TIMEOUT_ENV_KEY = 'UB_WP_ROUTES_CACHE_EXP';
    const UB_USER_AGENT            = 'Unbounce WP Plugin 1.1.2';
    const UB_VERSION               = '1.1.2';

    // WP Admin Pages
    const UB_ADMIN_PAGE_MAIN        = 'unbounce-pages';
    const UB_ADMIN_PAGE_SETTINGS    = 'unbounce-pages-settings';
    const UB_ADMIN_PAGE_DIAGNOSTICS = 'unbounce-pages-diagnostics';

    // Option keys
    const UB_DYNAMIC_CONFIG_CACHE_KEY   = 'ub-dynamic-config-cache';
    const UB_ROUTES_CACHE_KEY        = 'ub-route-cache';
    const UB_PAGE_SERVER_DOMAIN_KEY  = 'ub-page-server-domain';
    const UB_DYNAMIC_CONFIG_DOMAIN_KEY = 'ub-dynamic-config-domain';
    const UB_API_URL_KEY             = 'ub-api-url';
    const UB_API_CLIENT_ID_KEY       = 'ub-api-client-id';
    const UB_AUTHORIZED_DOMAINS_KEY  = 'ub-authorized-domains';
    const UB_HAS_AUTHORIZED_KEY      = 'ub-has-authorized';
    const UB_USER_ID_KEY             = 'ub-user-id';
    const UB_DOMAIN_ID_KEY           = 'ub-domain-id';
    const UB_DOMAIN_UUID_KEY         = 'ub-domain-uuid';
    const UB_CLIENT_ID_KEY           = 'ub-client-id';
    const UB_PROXY_ERROR_MESSAGE_KEY = 'ub-proxy-error-message';
    const UB_ALLOW_PUBLIC_ADDRESS_X_FORWARDED_FOR = 'ub-allow-public-address-x-forwarded-for';
    const UB_PLUGIN_VERSION_KEY      = 'ub-plugin-version';
    const UB_USE_CURL_KEY            = 'ub-use-curl';

    const UB_LOCK_NAME               = 'ub-sql-lock';

    const UB_RESPONSE_HEADERS_FORWARDED_KEY = 'ub-response-headers-forwarded';

    const UB_DEFAULT_REQUEST_HEADER_ALLOW = '/^(?:Accept|Content-Type|Referer|User-Agent|If-None-Match|Host|X-Forwarded-.+|X-Proxied-For|X-Ub-Wordpress-.+|Cookie|Accept-Language|Origin|Access-Control-Request-Headers|Access-Control-Request-Method)$/i';
    const UB_DEFAULT_REQUEST_HEADER_ADD = array();
    const UB_DEFAULT_REQUEST_COOKIE_ALLOW = array('ubvs', 'ubpv', 'ubvt', 'hubspotutk');
    const UB_DEFAULT_RESPONSE_HEADER_ALLOW = array(
        'content-length',
        'content-location',
        'content-type',
        'location',
        'link',
        'set-cookie',
        'cf-ray',
        'cf-cache-status',
        'access-control-allow-origin',
        'access-control-allow-credentials',
        'access-control-allow-headers',
        'access-control-max-age'
    );

    public static function ub_option_defaults()
    {
        // All options, used by UBDiagnostics and deactivation hook
        // Arrays are not allowed in class constants, so use a function
        return array(
            UBConfig::UB_DYNAMIC_CONFIG_CACHE_KEY => array(
                'request_header_allow' => UBConfig::UB_DEFAULT_REQUEST_HEADER_ALLOW,
                'request_header_add' => UBConfig::UB_DEFAULT_REQUEST_HEADER_ADD,
                'request_cookie_allow' => UBConfig::UB_DEFAULT_REQUEST_COOKIE_ALLOW,
                'response_header_allow' => UBConfig::UB_DEFAULT_RESPONSE_HEADER_ALLOW
            ),
            UBConfig::UB_ROUTES_CACHE_KEY => array(),
            UBConfig::UB_PAGE_SERVER_DOMAIN_KEY => UBConfig::default_page_server_domain(),
            UBConfig::UB_DYNAMIC_CONFIG_DOMAIN_KEY => UBConfig::default_dynamic_config_retrieval_domain(),
            UBConfig::UB_API_URL_KEY => UBConfig::default_api_url(),
            UBConfig::UB_API_CLIENT_ID_KEY => UBConfig::default_api_client_id(),
            UBConfig::UB_AUTHORIZED_DOMAINS_KEY => UBConfig::default_authorized_domains(),
            UBConfig::UB_HAS_AUTHORIZED_KEY => '',
            UBConfig::UB_USER_ID_KEY => '',
            UBConfig::UB_DOMAIN_ID_KEY => '',
            UBConfig::UB_DOMAIN_UUID_KEY => '',
            UBConfig::UB_CLIENT_ID_KEY => '',
            UBConfig::UB_PROXY_ERROR_MESSAGE_KEY => '',
            UBConfig::UB_ALLOW_PUBLIC_ADDRESS_X_FORWARDED_FOR => 0,
            UBConfig::UB_USE_CURL_KEY => 1,
            UBConfig::UB_PLUGIN_VERSION_KEY => UBConfig::UB_VERSION,
            // headers are expected to be in lowercase to simplify filtering
            UBConfig::UB_RESPONSE_HEADERS_FORWARDED_KEY => array(
                'x-unbounce-pageid',
                'x-unbounce-variant',
                'x-unbounce-visitorid'
            )
        );
    }

    public static function set_options_if_not_exist()
    {
        $ub_options = UBConfig::ub_option_defaults();
        foreach ($ub_options as $key => $default) {
            if (!get_option($key)) {
                add_option($key, $default);
            }
        }
    }

    public static function default_page_server_domain()
    {
        $domain = getenv('UB_PAGE_SERVER_DOMAIN');
        return $domain ? $domain : 'proxy.unbouncepages.com';
    }

    public static function default_dynamic_config_retrieval_domain()
    {
        $domain = getenv('UB_DYNAMIC_CONFIG_DOMAIN');
        return $domain ? $domain : 'wp-config.unbouncepages.com';
    }

    public static function default_api_url()
    {
        $url = getenv('UB_API_URL');
        return $url ? $url : 'https://api.unbounce.com';
    }

    public static function default_api_client_id()
    {
        $client_id = getenv('UB_API_CLIENT_ID');
        return $client_id ? $client_id : '660a311881321b9d4e777993e50875dec5da9cc4ef44369d121544b21da52b92';
    }

    public static function default_authorized_domains()
    {
        $domains = getenv('UB_AUTHORIZED_DOMAINS');
        return $domains ? explode(',', $domains) : array();
    }

    public static function page_server_domain()
    {
        $domain = get_option(UBConfig::UB_PAGE_SERVER_DOMAIN_KEY, UBConfig::default_page_server_domain());

        return UBConfig::add_domain_uuid_subdomain($domain);
    }

    public static function dynamic_config_retrieval_domain()
    {
        $domain = get_option(UBConfig::UB_DYNAMIC_CONFIG_DOMAIN_KEY, UBConfig::default_dynamic_config_retrieval_domain());

        return UBConfig::add_domain_uuid_subdomain($domain);
    }

    private static function add_domain_uuid_subdomain($domain)
    {
        $subdomain = get_option(UBConfig::UB_DOMAIN_UUID_KEY);
    
        if ($subdomain) {
            $domain = $subdomain . '.' . $domain;
        }
    
        return $domain;
    }

    public static function api_url()
    {
        return get_option(UBConfig::UB_API_URL_KEY, UBConfig::default_api_url());
    }

    public static function api_client_id()
    {
        return get_option(UBConfig::UB_API_CLIENT_ID_KEY, UBConfig::default_api_client_id());
    }

    public static function authorized_domains()
    {
        return get_option(UBConfig::UB_AUTHORIZED_DOMAINS_KEY, UBConfig::default_authorized_domains());
    }

    public static function has_authorized()
    {
        return (bool) get_option(UBConfig::UB_HAS_AUTHORIZED_KEY);
    }

    public static function debug_loggging_enabled()
    {
        return (defined('UB_ENABLE_LOCAL_LOGGING') && UB_ENABLE_LOCAL_LOGGING);
    }

    public static function allow_public_address_x_forwarded_for()
    {
        return get_option(UBConfig::UB_ALLOW_PUBLIC_ADDRESS_X_FORWARDED_FOR, 0) == 1;
    }

    public static function create_none_response()
    {
        return array(array('status' => 'NONE'));
    }

    public static function create_same_response($etag, $max_age)
    {
        return array(array('status' => 'SAME'), $etag, $max_age);
    }

    public static function create_new_response_proxyable_url_set($etag, $max_age, $proxyable_url_set)
    {
        return array(array('status' => 'NEW'), $etag, $max_age, $proxyable_url_set);
    }

    public static function create_new_response_dynamic_config($etag, $max_age, $request_header_allow, $request_header_add, $request_cookie_allow, $response_header_allow)
    {
        return array(array('status' => 'NEW'), $etag, $max_age, $request_header_allow, $request_header_add, $request_cookie_allow, $response_header_allow);
    }

    public static function create_failure_response($failure_message)
    {
        return array(array('status' => 'FAILURE',
                       'failure_message' => $failure_message));
    }

    public static function use_curl()
    {
        return get_option(UBConfig::UB_USE_CURL_KEY, 1) == 1;
    }

    public static function domain()
    {
        return parse_url(get_home_url(), PHP_URL_HOST);
    }

    public static function domain_with_port()
    {
        $port = parse_url(get_home_url(), PHP_URL_PORT);
        $host = parse_url(get_home_url(), PHP_URL_HOST);
        if ($port) {
            return $host . ':' . $port;
        } else {
            return $host;
        }
    }

    public static function response_headers_forwarded()
    {
        return get_option(UBConfig::UB_RESPONSE_HEADERS_FORWARDED_KEY);
    }

    public static function fetch_proxyable_url_set($domain, $etag, $ps_domain)
    {
        if (!$domain) {
            $failure_message = 'Domain not provided, not fetching sitemap.xml';
            UBLogger::warning($failure_message);
            return UBConfig::create_failure_response($failure_message);
        }

        try {
            $url = 'https://' . $ps_domain . '/sitemap.xml';
            
            UBLogger::debug("Retrieving routes from '$url', etag: '$etag', host: '$domain'");

            list($data, $http_code, $header_size, $curl_error) = UBConfig::make_curl_request($url, $domain, $etag);
            list($etag, $max_age) = UBConfig::process_headers($data, $header_size);

            if ($http_code == 200) {
                  $body = substr($data, $header_size);
                  list($success, $result) = UBConfig::url_list_from_sitemap($body);

                if ($success) {
                    UBLogger::debug("Retrieved new routes, HTTP code: '$http_code'");
                    return UBConfig::create_new_response_proxyable_url_set($etag, $max_age, $result);
                } else {
                    $errors = join(', ', $result);
                    $failure_message = "An error occurred while processing pages, XML errors: '$errors'";
                    UBLogger::warning($failure_message);
                    return UBConfig::create_failure_response($failure_message);
                }
            }

            return UBConfig::handle_non_200_http_response($http_code, $etag, $max_age, $curl_error, 'routes');
        } catch (Exception $e) {
            $failure_message = "An error occurred while retrieving routes; Error: " . $e;
            UBLogger::warning($failure_message);
            return UBConfig::create_failure_response($failure_message);
        }
    }

    public static function url_list_from_sitemap($string)
    {
        if (is_null($string)) {
            return array(false, array('input is null'));
        }

        if (!UBDiagnostics::is_xml_installed()) {
            return array(false, array('xml extension is not installed'));
        }

        $use_internal_errors = libxml_use_internal_errors(true);
        $sitemap = simplexml_load_string($string);

        if ($sitemap !== false) {
            libxml_use_internal_errors($use_internal_errors);
            $urls = array();

            // Valid XML that is not a valid sitemap.xml will be considered an empty sitemap.xml.
            // We have no easy way to tell the difference between the two.
            if (isset($sitemap->url)) {
                foreach ($sitemap->url as $sitemap_url) {
                    if (isset($sitemap_url->loc)) {
                        $url = (string) $sitemap_url->loc;
                        // URLs come in with protocol and trailing slash, we need just host and path with no
                        // trailing slash internally.
                        $urls[] = parse_url($url, PHP_URL_HOST) . rtrim(parse_url($url, PHP_URL_PATH), '/');
                    }
                }
            }

            return array(true, $urls);
        } else {
            // libXMLError has no default tostring, use print_r to get a string representation of it
            $errors = array_map(function ($error) {
                return print_r($error, true);
            }, libxml_get_errors());
            // Return what we tried to parse for debugging
            $errors[] = "XML content: ${string}";
            libxml_use_internal_errors($use_internal_errors);
            return array(false, $errors);
        }
    }

    public static function read_unbounce_domain_info($domain, $expire_now = false)
    {
        // Bail out if curl is not installed to prevent fatal error
        if (!UBDiagnostics::is_curl_installed()) {
            return array();
        }

        $proxyable_url_set = null;
        $cache_max_time_default = 10;

        $ps_domain = UBConfig::page_server_domain();
                     
        $domains_info = get_option(UBConfig::UB_ROUTES_CACHE_KEY, array());

        if (!is_array($domains_info)) {
            // This is a regression from BEE-878. We aren't sure why the data could be corrupted.
            $domains_info = array();
        }

        $domain_info = UBUtil::array_fetch($domains_info, $domain, array());

        $proxyable_url_set = UBUtil::array_fetch($domain_info, 'proxyable_url_set');
        $proxyable_url_set_fetched_at = UBUtil::array_fetch($domain_info, 'proxyable_url_set_fetched_at');
        $proxyable_url_set_cache_timeout = UBUtil::array_fetch($domain_info, 'proxyable_url_set_cache_timeout');
        $proxyable_url_set_etag = UBUtil::array_fetch($domain_info, 'proxyable_url_set_etag');

        $cache_max_time = is_null($proxyable_url_set_cache_timeout) ?
          $cache_max_time_default :
          $proxyable_url_set_cache_timeout;

        $current_time = time();

        if ($expire_now ||
            is_null($proxyable_url_set) ||
            ($current_time - $proxyable_url_set_fetched_at > $cache_max_time)) {
            try {
                $can_fetch = UBUtil::get_lock();
                UBLogger::debug('Locking: ' . $can_fetch);

                if ($can_fetch) {
                    $result_array = UBConfig::fetch_proxyable_url_set($domain, $proxyable_url_set_etag, $ps_domain);

                    list(
                        $routes_status,
                        $etag,
                        $max_age,
                        $proxyable_url_set_new
                    ) = [
                        $result_array[0] ?? null,
                        $result_array[1] ?? null,
                        $result_array[2] ?? null,
                        $result_array[3] ?? null
                    ];
                    
                    if ($routes_status['status'] == 'NEW') {
                              $domain_info['proxyable_url_set'] = $proxyable_url_set_new;
                              $domain_info['proxyable_url_set_etag'] = $etag;
                              $domain_info['proxyable_url_set_cache_timeout'] = $max_age;
                    } elseif ($routes_status['status'] == 'SAME') {
                              // Just extend the cache
                              $domain_info['proxyable_url_set_cache_timeout'] = $max_age;
                    } elseif ($routes_status['status'] == 'NONE') {
                              $domain_info['proxyable_url_set'] = array();
                              $domain_info['proxyable_url_set_etag'] = null;
                    } elseif ($routes_status['status'] == 'FAILURE') {
                              UBLogger::warning('Route fetching failed');
                    } else {
                              UBLogger::warning("Unknown response from route fetcher: '$routes_status'");
                    }

                    // Creation of domain_info entry
                    $domain_info['proxyable_url_set_fetched_at'] = $current_time;
                    $domain_info['last_status'] = $routes_status['status'];
                    if ($routes_status['status'] == 'FAILURE') {
                              $domain_info['failure_message'] = $routes_status['failure_message'];
                    }
                    $domains_info[$domain] = $domain_info;
                    // set autoload to false so that options are always loaded from DB
                    update_option(UBConfig::UB_ROUTES_CACHE_KEY, $domains_info, false);
                }
            } catch (Exception $e) {
                UBLogger::warning('Could not update sitemap: ' . $e);
            }

            $release_result = UBUtil::release_lock();
            UBLogger::debug('Unlocking: ' . $release_result);
        }

        return UBUtil::array_select_by_key(
            $domain_info,
            array('proxyable_url_set', 'proxyable_url_set_fetched_at', 'failure_message', 'last_status')
        );
    }

    // We are using a fetched dynamic config to retrieve information
    // such that we decide what request headers to allow and add,
    // as well as what response headers and request cookies to allow.
    public static function read_unbounce_dynamic_config($domain)
    {
        // Check if curl is installed to prevent fatal error
        if (!UBDiagnostics::is_curl_installed()) {
            return array();
        }

        $cache_max_time_default = UBConfig::DYNAMIC_CONFIG_DEFAULT_TIMEOUT;
        $dynamic_config = get_option(UBConfig::UB_DYNAMIC_CONFIG_CACHE_KEY, array());

        if (!is_array($dynamic_config)) {
            $dynamic_config = array();
        }

        $dynamic_config_fetched_at = UBUtil::array_fetch($dynamic_config, 'fetched_at');
        $dynamic_config_cache_timeout = UBUtil::array_fetch($dynamic_config, 'cache_timeout');
        $dynamic_config_etag = UBUtil::array_fetch($dynamic_config, 'etag');

        $cache_max_time = is_null($dynamic_config_cache_timeout) ? $cache_max_time_default : $dynamic_config_cache_timeout;

        // regardless of dynamic_config_cache_timeout being set, if the last fetch failed or returned a 404, we want to try again in 20 minutes
        if (isset($dynamic_config['last_status']) &&
            ($dynamic_config['last_status'] === 'FAILURE' || $dynamic_config['last_status'] === 'NONE')) {
            $cache_max_time = UBConfig::DYNAMIC_CONFIG_FAILURE_TIMEOUT;
        }

        $current_time = time();

        if (is_null($dynamic_config_fetched_at) ||
            ($current_time - $dynamic_config_fetched_at > $cache_max_time)) {
            try {
                $can_fetch = UBUtil::get_lock();
                UBLogger::debug('Locking: ' . $can_fetch);

                if ($can_fetch) {
                    $result_array = UBConfig::fetch_dynamic_config($domain, $dynamic_config_etag);
                    list(
                        $routes_status,
                        $etag,
                        $max_age,
                        $request_header_allow_new,
                        $request_header_add_new,
                        $request_cookie_allow_new,
                        $response_header_allow_new
                    ) = [
                        $result_array[0] ?? null,
                        $result_array[1] ?? null,
                        $result_array[2] ?? null,
                        $result_array[3] ?? null,
                        $result_array[4] ?? null,
                        $result_array[5] ?? null,
                        $result_array[6] ?? null
                    ];
                    
                    if ($routes_status['status'] === 'NEW') {
                        $dynamic_config['request_header_allow'] = $request_header_allow_new;
                        $dynamic_config['request_header_add'] = $request_header_add_new;
                        $dynamic_config['request_cookie_allow'] = $request_cookie_allow_new;
                        $dynamic_config['response_header_allow'] = $response_header_allow_new;
                        $dynamic_config['etag'] = $etag;
                        $dynamic_config['cache_timeout'] = $max_age;
                    } elseif ($routes_status['status'] === 'SAME') {
                        // Just extend the cache
                        $dynamic_config['cache_timeout'] = $max_age;
                    } elseif ($routes_status['status'] === 'FAILURE' || $routes_status['status'] === 'NONE') {
                        UBLogger::warning('Not updating the dynamic config: Fetching failed or 404 was returned');
                    } else {
                        UBLogger::warning("Unknown response from dynamic config fetcher: '$routes_status'");
                    }

                    $dynamic_config['fetched_at'] = $current_time;
                    $dynamic_config['last_status'] = $routes_status['status'];

                    if ($routes_status['status'] === 'FAILURE') {
                        $dynamic_config['failure_message'] = $routes_status['failure_message'];
                    }

                    update_option(UBConfig::UB_DYNAMIC_CONFIG_CACHE_KEY, $dynamic_config, false);
                }
            } catch (Exception $e) {
                UBLogger::warning('Could not update dynamic config: ' . $e);
                $dynamic_config['last_status'] = 'FAILURE';
                $dynamic_config['failure_message'] = $e->getMessage();
                update_option(UBConfig::UB_DYNAMIC_CONFIG_CACHE_KEY, $dynamic_config, false);
            }

            $release_result = UBUtil::release_lock();
            UBLogger::debug('Unlocking: ' . $release_result);
        }
        
        return UBUtil::array_select_by_key(
            $dynamic_config,
            array('request_header_allow', 'request_header_add', 'request_cookie_allow', 'response_header_allow')
        );
    }

    private static function make_curl_request($url, $domain, $etag)
    {
        $curl = curl_init();
        $curl_options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HEADER => true,
            CURLOPT_USERAGENT => UBConfig::UB_USER_AGENT,
            CURLOPT_HTTPHEADER => UBHTTP::convert_headers_to_curl(
                array(
                    'x-forwarded-host' => $domain,
                    'if-none-match' => $etag
                )
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT => 5
        );
        curl_setopt_array($curl, $curl_options);
        
        $data = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $header_size = strlen($data) - curl_getinfo($curl, CURLINFO_SIZE_DOWNLOAD);
        $curl_error = $http_code == 0 ? curl_error($curl) : null;
        
        curl_close($curl);
        
        return array($data, $http_code, $header_size, $curl_error);
    }

    private static function process_headers($data, $header_size)
    {
        $headers = substr($data, 0, $header_size);
        $etag = null;
        $max_age = null;
    
        $matches = array();
        $does_match = preg_match('/ETag: (\S+)/is', $headers, $matches);
        if ($does_match) {
            $etag = $matches[1];
        }
    
        $matches = array();
        $does_match = preg_match('/Cache-Control: max-age=(\S+)/is', $headers, $matches);
        if ($does_match) {
            $max_age = $matches[1];
        }

        // Make sure Cache-Control header is numeric to avoid errors later on when it is used for comparison
        if (is_numeric($max_age)) {
            $max_age = (int) $max_age;
        } else {
            $max_age = null;
        }
        
        return array($etag, $max_age);
    }
    
    

    public static function fetch_dynamic_config($domain, $etag)
    {
        if (!$domain) {
            $failure_message = 'Domain not provided, not fetching dynamic config';
            UBLogger::warning($failure_message);
            return UBConfig::create_failure_response($failure_message);
        }
        
        try {
            $url = 'https://' . UBConfig::dynamic_config_retrieval_domain() . '/v' . UBConfig::UB_VERSION;
            UBLogger::debug("Retrieving dynamic config from '$url', etag: '$etag', host: '$domain'");

            list($data, $http_code, $header_size, $curl_error) = UBConfig::make_curl_request($url, $domain, $etag);
            list($etag, $max_age) = UBConfig::process_headers($data, $header_size);

            if ($http_code == 200) {
                $body = substr($data, $header_size);
                $decoded_body = json_decode($body, true);

                if (json_last_error() == JSON_ERROR_NONE) {
                    UBLogger::debug("Retrieved new dynamic config, HTTP code: '$http_code'");
                    return UBConfig::create_new_response_dynamic_config($etag, $max_age, $decoded_body['request_header_allow'], $decoded_body['request_header_add'], $decoded_body['request_cookie_allow'], $decoded_body['response_header_allow']);
                } else {
                    $failure_message = "An error occurred while processing dynamic config, JSON errors: " . json_last_error_msg();
                    UBLogger::warning($failure_message);
                    return UBConfig::create_failure_response($failure_message);
                }
            }

            return UBConfig::handle_non_200_http_response($http_code, $etag, $max_age, $curl_error, 'dynamic config');
        } catch (Exception $e) {
            $failure_message = "An error occurred while retrieving dynamic config; Error: " . $e->getMessage();
            UBLogger::warning($failure_message);
            return UBConfig::create_failure_response($failure_message);
        }
    }

    public static function handle_non_200_http_response($http_code, $etag, $max_age, $curl_error, $context)
    {
        if ($http_code == 304) {
            UBLogger::debug("$context have not changed, HTTP code: '$http_code'");
            return UBConfig::create_same_response($etag, $max_age);
        }

        if ($http_code == 404) {
            UBLogger::debug("No $context to retrieve, HTTP code: '$http_code'");
            return UBConfig::create_none_response();
        }

        $failure_message = "An error occurred while retrieving $context;HTTP code: '$http_code';
        Error: " . $curl_error;
        UBLogger::warning($failure_message);
        return UBConfig::create_failure_response($failure_message);
    }
    
    public static function is_authorized_domain($domain0)
    {
        $pieces = explode(':', $domain0);
        $domain = $pieces[0];
        return in_array($domain, UBConfig::authorized_domains());
    }

    public static function update_authorization_options($domains, $data)
    {
        update_option(UBConfig::UB_USER_ID_KEY, $data['user_id']);
        update_option(UBConfig::UB_DOMAIN_ID_KEY, $data['domain_id']);
        update_option(UBConfig::UB_DOMAIN_UUID_KEY, $data['domain_uuid']);
        update_option(UBConfig::UB_CLIENT_ID_KEY, $data['client_id']);
        update_option(UBConfig::UB_AUTHORIZED_DOMAINS_KEY, $domains);
        update_option(UBConfig::UB_HAS_AUTHORIZED_KEY, true);
    }

    public static function int_min()
    {
        return (PHP_INT_MAX * -1) - 1;
    }
}
