<?php

function bosta_create_edit_pickup_form()
{
	$APIKey = bosta_get_api_key();
	if (empty($APIKey)) {
		echo "Please, add your API key";
	}
	$url = BOSTA_ENV_URL . '/pickup-locations ';
	$result = wp_remote_get($url, array(
		'timeout' => 30,
		'method' => 'GET',
		'headers' => array(
			'Content-Type' => 'application/json',
			'authorization' => $APIKey,
			'X-Requested-By' => 'WooCommerce',
			'X-Plugin-Version' => PLUGIN_VERSION
		),
	));

	if (is_wp_error($result) || $result['response']['code'] != 200) {
		$result = json_decode($result['body']);
		$error_message = $result->message;
		echo "Something went wrong: " .esc_html($error_message);
		return;
	} else {
		$result = json_decode($result['body'])->data;

	}
	if ( !empty($_GET['pickupId']) ) {
        $pickupId = sanitize_text_field($_GET['pickupId']);
		$url = BOSTA_ENV_URL . '/pickups/' . esc_html($pickupId);
		$pickupEditData = wp_remote_get($url, array(
			'timeout' => 30,
			'method' => 'GET',
			'headers' => array(
				'Content-Type' => 'application/json',
				'authorization' => $APIKey,
				'X-Requested-By' => 'WooCommerce',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
		));
		if (is_wp_error($pickupEditData)) {
			$error_message = $pickupEditData->get_error_message();
			echo "Something went wrong:" .esc_html($error_message);
		} else {
			$pickupEditData = json_decode($pickupEditData['body'])->message;
		}
	}
	echo "
              <style>
              table {
              border-collapse: collapse;
              width: 95%;
              }
              tr{
                  display:flex;
              }
              td{
               display: flex;
               flex-direction: column;
              }
              th, td {
              padding: 8px;
              }

              label{
                  font-weight:500;
                  font-size:15px;
                  padding-bottom:10px;
              }

              form {
                  margin-bottom: 30px;
              }

              .data{
                  height:32px;
                  width: 25vw;
              }

              .error{
                  text-align:center;
                  color:red;
              }

              .updated{
                  text-align:center;
                  color:green;
              }

              </style>";
	if (isset($_GET['pickupId'])) {

		echo "<p class='create-pickup-title'>Edit Pickup";

	} else {
		echo "<p class='create-pickup-title'>Create Pickup";
	}
	echo "
            </p>
              <p class='create-pickup-subtitle'>  Setup the pickup info</p>
              <form method='post' action='#'>
              <table>
              <thead>
              </thead>
                  <tbody>
                  <tr>
                  <td>
                   <label >Pickup Location</label>
                   <select required class='data' name='businessLocationId' > ";
	for ($i = 0; $i < count($result); $i++) {
		$pickupLocation = $result[$i]->locationName;
		$id = $result[$i]->_id;
		if ($id == $pickupEditData->businessLocationId) {
			echo '<option value="'. esc_attr($id).'" >'. esc_html($pickupLocation).'</option>';
		} else {
			echo '<option value="'. esc_attr($id).'" >'. esc_html($pickupLocation).'</option>';
		}

	}
	function selectdCheck($value1, $value2): string {
		if ($value1 == $value2) {
			return 'selected="selected"';
		} else {
			return '';
		}

	}
	echo " </select > </td>
               </tr>
                      <tr>
                         <td>
                          <label>Pickup Date</label>";
	if (isset($pickupEditData) && $pickupEditData->scheduledDate) {

		$date = date('Y-m-d', strtotime($pickupEditData->scheduledDate));
		?>
        <input value = "<?php echo (esc_attr($date)); ?>" required class='data' name='scheduledDate' type='date'  />
		<?php
	} else {
		echo " <input required class='data' name='scheduledDate'  type='date' />" ;
	}

	echo "    </td>
                      </tr>
                      <tr>
                         <td>
                              <label >Pickup Time</label>
                              "
	?>
    <select required class='data' name='scheduledTimeSlot' >
        <option  <?php if(isset($pickupEditData)){esc_attr(selectdCheck($pickupEditData->scheduledTimeSlot, '10:00 to 13:00'));}?>   value='10:00 to 13:00'>10:00AM to 01:00PM</option>
        <option <?php if(isset($pickupEditData)){esc_attr(selectdCheck($pickupEditData->scheduledTimeSlot, '13:00 to 16:00'));}?>value='13:00 to 16:00' >01:00PM to 04:00PM</option>
    </select>
	<?php
	echo "   </td>
                </tr>
                  </tbody>
              </table>";
	if (isset($_GET['pickupId'])) {

		echo "<input class='primary-button' type='submit' name='create' value='Edit pickup'/>";

	} else {
		echo " <input class='primary-button' type='submit' name='create' value='Create pickup'/>";
	}
	echo " </form>
              <span class='pickup-location-note'>To create and edit pickup locations, and select the default pickup location <a class='pickup-location-link' href='https://business.bosta.co/settings/pickup-locations' target='_blank'>Click here</a> </span>
              <p class='pickup-location-note'> Is this a recurring pickup? If yes  <a class='pickup-location-link' href='https://business.bosta.co/pickups/create' target='_blank'>Create from here</a></span>
              ";

	if (isset($_POST['create'])) {

		if (empty($_POST['scheduledDate'])) {
			echo "<div class='error'>Pickup Date is Required</div>";
		}

		if (empty($_POST['scheduledTimeSlot'])) {
			echo "<div class='error'>Pickup Time is Required<div>";
		}

		if (!empty($_POST['scheduledDate']) && !empty($_POST['scheduledTimeSlot'])) {
            $scheduledDate = sanitize_text_field($_POST['scheduledDate']);
            $scheduledTimeSlot = sanitize_text_field($_POST['scheduledTimeSlot']);
            $businessLocationId = sanitize_text_field($_POST['businessLocationId']);
			bosta_create_pickup_action('create_pickup', $scheduledDate, $scheduledTimeSlot, $businessLocationId);
		}
	}
}

function bosta_create_pickup_action($action, $scheduledDate, $scheduledTimeSlot, $businessLocationId)
{
	if ($action != 'create_pickup') {
		return;
	}

	$APIKey = get_option('woocommerce_bosta_settings')['APIKey'];
	if (empty($APIKey)) {
		$redirect_url = admin_url('admin.php?') . 'page=wc-settings&tab=shipping&section=bosta';
		wp_redirect($redirect_url);
	}
	$pickupData = new stdClass();
	$pickupData->scheduledDate = $scheduledDate;
	$pickupData->scheduledTimeSlot = $scheduledTimeSlot;
	$pickupData->businessLocationId = $businessLocationId;
	if (isset($_GET['pickupId'])) {
		$pickupId = sanitize_text_field($_GET['pickupId']);
		$result = wp_remote_request(BOSTA_ENV_URL . '/pickups/' . esc_html($pickupId), array(
			'timeout' => 30,
			'method' => 'PUT',
			'headers' => array(
				'Content-Type' => 'application/json',
				'authorization' => $APIKey,
				'X-Requested-By' => 'WooCommerce',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
			'body' => json_encode($pickupData),
		));

		$result = json_decode($result['body']);
		if ($result->success === false) {
			echo "<div class='error'>" . esc_html($result->message) . "<div>";
		} else {
			echo "<div class='updated'>Pickup Request Updated  Successfuly with id: " . esc_html($pickupId) . "<div>";
		}
	} else {

		$result = wp_remote_post(BOSTA_ENV_URL . '/pickups', array(
			'timeout' => 30,
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
				'authorization' => $APIKey,
				'X-Requested-By' => 'WooCommerce',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
			'body' => json_encode($pickupData),
		));

		$result = json_decode($result['body']);
		if ($result->success === false) {
			echo "<div class='error'>" . esc_html($result->message) . "<div>";
		} else {
			echo "<div class='updated'>Pickup Request Created Successfuly with id: " . esc_html($result
					->message->_id) . "<div>";
			$redirect_url = admin_url('admin.php?') . 'page=bosta-woocommerce-view-pickups';
			wp_redirect($redirect_url);
		}
	}

}

function bosta_view_scheduled_pickups()
{
	global $pagenow;
	$APIKey = get_option('woocommerce_bosta_settings')['APIKey'];
	if (empty($APIKey)) {
		$redirect_url = admin_url('admin.php?') . 'page=wc-settings&tab=shipping&section=bosta';
		wp_redirect($redirect_url);
	}

	if ('admin.php' === $pagenow && $_GET['page'] = 'bosta-woocommerce-view-pickups' && isset($_GET['pickupId'])) {

		echo "<p class='create-pickup-title'>Pickup Info
            </p>
              <p class='create-pickup-subtitle'>View all data about pickup

              </p>";
		$pickupId = sanitize_text_field($_GET['pickupId']);
		$url = BOSTA_ENV_URL . '/pickups/' . esc_html($pickupId);
		$result = wp_remote_post($url, array(
			'timeout' => 30,
			'method' => 'GET',
			'headers' => array(
				'Content-Type' => 'application/json',
				'authorization' => $APIKey,
				'X-Requested-By' => 'WooCommerce',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
			'body' => json_encode($pickupData),
		));

		if (is_wp_error($result)) {
			$error_message = $result->get_error_message();
			echo "Something went wrong: " .esc_html($error_message);
		} else {
			$pickupInfo = json_decode($result['body'])->message;
			$pickupLocation = $pickupInfo->business->address->firstLine;
			$pickupLocationCity = $pickupInfo->business->address->city->name;
			$pickupLocationZone = $pickupInfo->business->address->zone->name;
			$pickupLocationDistrict = $pickupInfo->business->address->district->name;
			$noOfPackgaes = $pickupInfo->noOfPackages ?: "0";
			$contactPerson = $pickupInfo->contactPerson;
			$scheduledDate = date('d/m/Y', strtotime($pickupInfo->scheduledDate));
			$deliveries = $pickupInfo->deliveryTrackingNumbers ? count($pickupInfo->deliveryTrackingNumbers) : "0";
			echo "<table class='order-details-table pickup-info-table'>
          <tr>
             <th>Pickup Id </th>
             <th>Pickup location</th>
             <th>Pickup date</th>
             <th>Picked PCKGs</th>
             <th>Contact person</th>
          </tr>
          <tr>
             <td>". esc_html($pickupInfo->puid)."</td>
             <td> ". esc_html($pickupLocation). " <br/>  ". esc_html($pickupLocationDistrict)." -  ".esc_html($pickupLocationZone).",  ".esc_html($pickupLocationCity)."</td>
             <td>".esc_html($scheduledDate)."</td>
             <td>  ". esc_html($noOfPackgaes). "</td>
             <td>". esc_html($contactPerson->name)." <br/>". esc_html($contactPerson->phone). "</td>
          </tr>
          <tr>
          <th>Notes </th>
          <th>Pickup Type</th>
          <th>Signature</th>
       </tr>
       <tr>
       <td class='last-field'>". esc_html($pickupInfo->notes)."</td>
       <td class='last-field'>" .esc_html($pickupInfo->packageType). "</td>
       <td class='last-field'> <img  class='signature-image' src=".esc_attr($pickupInfo->signature)."/></td>
    </tr>
    </table>
    ";
			if ($pickupInfo->isRepeated) {
				$repeatedPickupData = $pickupInfo->repeatedData;
				$startDate = $repeatedPickupData->startDate ? date('d/m/Y', strtotime($repeatedPickupData->startDate)) : 'N/A';
				$endDate = $repeatedPickupData->startDate ? date('d/m/Y', strtotime($repeatedPickupData->endDate)) : 'N/A';
				$repeatedDays = $repeatedPickupData->repeatedType == "Daily" ? "Daily" : join(",", $repeatedPickupData->days);
				$nextDate = $repeatedPickupData->nextpickupDate ?: 'N/A';
				echo "

        <p class='repetition-info-title'>        Repetition Info
            </p>
        <table class='order-details-table pickup-info-table'>
        <tr>
        <th>Start date</th>
        <th>End date</th>
        <th>Repetition type</th>
        <th>Next pickup date</th>
     </tr>
     <tr>
     <td class='last-field'>  ".esc_html($startDate)."</td>
     <td class='last-field'>  ".esc_html($endDate)."</td>
     <td class='last-field'>  ".esc_html($repeatedDays)."</td>
     <td class='last-field'>  ".esc_html($nextDate)."</td>
  	 </tr>
        </table>";
			}
			echo "<p class='create-pickup-title'>   Total Pickups (".esc_html($deliveries)."  </p>
    ";

			if (count($pickupInfo->deliveryTrackingNumbers) > 0) {
				$url = BOSTA_ENV_URL . '/deliveries/search?trackingNumbers=' . join(',', $pickupInfo->deliveryTrackingNumbers);
				$result = wp_remote_post($url, array(
					'timeout' => 30,
					'method' => 'GET',
					'headers' => array(
						'Content-Type' => 'application/json',
						'authorization' => $APIKey,
						'X-Requested-By' => 'WooCommerce',
						'X-Plugin-Version' => PLUGIN_VERSION
					),
					'body' => json_encode($pickupData),
				));
				if (is_wp_error($result)) {
					$error_message = $result->get_error_message();
					echo "Something went wrong: $error_message";
				} else {
					$pickupDelivries = json_decode($result['body'])->deliveries;
					echo " <table class='pickups-table'>
                    <tr>
                       <th>Tracking Number</th>
                       <th>Type</th>
                       <th>	Customer Info </th>
                       <th>	Dropoff Location</th>
                       <th>	COD </th></tr>";

					for ($counter = 0; $counter < count($pickupDelivries); $counter++) {
						$delivery = sanitize_text_field($pickupDelivries[$counter]);
						$deliveryType = sanitize_text_field($pickupDelivries[$counter]->type->value);
						$receiver = sanitize_text_field($pickupDelivries[$counter]->receiver);
						$dropOffAddress = sanitize_text_field($pickupDelivries[$counter]->dropOffAddress);
						$dropOffAddressDistrict = sanitize_text_field($dropOffAddress->district->name);
						$dropOffAddressZone = sanitize_text_field($dropOffAddress->zone->name);
						$dropOffAddressCity = sanitize_text_field($dropOffAddress->city->name);
						echo "<tr><td>".esc_html($delivery->trackingNumber)."</td>
							<td> ".esc_html($deliveryType)."</td>
							<td>".esc_html($receiver->firstName)."  ".esc_html($receiver->lastName)." <br/>".esc_html($receiver->phone)."</td>
							<td>      ".esc_html($dropOffAddressDistrict)." - ".esc_html($dropOffAddressZone).",  ".esc_html($dropOffAddressCity)."  </td>
							<td>  ".esc_html($delivery->cod)." LE</td></tr>";
					}
				}
			}

		}
	} else if ('admin.php' === $pagenow && $_GET['page'] = 'bosta-woocommerce-view-pickups' && !isset($_GET['pickupId'])) {
		?>   <button class="primary-button" onClick="document.location.href='admin.php?page=bosta-woocommerce-create-edit-pickup'">Create Pickup</button><?php
		if (isset($_GET['state']) && $_GET['state'] == 'history-pickups') {
			?>

            <div class="pickups-page-tabs">
                <button class="tablink " onClick="document.location.href='admin.php?page=bosta-woocommerce-view-pickups'"  id="defaultOpen">Upcoming pickups</button>
                <button class="tablink ActiveTab" onClick="document.location.href='admin.php?page=bosta-woocommerce-view-pickups&&state=history-pickups'">History pickups</button>
            </div>
			<?php
		} else {
			?>
            <div class="pickups-page-tabs">
                <button class="tablink ActiveTab" onClick="document.location.href='admin.php?page=bosta-woocommerce-view-pickups&&state=upcoming-pickups'"  id="defaultOpen">Upcoming pickups</button>
                <button class="tablink" onClick="document.location.href='admin.php?page=bosta-woocommerce-view-pickups&&state=history-pickups'">History pickups</button>
            </div>
			<?php
		}
		if ((isset($_GET['state']) && $_GET['state'] != 'history-pickups') || !isset($_GET['state'])) {
			$url = BOSTA_ENV_URL . '/pickups/search?state=Requested,Arrived+at+business,Route+Assigned,Picking+up,Receiving&pageId=-1';
		} else {
			$url = BOSTA_ENV_URL . '/pickups/search?state=Canceled,Picked up&pageId=-1';
		}
		// if(!isset($pickupData)) return;
		$result = wp_remote_get($url, array(
			'timeout' => 30,
			'method' => 'GET',
			'headers' => array(
				'Content-Type' => 'application/json',
				'authorization' => $APIKey,
				'X-Requested-By' => 'WooCommerce',
				'X-Plugin-Version' => PLUGIN_VERSION
			)
		));
		if ($result['response']['code'] != 200) {
			echo "<div class='error'>" . esc_html( $result->message ) . "<div>";
		} else {
			$result = json_decode($result['body']);
			$count = $result->result->count;
			$pickups = $result->result->pickups;
			$checkIfActionButtonNeeded = ! ( isset($_GET['state']) && $_GET['state'] == 'history-pickups' );
			echo "<h4 class='pickup-table-title'>Pickup Requests</h4>
           <h3 class='pickup-table-subtitle'>Total Pickups (" . esc_html( $count ) . ")<h3>
           <table class='pickups-table'>
              <tr>
                 <th>	Pickup Id</th>
                 <th>	Pickup location</th>
                 <th>Scheduled date </th>
                 <th>Pickup type </th>
                 <th>	Status </th>";
			if ( $checkIfActionButtonNeeded ) {
				echo "<th>Action </th>";
			}
			echo "</tr>
              ";
			for ( $counter = 0; $counter < count( $pickups ); $counter ++ ) {
				$id = $pickups[ $counter ]->_id;
				$puid = $pickups[ $counter ]->puid;
				$pickupLocationName = isset($pickups[ $counter ]->locationName) ? $pickups[ $counter ]->locationName : 'N/A';
				$pickupLocation_city = isset($pickups[ $counter ]->business->address->city->name) ? 
															$pickups[ $counter ]->business->address->city->name : '';
				$pickupLocation_zone = isset($pickups[ $counter ]->business->address->zone->name) ? 
															$pickups[ $counter ]->business->address->zone->name : '';
				$pickupLocation_district = isset($pickups[ $counter ]->business->address->district->name) ?
															$pickups[ $counter ]->business->address->district->name : '';
				$packageType = $pickups[ $counter ]->repeatedData->repeatedType;
				$scheduledDate = $pickups[ $counter ]->scheduledDate;

				switch ( $pickups[ $counter ]->state ) {
					case "Requested":
						$state = 'Created';
						break;
					case "Picking up":
					case "Route Assigned":
						$state = 'In progress';
						break;
					case "Picked up":
						$state = 'Picked up';
						break;
					case "Canceled":
						$state = 'Canceled';
						break;
					default:
						$state = $pickups[ $counter ]->state;
				}
				$state_class_name = strtolower( $state );

				echo "<tr>
              <td><a href='admin.php?page=bosta-woocommerce-view-pickups&&pickupId=" . esc_attr( $id ) . " ' id='myBtn'>" . esc_html( $puid ) . "</a></td>
              <td >" . esc_html( $pickupLocationName ) . "</br>" . esc_html( $pickupLocation_zone ) . " -   " . esc_html( $pickupLocation_district ) . " , " . esc_html( $pickupLocation_city ) . " </td>
              <td >    " . esc_html( $scheduledDate ) . "</td>
              <td >  " . esc_html( $packageType ) . "</td>
              <td > <span class='pickup_state_" . esc_attr( $state_class_name ) . "'>" . esc_html( $state ) . "<span></td><td>";
				if ( $checkIfActionButtonNeeded ) {
					echo '<a href="admin.php?page=bosta-woocommerce-create-edit-pickup&&pickupId=' . esc_attr( $id ) . '">Edit pickup</a>';

				}
				echo "</td>
              </tr>";
			}
			echo "</table>";
		}
	}
}