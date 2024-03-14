<?php
/**
 *
 * Smartbill functions
 *
 * @copyright  Intelligent IT SRL 2018-2022
 * @package smartbill-facturare-si-gestiune
 */

/**
 * Add metabox in order page
 */
function smartbill_order_details_meta_box( $post_type, $post) {
	// afisare doar daca s-a publicat / salvat postarea.
	if ( isset( $post ) ) {
		add_meta_box(
			'smartbill_woocommerce_meta_box',
			'Facturare SmartBill',
			'smartbill_order_details_invoice_box',
			'woocommerce_page_wc-orders',
			'side',
			'high'
		);
		
		add_meta_box(
			'smartbill_woocommerce_meta_box',
			'Facturare SmartBill',
			'smartbill_order_details_invoice_box',
			'shop_order',
			'side',
			'high'
		);
	}
}

/**
 * Show button to issue SmartBill invoice and link to invoice in metabox
 *
 * @param WP_POST $post WordPress post / woocommerce order.
 */
function smartbill_order_details_invoice_box( $order ) {
	if($order instanceof WC_Order){
		$id = $order->get_id();
	}else{
		$id = $order->ID;
		$order=new WC_Order($id);
	}
	
	$series            = get_post_meta(  $id, 'smartbill_series_name', true );
	$number            = get_post_meta(  $id, 'smartbill_document_number', true );
	$document_url      = get_post_meta(  $id, 'smartbill_private_link', true );
	
	$is_vat_payable    = Smartbill_Woocommerce_Settings::is_vat_payable();
	$document_settings = smartbill_get_settings_for_order( $order, $is_vat_payable );

	if ( ! empty( $document_url ) ) {
		// View invoice link instead of edit.
		$pattern      = '/editare/';
		$replacement  = 'vizualizare';
		$document_url = preg_replace( $pattern, $replacement, $document_url, -1 );
		echo '<h4>' . esc_attr( $series ) . ' ' . esc_attr( $number ) . '</h4>';
		echo '<p>
            <a class="button tips reissue" id="smartbill-woocommerce-invoice-button"
            data-tip=""
            href="' . esc_url_raw( smartbill_generate_url( 'smartbill-create', $id) ) . '" target="_self">' . esc_attr__( 'Reemite document', 'smartbill-woocommerce' ) . '</a>
            </p><p>
            <a class="button button-primary tips" id="smartbill-woocommerce-view-document-button"
            data-tip=""
            href="' . esc_url_raw( $document_url ) . '" target="_blank">' . esc_attr__( 'Vizualizeaza in SmartBill', 'smartbill-woocommerce' ) . '</a>
            </p>';
		echo '<p><a class="button button-primary tips sndmail" id="smartbill-woocommerce-send-document-email-button"
            data-tip=""
            href="' . esc_url_raw( smartbill_generate_url( 'smartbill-send-mail', $id) ) . '" target="_self">' . esc_attr__( 'Trimite factura clientului', 'smartbill-woocommerce' ) . '</a>
            </p>';

	 } else {
		// Generate invoice link.
		$options = get_option( 'smartbill_plugin_options_settings' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['document_type'] ) ) {
			$document_type = $options['document_type'];
		} else {
			$document_type = Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE;
		}
		if ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE == $document_type ) {
			$button_label = __( 'Emite factura in SmartBill', 'smartbill-woocommerce' );
		} else {
			$button_label = __( 'Emite proforma in SmartBill', 'smartbill-woocommerce' );
		}

		echo '<p>
            <a class="button tips issue" id="smartbill-woocommerce-invoice-button"
            data-tip=""
            href="' . esc_url_raw( smartbill_generate_url( 'smartbill-create', $id ) ) . '" >' . esc_attr( $button_label ) . '</a>
            </p>';
	}
}



/**
 * Create button for issueing document in smartbill column (orders page)
 *
 * @param WP_POST $post WordPress post / woocommerce order.
 */
function smartbill_order_details_invoice_column_button(  $column, $post_id  ) {
	$link = get_post_meta(  $post_id  , 'smartbill_private_link', true );
	if ( empty( $link ) ) {
		// Generate invoice.
		echo '<p>
            <a class="button tips smartbill-woocommerce-invoice-button" data-order="' . sanitize_key(  $post_id   ) . '"
            data-tip="' . esc_attr__( 'Emite in SmartBill', 'smartbill-woocommerce' ) . '"><img src="' . esc_url_raw( plugin_dir_url( __FILE__ ) . '../assets/images/smb_head.png' ) . '"/></a>
            </p>';
	}
}

