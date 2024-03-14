<?php

namespace XCurrency\App\Http\Controllers;

use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\WpMVC\Routing\Response;
use XCurrency\App\Http\Controllers\Controller;
use WC_Countries;
use WP_REST_Request;

class GeoIpController extends Controller {
    public CurrencyRepository $currency_repository;

    public function __construct( CurrencyRepository $currency_repository ) {
        $this->currency_repository = $currency_repository;
    }

    public function input_fields() {
        $countries    = new WC_Countries;
        $country_list = [];

        foreach ( $countries->get_countries() as $key => $country ) {
            $country_list[] = ['value' => $key, 'label' => $country];
        }

        $input = [
            'geo_countries_status' => [
                'label'   => esc_html__( 'Country List Type', 'x-currency' ),
                'type'    => 'XCurrencySelect',
                'options' => [
                    ['value' => 'enable', 'label' => esc_html__( 'Enable', 'x-currency' )],
                    ['value' => 'disable', 'label' => esc_html__( 'Disable', 'x-currency' )]
                ],
                'default' => 'disable',
                'help'    => esc_html__( "This feature will work if geo location is enable", 'x-currency' )
            ],

            'disable_countries'    => [
                'type'    => 'XCurrencyMultiSelect',
                'label'   => esc_html__( 'Country List', 'x-currency' ),
                'options' => $country_list,
                'help'    => esc_html__( "The countries you select here, will be disabled/enabled based on the 'Country list type' field", 'x-currency' )
            ],

            'welcome_country'      => [
                'label'       => esc_html__( 'Welcome Country', 'x-currency' ),
                'type'        => 'XCurrencySelect',
                'isClearable' => true,
                'options'     => $country_list,
                'help'        => esc_html__( "This feature will work if your user's welcome currency is auto", 'x-currency' )
            ]
        ];

        return Response::send(
            [
                'status' => 'success',
                'data'   => $input
            ] 
        );
    }

    public function save_currency_geo_location( WP_REST_Request $wp_rest_request ) {
        $this->currency_repository->update_geo( $wp_rest_request->get_params() );

        return Response::send(
            [
                'message' => esc_html__( 'Currency geo location updated successfully!', 'x-currency' ),
                'status'  => 'success'
            ] 
        );
    }
}