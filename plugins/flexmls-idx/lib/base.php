<?php

class flexmlsConnect {


  function __construct() {
  }


	static function initial_init(){
		// Code moved - eventual cleanup
	}

	static function in_dev_mode() {
		if( defined( 'FMC_DEV' ) && FMC_DEV ){
			return true;
		}
		return false;
	}


	static function widget_init(){
		// Load all of the widgets we need for the plugin.
		global $fmc_widgets;
		$SparkAPI = new \SparkAPI\Core();
		$auth_token = $SparkAPI->generate_auth_token();
		if( $auth_token && $fmc_widgets ){
			foreach( $fmc_widgets as $class => $wdg ){
				if( file_exists( FMC_PLUGIN_DIR . 'components/' . $wdg[ 'component' ] ) ){
					require_once( FMC_PLUGIN_DIR . 'components/' . $wdg[ 'component' ] );
					// All widgets require a "key" or auth token so this will be removed
					/*
					$meets_key_reqs = false;
					if ($wdg['requires_key'] == false || ($wdg['requires_key'] == true && flexmlsConnect::has_api_saved())) {
						$meets_key_reqs = true;
					}
					*/
					if( class_exists( $class, false ) && true == $wdg[ 'widget' ] ){
						register_widget( $class );
					}
					if( false == $wdg[ 'widget' ] ){
						new $class();
					}
				}
			}
		}
		// register where the AJAX calls should be routed when they come in
		add_action('wp_ajax_fmcShortcodeContainer', array('flexmlsConnect', 'shortcode_container') );
	}

  static function wp_init() {
    // handle form submission actions from the plugin
    if ( array_key_exists('fmc_do', $_POST) ) {
      switch($_POST['fmc_do']) {
        case "fmc_search":
          $handle = new fmcSearch();
          $handle->submit_search();
          break;
      }
    }
  }


  static function br_trigger_error($message, $errno) {

    if(isset($_GET['action'])
      && $_GET['action'] == 'error_scrape') {
      echo '<strong>' . $message . '</strong>';
      exit;

    } else {

      trigger_error($message, $errno);

    }
  }


  static function filter_mce_button($buttons) {
    array_push( $buttons, '|', 'fmc_button');
    return $buttons;
  }

  static function filter_mce_location_button($buttons) {
    array_push( $buttons, '|', 'fmc_button_location', 'fmc_button_locations');
    return $buttons;
  }

  static function filter_mce_plugin($plugins) {
    global $fmc_plugin_url;
    $plugins['fmc'] = $fmc_plugin_url . '/assets/js/tinymce_plugin.js';
    return $plugins;
  }

  static function filter_mce_plugin_global_vars() {
    global $fmc_plugin_url;
    ?>
    <script type='text/javascript'>
      var fmcPluginUrl = '<?php echo $fmc_plugin_url; ?>';
    </script>
    <?php
  }

  static function shortcode_container() {
  	/*
    global $fmc_widgets;
    global $fmc_api;

    $fmc_my_type = $fmc_api->GetMyAccount();
    $fmc_my_type = $fmc_my_type['UserType'];

    $return = '';

    $return .= "<div id='fmc_box_body'>";
    $return .= "<ul class='flexmls_connect__widget_menu'>\n";

    foreach ($fmc_widgets as $class => $widg) {
      if ($widg['shortcode'] == 'idx_agent_search' and $fmc_my_type == 'Member')
        continue;

      $short_title = str_replace("FlexMLS&reg;: ", "", $widg['title']);
      $return .= "<li class='flexmls_connect__widget_menu_item'><a class='fmc_which_shortcode' data-connect-shortcode='{$class}' style='cursor:pointer;'>{$short_title}</a></li>\n";
    }
    $return .= "</ul>\n";

    $return .= "<div id='fmc_shortcode_window_content'><p class='first'>please select a widget to the left</p></div>";

    $return .= "</div>";

    $response['title'] = "";
    $response['body'] = $return;
    echo flexmlsJSON::json_encode($response);
    exit;
    */
  }


  // called to put the start of the form on the shortcode generator page
  static function shortcode_header() {
  	return '';
    return "<div class='flexmls-shorcode-generator'><form fmc-shortcode-form='true'>";
  }

  // called to put the end of the form and submit button on the shortcode generator page
  static function shortcode_footer() {
  	return '';
    $return  = "<div class='flexmls-widget-settings-submit-row'>";
    $return .= "<input type='button' class='fmc_shortcode_submit button-primary' value='Insert Widget' />";
    $return .= "</div>";
    $return .= "</form>";
    $return .= "</div>";
    return $return;
  }


  static function clean_spaces_and_trim($value) {
    $value = trim($value);
    // keep looking for sequences of multiple spaces until they no longer exist
    while (preg_match('/\s\s+/', "{$value}")) {
      $value = preg_replace('/\s\s+/', ' ', "{$value}");
    }
    return trim($value);
  }


