<?php

if (!defined('ABSPATH')) exit;

if (! function_exists('oneon1secure_admin_settings_page_content')) {
	function oneon1secure_admin_settings_page_content() {

		if (! current_user_can('manage_options')) {
			wp_die( __('You do not have sufficient permissions to access this page.'));
		}

		$message 					= '';
		$apikey1on1secure = '';

        OneOn1Secure_enqueue_styles();

		if (isset($_POST['B1'])) {			//form was submitted

			check_admin_referer('update1on1securesettings');	//verify nonce

			$apikey1on1secure 						= preg_replace("/[^a-zA-Z0-9]/", "", sanitize_text_field($_REQUEST['apikey']));						//sanitize the input
			$visitorvalue1on1secure 			= filter_input(INPUT_POST, 'usavisitor', FILTER_SANITIZE_NUMBER_INT);											//sanitize the input as an integer
			$toruser1on1secure 						= filter_input(INPUT_POST, 'toruser', FILTER_SANITIZE_NUMBER_INT);												//sanitize the input as an integer
			$dataanalysis1on1secure 			= filter_input(INPUT_POST, 'dataanalysis', FILTER_SANITIZE_NUMBER_INT);										//sanitize the input as an integer
			$actionforbadips1on1secure 		= filter_input(INPUT_POST, 'actionforbadips', FILTER_SANITIZE_NUMBER_INT);								//sanitize the input as an integer
			$errorpageforbadips1on1secure	= filter_input(INPUT_POST, 'errorpageforbadips1on1secure', FILTER_SANITIZE_NUMBER_INT);		//sanitize the input as an integer
			$homeurl                      = sanitize_url(get_site_url());                                														//sanitize the input

			if((strlen($apikey1on1secure) != 16) && ($apikey1on1secure != '')) {
				$message = '<div id="message" class="error">Invalid API Key '.$apikey1on1secure.'</div>';
				$count = 0;
			} else {
				//they have entered their api key - now verify it

				$apiurl = 'https://api.1on1secure.com/?action=register&apitoken='.$apikey1on1secure.'&domain='.$homeurl;
				$response = wp_remote_get($apiurl, array('timeout' => 2));
				$body     = wp_remote_retrieve_body( $response );
				$apianswers = json_decode($body, true);

				//used to debug communication errors
				// $myipurl = 'http://checkip.dyndns.org/';
				// $myipresponse = wp_remote_get($myipurl, array('timeout' => 5));
				// $myipbody     = wp_remote_retrieve_body($myipresponse);
				// $myipanswer = json_decode($myipbody, true);

				if ($apianswers) {
					if ($apianswers['response'] == 'success') {
						if (!add_option('APIKey1on1Secure', $apikey1on1secure)) {
							update_option('APIKey1on1Secure', $apikey1on1secure);
						}
						$count = 1;
					} else {
						$message = '<div id="message" class="error">API Error: '.$apianswers['errmsg'].'</div>';
						$count = 0;
						if (!add_option('APIKey1on1Secure', '')) {
							update_option('APIKey1on1Secure', '');
						}
					}
				} else {
					$message = '<div id="message" class="error">Unable to contact registration server.</div>';
					$count = 0;
				}
			}

			if (!add_option('OnlyUSAVisitor1on1Secure', $visitorvalue1on1secure)) {
				update_option('OnlyUSAVisitor1on1Secure', $visitorvalue1on1secure);
			}
			if (!add_option('TorUser1on1Secure', $toruser1on1secure)) {
				update_option('TorUser1on1Secure', $toruser1on1secure);
			}
			if (!add_option('DataAnalysis1on1Secure', $dataanalysis1on1secure)) {
				update_option('DataAnalysis1on1Secure', $dataanalysis1on1secure);
			}
			if (!add_option('ActionForBadIPs1on1Secure', $actionforbadips1on1secure)) {
				update_option('ActionForBadIPs1on1Secure', $actionforbadips1on1secure);
			}
			if (!add_option('ErrorPageForBadIPs1on1Secure', $errorpageforbadips1on1secure)) {
				update_option('ErrorPageForBadIPs1on1Secure', $errorpageforbadips1on1secure);
			}

			if($count > 0) {
				$message = '<div id="message" class="updated fade">Congratulations You Are Ready To Go!</div>';
			}
		}

		DisplayAdminSettingPanelsOneOn1Secure($message);
	}
}

