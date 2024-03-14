<?php

/*
*	EPH XML method
*/
function tsseph_get_EPH($post_ids) {

    $tsseph_options = get_option( 'tsseph_options' );
	$tsseph_options = tsseph_init_new_vars($tsseph_options);
    $tsseph_bonus_options = get_option( 'tsseph_bonus_options' );

	$orders = tsseph_categorize_orders($post_ids, $tsseph_options);

    $ciarovy_kod = '';

    $eph_sets = array();

    foreach ( $orders as $eph_shipping_method_id => $order ) {

        $EPH = new SimpleXMLElement('<EPH/>');
        $EPH->addAttribute('verzia', '3.0');
    
        $InfoEPH = $EPH->addChild('InfoEPH');
            $Mena = $InfoEPH->addChild('Mena','EUR');
            $TypEPH = $InfoEPH->addChild('TypEPH', $tsseph_options['TypEPH']);
            $EPHID = $InfoEPH->addChild('EPHID');
            $Datum = $InfoEPH->addChild('Datum',date('Ymd',time()));
            $PocetZasielok = $InfoEPH->addChild('PocetZasielok',count($order));
        
        $Uhrada = $InfoEPH->addChild('Uhrada');
            $SposobUhrady = $Uhrada->addChild('SposobUhrady',$tsseph_options['SposobUhrady']);
            $SumaUhrady = $Uhrada->addChild('SumaUhrady','0.00');
        
            $DruhPPP = $InfoEPH->addChild('DruhPPP');
            $DruhZasielky = $InfoEPH->addChild('DruhZasielky', $eph_shipping_method_id);
            if ($tsseph_options['SposobSpracovania'] != 0) $SposobSpracovania = $InfoEPH->addChild('SposobSpracovania',$tsseph_options['SposobSpracovania']);	
        
        $Odosielatel = $InfoEPH->addChild('Odosielatel');
            $OdosielatelID = $Odosielatel->addChild('OdosielatelID');
            $Meno = $Odosielatel->addChild('Meno',$tsseph_options['Meno']);	
            $Organizacia = $Odosielatel->addChild('Organizacia',$tsseph_options['Organizacia']);	
            $Ulica = $Odosielatel->addChild('Ulica',$tsseph_options['Ulica']);	
            $Mesto = $Odosielatel->addChild('Mesto',$tsseph_options['Mesto']);	
            $PSC = $Odosielatel->addChild('PSC',$tsseph_options['PSC']);	
            $Krajina = $Odosielatel->addChild('Krajina',$tsseph_options['Krajina']);	
            $Telefon = $Odosielatel->addChild('Telefon',$tsseph_options['Telefon']);			
            $Email = $Odosielatel->addChild('Email',$tsseph_options['Email']);	
            $CisloUctu = $Odosielatel->addChild('CisloUctu',$tsseph_options['CisloUctu']);	
            
        $Zasielky = $EPH->addChild('Zasielky');

    foreach ( $order as $post_id ) {

        //Check if ciarovy kod is needed
        if ($tsseph_options['PodacieCislaEnabled']) {
            $parcel_number = tsseph_get_parcel_number($eph_shipping_method_id,'XML');
        } 

        $wer_order = tsseph_get_woo_data($post_id);

            $Zasielka = $Zasielky->addChild('Zasielka');
                $Adresat = $Zasielka->addChild('Adresat');
                    $Meno = $Adresat->addChild('Meno',$wer_order['first_name'] . " " . $wer_order['last_name']);
                    $Organizacia = $Adresat->addChild('Organizacia',$wer_order['company']);
                    $Ulica = $Adresat->addChild('Ulica',$wer_order['address_1'] . " " . $wer_order['address_2']);
                    $Mesto = $Adresat->addChild('Mesto',$wer_order['city']);
                    $PSC = $Adresat->addChild('PSC',$wer_order['postcode']);
                    $Krajina = $Adresat->addChild('Krajina',$wer_order['country']);
                    $Telefon = $Adresat->addChild('Telefon',$wer_order['phone']);
                    $Email = $Adresat->addChild('Email',$wer_order['email']);
                    
                $Spat = $Zasielka->addChild('Spat');

				if ($tsseph_options['RovnakaNavratova'] == 1) {
					$Meno = $Spat->addChild('Meno',$tsseph_options['Meno']);
					$Organizacia = $Spat->addChild('Organizacia',$tsseph_options['Organizacia']);
					$Ulica = $Spat->addChild('Ulica',$tsseph_options['Ulica']);
					$Mesto = $Spat->addChild('Mesto',$tsseph_options['Mesto']);
					$PSC = $Spat->addChild('PSC',$tsseph_options['PSC']);
					$Krajina = $Spat->addChild('Krajina',$tsseph_options['Krajina']);	
				}
				else {
					$Meno = $Spat->addChild('Meno',$tsseph_options['SMeno']);
					$Organizacia = $Spat->addChild('Organizacia',$tsseph_options['SOrganizacia']);
					$Ulica = $Spat->addChild('Ulica',$tsseph_options['SUlica']);
					$Mesto = $Spat->addChild('Mesto',$tsseph_options['SMesto']);
					$PSC = $Spat->addChild('PSC',$tsseph_options['SPSC']);
					$Krajina = $Spat->addChild('Krajina',$tsseph_options['SKrajina']);	
				}
                    
                $Info = $Zasielka->addChild('Info');
                    $CiarovyKod = $Info->addChild('CiarovyKod',$parcel_number);
                    $ZasielkaID = $Info->addChild('ZasielkaID', tsseph_limit_string($wer_order['order_number'],10));
                    $Hmotnost = $Info->addChild('Hmotnost',( $wer_order['order_weight'] != 0  ? $wer_order['order_weight'] : ''));
                    
                    $CenaDobierky = $Info->addChild('CenaDobierky',$wer_order['cena_dobierky']);
                    
                    $CenaPoistneho = $Info->addChild('CenaPoistneho', ($eph_shipping_method_id == 2 ? min($wer_order['total'], 500) : ''));
                    $CenaVyplatneho = $Info->addChild('CenaVyplatneho');
                    $Trieda = $Info->addChild('Trieda',$tsseph_options['Trieda']);
                    $CisloUctu = $Info->addChild('CisloUctu',$tsseph_options['CisloUctu']);
                    $SymbolPrevodu = $Info->addChild('SymbolPrevodu', tsseph_limit_string($wer_order['order_number'],10) );   
                    $Poznamka = $Info->addChild('Poznamka');
                    $DruhPPP = $Info->addChild('DruhPPP');
                    $DruhZasielky = $Info->addChild('DruhZasielky', $eph_shipping_method_id);

                    //Even if $PocetKusov = 1, it is considered by EPH as "viackusová zásielka"
                    if (tsseph_get_pocet_kusov($eph_shipping_method_id) > 1) {
                        $PocetKusov = $Info->addChild('PocetKusov', tsseph_get_pocet_kusov($eph_shipping_method_id));
                    }
            
                    $ObsahZasielky = $Info->addChild('ObsahZasielky');	

                    if ($wer_order['tsseph_shipping_method_id'] == 3 || $wer_order['tsseph_shipping_method_id'] == 8 || $wer_order['order_fragile'] == 1) {
                        $PouziteSluzby = $Zasielka->addChild('PouziteSluzby');   
                        if ($wer_order['tsseph_shipping_method_id'] == 3 || $wer_order['tsseph_shipping_method_id'] == 8) {
                            $Sluzba = $PouziteSluzby->addChild('Sluzba','PR');   
                        }
                        if ($wer_order['order_fragile'] == 1) {
                            $Sluzba = $PouziteSluzby->addChild('Sluzba','F');   
                        }                        
                    }

                    //Add ulozna doba if enabled and not equal to 0
                    if (isset($tsseph_bonus_options[1470]) && $tsseph_bonus_options[1470]['Enabled'] && isset($tsseph_options['UloznaLehota']) && $tsseph_options['UloznaLehota'] != 0) { 
                        $DalsieUdaje = $Zasielka->addChild('DalsieUdaje');
                        $Udaj = $DalsieUdaje->addChild('Udaj');
                        $Nazov = $Udaj->addChild('Nazov','UloznaLehota');  
                        $Hodnota = $Udaj->addChild('Hodnota',$tsseph_options['UloznaLehota']);  
                    }
        }

        $eph_sets[] = $EPH; 
    }

    return $eph_sets;
}

