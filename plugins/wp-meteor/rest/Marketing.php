<?php

namespace WP_Meteor\Rest;

use WP_Meteor\Engine\Base;
use WP_REST_Request;
use WP_REST_Response;

class Marketing extends Base
{
    public function initialize()
    {
        add_action('rest_api_init', function () {
            register_rest_route('wpmeteor/v1', '/detect/', array(
                'methods' => 'POST',
                'callback' => [$this, 'detect']
            ));
        });
    }

    public function detect(WP_REST_Request $request)
    {
        $settings = wpmeteor_get_settings();
        $settings['detected'] = @$settings['detected'] ?: [];
        $settings['detected'] = array_merge($settings['detected'], $request->get_param('data'));
        $settings['detected'] = array_slice($settings['detected'], -20);
        wpmeteor_set_settings($settings);
        return new WP_REST_Response(['status' => 'ok'], 200);
    }
}
