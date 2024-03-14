<?php

namespace TenWebPluginIO;

use Tenweb_Authorization\Login;

class TenWebIORest extends \WP_REST_Controller
{
    private $version = '2';
    private $route = 'tenwebio';
    private $status = 404;
    private $response = array();

    private $bases = array(
        'connect_from_core' => array('route'   => '/connect_from_core/',
                                     'methods' => \WP_REST_Server::CREATABLE,
                                     'args'    => array()),
        'check_domain'      => array('route'   => '/check_domain/',
                                     'methods' => \WP_REST_Server::CREATABLE,
                                     'args'    => array()),
        'logout'            => array('route'   => '/logout/',
                                     'methods' => \WP_REST_Server::CREATABLE,
                                     'args'    => array()),
    );


    public function registerRoutes()
    {
        $namespace = $this->route . '/v' . $this->version;
        foreach ($this->bases as $base => $route_data) {
            register_rest_route($namespace, $route_data['route'], array(
                array(
                    'methods'             => $route_data['methods'],
                    'callback'            => array($this, 'callback'),
                    'permission_callback' => $route_data['route'] == "logout" ? array($this, 'checkAuthorization') : '__return_true',
                    'args'                => $route_data['args']
                )
            ));
        }
    }

    public function checkAuthorization(WP_REST_Request $request)
    {
        if (!Login::get_instance()->check_logged_in()) {
            $data_for_response = array(
                "code"    => "unauthorized",
                "message" => "unauthorized",
                "data"    => array(
                    "status" => 401
                )
            );

            return new WP_Error('rest_forbidden', $data_for_response, 401);
        }
        $authorize = Login::get_instance()->authorize($request);
        if (is_array($authorize)) {
            return new WP_Error('rest_forbidden', $authorize, 401);
        }

        return true;
    }

    /**
     * @param $request
     *
     * @return \WP_REST_Response
     */
    public function callback($request)
    {
        $route = $request->get_route();
        $endpoint = $this->parseEndpoint($route);
        try {
            while (ob_get_level() !== 0) {
                ob_end_clean();
            }
            switch ($endpoint) {
                case 'connect_from_core':
                    $this->connectFromCore($request);
                    break;
                case 'check_domain':
                    $this->checkDomain($request);
                    break;
                case 'logout':
                    $this->logout($request);
                    break;
                default:
                    $this->status = 404;
                    $this->response = array(
                        "code"    => "rest_no_route",
                        "message" => "No route was found matching the URL and request method.",
                        "data"    => array(
                            "status" => 404
                        )
                    );
                    break;
            }
        } catch (\Exception $e) {
            $this->status = 500;
            $this->response = array(
                "code"    => "error",
                "message" => $e->getMessage(),
                "data"    => array(
                    "status" => 500
                )
            );
        }
        $headers = !empty($this->response["headers"]) ? $this->response["headers"] : [];

        return new \WP_REST_Response($this->response, $this->status, $headers);
    }


    public function connectFromCore($request)
    {

        try {
            $data_for_response = [];
            $parameters = $request->get_body_params();
            $this->status = 200;
            if (isset($parameters['nonce'])) {
                $saved_nonce = get_site_option(TENWEBIO_PREFIX . '_saved_nonce');
                if ($parameters['nonce'] === $saved_nonce) {
                    $data_for_response = Connect::connectToTenweb($parameters);
                    $headers_for_response = array('tenweb_check_domain' => "it_was_me");
                } else {
                    $data_for_response = array(
                        "code" => "ok",
                        "data" => "it_was_not_me"
                    );
                    $headers_for_response = array('tenweb_check_domain' => "it_was_not_me");
                }
                delete_site_option(TENWEBIO_PREFIX . '_saved_nonce');
            } else {
                $data_for_response = array(
                    "code" => "ok",
                    "data" => "it_was_not_me"
                );
                $headers_for_response = array('tenweb_check_domain' => "it_was_not_me");
            }
            $this->response = array_merge(array(
                "status"  => 200,
                "message" => "ok",
                "headers" => $headers_for_response
            ), $data_for_response);
        } catch (\Exception $e) {
            $this->status = 500;
            $this->response = array(
                "status"  => 500,
                "message" => $e->getMessage(),
            );
        }
    }

    public function checkDomain($request)
    {
        try {
            if (get_site_option(TENWEBIO_PREFIX . '_is_available') !== '1') {
                update_site_option(TENWEBIO_PREFIX . '_is_available', '1');
            }
            $parameters = $request->get_body_params();

            if (isset($parameters['confirm_token'])) {
                if (Login::get_instance()->checkConfirmToken($parameters['confirm_token'])) {
                    $data_for_response = array(
                        "code" => "ok",
                        "data" => "it_was_me"  // do not change
                    );
                    $headers_for_response = array('tenweb_check_domain' => "it_was_me");
                } else {
                    $data_for_response = array(
                        "code" => "ok",
                        "data" => "it_was_not_me" // do not change
                    );
                    $headers_for_response = array('tenweb_check_domain' => "it_was_not_me");
                }
            } else {
                $data_for_response = array(
                    "code" => "ok",
                    "data" => "alive"  // do not change
                );
                $headers_for_response = array('tenweb_check_domain' => "alive");
                if (!Login::get_instance()->check_logged_in()) {
                    $data_for_response['data'] = "alive_but_not_connected";
                    $headers_for_response['tenweb_check_domain'] = "alive_but_not_connected"; //do not change
                }
            }

            $tenweb_hash = $request->get_header('tenweb-check-hash');
            if (!empty($tenweb_hash)) {
                $encoded = '__' . $tenweb_hash . '.';
                $encoded .= base64_encode(json_encode($data_for_response));
                $encoded .= '.' . $tenweb_hash . '__';

                $data_for_response['encoded'] = $encoded;
                \Tenweb_Authorization\Helper::set_error_log('tenweb-check-hash', $encoded);
            }
            $this->status = 200;
            $this->response = array_merge(array(
                "status"  => 200,
                "message" => "ok",
                "headers" => $headers_for_response
            ), $data_for_response);

        } catch (\Exception $e) {
            $this->status = 500;
            $this->response = array(
                "status"  => 500,
                "message" => $e->getMessage(),
            );
        }
    }

    /**
     * @param $route
     *
     * @return int|null|string
     */
    private function parseEndpoint($route)
    {
        $route_url = substr($route, 9);
        foreach ($this->bases as $key => $value) {
            $route_regex = '/' . $key . '/';
            if (preg_match($route_regex, $route_url)) {
                return $key;
            }
        }

        return null;
    }

    public function logout($request)
    {
        $this->response = array(
            "status"  => 500,
            'success' => false,
            'message' => "Cannot logout client",
            "code"    => "not_ok"
        );
        try {
            Connect::disconnectFromTenweb();
            $this->status = 200;
            $this->response = array(
                "status"  => 200,
                "message" => "Successfully logged out",
                "code"    => "ok"
            );
        } catch (Exception $exception) {
            $this->status = 500;
            $this->response = array(
                "status"  => 500,
                "message" => 'Error in logging out client',
                "error"   => $exception->getMessage() . ' in ' . $exception->getFile() . ' on ' . $exception->getLine(),
            );
        }
    }
}

