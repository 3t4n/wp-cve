<?php

class Zwt_wp_linkpreviewer_Rest_Controller extends WP_REST_Controller
{
    public function register_routes()
    {
        register_rest_route(Zwt_wp_linkpreviewer_Constants::$REST_NAMESPACE, '/url', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'maybe_add_linkpreview_record'),
                'permission_callback' => array($this, 'confirm_user_admin')
            )
        ));
        register_rest_route(Zwt_wp_linkpreviewer_Constants::$REST_NAMESPACE, '/url/(?P<slug>\S+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_linkpreview_record'),
                'permission_callback' => array($this, 'confirm_user_admin')
            )
        ));
        register_rest_route(Zwt_wp_linkpreviewer_Constants::$REST_NAMESPACE, '/img/(?P<slug>\S+)/full', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'img_full'),
                'permission_callback' => array($this, 'plugin_enabled')
            )
        ));
        register_rest_route(Zwt_wp_linkpreviewer_Constants::$REST_NAMESPACE, '/img/(?P<slug>\S+)/compact', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'img_compact'),
                'permission_callback' => array($this, 'plugin_enabled')
            )
        ));
    }

    function plugin_enabled(){
        return Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED) == 1;
    }

    function confirm_user_admin(){
        return current_user_can('edit_posts');
    }

    function maybe_add_linkpreview_record($req)
    {
        $body = $req->get_json_params();
        $urlFetcher = new Zwt_wp_linkpreviewer_URL_Fetcher();
        $result = $urlFetcher->maybeFetchUrl($body['url']);
        return $result;
    }

    function get_linkpreview_record(WP_REST_Request $req)
    {
        $urlFetcher = new Zwt_wp_linkpreviewer_URL_Fetcher();
        return $urlFetcher->fetchUrlForHash($req->get_params()['slug']);
    }

    function img_full(WP_REST_Request $req)
    {
        $dbInstance = new Zwt_wp_linkpreviewer_Db();
        $this->handleImg($dbInstance->get_img_full(sanitize_title($req->get_params()['slug'])));
    }

    function img_compact(WP_REST_Request $req)
    {
        $dbInstance = new Zwt_wp_linkpreviewer_Db();
        return $this->handleImg($dbInstance->get_img_compact(sanitize_title($req->get_params()['slug'])));
    }

    private function handleImg($result)
    {
        if ($result) {
            header('Content-Type: image/png');
            header('Cache-Control: max-age=31536000');
            echo $result;
            exit;
        } else {
            return new WP_REST_Response(404,404);
        }
    }
}
