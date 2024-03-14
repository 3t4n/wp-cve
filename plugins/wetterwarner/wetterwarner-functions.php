<?php
/*
* Wetterwarner Funktionen
* Author: Tim Knigge
* https://it93.de/projekte/wetterwarner/
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function wetterwarner_xml($feed_url){
	$use_errors = libxml_use_internal_errors(true);
	if( !$xml = simplexml_load_file($feed_url))
		throw new Exception( __('Error reading the XML file. Please check path!', 'wetterwarner'));
	libxml_clear_errors();
	libxml_use_internal_errors($use_errors);
		/* Prüfen ob Feed ID gültig */
	if( !isset($xml->channel[0]->item) ){
		throw new Exception(__('Feed ID could not be found. Check configuration!', 'wetterwarner') . $feed_url);
	}
	return $xml;
}

function wetterwarner_meldungen($xml_data, $instance){
	/* Feed einlesen */
	$feed = array();
	
	foreach($xml_data->channel[0]->item as $item) {

	$feed[] = array(
        'title'        => (string) $item->title,
        'description'  => (string) $item->description,
        'link'         => (string) $item->link,
        'date'         => date('d.m.Y H:i', strtotime((string) $item->pubDate))
		);
	}
	return $feed;
}

function wetterwarner_wetterkarte($instance, $args, $region) {

    if($instance['ww_kartenbundeslandURL'] && $instance['ww_kartenbundeslandURL'] != ""){
		
		if (strpos($instance['ww_kartenbundeslandURL'], "https://") === 0) {
			$parsedUrl = parse_url($instance['ww_kartenbundeslandURL']);
			$filename = basename($parsedUrl['path']);
		} else {
			$filename = $instance['ww_kartenbundeslandURL'];
		}
		$karten_url = plugin_dir_url(__FILE__) . "tmp/" . $filename;
		$karten_url .= '?nocache=' . time();
		if (isset($instance['ww_meldungen_verlinken']) && $instance['ww_meldungen_verlinken']) {
			$karte = '<br><a href="https://www.wettergefahren.de/index.html" target="_blank" title="Aktuelle Wetterwarnungen für ' . $region . '"><img src="' . $karten_url . '" style="border: 0;" alt="Aktuelle Wetterwarnungen für ' . $region . '" width="' . $instance["ww_kartengroesse"] . '%"/></a>';
		} 	else {
        $karte = '<br><img src="' . $karten_url . '" style="border: 0;" alt="Aktuelle Wetterwarnungen für ' . $region . '" width="' . $instance["ww_kartengroesse"] . '%"/>';
		}
	}
	else{
		$karte = '<div class="error"><p>Es ist ein Fehler aufgetreten. Karten URL konnte nicht korrekt abgerufen werden.</p></div>';
	}
  
    return $karte;
}

function wetterwarner_feed_link($instance, $parameter){
	if(strpos($instance['ww_text_feed'], '%region%'))
		$feed_title = str_replace('%region%', $parameter->region, $instance['ww_text_feed']);
	else
		$feed_title = $instance['ww_text_feed'];
	$feedlink = '<p class="ww_wetterfeed"><span class="fa fa-rss"></span><a href="' . $parameter->feed_url . '"> ' . $feed_title . '</a></p>';
	return $feedlink;
}

function wetterwarner_quelle($value){
	$quelle = explode("Quelle:", $value['description']);
	$quelle = explode("<br />", $quelle[1]);
	$quelle = '<br><span class="ww_Quelle">Quelle: '.$quelle[0].'</span>';
	return $quelle;
}

function wetterwarner_gueltigkeit($value, $parameter){
	$gueltigkeit = explode($parameter->region, $value['description']);
	$gueltigkeit = explode("<br />", $gueltigkeit[1]);
	$gueltigkeit = '<br><span class="ww_Zeit">Gültig: '.$gueltigkeit[1].'</span>';
	return $gueltigkeit;
}

function wetterwarner_tooltip($text){
	$tooltip_code = 'onmouseover="popup(\' '.$text.' \')"';
	return $tooltip_code;
}

