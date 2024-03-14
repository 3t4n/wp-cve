<?php

class Permata extends WC_Gateway_BACS {
	function __construct() {
		$this->id = "bank_permata";
		$this->method_title = __( "Epeken Transfer Bank Permata", 'woocommerce' );
		$this->method_description = __( "Lakukan setting payment lewat transfer Bank Permata di sini.", 'woocommerce' );
		$this->title = __( "Transfer Bank Permata", 'woocommerce' );
		$this->icon = plugins_url( 'assets/logo-permata.png',__FILE__);
		$this->has_fields = false;
		$this->init_form_fields();
		$this->init_settings();

		foreach ( $this->settings as $setting_key => $value ) {
        		$this->$setting_key = $value;
    		}
		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'save_account_details' ) );
			$this -> generate_account_details_html();
    		}
		add_action( 'woocommerce_thankyou_bank_permata', array( $this, 'thankyou_page' ) );
		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );

		

 		// BACS account fields shown on the thanks page and in emails
                $this->account_details = get_option( 'woocommerce_bank_permata_accounts',
                        array(
                                array(
                                        'account_name'   => $this->get_option( 'account_name' ),
                                        'account_number' => $this->get_option( 'account_number' ),
                                        'sort_code'      => $this->get_option( 'sort_code' ),
                                        'bank_name'      => $this->get_option( 'bank_name' ),
                                        'iban'           => $this->get_option( 'iban' ),
                                        'bic'            => $this->get_option( 'bic' )
                                )
                        )
                );

	}

   public function init_form_fields() {
    $this->form_fields = array(
        'enabled' => array(
            'title'     => __( 'Enable / Disable', 'woocommerce' ),
            'label'     => __( 'Enable Cara Bayar Transfer Bank Permata', 'woocommerce' ),
            'type'      => 'checkbox',
            'default'   => 'yes',
        ),
        'title' => array(
            'title'     => __( 'Title', 'woocommerce' ),
            'type'      => 'text',
            'desc_tip'  => __( 'Judul payment method akan dilihat oleh pelanggan', 'woocommerce' ),
            'default'   => __( 'Transfer Bank Permata', 'woocommerce' ),
        ),
        'description' => array(
            'title'     => __( 'Description', 'woocommerce' ),
            'type'      => 'textarea',
            'desc_tip'  => __( 'Deskripsi payment method yang akan dilihat oleh pelanggan', 'woocommerce' ),
            'default'   => __( 'Transfer Bank Permata(Deskripsi Transfer)', 'woocommerce' ),
            'css'       => 'max-width:350px;'
        ),
        'instructions' => array(
                                'title'       => __( 'Instructions', 'woocommerce' ),
                                'type'        => 'textarea',
                                'description' => __( 'Instruksi yang akan ditambahkan pada the thank you page dan emails.', 'woocommerce' ),
                                'default'     => 'Transfer Bank Permata(Instruksi Transfer)',
                                'desc_tip'    => true,
                        ),
        'account_details' => array(
                                'type'        => 'account_details'
                        ),

    );      
  }	

       public function save_account_details() {

                $accounts = array();

                if ( isset( $_POST['bacs_account_name'] ) ) {

                        $account_names   = array_map( 'wc_clean', $_POST['bacs_account_name'] );
                        $account_numbers = array_map( 'wc_clean', $_POST['bacs_account_number'] );
                        $bank_names      = array_map( 'wc_clean', $_POST['bacs_bank_name'] );
                        $sort_codes      = array_map( 'wc_clean', $_POST['bacs_sort_code'] );
                        $ibans           = array_map( 'wc_clean', $_POST['bacs_iban'] );
                        $bics            = array_map( 'wc_clean', $_POST['bacs_bic'] );

                        foreach ( $account_names as $i => $name ) {
                                if ( ! isset( $account_names[ $i ] ) ) {
                                        continue;
                                }

                                $accounts[] = array(
                                        'account_name'   => $account_names[ $i ],
                                        'account_number' => $account_numbers[ $i ],
                                        'bank_name'      => $bank_names[ $i ],
                                        'sort_code'      => $sort_codes[ $i ],
                                        'iban'           => $ibans[ $i ],
                                        'bic'            => $bics[ $i ]
                                );
                        }
                }

                update_option( 'woocommerce_bank_permata_accounts', $accounts );

        }

 	public function thankyou_page( $order_id ) {

                if ( $this->instructions ) {
                        echo wpautop( wptexturize( wp_kses_post( $this->instructions ) ) );
                }
                $this->bank_details( $order_id );

        }

        private function bank_details( $order_id = '' ) {
		$wooversion = epeken_get_woo_version_number();	
                if ( empty( $this->account_details ) ) {
                        return;
                }

                // Get order and store in $order
                $order          = wc_get_order( $order_id );

                // Get the order country and country $locale
		$country = '';
                if($wooversion >= 3)
                 $country        = $order->get_billing_country();
                else
                 $country = $order -> billing_country;
                
		$locale         = $this->get_country_locale();

                // Get sortcode label in the $locale array and use appropriate one
                $sortcode = isset( $locale[ $country ]['sortcode']['label'] ) ? $locale[ $country ]['sortcode']['label'] : __( 'Sort Code', 'woocommerce' );

                echo '<h2>' . __( 'Our Bank Account Details', 'epeken-all-kurir' ) . '</h2>' . PHP_EOL;

                $bacs_accounts = apply_filters( 'woocommerce_bacs_accounts', $this->account_details );

                if ( ! empty( $bacs_accounts ) ) {
                        foreach ( $bacs_accounts as $bacs_account ) {

                                $bacs_account = (object) $bacs_account;

                                if ( $bacs_account->account_name || $bacs_account->bank_name ) {
                                        echo '<h3>' . wp_unslash( implode( ' - ', array_filter( array( $bacs_account->account_name, $bacs_account->bank_name ) ) ) ) . '</h3>' . PHP_EOL;
                                }

                                echo '<ul class="order_details bacs_details">' . PHP_EOL;

                                // BACS account fields shown on the thanks page and in emails
                                $account_fields = apply_filters( 'woocommerce_bacs_account_fields', array(
                                        'account_number'=> array(
                                                'label' => __( 'Account Number', 'woocommerce' ),
                                                'value' => $bacs_account->account_number
                                        ),
                           		'sort_code'     => array(
                                                'label' => $sortcode,
                                                'value' => $bacs_account->sort_code
                                        ),
                                        'iban'          => array(
                                                'label' => __( 'IBAN', 'woocommerce' ),
                                                'value' => $bacs_account->iban
                                        ),
                                        'bic'           => array(
                                                'label' => __( 'Swift Code', 'woocommerce' ),
                                                'value' => $bacs_account->bic
                                        )
                                ), $order_id );

                                foreach ( $account_fields as $field_key => $field ) {
                                        if ( ! empty( $field['value'] ) ) {
                                                echo '<li class="' . esc_attr( $field_key ) . '">' . esc_attr( $field['label'] ) . ': <strong>' . wptexturize( $field['value'] ) . '</strong></li>' . PHP_EOL;
                                        }
                                }

                                echo '</ul>';
                        }
                }

        }

     public function process_payment( $order_id ) {
		$wooversion = epeken_get_woo_version_number();
                $order = wc_get_order( $order_id );

                // Mark as on-hold (we're awaiting the payment)
                $order->update_status( 'on-hold', __( 'Menunggu pembayaran melalui Bank Permata', 'woocommerce' ) );

                // Reduce stock levels
		 if($wooversion >= 3)
                 wc_reduce_stock_levels($order_id);
                else
                 $order->reduce_order_stock();

                // Remove cart
                WC()->cart->empty_cart();

                // Return thankyou redirect
                return array(
                        'result'    => 'success',
                        'redirect'  => $this->get_return_url( $order )
                );

        }

	 public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		$order_id = '';
    		$wooversion = epeken_get_woo_version_number();

    	    	if($wooversion >= 3)
        		$order_id = $order->get_id();
	    	else
        	 	$order_id = $order->id;

		 $payment_method = '';
                if($wooversion >= 3)
                  $payment_method = $order->get_payment_method();
                else
                  $payment_method = $order -> payment_method;

                if ( ! $sent_to_admin && 'bank_permata' === $payment_method && $order->has_status( 'on-hold' ) ) {
                        if ( $this->instructions ) {
                                echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
                        }
                        $this->bank_details( $order_id );
                }

        }

	
}


?>
