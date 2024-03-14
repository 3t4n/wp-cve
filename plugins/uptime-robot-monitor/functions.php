<?php defined('ABSPATH') or die("No script kiddies please!");

function urpro_custmonitorcache($id,$day){
  global $wpdb;
  $table_name = $wpdb->base_prefix . 'urpro';
	$daytime = current_time('timestamp') - bcmul("86400",$day);
  $time = time() - urpro_data("refresh","no");
  $key = 'cache-'.$day.'-'.$id;
  $check = $wpdb->get_results ( "SELECT id FROM $table_name WHERE ur_key = '$key' AND time > '$time'");
  if(count($check) == 0){

	$curl = wp_remote_post('https://api.uptimerobot.com/v2/getMonitors', array(

	'method' => 'POST', 'timeout' => 45, 'redirection' => 5, 'httpversion' => '1.0', 'blocking' => true, 'headers' => array('Cache-Control' => 'no-cache'),

	'body' => array('api_key' => urpro_data("apikey","no"), 'monitors'=>$id, 'custom_uptime_ranges'=>$daytime.'_'.current_time('timestamp'))

	));


		$response = json_decode($curl['body'], true);

			if ( is_wp_error( $curl ) ) { return "connection error"; }

			elseif($response['stat'] == "fail"){ return "results incorrect";  } 

			elseif($response['stat'] == "ok"){
				$wpdb->insert($table_name, array('ur_value' => json_encode($response), 'ur_key' => $key, 'time' => time()));
				return $response;
			}else{ return "connection error"; }


  }else{ return json_decode($check[0]->ur_value, true); }
}

function urpro_admin_notice(){

	global $wpdb;
	$table_name = $wpdb->base_prefix . 'urpro';
	$data = $wpdb->get_results ( "SELECT ur_value FROM $table_name WHERE ur_key = 'errormessage'");

	if(count($data) == 1){
		echo '<div class="notice notice-error is-dismissible"><p>'.$data[0]->ur_value.'</p></div>';
		$sql = "DELETE FROM ".$table_name." WHERE ur_key = 'errormessage'";
		$wpdb->query($sql);
	}

}

function urpro_siteid(){
  if(isset($GLOBALS['urpro_glob_siteid'])){
			return $GLOBALS['urpro_glob_siteid'];
  }else{
	global $wpdb;
	$table_name = $wpdb->base_prefix . 'urpro';
	$data = $wpdb->get_results ( "SELECT ur_value FROM $table_name WHERE ur_key = 'multisite'");

		 if($data[0]->ur_value == 1){
			$GLOBALS['urpro_glob_siteid'] = "0";
			return 0;
		 }else{
			$GLOBALS['urpro_glob_siteid'] = get_current_blog_id();
			return get_current_blog_id();
		 }
  }
}

function urpro_data($urkey,$base){
  if(isset($GLOBALS['urpro_glob_data_'.$urkey.$base])){
			return $GLOBALS['urpro_glob_data_'.$urkey.$base];
  }else{
	global $wpdb;
	if($base == "yes"){ $siteid = 0; }else{ $siteid = urpro_siteid(); }
	$table_name = $wpdb->base_prefix . 'urpro';
	$data = $wpdb->get_results ( "SELECT ur_value FROM $table_name WHERE ur_key = '$urkey' AND siteid = '$siteid' ORDER BY id DESC LIMIT 1");
		if(count($data) == 0){
			return "";
		}elseif(count($data) == 1){
			if($data[0]->ur_value == ""){
				$GLOBALS['urpro_glob_data_'.$urkey.$base] = " ";
				return " ";
			}else{
				$GLOBALS['urpro_glob_data_'.$urkey.$base] = $data[0]->ur_value;
				return $data[0]->ur_value;
			}
		}else{
				$returndata = array();
				foreach($data as $return){
					$returndata[] = $return->ur_value;
				}
			$GLOBALS['urpro_glob_data_'.$urkey.$base] = $returndata;
			return $returndata;
		}
  }
}

