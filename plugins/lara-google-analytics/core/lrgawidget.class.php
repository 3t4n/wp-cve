<?php
namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");

class LaraGoogleAnalyticsWidget {
	
	private $systemTimeZone;
    private $gapi;
	private $cache;
	private $cachedOutput;
	private $cachePrefix;
	private $cacheTime;
	private $dParams  = array();
	private $params   = array();
	private $filters  = array();	
	private $settings = array();
	private $results  = array();
	private $output   = array();
	private $errors   = array();
	private $metrics  = array();
	private $cached;
	private $base_metric;
	private $currentQueryParams;
	private $mdCurrentQueryParams;
	private $cacheEarningsOutput;
	private $earningsCacheTime;
	
	function __construct(){
		$this->systemTimeZone = SystemBootStrap::getSystemTimeZone();
		$this->gapi = new GoogleAnalyticsAPI();
		$this->cache = true;
		$this->cachePrefix = "lrga_";
		$this->cacheTime = 3600;
		$this->setBaseMetric("activeUsers");
		$this->cacheEarningsOutput = true;
		$this->earningsCacheTime = 900;
		$this->metrics = array("activeUsers"            => array( "label"     => __('Active Users', 'lara-google-analytics')),
							   "newUsers"               => array( "label"     => __('New Users', 'lara-google-analytics')),
							   "sessions"               => array( "label"     => __('Sessions', 'lara-google-analytics')),
							   "screenPageViews"        => array( "label"     => __('Screen Page Views', 'lara-google-analytics')),
							   "eventCount"             => array( "label"     => __('Event Count', 'lara-google-analytics')),
							   "userEngagementDuration" => array( "label"     => __('Average Engagement Time', 'lara-google-analytics'), 
																  "lrformat"  => "seconds",
																  "total"     => "average" ),
							   "bounceRate"             => array( "label"     => __('Bounce Rate', 'lara-google-analytics'),
																  "lrafter"   => "%",	
							   									  "lrformat"  => "percentage",
																  "total"     => "average" ),
							   );
										
	}
	
	private function setBaseMetric($base_metric){
		$this->base_metric = $base_metric;
		$this->dParams = array( "keepEmptyRows" => TRUE, "metrics" => array(array("name" => $this->base_metric)));
		
	}
	
	private function getGraphObject(){
		$graphObject = false;
		if (in_array("ecom_woo", DataStore::$RUNTIME["permissions"])){
			if (class_exists( 'woocommerce' )){
				require(lrgawidget_plugin_dir . "core/plugins/earnings/lrga_earnings_sales.php");
				require(lrgawidget_plugin_dir . "core/plugins/earnings/wordpress/lrga_wp_woo_plugin.php");
				$graphObject = new lrga_wp_woo_plugin($this->get_session_setting('start_date'), $this->get_session_setting('end_date'));
			}
		}

		
		return $graphObject;
	}
	
