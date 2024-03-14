<?php
/*
* Decimal Product Quantity for WooCommerce
* JS Product Object.
* ajax_quantity.php
*/ 
	
	$Mode 		= isset($_REQUEST['mode']) ? sanitize_text_field($_REQUEST['mode']) : null;
	$Object_ID	= isset($_REQUEST['id']) ? sanitize_text_field($_REQUEST['id']) : 0;		

	$Product_QNT_Options = array();
	
	$Result = false; 
		
	// get_product_quantity
	if ($Mode == 'get_product_quantity') {
		if ($Object_ID) {
			$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Object_ID);
		}
		
		$Result = true;	
	}
	
	$Obj_Request = new stdClass();
	$Obj_Request->status 	= 'OK';
	$Obj_Request->answer 	= $Result;
	$Obj_Request->qnt_data 	= $WooDecimalProduct_QuantityData;

	wp_send_json($Obj_Request);    

	die; // Complete.