function urpro_datatime($urkey,$base){
	global $wpdb;
	if($base == "yes"){ $siteid = 0; }else{ $siteid = urpro_siteid(); }
	$table_name = $wpdb->base_prefix . 'urpro';
	$data = $wpdb->get_results ( "SELECT time FROM $table_name WHERE ur_key = '$urkey' AND siteid = '$siteid'");
		if(count($data) == 0){
			return "";
		}elseif(count($data) == 1){
			return $data[0]->time;
		}
}

function urpro_monitorcache($id){
 if(isset($GLOBALS['urpro_glob_cache_'.$id])){
	return $GLOBALS['urpro_glob_cache_'.$id];
 }else{
  global $wpdb;
  $table_name = $wpdb->base_prefix . 'urpro';
	$day1 = current_time('timestamp') - bcmul("86400","1");
	$day2 = current_time('timestamp') - bcmul("86400","7");
	$day3 = current_time('timestamp') - bcmul("86400","30");
	$day4 = current_time('timestamp') - bcmul("86400","365");
	$days = $day1.'_'.current_time('timestamp').'-'.$day2.'_'.current_time('timestamp').'-'.$day3.'_'.current_time('timestamp').'-'.$day4.'_'.current_time('timestamp');
  $time = time() - urpro_data("refresh","no");
  $key = 'cache-'.$id;
  $check = $wpdb->get_results ( "SELECT ur_value FROM $table_name WHERE ur_key = '$key' AND time > '$time'");
  if(count($check) == 0){

	$curl = wp_remote_post('https://api.uptimerobot.com/v2/getMonitors', array(

	'method' => 'POST', 'timeout' => 45, 'redirection' => 5, 'httpversion' => '1.0', 'blocking' => true, 'headers' => array('Cache-Control' => 'no-cache'),

	'body' => array('api_key' => urpro_data("apikey","no"), 'response_times'=>'1', 'logs'=>'1', 'monitors'=>$id, 'custom_uptime_ranges'=>$days)

	));

		$response = json_decode($curl['body'], true);

			if ( is_wp_error( $curl ) ) { return "Connection error"; }
    		elseif (is_null($response)) { return "Connection error"; }

			elseif($response['stat'] == "fail"){ return "Results incorrect";  } 

			elseif($response['stat'] == "ok"){
				$wpdb->insert($table_name, array('ur_value' => json_encode($response), 'ur_key' => $key, 'time' => time()));
				$GLOBALS['urpro_glob_cache_'.$id] = $response;
				return $response;
			}else{ return "Connection error"; }

  }else{ $returnval = json_decode($check[0]->ur_value, true); $GLOBALS['urpro_glob_cache_'.$id] = $returnval; return $returnval; }
 }
}

function urpro_monitordata($value,$id){
	$data = urpro_monitorcache($id);
	$data = $data['monitors']['0'];
	if(is_array($data) && isset($data[$value])){
      if(is_array($data[$value])){
		return (array)$data[$value];
      }else{
		return $data[$value];
      }
	}else{
		return "0";
	}
}

function urpro_api_monitorsloop($offset,$limit){
	
	$curl = wp_remote_post('https://api.uptimerobot.com/v2/getMonitors', array(

	'method' => 'POST', 'timeout' => 45, 'redirection' => 5, 'httpversion' => '1.0', 'blocking' => true, 'headers' => array('Cache-Control' => 'no-cache'),

	'body' => array('api_key' => urpro_data("apikey","no"), 'limit'=>$limit, 'offset'=>$offset)

	));

			$response = json_decode($curl['body'], true);

				if ( is_wp_error( $curl ) ) { return "1"; }

				elseif($response['stat'] == "fail"){ return "0"; }

				elseif($response['stat'] == "ok"){
						$addid = array();
					foreach($response['monitors'] as $monitor){
						$addid[] = $monitor['id'];
					}
						return $addid;
				}

				else{ return "1"; }

}

