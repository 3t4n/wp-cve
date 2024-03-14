<?php
/**
 * IZ_Integration_API
 *
 * @class           IZ_Integration_API
 * @since           1.0.0
 * @package         WC_iZettle_Integration
 * @category        Class
 * @author          bjorntech
 */

defined('ABSPATH') || exit;

if (!class_exists('IZ_Integration_API', false)) {

    class IZ_Integration_API
    {

        /**
         * Contains the API url
         * @access protected
         */
        protected $api_url = 'izettle.com';

        /**
         * get function.
         *
         * Performs an API GET request
         *
         * @access public
         * @param $path
         * @param $send_bearer
         * @param $url_prefix
         * @param $form
         * @return object
         */
        public function get($path, $send_bearer, $url_prefix = '', $form = array())
        {
            return $this->execute('GET', $path, $url_prefix, false, $form, $send_bearer);
        }

        /**
         * post function.
         *
         * Performs an API POST request
         *
         * @access public
         * @return object
         */
        public function post($path, $form = array(), $send_json = true, $send_bearer = true, $url_prefix = '')
        {
            return $this->execute('POST', $path, $url_prefix, $send_json, $form, $send_bearer);
        }

        /**
         * put function.
         *
         * Performs an API PUT request
         *
         * @access public
         * @return object
         */
        public function put($path, $form = array(), $send_json = true, $send_bearer = true, $url_prefix = '')
        {
            return $this->execute('PUT', $path, $url_prefix, $send_json, $form, $send_bearer);
        }

        /**
         * delete function.
         *
         * Performs an API DELETE request
         *
         * @access public
         * @return object
         */
        public function delete($path, $form = array(), $send_bearer = true, $url_prefix = '')
        {
            return $this->execute('DELETE', $path, $url_prefix, false, $form, $send_bearer);
        }

        public static function ratelimiter($identifier = '')
        {

            $current = microtime(true);
            
            $zettle_rate_limit_identifier = 'zettle_api_limiter_' . $identifier;

            $time_passed = $current - (float) get_site_transient($zettle_rate_limit_identifier , $current);
            set_site_transient($zettle_rate_limit_identifier, $current);

            if ($time_passed < 300000) {
                usleep(300000 - $time_passed);
            }
        }

        /**
         * execute function.
         *
         * Executes the API request
         *
         * @access public
         * @param  string $request_type
         * @param  array  $form
         * @return object
         * @throws IZ_Integration_API_Exception
         */

        public function execute($request_type, $path, $url_prefix, $send_json, $form, $send_bearer, $full_url = false)
        {

            if (($access_token = $this->get_access_token()) || ('token' == $path)) {

                $request_form_data = '';
                $params = '';

                if ('zettle' === $url_prefix) {
                    $api_url = $this->get_adm_url();
                } else {
                    $api_url = $this->api_url;
                }

                $url = trailingslashit('https://' . $url_prefix . '.' . $api_url . '/' . $path);

                if ($full_url) {
                    $url = $full_url;
                }

                $args = array(
                    'method' => $request_type,
                    'timeout' => 120,
                );

                if ($send_bearer === true) {
                    $args['headers'] = array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $access_token,
                    );
                } else {
                    $args['headers'] = $send_bearer;
                }

                if (is_array($form) && !empty($form)) {

                    if ('GET' == $request_type || 'DELETE' == $request_type) {
                        $url .= '?' . preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', http_build_query($form, '', '&'));
                    }

                    if (!$send_json) {
                        $args['body'] = $form;
                    } else {
                        $json_body = json_encode($form, JSON_INVALID_UTF8_IGNORE);
                        if (!$json_body) {
                            $json_error = json_last_error();
                            throw new IZ_Integration_API_Exception('JSON conversion failed when connecting to Zettle', $json_error, null, $url);
                        }
                        $args['body'] = $json_body;
                    }

                }

                if ('yes' == get_option('izettle_send_through_service') && 'zettle' !== $url_prefix) {

                    $args['headers'] = array(
                        'X-Url' => $url,
                        'X-Uuid' => $this->get_organization_uuid(),
                        'X-Website' => ($alternate_url = get_option('bjorntech_alternate_webhook_url')) ? $alternate_url : get_site_url(),
                    );

                    $response = wp_remote_request('https://zettle.' . $this->get_adm_url() . '/api', $args);

                } else {

                    $response = wp_remote_request($url, $args);

                }

                $rate_limiter_apis = [
                    'inventory',
                    'finance'
                ];

                if (in_array($url_prefix, $rate_limiter_apis)) {
                    self::ratelimiter($url_prefix);
                    WC_IZ()->logger->add(sprintf('execute: Rate limiter - %s', $url));
                }

                if (is_wp_error($response)) {
                    $code = $response->get_error_code();
                    $error = $response->get_error_message($code);
                    WC_IZ()->logger->add(sprintf('execute: %s - %s', $code, $error));
                    throw new IZ_Integration_API_Exception(sprintf('Got error %s when connecting to Zettle', $code), 0, null, $url);
                }

                $data = json_decode(wp_remote_retrieve_body($response));

                if (($http_code = wp_remote_retrieve_response_code($response)) == 429 && ($retry_after = wp_remote_retrieve_header($response,'Retry-After')) && is_numeric($retry_after)) {
                    $retry_after = intval($retry_after);
                    WC_IZ()->logger->add(sprintf('execute: Request %s returned Retry-After: %s - waiting until running the request again', $url, $retry_after));
                    sleep($retry_after);
                    $data = $this->execute($request_type, $path, $url_prefix, $send_json, $form, $send_bearer, $full_url);   
                }
                
                if (($http_code = wp_remote_retrieve_response_code($response)) > 299) {

                    if (isset($data->violations) && (count($data->violations) > 0)) {
                        $violatons = reset($data->violations);
                        $message = '[Zettle error] - ' . $violatons->developerMessage;
                    } elseif (isset($data->developerMessage)) {
                        $message = '[Zettle error] - ' . $data->developerMessage;
                    } else {
                        $message = __('Unknown error when connecting to Zettle', 'woo-izettle-integration');
                    }

                    throw new IZ_Integration_API_Exception($message, $http_code);
                }
                 

                if (($link_header = wp_remote_retrieve_header($response,'Link'))) {
                    $pattern = '/<(.*)>/';

                    preg_match($pattern, $link_header, $matches);

                    if (!empty($matches) && isset($matches[1])) {
                        $link_url = $matches[1];
                        
                        $link_url_origin = ('yes' == get_option('izettle_send_through_service') && 'zettle' !== $url_prefix) ? ('https://zettle.' . $this->get_adm_url() . '/api') : $url;

                        WC_IZ()->logger->add(sprintf('execute: Link URL %s found in link header value when making request to %s - fetching additional resource', $link_url_origin, $link_url));

                        $form = ('GET' == $request_type || 'DELETE' == $request_type) ? false : $form;

                        $link_data = $this->execute($request_type, $path, $url_prefix, $send_json, $form, $send_bearer, $link_url);

                        $data = array_merge($data, $link_data);

                    } else {
                        WC_IZ()->logger->add(sprintf('execute: Cursor not found in link header value %s', $link_header));
                    }
                }

                return $data;

            } else {

                throw new IZ_Integration_API_Exception('Token error - Contact BjornTech support at hello@bjorntech.com to resolve');

            }

        }

    }
}