if (! function_exists('DisplayAdminSettingPanelsOneOn1Secure')) {
	function DisplayAdminSettingPanelsOneOn1Secure($message) {
		$apikey = sanitize_text_field(get_option("APIKey1on1Secure"));      //sanitize the input to check the API key

		if (strlen($apikey) < 16) {													//if the api key is not added of first time install the plugin
			DisplayAPISettingPageOneOn1Secure($message);									//show the setting page with the register link
		} else {
			DisplayDashboardPanelOneOn1Secure($message);			//display the Graph, Last 5 bad IP, Hit Log Report and Settings boxes.
		}
	}
}

if (! function_exists('DisplayAPISettingPageOneOn1Secure')) {
	function DisplayAPISettingPageOneOn1Secure($message) {
		DisplayAdminSettingHeaderOneOn1Secure($message);
?>
		<div class="container-fluid oneononesecure-outercontainer" style="max-width: 95%; margin: 2 auto; box-sizing: border-box;">
			<div class="row">
				<div class="col-lg-12" style="padding-top: 20px;">
					<div class="panel panel-primary brandattention">
						<form method="POST" action="" class="BOX">
							<?php wp_nonce_field('update1on1securesettings'); ?>
							<div class="panel-heading" style="margin: 0; border-bottom: 3px solid #CDC7C7;">
									<h3 class="graph-header"><i class="fas fa-cog custom-icon"></i>Settings</h3>
							</div>
							<div style="padding-left: 35%; margin-top: 85px;">
								<table class="form-table">
									<tbody>
										<tr style="padding-left: 5px; margin-top: 10px;">
											<td>
												<a target="register" class="btn btn-primary" href="https://console.1on1secure.com/register.php">Get API Key</a>
											</td>
										</tr>
										<tr>
											<th style="padding-left: 40px; padding-top: 20px; font-size: 18px"><label for="apikey">API Key</label></th>
											<td>
												<input type="password" name="apikey" id="apikey" class="regular-text" value="" style="width: 180px; height: 30px; text-align: center;">
												<p style="text-align: left; padding-left: 5%; cursor: pointer; display: inline-block; vertical-align: middle;">
														<input type="submit" value="Update Settings" id="settings" name="B1" class="custom-button" style="border: none;">
												</p>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
<?php
	}
}

if (! function_exists('DisplayAdminSettingHeaderOneOn1Secure')) {
	function DisplayAdminSettingHeaderOneOn1Secure($message) {
		$oneononesecurelogo = plugins_url( '../images/1on1securelogo70.png', __FILE__ );		//add the image url

		if ($message != '') { print $message; }
?>
		<div class="container-fluid oneononesecure-outercontainer" style="max-width: 95%; margin: 2 auto; box-sizing: border-box;">
			<header class="header-menu" style="height: 120px;">
				<div id="icon-options-general" class="icon32"><br></div><br>
				<h1 class="heading" style="font-size: 40px; font-weight: bold; padding-top: 2px" ><img src="<?php print esc_url($oneononesecurelogo); ?>">&nbsp;&nbsp;1on1 Secure API Settings</h1>
			</header>
		</div>
<?php
	}
}

if (! function_exists('DisplayDashboardPanelOneOn1Secure')) {
	function DisplayDashboardPanelOneOn1Secure($message) {
		DisplayAdminSettingHeaderOneOn1Secure($message);

		$userdata = GetDashboardDataAPIOneOn1Secure();				//get the userdata from API 
?>
		<div class="container-fluid oneononesecure-outercontainer" style="max-width: 95%; margin: 2 auto; box-sizing: border-box;">

			<?php	AdminTopNavigationOneOn1Secure();	?><br>

			<div class="row" id="oneononesecure-PageMain">
				<?php DisplayHitLogPieChartOneOn1Secure($userdata); ?>
				<?php DisplayHitLogReportOneOn1Secure($userdata); ?>
			</div>  <!-- close row -->
			<div class="row" id="oneononesecure-PageMain">
        <?php DisplayLastFiveBadIPTableOneOn1Secure($userdata); ?>
				<?php Display1on1SecureAdmin($message); ?>
			</div>  <!-- close row -->

		</div>
<?php
	}
}