	public function getMainGraph(){

		$this->params = [   "metrics"    => [
											 ["name" => "newUsers"],
											 ["name" => "sessions"],
									         ["name" => "screenPageViews"],
											 ["name" => "eventCount"],
											 ["name" => "userEngagementDuration"],
											 ["name" => "bounceRate"],
											],
							"dimensions" => [
											 ["name" => "date"]
											],
							"orderBys"   => [
											 ["dimension" => ["dimensionName" => "date"]]
											]
						];	
						
		$this->doCall();
		
		$cachedCall = array();
        if (($this->cache === true) && (!empty($this->mdCurrentQueryParams))){
            $cachedCall = DataStore::get_from_cache($this->cachePrefix, $this->mdCurrentQueryParams."_output", $this->cacheTime);
		}
			
		if (!empty($cachedCall)){
			$this->output = $cachedCall;
			$this->cachedOutput = true;
		}else{
			
			$date_range = array();
			$start_date = new \DateTime($this->get_session_setting('start_date'));
			$end_date   = new \DateTime($this->get_session_setting('end_date'));			
			$period     = new \DatePeriod($start_date, new \DateInterval('P1D'), $end_date->modify( '+1 day' ));
			$periods    = iterator_to_array($period);
			foreach($periods as $date) { 
				$d = strtotime($date->format('Ymd') . " UTC" ) * 1000;
				$date_range[$d] = array($d, 0); 
			}
			
			$processed_results = array();
			foreach($this->results["metricHeaders"] as $id => $metric){
				$processed_results["plotdata"][$metric["name"]]            = array("label"    => $this->metrics[$metric["name"]]["label"],
																				   "data"     => $date_range,
																				   "lrbefore" => "",
																				   "lrafter"  => "",
																				   "lrformat" => "");
				$processed_results["totalsForAllResults"][$metric["name"]] = array("label"    => $this->metrics[$metric["name"]]["label"],
																				   "data"     => array(),
																				   "rawData"  => array(),
																				   "total"    => 0 ); 
			}
				
			if((!empty($this->results["rows"]) && (is_array($this->results["rows"])))){
				foreach ($this->results["rows"] as $row){
					$date = strtotime($row["dimensionValues"][0]["value"]." UTC") * 1000;
					foreach($this->results["metricHeaders"] as $id => $metric){
						$raw_value = $row["metricValues"][$id]["value"];
						$value     = $raw_value;
						
						if($metric["name"] === "userEngagementDuration"){
							$activeUsers = intval($row["metricValues"][0]["value"]);
							if( $activeUsers > 0 ){
								$value     = intval($value/$activeUsers);
								$raw_value = $raw_value/$row["metricValues"][0]["value"];
							}
						}
						
						if(!empty($this->metrics[$metric["name"]]["lrformat"])){
							if($this->metrics[$metric["name"]]["lrformat"] === "percentage"){
								$value = number_format((float)$value * 100, 1, '.', '');
							}
						}
						$processed_results["plotdata"][$metric["name"]]["data"][$date]          = array($date, $value);
						$processed_results["totalsForAllResults"][$metric["name"]]["total"]     = $processed_results["totalsForAllResults"][$metric["name"]]["total"] + $value;
						$processed_results["totalsForAllResults"][$metric["name"]]["rawData"][] = $raw_value;
					}
				}
			}
				

				foreach($processed_results["plotdata"] as $metric => $data){
					$processed_results["plotdata"][$metric]["data"] = array_values($processed_results["plotdata"][$metric]["data"]);
					if(!empty($this->metrics[$metric]["lrbefore"])){$processed_results["plotdata"][$metric]["lrbefore"] = $this->metrics[$metric]["lrbefore"];}
					if(!empty($this->metrics[$metric]["lrafter"])) {$processed_results["plotdata"][$metric]["lrafter"]  = $this->metrics[$metric]["lrafter"];}
					if(!empty($this->metrics[$metric]["lrformat"])){$processed_results["plotdata"][$metric]["lrformat"] = $this->metrics[$metric]["lrformat"];}
					foreach($processed_results["plotdata"][$metric]["data"] as $mdata){
						$processed_results["totalsForAllResults"][$metric]["data"][]  = $mdata[1];
					}
				}
				
				foreach($processed_results["totalsForAllResults"] as $id => $metric){
					$total = $processed_results["totalsForAllResults"][$id]["total"];
					if(!empty($this->metrics[$id]["total"])){
						if($this->metrics[$id]["total"] === "average"){
							$total = $this->calculateAverage($processed_results["totalsForAllResults"][$id]["rawData"]);
							unset($processed_results["totalsForAllResults"][$id]["rawData"]);
						}
					}else{
						$total = $this->shorten($total);
					}
					if(!empty($this->metrics[$id]["lrformat"])){
						if($this->metrics[$id]["lrformat"] === "seconds"){
							$total = gmdate("H:i:s", round($total));
						}elseif	($this->metrics[$id]["lrformat"] === "percentage"){
							$total = number_format((float)$total * 100, 1, '.', '') . "%";
						}
					}
					
					$processed_results["totalsForAllResults"][$id]["total"] = $total;
					$processed_results["totalsForAllResults"][$id]["data"]  = implode(",", $processed_results["totalsForAllResults"][$id]["data"]);
				}
				
			$this->output =  $processed_results;

			if (($this->cache === true) && (!empty($this->mdCurrentQueryParams))){
				DataStore::save_to_cache($this->cachePrefix, $this->mdCurrentQueryParams."_output", $this->output);
			}
		}
		$this->getEarnings();
		$this->jsonOutput();
	}
	
	
	private function getEarnings(){
		if ($this->get_database_setting('enable_ecommerce_graph') === "on"){
			$graphOutput  = array();
			if ($this->cacheEarningsOutput === true){
				$graphOutput = DataStore::get_from_cache($this->cachePrefix,"earnings_seriesData", $this->earningsCacheTime);
				$cached = true;
			}
			if (empty($graphOutput)){
				$cached = false;
				$graphObject = $this->getGraphObject();
				if (($graphObject !== false) && (is_object($graphObject))){
					$graphOutput = $graphObject->getGraphOutput();
					if ($this->cacheEarningsOutput === true){
						DataStore::save_to_cache($this->cachePrefix, "earnings_seriesData", $graphOutput);
					}
				}
			}

			if (!empty($graphOutput)){
				list($this->output['plotdata']['sales'], $this->output['plotdata']['earnings'],$this->output['graph']['settings']) = $graphOutput;
			}
			
			$this->output['getearnings'] = $cached;
		}
	}
	
