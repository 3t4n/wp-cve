<?php

namespace XCurrency\App\Http\Controllers;

use Exception;
use WP_REST_Request;
use XCurrency\App\Http\Controllers\Controller;
use XCurrency\App\Repositories\CurrencyRateRepository;
use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\WpMVC\Routing\Response;

class RateController extends Controller {
    public CurrencyRateRepository $currency_rate_repository;

    public CurrencyRepository $currency_repository;

    public function __construct( CurrencyRateRepository $currency_rate_repository, CurrencyRepository $currency_repository ) {
        $this->currency_rate_repository = $currency_rate_repository;
        $this->currency_repository      = $currency_repository;
    }

    public function exchange_single( WP_REST_Request $wp_rest_request ) {
        try {
            $currency_id   = $wp_rest_request->get_param( 'currency_id' );
            $currency_code = $wp_rest_request->get_param( 'currency_code' );
            $this->currency_rate_repository->exchange( $currency_id, $currency_code );

            return Response::send(
                [
                    'status'  => 'success',
                    'data'    => $this->currency_repository->get_all(),
                    'message' => esc_html__( 'Currency rates updated successfully!', 'x-currency' )
                ]
            );
        } catch ( Exception $exception ) {
            return Response::send(
                [
                    'status'  => 'failed',
                    'message' => $exception->getMessage()
                ]
            );
        }
    }

    public function exchange_all() {
        try {
            $this->currency_rate_repository->exchange();
            return Response::send(
                [
                    'status'  => 'success',
                    'data'    => $this->currency_repository->get_all(),
                    'message' => esc_html__( 'Currency rates updated successfully!', 'x-currency' )
                ]
            );
        } catch ( Exception $exception ) {
            return Response::send(
                [
                    'status'  => 'failed',
                    'message' => $exception->getMessage()
                ]
            );
        }
    }
}