if (! function_exists('ErrorPageForBadIps1on1secure')) {
	function ErrorPageForBadIps1on1secure($errorpageipsdropdowndefault) {
		$pageslist 				= ListWebpages1on1secure();
		$thereturn_clean 	= '';																//initialize var
		$selected 				= '';

		foreach ($pageslist as $errorpage) {
			if ($errorpageipsdropdowndefault == $errorpage['id'] ) { $selected = 'selected'; }
			if ($errorpageipsdropdowndefault != $errorpage['id'] ) { $selected = ''; }

			$thereturn_clean .= '<option value='.wp_kses_post($errorpage['id']).' '.wp_kses_post($selected).'>'.wp_kses_post($errorpage['name']).'</option>';
		}

		return $thereturn_clean;
	}
}

if (! function_exists('Display1on1SecureAdmin')) {
	function Display1on1SecureAdmin($message) {

		// wp_enqueue_script('1on1secure-wp-admin-js', plugins_url('../js/1on1secure-wp-admin.js?a=2', __FILE__), array('jquery'), null, true);
		// wp_enqueue_style('1on1secure-wp-admin-css', plugins_url('../css/1on1secure-wp-admin.css', __FILE__));

		$dataanalysis                  = absint(get_option("DataAnalysis1on1Secure"));             //sanitize the input
		$actionforbadips               = absint(get_option("ActionForBadIPs1on1Secure"));          //sanitize the input
		$errorpageipsdropdowndefault   = absint(get_option("ErrorPageForBadIPs1on1Secure"));       //sanitize the input
		$onlyusavisitor                = absint(get_option("OnlyUSAVisitor1on1Secure"));           //sanitize the input
		$toruser                       = absint(get_option("TorUser1on1Secure"));                  //sanitize the input
		$apikey                        = sanitize_text_field(get_option("APIKey1on1Secure"));      //sanitize the input
?>
	<div class="col-lg-6">
		<div class="panel panel-primary brandattention">
			<form method="POST" action="" class="BOX">
				<?php wp_nonce_field('update1on1securesettings'); ?>
				<div class="panel-heading" style="margin: 0; border-bottom: 3px solid #CDC7C7;">
						<h3 class="graph-header"><i class="fas fa-cog custom-icon"></i>Settings</h3>
				</div>
				<div style="padding-left: 50px;padding-right: 50px; margin-top: -1px">
					<table class="form-table">
						<tbody>
							<tr>
								<td>
                  <b>API Key</b><br>
									<input type="password" name="apikey" id="apikey" class="regular-text" value="<?php print esc_html($apikey); ?>">
									<?php //if (strlen($apikey) < 16) { print '<a target="register" class="btn btn-primary" href="https://console.1on1secure.com/register.php">Get API Key</a>'; } ?>
								</td>
							</tr>
							<tr>
								<td><input type="checkbox" id="usavisitor" name="usavisitor" value="1" <?php if ($onlyusavisitor > 0) { print 'checked'; } ?>> Only allow USA visitors</td>
							</tr>
							<tr>
								<td><input type="checkbox" id="toruser" name="toruser" value="1" <?php if ($toruser > 0) { print 'checked'; } ?>> Block Tor Users</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" id="dataanalysis" name="dataanalysis" value="1" <?php if ( $dataanalysis > 0) { print 'checked'; } ?> onclick="popupOneOn1Secure()"> opt-out of data analysis <br>
									<div id="agree" class="popupbox">
										<label class="popuplabel"><sup>Data analysis is how we collect data on potential threats and spam, are you sure you want to opt-out?</sup></label>
										<input class="popupbutton popupbutton2" type="button" value="No" id="no" onclick="closepopupOneOn1Secure()">
										<input class="popupbutton popupbutton1" type="button" value="Yes" id="yes" onclick="closepopupOneOn1Secure()">
									</div>
								</td>
							</tr>
							<tr>
								<td>
                  <b>Action for bad ips:</b><br>
									<select class="dropdown" name="actionforbadips" id="actionforbadips" style=" margin-bottom: 5px;" onclick="popupdropdownOneOn1Secure()">
										<option value="1" <?php if ($actionforbadips == 1) { print 'selected'; }?>>White Screen</option>
										<option value="2" <?php if ($actionforbadips == 2) { print 'selected'; }?>>Send to Honeypot</option>
										<option value="3" <?php if ($actionforbadips == 3) { print 'selected'; }?>>Error Page</option>
										<option value="4" <?php if ($actionforbadips == 4) { print 'selected'; }?>>CAPTCHA Challenge</option>
									</select>
									<br>
<?php
										if ($actionforbadips == 3) {
											print '<select class="dropdown" name="errorpageforbadips1on1secure" id="errorpageforbadips1on1secure">';
										} else {
											print '<select class="dropdown" name="errorpageforbadips1on1secure" id="errorpageforbadips1on1secure" style="display:none" disabled>';
										}
										print ErrorPageForBadIps1on1secure($errorpageipsdropdowndefault); //function returns escaped and clean string
?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
					<p style="text-align: right; cursor: pointer;"><input type="submit" value="Update Settings" id="settings" name="B1" class="custom-button" style="border: none;"></p>
				</div>
			</form>
		</div>
	</div>

<?php
	}
}