  static function strip_quotes($value) {
    $value = stripslashes($value);

    if (preg_match('/^\'(.*?)\'$/', $value)) {
      return substr($value, 1, -1);
    }
    else {
      return $value;
    }
  }


  static function widget_not_available(&$api, $detailed = false, $args = false, $settings = false) {
    $return = "";

    if (is_array($args)) {
      $return .= $args['before_widget'];
      $return .= $args['before_title'];
      $return .= isset($settings['title']) ? $settings['title'] : '';
      $return .= $args['after_title'];
    }

    if ($api->last_error_code == 1500) {
      $message = "This widget requires a subscription to FlexMLS&reg; IDX in order to work.  <a href=''>Buy Now</a>.";
    }
    elseif ($detailed == true) {
      $message = "There was an issue communicating with the FlexMLS&reg; IDX API services required to generate this widget.  Please refresh the page or try again later.  Error code: ".$api->last_error_code;
    }
    else {
      $message = "This widget is temporarily unavailable.  Please refresh the page or try again later.  Error code: ".$api->last_error_code;
    }

    $return .= $message;

    if (is_array($args)) {
      $return .= $args['after_widget'];
    }

    return $return;
  }


  static function widget_missing_requirements($widget, $reqs_missing) {

    if (is_user_logged_in()) {
      return "<span style='color:red;'>FlexMLS&reg; IDX: {$reqs_missing} are required settings for the {$widget} widget.</span>";
    }
    else {
      return false;
    }

  }


  static function shortcode($attr = array()) {
    global $fmc_api;

    if(empty($fmc_api)){
      return false;
    }

    if (!is_array($attr)) {
      $attr = array();
    }

    if (!array_key_exists('width', $attr)) {
      $attr['width'] = 600;
    }
    if (!array_key_exists('height', $attr)) {
      $attr['height'] = 500;
    }

    $show_link = flexmlsConnect::get_default_idx_link_url();

    $query_url = flexmlsConnect::wp_input_get('url');

    if (!empty($query_url)) {
      $show_link = $query_url;
    }
    else {
      $default_link = array_key_exists('default', $attr) ? $attr['default'] : "";
      if (!empty($default_link)) {
        $show_link = $default_link;
      }
    }

    if (empty($show_link)) {
      return;
    }

    if (!empty($query_url)) {
      $show_link = $query_url;
    }

    if(strpos($show_link, 'StreetAddress')){
       $show_link = str_replace('StreetAddress', 'streetaddress', $show_link);
    }

    return "<iframe src='{$show_link}' width='{$attr['width']}' height='{$attr['height']}' frameborder='0'></iframe>";
  }


  static function is_mobile() {

    $mobile_enabled = false;



    // WPTouch: http://wordpress.org/extend/plugins/wptouch/
    global $wptouch_plugin;

    if (is_object($wptouch_plugin)) {
      if ($wptouch_plugin->applemobile == true) {
        $mobile_enabled = true;
      }
    }

    // @todo add more later as deemed necessary

    return $mobile_enabled;

  }


  static function has_api_saved() {
    $options = get_option('fmc_settings');

    if ( empty($options['api_key']) || empty($options['api_secret']) ) {
      return false;
    }
    else {
      return true;
    }

  }

  static function use_default_titles() {
    $options = get_option('fmc_settings');

    if ($options['default_titles'] == true) {
      return true;
    }
    else {
      return false;
    }
  }


  static function get_destination_link() {
    $options = get_option('fmc_settings');
    $permalink = get_permalink($options['destlink']);
    return $permalink;
  }


  static function make_destination_link($link, $as = 'url', $params = array()) {

    $extra_query_string = null;
    if ( count($params) > 0 ) {
      $extra_query_string = http_build_query($params);
    }

    $options = get_option('fmc_settings');

    if (flexmlsConnect::get_destination_pref() == "own") {
      if (empty($extra_query_string)) {
        return $link;
      }
      else {
        return $link . '?' . $extra_query_string;
      }
    }

    if (!empty($options['destlink'])) {

      $permalink = get_permalink($options['destlink']);

      if (empty($permalink)) {
        return $link;
      }

      $return = "";

      $link = urlencode($link);

      if (strpos($permalink, '?') !== false) {
        $return = $permalink . '&' . $as . '=' . $link;
      }
      else {
        $return = $permalink . '?' . $as . '=' . $link;
      }

      if (empty($extra_query_string)) {
        return $return;
      }
      else {
        return $return . '&' . $extra_query_string;
      }

    }
    else {
      if (empty($extra_query_string)) {
        return $link;
      }
      else {
        return $link . '?' . $extra_query_string;
      }
    }

  }


  static function get_destination_window_pref() {
    $fmc_settings = get_option( 'fmc_settings' );
    return $fmc_settings[ 'destwindow' ];
    //$options = new Fmc_Settings;
	//return $options->destwindow();
  }

  static function get_destination_pref() {
    $options = new Fmc_Settings;
    return $options->destpref();
  }

  static function get_no_listings_page_number(){
    $options = new Fmc_Settings;
    return $options->listlink();
  }

