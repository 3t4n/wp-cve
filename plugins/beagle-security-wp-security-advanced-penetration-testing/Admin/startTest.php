<?php

//GPL license
include sanitize_file_name('gplLicense.php');

/*To start the test after verifying the application*/
function Beagle_WP_start_Test() {

	global $wpdb;

	global $apiServerBaseUrl;

	$Beagle_WP_scan_table = $wpdb->prefix."beagleScanData";

	$getTokenFromTbl = $wpdb->get_results($wpdb->prepare("SELECT * FROM $Beagle_WP_scan_table"));

	foreach ( $getTokenFromTbl as $print ){
		$Beagle_WP_access_token = $print->access_token;
		$Beagle_WP_application_token = $print->application_token;
	}

	$beaglrURL = $apiServerBaseUrl.'test/start';

	$scanStartData = array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token);

	$startRequest = wp_remote_post($beaglrURL, array(
		'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
		'body'        => json_encode($scanStartData),
		'method'      => 'POST',
		'data_format' => 'body',
	));

	$startMsgResponse = json_decode(wp_remote_retrieve_body( $startRequest ));
	try {
		if( $startMsgResponse ) {

			try {
				if ($startMsgResponse->status == 'Failed') {

					$updateData = $wpdb->query($wpdb->prepare("UPDATE $Beagle_WP_scan_table  SET status = %s WHERE application_token = %s",$startMsgResponse->status,  $Beagle_WP_application_token));

					?>
					<script>
						function app_Exist(){
							var errorMsg=setTimeout("document.getElementById('errMsg').style.display='none';",4000);
						}
						app_Exist()
					</script>
					<div class="errormsg">
						<p id="errMsg"><?php echo $startMsgResponse->message;?></p>
					</div>
					<?php
				}else {

					$resultToken = $startMsgResponse->result_token;

					$updateData = $wpdb->query( $wpdb->prepare("UPDATE $Beagle_WP_scan_table  SET status = %s , result_token = %s, runningStatus = %s WHERE application_token = %s",$startMsgResponse->status, $startMsgResponse->result_token, "Running",  $Beagle_WP_application_token));
					?>
					<script>
						function app_Exist(){
							var errorMsg=setTimeout("document.getElementById('errMsg').style.display='none';",4000);
						}
						app_Exist()
					</script>
					<div class="message">
						<p id="errMsg">Test started successfully.</p>
					</div>
					<?php

				}
			}
			catch(Exception $e) {
			}
		}
		else {

			?>
			<script>
				function app_Exist(){
					var errorMsg=setTimeout("document.getElementById('errMsg').style.display='none';",4000);
				}
				app_Exist()
			</script>
			<div class="errormsg">
				<p id="errMsg">Test start failed!</p>
			</div>
			<?php
		}
	}catch(Exception $e) {
	}
}