if (! function_exists('AdminTopNavigationOneOn1Secure')) {
	function AdminTopNavigationOneOn1Secure() {															//display the button links
	//call to display on 1on1secure-function.php -- function OneOn1Secure_custom_admin_notice

    $getthewebsiteurl 	= sanitize_url(get_site_url());            				//get current site URL and sanitize

?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-6">
					<div class="panel-body" style="margin-top: 30px;">

						<div id="gotooneononsecure" style="display: inline-block; position: relative; top: -2px; margin-left: 7px; font-size: 14px;">
							<a id="gotooneononsecurelink" class="custom-button" target="_blank" href="https://console.1on1secure.com/myaccount.php">
								<i class="fas fa-chart-line"></i>&nbsp;&nbsp;1on1 Secure Dashboard
							</a>
						</div>

						<div id="supportbutton" style="display: inline-block; position: relative; top: -2px; margin-left: 7px; font-size: 14px;">
							<a id="supportbuttonlink" class="custom-button" target="_blank" href="https://wordpress.org/support/plugin/1on1-secure">
								<i class="fas fa-info-circle"></i>&nbsp;&nbsp;Support
							</a>
						</div>

						<!-- <div id="reviewbutton" style="display: inline-block; position: relative; top: -2px; margin-left: 7px; font-size: 14px;">
							<a id="reviewbuttonlink" class="custom-button" target="_blank" href="#">
								<i class="fas fa-comment-dots"></i>&nbsp;&nbsp;Do you like our product?&nbsp;  Post your feedback here
							</a>
						</div> -->

					</div>
				</div>

        <div class="col-lg-6">
          <p class="customprotect-button" style="font-size: 16px; padding-top: 35px;"><i class="dashicons dashicons-shield custom-icon" style="font-size: 25px"></i>
            Protected: <a href="<?php print esc_url($getthewebsiteurl); ?>" target="_blank";><?php print esc_html($getthewebsiteurl); ?></a>
          </p>
        </div>

			</div>
		</div>
<?php
	}
}

if (! function_exists('WebsiteNameAndLinkOneOn1Secure')) {
	function WebsiteNameAndLinkOneOn1Secure() {																															//display the product name and link
		$getthewebsiteurl 	= sanitize_url(get_site_url());                               										//get current site URL and sanitize
?>
		<div class="row">

			<div class="col-lg-12">
        <p class="customprotect-button" style="font-size: 20px; padding-top: 10px;"><i class="dashicons dashicons-shield custom-icon" style="font-size: 25px"></i>
        Protected Site:
          <a href="<?php print esc_url($getthewebsiteurl); ?>" target="_blank";><?php print esc_html($getthewebsiteurl); ?></a>
        </p>
      </div>

		</div>
<?php
	}
}