  static function get_no_listings_pref(){
    $options = new Fmc_Settings;
    return $options->listpref();
  }


  static function get_default_idx_link() {
    global $fmc_api;

    $options = get_option('fmc_settings');

    if (array_key_exists('default_link', $options) && !empty($options['default_link'])) {
      // This link isn't validated. Use the FMC_IDX_Links class instead.
      return $options['default_link'];
    }
    else {
      $api_links = flexmlsConnect::get_all_idx_links();
      return $api_links[0]['LinkId'];
    }

  }

  static function get_idx_link_details($my_link) {
    global $fmc_api;

    $IDXLinks = new \SparkAPI\IDXLinks();
    $api_links = $IDXLinks->get_all_idx_links();

    //$api_links = flexmlsConnect::get_all_idx_links();

    if (is_array($api_links)) {
      foreach ($api_links as $link) {
        if ($link['LinkId'] == $my_link) {
          return $link;
        }
      }
    }

    return false;

  }

  static function get_default_idx_link_url() {
    global $fmc_api;

    $default_link = flexmlsConnect::get_default_idx_link();
    $api_links = flexmlsConnect::get_all_idx_links();

    $valid_links = array();
    foreach ($api_links as $link) {
      $valid_links[$link['LinkId']] = array('Uri' => $link['Uri'], 'Name' => $link['Name']);
    }

    if (array_key_exists($default_link, $valid_links) && array_key_exists('Uri', $valid_links[$default_link])) {
      return $valid_links[$default_link]['Uri'];
    }
    else {
      return "";
    }

  }

  static function remove_starting_equals($val) {
    if (preg_match('/^\=/', $val)) {
      $val = substr($val, 1);
    }
    return $val;
  }

  static function is_ie() {

    $this_ua = getenv('HTTP_USER_AGENT');

    if ($this_ua && (strpos($this_ua, 'MSIE') !== false) && (strpos($this_ua, 'Opera') === false) ) {
      return true;
    }
    else {
      return false;
    }

  }

  static function ie_version() {
    preg_match('/MSIE ([0-9]\.[0-9])/', $_SERVER['HTTP_USER_AGENT'], $reg);
    if(!isset($reg[1])) {
      return -1;
    } else {
      return floatval($reg[1]);
    }
  }


	static function clear_temp_cache(){
		$count = get_option( 'fmc_cache_version' );
		if( empty( $count ) ){
			$count = 0;
		}
		$count++;
		update_option( 'fmc_cache_version', $count );
		$spark = new \SparkAPI\Core();
		$spark->clear_cache();
		//flexmlsConnect::garbage_collect_bad_caches();
	}

  static function greatest_fitting_number($num, $slide, $max) {
    if (($num * ($slide + 1)) <= $max) {
      return flexmlsConnect::greatest_fitting_number($num, $slide + 1, $max);
    }
    else {
      return $num * $slide;
    }
  }

  /**
   *
   * Used to calculate the API limit needed to fill as many
   * slides as possible without having any partially filled.
   */
  static function generate_api_limit_value($hor, $ver) {

    // _limit number to shoot for if we can
    $kind_limit = 1;

    // maximum _limit is allowed to be
    $max_limit = 25;

    if (empty($hor)) {
      $hor = 1;
    }
    if (empty($ver)) {
      $ver = 1;
    }

    $total = (int)$hor * (int)$ver;

    if ($total < $kind_limit) {
      return flexmlsConnect::greatest_fitting_number($total, 1, $kind_limit);
    }
    elseif ($total >= $kind_limit && $total <= $max_limit) {
      return $total;
    }
    else {
      return $max_limit;
    }

  }


  static function generate_appropriate_dimensions($hor, $ver) {
    $new_horizontal = $hor;

    if ($new_horizontal > 25) {
      $new_horizontal = 25;
    }

    $initial_total = ($hor * $ver);

    if ($initial_total > 25) {
      $room_to_grow = true;
      $new_vertical = 1;
      while ($room_to_grow) {
        if (($new_horizontal * ($new_vertical + 1)) >= 25) {
          $room_to_grow = false;
        }
        else {
          $new_vertical++;
        }
      }
    }
    else {
      // the grid is fine as-is
      $new_vertical = $ver;
    }

    return array( $new_horizontal, $new_vertical );
  }


  static function parse_location_search_string($location) {
    $locations = array();
    if (!empty($location)) {
        if (preg_match('/\|/', $location)) {
            $locations = explode("|", $location);
        }
        else {
            $locations[] = $location;
        }
    }

    $return = array();

    foreach ($locations as $loc) {
        list($loc_name, $loc_value) = explode("=", $loc, 2);
        list($loc_value, $loc_display) = explode("&", $loc_value);
        $loc_value_nice = preg_replace('/^\'(.*)\'$/', "$1", $loc_value);
        // if there weren't any single quotes, just use the original value
  if (empty($loc_value_nice)) {
    $loc_value_nice = $loc_value;
  }
        $loc_value_nice = flexmlsConnect::remove_starting_equals($loc_value_nice);
        $return[] = array(
    'r' => $loc,
            'f' => $loc_name,
            'v' => $loc_value_nice,
            'l' => $loc_display
        );
    }

    return $return;
  }

