<?php
class WC_Esto_Pay_Payment extends WC_Esto_Payment {

    function __construct() {

        $this->id            = 'esto_pay';
        $this->method_title  = __( 'ESTO Pay', 'woo-esto' );
        $this->method_description  = __( 'ESTO Pay bank payments are a direct payment method for all Baltic and Finnish banklinks. Contact ESTO Partner Support for additional information and activation.', 'woo-esto' );
        $this->schedule_type = 'ESTO_PAY';

        parent::__construct();

        $this->admin_page_title = __( 'ESTO Pay payment gateway', 'woo-esto' );
        $this->min_amount       = 0.1;
        $this->max_amount       = 999999;

        // needed to display logos even without description
        $this->has_fields = $this->get_option( 'show_bank_logos' ) != 'no';

        // to allow showing ESTO Pay even when we don't want other ESTO methods in that country
        // $this->ignore_disabled_countries = $this->get_option( 'ignore_disabled_countries' );

        add_action( 'wp_enqueue_scripts', [$this, 'enqueue'] );
    }

    function init_form_fields() {

        parent::init_form_fields();

        $this->form_fields = [
                'enabled'     => [
                    'title'   => __('Enable/Disable', 'woo-esto'),
                    'type'    => 'checkbox',
                    'label'   => __('ESTO Pay is a direct payment method for credit cards, banklinks, etc. Contact ESTO support for additional information.', 'woo-esto'),
                    'default' => 'no',
                ],
                'title'       => [
                    'title'       => __('Title', 'woo-esto'),
                    'type'        => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woo-esto'),
                    'default'     => __('Pay in the bank', 'woo-esto'),
                ],
                'description' => [
                    'title'       => __('Description', 'woo-esto'),
                    'type'        => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woo-esto'),
                    'default'     => __('Payment is made using a secure payment solution called KEVIN (UAB “KEVIN EU”), which is licensed by the Bank of Lithuania.',
                        'woo-esto'),
                ],
            ]
            + [
                'show_logo' => $this->form_fields['show_logo'],
                'logo'      => $this->form_fields['logo'],
            ]
            + [
                'show_bank_logos'                 => [
                    'title'   => __('Show bank logos', 'woo-esto'),
                    'type'    => 'checkbox',
                    'label'   => __('This option enables showing country dropdown and bank logos', 'woo-esto'),
                    'default' => 'yes',
                ],
                'bank_logos_layout'               => [
                    'title'   => __('Bank logos layout', 'woo-esto'),
                    'type'    => 'select',
                    'options' => [
                        'columns-1' => __('1 column', 'woo-esto'),
                        'row'       => __('Row', 'woo-esto'),
                        'columns-2' => __('2 columns', 'woo-esto'),
                        'columns-3' => __('3 columns', 'woo-esto'),
                        'columns-4' => __('4 columns', 'woo-esto'),
                    ],
                    'default' => 'columns-2'
                ],
                'disable_bank_preselect_redirect' => [
                    'title'   => __('Disable preselected bank redirect', 'woo-esto'),
                    'type'    => 'checkbox',
                    'label'   => __('Disables redirection to the bank selected in checkout on Esto webpage', 'woo-esto'),
                    'default' => 'no',
                ],
                // 'ignore_disabled_countries' => [
                //     'title'       => __( 'Ignore disabled countries', 'woo-esto' ),
                //     'type'        => 'multiselect',
                //     'class'       => 'wc-enhanced-select',
                //     'options'     => WC()->countries->get_countries(),
                //     'default'     => [],
                //     'description' => __( 'Countries where to show this method even when other ESTO methods are disabled for that country.', 'woo-esto' ),
                //     'desc_tip'    => true
                // ],
            ] + [
                'countries'  => [
                    'title'       => __('Countries', 'woo-esto'),
                    'type'        => 'multiselect',
                    'class'       => 'wc-enhanced-select',
                    'options'     => WC()->countries ? WC()->countries->get_countries() : esto_get_countries(),
                    'default'     => [],
                    'description' => __('Specify countries for ESTO Pay method.', 'woo-esto'),
                    'desc_tip'    => true
                ]
            ] + [
                'set_on_hold_status' => $this->form_fields['set_on_hold_status'],
                'order_prefix'       => $this->form_fields['order_prefix'],
            ];

        $this->form_fields['show_logo']['default'] = 'no';
    }

    public function enqueue() {
        if ( is_checkout() ) {
            wp_enqueue_style( 'woo-esto-checkout-css', plugins_url( 'assets/css/checkout.css', dirname( __FILE__ ) ), false, filemtime( dirname( __FILE__, 2 ) . '/assets/css/checkout.css' ) );
            wp_enqueue_script( 'woo-esto-checkout-js', plugins_url( 'assets/js/checkout.js', dirname( __FILE__ ) ), ['jquery'], filemtime( dirname( __FILE__, 2 ) . '/assets/js/checkout.js' ), true );
        }
    }