/**
 * Create url for ajax call
 *
 * @param string $arg_name argument.
 * @param int    $order_id woocommerce order id.
 *
 * @return string $complete_url
 */
function smartbill_generate_url( $arg_name, $order_id ) {
	$action_url   = add_query_arg( $arg_name, $order_id );
	$complete_url = wp_nonce_url( $action_url );
	return esc_url( $complete_url );
}

/**
 * Ajax send document to client
 */
function smartbill_woocommerce_send_document_mail() {
	check_ajax_referer( 'smartbill_nonce', 'security' );
	if ( isset( $_POST['data'] ) && isset( $_POST['data']['order_id'] ) ) {
		$order_id = (int) sanitize_text_field( wp_unslash( $_POST['data']['order_id'] ) );

		if ( ! is_numeric( $order_id ) || ( $order_id <= 0 ) ) {
			$return['status']  = false;
			$return['message'] = esc_attr__( 'Comanda furnizata este invalida', 'smartbill-woocommerce' );
		} else {
			$return = smartbill_send_document_mail( $order_id );
		}
	} else {
		$return['status']  = false;
		$return['message'] = esc_attr__( 'Comanda furnizata este invalida', 'smartbill-woocommerce' );
	}
	echo wp_json_encode( $return );
	die();
}


/**
 * Ajax create document
 */
function smartbill_woocommerce_issue_document() {
	check_ajax_referer( 'smartbill_nonce', 'security' );
	if ( isset( $_POST['data'] ) && isset( $_POST['data']['order_id'] ) ) {
			$order_id = (int) sanitize_text_field( wp_unslash( $_POST['data']['order_id'] ) );
		if ( ! is_numeric( $order_id ) || ( $order_id <= 0 ) ) {
			$return['status']  = false;
			$return['message'] = __( 'Comanda furnizata este invalida', 'smartbill-woocommerce' );
		} elseif ( isset( $is_first ) ) {
			$is_first = filter_var( $data['is_first'], FILTER_VALIDATE_BOOLEAN );
			$return   = smartbill_create_document( $order_id, $is_first );
		} else {
			$return = smartbill_create_document( $order_id );
		}
	} else {
		$return['status']  = false;
		$return['message'] = __( 'Comanda furnizata este invalida', 'smartbill-woocommerce' );
	}
	echo wp_json_encode( $return );
	die();
}

/**
 * Create document on request
 *
 * @return void
 */
function smartbill_init_plugin_actions() {
	// links.
	if ( isset( $_GET['smartbill-create'] ) ) {
		smartbill_create_document( sanitize_text_field( wp_unslash( $_GET['smartbill-create'] ) ) );
	}
	if ( isset( $_GET['smartbill-send-mail'] ) ) {
		smartbill_send_document_mail( sanitize_text_field( wp_unslash( $_GET['smartbill-send-mail'] ) ) );
	}
}

/**
 * Get Customer info
 *
 * @param int $order_id woocommerce order id.
 *
 * @return Array $smartbill_client
 */
