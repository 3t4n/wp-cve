<?php

class fast_CF_IPN_Class {

    function __construct( $data_arr ) {
        require_once( FASTCF_MAIN_PLUGINS_DIRR . '/fastmember/lib/common.php' );
        $this->FCF_process_ipn_data( $data_arr );
    }

    function get_CF_product_data($CF_prodid) {
        if ( !isset( $CF_prodid ) || $CF_prodid == "" )
            return false;
        global $wpdb;
        $proddata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpbn_products
                WHERE FIND_IN_SET('%s',fcfprodid) > 0", $CF_prodid ) );
        if( count( $proddata )>0 ) {
            return $proddata[0];
        } else { return false; }
    }

    function FM_existing_CFTXN_exit($txn_id) {
        global $wpdb;
        $stub = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}wpbn_transactions WHERE txn_id LIKE %s", '%'.$txn_id.'[%') );
        if ($stub) {
            fmLog(_fm("Transaction ID already in transaction table"));
            return true;
        }
        return false;
    }

    function FCF_process_ipn_data($data_arr) {

        global $wpdb;

        $purchase_id = empty($data_arr['id']) ? $data_arr['purchase']['id'] : $data_arr['id'];
        $purchase_products = empty($data_arr['attributes']['products']) ? $data_arr['purchase']['products'] : $data_arr['attributes']['products'];

        $now = time();
        $txn_id = sanitize_text_field( $purchase_id );
        $fmpcount = 0; $numpayments = 0;
		$CF_paymnt_counts = 0;

        foreach ( $purchase_products as $prod_arr ) {
            $chk_prod_id = sanitize_text_field( $prod_arr['id'] );
            $proddata = $this->get_CF_product_data( $chk_prod_id );
            if ( $proddata !== false ) {
                $prodid = $proddata->id;
                $fmpcount++;
            } else continue;

            if ( $fmpcount == 1 ) {
                $contact_email = empty($data_arr['attributes']['contact']['email']) ? $data_arr['purchase']['contact']['email'] : $data_arr['attributes']['contact']['email'];
                $usermail = sanitize_email( $contact_email );
                $userid = FM_get_buyer_UID($usermail) ;
                $firstname =  $data_arr['attributes']['contact']['first-name'];
                $lastname =  $data_arr['attributes']['contact']['last-name'];
                $isnewuser = 0;
                if (!$userid) {
                    $livepass = wp_generate_password();
                    $userid = wp_create_user($usermail, $livepass, $usermail);
                    if (is_object($userid)) {
                        $userid = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}users WHERE user_login=%s", $usermail ) );
                        fmLog("New user creating error.");
                    } else {
                        $isnewuser = 1;
                        fmLog("New user: $userid");
                    }

                }
                wp_update_user( array( 'ID' => $userid, 'first_name' => $firstname, 'last_name' => $lastname, 'display_name' => $firstname.' '.$lastname  ) );
                if (!$userid) {
                    $userid = 0;
                    fmLog("Found no userid.");
                    return;
                }
            }
            $amount_cents = empty($prod_arr['amount_cents']) ? intval( $prod_arr['amount']['fractional'] ) : $prod_arr['amount_cents'];
            $pamount2 = sanitize_text_field( $amount_cents );
			$pamount = (float)$pamount2/100 ;
			$recpaidamount = $pamount;
            $expires = 0;
            $addinterval = 100*365*24*3600; // default - one time payment - 100 years (lifetime)
            $numpayments = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}wpbn_transactions
                            WHERE userid=%s AND prodid=%s AND txn_id=%s", $userid, $prodid, $txn_id ) );

            if ($proddata->isrecurr == 0 && $numpayments>=1) {
                    return;
            }

            if ($proddata->isrecurr == 1) {      //subscriptions only with stripe for now

				if ( isset( $data_arr['attributes']['stripe-customer-token'] ) &&
					isset( $data_arr['attributes']['subscription-id'] ) ) {

					$stripe_subs_id = $data_arr['attributes']['subscription-id'];
					$option_name = 'cf_id_' . $stripe_subs_id;
					update_option( $option_name, $txn_id );

          if(!get_user_meta( $userid, '_stripe_customer_id', true )){
            $fast_stripe_options = get_option('fast_stripe_settings');
            // check if we are using test mode
            if ( isset( $fast_stripe_options['test_mode'] ) && $fast_stripe_options['test_mode'] ) {
                $stripe_secret_key = trim( $fast_stripe_options['test_secret_key'] );
            } else {
                $stripe_secret_key = trim( $fast_stripe_options['live_secret_key'] );
            }
            $options = [
                'headers'     => [
                  'Authorization' => 'Basic ' . base64_encode( $stripe_secret_key . ':' . '' )
                ],
                'timeout'     => 60,
                'sslverify'   => true,
            ];

            $response = wp_remote_get('https://api.stripe.com/v1/subscriptions/'.$stripe_subs_id, $options );
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                fmLog("CF stripe get subscription error ".$error_message);
            } else {
              $response_body = wp_remote_retrieve_body($response);
              $responseObj = json_decode($response_body);
              if(property_exists($responseObj, 'error')){
                fmLog("CF stripe get subscription error ".$responseObj->error->message);
              }else{
                $stripe_customer_id = $responseObj->customer;
                update_user_meta( $userid, '_stripe_customer_id', $stripe_customer_id );
              }
            }
          }

				}
                $numpayments = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}wpbn_transactions
                        WHERE userid=%s AND prodid=%s AND txn_id LIKE %s", $userid, $prodid, "%" . $txn_id . "%" ) );

                $rcv_paymnt_counts = empty( $data_arr['purchase']['payments_count'] ) ? 0 : $data_arr['purchase']['payments_count'];
                $CF_paymnt_counts2 = sanitize_text_field( $rcv_paymnt_counts );
                $CF_paymnt_counts = intval( $CF_paymnt_counts2 );

				if ($numpayments>=1 && $numpayments>=$CF_paymnt_counts ) {
                        return;
                }


                if ($proddata->trialquantity >= 1 && empty($numpayments) ) {
					$pamount = 0.00 ;
					$recpaidamount = $pamount;
				} else {
					$pamount = $proddata->curprice ;
					$recpaidamount = $pamount;
				}

				$subs_idx = empty($numpayments) ? 1 : $numpayments+1;
				if ($proddata->trialquantity >= 1) {
					$subs_idx = $subs_idx - 1;
				}
                $txn_id = $txn_id . "_paid_" . $subs_idx;

                $expires = $now;

                if ( $pamount == $proddata->trialprice ) {
                    $trialqty = $proddata->trialquantity;
                    $fmperioddays = fastmemgetPeriodDays($proddata->trialperiod);
                    $addinterval = 24*3600*$trialqty*$fmperioddays;
                } else {
                    $paidqty = $proddata->paidquantity;
                    $addinterval = 24*3600*$paidqty*fastmemgetPeriodDays($proddata->paidperiod);
                    $exp = $wpdb->get_results( $wpdb->prepare( "SELECT expires FROM {$wpdb->prefix}wpbn_users
                            WHERE (expires>%d) AND (userid=%s) AND (prodid=%s)", 1, $userid, $prodid ) );
                    if (count($exp)) $expires = $exp[0]->expires;
                    if(date('Y-m-d', time()) == date('Y-m-d', strtotime($expires))){
                      $expires = $expires;
                    }else{
                      $expires = time();
                    }
                    if ($proddata->numpayments) {
                        $needpayments = $proddata->numpayments;
                        if ($proddata->trialquantity) $needpayments++;
                        $numpayments = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}wpbn_transactions
                                            WHERE (userid=%s) AND (prodid=%s)", $userid, $prodid ) );
                        if ($numpayments >= $needpayments) $addinterval = 100*365*24*3600; //all installments paid
                    }
                }
            }

            $newexpire = $expires + $addinterval;
            $newmembership = 0;
            $stub = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}wpbn_users WHERE (userid=%s) AND (prodid=%s)", $userid, $prodid ) );
            if ($stub) {
                $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}wpbn_users
                        SET expires='$newexpire' WHERE (userid=%s) AND (prodid=%s);", $userid, $prodid ) );
                fmLog("Existing membership for userid=$userid AND prodid=$prodid");
            } else {
                $newmembership = 1;
                if ( !empty( $proddata->urole ) && trim( $proddata->urole ) !== "" ) {
                    wp_update_user( array( 'ID' => $userid, 'role' => $proddata->urole ) );
                }
                $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpbn_hits (rdparty, madesale, userid, prodid, price, postid, affid, track, htime, userip, rapisaff, otoid, coupon)
                        VALUES (%d, %d, %s, %s, %s, %d, %d, %s, %s, %s, %d, %d, %s);",12, 1, $userid, $prodid, $recpaidamount, 0, 0, '', $now, '', 0, 0, '' ) );
                $hitid = $wpdb->insert_id;
                $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpbn_users
                                        (hitid, prodid, userid, affid, tpurchased, expires, affcomm, innersubscr)
                                        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", $hitid, $prodid, $userid, '1', $now, $newexpire, '50', $proddata->addintar ) );
                fmLog("New membership for userid=$userid AND prodid=$prodid");
                fmAddToAR($userid, 1);
            }
            $txn_rec_id = $txn_id ;
            fmLog("New TXN for txn_id=$txn_rec_id");
            //record the transaction
            $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpbn_transactions (pp, txn_id, amount, userid, affid, affiliateemail, affcomm, israp, ttransaction, payer_email, prodid)
                                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);", '12', $txn_rec_id, $recpaidamount, $userid, '0', '', '0', '0', $now, $usermail, $prodid ) );
            $txn_idx_id = $wpdb->insert_id;
            do_action( 'FM_after_transaction_recorded', $txn_idx_id, $prodid, $userid );
            if ($newmembership) {
                $postid = $wpdb->get_var( $wpdb->prepare( "SELECT loginpage FROM {$wpdb->prefix}wpbn_products WHERE id=%s", $prodid ) );
                if (!$postid) $postid = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}posts WHERE post_content like %s", '%[fastmemloginform product="' . $prodid . '"]%' ) );
                if (!$postid) $postid = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}posts WHERE post_content like %s", '%[wpbuynowloginform product="' . $prodid . '"]%' ) );
                if ($postid) $url = get_permalink($postid);
                else $url = site_url('/wp-login.php');
                $userdata = get_userdata($userid);
                if($isnewuser == 0) $livepass = FM_get_preexist_message();
                fmSendWelcomeEmail($prodid, $userid, $livepass);
                if (!fmSendConfirmARMessage($proddata, $userdata)) fmSendFirstARMessage($proddata, $userdata);
            }
        }

        fmLog("Made it to the end of the ClickFunnels IPN processing code");
        return 1;

    }


}