	public function getGraphData(){
		if ($this->get_database_setting('enable_ecommerce_graph') === "on"){
			$graphData  = array();
			if ($this->cacheEarningsOutput === true){
				$graphData = DataStore::get_from_cache($this->cachePrefix,"earnings_graphData", $this->earningsCacheTime);
				$cached = true;
			}
			
			if (empty($graphData)){
				$cached = false;
				$graphObject = $this->getGraphObject();
				if (($graphObject !== false) && (is_object($graphObject))){
					$graphData = $graphObject->getGraphData();
					DataStore::save_to_cache($this->cachePrefix, "earnings_graphData", $graphData);
				}
			}
			
			if (!empty($graphData)){
				$this->output = $graphData;
			}
			$this->output['gaoptionscached'] = $cached;
		}
		$this->jsonOutput();
	}	
	
	public function getBrowsers($lrdata){
		$this->params = array( "dimensions" => array(array("name" => "browser")));
		$this->doCall(true);
	}

	public function getLanguages(){
		$this->params = array( "dimensions" => array(array("name" => "language")));			
		$this->doCall(true);
	}

	public function getOS($lrdata){
		$this->params = array( "dimensions" => array(array("name" => "operatingSystem")));
		$this->doCall(true);
	}
	
	public function getDevices($lrdata){
		$this->params = array( "dimensions" => array(array("name" => "deviceCategory")));
		$this->doCall(true);
	}	

	public function getScreenResolution(){
		$this->params = array( "dimensions" => array(array("name" => "ScreenResolution")));		
		$this->doCall(true);
	}	

	public function getPages(){
		$this->setBaseMetric("screenPageViews");
		$this->params = array( "dimensions" => array(array("name" => "hostName"),array("name" => "pagePath"),array("name" => "pageTitle"))	);
		$this->doCall(true);
	}
	
	private function doCall($handleOutput=false){
		$this->checkSettings();
		$_params = array_merge_recursive($this->dParams, $this->params, $this->filters);
		$this->gapi->buildQuery($_params);
		$this->setCurrentQueryParms();
		$this->inCache($this->currentQueryParams);
		if (!$this->cached){
			$this->results = $this->gapi->doQuery();
			if ($handleOutput){
				$processed_results = array("table_data" => array(), "total" => 0);
				if((!empty($this->results["rows"]) && (is_array($this->results["rows"])))){
					foreach ($this->results["rows"] as $row){
						$processed_row = array();
						
						if($this->base_metric === "screenPageViews"){
							$processed_row[] = array($row["dimensionValues"][0]["value"] . $row["dimensionValues"][1]["value"], $row["dimensionValues"][2]["value"]);//escape html
						}else{
							foreach($this->results["dimensionHeaders"] as $id => $dimension){
								$value = $row["dimensionValues"][$id]["value"];
								if($dimension["name"] === "deviceCategory"){
									$value = ucwords($value);
								}
								$processed_row[] = $value;
							}
						}
						
						foreach($this->results["metricHeaders"] as $id => $metric){
							$processed_row[] = $row["metricValues"][$id]["value"];
							if($metric["name"] === $this->base_metric ){
								$processed_results["total"] = $processed_results["total"] + $row["metricValues"][$id]["value"];
							}
						}
						array_walk_recursive($processed_row, array($this, 'html_escape'));
						$processed_results["table_data"][] = $processed_row;
						
					}
					
					foreach ($processed_results["table_data"] as $index => $record){
						if(!empty($this->filters)){
							unset($processed_results["table_data"][$index][0]);
							$processed_results["table_data"][$index] = array_values($processed_results["table_data"][$index]);
						}
						$processed_results["table_data"][$index][] = number_format(((end($record)*100)/$processed_results["total"]),2);
					}
				}
				$this->results = $processed_results;
			}

			if ($this->cache){
				DataStore::save_to_cache($this->cachePrefix, $this->mdCurrentQueryParams, $this->results);
			}
		}

		if ($handleOutput){
			$this->output  = $this->results;
			$this->jsonOutput();
		}
	}
	