/*
*	EPH NEW API method
*/
function tsseph_get_EPH_parcels($post_ids) {

    $tsseph_options = get_option( 'tsseph_options' );
    $tsseph_bonus_options = get_option( 'tsseph_bonus_options' );
    
    //Reset log 
    $tsseph_options['LastLog'] = array();
    update_option('tsseph_options', $tsseph_options);

    $tsseph_options = tsseph_init_new_vars($tsseph_options);

    $orders = tsseph_categorize_orders($post_ids, $tsseph_options);

    $eph_sets = array();

    foreach ( $orders as $eph_shipping_method_id => $order ) {

    //Prepare orders
    $order_i = 0;
    $order_numbers = array();
    $druh_zasielky = tsseph_posta_api_get_druh_zasielky($eph_shipping_method_id);
    $parcel_number = '';

        foreach ( $order as $post_id ) {

            $wer_order = tsseph_get_woo_data($post_id);

            $order_numbers[$post_id] = $wer_order['order_number'];
    
            //Check if ciarovy kod is needed
            if ($tsseph_options['PodacieCislaEnabled']) {
                $parcel_number = tsseph_get_parcel_number($eph_shipping_method_id,'API');
            } 

            //Define return address
            if ($tsseph_options['RovnakaNavratova'] == 1) {
                $Spat = array(
                    'name' => $tsseph_options['Meno'],
                    'organization' => $tsseph_options['Organizacia'],
                    'street' => $tsseph_options['Ulica'],
                    'city' => $tsseph_options['Mesto'],
                    'zip' => $tsseph_options['PSC'],
                    'country' => $tsseph_options['Krajina']);
            }
            else {
                $Spat = array(
                    'name' => $tsseph_options['SMeno'],
                    'organization' => $tsseph_options['SOrganizacia'],
                    'street' => $tsseph_options['SUlica'],
                    'city' => $tsseph_options['SMesto'],
                    'zip' => $tsseph_options['SPSC'],
                    'country' => $tsseph_options['SKrajina']);
            }

            $Zasielky['Zasielky'][$post_id] = array(
                    'id' => tsseph_limit_string($wer_order['order_number'],10),
                    'parcel_number' => $parcel_number,
                    'recipient' => array(
                        'name' => $wer_order['first_name'] . " " . $wer_order['last_name'],  
                        'organization' => $wer_order['company'],
                        'street' => $wer_order['address_1'] . " " . $wer_order['address_2'],
                        'city' => $wer_order['city'],
                        'zip' => $wer_order['postcode'],
                        'country' => $wer_order['country'],
                        'phone' => $wer_order['phone'],
                        'email' => $wer_order['email']
                    ),
                    'back' => $Spat,
                    'parcel_class' => 'c' . $tsseph_options['Trieda'],
                    'services' => tsseph_posta_api_get_doplnkove_sluzby($wer_order),
                    'note' => tsseph_limit_string($wer_order['order_number'],10)
                );

                //Add Cod if any
                if ($wer_order['cena_dobierky'] != 0) {
                    $Zasielky['Zasielky'][$post_id]['cod'] = array(
                            'type'=> 'bdnu', 
                            'amount'    => array('value' => round(floatval($wer_order['cena_dobierky']),2), 'currency' => 'EUR'), 
                            'iban'      => $tsseph_options['CisloUctu'],
                            'symbol'    => tsseph_limit_string($wer_order['order_number'],10)
                    );
                }

                //Add Weight if any
                if ($wer_order['order_weight'] != 0) {
                    $Zasielky['Zasielky'][$post_id]['weight'] = round(floatval($wer_order['order_weight']),3);
                }

                //Add insurance if any
                if ($eph_shipping_method_id == 2) {
                    $Zasielky['Zasielky'][$post_id]['insurance'] = array(
                        'value' => round(floatval(min($wer_order['total'],500)),2),
                        'currency' => 'EUR'
                    );
                }                
            
                //Add parts if any
                if (tsseph_get_pocet_kusov($eph_shipping_method_id) != '') {
                    $Zasielky['Zasielky'][$post_id]['parts'] = tsseph_get_pocet_kusov($eph_shipping_method_id);
                }     
                
                //Add ulozna doba if enabled and not equal to 0
                if (isset($tsseph_bonus_options[1470]) && $tsseph_bonus_options[1470]['Enabled'] && isset($tsseph_options['UloznaLehota']) && $tsseph_options['UloznaLehota'] != 0) { 
                    $Zasielky['Zasielky'][$post_id]['handover_period'] = intval($tsseph_options['UloznaLehota']);
                }

            $order_i++;
        }

        $EPH = array(
                'sheet' => array(
                    'parcel_category' => $druh_zasielky['code'],
                    'payment_type' => tsseph_posta_api_get_sposob_uhrady($tsseph_options['SposobUhrady']),
                    'reception_method' => 'post',
                    'sender' => array(
                        'name' 			=> $tsseph_options['Meno'],
                        'organization' 	=> $tsseph_options['Organizacia'],
                        'street' 		=> $tsseph_options['Ulica'],
                        'city' 			=> $tsseph_options['Mesto'],
                        'zip'			=> $tsseph_options['PSC'],
                        'country'		=> $tsseph_options['Krajina'],
                        'phone'			=> $tsseph_options['Telefon'],
                        'email'			=> $tsseph_options['Email'] 
                    ),
                    'contract' => ($tsseph_options['ZmluvnyVztahEnabled'] ? true : false),
                    'own_parcel_numbers' => (($tsseph_options['PodacieCislaEnabled'] && !empty($parcel_number)) ? true : false),
                    'parcels' => $Zasielky['Zasielky']        
            ),
        );

        $eph_sets[] = array(
            'EPH' => $EPH,
            'OrderNumbers' => $order_numbers
        ); 

        $Zasielky['Zasielky'] = [];
    }

    return $eph_sets;
}