  static function cache_turned_on() {
    return true;
  }


  static function get_neighborhood_template_content($page_id = false) {

    $neigh_template_page_id = flexmlsConnect::get_neighborhood_template_id($page_id);

    if (!$neigh_template_page_id) {
      return false;
    }

    $content = get_page($neigh_template_page_id);
    return $content->post_content;

  }


	static function get_neighborhood_template_id( $page_id = false ){
		if( !$page_id || 'default' == $page_id ){
			$options = get_option( 'fmc_settings' );
			$neigh_template_page_id = $options[ 'neigh_template' ];
		} else {
			$neigh_template_page_id = $page_id;
		}
		if( empty( $neigh_template_page_id ) ){
			return false;
		}
		return $neigh_template_page_id;
	}

  static function special_location_tag_text() {
    return "";
  }


  static function wp_input_get($key) {

    if (isset($_GET) && is_array($_GET) && array_key_exists($key, $_GET)) {
      return stripslashes($_GET[$key]);
    }
    else {
      // parse the query string manually.  some kind of internal redirect
      // or protection is keeping PHP from knowing what $_GET is

      $full_requested_url = (preg_match('/^HTTP\//', $_SERVER['SERVER_PROTOCOL'])) ? "http" : "https";
      $full_requested_url .= "://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

      $query_string = parse_url($full_requested_url, PHP_URL_QUERY);
      $query_parts = explode("&", $query_string ?? '');
      $manual = array();
      foreach ($query_parts as $p) {
        list($k, $v) = array_pad(@explode("=", $p, 2),-2,null);
        if (array_key_exists($k, $manual)) {
          $manual[$k] .= ",".urldecode($v);
        }
        else {
          $manual[$k] = urldecode($v);
        }
      }
      if ( array_key_exists($key, $manual) ) {
        return $manual[$key];
      }
      else {
        return null;
      }
    }
  }


  static function wp_input_post($key) {
    if (isset($_POST) && is_array($_POST) && array_key_exists($key, $_POST)) {
      return stripslashes($_POST[$key]);
    }
    else {
      return null;
    }
  }


  static function wp_input_get_post($key) {
    $via_post = self::wp_input_post($key);
    if ($via_post !== null) {
      return $via_post;
    }

    $via_get = self::wp_input_get($key);
    if ($via_get !== null) {
      return $via_get;
    }

    return null;
  }

  static function send_notification() {

    $options = get_option('fmc_settings');

    if (!array_key_exists('contact_notifications', $options)) {
      return true;
    }
    elseif ($options['contact_notifications'] === true) {
      return true;
    }
    else {
      return false;
    }

  }


  static function format_listing_street_address($data) {

    $listing = $data['StandardFields'];
    $first_line_address = (flexmlsConnect::is_not_blank_or_restricted($listing['UnparsedFirstLineAddress'])) ? $listing['UnparsedFirstLineAddress'] : "";
    $second_line_address = "";

    if ( flexmlsConnect::is_not_blank_or_restricted($listing['City']) ) {
      $second_line_address .= "{$listing['City']}, ";
    }

    if ( flexmlsConnect::is_not_blank_or_restricted($listing['StateOrProvince']) ) {
      $second_line_address .= "{$listing['StateOrProvince']} ";
    }

    if ( flexmlsConnect::is_not_blank_or_restricted($listing['StateOrProvince']) ) {
      $second_line_address .= "{$listing['PostalCode']}";
    }

    $second_line_address = str_replace("********", "", $second_line_address);
    $second_line_address = flexmlsConnect::clean_spaces_and_trim($second_line_address);

    $one_line_address = (!empty($first_line_address)) ? $first_line_address . ", " : "";
    $one_line_address .= "{$second_line_address}";
    $one_line_address = flexmlsConnect::clean_spaces_and_trim($one_line_address);

    return array($first_line_address, $second_line_address, $one_line_address);

  }

  static function is_not_blank_or_restricted($val) {
    if (!is_array($val)) {
      $val = trim((string) $val);
      return ( empty($val) or $val == "********") ? false : true;
    } else {
      $result = true;
      foreach ($val as $v) {
        if (!flexmlsConnect::is_not_blank_or_restricted($v)){
          $result = false;
        }
      }
      return $result;
    }
  }

  static function generate_nice_urls() {
    global $wp_rewrite;
    return $wp_rewrite->using_mod_rewrite_permalinks();
  }

  static function make_nice_tag_url($tag, $params = array(), $type='fmc_tag') {

    $query_string = null;
    if ( count($params) > 0 ) {
      $query_string .= '?'. http_build_query($params);
    }
    if (flexmlsConnect::generate_nice_urls()) {
      if ($type == 'fmc_tag'){
        $options = get_option('fmc_settings');
        return get_home_url() . '/' . $options['permabase'] . '/' . $tag . '/' . $query_string;
      }
      elseif ($type == 'fmc_vow_tag'){
        return get_home_url() . '/' . 'portal' . '/' . $tag . '/' . $query_string;
      }
    }
    else {
      return flexmlsConnect::make_destination_link($tag, $type, $params);
    }
  }