if (! function_exists('DisplayHitLogPieChartOneOn1Secure')) {
	function DisplayHitLogPieChartOneOn1Secure($userdata) {																														//display the pie chart
?>
		<div class="col-lg-6">
			<div class="panel panel-primary brandattention">
				<div class="panel-heading" style="margin: 0; border-bottom: 3px solid #CDC7C7;">
					<h3 class="graph-header"><i class="fas fa-chart-pie custom-icon"></i>Last 7 Days</h3>
				</div>
				<div class="panel-body">
					<?php GetTotalHitDataAndPieChartOneOn1Secure($userdata); ?>
				</div>
			</div>
		</div>
<?php
	}
}

if (! function_exists('GetTotalHitDataAndPieChartOneOn1Secure')) {
	function GetTotalHitDataAndPieChartOneOn1Secure($userdata) {																											//get the hit log data and create the Pie chart

		//check for error
		if (isset($userdata['error'])) {

			print '
			<div style="margin-top: 10%; font-size: 20px; display: flex; justify-content: center; align-items: center;">
				<div>Graph is Not Available. Please reload the page.</div>
			</div>';

			return;	//exit the function
		}

			$totalhits = $userdata['graph'][0]['thecount'] + $userdata['graph'][1]['thecount'];						//calculate the total hits

			$chartdata[] = array('name', 'Hits');  																																//set the chart data Header

			foreach ($userdata['graph'] as $piechartdatalist => $piechartarray) {
				$chartdata[] = array($piechartarray['name'], (int)$piechartarray['thecount']);											//add the value to the array
			}

			$piechartdataarray = array('piechartvalue' => $chartdata);																						//set the format before sending to the JS

			wp_localize_script('1on1secure-admin', 'piechartdataarray', $piechartdataarray);											//send the data to JS with WP function

			print '
				<div class="table-wrapper" style="display: flex; flex-direction: column; align-items: center;">
					<div>
						<div id="piechart" style="width: 400px; height: 400px;"></div><br>
						<table class="table-wrapper" style="margin-left: -90px; margin-bottom: 10px; margin-top: -150px; font-size: 16px">
								<tr>
										<td nowrap><b>'.'Blocked IPs: '.'&nbsp;</b></td>
										<td><b>'.number_format($userdata['graph'][0]['thecount']).'</b></td>
								</tr>
								<tr>
										<td nowrap><b>'.'Clean IPs: '.'&nbsp;</b></td>
										<td><b>'.number_format($userdata['graph'][1]['thecount']).'</b></td>
								</tr>
								<tr>
										<td nowrap><b>'.'Total Hits: '.'&nbsp;</b></td>
										<td><b>'.number_format($totalhits).'</b></td>
								</tr>                
						</table>
					</div>
				</div>';
		//}
	}
}

if (! function_exists('DisplayLastFiveBadIPTableOneOn1Secure')) {
	function DisplayLastFiveBadIPTableOneOn1Secure($userdata) {
?>
		<div class="col-lg-6">
			<div class="panel panel-primary brandattention">
			<div class="panel-heading" style="margin: 0; border-bottom: 3px solid #CDC7C7; display: flex; justify-content: space-between; align-items: center;">
    		<h3 class="graph-header"><i class="fas fa-user-secret custom-icon"></i>Blocked IPs</h3>
  	  	<a href="https://console.1on1secure.com/myaccount.php" target="_blank" id="seemore" class="custom-button" style="cursor: pointer;">See more</a>
			</div>


				<div class="panel-body">
					<?php GetFiveBadIPDataOneOn1Secure($userdata); ?>
				</div>
			</div>
		</div>
<?php
	}
}