function urpro_api_monitorlist(){

 $refreshtime = time()-604800;
 if(urpro_datatime('monitorlist','no') < $refreshtime or count(json_decode(urpro_data('monitorlist','no'),true)) == 0){

		$limit = "45";

	$curl = wp_remote_post('https://api.uptimerobot.com/v2/getMonitors', array(

	'method' => 'POST', 'timeout' => 45, 'redirection' => 5, 'httpversion' => '1.0', 'blocking' => true, 'headers' => array('Cache-Control' => 'no-cache'),

	'body' => array('api_key' => urpro_data("apikey","no"), 'limit'=>$limit)

	));

		$response = json_decode($curl['body'], true);

			if ( is_wp_error( $curl ) ) { $updatelist = 1; }

			elseif($response['stat'] == "fail"){ $updatelist = array(); }

			elseif($response['stat'] == "ok"){
				$count = $response['pagination']['total'];
				$rounds = range(1, bcdiv($count,$limit)+1);
				$offset = 0;
					$monitorlist = array();
				foreach($rounds as $i) {
					$monitorlist[] = urpro_api_monitorsloop($offset,$limit);
					$offset = $offset + $limit;
				}	$updatelist = $monitorlist;
			}else{ $updatelist = array(); }

  if($updatelist != 1 OR 0){
	$newlist = array();
		foreach($updatelist as $list){ foreach($list as $id){
			$newlist[] = $id;
		}}
	global $wpdb;
	$siteid = urpro_siteid();
	$table_name = $wpdb->base_prefix . 'urpro';
	if(urpro_data("monitorlist","no") != ""){
		$wpdb->update($table_name, array('ur_value' => json_encode($newlist), 'time'=>time()), array('siteid' => $siteid, 'ur_key' => 'monitorlist', ));
	}else{
		$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'monitorlist', 'ur_value' => json_encode($newlist), 'time'=>time()));
	}
	return $newlist;
  }else{ return $updatelist; }
 }else{ return json_decode(urpro_data('monitorlist','no'),true); }
}

function urpro_sorter($key) {
	return function ($b, $a) use ($key) {
        	return strnatcmp($a[$key], $b[$key]);
	};
}

function urpro_sorterasc($key) {
	return function ($a, $b) use ($key) {
        	return strnatcmp($a[$key], $b[$key]);
	};
}

function urpro_sortname($return){

	$order = array();
	foreach($return as $id){
		$order[] = array('friendly_name' => urpro_monitordata("friendly_name",$id), 'id' => $id);
	}
		usort($order,urpro_sorterasc('friendly_name'));
		$return = array();
	foreach($order as $monitor){
		$return[] = $monitor['id'];
	}
		return $return;
}

function urpro_sortoffline($return){

	if(urpro_data("offlinetop","no") == "1"){ if(!isset($_GET['page']) OR $_GET['page'] != "urpro-monitors"){

	$order = array();
	foreach($return as $id){
		$order[] = array('status' => urpro_monitordata("status",$id), 'id' => $id);
	}
		usort($order,urpro_sorter('status'));
		$return = array();
	foreach($order as $monitor){
		$return[] = $monitor['id'];
	}

	}}

		return $return;

}

function urpro_monitororder(){
 $refreshtime = time()-86400;;
 if(urpro_datatime('monitororder','no') < $refreshtime OR count(json_decode(urpro_data("monitororder","no"),true)) == 0){
		global $wpdb;
		$siteid = urpro_siteid();
		$table_name = $wpdb->base_prefix . 'urpro';
		$oldorder = json_decode(urpro_data("monitororder","no"),true);
	if(count((array)$oldorder) != 0){
		$checkorder = urpro_api_monitorlist();
		$returnlist = array();
			  foreach($oldorder as $monitor){
				if(in_array($monitor, $checkorder)){
					$returnlist[] = $monitor;
				}
			  }
		$wpdb->update($table_name, array('ur_value' => json_encode($returnlist), 'time' => time()), array('ur_key' => 'monitororder', 'siteid' => $siteid));
		$return = $returnlist;
	}else{
		$return = urpro_api_monitorlist();
		$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'monitororder', 'ur_value' => json_encode($return), 'time' => time()));
	}
		
		$return = urpro_sortoffline($return);
	return $return;
 }else{
	return urpro_sortoffline(json_decode(urpro_data("monitororder","no"),true));
 }		
}