function smartbill_create_client_details( $order_id ) {
	$order            = new WC_Order( $order_id );
	$order_meta       = get_post_meta( $order_id );
	$anaf_client      = new ANAFAPIClient();
	$smartbill_client = array();
	
	$company_name = $order->get_billing_company();
	if( is_string($company_name) ){
		$company_name = trim( $company_name );
	}
	// get info from order.
	$city        = $order->get_billing_city();
	$country     = $order->get_billing_country();
	// get all counties (judete).
	$wc_countries = new WC_Countries();
	$wc_counties = $wc_countries->get_states( $country );
	$county      = '';
	$county_code = $order->get_billing_state();

	if ( isset( $wc_counties[ $county_code ] ) ) {
		$county = $wc_counties[ $county_code ];
		$county = remove_accents( html_entity_decode( $county ) );
	}
	$email         = $order->get_billing_email();

	$billing_type = get_post_meta( $order_id, 'smartbill_billing_type', true );
	
	$is_old_cif    = get_post_meta( $order_id, 'smartbill_cif', true );
	$is_old_regcom = get_post_meta( $order_id, 'smartbill_regcom', true ); 
	$old_billing   = ( !empty($is_old_cif) || !empty($is_old_regcom) ) && ! empty( $company_name );
	if ( empty($billing_type) ) {
		$billing_type = 'pf';
	}
	if ( 'pj' == $billing_type || $old_billing ) {
		$invoice_name = $company_name;
		$vat_code;

		$smartbill_billing_cif = get_post_meta( $order_id, 'smartbill_billing_cif', true );
		if ( !empty( $smartbill_billing_cif ) ) {
			$vat_code = $smartbill_billing_cif;
		} else {
			$vat_code = empty($is_old_cif) ? '' : $is_old_cif;
		}
		$customer_tax_details = $anaf_client->get_vat_info( $vat_code );
		
		if("error" === $customer_tax_details || is_null($customer_tax_details) || !isset($customer_tax_details->found) || empty($customer_tax_details->found)){
			$is_tax_payer  = "error";
		}else{
			$is_tax_payer  = $anaf_client->is_tax_payer( $customer_tax_details );
		}

		$smartbill_billing_nr_reg_com = get_post_meta( $order_id, 'smartbill_billing_nr_reg_com', true );

		if ( !empty( $smartbill_billing_nr_reg_com ) ) {
			$smartbill_client['regCom'] = trim($smartbill_billing_nr_reg_com);
		} else {
			$smartbill_client['regCom'] = empty($is_old_regcom) ? '' : $is_old_regcom;
		}

		$smartbill_cont_banca     = get_post_meta( $order_id, 'smartbill_cont_banca', true );
		$smartbill_banca          = get_post_meta( $order_id, 'smartbill_banca', true );
		$smartbill_client['iban'] = empty($smartbill_cont_banca) ? '' : $smartbill_cont_banca;
		$smartbill_client['bank'] = empty($smartbill_banca) ? '' : $smartbill_banca;
	} else {
		$vat_code     = '';
		$first_name   = $order->get_billing_first_name();
		$last_name    = $order->get_billing_last_name();
		$invoice_name = $first_name . ' ' . $last_name;
		$is_tax_payer = false;
	}

	// get all address lines.

	$address1 = $order->get_billing_address_1();
	$address2 = $order->get_billing_address_2();
	$street   = $address1 . ' ' . $address2;
	$vat_code = strtoupper($vat_code);
	
	//Fix for trim() false result
	$vat_code = str_replace(" ", "", $vat_code); 

	if("error" === $is_tax_payer ){
		if( 'RO' != substr($vat_code, 0, 2)){
			$is_tax_payer = true;
		}else{
			$is_tax_payer = false;
		}
	}else{
		if( $is_tax_payer ){
			if( 'RO' != substr($vat_code, 0, 2)){
				$vat_code = 'RO'.$vat_code;
			}
		}else{
			$vat_code = str_replace('RO','',$vat_code);
		}
	}

	$country = WC()->countries->countries[ $country ];
	$smartbill_client['name']       = $invoice_name;
	$smartbill_client['vatCode']    = $vat_code;
	$smartbill_client['address']    = $street;
	$smartbill_client['isTaxPayer'] = $is_tax_payer;
	$smartbill_client['city']       = $city;
	$smartbill_client['county']     = $county;
	$smartbill_client['country']    = $country;
	$smartbill_client['email']      = $email;

	$smartbill_client['phone'] = $order->get_billing_phone();
	$options                   = get_option( 'smartbill_plugin_options_settings' );
	if ( ! empty( $options ) && is_array( $options ) && isset( $options['save_client'] ) ) {
		$save_client = $options['save_client'];
	} else {
		$save_client = 0;
	}

	$smartbill_client['saveToDb'] = (bool) $save_client;

	return $smartbill_client;

}

/**
 * Send smartbill document to client
 *
 * @param int $order_id woocommerce order id.
 *
 * @throws Exception Invalid login.
 *
 * @return response $return
 */