if (! function_exists('GetFiveBadIPDataOneOn1Secure')) {
	function GetFiveBadIPDataOneOn1Secure() {																													//get the bad IP data and create the table

		//check for error
		if (isset($userdata['error'])) {

			print '
			<div style="margin-top: 10%; font-size: 20px; display: flex; justify-content: center; align-items: center;">
				<div>Blocked IP report is Not Available. Please reload the page.</div>
			</div>';

			return;	//exit the function
		}

		if (empty($userdata['fivebadip'][0])) {																																	//if there is no data

			// print '
			// <div style="margin-top: 15%; font-size: 20px; display: flex; justify-content: center; align-items: center;">
			// 	<div>Blocked IP report contains no data at this time.</div>
			// </div>';
?>
			<div class="table-responsive" style="padding: 5px 5px 0.5px 5px;">
				<table class="table table-striped table-bordered" id="myTable">
					<thead>
							<tr>
									<th rowspan="1" style="width: 49.6px; text-align: center;" aria-label="IP">IP</th>
									<th rowspan="1" style="width: 130px; text-align: center;">Date&nbsp;<i class="action-icon fa fa-sort"></i></th>
									<th rowspan="1" style="width: 49.6px; text-align: center;" aria-label="Classification">Classification</th>
									<th rowspan="1" style="width: 49.6px; text-align: center;" aria-label="URL">URL</th>
							</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: center;"> - </td>
							<td style="font-size: 15px; text-align: center"> - </td>
							<td style="text-align: center;"> - </td>
							<td style="text-align: center;"> - </td>
						</tr>
					</tbody>
				</table>
			</div>
<?php

			return;	//exit the function
		} else {

			foreach ($userdata['fivebadip'] as &$record) {																							//get the value in the array
				$date = new DateTime($record['stamp']);																												//create a new DateTime object
				$record['stamp'] = $date->format('F j, Y')."<br>".$date->format('h:i a');											//change the data and time format

			}
				// Create the table
				print '
				<div class="table-responsive" style="padding: 5px 5px 0.5px 5px;">
					<table class="table table-striped table-bordered" id="myTable">
						<thead>
						<tr>
							<th rowspan="1" style="width: 49.6px; text-align: center;" aria-label="IP">IP</th>
							<th rowspan="1" style="width: 160px; text-align: center;" >Date&nbsp;<i class="action-icon fa fa-sort"></i></th>
							<th rowspan="1" style="width: 49.6px; text-align: center;" aria-label="Classification">Classification</th>
							<th rowspan="1" style="width: 49.6px; text-align: center;" aria-label="URL">URL</th>
						</tr>
						</thead>
						<tbody>';
							foreach ($userdata['fivebadip'] as $row) {
								print '
								<tr>
									<td>'.$row['ip'].'</td>
									<td style="font-size: 15;">'.$row['stamp'].'</td>
									<td>'.$row['description'].'</td>
									<td>'.$row['urlhit'].'</td>
								</tr>';
							}
				print '
					</tbody>
					</table>
				</div>';
		}
	}
}

if (! function_exists('DisplayHitLogReportOneOn1Secure')) {
	function DisplayHitLogReportOneOn1Secure($userdata) {
?>
		<div class="col-lg-6">
			<div class="panel panel-primary brandattention">
				<div class="panel-heading" style="margin: 0; border-bottom: 3px solid #CDC7C7;">
					<h3 class="graph-header"><i class="fas fa-user-shield custom-icon"></i>Website Hits Report</h3>
				</div>
				<div class="panel-body">
					<?php GetHitLogReportAndAreaChartOneOn1Secure($userdata); ?>
				</div>
			</div>
		</div>
<?php
	}
}