function enqueueStyleAndScripts(){
	wp_register_script( 'tooltip', plugin_dir_url( __FILE__ ) . 'js/nhpup_1.1.js', array( 'jquery' ), '1.1', false );
	wp_register_style('font-awesome', plugin_dir_url( __FILE__ ) . 'resources/font-awesome/css/font-awesome.min.css');
	wp_register_style( 'style-frontend',  plugin_dir_url( __FILE__ ) . 'css/style-frontend.css' );
	wp_register_style( 'weather-icons',  plugin_dir_url( __FILE__ ) . 'resources/weather-icons/css/weather-icons.min.css' );

	wp_enqueue_style('font-awesome');
	wp_enqueue_style('style-frontend');
	wp_enqueue_style('weather-icons');
	wp_enqueue_script( 'tooltip' );
}

function wetterwarner_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/options-general.php?page=wetterwarner' ) ) . '">' . __( 'Settings', 'wetterwarner' ) . '</a>'
	), $links );
	return $links;
}

function wetterwarner_icons($shorttitle){	
		$icon="";
		switch ($shorttitle){
			case "STURMBÖEN":
			case "WINDBÖEN":
			case "SCHWERE STURMBÖEN":
			$icon = "wi-cloudy-windy";
			break;
			case "GEWITTER":
			case "STARKES GEWITTER":
			$icon = "wi-storm-showers";
			break;
			case "GEWITTER":
			case "STARKES GEWITTER":
			case "SCHWERES GEWITTER":
			case "EXTREMES GEWITTER":
			$icon = "wi-thunderstorm";
			break;
			case "DAUERREGEN":
			$icon = "wi-rain";
			break;
			case "SCHNEEFALL":
			case "STARKER SCHNEEFALL":
			$icon = "wi-snow";
			break;
			case "GLATTEIS":
			case "FROST":
			$icon = "wi-snowflake-cold";
			break;
			case "STARKREGEN": 
			$icon = "wi-rain-wind";
			break;
			case "NEBEL": 
			$icon = "wi-fog";
			break;
			case "TAUWETTER": 
			$icon = "wi-horizon-alt";
			break;
			default:
			$icon = "wi-cloudy";
			}
		return "ww_icon wi ".$icon;
}

function wetterwarner_parameter($xml_data, $instance){
	/* Variablen deklarieren */
	$rss_title = explode("Warnregion:", $xml_data->channel->title);
	$title = (empty($instance['ww_widget_titel'])) ? '' : apply_filters('ww_widget_titel', $instance['ww_widget_titel']);
	$feed_title = $instance['ww_text_feed'];
	$einleitung = $instance['ww_einleitungstext'];
	
	/* Variablen durch Text ersetzen */
	$einleitung = str_replace('%region%', $rss_title[1], $instance['ww_einleitungstext']);
	$feed_title = str_replace('%region%', $rss_title[1], $instance['ww_text_feed']);		
	$title = str_replace('%region%', $rss_title[1], $title);
	
	$parameter = (object) array(
		'einleitung' 	=> $einleitung,
		'feed_title' 	=> $feed_title,
		'widget_title' 	=> $title,
		'region'  		=> $rss_title[1],
		'rss_title'    	=> $instance['ww_einleitungstext'],
		'feed_url'		=> 'https://wettwarn.de/rss/'.strtolower($instance['ww_feed_id']).'.rss'
		);
		
	if($instance['ww_feed_id'] =="100")
		$parameter->feed_url = "https://it93.de/projekte/wetterwarner/";
	return $parameter;
}