function smartbill_send_document_mail( $order_id ) {
	try {
		$order = new WC_Order( $order_id );

		// get document number.
		$number = get_post_meta( $order_id, 'smartbill_invoice_log', true );

		$doc_type = str_contains($number['smartbill_document_url'],'proforma')?'proforma':'factura';
		$series = $number['smartbill_series'];
		$number = $number['smartbill_invoice_id'];

		// get email.
		$client_details = smartbill_create_client_details( $order_id );

		// get options.
		$login_options = get_option( 'smartbill_plugin_options' );
		if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
			throw new Exception( __( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
		}
		$company_vat_code  = $login_options['vat_code'];
		$is_vat_payable    = Smartbill_Woocommerce_Settings::is_vat_payable();
		$document_settings = smartbill_get_settings_for_order( $order, $is_vat_payable );	
		$smartbill_email = array(
			'companyVatCode' => $company_vat_code,
			'seriesName'     => $series,
			'type'           => $doc_type,
			'number'         => $number,
			'to'             => $client_details['email'],
		);
		if ( '1' == $document_settings['send_mail_with_document'] ) {
			$smartbill_email['cc']  = $document_settings['send_mail_cc'];
			$smartbill_email['bcc'] = $document_settings['send_mail_bcc'];
		} else {
			$document_settings['send_mail_cc']  = '';
			$document_settings['send_mail_bcc'] = '';
		}

		$client      = new SmartBill_Cloud_REST_Client( $login_options['username'], $login_options['password'] );
		$server_call = $client->send_document( $smartbill_email );
		$m_bcc ="";
		$m_cc  ="";
		if ( ! empty( $document_settings['send_mail_bcc'] ) ) {
			$m_bcc = ', ' . $document_settings['send_mail_bcc'];}
		if ( ! empty( $document_settings['send_mail_cc'] ) ) {
			$m_cc = ', ' . $document_settings['send_mail_cc'];}
		/* translators: 4$s Factura 3$s bcc email addresses 2$s cc email addresses 1$s client email*/
		$message = sprintf( __( '%4$s a fost trimisa cu succes catre: %1$s%2$s%3$s.', 'smartbill-woocommerce' ), $client_details['email'], $m_cc, $m_bcc, ucwords( $doc_type ) );
		$order->add_order_note( $message );

		$return = array(
			'status'  => 'true',
			'code'    => $server_call['status']['code'],
			'message' => $message,
			'headers' => $server_call['get_headers'],
		);
		return $return;

	} catch ( Exception $e ) {
		if ( ! empty( $document_settings['send_mail_bcc'] ) ) {
			$m_bcc = ', ' . $document_settings['send_mail_bcc'];}
		if ( ! empty( $document_settings['send_mail_cc'] ) ) {
			$m_cc = ', ' . $document_settings['send_mail_cc'];}
		$return['status'] = false;
		/* translators: 4$s Factura 3$s bcc email addresses 2$s cc email addresses 1$s client email*/
		$return['error'] = sprintf( __( '%4$s nu a fost trimisa catre: %1$s%2$s%3$s.', 'smartbill-woocommerce' ), $client_details['email'], $m_cc, $m_bcc, ucwords( $doc_type ) );
		return $return;
	}
}

/**
 * Create smartbill document
 *
 * @param int     $order_id woocommerce order id.
 * @param boolean $get_um always true.
 *
 * @throws Exception Invalid login.
 * @throws \Exception Invalid products.
 *
 * @return response $return
 */
function smartbill_create_document( $order_id, $get_um = true ) {
	try {
		$order      = new WC_Order( $order_id );
		$order_meta = get_post_meta( $order_id );

		// build custom fields.
		$client_details = smartbill_create_client_details( $order_id );

		// get options.
		$login_options = get_option( 'smartbill_plugin_options' );
		if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
			throw new Exception( esc_attr__( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
		}
		
		$is_vat_payable    = Smartbill_Woocommerce_Settings::is_vat_payable();
		if($is_vat_payable){
			$vat_rates     = Smartbill_Woocommerce_Settings::get_vat_rates();
		}
		
		$document_settings = smartbill_get_settings_for_order( $order, $is_vat_payable );

		if ( $get_um ) {
			if ( 'no_value' == strtolower( $document_settings['um'] ) ) {
				throw new \Exception( '<br>Verifica setarile modulului SmartBill' );
			}
		}
		
		$smartbill_product = $document_settings['smartbill_product'];
		$existing_products = SmartBillUtils::get_order_products( $order, $document_settings );
		$products          = array();
		$product_vat       = $document_settings['product_vat'];
		if ( empty( $existing_products ) ) {
			throw new \Exception( __( 'Eroare la citirea datelor din WooCommerce.', 'smartbill-woocommerce' ) );
		}
		foreach ( $existing_products as $product ) {
			// convert from stdClass to array.
			$array_product = json_decode( wp_json_encode( $product ), true );

			if ( $is_vat_payable ) {
				if ( array_key_exists( $product_vat, $vat_rates ) ) {
					$selected_vat = $vat_rates[ $product_vat ];
					if ( ! isset( $array_product['taxName'] ) ) {
						$array_product['taxName'] = $selected_vat['name'];
					}
					if ( ! isset( $array_product['taxPercentage'] ) ) {
						$array_product['taxPercentage'] = $selected_vat['percentage'];
					}
				}
			}

			if ( $smartbill_product && ! $array_product['isDiscount'] ) {
				$array_product['useSBProductName'] = true;
			}

			$products[] = $array_product;
		}

		$is_draft           = $document_settings['invoice_is_draft'];
		$due_days           = $document_settings['due_days'];
		$delivery_days      = $document_settings['delivery_days'];
		$show_delivery_days = $document_settings['show_delivery_days'];

		if ( isset( $document_settings['send_mail_with_document'] ) ) {
			$send_mail_with_document = $document_settings['send_mail_with_document'];
		} else {
			$send_mail_with_document = '0';
		}

		if ( empty( $due_days ) ) {
			$due_days = 0;
		}
		if ( empty( $delivery_days ) ) {
			$delivery_days = 0;
		}

		$company_vat_code = $login_options['vat_code'];
		$currency         = trim( $document_settings['currency'] );
		$document_date    = gmdate( 'Y-m-d' );
		if ( '2' == $document_settings['document_date'] ) {
			$document_date = date_format( $order->get_date_created(), 'Y-m-d' );
		}
		$language          = $document_settings['invoice_lang'];
		$smartbill_invoice = array(
			'companyVatCode' => $company_vat_code,
			'client'         => $client_details,
			'issueDate'      => $document_date,
			'seriesName'     => $document_settings['document_series'],
			'isDraft'        => $is_draft ? true : false,
			'observations'   => '',
			'currency'       => $currency,
			'language'       => $language,
			'products'       => $products,
		);

		if(true == $document_settings['payment_url'] ){
		//&& Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE == $document_settings['document_type']){
			$smartbill_invoice['paymentUrl'] = 'Generate URL';
		}

		if ( ! empty( $document_settings['show_order_mention'] ) ) {
			$smartbill_invoice['mentions'] = str_replace( '#nr_comanda_online#', $order_id, $document_settings['show_order_mention'] );
			$smartbill_invoice['mentions'] = str_replace( '#tip_plata#', $order->get_payment_method_title(), $smartbill_invoice['mentions'] );
		}
		

		if ( '1' == $document_settings['add_delegate_data'] ) {
			if ( ! empty( $document_settings['issuer_name'] ) ) {
				$smartbill_invoice['issuerName'] = $document_settings['issuer_name'];
			}
			if ( ! empty( $document_settings['issuer_cnp'] ) ) {
				$smartbill_invoice['issuerCnp'] = $document_settings['issuer_cnp'];
			}
			if ( ! empty( $document_settings['delegate_name'] ) ) {
				$smartbill_invoice['delegateName'] = $document_settings['delegate_name'];
			}
			if ( ! empty( $document_settings['delegate_bulletin'] ) ) {
				$smartbill_invoice['delegateIdentityCard'] = $document_settings['delegate_bulletin'];
			}
			if ( ! empty( $document_settings['delegate_auto'] ) ) {
				$smartbill_invoice['delegateAuto'] = $document_settings['delegate_auto'];
			}
		}

		if ( ! empty( $document_settings['show_order_obs'] ) ) {
			$smartbill_invoice['observations'] = str_replace( '#nr_comanda_online#', $order_id, $document_settings['show_order_obs'] );
		}

		if ( true == $document_settings['use_intra_cif'] && 'RO' !== $client_details['country'] ) {
			$smartbill_invoice['useIntraCif'] = true;
		}

		if ( '1' == $send_mail_with_document ) {
			$smartbill_invoice['sendEmail'] = true;
			$cc_bcc                         = array();
			$cc_bcc['cc']                   = $document_settings['send_mail_cc'];
			$cc_bcc['bcc']                  = $document_settings['send_mail_bcc'];
			$smartbill_invoice['email']     = $cc_bcc;
		}

		if ( $show_delivery_days ) {
			$smartbill_invoice['deliveryDate'] = gmdate( 'Y-m-d', time() + absint( $delivery_days ) * 24 * 3600 );
		}
		if ( $document_settings['issue_with_due_date'] ) {
			$smartbill_invoice['dueDate'] = gmdate( 'Y-m-d', time() + absint( $due_days ) * 24 * 3600 );
		}
		if ( isset( $document_settings['useStock'] ) ) {
			$smartbill_invoice['useStock'] = $document_settings['useStock'];
		}
		// Add order fields for 'TVA la incasare' setting.
		if ( $is_vat_payable ) {
			$smartbill_invoice['usePaymentTax'] = false;
			if ( $document_settings['use_payment_tax'] ) {
				$smartbill_invoice['usePaymentTax'] = true;
				$smartbill_invoice['paymentBase']   = 0;
				$smartbill_invoice['colectedTax']   = 0;
				$smartbill_invoice['paymentTotal']  = 0;
			}
		}

		$client = new SmartBill_Cloud_REST_Client( $login_options['username'], $login_options['password'] );
		$client->set_woocommerce_order_id( $order_id );

		$wp_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$debug_mode = $wp_settings->get_debugging_mode();
		} else {
			$debug_mode = false;
		}

		if ( $debug_mode ) {
			$client->set_woocommerce_settings_details( SmartBillUtils::export_settings() );
			$client->set_woocommerce_full_details( SmartBillUtils::export_order( $order_id ) );
		}

		$invoice_logger = new SmartBill_Data_Logger( $order_id );
		$client->set_data_logger( $invoice_logger );
		if ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE == $document_settings['document_type'] ) {
			if ( ! empty( $order->get_date_paid() ) && true == $document_settings['invoice_cashing'] ) {
					$smartbill_invoice['payment'] = array(
						'value'  => $order->get_total(),
						'type'   => 'Card online',
						'isCash' => false,
					);
			}
			$server_call = $client->create_invoice_with_document_address( $smartbill_invoice, $debug_mode );
		} elseif ( Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_ESTIMATE == $document_settings['document_type'] ) {
			$server_call = $client->create_proforma_with_document_address( $smartbill_invoice, $debug_mode );
		} else {
			throw new \Exception( __( 'Tipul de document emis este invalid.', 'smartbill-woocommerce' ) );
		}
		if ( $server_call['errorText'] ) {
			$return['status']  = false;
			$return['message'] = $server_call['message'];
			$return['error']   = $server_call['errorText'];
			$return['headers'] = $server_call['get_headers'];
		} else {
			$return['status']  = true;
			$return['headers'] = $server_call['get_headers'];
			if ( isset( $server_call['number'] ) && ( $server_call['number'] ) ) {
				$return['message'] = __( 'Documentul a fost emis cu succes: ', 'smartbill-woocommerce' ) . $server_call['message'] . $server_call['series'] . ' ' . $server_call['number'] . '.';
				$invoice_logger->set_data( $order_id, 'smartbill_invoice_id', $server_call['number'] )
					->set_data( $order_id, 'smartbill_series', $server_call['series'] )
					->set_data( $order_id, 'smartbill_document_url', $server_call['documentUrl'] )
					->set_data( $order_id, 'smartbill_view_document_url', $server_call['documentViewUrl'] )
					->set_data( $order_id, 'smartbill_status', Smartbill_Woocommerce_Settings::SMARTBILL_DATABASE_INVOICE_STATUS_FINAL )
					->save( $order_id );
			} else {
				if ( isset( $server_call['series'] ) ) {
					$invoice_logger->set_data( $order_id, 'smartbill_series', $server_call['series'] );
				}
				$invoice_logger->set_data( $order_id, 'smartbill_document_url', $server_call['documentUrl'] );
				$invoice_logger->set_data( $order_id, 'smartbill_status', Smartbill_Woocommerce_Settings::SMARTBILL_DATABASE_INVOICE_STATUS_DRAFT )->save( $order_id );
				$invoice_logger->set_data( $order_id, 'smartbill_view_document_url', $server_call['documentViewUrl'] );
				$return['message'] = __( 'Operatiunea s-a desfasurat cu succes: ', 'smartbill-woocommerce' ) . $server_call['message'];
			}
			$return['number'] = $server_call['number'];
			$return['series'] = $server_call['series'];
			$return['status'] = true;
			/* translators: Variables `series` and `number` are document info */
			$order->add_order_note( sprintf( __( 'Documentul SmartBill %1$s %2$s a fost creat.', 'smartbill-woocommerce' ), $return['series'], $return['number'] ) );
			if ( '1' == $send_mail_with_document ) {
				if ( ! empty( $cc_bcc['cc'] ) ) {
					$cc_bcc['cc'] = ', ' . $cc_bcc['cc'];}
				if ( ! empty( $cc_bcc['bcc'] ) ) {
					$cc_bcc['bcc'] = ', ' . $cc_bcc['bcc'];}
				/* translators: 4$s Factura 3$s bcc email addresses 2$s cc email addresses 1$s client email*/
				$message = sprintf( __( '%4$s a fost trimisa cu succes catre: %1$s%2$s%3$s.', 'smartbill-woocommerce' ), $client_details['email'], $cc_bcc['cc'], $cc_bcc['bcc'], 'Factura' );
				$order->add_order_note( esc_attr( $message ) );
			}
		}
	} catch ( Exception $e ) {
		$return['error']   = $e->getMessage();
		$return['message'] = $e->getMessage();
		$return['status']  = false;
	}

	if ( ! empty( $server_call['documentUrl'] ) ) {
		update_post_meta( $order_id, 'smartbill_private_link', $server_call['documentUrl'] );
	}
	return $return;

}


