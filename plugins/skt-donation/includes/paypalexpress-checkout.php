<div class="paypal_hide_show skt_donation_box skt_donation_form">
<?php
	global $wpdb;
	$wp_skt_choose_currency_paypal = $wpdb->prefix . "skt_choose_currency_paypal";
    $select_choose_currency_paypal = $wpdb->get_row("SELECT * FROM $wp_skt_choose_currency_paypal WHERE id='1'");
    $get_choose_stripe_count = $wpdb->num_rows;
    if ($get_choose_stripe_count <= 0) {
        $for_paypal_payment ="USD";
        $for_paypal_sign ="&#36;";
    }else{
        $type_currency_id_paypal = $select_choose_currency_paypal->type_currency_id;
        $currency_symbol_id_paypal = $select_choose_currency_paypal->currency_symbol_id;
        $skt_country_type_currency = $wpdb->prefix . "skt_country_type_currency";
        $select_type_currency_stripe = $wpdb->get_row("SELECT * FROM $skt_country_type_currency WHERE id='$type_currency_id_paypal'");
        $for_paypal_payment =  $select_type_currency_stripe->currency_stripe;
        $for_paypal_sign =  $select_type_currency_stripe->currency_sign;
    }
	if ( esc_attr(get_option('skt_donation_paypalexp_mode_zero_one') == 'true' )){
	    $clientId = esc_attr( get_option('skt_donation_paypalexp_test_api') );
	    $secret = esc_attr( get_option('skt_donation_paypalexp_secretkey') );
	    $sandbox_live = "https://api-m.sandbox.paypal.com";
	}else{
	   $clientId = esc_attr( get_option('skt_donation_paypalexp_live_api') );
	   $secret = esc_attr( get_option('skt_donation_paypalexpIlive_secretkey') );
	   $sandbox_live = "https://api-m.paypal.com";
	}
	$recurringtime = esc_attr( get_option('skt_donation_priceper') );
	$productname = "Donations";
?>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $clientId;?>&vault=true&intent=subscription" data-namespace="paypal_sdk"></script>
<?php
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $sandbox_live."/v1/oauth2/token");
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
	$result = curl_exec($ch);
	if(empty($result))die("Error: No response.");
	else{
	    $json = json_decode($result);
	  	$tokencode = $json->access_token;
	}
curl_close($ch);
$url = $sandbox_live."/v1/catalogs/products";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Content-Type: application/json",
   "Authorization: Bearer ".$tokencode,
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = <<<DATA
{
  "name": "$productname",
  "description": "Donations",
  "type": "SERVICE",
  "category": "SOFTWARE",
  "image_url": "https://example.com/streaming.jpg",
  "home_url": "https://example.com/home"
}
DATA;
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($curl);
if(empty($resp))die("Error: No response.");
else{
    $respjson = json_decode($resp);
  	$product_id = $respjson->id;
}
curl_close($curl);
$url = $sandbox_live."/v1/billing/plans";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Accept: application/json",
   "Authorization: Bearer ".$tokencode,
   "Prefer: return=representation",
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = <<<DATA
{
        "product_id": "$product_id",
        "name": "$productname",
        "description": " ",
        "billing_cycles": [
          {
            "frequency": {
                "interval_unit": "$recurringtime",
                "interval_count": 1
            },
            "tenure_type": "TRIAL",
            "sequence": 1,
            "total_cycles": 1
          },
            {
              "frequency": {
                "interval_unit": "$recurringtime",
                "interval_count": 1
              },
              "tenure_type": "REGULAR",
              "sequence": 2,
              "total_cycles": 12,
              "pricing_scheme": {
                "fixed_price": {
                  "value": "$donation_amount",
                  "currency_code": "$for_paypal_payment"
                }
              }
            }
          ],
        "payment_preferences": {
          "service_type": "PREPAID",
          "auto_bill_outstanding": true,
          "setup_fee": {
            "value": "$donation_amount",
            "currency_code": "$for_paypal_payment"
          },
          "setup_fee_failure_action": "CONTINUE",
          "payment_failure_threshold": 3
        },
        "quantity_supported": true,
        "taxes": {
          "percentage": "1",
          "inclusive": true
        }
    }
DATA;

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp_subscription = curl_exec($curl);
if(empty($resp_subscription))die("Error: No response.");
else{
    $respsubscriptionjson = json_decode($resp_subscription);
  	$subscription_id = $respsubscriptionjson->id;
}

curl_close($curl);
?>
    <div id="sktchangeeventone">
        <form name="checkoutexpress" id="sktpaypalbuttoncontainer" class="slt_form_horizontal" method="post">
            <?php if(esc_attr(get_option('skt_donation_first_name_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_first_name_lable') ); ?></label>
            <input type="text" name="first_name"  id="sktfname" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_first_name') ); ?>" value="" required></br></br>
            <?php } ?>
            <?php if(esc_attr(get_option('skt_donation_last_name_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_last_name_lable') ); ?></label>
            <input type="text" name="last_name" id="sktlname" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_last_name') ); ?>" value="" required></br></br>
            <?php }?>
            <?php if(esc_attr(get_option('skt_donation_email_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_email_lable') ); ?></label>
            <input type="text" name="email" id="sktemail" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_email') ); ?>" value="" required></br></br>
            <?php } ?>
            <?php if(esc_attr(get_option('skt_donation_phone_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_phone_name_lable') ); ?></label>
            <input type="text" name="phone" id="sktphone" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_phone_name') ); ?>" value="" required></br></br>
            <?php } ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_amount_lable') ); ?></label>
            <input type="text" name="donation_amount" id="sktdonationamount" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_amount') ); ?>" value="<?php echo esc_attr($donation_amount);?>" required="required" readonly></br></br>
            <input type="hidden" name="payment_in_currency" value="<?php echo esc_attr($for_paypal_payment);?>">
            <input type="text" name="currency_sign" value="<?php echo esc_attr($for_paypal_sign);?>" readonly>
            <?php wp_nonce_field( 'paypalexpress_subscriptionnormal', 'add_paypalexpress_nonce' ); ?>
            <div id="paypalcheckout-button-container"></div>
        </form> 
    </div>
    <script>
      paypal_sdk.Buttons({
        createSubscription: function(data, actions) {
        return actions.subscription.create({
            'plan_id': '<?php echo $subscription_id;?>'
          });
      },
      onApprove: function(data, actions) {
        var subscriptionID = data.subscriptionID;
          jQuery('#sktpaypalbuttoncontainer').append('<div><input type="text" id="paypalsubscriptionid" name="paypalexpsubscription_id" value=""/></div>');
          jQuery("#paypalsubscriptionid").val(subscriptionID);
          jQuery("form[name=checkoutexpress]").submit();
      }
    }).render('#paypalcheckout-button-container');
  </script>
</div>