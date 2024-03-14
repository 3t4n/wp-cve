<?php

namespace XCurrency\App\Http\Controllers;

use WP_REST_Request;
use XCurrency\App\Http\Controllers\Controller;
use XCurrency\App\Repositories\SettingRepository;
use XCurrency\WpMVC\Routing\Response;

class SettingController extends Controller {
    public SettingRepository $setting_repository;

    public function __construct( SettingRepository $setting_repository ) {
        $this->setting_repository = $setting_repository;
    }

    public function setting_inputs() {
        return Response::send(
            [
                'message' => esc_html__( 'Settings Input Retrieved Successfully!', 'x-currency' ),
                'data'    => $this->setting_repository->input_fields_with_value(),
                'status'  => 'success'
            ]
        );
    }

    public function save_settings( WP_REST_Request $wp_rest_request ) {
        $this->setting_repository->save_settings( $wp_rest_request->get_params() );
        return Response::send(
            [
                'message' => esc_html__( 'Settings Data Updated Successfully!', 'x-currency' ),
                'data'    => [],
                'status'  => 'success'
            ]
        );
    }
}