/**
 * Create settings array for later use
 *
 * @param WC_Order $order woocommerce order.
 * @param boolean  $is_vat_payable get_vat_info response.
 *
 * @return Array
 */
function smartbill_get_settings_for_order( $order, $is_vat_payable ) {
	$options        = get_option( 'smartbill_plugin_options_settings' );
	$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();

	if ( ! empty( $options ) && is_array( $options ) && isset( $options['document_type'] ) ) {
		$document_type = $options['document_type'];
	} else {
		$document_type = Smartbill_Woocommerce_Settings::SMARTBILL_DOCUMENT_TYPE_INVOICE;
	}

	if ( ! empty( $options ) && is_array( $options ) && isset( $options['stock'] ) ) {
		$stock = $options['stock'];
		if ( 'fara-gestiune' == $stock ) {
			$stock = '';
		}
	} else {
		$stock = '';
	}

	if ( ! empty( $options ) && is_array( $options ) && isset( $options['extra_taxes'] ) ) {
		$extra_taxes = $options['extra_taxes'];
	} else {
		$extra_taxes = array();
	}

	$doc_type = 'invoice';
	if ( 1 == $document_type ) {
		$doc_type = 'estimate';
	}

	$billing_currency = trim( $admin_settings->get_billing_currency() );
	// Create product settings for latter use.
	$document_settings = array(
		'included_vat'            => $admin_settings->get_included_vat(),
		'included_vat_coupons'    => 'yes' == get_option( 'woocommerce_calc_taxes' ) ? false : true,
		'use_payment_tax'         => $admin_settings->get_use_payment_tax(),
		'shipping_included_vat'   => $admin_settings->get_shipping_included_vat(),
		'um'                      => $admin_settings->get_um(),
		'saveProductToDb'         => $admin_settings->get_save_product(),
		'product_vat'             => $admin_settings->get_product_vat(),
		'isTaxPayer'              => $is_vat_payable,
		'useStock'                => false,
		'warehouse'               => $stock,
		'extraTaxes'              => $extra_taxes,
		'include_shipping'        => $admin_settings->get_include_shipping(),
		'taxes'                   => $order->get_taxes(),
		'shipping_vat'            => $admin_settings->get_shipping_vat(),
		// Intermediary fields for other functions.
		'companySettings'         => array(),
		'save_client'             => $admin_settings->get_save_client(),
		'document_series'         => $admin_settings->get_document_series( $doc_type ),
		'document_type'           => $document_type,
		'billing_currency'        => $billing_currency,
		'due_days'                => $admin_settings->get_due_days(),
		'delivery_days'           => $admin_settings->get_delivery_days(),
		'show_delivery_days'      => $admin_settings->get_show_delivery_days(),
		'invoice_is_draft'        => $admin_settings->get_invoice_is_draft(),
		'issue_with_due_date'     => $admin_settings->get_issue_with_due_date(),
		'send_mail_with_document' => $admin_settings->get_send_mail_with_document(),
		'send_mail_cc'            => $admin_settings->get_send_mail_cc(),
		'send_mail_bcc'           => $admin_settings->get_send_mail_bcc(),
		'smartbill_product'       => $admin_settings->get_smartbill_product(),
		'document_date'           => $admin_settings->get_document_date(),
		'use_intra_cif'           => $admin_settings->get_cif(),
		'invoice_lang'            => $admin_settings->get_language(),
		'currency'                => $admin_settings->get_billing_currency(),
		'show_order_mention'      => $admin_settings->get_show_order_mention(),
		'show_order_obs'          => $admin_settings->get_show_order_obs(),
		'coupon_text'             => $admin_settings->get_coupon_text(),
		'shipping_name'           => $admin_settings->get_shipping_name(),
		'discount_text'           => $admin_settings->get_discount_text(),
		'invoice_cashing'         => $admin_settings->get_invoice_cashing(),
		'issuer_cnp'              => $admin_settings->get_issuer_cnp(),
		'issuer_name'             => $admin_settings->get_issuer_name(),
		'add_delegate_data'       => $admin_settings->get_add_delegate_data(),
		'delegate_name'           => $admin_settings->get_delegate_name(),
		'delegate_bulletin'       => $admin_settings->get_delegate_bulletin(),
		'delegate_auto'           => $admin_settings->get_delegate_auto(),
		'free_shipping'			  => $admin_settings->get_free_shipping(),
		'payment_url' 		      => $admin_settings->get_payment_url(),
	);

	if ( ! empty( $stock ) ) {
		$document_settings['useStock'] = true;
	}
	if ( 1 == $document_type ) {
		unset( $document_settings['useStock'] );
	}
	return $document_settings;
}


