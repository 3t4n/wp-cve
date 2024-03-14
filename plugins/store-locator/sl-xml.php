<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sl_xml_out($buff) {
	preg_match("@<markers>.*<\/markers>@s", $buff, $the_xml);
	//$the_xml[0]=preg_replace("@\n@","",$the_xml[0]);
	return $the_xml[0];
}
if (empty($_GET['debug'])) {
	ob_start("sl_xml_out");
}
header("Content-type: text/xml");
//Last save: 12/4/18 5:04 pm
/*
$username=DB_USER;
$password=DB_PASSWORD;
$database=DB_NAME;
$host=DB_HOST;

// Opens a connection to a MySQL server
$connection=mysql_connect ($host, $username, $password);
if (!$connection) { die('Not connected : ' . mysql_error()); }

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
mysql_query("SET NAMES utf8");
if (!$db_selected) { die ('Can\'t use db : ' . mysql_error());}
*/

//Removing any vars never intended for $_GET
$sl_ap_xml = array("sl_custom_fields", "sl_xml_columns");
foreach ($sl_ap_xml as $value){ if (!empty($_GET[$value])){ unset($_GET[$value]); } }

$sl_custom_fields = (!empty($sl_xml_columns))? ", ".implode(", ", $sl_xml_columns) : "" ;

if (!empty($_GET)) { $_sl = $_GET; unset($_GET['mode']); unset($_GET['lat']); unset($_GET["lng"]); unset($_GET["radius"]); unset($_GET["edit"]);}
$_GET=array_filter($_GET); //removing any empty $_GET items that may disrupt query

$sl_param_where_clause="";
$sl_param_order_clause="";
if (function_exists("do_sl_hook")){ do_sl_hook("sl_xml_query"); }

$num_initial_displayed=(trim($sl_vars['num_initial_displayed'])!="" && preg_match("@^[0-9]+$@", $sl_vars['num_initial_displayed']))? $sl_vars['num_initial_displayed'] : "25";

if (!empty($_sl['mode']) && $_sl['mode']=='gen') {
	// Get parameters from URL
	$center_lat = $_sl['lat'];
	$center_lng = $_sl['lng'];
	$radius = $_sl['radius'];
	
	$multiplier=3959;
	$multiplier=($sl_vars['distance_unit']=="km")? ($multiplier*1.609344) : $multiplier;
	
	$sl_param_order_clause = (empty($sl_param_order_clause))? "ORDER BY sl_distance" : $sl_param_order_clause.", sl_distance ASC"; //auto-append 'sl_distance' to clause at the end since it can't be done via sl_param_order_clause mod (will cause errors when loading locations by default). v3.93

	// Select all the rows in the markers table
	$query = $wpdb->prepare(
	"SELECT sl_address, sl_address2, sl_store, sl_city, sl_state, sl_zip, sl_latitude, sl_longitude, sl_description, sl_url, sl_hours, sl_phone, sl_fax, sl_email, sl_image, sl_tags".
	" %s,".
	" ( %f * acos( cos( radians('%s') ) * cos( radians( sl_latitude ) ) * cos( radians( sl_longitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( sl_latitude ) ) ) ) AS sl_distance".
	" FROM ".SL_TABLE.
	" WHERE sl_store<>'' AND sl_longitude<>'' AND sl_latitude<>''".
	" %s".
	" HAVING sl_distance < '%s'".
	" %s".
	" LIMIT %d",
	$sl_custom_fields,
	$multiplier,
	$center_lat,
	$center_lng,
	$center_lat,
	$sl_param_where_clause,
	$radius,
	$sl_param_order_clause,
	$num_initial_displayed); //die($query);
} else {
	// Select all the rows in the markers table
	$query =  $wpdb->prepare(
	"SELECT sl_address, sl_address2, sl_store, sl_city, sl_state, sl_zip, sl_latitude, sl_longitude, sl_description, sl_url, sl_hours, sl_phone, sl_fax, sl_email, sl_image, sl_tags".
	" %s".
	" FROM ".SL_TABLE.
	" WHERE sl_store<>'' AND sl_longitude<>'' AND sl_latitude<>''".
	" %s".
	" %s".
	" LIMIT %d",
	$sl_custom_fields,
	$sl_param_where_clause,
	$sl_param_order_clause,
	$num_initial_displayed); //die($query);
}

//die($query);
$result = $wpdb->get_results($query, ARRAY_A);

// Start XML file, echo parent node
echo "<markers>\n";
// Iterate through the rows, printing XML nodes for each
foreach ($result as $row){
  $addr2=(trim($row['sl_address2'])!="")? " ".sl_parseToXML($row['sl_address2']) : "" ;
  $row['sl_distance']=(!empty($row['sl_distance']))? $row['sl_distance'] : "" ;
  $row['sl_url']=(!sl_url_test($row['sl_url']) && trim($row['sl_url'])!="")? "http://".$row['sl_url'] : $row['sl_url'] ;
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . sl_parseToXML($row['sl_store']) . '" ';
  echo 'address="' . sl_parseToXML($row['sl_address']) .$addr2. ', '. sl_parseToXML($row['sl_city']). ', ' .sl_parseToXML($row['sl_state']).' ' .sl_parseToXML($row['sl_zip']).'" ';
  echo 'street="' . sl_parseToXML($row['sl_address']) . '" ';  //should've been sl_street in DB
  echo 'street2="' . sl_parseToXML($row['sl_address2']) . '" '; //should've been sl_street2 in DB
  echo 'city="' . sl_parseToXML($row['sl_city']). '" ';
  echo 'state="' . sl_parseToXML($row['sl_state']). '" ';
  echo 'zip="' . sl_parseToXML($row['sl_zip']). '" ';
  echo 'lat="' . sl_parseToXML($row['sl_latitude']) . '" ';
  echo 'lng="' . sl_parseToXML($row['sl_longitude']) . '" ';
  echo 'distance="' . sl_parseToXML($row['sl_distance']) . '" ';
  echo 'description="' . sl_parseToXML($row['sl_description']) . '" ';
  echo 'url="' . sl_parseToXML($row['sl_url']) . '" ';
  echo 'hours="' . sl_parseToXML($row['sl_hours']) . '" ';
  echo 'phone="' . sl_parseToXML($row['sl_phone']) . '" ';
  echo 'fax="' . sl_parseToXML($row['sl_fax']) . '" ';
  echo 'email="' . sl_parseToXML($row['sl_email']) . '" ';
  echo 'image="' . sl_parseToXML($row['sl_image']) . '" ';
  echo 'tags="' . sl_parseToXML($row['sl_tags']) . '" ';
  if (!empty($sl_xml_columns)){ 
  $alrdy_used=array('name', 'address', 'street', 'street2', 'city', 'state', 'zip', 'lat', 'lng', 'distance', 'description', 'url', 'hours', 'phone', 'fax', 'email', 'image', 'tags');
  	foreach($sl_xml_columns as $key=>$value) {
  		if (!in_array($value, $alrdy_used)) { //can't have duplicate property names in xml
	  		$row[$value]=(!isset($row[$value]))? "" : $row[$value] ;
  			 echo "$value=\"" . sl_parseToXML($row[$value]) . "\" ";
  			 $alrdy_used[]=$value;
  		}
  	}
  }
  echo "/>\n";
}

// End XML file
echo "</markers>\n";
if (empty($_GET['debug'])) {
	ob_end_flush();
}

//var_dump($_GET);
//print_r($sl_xml_columns); die();
//die($query);
//var_dump($sl_param_where_clause); die;
?>