function urpro_stylecolor($key){

	if(urpro_data($key,"no") != ""){
		return urpro_data($key,"no");
	}elseif($key == "style_font"){
		return "#333333";
	}elseif($key == "style_online"){
		return "#006600";
	}elseif($key == "style_offline"){
		return "#FF0000";
	}elseif($key == "style_paused"){
		return "#FFA500";
	}elseif($key == "style_chart"){
		return "#388e8e";
	}elseif($key == "style_100"){
		return "#006600";
	}elseif($key == "style_99999"){
		return "#328432";
	}elseif($key == "style_99899"){
		return "#6FA86F";
	}elseif($key == "style_99499"){
		return "#FFA500";
	}elseif($key == "style_99500"){
		return "#FF0000";
	}elseif(urpro_data($key,"no") == "" AND $key = "style_0"){
		return "#404040";
	}
}

function urpro_alter_brightness($colourstr, $steps) {
    $colourstr    = str_replace( '#', '', $colourstr );
    $steps  = max( -255, min( 255, $steps ) );
    if ( 3 == strlen( $colourstr ) ) {
        $colourstr    = str_repeat( substr( $colourstr, 0, 1 ), 2 ) . str_repeat( substr( $colourstr, 1, 1 ), 2 ) . str_repeat( substr( $colourstr, 2, 1 ), 2 );
    }
    $rgb=array(substr($colourstr,0,2),  substr($colourstr,2,2), substr($colourstr,4,2));
    for($i = 0; $i< count($rgb); $i++){
      $rgb[$i] = str_pad(dechex(max(0,min(255, hexdec($rgb[$i]) + $steps))),2,"0",STR_PAD_LEFT) ;
    }
    return '#'.implode('', $rgb);
}

function urpro_getstyle($value,$type){
	if($type == "status"){
		$returns = array("0"=>"style_paused","1"=>"style_font","2"=>"style_online","8"=>"style_offline","9"=>"style_offline",);
		return 'style="font-weight: bold; color: '.urpro_stylecolor($returns[$value]).';"';
	}elseif($type == "dashstatus"){
		$returns = array("0"=>"style_paused","1"=>"style_font","2"=>"style_online","8"=>"style_offline","9"=>"style_offline",);
		if($value != 2){ return 'style="font-weight: bold; color: '.urpro_stylecolor($returns[$value]).';"';
		}else{ return 'style="font-weight: bold;"';
		}
	}elseif($type == "log"){
		$returns = array("1"=>"style_offline","2"=>"style_online","98"=>"style_font","99"=>"style_paused",);
		return urpro_alter_brightness(urpro_stylecolor($returns[$value]),200);
	}elseif($type == "uptime"){	
		$return = '<td style="font-weight: bold; color: ';
			if($value == "100.000"){ $return .= urpro_stylecolor("style_100");
			}elseif($value == "99.999"){ $return .= urpro_stylecolor("style_99999");
			}elseif($value > "99.899"){ $return .= urpro_stylecolor("style_99899");
			}elseif($value > "99.499"){ $return .= urpro_stylecolor("style_99499");
			}elseif($value == "0"){ $return .= urpro_stylecolor("style_0");
			}elseif($value < "99.500"){ $return .= urpro_stylecolor("style_99500");
			}else{ $return .= urpro_stylecolor("style_font"); }
		$return .= '">'.floatval($value).'%</td>';
		return $return;
	}
}