	private function inCache($query){
        $this->cached = false; 		
		if ($this->cache){
			$queryID = md5(json_encode($query, true));
			$cachedCall = DataStore::get_from_cache($this->cachePrefix, $queryID, $this->cacheTime);
			if (!empty($cachedCall)){
				$this->results = $cachedCall;
				$this->cached = true;
			}
	    }
	}

	private function setCurrentQueryParms(){
		$this->currentQueryParams = $this->gapi->getQueryParams();
		$this->mdCurrentQueryParams = md5(json_encode($this->currentQueryParams, true));
	}
	
	private function checkSettings (){
		if ( ($this->get_database_setting('client_id') === null) || ($this->get_database_setting('client_secret') === null) || ($this->get_database_setting('access_token')=== null) ) {
			$this->output = array("setup" => 1);
			$this->jsonOutput();
		}elseif ($this->get_database_setting('measurementId') === null || $this->get_database_setting('property_id') === null){
			$this->output = array("setup" => 2);
			$this->jsonOutput();
		}
		
		if ( ($this->get_session_setting('start_date') !== null) && ($this->get_session_setting('end_date') !== null)){
			$this->setGapiValues(array( 'start_date'   => $this->get_session_setting('start_date'), 
										'end_date'     => $this->get_session_setting('end_date')));
		}
		$this->setGapiValues(array('property_id'   => $this->get_database_setting('property_id')));
		$this->refreshToken();		
	}

    ## Authentication Methods	
	public function getAuthURL($lrdata){
		$this->setGapiValues(array( 'client_id' => $lrdata['client_id'], 'client_secret'  => $lrdata['client_secret']));
		$this->output = array('url'=>$this->gapi->authURL());
		$this->jsonOutput();
	}
	
	public function getAccountSummaries($lrdata){
		$this->output['current_selected'] = array("account_id"          => $this->get_database_setting('account_id'),
		                                           "property_id"        => $this->get_database_setting('property_id'),
												   "datastream_id"      => $this->get_database_setting('datastream_id'),
												   "measurementId"      => $this->get_database_setting('measurementId')); 

		if(!empty($this->output['current_selected']["property_id"]) && empty($lrdata["pid"])){
			$lrdata["pid"] = $this->output['current_selected']["property_id"];
		}
		$this->refreshToken();
		
		if(!empty($lrdata["purge"]) && $lrdata["purge"] === "true"){
			$this->purgeCache();
		}
		
		$this->results = DataStore::get_from_cache($this->cachePrefix, md5('accountSummaries'), $this->cacheTime);
		if (empty($this->results['accountSummaries'])){
			$this->results = $this->gapi->getAccounts();
			DataStore::save_to_cache($this->cachePrefix, md5('accountSummaries'), $this->results);
		}else{$this->output['accountSummaries_cache'] = true;}
		
		if (!empty($this->results['accountSummaries']) && is_array($this->results['accountSummaries'])){
			foreach ($this->results['accountSummaries'] as $account){
				$account_id = str_replace("accounts/", "", $account['account']);
				$this->output['accountSummaries'][$account_id] = array( "id"          => $account_id,
				                                                        "displayName" => $account['displayName']);
				if (!empty($account['propertySummaries']) && is_array($account['propertySummaries'])){
					foreach ($account['propertySummaries'] as $property){
						$property_id = str_replace("properties/", "", $property['property']);
						$this->output['accountSummaries'][$account_id]["properties"][$property_id] = array( "id"          => $property_id,
						                                                                                    "displayName" => $property['displayName']);
						if(!empty($lrdata["pid"]) && $lrdata["pid"] === $property_id){
							$this->output['current_selected']["account_id"]  = $account_id;
							$this->output['current_selected']["property_id"] = $property_id;
							$results = $this->gapi->getProperty($property_id);
							if(!empty($results["timeZone"])){
								$this->output['accountSummaries'][$account_id]["properties"][$property_id]["timeZone"] = $results["timeZone"];
							}
							$results = $this->gapi->getDataStreams($property_id);
							if(!empty($results["dataStreams"]) && is_array($results["dataStreams"])){
								foreach ($results["dataStreams"] as $dataStream){
									if(!empty($dataStream['webStreamData'])){
										$dataStream_id  = str_replace("properties/" . $property_id . "/dataStreams/", "", $dataStream["name"]);
										$measurement_id = "G-" . strtoupper(ltrim(strtolower($dataStream['webStreamData']["measurementId"]),"g-"));
										$this->output['accountSummaries'][$account_id]["properties"][$property_id]["dataStreams"][$dataStream_id] = array( "id"            => $dataStream_id,
																																						   "type"          => $dataStream['type'],					
																																						   "displayName"   => $dataStream['displayName'],
																																						   "measurementId" => $measurement_id,
																																						   "defaultUri"    => $dataStream['webStreamData']["defaultUri"]);
									}
								}
								if(!empty($this->output['accountSummaries'][$account_id]["properties"][$property_id]["dataStreams"])){
									if (array_key_exists($this->output['current_selected']["datastream_id"], $this->output['accountSummaries'][$account_id]["properties"][$property_id]["dataStreams"]) !== true) {
										$ds = array_key_first($this->output['accountSummaries'][$account_id]["properties"][$property_id]["dataStreams"]);
										$this->output['current_selected']["datastream_id"] = $this->output['accountSummaries'][$account_id]["properties"][$property_id]["dataStreams"][$ds]["id"];
										$this->output['current_selected']["measurementId"] = $this->output['accountSummaries'][$account_id]["properties"][$property_id]["dataStreams"][$ds]["measurementId"];
									}
								}
							}
						}
					}
				}else{unset($this->output['accountSummaries'][$account_id]);}
			}
		}
		
		DataStore::save_to_cache($this->cachePrefix, md5('accountSummaries')."_output", $this->output);
		$this->jsonOutput();
	}

