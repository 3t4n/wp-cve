<?php
/**
 * @file
 * Generates visitor clickstream report
 *
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_visitor_clickstream_report_page($report_params = '-', $report_subtype = '-', $report_subfilter = '-', $entity_type = '-', $entity = '-') {
  require_once INTEL_DIR . "/includes/intel.reports.php";
  require_once INTEL_DIR . "/includes/intel.ga.php";
  $output = '';

  $vars = array();
  if (!empty($entity_type) && $entity_type != '-') {
    $vars['entity_type'] = $entity_type;
    $vars['entity'] = $entity;
  }

  $vars = intel_init_reports_vars('visitor_clickstream', 'visitor', $report_params, $report_subtype, $report_subfilter, $vars);
  // TODO: hack until init of timeline fixed on ajax loading
  $_GET['return_type'] = 'nonajax';

  $output = intel_build_report($vars);

  return $output;
}

function intel_visitor_clickstream_report($vars) {
//dsm($vars);
  $visitor = $vars['entity'];
  if (!empty($visitor->vtk)) {
    $vtkid = substr($visitor->vtk, 0 , 20);
  }

  $vtkids = array();
  if (!empty($visitor->identifiers['vtk']) && is_array($visitor->identifiers['vtk'])) {
    foreach ($visitor->identifiers['vtk'] AS $vtk) {
      $vtkids[] = substr($vtk, 0, 20);
    }
  }

  list($start_date, $end_date, $number_of_days, $timezone_offset) = _intel_get_report_dates("-90 days", "Now");

  $cache_options = array();
  if (!empty($_GET['refresh'])) {
    $cache_options['refresh'] = $_GET['refresh'];
  }
  else {
    $cache_options['round_start'] = 1;
    $cache_options['round_end'] = 1;
  }

  $options = array(
    'start_date' => $start_date,
    'end_date' => $end_date,
    'cache_options' => $cache_options,
    'mode' => 'clickstream',
    'segment' => 'sessions::condition::ga:dimension5==' . implode(',ga:dimension5==', $vtkids),
  );
  $visits = intel_fetch_analytics_visits($options);

  // set last hit
  $lasthit = $visits['_lasthit'];

  if ((time() - $lasthit) < 1800) {
    $msg = Intel_Df::t('The last hit retrieved from Google Analytics was only !time minutes ago.', array(
      '!time' => floor((time() - $lasthit)/60),
    ));
    $msg .= ' ' . Intel_Df::t('Data may be missing as it may take Google Analytics up to 30 minutes to fully prepare analytics data.');
    Intel_Df::drupal_set_message($msg, 'warning');
  }

  $output = '';

  $vars = array(
    'visits' => $visits,
    'visitorid' => $vtkid,
    'visitor' => $visitor,
    'vtkids' => $vtkids,
    'timezone_offset' => $timezone_offset,
  );

  $output .= theme_intel_visitor_clickstream($vars);

  return $output;
}

function theme_intel_visitor_clickstream($variables) {
  $visits = $variables['visits'];
  $vtkid = $variables['visitorid'];
  $vtkids = $variables['vtkids'];
  $timezone_offset = $variables['timezone_offset'];

  wp_enqueue_style('intel_report', INTEL_URL . 'css/intel.report.css');
  //drupal_add_js(drupal_get_path('module', 'intel') . '/intel.report.js');

  $tldata = array(
    'dates' => array(),
    'eras' => array(),
    'visits' => array(),
    'pages' => array(),
    'events' => array(),
  );
  $output = '';

  $startday = '';
  $endday = '';
  $vi = 0;
  foreach ($visits AS $i => $visit) {
    if (substr($i, 0, 1) == '_') {
      continue;
    }
    $vout = '';
    list($vtkid, $t) = explode('-', $i);
    $ts = $visit['time'];
    $day = date('Y-m-d', $ts);
    if (!$startday) {
      $startday = $day;
    }
    $endday = $day;
    $visit_ts = $ts;
    $vi++;
    $tldata['visits'][$ts] = _intel_init_tl_visit($visit, $ts, $vi);
    $vout .= '<div class="session">' . "\n";
    $vout .= '  <h3 class="card-header">' . Intel_Df::t('Visit');
    $vout .= ': ' . Intel_Df::format_date($ts + $timezone_offset, 'long') . "</h3>\n";
    $vout .= '  <div class="row">';
    $col1 =  '    <div class="col-md-3">' . "\n";
    $col1 .= Intel_Df::theme('intel_trafficsource_block', array('trafficsource' => $visit['trafficsource']));
    if (!empty($visit['location'])) {
      $col1 .= Intel_Df::theme('intel_location_block', array(
        'location' => $visit['location']
      ));
    }
    if (!empty($visit['device'])) {
      $col1 .= Intel_Df::theme('intel_browser_environment_block', array(
        'environment' => $visit['device']
      ));
    }
    $col1 .= '  </div>' . "\n";

    $col2 =  '  <div class="col-md-9">' . "\n";
    $col2 .= '    <div class="card chronology report-box">' . "\n";
    $col2 .= '      <h4 class="card-header">' . Intel_Df::t('Chronology') . "</h4>\n";
    $hit_vars = array(
      'hits' => $visit['hits'],
    );
    $col2 .= Intel_Df::theme_ref('intel_visit_hits_table', $hit_vars);

    foreach ($hit_vars['hit_pageviews'] as $pi => $pageview) {
      $tldata['pages'][$pageview['time']] = _intel_init_tl_page($pageview, $pageview['time'], $visit_ts, $i,  $vars = array());
    }

    $col2 .= '    </div>';

    $vout .= $col1 . $col2;
    $vout .= '  </div>';
    $vout .= '</div>';

    $output .= $vout;
  }

  $visitor = $variables['visitor'];

  $data = array();
  $start = date("m/d/Y", strtotime("$startday -1 day"));
  $end = date("m/d/Y", strtotime("$endday +1 day"));
  $charts = array(
    "startDate" => $start,
    "endDate" => $end,
    "headline" => "Headline Goes Here, Not sure what this is for",
    "value" => "28",  // not sure what this is for
  );

  $tf = array(
    '@pages' => Intel_Df::format_plural(count($tldata['pages']), '1 page', '@count pages'),
    '@visits' => Intel_Df::format_plural(count($tldata['visits']), '1 visit', '@count visits'),
  );
  $data['timeline'] = array(
    'headline' => Intel_Df::t("%name clickstream", array('%name' => $visitor->label())),
    'type' => "default",
    'text' => Intel_Df::t("viewed @pages in @visits in the last 90 days.", $tf),
    'date' => array(),
    'era' => array(),
    'chart' => $charts,
  );
  $vdata = array();
  foreach ($tldata['visits'] AS $ts => $visit) {
    $vdata[$visit['visitindex']]['timeline'] = $data['timeline'];
  }
  //dsm($vdata);
  $visit_start_slides = array();
  $i = 1;
  foreach ($tldata['visits'] AS $ts => $visit) {
    $data['timeline']['date'][] = $visit;
    $data['timeline']['era'][] = array(
      'startDate' => $visit['startDate'],
      'endDate' => $visit['endDate'],
      'headline' => $visit['headline'],
      'tag' => 'visits',
    );
    $vdata[$visit['visitindex']]['timeline']['date'][] = $visit;
  }

  foreach ($tldata['pages'] AS $ts => $page) {
    if (!empty($page['goals'])) {
      $page['text'] .= "<br>\n<br>\n<strong>" . Intel_Df::t('Goals') . "</strong><br>\n" . implode("<br>\n", $page['goals']);
    }
    if (!empty($page['valuedevents'])) {
      $page['text'] .= "<br>\n<br>\n<strong>" . Intel_Df::t('Valued events') . "</strong><br>\n" . implode("<br>\n", $page['valuedevents']);
    }
    if (!empty($page['events'])) {
      $page['text'] .= "<br>\n<br>\n<strong>" . Intel_Df::t('Events') . "</strong><br>\n" . implode("<br>\n", $page['events']);
    }
    if (!empty($page['ctaimpressions'])) {
      $page['text'] .= "<br>\n<br>\n<strong>" . Intel_Df::t('CTA impressions') . "</strong><br>\n" . implode("<br>\n", $page['ctaimpressions']);
    }


    unset($page['ctaimpressions']);
    unset($page['events']);
    unset($page['valuedevents']);
    unset($page['goals']);
    unset($page['hostpath']);
    $data['timeline']['date'][] = $page;
    $vdata[$page['visitindex']]['timeline']['date'][] = $page;
  }

  $lastts = 0;
  foreach ($tldata['events'] AS $ts => $events) {
    foreach ($events AS $event) {
      $data['timeline']['date'][] = $event;
      $vdata[$event['visitindex']]['timeline']['date'][] = $page;
      break;
    }
  }

  usort($data['timeline']['date'], '_intel_sort_tldata');

  $visit_starts = array();
  $start_at_slide = 0;
  foreach ($data['timeline']['date'] AS $i => $slide) {
    if (!empty($slide['visitcount'])) {
      $visit_starts[$slide['visitcount']] = $i;
      if (!empty($_GET['visit-ts']) && ($_GET['visit-ts'] == $slide['visitts'])) {
        $start_at_slide = $i+1;
      }
    }
  }
  //dsm($start_at_slide);
  //dsm($tldata);
  //dsm($data);
  //dsm($vdata);
  $json = json_encode($data);

  //$json = drupal_json_encode($vdata[3]);

  $script = <<<EOT5
  jQuery(document).ready(function() {
    var width = jQuery('#main-timeline').width();
    var dataObject = {$json};
    createStoryJS({
     type:       'timeline',
     width:      width,
     height:     '600',
     source:     dataObject,
     embed_id:   'main-timeline',
     start_at_slide: $start_at_slide
     });
  });
EOT5;

  $options = array('type' => 'file', 'weight' => -1, 'preprocess' => FALSE);

  // older TimelineJS library put compiled files under compiled.
  if (file_exists(INTEL_DIR . 'vendor/TimelineJS/compiled/js/storyjs-embed.js')) {
    wp_enqueue_script('intel_timelinejs', INTEL_URL . 'vendor/TimelineJS/compiled/js/storyjs-embed.js');
    //drupal_add_js(intel()->libraries_get_path('TimelineJS') . '/compiled/js/storyjs-embed.js', $options);
  }
  else {
    wp_enqueue_script('intel_timelinejs', INTEL_URL . 'vendor/TimelineJS/build/js/storyjs-embed.js');
    //drupal_add_js(intel()->libraries_get_path('TimelineJS') . '/build/js/storyjs-embed.js', $options);
  }
  $output .= '<script>' . $script . '</script>';
  //wp_add_inline_script('intel_clickstream_timelinejs', $script);
  //$output .= $script;
  //drupal_add_js($script, array('type' => 'inline', 'scope' => 'header'));

  $output =   '<div id="main-timeline" class="timeline"></div><div id="intel-report"><div id="visitor-report">' . $output . '</div></div>';

  return $output;
}

function _intel_format_timelinejs_timestamp($time) {
  //return date("Y,m,d H,i,s", $time);
  return date("m/d/Y H:i:s", $time);
}

function _intel_format_delta_time($secs) {
  $s = floor($secs%60);
  $str = (($s < 10) ? '0' : '') . $s;
  $m = floor($secs/60);
  if ($m == 0) {
    return "0:$str";
  }
  elseif ($m < 60) {
    return "$m:$str";
  }
  else {
    $m = floor($m%60);
    $str = (($m < 10) ? '0' : '') . $m . ":" . $str;
    $h = floor($secs/3600);
    return "$h:$str";
  }
}

function _intel_sort_tldata($a, $b) {
  return ($a['startDate'] > $b['startDate']) ? 1 : -1;
}

function _intel_init_tl_visit($visit, $ts, $visitindex) {
  static $visit_count;
  $visit_count++;
  $ret = array(
    'startDate' => _intel_format_timelinejs_timestamp($ts-1),
    'endDate' => _intel_format_timelinejs_timestamp($ts + $visit['entrance']['sessionDuration']),
    //'headline' => 'Visit ' . $step['visit']['sessionCount'],
    'headline' => Intel_Df::t('Visit'),
    'text' => '',
    'tag' => 'visits',
    'asset' => array(),
    'visitindex' => $visitindex,
    //'visitcount' => $step['visit']['sessionCount'],
    'visitcount' => $visit_count,
    'visitts' => $ts,
  );
  $ret['text'] .= '<p class="header">' . Intel_Df::t('Traffic source') . '</p>';
  $ret['text'] .= Intel_Df::theme('intel_trafficsource', array('trafficsource' => $visit['trafficsource']));
  return $ret;
}

function _intel_init_tl_page($hit, $ts, $visit_ts, $visitindex, $vars = array()) {
  // TODO quite a hackish way to filter off end of page titles
  $site_name = get_option('site_name', "notgonnabeaname");
  $headline = $hit['pageview']['pageTitle'];
  //$headline = str_replace(" | $site_name", "", $headline);
  //$headline = str_replace("$site_name", "", $headline);
  $ret = array(
    'startDate' => _intel_format_timelinejs_timestamp($ts),
    'endDate' => _intel_format_timelinejs_timestamp($ts + $hit['pageview']['timeOnPage']),
    'headline' => $headline,
    'text' => '',
    'tag' => 'pages',
    'asset' => array(
      'media' => "http://" . $hit['pageview']['hostname'] . $hit['pageview']['pagePath'],
      'thumbnail' => 'http://free.pagepeeker.com/v2/thumbs.php?size=x&url=' . $hit['pageview']['hostname'] . $hit['pageview']['pagePath'],
    ),
    'ctaimpressions' => array(),
    'events' => array(),
    'valuedevents' => array(),
    'goals' => array(),
    'originevents' => array(),
    'hostpath' => $hit['pageview']['hostname'] . $hit['pageview']['pagePath'],
    'visitindex' => $visitindex,
  );
  $text = "session: +" . _intel_format_delta_time($ts - $visit_ts) . ', on-page: ' . _intel_format_delta_time($hit['pageview']['timeOnPage']);
  $ret['text'] = $text;
  foreach ($hit['hits'] as $i => $h) {
    if (!empty($h['eventCategory'])) {
      $text = $h['eventCategory'];
      $text .= ': ' . $h['eventAction'];
      if ($h['eventMode'] == 'goal') {
        $ret['goals'][] = $text . ' +' . number_format($h['eventValue'], 2);
      }
      elseif ($h['eventMode'] == 'valued') {
        $ret['valuedevents'][] = $text . ' +' . number_format($h['eventValue'], 2);
      }
      else {
        $ret['events'][] = $text;
      }
    }
  }
  return $ret;
}

function _intel_init_tl_event($hit, $i, $ts, $visit_ts, $visitindex, $vars = array()) {
  $headline = $hit['eventCategory'] . ": " . $hit['eventAction'];
  $text = "session time: +" . _intel_format_delta_time($ts - $visit_ts);
  $tag = 'events';
  if (substr($hit['eventCategory'], -1) == '!') {
    $tag = 'valued-events';
    $headline = $vars['valuetext'] . " " . $headline;
  }
  $ret = array(
    'startDate' => _intel_format_timelinejs_timestamp($ts+1),
    'endDate' => _intel_format_timelinejs_timestamp($ts+1),
    'headline' => $headline,
    'text' => $text . ((!empty($vars['$text'])) ? "<br>\n<br>\n" . $vars['$text'] : ""),
    'tag' => $tag,
    'asset' => array(
      'media' => (!empty($vars['media'])) ? $vars['media'] : "http://" . $step['events'][$i]['hostname'] . $step['events'][$i]['pagePath'],
    ),
    'visitindex' => $visitindex,
  );
  return $ret;
}

/*
function _intel_tl_add_event_to_page(&$tldata, $ts, $text, $type = 'events', $originevent = array()) {
  $vc = 0;
  $tldata['pages'][$ts][$type][] = $text;

  if (count($tldata['pages'][$ts]['goals'])) {
    //$tldata['pages'][$ts]['tag'] = 'goals';
  }
  elseif (count($tldata['pages'][$ts]['valuedevents'])) {
    //$tldata['pages'][$ts]['tag'] = 'valued events';
  }
  if (!empty($originevent)) {
    $type = strtolower($originevent['eventCategory']);
    if (!isset($tldata['pages'][$ts]['originevents'][$type])) {
      $tldata['pages'][$ts]['originevents'][$type] = array();
    }
    $tldata['pages'][$ts]['originevents'][$type][] = $originevent;
  }
}





function theme_intel_visitor_environment($step, $s = '') {
  $output = '';
  $output .= $s . Intel_Df::t('browser') . ': ' . $step['environment']['browser'] . " v" . $step['environment']['browserVersion'] . "<br />\n";
  $output .= $s . Intel_Df::t('operating system') . ': ' . $step['environment']['operatingSystem'] . " " . $step['environment']['operatingSystemVersion'] . "<br />\n";
  if (!empty($step['environment']['mobileDeviceInfo'])) {
    $output .= $s . Intel_Df::t('mobile device') . ': ' . $step['environment']['mobileDeviceInfo'] . "<br />\n";
  }
  $output .= $s . Intel_Df::t('screen resolution') . ': ' . $step['environment']['screenResolution'] . "<br />\n";
  $output .= $s . Intel_Df::t('language') . ': ' . $step['environment']['language'] . "<br />\n";
  return $output;
}


function theme_intel_visitor_referrer($step, $s = '', &$tlvisit) {
  $output = '';
  $tldata_link = '';
  //$tlreferrer = '';
  $tltext = (($tlvisit['text']) ? "<br>\n<br>\n" : '') . '<strong>' . Intel_Df::t('Referrer') . "</strong><br>\n";
  $dldate = array();
  $ref_alts = array(
    'http://google.com/search?q=(not provided)' => 'http://google.com/search',
    'http://facebook.com' => 'http://www.facebook.com'
  );
  if (!isset($step['referrer'])) {
    return $output;
  }
  if ($step['referrer']['medium'] == '(none)') {
    $tltext .= Intel_Df::t("Source") . ': ' . $step['referrer']['source'];
    $output .= $s . Intel_Df::t('source') . ': ' . $step['referrer']['source'] . "<br />\n";
  }
  elseif ($step['referrer']['medium'] == 'referral') {
    $tltext .= Intel_Df::t("Source") . ': ' . $step['referrer']['medium'];
    $output .= $s . Intel_Df::t('source') . ': ' . $step['referrer']['medium'] . "<br />\n";
  }
  else {
    $tltext .= Intel_Df::t("Source") . ': ' . $step['referrer']['medium'] . ' / ' . $step['referrer']['source'];
    $output .= $s . Intel_Df::t('source') . ': ' . $step['referrer']['medium'] . ' / ' . $step['referrer']['source'] . "<br />\n";
  }
  if ($step['referrer']['medium'] != '(none)') {
    if ($step['referrer']['medium'] == 'organic') {
      $output .= $s . Intel_Df::t('keyword') . ': ' . $step['referrer']['keyword'] . "<br />\n";
      if ($step['referrer']['source'] == 'google') {
        $tltext .= "<br>\n" . Intel_Df::t("Keyword") . ": " . $step['referrer']['keyword'];
        $tldata_link = 'http://google.com/search?q=' . $step['referrer']['keyword'];
      }
    }
    if ($step['referrer']['medium'] == 'referral') {
      $url = $step['referrer']['source'] . $step['referrer']['referralPath'];
      $l = Intel_Df::l($url, "http://" . $url, array('attributes' => array('target' => $step['referrer']['source'])));
      $output .= $s . Intel_Df::t('path') . ': ' . $l . "<br />\n";
      $tldata_link = "http://" . $step['referrer']['source'] . $step['referrer']['referralPath'];
      $tltext .= "<br>\n" . $l;
    }
    if ($step['referrer']['socialNetwork'] != '(not set)') {
      $output .= $s . Intel_Df::t('social network') . ': ' . $step['referrer']['socialNetwork'] . "<br />\n";
    }
    if ($step['referrer']['campaign'] != '(not set)') {
      $output .= $s . Intel_Df::t('campaign') . ': ' . $step['referrer']['campaign'] . "<br />\n";
    }
  }

  if (!empty($ref_alts[$tldata_link])) {
    $tldata_link = $ref_alts[$tldata_link];
  }

  if ($tltext) {
    $tlvisit['text'] .= (($tlvisit['text']) ? "<br>\n" : '') . $tltext;
  }

  if ($tldata_link) {
    $tlvisit['asset']['media'] = $tldata_link;
  }

  return $output;
}

function theme_intel_visitor_map($locations, $options = array(), $s = '') {
  $args = func_get_args();
  $vars = array(
    'locations' => array(),
  );
  $vars['locations'][] = array(
    'latitude' => $locations['lat'],
    'longitude' => $locations['lon'],
    'name' => $locations['name'],
  );
  $output = Intel_Df::theme('intel_map', $vars);
  return $output;

  foreach ($locations as $v) {
    $loc = array(
      'latitude' => $v['lat'],
      'longitude' => $v['lon'],
      'name' => $v['name'],
    );
    $vars['locations'][] = $loc;
  }
  $output = Intel_Df::theme('intel_map', $vars);
  return $output;

  static $map_index;
  if (!isset($map_index)) {
    $map_index = 0;
  }
  $div_id = 'map_div_' . $map_index;
  // check if single element was passed
  if (isset($locations['lat'])) {
    $locations = array(
      $locations,
    );
  }
  $mode = 1;
  $output = '';
  if ($mode == 1) {
    //$options = array('type' => 'external', 'weight' => -1);
    $gmap_apikey = get_option('intel_gmap_apikey', '');
    wp_enqueue_script( 'intl_googleapis_map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $gmap_apikey . '&callback=intelInitMap');
    $locstr = '';
    $center = array('lat' => 0, 'lon' => 0, );
    foreach ($locations AS $loc) {
      $locstr .= "[" . $loc['lat'] . ", " . $loc['lon'] . ", " . "'" . $loc['name'] . "'],\n";
      $center['lat'] = $loc['lat'];
      $center['lon'] = $loc['lon'];
    }
    $locstr = substr($locstr, 0, -1); // chop last comma
    //$output .= <<<EOT
    $script = <<<EOT
function initialize_map_$map_index() {
  var mapOptions = {
    zoom: 4,
    center: new google.maps.LatLng({$center['lat']}, {$center['lon']}),
    disableDefaultUI: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  var map = new google.maps.Map(document.getElementById('map-canvas-$map_index'),
      mapOptions);
      
  var circleOptions = {
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 1,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
    map: map,
    center: new google.maps.LatLng({$center['lat']}, {$center['lon']}),
    radius: 50000
  };
  locCircle = new google.maps.Circle(circleOptions);
}
google.maps.event.addDomListener(window, 'load', initialize_map_$map_index);
EOT;
    //drupal_add_js($script, array('type' => 'inline', 'scope' => 'header'));
    $output = $script;
    $output .= $s . '<div id="map-canvas-' . $map_index . '" class="map-canvas"></div>' . "\n";
    $map_index++;
  }
  return $output;
}

function _intel_get_visit_timedif($visits, $timestamp) {
  $timestamp = (int) $timestamp;
  $vi = 0;
  foreach ($visits AS $vt) {
    if (!isset($visits[$vi+1]) || ($visits[$vi+1] > $timestamp)) {
      break;
    }
    $vi++;
  }
  return $timestamp - $visits[$vi];
}

function _intel_init_events_array() {
  $a = array();
  $a['_all'] = _intel_init_events_array_element();
  return $a;
}

function _intel_init_events_array_element() {
  $a = array(
    'value' => 0,
    'totalEvents' => 0,
    'uniqueEvents' => 0,
    'totalValuedEvents' => 0,
    'uniqueValuedEvents' => 0,
  );
  return $a;
}

function _intel_get_page_step_index($steps, $timestamp, $hostname, $pagepath) {
  // check if event is on current page
  if (!empty($steps[$timestamp]['page']['hostname'])) {
    if (($steps[$timestamp]['page']['hostname'] == $hostname) && ($steps[$timestamp]['page']['pagePath'] == $pagepath)) {
      return $timestamp;
    }
  }
  // walk pages array backwards
  end($steps);
  while ( !is_null($ti = key($steps)) ) {
    // if the page element's timestamp is less
    if ($ti <= ($timestamp+1)) {  // note add a second to timestamp to correct for possible timing delays
      if (isset($steps[$ti]['page']) && ($steps[$ti]['page']['hostname'] == $hostname) && ($steps[$ti]['page']['pagePath'] == $pagepath)) {
        return $ti;
      }
    }
    prev($steps);
  }
  return FALSE;
}

function _intel_get_visit_step_index($steps, $timestamp) {
  end($steps);
  while ( !is_null($ti = key($steps)) ) {
    if (isset($steps[$ti]['visit']) && ($ti <= $timestamp)) {
      return $ti;
    }
    prev($steps);
  }
  return FALSE;
}

function _intel_update_event_all_values($all, $event) {
  $all['value'] += $event['value'];
  $all['totalEvents'] += (!empty($event['totalEvents'])) ? $event['totalEvents'] : 1;
  $all['uniqueEvents'] += (!empty($event['uniqueEvents'])) ? $event['uniqueEvents'] : 1;
  $all['totalValuedEvents'] += (!empty($event['totalValuedEvents'])) ? $event['totalValuedEvents'] : (($event['is_valuedevent']) ? 1 : 0);
  $all['uniqueValuedEvents'] += (!empty($event['uniqueValuedEvents'])) ? $event['uniqueValuedEvents'] : (($event['is_valuedevent']) ? 1 : 0);
  return $all;
}



function intel_score_data($data, $mode) {
  if ($mode == 'steps') {
    return intel_score_steps_data($data);
  }
  elseif ($mode == 'pages') {
    return intel_score_pages_data($data);
  }
}

function intel_score_steps_data($steps) {
  $scoring = intel_get_scorings();
//dsm($steps);
//dsm($scoring);
  $datascore = 0;
  $pagescore = 0;
  $pagecount = 0;
  $visitindex = 0;
  foreach ($steps AS $ts => $step) {
    if (substr($ts, 0, 1) != '_') {
      if (isset($step['entrance'])) {
        if ($visitindex) {
          $steps[$visitindex]['entrance']['score'] = $visitscore;
          $datascore += $visitscore;
        }
        $visitindex = $ts;
        $visitscore = 0;
        $visitscore += $scoring['entrance'];
        $visitscore += $step['entrance']['goalValueAll'];
        if (isset($step['entrance']['events']['_all'])) {
          $visitscore += $step['entrance']['events']['_all']['value'];
        }
        $pagecount = 1;
      }

      if (isset($step['pageview'])) {
        $pagescore = 0;
        if (isset($step['pageview']['events']['_all'])) {
          $pagescore += $step['pageview']['events']['_all']['value'];
        }
        $steps[$ts]['pageview']['score'] = $pagescore;

        if ($pagecount == 2) {
          $visitscore += $scoring['stick'];
        }
        elseif ($pagecount > 2) {
          $visitscore += $scoring['additional_pages'];
        }
        $pagecount++;
      }
    }
  }
  if ($visitindex > 1) {
    $steps[$visitindex]['entrance']['score'] = $visitscore;
    $datascore += $visitscore;
    $steps['_score'] = $datascore;
  }
//dsm($steps);
  return $steps;
}
*/