/**
 *  Add smartbill column in admin orders page
 *
 *  @param Array $columns array with columns for admin orders table.
 */
function smartbill_add_invoice_column( $columns ) {
	$columns['smartbill_woocommerce_invoice'] = 'SmartBill';
	return $columns;
}

/**
 *  Add content in smartbill column from woocommerce orders page
 *
 *  @param Array $column array with columns for admin orders table.
 */
function smartbill_add_invoice_column_content( $column ,$order) {
	if( is_a($order,'WC_Order')){
		$order_id = $order->get_id();
	}else{
		$order_id=$order;
	}
	
	switch ( $column ) {
		case 'smartbill_woocommerce_invoice':
			$invoice_log        = get_post_meta( $order_id, 'smartbill_invoice_log', true );
			$document_url       = get_post_meta( $order_id, 'smartbill_private_link', true );
						$series = isset( $invoice_log['smartbill_series'] ) ? $invoice_log['smartbill_series'] : '';
			$number             = isset( $invoice_log['smartbill_invoice_id'] ) ? $invoice_log['smartbill_invoice_id'] : '';
			// Modificam sa duca spre vizualizare, nu spre editare.
			$pattern      = '/editare/';
			$replacement  = 'vizualizare';
			$document_url = preg_replace( $pattern, $replacement, $document_url, -1 );

			if ( ! empty( $series ) && ! empty( $number ) && ! empty( $document_url ) ) {
				echo '<a href="' . esc_url( $document_url ) . '" target="_blank">' . esc_attr( $series ) . ' ' . esc_attr( $number ) . '</a>';
			}
			break;

		case 'wc_actions':
		case 'order_actions':
			smartbill_order_details_invoice_column_button( $column, $order_id );
			break;
	}
}


if ( check_smartbill_compatibility() || in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_filter( 'manage_woocommerce_page_wc-orders_columns', 'smartbill_add_invoice_column', 11 );
	add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'smartbill_add_invoice_column_content', 11, 2 );
	add_filter( 'manage_edit-shop_order_columns', 'smartbill_add_invoice_column', 11 );
	add_action( 'manage_shop_order_posts_custom_column', 'smartbill_add_invoice_column_content', 11, 2 );
	add_action( 'add_meta_boxes', 'smartbill_order_details_meta_box', 10, 2 );
	// display issue document button.
	add_action( 'init', 'smartbill_init_plugin_actions' );

	add_action( 'wp_ajax_smartbill_woocommerce_issue_document', 'smartbill_woocommerce_issue_document' );
	add_action( 'wp_ajax_smartbill_woocommerce_send_document_mail', 'smartbill_woocommerce_send_document_mail' );
}