	public function getAccessToken($lrdata){
		if ($lrdata['client_id'] === lrgawidget_plugin_client_id){$lrdata['client_secret'] = lrgawidget_plugin_client_secret;}
		$this->setGapiValues(array( 'client_id' => $lrdata['client_id'], 'client_secret'  => $lrdata['client_secret'], 'code' => $lrdata['code']));
		$results = $this->gapi->getAccessToken();
		$this->set_database_setting(array('client_id'     => $lrdata['client_id'],
										  'client_secret' => $lrdata['client_secret'],
										  'access_token'  => $results['access_token'],
										  'token_type'    => $results['token_type'],
										  'expires_in'    => $results['expires_in'],
										  'refresh_token' => $results['refresh_token'],
										  'created_on'    => time()));
		$this->jsonOutput();
	}
	private function refreshToken(){
		if (($this->get_database_setting('created_on') + $this->get_database_setting('expires_in')) <=  time() ){
			$this->setGapiValues(array( 'client_id'     => $this->get_database_setting('client_id'),
										'client_secret' => $this->get_database_setting('client_secret'),
										'refresh_token' => $this->get_database_setting('refresh_token')));
			$results = $this->gapi->refreshAccessToken();
			$this->set_database_setting(array('access_token'  => $results['access_token'],
											  'token_type'    => $results['token_type'],
											  'expires_in'    => $results['expires_in'],
											  'created_on'    => time()));
			$this->purgeCache();
		}
		$this->setGapiValues(array('access_token' => $this->get_database_setting('access_token')));
	}
	