  static function make_nice_address_url($data, $params = array(), $type='fmc_tag') {
    $address = flexmlsConnect::format_listing_street_address($data);

    $return = $address[0] .'-'. $address[1] .'-mls_'. $data['StandardFields']['ListingId'];
    $return = preg_replace('/[^\w]/', '-', $return);

    while (preg_match('/\-\-/', $return)) {
      $return = preg_replace('/\-\-/', '-', $return);
    }

    $return = preg_replace('/^\-/', '', $return);
    $return = preg_replace('/\-$/', '', $return);

    if (flexmlsConnect::generate_nice_urls()) {
      $options = get_option('fmc_settings');

      if (count($params) > 0) {
        $return .= '?'. http_build_query($params);
      }

      if ($type == 'fmc_vow_tag'){
        return get_home_url() . '/' . 'portal' . '/' . $return;
      }
      else {
        return get_home_url() . '/' . $options['permabase'] . '/' . $return;
      }
    }
    else {
      return flexmlsConnect::make_destination_link($return, $type, $params);
    }
  }

  static function make_nice_address_title($data) {
    $address = flexmlsConnect::format_listing_street_address($data);

    $return = $address[0] .', '. $address[1] .' (MLS# '. $data['StandardFields']['ListingId'] .')';
    $return = flexmlsConnect::clean_spaces_and_trim($return);

    return $return;
  }

  static function format_date ($format,$date){
    //Format Last Modified Date
    //search for "php date" for format specs
    $LastModifiedDate= "";
    if (flexmlsConnect::is_not_blank_or_restricted($date)){
      $Seconds = strtotime($date);
      $LastModifiedDate=date($format,$Seconds);
    }
    return $LastModifiedDate;
  }


  static function make_api_formatted_value($value, $type) {

    $formatted_value = null;

    if ($type == 'Character') {
      $formatted_value = (string) "'". addslashes( trim( trim($value) ,"'") ) ."'";
    }
    elseif ($type == 'Integer') {
      $formatted_value = (int) $value;
    }
    elseif ($type == 'Decimal') {
      $formatted_value = number_format($value, 2, '.', '');
    }
    elseif ($type == 'Date' || $type == 'Datetime') {
      $formatted_value = trim($value); // no single quotes
    }
    else { }

    return $formatted_value;

  }

  static function NAR_broker_attribution($sf) {
    global $fmc_api;
    $AttributionContact = null;

    $GetListingOfficeInfo = $fmc_api->GetAccount($sf['ListOfficeId']);
    $GetListingAgentInfo = $fmc_api->GetAccount($sf['ListAgentId']);

    if ( flexmlsConnect::is_not_blank_or_restricted( $sf['AttributionContact'] ) ) {
      $AttributionContact = $sf['AttributionContact'];
    } 
    elseif ( flexmlsConnect::is_not_blank_or_restricted( $GetListingAgentInfo['AttributionContact'] ) ) {
      $AttributionContact = $GetListingAgentInfo['AttributionContact'];
    } 
    elseif ( flexmlsConnect::is_not_blank_or_restricted( $GetListingOfficeInfo['AttributionContact'] ) ) {
      $AttributionContact = $GetListingOfficeInfo['AttributionContact'];
    }

    if( isset( $AttributionContact ) ){
      //$return = "<div class='listing-req'>Broker Attribute: " . $AttributionContact . "</div>\n <hr /> \n";
      $return = $AttributionContact;
    } 
    else {
      $return = false;
    }

    return $return;

  } 

  static function fbs_products_branding_link() {
    $branding_base_url = "https://fbsproducts.com/?";
    $branding_url_utm = urlencode("utm_source=wp_plugin&utm_medium=software&utm_campaign=powered_by&utm_content=powered_by");
    $branding_text = "Search powered by FBS Products";
    $return = '<a href="' . $branding_base_url . $branding_url_utm . '" target="_blank">' . $branding_text .  '</a>';

    return $return;
  }

  static function get_big_idx_disclosure_text() {
    global $fmc_api;

    $api_system_info = $fmc_api->GetSystemInfo();
    return trim( $api_system_info['Configuration'][0]['IdxDisclaimer'] );
  }

  static function add_contact($content){
        global $fmc_api;
     return ($fmc_api->AddContact($content, flexmlsConnect::send_notification()));
  }

  static function message_me($subject, $body, $from_email){
    global $fmc_api;
    $my_account = $fmc_api->GetMyAccount();
    $sender = $fmc_api->GetContacts(null, array("_select" => "Id", "_filter" => "PrimaryEmail Eq '{$from_email}'"));
    return $fmc_api->AddMessage(array(
    'Type'       => 'General',
    'Subject'    => $subject,
    'Body'       => $body,
    'Recipients' => array($my_account['Id']),
    'SenderId'   => $sender[0]['Id']
    ));
  }

