<?php

namespace XCurrency\App\Http\Controllers;

use Exception;
use WP_REST_Request;
use XCurrency\App\Http\Controllers\Controller;
use XCurrency\App\Repositories\CurrencyRateRepository;
use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\WpMVC\Routing\Response;

class CurrencyController extends Controller {
    public CurrencyRepository $currency_repository;

    public CurrencyRateRepository $currency_rate_repository;

    public function __construct( CurrencyRepository $currency_repository, CurrencyRateRepository $currency_rate_repository ) {
        $this->currency_repository      = $currency_repository;
        $this->currency_rate_repository = $currency_rate_repository;
    }

    public function index() {
        return Response::send(
            [
                'data'   => $this->currency_repository->get_all(),
                'status' => 'success'
            ]
        );
    }

    public function create( WP_REST_Request $wp_rest_request ) {
        try {
            $currency_id = $this->currency_repository->create( $wp_rest_request->get_params() );
            return Response::send(
                [
                    'message' => esc_html__( 'Currency created successfully!', 'x-currency' ),
                    'data'    => ['currency_id' => $currency_id],
                    'status'  => 'success'
                ]
            );
        } catch ( Exception $exception ) {
            return Response::send(
                [
                    'message' => $exception->getMessage(),
                    'status'  => 'failed'
                ]
            );
        }
    }

    public function update( WP_REST_Request $wp_rest_request ) {
        try {
            $this->currency_repository->update( $wp_rest_request->get_params() );
            return Response::send(
                [
                    'message' => esc_html__( 'Currency updated successfully!', 'x-currency' ),
                    'status'  => 'success'
                ]
            );
        } catch ( Exception $exception ) {
            return Response::send(
                [
                    'message' => $exception->getMessage(),
                    'status'  => 'failed'
                ]
            );
        }
    }