    public function payment_fields() {
        $description = $this->get_description();
        if ( $description ) {
            echo wpautop( wptexturize( $description ) ); // @codingStandardsIgnoreLine.
        }

        if ( $this->get_option( 'show_bank_logos' ) == 'no' ) {
            return;
        }

        // Get connection mode
        $test_mode = false; // by default
        $payment_settings = get_option('woocommerce_esto_settings', null);
        if ($payment_settings && !empty($payment_settings['connection_mode']) && $payment_settings['connection_mode'] == 'test') $test_mode = true;

        /** @var array $country_keys */
        $country_keys = $this->get_option( 'countries', ['EE', 'LV', 'LT', 'FI']);

        $wc_countries = WC()->countries->get_countries();
        $countries = [];

        foreach ($country_keys as $country_key)
        {
            $countries[strtolower($country_key)] = __( $wc_countries[$country_key], 'woocommerce' );
        }

        $logos              = WC()->session->get( 'esto_logos' );
        $check_country_keys = WC()->session->get( 'esto_country_keys');
        $check_logos_time   = ( (int) WC()->session->get( 'esto_logos_time_' . esto_get_country(), time() ) + 600 );

        if ($check_logos_time < time() || empty($logos) || ($check_country_keys !== implode('-', $country_keys)))  {

            $logos = [];

            foreach ( $countries as $key => $val ) {

                $url  = esto_get_api_url() . "v2/purchase/payment-methods?country_code=" . strtoupper( $key );
                if ($test_mode) $url .= "&test_mode=1";
                $curl = curl_init( $url );
                curl_setopt( $curl, CURLOPT_URL, $url );
                curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

                curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json, application/x-www-form-urlencoded"
                ) );

                curl_setopt( $curl, CURLOPT_USERPWD, $this->shop_id . ":" . $this->secret_key );
                curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );

                //for debug only!
                curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
                curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

                $resp = curl_exec( $curl );
                curl_close( $curl );

                $data = json_decode( $resp );

                if ( ! empty( $data ) ) {

                    foreach ( $data as $row ) {

                        if ( $row->type != 'BANKLINK' ) {
                            continue;
                        }

                        if ( isset( $logos[ $key ] ) === false ) {
                            $logos[ $key ] = [];
                        }

                        $logos[ $key ][] = $row;
                    }
                }
            }

            WC()->session->set('esto_logos', $logos);
            WC()->session->set('esto_country_keys', implode('-', $country_keys));
            WC()->session->set('esto_logos_time_' . esto_get_country(), time());

        }

        switch (esto_get_api_url()) {
            case WOO_ESTO_API_URL_LT:
                $default_country = 'lt';
                break;
            case WOO_ESTO_API_URL_LV:
                $default_country = 'lv';
                break;
            default:
                $default_country = 'ee';
        }

        $layout = $this->get_option( 'bank_logos_layout' );

        if ( method_exists( WC()->customer, 'get_billing_country' ) ) {
            $current_country = WC()->customer->get_billing_country();
        }
        else {
            $current_country = WC()->customer->get_country();
        }

        $current_country = strtolower( $current_country );

        if ( isset( $logos[ $current_country ] ) ) {
            $default_country = $current_country;
        }

        ?>
        <select class="esto-pay-countries">
            <?php foreach ( $countries as $country_code => $country_name ) : ?>
                <option value="<?= $country_code ?>"<?php selected( $default_country, $country_code, true ) ?>><?= $country_name ?></option>
            <?php endforeach; ?>
        </select>

        <div class="esto-pay-logos esto-pay-logos-layout-<?= $layout ?>">
            <input type="hidden" name="esto_pay_bank_selection" value="">
            <?php foreach ( $logos as $country_key => $country_logos ) :
                $style = $country_key != $default_country ? ' style="display: none;"' : '';
                ?>
                <div class="esto-pay-logos__country esto-pay-logos__country--<?= $country_key ?>"<?= $style ?>>
                    <?php foreach ( $country_logos as $logo ) : ?>
                        <div class="esto-pay-logo esto-pay-logo__<?= strtolower($logo->name) ?>" data-bank-id="<?= $logo->key ?>">
                            <img src="<?= apply_filters( 'woo_esto_banklink_logo', $logo->logo_url, $logo->key, $country_key ) ?>">
                        </div>
                              <?php endforeach; ?>
                </div>
                  <?php endforeach; ?>
        </div>
        <?php
    }

    public function validate_fields() {
        if ( $this->get_option( 'disable_bank_preselect_redirect' ) != 'yes' && empty( $_REQUEST['esto_pay_bank_selection'] ) ) {
            wc_add_notice( __( 'Please select a bank', 'woo-esto' ), 'error' );
            return false;
        }

        return true;
    }
}