  static function mls_requires_office_name_in_search_results() {
    global $fmc_api;
    $api_system_info = $fmc_api->GetSystemInfo();
    $mlsId = $api_system_info["MlsId"];
    $compList = ($api_system_info["DisplayCompliance"][$mlsId]["View"]["Summary"]["DisplayCompliance"]);

    return (in_array("ListOfficeName",$compList));
  }

  static function mls_required_fields_and_values($type, &$record){
    //$type   String   "Summary" | "Detail"
    //$record GetListings(params)[0]
    global $fmc_plugin_url;
    global $fmc_api;
    $api_system_info = $fmc_api->GetSystemInfo();
    $mlsId = $api_system_info["MlsId"];
    $compList = ($api_system_info["DisplayCompliance"][$mlsId]["View"][$type]['DisplayCompliance']);
    $sf = $record["StandardFields"];


    //Get Adresses
    //Since these fields take a considerable amount of time to get, check if they are required from the compliance list beforehand.
    $OfficeAddress = '';
    if (in_array('ListOfficeAddress',$compList)){
        $OfficeInfo = $fmc_api->GetAccountsByOffice($sf["ListOfficeId"]);
        $OfficeAddress = ($OfficeInfo[0]["Addresses"][0]["Address"]);
    }

    $AgentAddress = '';
    if (in_array('ListMemberAddress',$compList)){
        $AgentInfo  = $fmc_api->GetAccount($sf["ListAgentId"]);
        $AgentAddress = ($AgentInfo["Addresses"][0]["Address"]);
          }

          $CoAgentAddress = '';
    if (in_array('CoListAgentAddress',$compList)){
      $CoAgentInfo  = $fmc_api->GetAccount($sf["CoListAgentId"]);
      $CoAgentAddress = ($CoAgentInfo["Addresses"][0]["Address"]);
          }

    //Names
    $AgentName = "";
    $CoAgentName = "";
                if ((flexmlsConnect::is_not_blank_or_restricted($sf["ListAgentFirstName"])) && (flexmlsConnect::is_not_blank_or_restricted($sf["ListAgentLastName"])))
                        $AgentName = "{$sf["ListAgentFirstName"]} {$sf["ListAgentLastName"]}";

    if ((flexmlsConnect::is_not_blank_or_restricted($sf["CoListAgentFirstName"])) && (flexmlsConnect::is_not_blank_or_restricted($sf["CoListAgentLastName"])))
                        $CoAgentName = "{$sf["CoListAgentFirstName"]} {$sf["CoListAgentLastName"]}";


    //Primary Phone Numbers and Extensions
    $ListOfficePhone = "";
    $ListAgentPhone = "";
    $CoListAgentPhone = "";
    if (flexmlsConnect::is_not_blank_or_restricted($sf["ListOfficePhone"]))
      $ListOfficePhone = $sf["ListOfficePhone"];
      if (flexmlsConnect::is_not_blank_or_restricted($sf["ListOfficePhoneExt"]))
                          $ListOfficePhone .= " ext. " . $sf["ListOfficePhoneExt"];

    if (flexmlsConnect::is_not_blank_or_restricted($sf["ListAgentPreferredPhone"]))
                        $ListAgentPhone = $sf["ListAgentPreferredPhone"];
                        if (flexmlsConnect::is_not_blank_or_restricted($sf["ListAgentPreferredPhone"]))
                                $ListAgentPhone .= " ext. " . $sf["ListAgentPreferredPhone"];

                if (flexmlsConnect::is_not_blank_or_restricted($sf["CoListAgentPreferredPhone"]))
                        $CoListAgentPhone = $sf["CoListAgentPreferredPhone"];
                        if (flexmlsConnect::is_not_blank_or_restricted($sf["CoListAgentPreferredPhone"]))
                                $CoListAgentPhone .= " ext. " . $sf["CoListAgentPreferredPhone"];


    //format last modified date
    $LastModifiedDate = flexmlsConnect::format_date("F - d - Y", $sf["ModificationTimestamp"]);

    $logo="";
    if ($api_system_info['Configuration'][0]['IdxLogoSmall']){
      $logo = $api_system_info['Configuration'][0]['IdxLogoSmall'];
    }
    elseif ($api_system_info['Configuration'][0]['IdxLogo']){
        $logo = $api_system_info['Configuration'][0]['IdxLogo'];
    }
    else{
      $logo = "IDX";
    }

    $listing_office_label = ($sf['StateOrProvince'] == 'NY') ? 'Listing Courtesy of' : 'Listing Office:';

    //These will be printed in this order.
    $possibleRequired = array(
      "ListOfficeName"  => array($listing_office_label,$sf["ListOfficeName"]),
      "ListOfficePhone"   => array("Office Phone:",$ListOfficePhone),
      "ListOfficeEmail"   => array("Office Email:",$sf["ListOfficeEmail"]),
      "ListOfficeURL"   => array("Office Website:",$sf["ListOfficeURL"]),
      "ListOfficeAddress"   => array("Office Address:",$OfficeAddress),
      "ListAgentName"   => array("Listing Agent:",$AgentName),//Agent name is done below to make sure first and last name are present
      "ListMemberPhone"   => array("Agent Phone:",$sf["ListAgentPreferredPhone"] ),
      "ListMemberEmail"   => array("Agent Email:",$sf["ListAgentEmail"]),
      "ListMemberURL"   => array("Agent Website:",$sf["ListAgentURL"]),
      "ListMemberAddress"   => array("Agent Address:",$AgentAddress),
      "CoListOfficeName"  => array("Co Office Name:",$sf["CoListOfficeName"]),
      "CoListOfficePhone" => array("Co Office Phone:",$sf["CoListOfficePhone"]),
      "CoListOfficeEmail" => array("Co Office Email:",$sf["CoListOfficeEmail"]),
      "CoListOfficeURL" => array("Co Office Website:",$sf["CoListOfficeURL"]),
      "CoListOfficeAddress" => array("Co Office Address:","$CoAgentAddress"),
      "CoListAgentName" => array("Co Listing Agent:",$CoAgentName),
      "CoListAgentPhone"  => array("Co Agent Phone:",$CoListAgentPhone),
      "CoListAgentEmail"  => array("Co Agent Email:",$sf["CoListAgentEmail"]),
      "CoListAgentURL"  => array("Co Agent Webpage:",$sf["CoListAgentURL"]),
      "CoListAgentAddress"  => array("Co Agent Address:",$CoAgentAddress),
      "ListingUpdateTimestamp"=> array("Last Updated:",$LastModifiedDate),
      "IDXLogo"               => array("LOGO",$logo),//Todo -- Print Logo?
    );
    //var_dump($logo);
    $values= array();

    /*foreach ($compList as $test){
        array_push($values,array($possibleRequired[$test][0],$possibleRequired[$test][1]));
    } */

    foreach ($possibleRequired as $key => $value){
      if (in_array($key, $compList))
        array_push($values, array($value[0], $value[1]));
    }
    return $values;
  }

