<?php

//GPL license

include sanitize_file_name('gplLicense.php');

/*To post the signature in the wordpress root directory*/
function Beagle_WP_addDataTo_DB()
{
	global $wpdb;

	global $apiServerBaseUrl;

	$Beagle_WP_scan_table = $wpdb->prefix . "beagleScanData";

	$Beagle_WP_access_token = sanitize_text_field($_POST['access_token']);

	$Beagle_WP_application_token = sanitize_text_field($_POST['application_token']);

	try {
		if (isset($_POST['startVerify'])) {
			$Beagle_WP_dataInDB = $wpdb->get_results("SELECT * FROM $Beagle_WP_scan_table WHERE `application_token`='$Beagle_WP_application_token'");
			try {
				if (!$Beagle_WP_dataInDB) {

					$beaglrURL = $apiServerBaseUrl . 'test/signature';
					$getverifiedToken = array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token);
					$request = wp_remote_post($beaglrURL, array(
						'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
						'body'        => json_encode($getverifiedToken),
						'method'      => 'POST',
						'data_format' => 'body',
					));
					$statusResponse = json_decode(wp_remote_retrieve_body($request));
					try {
						$dateLog = date("Y-m-d");
						if ($statusResponse->status == "Failed") { 
							try {
								$Beagle_WP_log_save_path = plugin_dir_path(__FILE__)  . '../log.txt';
								if (is_writable($Beagle_WP_log_save_path)) {
									$Beagle_WP_log_open = fopen($Beagle_WP_log_save_path, "a");
									$Beagle_WP_log_write = fputs($Beagle_WP_log_open, "\n" .$dateLog. " -> " .$statusResponse->message. + "\n");
									fclose($Beagle_WP_log_open);
								}
							} catch (Exception $e) {
							}
							?>
							<script>
								function appExist() {
									var errorMsg = setTimeout("document.getElementById('errMsg').style.display='none';", 4000);
								}
								appExist()
							</script>
							<div class="errormsg">
								<p id="errMsg">Invalid credentials. Please try again!</p>
							</div>
							<?php
						} else if($statusResponse == null) {
							try {
								$Beagle_WP_log_save_path = plugin_dir_path(__FILE__)  . '../log.txt';
								if (is_writable($Beagle_WP_log_save_path)) {
									$Beagle_WP_log_open = fopen($Beagle_WP_log_save_path, "a");
									$Beagle_WP_log_write = fputs($Beagle_WP_log_open, "\n" .$dateLog. "-> User not authorized. \n");
									fclose($Beagle_WP_log_open);
								}
							} catch (Exception $e) {
							}
							?>
							<script>
								function appExist() {
									var errorMsg = setTimeout("document.getElementById('errMsg').style.display='none';", 4000);
								}
								appExist()
							</script>
							<div class="errormsg">
								<p id="errMsg">User not authorized!</p>
							</div>
							<?php
						}
						else if ($statusResponse->status == "NotVerified") {
							$verifyText = $statusResponse->signature;
							try {
								$Beagle_WP_file_save_path = plugin_dir_path(__FILE__)  . '../' . $verifyText . '.txt';
								$Beagle_WP_file_save_path_test = plugin_dir_path(__FILE__)  . '../fileWrightTest.txt';
								if (is_writable($Beagle_WP_file_save_path_test)) {
									$Beagle_WP_file_open = fopen($Beagle_WP_file_save_path, "a");
									$Beagle_WP_file_write = fputs($Beagle_WP_file_open, $verifyText);
									fclose($Beagle_WP_file_open);
									if ($Beagle_WP_file_write) {
										$wpdb->insert($Beagle_WP_scan_table, array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token, "verified" => 0, "title" => $statusResponse->title, "url" => $statusResponse->url, "runningStatus" => "notRunning", "autoVerify" => "0"), array("%s", "%s", "%d", "%s", "%s", "%s", "%d"));
									} else {
										?>
										<script>
											function appExist() {
												var errorMsg = setTimeout("document.getElementById('errMsg').style.display='none';", 4000);
											}
											appExist()
										</script>
										<div class="errormsg">
											<p id="errMsg">An unexpected error occurred. Please try again!</p>
										</div>
										<?php
									}
								}else {
									$wpdb->insert($Beagle_WP_scan_table, array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token, "verified" => 0, "title" => $statusResponse->title, "url" => $statusResponse->url, "runningStatus" => "notRunning", "autoVerify" => "1"), array("%s", "%s", "%d", "%s", "%s", "%s", "%d"));
								}
							} catch (Exception $e) {
							}
						} else {
							$wpdb->insert($Beagle_WP_scan_table, array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token, "verified" => 1, "title" => $statusResponse->title, "url" => $statusResponse->url, "runningStatus" => "notRunning", "autoVerify" => "0"), array("%s", "%s", "%d", "%s", "%s", "%s", "%d"));
						}
					} catch (Exception $e) {
					}
				}
			} catch (Exception $e) {
			}
		}
	} catch (Exception $e) {
	}
}
