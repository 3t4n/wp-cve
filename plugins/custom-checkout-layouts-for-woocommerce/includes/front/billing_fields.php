<?php
$cclw_checkout_fields = get_option( 'cclw_checkout_fields');
 $billing_firstname = $cclw_checkout_fields['cclw_billing_first_name'][0];
$billing_lastname = $cclw_checkout_fields['cclw_billing_last_name'][0];
$billing_company = $cclw_checkout_fields['cclw_billing_company'][0];
$billing_country = $cclw_checkout_fields['cclw_billing_country'][0];
$billing_adress1 = $cclw_checkout_fields['cclw_address_1'][0];
$billing_adress2 = $cclw_checkout_fields['cclw_address_2'][0];
$billing_city = $cclw_checkout_fields['cclw_billing_city'][0];
$billing_state = $cclw_checkout_fields['cclw_billing_state'][0];
$billing_postcode = $cclw_checkout_fields['cclw_billing_postcode'][0];
$billing_phone = $cclw_checkout_fields['cclw_billing_phone'][0];
$billing_email = $cclw_checkout_fields['cclw_billing_email'][0];

$billing_fields  = array($billing_firstname,$billing_lastname,$billing_company,$billing_country,$billing_adress1,$billing_adress2,$billing_city,$billing_state,$billing_postcode,$billing_phone,$billing_email);

 foreach($billing_fields as $reset_billing_field)
				 {
					if(isset($reset_billing_field['show_hide']) && $reset_billing_field['show_hide'] == 'hide' && isset($reset_billing_field['required']) && $reset_billing_field['required'] == 'false')
					  {
						 unset($fields[$reset_billing_field['slug']]); 
						 $fields[$reset_billing_field['slug']]['required'] = 0; 
					  }/*Checks if this field to be shown on front end*/
					  else
					  {						
						
						/**Label section*/
						  if(isset($reset_billing_field['label']) && $reset_billing_field['label'] != '' )
						  {
							  $fields[$reset_billing_field['slug']]['label'] = $reset_billing_field['label'];
						  }
						  else
						  {
							unset($fields[$reset_billing_field['slug']]['label']);  
						  } 
						  /*placeholder */
							  if(isset($reset_billing_field['placeholder']) && $reset_billing_field['placeholder'] != '')
							  {
								  $fields[$reset_billing_field['slug']]['placeholder'] = $reset_billing_field['placeholder'];
							  }
						  /*required section */
							  if(isset($reset_billing_field['required']) && $reset_billing_field['required'] == 'true' )
							  {
								  $fields[$reset_billing_field['slug']]['required'] = 1;  
								  
							  }
							   else
							  {
								 $fields[$reset_billing_field['slug']]['required'] = 0;   
								 
							  } 
							  /*Set classes for fields*/
							   if(isset($reset_billing_field['width']) && $reset_billing_field['width'] != '' )
						      {
							   $fields[$reset_billing_field['slug']]['class'] = array($reset_billing_field['width']);
						      }
					  }/*Closes show Hide condition else*/  
			      }  
				   
