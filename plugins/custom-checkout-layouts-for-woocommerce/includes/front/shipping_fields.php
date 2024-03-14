<?php
$cclw_checkout_fields = get_option( 'cclw_checkout_fields');

				  
/*Shipping fields*/

$shipping_firstname = $cclw_checkout_fields['cclw_shipping_first_name'][0];
$shipping_lastname = $cclw_checkout_fields['cclw_shipping_last_name'][0];
$shipping_company = $cclw_checkout_fields['cclw_shipping_company'][0];
$shipping_country = $cclw_checkout_fields['cclw_shipping_country'][0];
$shipping_adress1 = $cclw_checkout_fields['cclw_ship_address_1'][0];
$shipping_adress2 = $cclw_checkout_fields['cclw_ship_address_2'][0];
$shipping_city = $cclw_checkout_fields['cclw_shipping_city'][0];
$shipping_state = $cclw_checkout_fields['cclw_shipping_state'][0];
$shipping_postcode = $cclw_checkout_fields['cclw_shipping_postcode'][0];


$shipping_fields  = array($shipping_firstname,$shipping_lastname,$shipping_company,$shipping_country,$shipping_adress1,$shipping_adress2,$shipping_city,$shipping_state,$shipping_postcode);

  foreach($shipping_fields as $reset_shipping_field)
				  {
					 if(isset($reset_shipping_field['show_hide']) && $reset_shipping_field['show_hide'] == 'hide')
					  {
						 unset($fields[$reset_shipping_field['slug']]); 
					  }/*Checks if this field to be shown on front end*/
					  else
					  {						
						
						/**Label section*/
						  if(isset($reset_shipping_field['label']) && $reset_shipping_field['label'] != '' )
						  {
							  $fields[$reset_shipping_field['slug']]['label'] = $reset_shipping_field['label'];
						  }
						  else
						  {
							unset($fields[$reset_shipping_field['slug']]['label']);  
						  } 
						  /*placeholder */
							  if(isset($reset_shipping_field['placeholder']) && $reset_shipping_field['placeholder'] != '')
							  {
								  $fields[$reset_shipping_field['slug']]['placeholder'] = $reset_shipping_field['placeholder'];
							  }
						  /*required section */
							  if(isset($reset_shipping_field['required']) && $reset_shipping_field['required'] == 'true' )
							  {
								  $fields[$reset_shipping_field['slug']]['required'] = 1;  
								  
							  }
							   else
							  {
								 $fields[$reset_shipping_field['slug']]['required'] = 0;   
								 
							  } 
					  }/*Closes show Hide condition else*/  
			      } 
		  
 

?>