if (! function_exists('GetHitLogReportAndAreaChartOneOn1Secure')) {
	function GetHitLogReportAndAreaChartOneOn1Secure($userdata) {																						//get the data and create the Area chart

		//check for error
		if (isset($userdata['error'])) {

			print '
			<div style="margin-top: 10%; font-size: 20px; display: flex; justify-content: center; align-items: center;">
				<div>Website Hits Report is Not Available. Please reload the page.</div>
			</div>';

			return;	//exit the function
		}

			$hitlogchartdata[] = array('Date', 'Hits');  																									//set the chart data Header

			foreach ($userdata['hitlogreport'] as $hitlogname => $hitloglists) {
				$hitlognames[] = $hitlogname;
				foreach ($hitloglists as $hitlogdate => $hitloglist) {
					$date = date('M d', strtotime($hitloglist['date'] ?? '')); 																//set the date format

					if (isset($hitloglist['count'])) {
						$hitlogchartdata[$hitlogname][] = array($date, (int)$hitloglist['count']);							//add the data into the chart
					} else {
						$hitlogchartdata[$hitlogname][] = array($date, 0);																			//fix the undefined array error
					}
				}
			}

			//set the format before sending to the JS
			$hitlogchartdataarray = array(
				'hitlogchartvalue' => $hitlogchartdata,
				'hitlognames' => $hitlognames
			);

			wp_localize_script('1on1secure-admin', 'hitlogchartdataarray', $hitlogchartdataarray);				//send the data to JS with WP function

			//dropdown classification
			print '
			<select id="classificationSelect" onchange="updateVisibleChartOneOn1Secure();" style="margin-left: 25px; margin-top: 15px; margin-bottom: 5px;">';
				foreach ($userdata['hitlogreport'] as $hitlogname => $hitloglist) {
					print '
					<option value="'.($hitlogname).'">'.$hitlogname.'</option>';
				}
			print'
			</select>';

			//display the chart
			foreach ($userdata['hitlogreport'] as $hitlogname => $hitloglist) {
				print '
					<div style="position: relative; align-content: flex-start; display: flex; flex-flow: wrap">
						<div id="chart_'.esc_attr($hitlogname).'" style="width: 100%; height: 350px; margin-top: -20px; margin-bottom: 5px;"></div>
					</div>';
			}
		//}
	}
}

if (! function_exists('GetDashboardDataAPIOneOn1Secure')) {
	function GetDashboardDataAPIOneOn1Secure() {

		$apikey 	= sanitize_text_field(get_option("APIKey1on1Secure"));									//get the API key
		$homeurl 	= sanitize_url(get_site_url());                                					//sanitize the site URL

		//get the data from the API
		$apiurl 	= 'https://api.1on1secure.com/myaccount.php?action=allstatssummary&apitoken='.$apikey.'&domain='.$homeurl;
		$response = wp_remote_get($apiurl, array('timeout' => 9));

		if (is_wp_error($response)) {																											//if there is an error, show it
			$apidata['error']	= '1on1secure Error {DMK-441} : '.$response->get_error_message();
		} else if (wp_remote_retrieve_response_code($response) != 200 ) {
			$apidata['error']	= '1on1secure Error {DMK-441} : '.wp_remote_retrieve_response_code($response);
		} else {
			$body     = wp_remote_retrieve_body($response);																	//if there is no error, get the data
			$apidata	= json_decode($body, true);
		}

		return $apidata;
	}
}

if (! function_exists('OneOn1Secure_enqueue_styles')) {
  function OneOn1Secure_enqueue_styles() {                              //add the stylesheet and script

    //load admin page script
    wp_enqueue_script('1on1secure-admin', plugins_url('../js/1on1secure-wp-admin.js?a=3', __FILE__), array('jquery', '1on1secure-google-charts'), null, true);

    //add the admin website stylesheet
    wp_enqueue_style('1on1secure-wp-admin-css', plugins_url( '../css/1on1secure-wp-admin.css', __FILE__ ));

    // Add Bootstrap CSS
    wp_enqueue_style('1on1secure-bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array(), '4.3.1');

    // Enqueue Font Awesome stylesheet
    wp_enqueue_style('1on1secure-font-awesome-6.4.2', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2');

    //google API
    wp_enqueue_script('1on1secure-google-api-js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');

    // Add Bootstrap JavaScript
    wp_enqueue_script('1on1secure-bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'), '4.3.1', true);

    //google draw chart
    wp_enqueue_script('1on1secure-google-charts', 'https://www.gstatic.com/charts/loader.js');

    //use the dashicons in the WordPress, you can choose the icon in https://developer.wordpress.org/resource/dashicons/
    wp_enqueue_style('dashicons');
  }
}