/*
*	Get WooCommerce order data
*/
function tsseph_get_woo_data($post_id) {

    $tsseph_options = get_option( 'tsseph_options' );

    $order = wc_get_order($post_id);
    
    //Get eph shipping method
    $eph_shipping_method = $order->get_meta('tsseph_shipping_method_id', true);  

    if (empty($eph_shipping_method)) {

        foreach( $order->get_items( 'shipping' ) as $Shipping_item_obj ){
			$shipping_instance_id = $Shipping_item_obj->get_instance_id();
		}

        $eph_shipping_method = (isset($tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id]) ? $tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id] : 1);
    }

    //Get Order weight
    $weight = tsseph_calculate_weight($post_id);
    $order_fragile = !empty($order->get_meta('tsseph_fragile', true )) ? $order->get_meta('tsseph_fragile', true ) : 0;
      
    //WooCommerce version check 
    global $woocommerce;

    if(version_compare( $woocommerce->version, '3.0', ">=" )) {
        $order_data = array_merge( array( 'id' => $order->get_id() ), $order->get_data(), array( 'meta_data' => $order->get_meta_data() ) );
        
        $wer_order = array(
            'first_name' => $order_data['shipping']['first_name'],
            'last_name' => $order_data['shipping']['last_name'],
            'company' => $order_data['shipping']['company'],
            'address_1' => $order_data['shipping']['address_1'],
            'address_2' => $order_data['shipping']['address_2'],
            'city' => $order_data['shipping']['city'],
            'postcode' => $order_data['shipping']['postcode'],
            'country' => $order_data['shipping']['country'],
            'phone' => $order_data['billing']['phone'],
            'email' => $order_data['billing']['email'],
            'total' => $order_data['total'],
            'payment_method' => $order_data['payment_method'],
            'order_weight' => $weight,
            'order_fragile' => $order_fragile,
            'order_number' => $order->get_order_number()
        );	
    } 
    else {
        $wer_order = array(
            'first_name' => $order->shipping_first_name,
            'last_name' => $order->shipping_last_name,
            'company' => $order->shipping_company,
            'address_1' =>  $order->shipping_address_1,
            'address_2' =>  $order->shipping_address_2,
            'city' => $order->shipping_city,
            'postcode' => $order->shipping_postcode,
            'country' => $order->shipping_country,
            'phone' => $order->billing_phone,
            'email' => $order->billing_email,
            'total' => $order->get_total(),
            'payment_method' => $order->payment_method,
            'order_weight' => $weight,
            'order_fragile' => $order_fragile,
            'order_number' => $order->get_order_number()
        );	        
    }

    $wer_order['tsseph_shipping_method_id'] = $eph_shipping_method;

    //Calculate dobierka value
    if (in_array($wer_order['payment_method'], $tsseph_options['PaymentType'])) {
        $wer_order['cena_dobierky'] = $wer_order['total'];
    } 
    else {
        $wer_order['cena_dobierky'] = 0;
    }


    return $wer_order;
}

