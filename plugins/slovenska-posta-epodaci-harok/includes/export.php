<?php

/*
*	Register bulk actions
*/
function tsseph_register_bulk_action($bulk_actions) {

    $bulk_actions['export_posta'] = __( 'Podací hárok export (XML)', 'spirit-eph');
    $bulk_actions['export_posta_api'] = __( 'Podací hárok odoslať (API)', 'spirit-eph');
    return $bulk_actions;
}

/*
*	Actions to apply when user selects XML or API bulk action
*/
function tsseph_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
	
	//XML
	if ( $doaction === 'export_posta' ) {
	
		$eph_sets = tsseph_get_EPH($post_ids);

		//Delete existing zip 
		if(file_exists(plugin_dir_path( dirname(__FILE__) ) . 'export/EPH.zip')) {
			unlink(plugin_dir_path( dirname(__FILE__) ) . 'export/EPH.zip'); 
		}

		//Put all xml exports into one zip
		$zip = new ZipArchive();
		$zip->open(plugin_dir_path( dirname(__FILE__) ) . 'export/EPH.zip', ZIPARCHIVE::CREATE);

		$unlink_arr = array();

		foreach ($eph_sets as $key => $EPH) {

			$filename = plugin_dir_path( dirname(__FILE__) ) . 'export/EPH-' . $key . '.xml'; 
			$myfile = fopen($filename, "w") or die("Unable to open file!");
			fwrite($myfile, $EPH->asXML());
			fclose($myfile);  

			$zip->addFile($filename,'EPH-' . $key . '.xml');
			$unlink_arr[] = $filename;
		}

		$zip->close(); 

		//Delete the former xmls
		foreach($unlink_arr as $filename) { 
			unlink($filename); 
		}

		$redirect_to = remove_query_arg('exported_orders_api', $redirect_to);
		$redirect_to = add_query_arg( 'exported_orders', count($post_ids) , $redirect_to );
	}

	//API
  	else if ( $doaction === 'export_posta_api' ) {

		$error_msg = "";
		$orders_sent = array();
		$orders_not_sent = array();

		$eph_sets = tsseph_get_EPH_parcels($post_ids);

		foreach ($eph_sets as $eph_set) {

			//Create sheet
			$response = tsseph_posta_api_call('sheets','PUT',$eph_set['EPH']);

			//Validation error
			if ($response['status'] != 'ok') {

				foreach($eph_set['OrderNumbers'] as $order_number) {
					$orders_not_sent[] =  $order_number;
				}

				foreach ($response['validation_errors'] as $error) {
					$error_msg .= $error['attribute'] . " (" . $error['reason'] . "), ";
				}
				$error_msg = rtrim($error_msg,", ");

				continue;
			}

			$sheet_id = $response['sheet']['id'];
		
			//Create parcels
			foreach ($eph_set['EPH']['sheet']['parcels'] as $order_id => $parcel) {
				$response = tsseph_posta_api_call("sheets/{$sheet_id}/parcels",'PUT',array('parcel' => $parcel));

				//If OK
				if ($response['status'] == 'ok') {

					$order = wc_get_order( $order_id );

					$order->update_meta_data( 'tsseph_tracking_no', $response['parcel']['parcel_number']);
					$order->update_meta_data( 'tsseph_parcel_id', $response['parcel']['id']);
					$order->update_meta_data( 'tsseph_sheet_id',$sheet_id);

					$orders_sent[] = $eph_set['OrderNumbers'][$order_id];

					//Fetch address labels
					$responseLabels = tsseph_posta_api_call("sheets/" . $sheet_id  . "/parcels/" . $response['parcel']['id'] . "/labels",'POST',array("format" => "pdf"));
					if ($responseLabels['status'] == 'ok') {
						$order->update_meta_data( 'tsseph_labels', $responseLabels['labels']['url'] );
					}

					$order->save();
				}
				//Validation error
				else {
					$orders_not_sent[] =  $eph_set['OrderNumbers'][$order_id];

					foreach ($response['validation_errors'] as $error) {
						$error_msg .= $error['attribute'] . " (" . $error['reason'] . "), ";
					}
					$error_msg = rtrim($error_msg,", ");
				}

			}

			//Register sheet
			$response = tsseph_posta_api_call("sheets/{$sheet_id}/register",'POST',array());
		}

		$redirect_to = remove_query_arg('exported_orders', $redirect_to);
    	$redirect_to = add_query_arg(
			array(
				'exported_orders_api' => count($post_ids),
				'eph_status' => $error_msg,
				'orders_sent' => implode(',',$orders_sent),
				'orders_not_sent' => implode(',',$orders_not_sent)
			), 
			$redirect_to 
		);
  }

  	return $redirect_to;
}

/*
* 	Show admin notice after API / XML bulk action
*/
function tsseph_bulk_action_admin_notice() {
	if(isset($_REQUEST['exported_orders'])) { $exported_orders = absint($_REQUEST['exported_orders']);} else $exported_orders = 0;
	if(isset($_REQUEST['exported_orders_api'])) { $exported_orders_api = absint($_REQUEST['exported_orders_api']);} else $exported_orders_api = 0;
	if(isset($_REQUEST['eph_status'])) { $eph_status = sanitize_text_field($_REQUEST['eph_status']);} else $eph_status = '';
	if(isset($_REQUEST['orders_sent'])) { $orders_sent = sanitize_text_field($_REQUEST['orders_sent']);} else $orders_sent = '';
	if(isset($_REQUEST['orders_not_sent'])) { $orders_not_sent = sanitize_text_field($_REQUEST['orders_not_sent']);} else $orders_not_sent = '';


	if ( $exported_orders != 0 ) {
		echo '<div id="message" class="updated fade"><a href="' .  plugins_url( '..\export\EPH.zip', __FILE__ ) . '" download>'. __('EPH.zip súbor vytvorený.','spirit-eph') . '</a> ' . __('Počet objednávok','spirit-eph') .': ' . $exported_orders . '</div>';
	}
	else if ( $exported_orders_api != 0) {

		if ($orders_sent != "") {
			echo '<div id="message" class="updated fade">' . sprintf( __('Počet odoslaných objednávok: %d', 'spirit-eph'),count(explode(',',$orders_sent))) . ' (' . $orders_sent . ')</div>';
		}
	
		if ($orders_not_sent != "") {
			echo '<div id="message" class="error fade">' . __('Vyskytol sa problém pre objednávky: ', 'spirit-eph') . $orders_not_sent . ' -> ' . $eph_status . '</div>';
		}
	}    
}
  