  static function is_odd($val) {
    return ($val % 2) ? true : false;
  }


  /*
   * Take a value and clean it so it can be used as a parameter value in what's sent to the API.
   *
   * @param string $var Regular string of text to be cleaned
   * @return string Cleaned string
   */
  static function clean_comma_list($var) {

    $return = "";
    if ( strpos($var, ',') !== false ) {
      // $var contains a comma so break it apart into a list...
      $list = explode(",", $var);
      // trim the extra spaces and weird characters from the beginning and end of each item in the list...
      $list = array_map('trim', $list);
      // and put it back together as a comma-separated string to be returned
      $return = implode(",", $list);
    }
    else {
      // trim the extra spaces and weird characters from the beginning and end of the string to be returned
      $return = trim($var);
    }
    return $return;
  }

  static function page_slug_tag() {
    global $wp_query;
    return $wp_query->get('fmc_tag');
  }

  static function get_all_idx_links($only_saved_search = false) {
    global $fmc_api;

    $return = array();

    $current_page = 0;
    $total_pages = 1;

    while ($current_page < $total_pages) {

      $current_page++;

      $params = array(
          '_pagination' => 1,
          '_page' => $current_page
      );

      $result = $fmc_api->GetIDXLinks($params);

      if ( is_array($result) ) {
        foreach ($result as $r) {
          if ($only_saved_search and !array_key_exists('SearchId', $r) ) {
            // we're only wanting saved search links and this isn't one
            continue;
          }
          $return[] = $r;
        }
      }

      if ( $fmc_api->total_pages == null ) {
        break;
      }
      else {
        $current_page = $fmc_api->current_page;
        $total_pages = $fmc_api->total_pages;
      }
    }

    return $return;
  }

  static function possible_destinations() {
    return array('local' => 'my search results', 'remote' => 'a flexmls IDX frame');
  }

  static function is_agent() {
    $type = get_option('fmc_my_type');
    return ($type == 'Member') ? true : false;
  }

  static function is_office() {
    $type = get_option('fmc_my_type');
    return ($type == 'Office') ? true : false;
  }

  static function is_company() {
    $type = get_option('fmc_my_type');
    return ($type == 'Company') ? true : false;
  }

  static function get_office_id() {
    return get_option('fmc_my_office');
  }

  static function get_company_id() {
    return get_option('fmc_my_company');
  }

  static function possible_fonts() {
    return array(
      'Arial' => 'Arial',
      'Lucida Sans Unicode' => 'Lucida Sans Unicode',
      'Tahoma' => 'Tahoma',
      'Verdana' => 'Verdana'
    );
  }