/*
*   Calculate weight
*/
function tsseph_calculate_weight($post_id) {
    $weight = 0;
    $weight_unit = get_option( 'woocommerce_weight_unit');

    $order = wc_get_order($post_id);

    if (!empty($order->get_meta('tsseph_weight',true))) {
        $weight = $order->get_meta('tsseph_weight',true);
    }
    else {

        foreach ( $order->get_items() as $item ) {
    
            $variant = wc_get_product($item->get_variation_id());
            $product = wc_get_product($item->get_product_id());

            if (gettype($product) != 'boolean' && $product->has_weight()) {
                if ($product->get_type() == 'variable') {
                    if (!empty($variant) && $variant->has_weight()) {
                        $weight += $variant->get_weight() * $item->get_quantity();
                    }
                    else {
                        $weight += $product->get_weight() * $item->get_quantity();
                    }
                }
                else {
                    $weight += $product->get_weight() * $item->get_quantity();
                }
            }
        }

        //Convert weight to KG
        $weight = wc_get_weight($weight,'kg',$weight_unit);
    }


    return $weight;
}

/*
*   Get pocet kusov (Expres kuriér)
*/
function tsseph_get_pocet_kusov($eph_shipping_method_id) {
    $pocet_kusov = '';

    if ($eph_shipping_method_id == 8) {
        $pocet_kusov = 1;
    }

    return  $pocet_kusov;
}