function wetterwarner_debug_info($debug_info){
	$options = get_option('wetterwarner_settings');
	$debug_info['wetterwarner'] = array(
        'label'    => __( 'Wetterwarner', 'wetterwarner' ),
        'fields'   => array(
			'ww_page_url' => array(
                'label'    => __( 'Page URL', 'wetterwarner' ),
                'value'   => site_url(),
                'private' => false,
            ),
			'ww_workp' => array(
                'label'    => __( 'work path', 'wetterwarner' ),
                'value'   => plugin_dir_path(__FILE__),
                'private' => false,
            ),
            'ww_tmp' => array(
                'label'    => __( 'Temp Folder writable', 'wetterwarner' ),
                'value'   => (is_writable(__DIR__ . '/tmp/') ? "true" : "false"),
                'private' => false,
            ),
			'ww_ini_get' => array(
                'label'    => __( 'PHP ini status', 'wetterwarner' ),
                'value'   => ini_get('allow_url_fopen') ? "true" : "false",
                'private' => false,
            ),
			'ww_bg1' => array(
                'label'    => __( 'background-color 1', 'wetterwarner' ),
                'value'   => $options['ww_farbe_stufe1'],
                'private' => false,
            ),
			'ww_bg2' => array(
                'label'    => __( 'background-color 2', 'wetterwarner' ),
                'value'   => $options['ww_farbe_stufe2'],
                'private' => false,
            ),
			'ww_bg3' => array(
                'label'    => __( 'background-color 3', 'wetterwarner' ),
                'value'   => $options['ww_farbe_stufe3'],
                'private' => false,
            ),
			'ww_bg4' => array(
                'label'    => __( 'background-color 4', 'wetterwarner' ),
                'value'   => $options['ww_farbe_stufe4'],
                'private' => false,
            ), 
        ),
    );
    return $debug_info;
}

function wetterwarner_meldung_hintergrund($value, $options){
	$stufe = explode("Stufe ", $value['description']);
	$stufe = explode(" ", $stufe[1]);
	switch ($stufe[0]){
		case "1":
		if( !isset( $options['ww_farbe_stufe1'] )) {
			$farbe = 'rgba(255,255,170,0.5)'; 
		}
		else{
			$farbe = $options['ww_farbe_stufe1'];
		}
		break;
		case "2":
		$farbe = $options['ww_farbe_stufe2'];
		if( !isset( $options['ww_farbe_stufe2'] )) {
			$farbe = 'rgba(255,218,188,0.5)'; 
		}
		else{
			$farbe = $options['ww_farbe_stufe2'];
		}
		break;
		case "3":
		$farbe = $options['ww_farbe_stufe3'];
		if( !isset( $options['ww_farbe_stufe3'] )) {
			$farbe = 'rgba(255,204,204,0.5)'; 
		}
		else{
			$farbe = $options['ww_farbe_stufe3'];
		}
		break;
		case "4":
		$farbe = $options['ww_farbe_stufe4'];
		if( !isset( $options['ww_farbe_stufe4'] )) {
			$farbe = 'rgba(198,155,198,0.5)'; 
		}
		else{
			$farbe = $options['ww_farbe_stufe4'];
		}
		break;
	}
	$hintergrund = "style=\"background-color:" . $farbe . "\"";
	return $hintergrund;
}


function wetterwarner_cache_refresh() {
	$path = __DIR__ ."/tmp/";
	array_map('unlink', glob( "$path*.webp"));
	array_map('unlink', glob( "$path*.rss"));
}


function wetterwarner_get_map_mapping(){
	
	return array(
		"Schleswig-Holstein" => "warning_map_shh.webp",
		"Hamburg" => "warning_map_shh.webp",
		"Niedersachsen" => "warning_map_nib.webp",
		"Bremen" => "warning_map_nib.webp",
		"Rheinland-Pfalz" => "warning_map_rps.webp",
		"Saarland" => "warning_map_rps.webp",
		"Berlin" => "warning_map_bbb.webp",
		"Brandenburg" => "warning_map_bbb.webp",
		"Nordrhein-Westfalen" => "warning_map_nrw.webp",
		"Sachsen" => "warning_map_sac.webp",
		"Sachsen-Anhalt" => "warning_map_saa.webp",
		"Thüringen" => "warning_map_thu.webp",
		"Bayern" => "warning_map_bay.webp",
		"Hessen" => "warning_map_hes.webp",
		"Mecklenburg-Vorpommern" => "warning_map_mvp.webp",
		"Baden-Württemberg" => "warning_map_baw.webp",
	);
}

function wetterwarner_get_file($url){
	$parsedUrl = parse_url($url);
	$filename = basename($parsedUrl['path']);
	$path = plugin_dir_path(__FILE__) . 'tmp/' . $filename;
	$response = wp_remote_get($url);
	if (!is_wp_error($response)) {
		$data = wp_remote_retrieve_body($response);
		file_put_contents($path, $data);
	}
}