	public function setMeasurementID($lrdata){
		$data = DataStore::get_from_cache($this->cachePrefix, md5('accountSummaries')."_output", $this->cacheTime);
		$selectedDataStream = array();
		
		if (!empty($data['accountSummaries']) && is_array($data['accountSummaries'])){
			foreach ($data['accountSummaries'] as $account){
				if ($account['id'] == $lrdata['account_id']){
					$selectedDataStream['account_id'] = $account['id'];
					foreach ($account['properties'] as $property){
						if ($property['id'] == $lrdata['property_id']){
							$selectedDataStream['property_id'] = $property['id'];
							$selectedDataStream['property_timezone'] = $property['timeZone'];
							foreach ($property['dataStreams'] as $dataStream){
								if ($dataStream['id'] == $lrdata['datastream_id']){
									$selectedDataStream['datastream_id'] = $dataStream['id'];
									$selectedDataStream['measurementId'] = $dataStream['measurementId'];
									break;
								}
							}
							break;
						}
					}
					break;	
				}
			}
		}
		
		
		if(empty($selectedDataStream['account_id'])){$this->errors[] = "Invalid Account ID";}
		if(empty($selectedDataStream['property_id'])){$this->errors[] = "Invalid Property ID";}
		if(empty($selectedDataStream['datastream_id'])){$this->errors[] = "Invalid Data Stream ID";}
		if(empty($selectedDataStream['measurementId'])){$this->errors[] = "Invalid Measurement ID";}
		if (empty($this->errors)){
			$this->set_database_setting(array('account_id'        => $selectedDataStream['account_id'],
											  'property_id'       => $selectedDataStream['property_id'],
											  'datastream_id'     => $selectedDataStream['datastream_id'],
											  "measurementId"     => $selectedDataStream['measurementId']));
								 
			if(!empty($lrdata['enable_ga4_tracking']) && $lrdata['enable_ga4_tracking'] === "on"){
				$this->set_database_setting(array('enable_ga4_tracking'  => 'on'));
			}else{
				$this->set_database_setting(array('enable_ga4_tracking'  => 'off'));
			}
			
			if(!empty($lrdata['enable_ecommerce_graph']) && $lrdata['enable_ecommerce_graph'] === "on"){
				$this->set_database_setting(array('enable_ecommerce_graph'  => 'on'));
			}else{
				$this->set_database_setting(array('enable_ecommerce_graph'  => 'off'));
			}			
			$this->purgeCache();
		}
		$this->jsonOutput();
	}

	public function settingsReset(){
		DataStore::reset_settings();
		$this->purgeCache();
		$this->output = array("setup" => 1);
		$this->jsonOutput();
	}	

	public function setDateRange($start_date, $end_date){
		if (($this->get_session_setting('start_date') != $start_date) || ($this->get_session_setting('end_date') != $end_date)){
			$this->set_session_setting(array('start_date' => $start_date, 'end_date' => $end_date));
			$this->purgeCache();
		}
	}

	public function setSystemTimeZone($systemTimeZone){
		$this->systemTimeZone = $systemTimeZone; 
	}	

    private function purgeCache(){
		if ($this->cache){
			DataStore::purge_cache($this->cachePrefix);
		}
	}	

	private function get_database_setting($name){
		return DataStore::database_get("settings",$name);
	}
	
	private function get_session_setting($name){
		return DataStore::session_get("settings",$name);
	}
	
	
	private function set_database_setting($settings){
		foreach ($settings as $name => $value){
			DataStore::database_set("settings",$name,$value);
		}
	}

	private function set_session_setting($settings){
		foreach ($settings as $name => $value){
			DataStore::session_set("settings",$name,$value);
		}
	}
	
	
	private function setGapiValues($kvPairs){
		foreach ($kvPairs as $key => $val){
			$this->gapi->$key = $val;
		}
	}

	private function html_escape(&$item){
		$item = htmlspecialchars($item);
	}

	private function calculateAverage($arr){
		$average = 0;
		$arr = array_filter($arr);
		if(count($arr)) {
			$average = array_sum($arr)/count($arr);
		}
		return $average;
	}
	
	private function shorten($number){
		$suffix = ['', 'K', 'M', 'B', 'T', 'Qa', 'Qi'];
		$precision = 1;
		for($i = 0; $i < count($suffix); $i++){
			$divide = $number / pow(1000, $i);
			if($divide < 1000){
				return round($divide, $precision).$suffix[$i];
				break;
			}
		}
	}
	
	private function jsonOutput(){
		@ini_set('precision', 14);
		@ini_set('serialize_precision', 14);		
		if (empty($this->errors)){
			if ($this->cached){ $this->output['cached'] = "true";}
			if ($this->cachedOutput){ $this->output['cachedOutput'] = "true";}
			$this->output['system_timezone'] = $this->systemTimeZone;
			$this->output['start'] = $this->get_session_setting('start_date');			
			$this->output['end'] = $this->get_session_setting('end_date');
			$this->output['status'] = "done";
			OutputHandler::jsonOutput($this->output);
		}else{ ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('Something went wrong .. please contact an administrator', 'lara-google-analytics'),100001,$this->errors);  }
		
		exit();
	}	
	
}
?>