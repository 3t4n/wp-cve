<html>
<head> 
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

 
</head>
<body>


	<div class="container" style="margin-top:2%">
			<a href="<?php echo admin_url( '?page=smsa-shipping-official/order-list.php' );?>" class="smsa_action">Back to Orders</a>
   <p><center></center></p>
  <form action="#" style="width:100%" method="post">
  	<button type="submit" class="btn btn-primary mt-3 sub-btn" style="margin-bottom:3%">Create All Shipments</button>
<?php

	
					
if($_POST)
{
					$store_address = get_option( 'woocommerce_store_address' );
					$store_address_2   = get_option( 'woocommerce_store_address_2' );
					$store_city        = get_option( 'woocommerce_store_city' );
					$store_postcode    = get_option( 'woocommerce_store_postcode' );
					$store_raw_country = get_option( 'woocommerce_default_country' );
					$site_title = get_bloginfo( 'name' );


					//echo $store_address.'-'.$store_address_2.'-'.$store_city.'-'.$store_postcode.'-'.$store_raw_country.'-'.$site_title;
					if(strpos($store_raw_country,':') !== false )
					{
						$split_country=explode(":", $store_raw_country);
						$store_country = $split_country[0];
			            $store_state   = $split_country[1];
					}
					else
					{
							$store_country = $store_raw_country;
			            	$store_state   = "";
					}
					$sett = get_option('woocommerce_smsa-express-integration_settings');

					$shipping_data = array();
					$shipping_data['shipperAddressDetails']['addressType'] = "SHIPPER";
				    $shipping_data['shipperAddressDetails']['name'] = $site_title;
				    $shipping_data['shipperAddressDetails']['addressLine1'] = $store_address;
				    $shipping_data['shipperAddressDetails']['addressLine2'] = $store_address_2;
				    $shipping_data['shipperAddressDetails']['district'] = $store_state;
				    $shipping_data['shipperAddressDetails']['addressCity'] = $store_city;
				    $shipping_data['shipperAddressDetails']['addressCountryCode'] = $store_country;
				    $shipping_data['shipperAddressDetails']['postalCode'] = $store_postcode;
				    $shipping_data['shipperAddressDetails']['phoneNumber'] = $sett['store_phone'];

                    $body = array(
                    'accountNumber' => $sett['smsa_account_no'],
                    'username' => $sett['smsa_username'],
                    'password' => $sett['smsa_password'],
                    );

                    $args = array(
                    'body' => json_encode($body) ,
                    'timeout' => '5',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8'
                    ) ,
                    'cookies' => array() ,
                    );
                    $re = wp_remote_post('https://smsaopenapis.azurewebsites.net/api/Token', $args);

                    $resp = json_decode($re['body']);
                if (isset($resp->token))
                {
                	 

					foreach($_POST as $post)
					{

						if($post['parcels']>0 && $post['parcels']<4 && $post['weight']>0)
						{
							

							$shipping_data['reference'] = $post['reference'];
							$shipping_data['consigneeAddressDetails']['addressType'] = "CONSIGNEE";
							$shipping_data['consigneeAddressDetails']['name'] = ucwords($post['c_name']);
							$shipping_data['consigneeAddressDetails']['addressLine1'] = $post['addressLine1'];
							$shipping_data['consigneeAddressDetails']['addressLine2'] = $post['addressLine2'];
							$shipping_data['consigneeAddressDetails']['district'] = ucwords($post['district']);
							$shipping_data['consigneeAddressDetails']['addressCity'] = ucwords($post['addressCity']);
							$shipping_data['consigneeAddressDetails']['addressCountryCode'] = $post['addressCountryCode'];
							$shipping_data['consigneeAddressDetails']['postalCode'] = $post['postalCode'];
							$shipping_data['consigneeAddressDetails']['phoneNumber'] = $post['c_phone'];
							$shipping_data['codAmount'] = (float)$post['cod'];
							$shipping_data['declaredValue'] = (float)$post['declaredValue'];
							$shipping_data['shipmentCurrency'] = $post['shipmentCurrency'];
							$shipping_data['shipmentWeight'] = (float)$post['weight'];
							$shipping_data['weightUnit'] = "KG";
							$shipping_data['shipmentContents'] = $post['shipmentContents'];
							$shipping_data['shipmentParcelsCount'] = (float)$post['parcels'];

							

							$args = array(
							'body' => json_encode($shipping_data) ,
							'timeout' => '5',
							'redirection' => '5',
							'httpversion' => '1.0',
							'blocking' => true,
							'headers' => array(
							'Content-Type' => 'application/json; charset=utf-8',
							'Authorization' => 'Bearer ' . $resp->token

							) ,
							'cookies' => array() ,
							);
							$re = wp_remote_post('https://smsaopenapis.azurewebsites.net/api/Shipment/B2CNewShipment', $args);

							$resp1 = json_decode($re['body']);
							

							if($re['response']['code']==400)
							{
							echo "<p style='color:red;text-align:center;'>Error:-" .$re['body']."</p>";      
							}

							if (isset($resp1->sawb))
							{

							 update_post_meta($post['order_id'], 'smsa_awb_no', $resp1->sawb);
							 echo "<p style='color:green;text-align:center;'>AWB number generated successfully of order " .$post['reference']. "</p>";
							}
							if (isset($resp1->errors))
							{

								foreach ($resp1->errors as $key => $value)
								{
									echo "<p style='color:red;text-align:center;'>Error(".$post['reference'].') ' . $key . ' ----- ' . $value['0'] . '<br></p>';
								}
							}

						}
						else
						{
							if($post['weight']>0)
							{
								echo "<p style='color:red;text-align:center;'>Please make sure parcel count value should be between 1-3 of order ".$post['reference']."</p>"; 
							}
							else
							{
								echo "<p style='color:red;text-align:center;'>Please make sure Weight should be more than Zero of order ".$post['reference']."</p>"; 
							}
						}
					}
                }
}
if(!isset($_GET['order_ids']))
{
    wp_redirect( admin_url( 'edit.php?post_type=shop_order' ) );
       
}
else
{

		$i=0;
		foreach($_GET['order_ids'] as $order_id)
		{
			$awb_nn = get_post_meta($order_id, 'smsa_awb_no',true);
           if($awb_nn=="")
           {
			$order = wc_get_order($order_id);
			$order_data = $order->get_data();
			// Get the order key
			$order_key = $order->get_order_key();

			// Get the order number
			$order_key = $order->get_order_number();
			$pay_method=$order->get_payment_method();

			

			$dv= $order_data['total']-$order_data['total_tax']-$order_data['shipping_total'];

			foreach( $order->get_items( 'shipping' ) as $item_id => $item ){

			$item_data = $item->get_data();


			$shipping_data_method_id    = $item_data['method_id'];

			}

			

			if($pay_method=='cod')
			{
				$amount=$order->get_total();
			}
			else
			{
				$amount=0;
			}


// echo "<pre>";
// print_r($order->get_data());
// print_r($order->get_items());
// echo "</pre>";
			$weight = 0;
			$note = array();
			foreach ($order->get_items() as $item)
			{
				if ($item['product_id'] > 0)
				{
				$_product = $item->get_product();
				if (!$_product->is_virtual())
				{
				if($_product->get_weight()!="")
				{
				$weight += $_product->get_weight() * $item['qty'];
				}
				}
				}
				$note[] = $item->get_name();

			}
			 $weight_unit = get_option('woocommerce_weight_unit');
			
			if($weight_unit=="lbs")
			{
				$weight=$weight*0.4535;
			}
			elseif($weight_unit=="g")
			{
				$weight=$weight/1000;
			}
			elseif($weight_unit=="oz")
			{
				$weight=$weight*0.0283495;
			}
			else
			{
				$Weight=$weight;
			}
			$final_note = implode(",", $note);



			$phone="";
			if ($order->get_billing_phone() != "")
			{
				$phone = $order->get_billing_phone();
			}
			

				if ($order->get_billing_address_1() != $order->get_shipping_address_1())
				{
					$ship_no = get_post_meta($order_id, '_shipping_phone', true);
					if ($ship_no != "")
					{
						$phone = $ship_no;
					}
				
				}
				?>
			
    <h3>CONSIGNEE DETAILS FOR ORDER <?php echo $order_id;?></h3>
  <input type="hidden" name="<?php echo $i;?>[action]" value="create_shipment">
    <input type="hidden" name="<?php echo $i;?>[order_id]" value="<?php echo $order_id; ?>">
 
    <input type="hidden" name="<?php echo $i;?>[reference]" value="Ref_<?php echo $order_id; ?>">
  
   
    <input type="hidden" name="<?php echo $i;?>[shipmentCurrency]" value="<?php echo $order_data['currency']; ?>">
  
    <div class="row">
      <div class="col">
        <label>Name</label>
        <input type="text" class="form-control"  name="<?php echo $i;?>[c_name]" value="<?php echo $order_data['shipping']['first_name'].' '.$order_data['shipping']['last_name'];?>" required>
      </div>
      <div class="col">
        <label>Phone</label>
        <input type="text" class="form-control" name="<?php echo $i;?>[c_phone]" value="<?php echo $phone;?>" required>
      </div>
    </div>
     <div class="row">
      <div class="col">
        <label>Address Line1</label>
        <input type="text" class="form-control"  name="<?php echo $i;?>[addressLine1]" value="<?php echo $order_data['shipping']['address_1'];?>">
      </div>
      <div class="col">
        <label>Address Line2</label>
        <input type="text" class="form-control" name="<?php echo $i;?>[addressLine2]" value="<?php echo $order_data['shipping']['address_2'];?>">
      </div>
    </div>
    <div class="row">
      <div class="col">
        <label>City</label>
        <input type="text" class="form-control"  name="<?php echo $i;?>[addressCity]" value="<?php echo $order_data['shipping']['city'];?>" required>
      </div>
      <div class="col">
        <label>District</label>
        <input type="text" class="form-control" name="<?php echo $i;?>[district]" value="<?php echo $order_data['shipping']['state'];?>">
      </div></div>
      <div class="row">
      <div class="col">
        <label>Country Code</label>
        <input type="text" class="form-control"  name="<?php echo $i;?>[addressCountryCode]" value="<?php echo $order_data['shipping']['country'];?>" required>
      </div>
      <div class="col">
        <label>Postal Code</label>
        <input type="text" class="form-control" name="<?php echo $i;?>[postalCode]" value="<?php echo $order_data['shipping']['postcode'];?>">
      </div>
    </div> 
     <div class="row">
      <div class="col">
        <label class="pb-text">Customs Declared Value (<?php echo $order_data['currency']; ?>)</label>
        <input type="text" class="form-control"  name="<?php echo $i;?>[declaredValue]" value="<?php echo $dv;?>" required>
      </div>
      <div class="col">
        <label class="pb-text">Total Cash on Delivery (<?php echo $order_data['currency']; ?>)</label>
        <input type="text" class="form-control" name="<?php echo $i;?>[cod]" value="<?php echo $amount;?>" required>
      </div>
    </div> 
     <div class="row">
      <div class="col">
        <label>Number of parcel</label>
        <input type="number" class="form-control"  name="<?php echo $i;?>[parcels]" value="1" min="1" max="3">
      </div>
      <div class="col">
        <label>Weight(KG)</label>
        <input type="text" class="form-control" name="<?php echo $i;?>[weight]" value="<?php echo $weight;?>">
      </div>
    </div>
     <div class="row">
      <div class="col">
        <label>Products Description</label>
        <input type="text" class="form-control"  name="<?php echo $i;?>[shipmentContents]" value="<?php echo $final_note; ?>" required>
      </div>
      
    </div>

    <hr/>

  

<?php 
	$i++;
}
	}
		if($i>0)
		{
			echo '<button type="submit" class="btn btn-primary mt-3 sub-btn">Create All Shipments</button>';
		}		
		else
		{
			?>
			<script>
				$('.sub-btn').hide();
			</script>
		<?php 
			if(!$_POST)
			{

				echo "<p style='color:red;text-align:center;'>Shipment is already created for orders</p>";
			}
		}

}
?>
</form>
</div>
</body>
</html>
<style>
	.pb-text
	{
		color: purple !important;
font-weight: bold !important;
	}
	label {
	font-weight: bold;
	margin-top: .5rem;
}
.form-control {
    color: black !important;
}
</style>