function wetterwarner_data_update() {
	$options = get_option('widget_wetterwarner_widget');
	if (is_array($options)) {
		foreach ($options as $item) {
			if (isset($item['ww_feed_id'])) {
				$ww_feed_id_value = $item['ww_feed_id'];
				if($ww_feed_id_value == 100)
					$feed_url = 'https://api.it93.de/wetterwarner/100.rss';
				else
					$feed_url = 'https://wettwarn.de/rss/' . $ww_feed_id_value . '.rss';
				
				wetterwarner_get_file($feed_url);
			}
			if (isset($item['ww_kartenbundeslandURL'])){
				if (strpos($item['ww_kartenbundeslandURL'], "https://") === 0) {
					$parsedUrl = parse_url($item['ww_kartenbundeslandURL']);
					$filename = basename($parsedUrl['path']);
				} else {
					$filename = $item['ww_kartenbundeslandURL'];
				}
				wetterwarner_get_file('https://api.it93.de/wetterwarner/worker/files/' . $filename);
			}
		}
	}
}

function wetterwarner_activation() {
	wetterwarner_data_update();
	wp_schedule_event(time(), '10minutes', 'wetterwarner_data_update');
}

function wetterwarner_deactivation() {
	wp_clear_scheduled_hook('wetterwarner_data_update');
	wetterwarner_cache_refresh();
}

function wetterwarner_load_textdomain(){
	load_plugin_textdomain( 'wetterwarner', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

function wetterwarner_init_widget(){
	return register_widget('Wetterwarner_Widget');
}

function wetterwarner_upgrade_completed( $upgrader_object, $options ) {
	$name = plugin_basename( __FILE__ );
	    if ( isset( $options['action'] ) && isset( $options['type'] ) && isset( $options['plugins'] ) && $options['action'] == 'update' && $options['type'] == 'plugin' && is_array( $options['plugins'] ) ) {
		foreach( $options['plugins'] as $plugin ) {
			if( $plugin == $name ) {
				wetterwarner_activation();
			}
		}
	}
}
function wetterwarner_add_konfig_check( $tests ) {
    $tests['direct']['wetterwarner'] = array(
        'label' => __( 'Konfig Check' ),
        'test'  => 'wetterwarner_konfig_check',
    );
    return $tests;
}

function wetterwarner_cron_schedule(){
	 $schedules['10minutes'] = array(
        'interval' => 600,
        'display'  => __('every 10 minutes')
    );
    return $schedules;
}

function wetterwarner_konfig_check( ){
	// Check Temp Ordner
	$result = array(
        'label'       => __( 'Wetterwarner can work properly' ),
        'status'      => 'good',
        'badge'       => array(
            'label' => __( 'Wetterwarner' ),
            'color' => 'blue',
        ),
        'description' => sprintf(
            '<p>%s</p>',
            __( 'Your page has passed function tests' )
        ),
        'actions'     => '',
        'test'        => 'wetterwarner',
    );
 
    if(!is_writable(__DIR__ . '/tmp/')){
        $result['status'] = 'critical';
        $result['label'] = __( 'Wetterwarner Permission error' );
		$result['badge'] = array(
            'label' => __( 'Wetterwarner' ),
            'color' => 'red',
        );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( '/tmp/ folder not writable, wetterwarner cache function cannot be activated, but is needed!' )
        );
    }
	
	if(!ini_get('allow_url_fopen')){
        $result['status'] = 'critical';
        $result['label'] = __( 'Wetterwarner PHP ini error' );
		$result['badge'] = array(
            'label' => __( 'Wetterwarner' ),
            'color' => 'red',
        );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( 'Background: In the php.ini file settings can be made that affect the behavior of PHP and thus the behavior of PHP scripts on the server. Important for this plugin is that the setting "allow_url_fopen " is ON. Otherwise Weatherwarner can not access the external RSS feed or the weather map. Please ask your provider how you can change the setting in php.ini.' )
        );
    }
    return $result;
}
?>