    public function input_fields() {
        $payment_gateways    = [];
        $wc_payment_gateways = \WC_Payment_Gateways::instance();
        foreach ( $wc_payment_gateways->get_available_payment_gateways() as $key => $value ) {
            $payment_gateways[] = ['value' => $key, 'label' => $value->title];
        }

        $input = [
            'active'                   => [
                'label'   => esc_html__( 'Active', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => true
            ],
            'name'                     => [
                'label'       => esc_html__( 'Name', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter currency name', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'Currency name field is required', 'x-currency' ),
                'default'     => ''
            ],
            'code'                     => [
                'label'       => esc_html__( 'Code', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter currency code', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'Currency code field is required', 'x-currency' ),
                'default'     => ''
            ],
            'symbol'                   => [
                'label'       => esc_html__( 'Symbol', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter currency symbol', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'Currency symbol field is required', 'x-currency' ),
                'default'     => ''
            ],
            'flag'                     => [
                'label'    => esc_html__( 'Flag', 'x-currency' ),
                'type'     => 'XCurrencyImage',
                'required' => true,
                'message'  => esc_html__( 'Currency flag field is required', 'x-currency' ),
                'tooltips' => 'If you need to download flag, go ahead and download from overview page',
                'default'  => ''
            ],
            'rate'                     => [
                'label'       => esc_html__( 'Rate( 1 Base Currency = ? )', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter currency rate', 'x-currency' ),
                'type'        => 'XCurrencyNumber',
                'required'    => true,
                'message'     => esc_html__( 'Currency rate field is required', 'x-currency' ),
                'default'     => ''
            ],
            'rate_type'                => [
                'label'    => esc_html__( 'Rate Type', 'x-currency' ),
                'type'     => 'XCurrencySelect',
                'required' => true,
                'options'  => [
                    ['value' => 'auto', 'label' => esc_html__( 'Auto', 'x-currency' )],
                    ['value' => 'fixed', 'label' => esc_html__( 'Fixed', 'x-currency' )]
                ],
                'message'  => esc_html__( 'Currency rate field is required', 'x-currency' ),
                'default'  => 'auto'
            ],
            'extra_fee'                => [
                'label'       => esc_html__( 'Extra fee for the total amount of the cart', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter extra fee', 'x-currency' ),
                'type'        => 'XCurrencyNumber',
                'default'     => ''
            ],
            'extra_fee_type'           => [
                'label'   => esc_html__( 'Extra Fee Type', 'x-currency' ),
                'type'    => 'XCurrencySelect',
                'options' => [
                    ['value' => 'fixed', 'label' => esc_html__( 'Fixed', 'x-currency' )],
                    ['value' => 'percent', 'label' => esc_html__( '%', 'x-currency' )]
                ],
                'default' => 'fixed'
            ],
            'thousand_separator'       => [
                'label'       => esc_html__( 'Currency thousand separator', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter thousand separator', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'Currency thousand separator field is required', 'x-currency' ),
                'default'     => ','
            ],
            'decimal_separator'        => [
                'label'       => esc_html__( 'Currency decimal separator', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter decimal separator', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'Currency decimal separator field is required', 'x-currency' ),
                'default'     => '.'
            ],
            'max_decimal'              => [
                'type'        => 'XCurrencySelect',
                'label'       => esc_html__( 'Currency decimal', 'x-currency' ),
                'options'     => [
                    ['value' => 0, 'label' => 0],
                    ['value' => 1, 'label' => 1],
                    ['value' => 2, 'label' => 2],
                    ['value' => 3, 'label' => 3],
                    ['value' => 4, 'label' => 4],
                    ['value' => 5, 'label' => 5],
                    ['value' => 6, 'label' => 6],
                    ['value' => 7, 'label' => 7],
                    ['value' => 8, 'label' => 8],
                    ['value' => 9, 'label' => 9],
                    ['value' => 10, 'label' => 10],
                    ['value' => 11, 'label' => 11],
                    ['value' => 12, 'label' => 12]
                ],
                'default'     => 2,
                'placeholder' => esc_html__( 'Select currency decimal', 'x-currency' ),
                'required'    => true,
                'message'     => esc_html__( 'Currency decimal field is required', 'x-currency' )
            ],
            'rounding'                 => [
                'type'     => 'XCurrencySelect',
                'label'    => esc_html__( 'Rounding', 'x-currency' ),
                'options'  => [
                    ['value' => 'disabled', 'label' => esc_html__( 'Disabled', 'x-currency' )],
                    ['value' => 'up', 'label' => esc_html__( 'Up', 'x-currency' )],
                    ['value' => 'down', 'label' => esc_html__( 'Down', 'x-currency' )],
                    ['value' => 'nearest', 'label' => esc_html__( 'Nearest', 'x-currency' )]
                ],
                'required' => true,
                'default'  => 'disabled'
            ],
            'symbol_position'          => [
                'type'        => 'XCurrencySelect',
                'label'       => esc_html__( 'Currency symbol position', 'x-currency' ),
                'options'     => [
                    ['value' => 'left', 'label' => esc_html__( 'Left', 'x-currency' )],
                    ['value' => 'left_space', 'label' => esc_html__( 'Left space', 'x-currency' )],
                    ['value' => 'right', 'label' => esc_html__( 'Right', 'x-currency' )],
                    ['value' => 'right_space', 'label' => esc_html__( 'Right space', 'x-currency' )]
                ],
                'placeholder' => esc_html__( 'Select symbol position', 'x-currency' ),
                'required'    => true,
                'message'     => esc_html__( 'Currency symbol position field is required', 'x-currency' ),
                'default'     => 'left_space'
            ],
            'disable_payment_gateways' => [
                'type'     => 'XCurrencyMultiSelect',
                'default'  => ['right'],
                'label'    => esc_html__( 'Disable payment gateways for this currency', 'x-currency' ),
                'options'  => $payment_gateways,
                'tooltips' => esc_html__( 'Here WooCommerce payment gateways will appear.', 'x-currency' )
            ]
        ];
        return Response::send(
            [
                'data'   => $input,
                'status' => 'success'
            ]
        );
    }

    public function organizer( WP_REST_Request $wp_rest_request ) {
        try {
            $data = $wp_rest_request->get_params();
            $this->currency_repository->query( $data['keys'], $data['type'] );
            return Response::send(
                [
                    'message' => esc_html__( 'Currency organized successfully!', 'x-currency' ),
                    'status'  => 'success'
                ]
            );
        } catch ( Exception $e ) {
            return Response::send(
                [
                    'message' => esc_html__( 'Something is wrong', 'x-currency' ),
                    'status'  => 'failed'
                ]
            );
        }
    }

    public function demo_currencies() {
        $rates = get_option( x_currency_config()->get( 'app.currency_rates_option_key' ) );

        if ( empty( $rates ) ) {
            $base_currency_code = x_currency_base_code();
            $rates              = $this->currency_rate_repository->exchange_base_currency( $base_currency_code, x_currency_get_json_file_content( x_currency_dir( 'sample-data/rates.json' ) ) );
        } else {
            $rates = unserialize( $rates );
        }
        
        $symbols = x_currency_symbols();
        $data    = [];
        foreach ( get_woocommerce_currencies() as $code => $name ) {
            $data[$code] = [
                'name'               => $name,
                'code'               => $code,
                'symbol'             => isset( $symbols[$code] ) ? $symbols[$code] : '',
                'rate'               => isset( $rates['rates'][$code] ) ? $rates['rates'][$code] : '',
                'rate_type'          => 'auto',
                'extra_fee'          => '0', // must be string
                'extra_fee_type'     => 'fixed',
                'thousand_separator' => ',',
                'decimal_separator'  => '.',
                'max_decimal'        => 2,
                'position'           => 'left'
            ];
        }

        return Response::send(
            [
                'data'   => $data,
                'status' => 'success'
            ]
        );
    }

    public function attachment( WP_REST_Request $wp_rest_request ) {
        $attachment = wp_get_attachment_image_src( sanitize_key( $wp_rest_request->get_param( 'id' ) ) );

        if ( ! empty( $attachment[0] ) ) {
            $attachment_url = $attachment[0];
        } else {
            $attachment_url = x_currency_url( 'media/common/dummy-flag.jpg' );
        }

        return Response::send(
            [
                'attachment_url' => $attachment_url,
                'status'         => 'success'
            ] 
        );
    }
}