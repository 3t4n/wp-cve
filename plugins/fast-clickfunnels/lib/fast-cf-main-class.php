<?php

class fast_CF_Main_Class {
    function __construct() {
        add_action('init', array($this, 'fastcf_ipn_handle' ) );
        if ( is_admin() ) {
            add_filter('fm_prod_third_party_int', array($this, 'fcf_third_party_int_html' ), 10, 1);
        }
    }

    function fcf_third_party_int_html($content) {

        $fcfprodid = sanitize_text_field( $_POST["fcfprodid"] );

        $content .= "<h2 style='margin-bottom:15px 0px;'>"._fm('ClickFunnels® Integration')."</h2>
					<div><p>
                    "._fm('You are ready to integrate with ClickFunnels')." (<a href='https://clickfunnels.net/' target='_blank'>Get ClickFunnels Here</a>).<br />
                    "._fm('In your ClickFunnels account please choose Access Integrations from the Edit Funnel options and add the following URL into the Webhook URL field:'). "<br/>
                    <input type='text' value='". site_url("/") ."?fcfipn_api=fast_CF_IPN_handle' readonly style='width: 100%;' /><br /><br />
                    "._fm('ClickFunnels Product  ID #')."&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' id='fcfprodid'  placeholder='Enter ClickFunnels product ID here' style='width: 30%;' maxlength='20' name='fcfprodid' value='$fcfprodid' /><br/><br/>
					</p>
                    <p>This integration is developed and maintained by FastFlow.io. Disclosure: FastFlow.io is an independent ClickFunnels Affiliate, not an employee. FastFlow.io may receive referral payments from ClickFunnels. The opinions expressed here are FastFlow.io's own and are not official statements of ClickFunnels or its parent company, Etison LLC.</p>

                    
                    </div>";
        return $content;
    }

    function fastcf_ipn_handle() {
        $raw_data = @file_get_contents("php://input");
        $raw_data_obj = json_decode( $raw_data, true );
        if(!empty($raw_data_obj['data'])){
            $raw_data_obj = $raw_data_obj['data'];
        }
        $raw_data_obj_str = "<pre>" . print_r($raw_data_obj,true) . "</pre>";
        fmLog("Received ClickFunnels raw data object: $raw_data_obj_str");
        if( is_array( $raw_data_obj ) && !empty( $raw_data_obj['time'] ) ) {
            fmLog("Data object valid 1");
            http_response_code(200);
            exit;
        }
        if( is_array($raw_data_obj) && !empty( $raw_data_obj['id'] ) && !empty( $raw_data_obj['type'] ) && $raw_data_obj['type'] == "purchases" && isset($raw_data_obj['attributes']['status']) && $raw_data_obj['attributes']['status'] == 'paid' ) {
            fmLog("Data object valid 2: Paid");
            require_once( FASTCF_DIR . '/lib/fast-cf-ipn-class.php' );
            $fcfipn = new fast_CF_IPN_Class($raw_data_obj);
        }else if(is_array($raw_data_obj) && !empty( $raw_data_obj['id'] ) && !empty( $raw_data_obj['type'] ) && $raw_data_obj['type'] == "purchases" && isset($raw_data_obj['attributes']['status']) && $raw_data_obj['attributes']['status'] == 'refunded'){
          fmLog("Data object valid 2: Refunded");
          global $wpdb;
          $txn_id = sanitize_text_field( $raw_data_obj['id'] );
          $refunded_txns = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpbn_transactions WHERE txn_id = %s", $txn_id ) );
        	foreach ($refunded_txns as $refunded_txn) {
              $wpdb->update($wpdb->prefix."wpbn_transactions",array("txn_status"=>1, "affcomm" => 0),array("id"=>$refunded_txn->id),array("%d","%d"),array("%d"));
              do_action( 'FM_after_transaction_refunded', $txn_id );
              $ruserid = $wpdb->get_var( $wpdb->prepare( "SELECT userid FROM {$wpdb->prefix}wpbn_transactions WHERE id = %d", $refunded_txn->id ) );
              $rprodid = $wpdb->get_var( $wpdb->prepare( "SELECT prodid FROM {$wpdb->prefix}wpbn_transactions WHERE id = %d", $refunded_txn->id ) );
              $wpdb->update($wpdb->prefix."wpbn_users",array("expires"=>1, "affcomm"=>0, "status" =>0),array("userid"=>$ruserid, "prodid"=> $rprodid),array("%d","%d","%d"),array("%d", "%d"));
          }
        }else{
            fmLog("Data object not valid 2");
        }

    }

}