/*
*   Orders with same eph_shipping_method needs to be sent together
*/
function tsseph_categorize_orders($post_ids, $tsseph_options) {

    foreach ( $post_ids as $post_id ) {
        //Get eph shipping method

        $order = new WC_Order($post_id); 

        $eph_shipping_method = $order->get_meta('tsseph_shipping_method_id', true);  

        if (empty($eph_shipping_method)) {

			foreach( $order->get_items( 'shipping' ) as $Shipping_item_obj ){
				$shipping_instance_id = $Shipping_item_obj->get_instance_id();
			}

			if (!empty($tsseph_options['PredvolenyDruhZasielky']) && empty($tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id])) {
				$eph_shipping_method = $tsseph_options['PredvolenyDruhZasielky'];
			} else {
				$eph_shipping_method = (!empty($tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id]) ? $tsseph_options['PredvolenyDruhZasielky_' . $shipping_instance_id] : 1); 
			}	
			
        }

        //Categorize orders
        $orders[tsseph_get_druh_zasielky( $eph_shipping_method)][] = $post_id;
    }

    return $orders;
}

/*
*	Get ID of druh zasielky
*/
function tsseph_get_druh_zasielky($eph_shipping_method) {
    $druh_zasielky = 1;

    switch($eph_shipping_method) {
        case 2: 
            $druh_zasielky = 4;
            break;
        case 3:
            $druh_zasielky = 4;
            break;      
        case 4:
            $druh_zasielky = 14;
            break; 
        case 5:
            $druh_zasielky = 8;
            break;     
		case 6:
			$druh_zasielky = 2;
			break;   
        case 7:
            $druh_zasielky = 30;
            break;    
        case 8:
            $druh_zasielky = 8;
            break;                                   			          
        default:
            $druh_zasielky = 1;                    
    }

    return $druh_zasielky;
}