  static function hexLighter($hex, $factor = 20) {
    $hex = str_replace('#', '', $hex);
    $new_hex = '';

    $base['R'] = hexdec($hex[0].$hex[1]);
    $base['G'] = hexdec($hex[2].$hex[3]);
    $base['B'] = hexdec($hex[4].$hex[5]);

    foreach ($base as $k => $v) {
      $amount = 255 - $v;
      $amount = $amount / 100;
      $amount = round($amount * $factor);
      $new_decimal = $v + $amount;

      $new_hex_component = dechex($new_decimal);
      if (strlen($new_hex_component) < 2) {
        $new_hex_component = "0".$new_hex_component;
      }
      $new_hex .= $new_hex_component;
    }

    return '#' . $new_hex;
  }

  static function hexDarker($color, $dif=20){
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6){ return '#000000'; }
    $rgb = '';

    for ($x=0;$x<3;$x++){
        $c = hexdec(substr($color,(2*$x),2)) - $dif;
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
    }

    return '#' . $rgb;
  }

  public static function allowMultipleLists() {
    $options = get_option('fmc_settings');

    if (array_key_exists('multiple_summaries', $options)) {
      if ($options['multiple_summaries']) {
          return true;
      }
    }
    return false;
  }


  static function nice_property_type_label($abbrev) {
    global $fmc_api;
    $options = get_option('fmc_settings');

    if ( array_key_exists("property_type_label_{$abbrev}", $options) and !empty($options["property_type_label_{$abbrev}"]) ) {
      return $options["property_type_label_{$abbrev}"];
    }
    else {
      $api_property_types = $fmc_api->GetPropertyTypes();
      return $api_property_types[$abbrev];
    }
  }

  //todo: check if this works as expected
  public static function gentle_price_rounding($val) {
    // check if the value has decimal places and if those aren't just zeros

    if ( !flexmlsConnect::is_not_blank_or_restricted($val) )
    return "";

      if ( strpos($val, '.') !== false ) {
        // has a decimal
        $places = explode(".", $val);
        if ($places[1] != "00") {
          return number_format($val, 2);
        }
      }

      return number_format($val, 0);
  }

	public static function garbage_collect_bad_caches(){
		$SparkAPI = new \SparkAPI\Core();
		$SparkAPI->clear_cache();
		return true;
	}

  static function translate_tiny_code($tiny_id){
    $t_id = (string) flexmlsConnect::bc_base_convert($tiny_id,36,10);
    $prefix = "20";
    if ( $t_id[0]=='9' && strlen($t_id) == 18){
      $prefix="19";
    }
    while ((strlen($prefix . $t_id)) < 20){
      $prefix .= "0";
    }
    return  $prefix . $t_id . "000000";
  }

  static function bc_base_convert($value,$quellformat,$zielformat){
    $vorrat = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if(max($quellformat,$zielformat) > strlen($vorrat))
      trigger_error('Bad Format max: '.strlen($vorrat),E_USER_ERROR);
    if(min($quellformat,$zielformat) < 2)
      trigger_error('Bad Format min: 2',E_USER_ERROR);
    $dezi   = '0';
    $level  = 0;
    $result = '';
    $value  = trim((string)$value,"\r\n\t +");
    $vorzeichen = '-' === $value[0]?'-':'';
    $value  = ltrim($value,"-0");
    $len    = strlen($value);
    for($i=0;$i<$len;$i++)
    {
      $wert = strpos($vorrat,$value[$len-1-$i]);
      if(FALSE === $wert) trigger_error('Bad Char in input 1',E_USER_ERROR);
      if($wert >= $quellformat) trigger_error('Bad Char in input 2',E_USER_ERROR);
      $dezi = bcadd($dezi,bcmul(bcpow($quellformat,$i),$wert));
    }
    if(10 == $zielformat) return $vorzeichen.$dezi; // abkürzung
    while(1 !== bccomp(bcpow($zielformat,$level++),$dezi));
    for($i=$level-2;$i>=0;$i--)
    {
      $factor  = bcpow($zielformat,$i);
      $zahl    = bcdiv($dezi,$factor,0);
      $dezi    = bcmod($dezi,$factor);
      $result .= $vorrat[$zahl];
    }
    $result = empty($result)?'0':$result;
    return $vorzeichen.$result ;
  }

  static function show_error($error = array()){
    $return = '<div class="fmc-error"><b>Error:</b> ';
    if (array_key_exists("title", $error)) {
      $return .= $error["title"] . "<br>";
    }
    if (array_key_exists("message", $error)) {
      $return .= $error["message"];
    }
    $return .= "</div>";
    return $return;
  }

  static function get_portal_slug() {
    global $fmc_api;

    $portal_slug = null;
    $portal = $fmc_api->GetPortal();

    if( sizeof($portal) > 0 && array_key_exists('DisplayName', $portal[0]) && !empty($portal[0]['DisplayName'])){
      $portal_slug = $portal[0]['DisplayName'];
    } else {
      $fmc_api->SetPortal(array(), array('AutoName' => true));
      $fmc_api->DeleteCache('portal');
      $portal = $fmc_api->GetPortal();
      $portal_slug = $portal[0]['DisplayName'];
    }

    return $portal_slug;
  }

}
