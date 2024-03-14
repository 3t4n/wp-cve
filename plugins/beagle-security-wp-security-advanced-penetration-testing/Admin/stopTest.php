<?php

//GPL license
include sanitize_file_name('gplLicense.php');

/*to stop the ongoing test*/
function Beagle_WP_stop_Test() {

	global $wpdb;
	
	global $apiServerBaseUrl;

	$Beagle_WP_scan_table = $wpdb->prefix."beagleScanData";

	$getTokenFromTbl = $wpdb->get_results($wpdb->prepare("SELECT * FROM $Beagle_WP_scan_table"));

	foreach ( $getTokenFromTbl as $print ){
		$Beagle_WP_access_token = $print->access_token;
		$Beagle_WP_application_token = $print->application_token;
	}
	try {

		if($Beagle_WP_access_token != null || $Beagle_WP_application_token != null) {

			try {

				if(isset($_POST['stopBeagleTest']) || isset($_POST['restartBeagleTest'])){
					$_POST = array();
					$beaglrURL = $apiServerBaseUrl.'test/stop';
					
					$scanStopData = array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token);
					$request = wp_remote_post($beaglrURL, array(
						'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
						'body'        => json_encode($scanStopData),
						'method'      => 'POST',
						'data_format' => 'body',
					));

					$stopResponse = json_decode(wp_remote_retrieve_body( $request ));

					try {

						if( $stopResponse->status == "Failed" ) {

							$updateData = $wpdb->query($wpdb->prepare("UPDATE $Beagle_WP_scan_table  SET status = %s  WHERE application_token = %s",$stopResponse->status, $Beagle_WP_application_token));

							?>
							<script>
								function app_Exist(){
									var errorMsg=setTimeout("document.getElementById('errMsg').style.display='none';",4000);
								}
								app_Exist()
							</script>
							<div class="errormsg">
								<p id="errMsg">Test stop failed!</p>
							</div>
							<?php
						} else {
							$updateData = $wpdb->query( $wpdb->prepare("UPDATE $Beagle_WP_scan_table  SET status = %s, runningStatus = %s  WHERE application_token = %s",$stopResponse->status, "notRunning" , $Beagle_WP_application_token));
							?>
							<script>
								function app_Exist(){
									var errorMsg=setTimeout("document.getElementById('errMsg').style.display='none';",4000);
								}
								app_Exist()
							</script>
							<div class="message">
								<p id="errMsg">Test stopped successfully</p>
							</div>
							<?php
						}
					}catch (Exception $e) {
					}
				}
			}catch (Exception $e) {
			}

		}else {
			?>
			<script>
				function app_Exist(){
					var errorMsg=setTimeout("document.getElementById('errMsg').style.display='none';",4000);
				}
				app_Exist()
			</script>
			<div class="errormsg">
				<p id="errMsg">Error!</p>
			</div>
			<?php
		}

	}
	catch (Exception $e) {
	}
}