/*
*	Decide wether to include order weight
*/
function tsseph_include_order_weight($SposobUhrady) {
    $include_weight = false;

    if (($SposobUhrady >= 1 && $SposobUhrady <= 3) ||  ($SposobUhrady >= 8 && $SposobUhrady <= 9)) {
        $include_weight = true;
    }

    return $include_weight;
}

/*
* Verify number is numeric
*/
function tsseph_only_number($value) {
    if (is_numeric($value)) {
        return $value;
    }
    else {
        if (is_numeric(str_replace(',', '.', $value))) {
            return (str_replace(',', '.', $value));
        }
        else {
            return 0;
        }
    }
}

/*
* Get order tracking code, if do not exist, fetch it
*/
function tsseph_get_tracking_code($order_id) {

    $order = wc_get_order($order_id);

	$tsseph_sheet_id = !empty($order->get_meta('tsseph_sheet_id', true )) ? $order->get_meta( 'tsseph_sheet_id', true ) : '';
	$tsseph_tracking_no = !empty( $order->get_meta('tsseph_tracking_no', true )) ?  $order->get_meta('tsseph_tracking_no', true ) : ''; //Ciarovy kod

	//Get order tracking number
	if ($tsseph_sheet_id != '' && $tsseph_tracking_no == '') {


		$data = array(
			'auth' => tsseph_posta_api_get_auth(),
			'sheetId' => $tsseph_sheet_id
		);

		$response = tsseph_posta_api('getSheet',$data);


		if (!empty($response) && $response->sheetStatus == 'registered') {

			//If sheet contains more than one order, array is used
			if (is_array($response->EPH->Zasielky->Zasielka)) {
				foreach($response->EPH->Zasielky->Zasielka as $zasielka) {
					if ($zasielka->Info->ZasielkaID == $order->get_order_number()) {
						if (!empty($zasielka->Info->CiarovyKod)) {
							
							$tsseph_tracking_no = $zasielka->Info->CiarovyKod;
						}
					}
				}
			}
			//In case only one order in sheet
			else {
				if ($response->EPH->Zasielky->Zasielka->Info->ZasielkaID == $order->get_order_number()) {
					if (!empty($response->EPH->Zasielky->Zasielka->Info->CiarovyKod)) {
						
						$tsseph_tracking_no = $response->EPH->Zasielky->Zasielka->Info->CiarovyKod;
					}
				}				
			}

            $order->update_meta_data( 'tsseph_tracking_no',  $tsseph_tracking_no);
            $order->save();
		}
	}	

	return $tsseph_tracking_no;
}