function urpro_outputs($value,$type){

	if($type == "status"){
		$returns = array("0"=> __('Paused', 'urpro'),"1"=> __('Not checked', 'urpro'),"2"=> __('Online', 'urpro'),"8"=> __('Seems offline', 'urpro'),"9"=> __('Offline', 'urpro'),"98"=> __('Started', 'urpro'),"99"=> __('Paused', 'urpro'));
		return $returns[$value];
	}elseif($type == "log"){
		$returns = array("1"=> __('Offline', 'urpro'),"2"=> __('Online', 'urpro'),"98"=> __('Started', 'urpro'),"99"=> __('Paused', 'urpro'));
		return $returns[$value];
	}elseif($type == "type"){
		$returns = array("1"=> __('HTTP(s)', 'urpro'),"2"=> __('Keyword', 'urpro'),"3"=> __('Ping', 'urpro'));
		return $returns[$value];
	}elseif($type == "sub_type"){
		$returns = array("1"=> __('HTTP', 'urpro'),"2"=> __('HTTPS', 'urpro'),"3"=> __('FTP', 'urpro'),"4"=> __('SMTP', 'urpro'),"5"=> __('POP3', 'urpro'),"6"=> __('IMAP', 'urpro'),"99"=> __('PORT', 'urpro'));
		return $returns[$value];
	}elseif($type == "uptimetitle"){
		if($value == "1"){ $return = __('Last 24H','urpro');
		}elseif($value == "365"){ $return = __('Last year','urpro');
		}else{ $return = $value.' '.__('days','urpro'); }
		return $return;
	}
}

function urpro_sectotime($sec,$type){
	$dtF = new \DateTime('@0');
	$dtT = new \DateTime("@$sec");
		if($sec < 60){
			if($type == "short"){
				return $dtF->diff($dtT)->format(' %s '. __('s', 'urpro'));
			}elseif($type == "medium"){
				return $dtF->diff($dtT)->format(' %s '. __('sec', 'urpro'));
			}elseif($type == "long"){
				return $dtF->diff($dtT)->format(' %s '. __('seconds', 'urpro'));
			}elseif($type == "verylong"){
				return $dtF->diff($dtT)->format(' %s '. __('seconds', 'urpro'));
			}
		}elseif($sec < 3600){
			if($type == "short"){
				return $dtF->diff($dtT)->format('%i '.__('m', 'urpro').', %s '. __('s', 'urpro'));
			}elseif($type == "medium"){
				return $dtF->diff($dtT)->format('%i '.__('min', 'urpro').', %s '. __('sec', 'urpro'));
			}elseif($type == "long"){
				return $dtF->diff($dtT)->format('%i '.__('min', 'urpro').' '.__('and', 'urpro').' %s '. __('sec', 'urpro'));
			}elseif($type == "verylong"){
				return $dtF->diff($dtT)->format('%i '.__('minutes', 'urpro').' '.__('and', 'urpro').' %s '. __('seconds', 'urpro'));
			}
		}elseif($sec < 86400){
			if($type == "short"){
				return $dtF->diff($dtT)->format('%h'.__('h', 'urpro').', %i'.__('m', 'urpro'));
			}elseif($type == "medium"){
				return $dtF->diff($dtT)->format('%h'.__('h', 'urpro').', %i'.__('m', 'urpro'));
			}elseif($type == "long"){
				return $dtF->diff($dtT)->format('%h '.__('hours', 'urpro').', %i '.__('min', 'urpro').' '.__('and', 'urpro').' %s '. __('sec', 'urpro'));
			}elseif($type == "verylong"){
				return $dtF->diff($dtT)->format('%h '.__('hours', 'urpro').', %i '.__('minutes', 'urpro').' '.__('and', 'urpro').' %s '. __('seconds', 'urpro'));
			}
		}else{
			if($type == "short"){
				return $dtF->diff($dtT)->format('%a'.__('d', 'urpro').', %h'.__('h', 'urpro').', %i'.__('m', 'urpro'));
			}elseif($type == "medium"){
				return $dtF->diff($dtT)->format('%a '.__('days', 'urpro').', %h'.__('h', 'urpro').', %i'.__('m', 'urpro'));
			}elseif($type == "long"){
				return $dtF->diff($dtT)->format('%a '.__('days', 'urpro').', %h '.__('hours', 'urpro').', %i '.__('min', 'urpro').' '.__('and', 'urpro').' %s '. __('sec', 'urpro'));
			}elseif($type == "verylong"){
				return $dtF->diff($dtT)->format('%a '.__('days', 'urpro').', %h '.__('hours', 'urpro').', %i '.__('minutes', 'urpro').' '.__('and', 'urpro').' %s '. __('seconds', 'urpro'));
			}
		}
}

function urpro_timezone($time) {
	return ($time+(60 * 60 * get_option('gmt_offset')));
}