/*
* Get SK Posta error reason text
*/
function tsseph_get_error_reason($code) {

	switch($code) {
		case 'out_of_range': $text = __('hodnota mimo rozsah','spirit-eph'); break; 
		case 'invalid_format': $text = __('neplatný formát','spirit-eph'); break; 
		case 'invalid_value': $text = __('neplatná hodnota','spirit-eph'); break; 
		case 'required': $text = __('povinný údaj','spirit-eph'); break; 
		case 'parcel_number_parcel_type_not_match': $text = __('podacie číslo nie je možné použiť pre daný druh zásielky','spirit-eph'); break; 
		case 'parcel_number_country_not_match': $text = __('podacie číslo nie je možné použiť pre uvedenú krajinu adresáta','spirit-eph'); break; 
		case 'parcel_number_subject_not_match': $text = __('podacie číslo bolo pridelené inému používateľovi','spirit-eph'); break; 
		case 'parcel_number_unavailable': $text = __('podacie číslo nie je možné použiť','spirit-eph'); break; 
		case 'parcel_number_already_used': $text = __('zásielka so zadaným podacím číslom už bola podaná','spirit-eph'); break; 
		case 'already_exists': $text = __('zásielka so zadaným podacím číslom už v hárku existuje','spirit-eph'); break; 
		case 'invalid_zip': $text = __('neplatné PSČ','spirit-eph'); break; 
		case 'invalid_bnp_zip ': $text = __('neplatné PSČ pre zásielku so službou „Na poštu/do BalíkoBOXu“','spirit-eph'); break; 
		case 'invalid_nobnp_street': $text = __('nepovolený spôsob doručenia','spirit-eph'); break;  
		case 'invalid_nopr_street': $text = __('nepovolený spôsob doručenia','spirit-eph'); break; 
		case 'invalid_post_name': $text = __('neplatný názov pošty pre dané PSČ','spirit-eph'); break; 
		case 'invalid_prefix_checksum': $text = __('neplatný kontrolný súčet','spirit-eph'); break; 
		case 'invalid_base_checksum': $text = __('neplatný kontrolný súčet','spirit-eph'); break; 
		case 'invalid_bank_code': $text = __('neplatný kód banky','spirit-eph'); break; 
		case 'invalid_iban_checksum': $text = __('neplatný kontrolný súčet IBANu','spirit-eph'); break; 
		default: $text = __('chyba','spirit-eph'); break; 
	}

	return $text;
}

/*
* Get place type
*/
function tsseph_get_druh_zasielky_options() {

	return array(
		'1' => __('Doporučený list','spirit-eph'),
		'2' => __('Balík na adresu','spirit-eph'),
		'3' => __('Balík na poštu','spirit-eph'),
		'4' => __('Balík - zmluvní zákazníci','spirit-eph'),
		'5' => __('Expres kuriér','spirit-eph'),
        '8' => __('Expres kuriér na poštu','spirit-eph'),
		'6' => __('Poistený list','spirit-eph'),
        '7' => __('List','spirit-eph')
	);
}

/*
* Limit string length
*/

function tsseph_limit_string($text,$length) {
	return (strlen($text) > $length ? mb_substr($text, (-1)*$length) : $text);
}

/*
*   Init variables that were added by update
*/
function tsseph_init_new_vars($tsseph_options) {
	if (!isset($tsseph_options['RovnakaNavratova'])) { $tsseph_options['RovnakaNavratova'] = 1; } 
	if (!isset($tsseph_options['SMeno'])) { $tsseph_options['SMeno'] = ""; } 
	if (!isset($tsseph_options['SOrganizacia'])) { $tsseph_options['SOrganizacia'] = ""; } 
	if (!isset($tsseph_options['SUlica'])) { $tsseph_options['SUlica'] = ""; } 
	if (!isset($tsseph_options['SMesto'])) { $tsseph_options['SMesto'] = ""; } 
	if (!isset($tsseph_options['SPSC'])) { $tsseph_options['SPSC'] = ""; } 
	if (!isset($tsseph_options['SKrajina'])) { $tsseph_options['SKrajina'] = "SK"; } 
	if (!isset($tsseph_options['SendTrackingNo'])) { $tsseph_options['SendTrackingNo'] = 1; } 

    //Vlastné podacie čísla
    if (!isset($tsseph_options['PodacieCislaEnabled'])) { $tsseph_options['PodacieCislaEnabled'] = 0; }
	if (!isset($tsseph_options['PodacieCislaEnabled']) && $tsseph_options['AktualnePodCislo'] != '') { $tsseph_options['PodacieCislaEnabled'] = 1; }

    //Zmluvný vzťah
    if (!isset($tsseph_options['ZmluvnyVztahEnabled'])) { $tsseph_options['ZmluvnyVztahEnabled'] = 0; }
   
	return $tsseph_options;
}

/*
*   Write Log Debugging
*/
if ( ! function_exists('write_log')) {
	function write_log ( $log )  {
	   if ( is_array( $log ) || is_object( $log ) ) {
		  error_log( print_r( $log, true ) );
	   } else {
		  error_log( $log );
	   }
	}
 }