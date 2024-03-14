<?php
/**
 * @file
 * Functions to support extended Google Analytics data.
 *
 * @author Tom McCracken <tomm@getlevelten.com>
 */

//function intel_init_reports_vars($report_name, $report_type, $report_params = '-', $report_subtype = '-', $report_subfilter = '-',  $entity_type = '-', $entity = '-') {
function intel_init_reports_vars($report_name, $report_type, $report_params = '-', $report_subtype = '-', $report_subfilter = '-',  $vars = array()) {
//$args = func_get_args();
//dsm($args);
  if (!empty($_GET['report_params'])) {
    $report_params = $_GET['report_params'];
  }
  if (!is_array($vars)) {
    $vars = array();
  }
  if (!isset($_SESSION['intel_report_vars'])) {
    $_SESSION['intel_report_vars'] = array();
  }
  if (!empty($_SESSION['intel_report_vars']) && is_array($_SESSION['intel_report_vars'])) {
    $vars += $_SESSION['intel_report_vars'];
  }
  $vars += array(
    'title' => '',
    // machine name for report, used to determine dynamic function names
    'report_name' => $report_name,
    // top level class for the report
    'report_type' => $report_type,
    // 2nd level class for the report
    'report_subtype' => (isset($report_subtype) && $report_subtype != '-') ? $report_subtype : '',
    // used by some reports such as trafficsource and page-attr, to clarify
    // the index for the ga data
    'indexBy' => '',
    // used by page-attr reports to the specific attr_info specified in the report
    'report_attr_info' => '',
    // report subfilters work like static filters except that the filter is not
    // persistant across reports and title/header are treated differently
    'report_subfilter_type' => '',
    'report_subfilter' => rawurldecode(($report_subfilter && ($report_subfilter != '-')) ? $report_subfilter : ''),
    // list of params to provide extra report configuration settings
    'report_mode' => 'default.top',
    // the report_mode elements as an array
    'report_modes' => array(),
    // specifies the contextual perspective for analyizing data. E.g. site,
    // visitor, entrances, page (balance of entrance and pageview data).
    // By default, context is based on given filter types. However these can be
    // overridden to provide analysis from a different perspective.
    'context' => '',
    // used to specify secondary context. Currently it is only used to specify
    // if the report should be analyzed as a subsite of the main site.
    'context_mode' => '',
    // the timeframe input for the analysis specified as a timeops string or array of
    // start_time & end_time. Timeframe is designed to use human friendly designators
    'timeframe' => '',
    // unix timestamp for report data starting date
    'start_date' => 0,
    // unix timestamp for report data ending date
    'end_date' => 0,
    // number of days in analysis
    'number_of_days' => 0,
    // number of seconds local timezone if off from UTC
    'timezone_offset' => 0,
    // GA start_date and end_date only pull data for complete days. To get
    // data for only part of a day, hit timestamp filtering must be used.
    // flags to determine if fractional days are used in reports. Set start=1
    // or end=1 to add timestamp filtering to get fractional day data
    'fractional_dates' => array(),
    'cache_options' => array(),
    'comp' => array(),
    'row_count' => 100,
    'feed_rows' => 200,
    'filter_type' => '',
    'filter' => '',
    'subsite' => '',
    'report_args' => array(),
    'report_params' => array(),
    'filter_defs' => array(),
    'filters' => array(),
    'report_info' => array(),
    'report callback' => 'intel_' . $report_name . '_report',
    'entity' => '',
    'entity_type' => '',
    'entities' => array(),
    'attribute_info' => array(
      'page' => array(),
      'visitor' => array(),
    ),
    'related_reports' => array(),
    'extended_mode' => get_option('intel_extended_mode', 0),
  );



  // save end elements of report request uri
  $vars['report_args'] = array_slice(explode('/', $_GET['q']), 3);

  // process special filters that pass in the actual Drupal entity
  if ($vars['entity_type'] == 'node') {
    $sys_path = 'node/' . $vars['entity']->nid;
    // check if page is homepage
    $site_frontpage = get_option('site_frontpage', '');
    $path = ($site_frontpage == $sys_path) ? '/' : url('node/' . $vars['entity']->nid);
    // encode as if it were an url arg
    $report_params .= (($report_params) ? '&' : '') . 'f0=pagePath:' . rawurlencode(str_replace('/', '|', $path));
  }
  elseif ($vars['entity_type'] == 'intel_visitor') {
    if ($vars['report_name'] == 'visitor_clickstream') {
      $vars['report_subfilter'] = 'vtk:' . substr($vars['entity']->vtk, 0, 20);
    }
    else {
      $report_params .= (($report_params) ? '&' : '') . 'f0=vtk:' . substr($vars['entity']->vtk, 0, 20);
    }
  }
  elseif ($vars['entity_type'] == 'og_group') {
    $vars['filter_type'] = 'page-attr';
    // TODO use entity_info to determine id
    if (!is_object($vars['entity']) && is_numeric($vars['entity'])) {
      $vars['entity_id'] = $vars['entity'];
      $vars['entity_type'] = get_option('intel_og_default_group_type', 'node');
      $vars['entity'] = entity_load($vars['entity_type'], array($vars['entity_id']));
      $vars['entity'] = $vars['entity'][$vars['entity_id']];
    }
    $report_params .= (($report_params) ? '&' : '') . 'f0=pa-og:' . $vars['entity_id'];
    //$vars['filter'] = 'og:' . $vars['entity']->id;
    //$vars['og_entity'] = intel_visitor_load($filter_element->vtk);
  }


  $to_vars = array(
    'c' => 'context',
    'cm' => 'context_mode',
    't' => 'timeframe',
    's' => 'subsite',
  );
  $filter_keys = array('df', 's', 'f0', 'f1', 'f2', 'f3');
  $args = explode('&', $report_params);
  foreach ($args AS $kv) {
    $kv = explode('=', $kv);
    if (count($kv) != 2) {
      continue;
    }
    if (in_array($kv[0], $filter_keys)) {
      $fd = $vars['filter_defs'][$kv[0]] = intel_parse_report_filter_arg($kv[1]);
      if (!empty($fd['type'])) {
        if (!isset($vars['filters'][$fd['type']])) {
          $vars['filters'][$fd['type']] = array();
        }
        $vars['filters'][$fd['type']][] = $fd['key'] . ':' . $fd['value'];
      }
    }
    $vars['report_params'][$kv[0]] = $kv[1];
    if (isset($to_vars[$kv[0]])) {
      $vars[$to_vars[$kv[0]]] = $kv[1];
    }
  }

  $reports = intel_reports();
  $report_subtype = strtolower($report_subtype);
  if (isset($reports[$report_type]) && isset($reports[$report_type][$report_subtype])) {
    $vars['report_info'] = $reports[$report_type][$report_subtype];
    if (isset($vars['report_info']['title'])) {
      $vars['title'] = $vars['report_info']['title'];
    }
    if ($vars['report_info']['key']) {
      $vars['report_mode'] = $vars['report_info']['key'];
    }
    if (isset($vars['report_info']['report callback'])) {
      $vars['report callback'] = $vars['report_info']['report callback'];
      if (isset($vars['report_info']['file'])) {
        include_once drupal_get_path('module', 'intel') . '/' . $vars['report_info']['file'];
      }
    }
  }

  if ($vars['subsite']) {
    $vars['filters'] = intel_reports_add_filter($vars['filters'], $vars['subsite'], 'page-attr');
    if (empty($vars['context_mode'])) {
      $vars['context_mode'] = 'subsite';
    }
  }


  /*
  // process static filter
  if ($vars['filter_type']) {
    if ($vars['filter_type'] == 'visitor') {
      $v = explode(':', $vars['filter']);
      $vars['entity'] = intel_visitor_load_or_create($v[1]);
      $vars['entity_type'] = 'intel_visitor';
    }
    $vars['filter'] = rawurldecode($vars['filter']);
    // if filter is landingPagePath or pagePath, replace | encoding with /
    if (strpos($vars['filter'], 'agePath:') || strpos($vars['filter'], 'ostpath:')) {
      $vars['filter'] = str_replace('|', '/', $vars['filter']);
    }

    $vars['filters'] = intel_reports_add_filter($vars['filters'], $vars['filter'], $vars['filter_type']);

    if (!$vars['context'] && ($report_type == 'scorecard' || $report_type == 'content')) {
      $vars['context'] = $vars['filter_type'];
    }
  }
  */

  // process report_subfilter
  if ($vars['report_subfilter']) {
    if ($vars['report_type'] == 'trafficsource') {
      $f0trans = array(
        'searchEngine' => Intel_Df::t('organic'),
        'searchKeyword' => Intel_Df::t('keyword'),
        'referralHostname' => '',
        'referralHostpath' => '',
      );
      $vars['report_subfilter_type'] = 'trafficsource';
      if (substr($vars['report_subfilter'], 0, 3) == 'ts-') {
        $vars['report_subfilter'] = substr($vars['report_subfilter'], 3);
      }
      $a = explode(':', $vars['report_subfilter']);

      if (!isset($vars['filters']['trafficsource'])) {
        $vars['filters']['trafficsource'] = array();
      }
      $vars['filters']['trafficsource'][] = $vars['report_subfilter'];
      $a[1] = rawurldecode($a[1]);
      if ($a[0] == 'searchEngine') {
        $vars['title'] .= ': ' . ucfirst($a[1]);
      }
      else {
        $vars['title'] .= ': ' . $a[1];
      }
      if (isset($f0trans[$a[0]])) {
        if (!empty($f0trans[$a[0]])) {
          $vars['title'] .= ' (' . $f0trans[$a[0]] . ')';
        }
      }
      else {
        $vars['title'] .= ' (' . $a[0] . ')';
      }
    }
    elseif ($vars['report_type'] == 'event') {
      $vars['report_subfilter_type'] = 'event';
      $a = explode(':', $vars['report_subfilter']);
      $a[1] = rawurldecode($a[1]);
      if (!isset($vars['filters']['event'])) {
        $vars['filters']['event'] = array();
      }
      $vars['filters']['event'][] = $vars['report_subfilter'];
      $vars['title'] = Intel_Df::t('Events') . ': ' . $a[1];
    }
    elseif ($vars['report_type'] == 'visitor') {
      $vars['report_subfilter_type'] = 'visitor';
      if (substr($vars['report_subfilter'], 0, 2) == 'v-') {
        $vars['report_subfilter'] = substr($vars['report_subfilter'], 2);
        $a = explode(':', $vars['report_subfilter']);
        if (!isset($vars['filters']['visitor'])) {
          $vars['filters']['visitor'] = array();
        }
        $vars['filters']['visitor'][] = $vars['report_subfilter'];
        $a[1] = rawurldecode($a[1]);
        $vars['title'] .= ': ' . $a[1];
      }
      else {
        if (empty($vars['entity']->id)) {
          $v = explode(':', $vars['report_subfilter']);
          $vars['entity'] = intel_visitor_load_or_create($v[1]);
          $vars['entity_type'] = 'intel_visitor';
        }
        $vars['title'] .= ': ' . $vars['entity']->label();
      }

    }
  }

  if (!$vars['context']) {
    if (!empty($vars['filter_defs']['f0'])) {
      $vars['context'] = $vars['filter_defs']['f0']['type'];
    }
    else {
      if ($report_type == 'content') {
        if (substr($vars['report_subtype'], 0, 3) == 'pa-') {
          $vars['context'] = 'page-attr';
        }
        else {
          $vars['context'] = 'page';
        }
      }
      else {
        $vars['context'] = 'site';
      }
    }
    if ($report_type == 'trafficsource') {
      $vars['context'] = 'trafficsource';
    }
    if ($report_type == 'visitor') {
      $vars['context'] = 'visitor';
    }
  }

  $attr_info = intel_get_page_attribute_info();

  // add any valid dynamic filters in query string
  $valid_filter_types = intel_reports_filter_types();
  foreach ($_GET AS $filter_type => $filter) {
    if (isset($valid_filter_types[$filter_type])) {
      $vars['filters'] = intel_reports_add_filter($vars['filters'], $filter_type, $filter);
    }
  }

  // get attr info if needed
  foreach ($vars['filters'] AS $type => $farr) {
    if ($type == 'page-attr') {
      foreach ($farr AS $i => $filter) {
        $a = explode(':', $filter);
        $vars['attribute_info']['page'][$a[0]] = intel_get_page_attribute_info($a[0]);
      }
    }
    if ($type == 'visitor-attr') {
      foreach ($farr AS $i => $filter) {
        $a = explode(':', $filter);
        $vars['attribute_info']['visitor'][$a[0]] = intel_get_visitor_attribute_info($a[0]);
      }
    }
    if ($type == 'visitor') {
      if (!isset($vars['entities']['intel_visitor'])) {
        $vars['entities']['intel_visitor'] = array();
      }
      foreach ($farr AS $i => $filter) {
        $a = explode(':', $filter);
        $vars['entities']['intel_visitor'][$a[1]] = intel_visitor_load_or_create($a[1]);
      }
    }
  }

  // if page filter exists, try to load the node to get attributes
  if ($vars['filter_type'] == 'page' && empty($vars['entity'])) {
    $a = explode(':', $vars['filter']);
    $url = intel_parse_url($a[1]);
    if (!empty($url['nid'])) {
      $vars['entity'] = node_load($url['nid']);
      $vars['entity_type'] = 'node';
    }
  }

  if ($vars['entity_type'] == 'node') {
    if (!isset($vars['entities']['node'])) {
      $vars['entities']['node'] = array();
    }
    $vars['entities']['node'][$vars['entity']->nid] = $vars['entity'];
    $entity_attrs = intel_get_entity_intel_attributes($vars['entity'], 'node');
  }

  if (!empty($entity_attrs)) {
    foreach ($entity_attrs['page'] AS $attr_key => $value) {
      // skip/disable entity type
      if ($attr_key == 'et') {
        continue;
      }

      $params = array();
      $path0 = intel_build_report_path($vars['report_type'], $params, $vars['report_subtype']);

      // check if user has access to report. We do this by checking if user has
      // access to the segment report.
      // TODO: create method for true filter permission
      $params['_filter_add'] = 'pa-' . $attr_key;
      $attr_rep_path = intel_build_report_path('content', array(), $params['_filter_add']);

      $router_item = menu_get_item($attr_rep_path);

      if ($router_item = menu_get_item($attr_rep_path)) {
        if ($attr_info[$attr_key]['type'] == 'flag') {
          //$vars['related_reports'][] = 'ga:dimension1=@&' . $attr_key . '&';
        }
        elseif (in_array($attr_info[$attr_key]['type'], array('value', 'scalar', 'item'))) {
          $attr_option_info = intel_get_attribute_option_info('page', $attr_key, $value);
          $params['_filter_add'] .= ':' . rawurlencode(!empty($attr_option_info['filter_value']) ? $attr_option_info['filter_value'] : $value);

          // check if report is active TODO: need more elegant way to handel this
          if (!isset($attr_info[$attr_key]['access callback']) || $attr_info[$attr_key]['access callback']) {
            $vars['related_reports'][] = Intel_Df::l($attr_info[$attr_key]['title'] . ': ' . $attr_option_info['title'], intel_build_report_path($vars['report_type'], $params, $vars['report_subtype']));
          }
        }
        elseif ($attr_info[$attr_key]['type'] == 'list') {
          if (is_array($value)) {
            foreach ($value AS $k => $v) {
              $attr_option_info = intel_get_attribute_option_info('page', $attr_key, $k);
              $t = $attr_info[$attr_key]['title'] . ': ' . (isset($attr_option_info['title']) ? $attr_option_info['title'] : $v);

              $params['_filter_add'] .= ':' . rawurlencode($k);

              $vars['related_reports'][] = Intel_Df::l($t, intel_build_report_path($vars['report_type'], $params, $vars['report_subtype']));
            }
          }
        }
      }
    }
  }

  // parse contexts and report_modes into array elements
  $vars['report_modes'] = explode('.', $vars['report_mode']);

  $a = explode('.', $vars['context']);
  if (count($a) == 2) {
    $vars['context'] = $a[0];
    $vars['context_mode'] = $a[1];
  }

  if (!empty($_GET['timeframe'])) {
    $_SESSION['intel_report_vars']['timeframe'] = $vars['timeframe'] = $_GET['timeframe'];
  }

  if (empty($vars['timeframe'])) {
    $vars['timeframe'] = 'l30d';
  }

  list($vars['start_date'], $vars['end_date'], $vars['number_of_days'], $vars['timezone_offset']) = _intel_get_report_dates_from_ops($vars['timeframe'], $vars['cache_options']);

  // check for DateHourMinute format
  $a = explode(',', $vars['timeframe']);
  if (count($a) == 2 && strlen($a[0]) == 12 && strlen($a[1]) == 12) {
    if (!isset($vars['filters']['datetime'])) {
      $vars['filters']['datetime'] = array();
    }
    // make not to use the input timestamp and used the ga timezone corrected start_date & end_date
    $vars['filters']['datetime'][] = "dateHourMinute:" . date('YmdHi', $vars['start_date']) . "-" . date('YmdHi', $vars['end_date']) ;
  }

  if (!empty($_GET['refresh'])) {
    $vars['cache_options']['refresh'] = 1;
  }

  // set report start and end dates
  if ($vars['report_modes'][1] == 'trend') {
    if (empty($vars['comp']['timeframe'])) {
      $vars['comp']['timeframe'] = 'l24h';
      $vars['comp']['fractional_dates'] = array(
        'start' => 1,
      );
    }
    list($vars['comp']['start_date'], $vars['comp']['end_date'], $vars['comp']['number_of_days']) = _intel_get_report_dates_from_ops($vars['comp']['timeframe'], $vars['cache_options']);
    // round end time to the hour to set a one hour cache
    //$vars['comp']['start_date'] = (int)(3600 * round(($vars['comp']['start_date'] + 1800) / 3600));
    $vars['comp']['start_date'] = strtotime('-1 days midnight');
    $vars['comp']['end_date'] = (int)(3600 * round(($vars['comp']['end_date'] + 1800) / 3600))-1;
    $vars['comp']['number_of_days'] = ($vars['comp']['end_date'] - $vars['comp']['start_date']) / (60 * 60 * 24);


    // set refresh options for comp data
    if (!empty($_GET['refresh'])) {
      $vars['comp']['cache_options'] = array('refresh' => 1);
    }
    else {
      $vars['comp']['cache_options'] = array('expire' => $vars['comp']['end_date']);
    }
  }

  return $vars;
}

function intel_build_report_params_arg($params) {
  $str = '';
  $filter_add = '';
  $filter_del = '';
  $ignore = array();
  if (isset($params['_filter_add'])) {
    $filter_add = $params['_filter_add'];
    unset($params['_filter_add']);
  }
  if (isset($params['_filter_only'])) {
    $filter_add = $params['_filter_only'];
    unset($params['_filter_only']);
    $ignore = array('f0', 'f1', 'f2', 'f3');
  }
  if (isset($params['_filter_del'])) {
    $filter_del = $params['_filter_del'];
    unset($params['_filter_del']);
  }
  $filters_index = 0;
  foreach ($params AS $k => $v) {
    if (in_array($k, $ignore) || empty($v)) {
      continue;
    }
    if ($filter_del == $v) {
      continue;
    }
    if (substr($k, 0, 1) == 'f') {
      $k = 'f' . $filters_index;
      $filters_index++;
    }
    if ($str) {
      $str .= '&';
    }
    $str .= "$k=$v";
  }
  if ($filter_add) {
    if ($str) {
      $str .= '&';
    }
    $str .= "f$filters_index=$filter_add";
  }
  return $str;
}

function intel_build_report_path($report_type, $report_params = '', $report_subtype = '', $report_subfilter = '') {
  $path = 'admin/reports/intel/' . $report_type;
// removed for WP
//  if (is_array($report_params)) {
//    $report_params = intel_build_report_params_arg($report_params);
//  }
//  $path .= '/' . (($report_params) ? $report_params : '-');
  if ($report_subtype) {
    $path .= '/' . $report_subtype;
  }
  if ($report_subfilter) {
    //$path .= '/' . $report_subfilter;
  }


  return $path;
}

function intel_build_report_query($report_type, $report_params = '', $report_subtype = '', $report_subfilter = '', $query = array()) {
  if (is_array($report_params)) {
    $query['report_params'] = intel_build_report_params_arg($report_params);
  }
  return $query;
}

function intel_build_report_links($link_keys, $filter, $report_params) {
  $links = array();
  $trans = array(
    'trafficsource' => 'source',
    'visitor' => 'visitors',
  );
  $params = $report_params;
  foreach ($link_keys AS $def) {
    if (!empty($def['subtype'])) {
      $params['_filter_only'] = $filter;
      $text = (isset($trans[$def['subtype']])) ? $trans[$def['subtype']] : $def['subtype'];
      $item = Intel_Df::l($text, intel_build_report_path($def['type'], $params, $def['subtype'], $filter), array('query' => intel_build_report_query($def['type'], $params, $def['subtype'], $filter)));
    }
    else {
      $params['_filter_only'] = $filter;
      $text = (isset($trans[$def['type']])) ? $trans[$def['type']] : $def['type'];
      $item = Intel_Df::l(Intel_Df::t($text), intel_build_report_path($def['type'], $params), array('query' => intel_build_report_query($def['type'], $params)));
    }

    if (count($report_params) > 0) {
      $params = $report_params;
      if (!empty($def['subtype'])) {
        $params['_filter_add'] = $filter;
        $item .= Intel_Df::l('+', intel_build_report_path($def['type'], $params, $def['subtype'], $filter), array('query' => intel_build_report_query($def['type'], $params, $def['subtype'], $filter)));
      }
      else {
        $params['_filter_add'] = $filter;
        $item .= Intel_Df::l('+', intel_build_report_path($def['type'], $params), array('query' => intel_build_report_query($def['type'], $params)));
      }
    }
    $links[] = $item;
  }
  return $links;
}

function intel_build_report($vars) {
  $output = '';
  $vars += array(
    'title' => '',
  );

  // Display messages or hide reports that require data not supported by IAPI level
  $api_level = intel_api_level();
  $error = '';
  $emsgs = array(
    'page-attr' => Intel_Df::t('Page attribute and content segment reporting features are not available with the free version of Intelligence.'),
    'visitor'  => Intel_Df::t('Visitor data and reporting features are not available with the free version of Intelligence.'),
  );
  // Page attribute report api access
  if ($vars['filter_type'] == 'page-attr' || (substr($vars['report_subtype'], 0, 3) == 'pa-')) {
    if ($api_level != 'pro' && $api_level != 'basic') {
      $error = $emsgs['page-attr'];
    }
  }
  // Visitor report api access
  if ($vars['filter_type'] == 'visitor' || $vars['report_type'] == 'visitor') {
    if ($api_level != 'pro') {
      $error = $emsgs['visitor'];
    }
  }


  if (empty($_GET['return_type']) || ($_GET['return_type'] == 'nonajax')) {
    intel_add_report_headers();
    if (!empty($vars['title'])) {
      intel()->set_page_title($vars['title']);
    }
    require_once INTEL_DIR . 'includes/class-intel-form.php';
    //$filter_form = Intel_Form::drupal_get_form('intel_report_filters_form', $vars['filters'], $vars);
    //$output .= Intel_Df::render($filter_form);
    $form = Intel_Form::drupal_get_form('intel_report_filters_form', $vars['filters'], $vars);
    $output .= Intel_Df::render($form);
  }

  if ($error) {
    Intel_Df::drupal_set_message($error, 'error');
  }
  elseif (empty($_GET['return_type']) && empty($vars['return_type'])) {
    $output .= intel_get_report_ajax_container($vars);
  }
  elseif ($_GET['return_type'] == 'nonajax' || (!empty($vars['return_type']) && ($vars['return_type'] == 'nonajax'))) {
    $output .= $vars['report callback']($vars);
  }
  elseif ($_GET['return_type'] == 'json') {
    $data = array(
      'report' => $vars['report callback']($vars),
    );
    drupal_json_output($data);
    return;
  }
  return $output;
}

function theme_intel_report($vars) {
  intel_include_library_file("reports/class.{$vars['report_name']}_report_view.php");

  $output = '';
  // create class name - translate _ format to class cammel case
  if (empty($vars['data']['_empty'])) {
    $class = str_replace('_', ' ', $vars['report_name']);
    $class = ucwords($class);
    $class = 'levelten\intel\\' . str_replace(' ', '', $class) . 'ReportView';
    $report_view = new $class();
    $report_view->setData($vars['data']);
    $report_view->setTableRowCount($vars['row_count']);
    $report_view->setModes($vars['report_modes']);
    $report_view->setParam('context', $vars['context']);
    $report_view->setParam('context_mode', $vars['context_mode']);
    if (isset($vars['goals'])) {
      $report_view->setGoals($vars['goals']);
    }
    if (isset($vars['indexBy'])) {
      $report_view->setParam('indexBy', $vars['indexBy']);
    }
    if (isset($vars['indexByLabel'])) {
      $report_view->setParam('indexByLabel', $vars['indexByLabel']);
    }
    if (isset($vars['report_attribute_info'])) {
      $report_view->setAttributeInfo($vars['report_attribute_info']);
    }
    if (isset($vars['comp']['start_date']) && isset($vars['comp']['end_date'])) {
      $report_view->setDateRange($vars['comp']['start_date'], $vars['comp']['end_date'], $vars['comp']['number_of_days']);
    }
    else {
      $report_view->setDateRange($vars['start_date'], $vars['end_date'], $vars['number_of_days']);
    }
    if (!empty($vars['page_count'])) {
      $report_view->setPageCount($vars['page_count']);
    }
    $report_view->setPageMetaCallback('intel_get_page_meta_callback');
    $report_view->setTargets(intel_get_targets());
    \LevelTen\Intel\ReportPageHeader::setAddScriptCallback('intel_report_add_js_callback');
    $output .= $report_view->renderReport();


  }
  else {
    $output .= '<div class="alert alert-warning">' . Intel_Df::t('No data found.') . '</div>';
  }

  $output .= Intel_Df::t("Time frame: %start_date - %end_date", array(
    '%start_date' => Intel_Df::format_date($vars['start_date'], "M j, Y G:i"),
    '%end_date' => Intel_Df::format_date($vars['end_date'], "M j, Y G:i"),
  ));
  if (isset($vars['comp']['start_date'])) {
    $output .= ', ' . Intel_Df::t("Comp timeframe: %start_date - %end_date", array(
        '%start_date' => Intel_Df::format_date($vars['comp']['start_date'], "M j, Y G:i"),
        '%end_date' => Intel_Df::format_date($vars['comp']['end_date'], "M j, Y G:i"),
      ));
  }
  if (!empty($vars['cache_options']['refresh'])) {
    $output .= ' (' . Intel_Df::t('refresh') . ')';
  }

  if (!empty($vars['cache_options']['round_start']) || !empty($vars['cache_options']['round_end'])) {
    $cache_time = 900;

    $start = $vars['start_date'];
    if (!empty($vars['cache_options']['round_start'])) {
      $start = $cache_time * floor($start / $cache_time);
    }
    $end = $vars['end_date'];
    if (!empty($vars['cache_options']['round_end'])) {
      $end = $cache_time * floor($end / $cache_time);
    }

    $output .= ' ' . Intel_Df::t("(cache: %start_date - %end_date)", array(
        '%start_date' => Intel_Df::format_date($start, "M j, Y G:i"),
        '%end_date' => Intel_Df::format_date($end, "M j, Y G:i"),
      ));
  }


  $query = $_GET;
  $query['refresh'] = 1;
  $l_options = Intel_Df::l_options_add_query($query);
  $output .= ' ' . Intel_Df::l(Intel_Df::t('Clear cache'), Intel_Df::current_path(), $l_options);

  return $output;
}

function intel_parse_report_filter_arg($str) {

  $types = array(
    'trafficcategory' => 'trafficsource',
    'pagePath' => 'page',
    'landingPagePath' => 'page',
    'event' => 'event',
    'eventCategory' => 'event',
    'vtk' => 'visitor',
  );

  $a = explode(':', $str, 2);
  $fd = array(
    'type' => '',
    'key' => $a[0],
    'value' => $a[1],
  );
  $b = explode('-', $a[0]);
  if (isset($b[1])) {
    $fd['key'] = $b[1];
  }

  if ($b[0] == 'pa') {
    $fd['type'] = 'page-attr';
  }
  elseif ($b[0] == 'va') {
    $fd['type'] = 'visitor-attr';
  }
  elseif ($b[0] == 'v') {
    $fd['type'] = 'visitor';
  }
  elseif ($b[0] == 'ts') {
    $fd['type'] = 'trafficsource';
  }
  elseif (isset($types[$fd['key']])) {
    $fd['type'] = $types[$fd['key']];
  }
  else {
    return array();
  }

  // decode url entities
  $fd['value'] = rawurldecode($fd['value']);

  // reverse slash to pipe translations for pages and traffic source referrals
  if ($fd['type'] == 'page' || (strpos($fd['key'], 'referral') === 0)) {
    $fd['value'] = str_replace('|', '/', $fd['value']);
  }

  return $fd;
}

/**
 * Builds a new ga data set using the difference between to other data sets
 *
 * @param $data: baseline data
 * @param $data_comp
 */
function intel_build_report_data_delta(&$data, &$data_comp, $vars) {
  $delta = array();
  $data_scopes = array(
    'entrance' => 1,
    'pageview' => 1,
    'score' => 1,
  );
  foreach ($data_comp AS $index => $de) {
    // normalize comp data by number of days
    foreach ($data_scopes AS $scope => $flag) {
      if (isset($data_comp[$index][$scope])) {
        if (is_array($data_comp[$index][$scope])) {
          foreach ($data_comp[$index][$scope] AS $k => $v) {
            if (!is_array($data_comp[$index][$scope][$k])) {
              $data_comp[$index][$scope][$k] /= $vars['comp']['number_of_days'];
            }
          }
        }
        else {
          if (isset($data_comp[$index][$scope])) {
            $data_comp[$index][$scope] /= $vars['comp']['number_of_days'];
          }
        }
      }
    }

    $delta[$index] = $data_comp[$index];

    if (isset($data[$index])) {
      foreach ($data_scopes AS $scope => $flag) {
        if (isset($delta[$index][$scope]) && $data[$index][$scope]) {
          if (is_array($delta[$index][$scope])) {
            foreach ($delta[$index][$scope] AS $k => $v) {
              if (isset($data[$index][$scope][$k]) && !is_array($data[$index][$scope][$k])) {
                $delta[$index][$scope][$k] -= ($data[$index][$scope][$k] / $vars['number_of_days']);
              }
            }
          }
          else {
            if (isset($data[$index][$scope])) {
              $delta[$index][$scope] -= ($data[$index][$scope] / $vars['number_of_days']);
            }
          }
        }
      }
    }
  }

  // look for largest losses by looking for items in data but not in data_comp
  foreach ($data AS $index => $de) {
    // if entry already exists, skip
    if (isset($delta[$index])) {
      continue;
    }
    $delta[$index] = $data[$index];
    foreach ($data_scopes AS $scope => $flag) {
      if (isset($delta[$index][$scope])) {
        if (is_array($delta[$index][$scope])) {
          foreach ($delta[$index][$scope] AS $k => $v) {
            if (!is_array($delta[$index][$scope][$k])) {
              $delta[$index][$scope][$k] = -1 * $delta[$index][$scope][$k] / $vars['number_of_days'];
            }
          }
        }
        else {
          $delta[$index][$scope] = -1 * $delta[$index][$scope] / $vars['number_of_days'];
        }
      }
    }
  }
  return $delta;
}


function intel_reports_add_filter($filters, $filter, $filter_type = '-') {
  if (!isset($filters[$filter_type])) {
    $filters[$filter_type] = array();
  }
  $filters[$filter_type][] = rawurldecode($filter);
  return $filters;
}

function intel_reports_filter_types() {
  array(
    'page' => Intel_Df::t('Page'),
    'page-attr' => Intel_Df::t('Page attribute'),
    'event' => Intel_Df::t('Event'),
    'trafficsource' => Intel_Df::t('Traffic source'),
    'visitor' => Intel_Df::t('Visitor'),
    'visitor-attr' => Intel_Df::t('Visitor attribute'),
    'location' => Intel_Df::t('Location'),
  );
}

function intel_report_filters_form($form, &$form_state, $filters = array(), $vars = array()) {
  //$access = user_access('view all intel reports');
  // TODO: this is a hack, work out more elegant way of handeling permissions
  //if (!$access) {
  //  drupal_add_css('.intel-static-filter, .intel-related-links {display: none;} #intel-report a.intel-more {display: none;}', array('group' => CSS_THEME, 'type' => 'inline'));
  //}

  $dynamic_filters = array();
  $context = (!empty($vars['context'])) ? $vars['context'] : '';

  $cur_path = Intel_Df::current_path();
  $cur_path_args = explode('/', $cur_path);
  if (count($cur_path_args) != 3 || ($cur_path_args[0] != 'visitor') || ($cur_path_args[2] != 'clickstream')) {
    $options = array(
      'l30d' => Intel_Df::t('Last 30 days'),
      'l28d' => Intel_Df::t('Last 28 days'),
      'l7d' => Intel_Df::t('Last 7 days'),
      'yesterday' => Intel_Df::t('Yesterday'),
      'thismonth' => Intel_Df::t('This month'),
      'thisweek' => Intel_Df::t('This week'),
      'p30d' => Intel_Df::t('Prior 30 days before last'),
      'p28d' => Intel_Df::t('Prior 28 days before last'),
      'p7d' => Intel_Df::t('Prior 7 days before last'),
      'p1d' => Intel_Df::t('Day before yesterday'),
      'lastmonth' => Intel_Df::t('Last month'),
      'lastweek' => Intel_Df::t('Last week'),
      'l30dfn' => Intel_Df::t('Last 30 days from now'),
      'l28dfn' => Intel_Df::t('Last 28 days from now'),
      'l7dfn' => Intel_Df::t('Last 7 days from now'),
      'today' => Intel_Df::t('Today'),
      'monthtodate' => Intel_Df::t('Month to now'),
      'weektodate' => Intel_Df::t('Week to now'),
    );
    if (date('j') == 1) {
      unset($options['thismonth']);
    }
    if (date('w') == 0) {
      unset($options['thisweek']);
    }

    //$l_options = Intel_Df::l_options_add_class();
    //$btn =
    $form['timeframe'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Time frame'),
      '#id' => 'timeframe',
      '#options' => $options,
      '#default_value' => $vars['timeframe'],
      //'#prefix' => '',
      '#field_prefix' => Intel_Df::format_date($vars['start_date'], 'M j, Y') . ' - ' . Intel_Df::format_date($vars['end_date'], 'M j, Y'),
    );

  }

  $form['filters'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Filters'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $events = array(
    '' => Intel_Df::t('-'),
  );
  $metas = intel_get_intel_event_info();
  foreach ($metas AS $meta) {
    if (isset($meta['category'])) {
      $events[$meta['category']] = $meta['title'];
    }
  }
  $trafficsource_types = array(
    '' => Intel_Df::t('-'),
    'trafficcategory' => Intel_Df::t('Category'),
    'source' => Intel_Df::t('Source'),
    'medium' => Intel_Df::t('Medium'),
    'referralPath' => Intel_Df::t('Referral path'),
    'socialNetwork' => Intel_Df::t('Social network'),
    'keyword' => Intel_Df::t('Keyword'),
  );

  if ($context != 'page') {
    $modes = array(
      '' => '-',
      'landingPagePath' => Intel_Df::t('Entrance'),
      'pagePath' => Intel_Df::t('View'),
    );
    $mode_default = 'entrance';
    $path_default = '';
    if (!empty($filters['page'])) {
      $a = explode(':', $filters['page'][0]);
      $mode_default = $a[0];
      $path_default = $a[1];
      $dynamic_filters['page'] = "{$a[0]}={$a[1]}";
    }
    $form['filters']['page_mode'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Page mode'),
      '#id' => 'page-mode',
      '#options' => $modes,
      '#default_value' => $mode_default,
      '#prefix' => '<div class="filter-fieldset filter-fieldset-page">',
    );
    $form['filters']['page_path'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Page path'),
      '#id' => 'page-path',
      '#size' => 80,
      '#default_value' => $path_default,
      '#suffix' => '</div>',
    );
  }

  if ($context != 'page-attr') {
    $pa_options = array(
      '' => Intel_Df::t('-'),
    );
    $pa_options = array_merge($pa_options, intel_get_field_page_attribute_allowed_values());
    $pa_type_default = '';
    $pa_value_default = '';
    if (!empty($filters['page-attr'])) {
      $a = explode(':', $filters['page-attr'][0]);
      $va_type_default = $a[0];
      $va_value_default = $a[1];
      $dynamic_filters['page-attr'] = "{$a[0]}={$a[1]}";
    }
    $form['filters']['page_attr_type'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Page attribute'),
      '#options' => $pa_options,
      '#default_value' => $pa_type_default,
      '#prefix' => '<div class="filter-fieldset filter-fieldset-visitor-attr">',
    );
    $form['filters']['page_attr_value'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Page attribute value'),
      '#id' => 'page-attr-value',
      '#size' => 80,
      '#default_value' => $pa_value_default,
      '#suffix' => '</div>',
    );
  }

  if ($context != 'trafficsource') {
    $trafficsource_type_default = '';
    $trafficsource_value_default = '';
    if (!empty($filters['trafficsource'])) {
      $a = explode(':', $filters['trafficsource'][0]);
      $trafficsource_type_default = $a[0];
      $trafficsource_value_default = $a[1];
      $dynamic_filters['trafficsource'] = "{$a[0]}={$a[1]}";
    }
    $form['filters']['trafficsource_type'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Traffic source type'),
      '#id' => 'trafficsource-type',
      '#options' => $trafficsource_types,
      '#default_value' => $trafficsource_type_default,
      '#prefix' => '<div class="filter-fieldset filter-fieldset-referrer">',
    );
    $form['filters']['trafficsource_value'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Traffic source value'),
      '#id' => 'trafficsource-value',
      '#size' => 80,
      '#default_value' => $trafficsource_value_default,
      '#suffix' => '</div>',
    );
  }

  $loc_types = array(
    '' => Intel_Df::t('-'),
    'subContinent' => Intel_Df::t('Sub Continent'),
    'country' => Intel_Df::t('Country'),
    'region' => Intel_Df::t('Region / state'),
    'city' => Intel_Df::t('City'),
    'metro' => Intel_Df::t('Metro'),
  );
  $loc_type_default = '';
  $loc_value_default = '';
  if (!empty($filters['location'])) {
    $a = explode(':', $filters['location'][0]);
    $loc_type_default = $a[0];
    $loc_value_default = $a[1];
    $dynamic_filters['location'] = "{$a[0]}={$a[1]}";
  }
  $form['filters']['location_type'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Location type'),
    '#id' => 'location-type',
    '#options' => $loc_types,
    '#default_value' => $loc_type_default,
    '#prefix' => '<div class="filter-fieldset filter-fieldset-location">',
  );
  $form['filters']['loc_value'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Location value'),
    '#id' => 'location-value',
    '#size' => 80,
    '#default_value' => $loc_value_default,
    '#suffix' => '</div>',
  );

  if ($context != 'visitor') {
    $visitor_options = array(
      '' => Intel_Df::t('-'),
      'vtkid' => Intel_Df::t('By visitor token'),
      'email' => Intel_Df::t('By email address'),
    );
    $visitor_type_default = '';
    $visitor_value_default = '';
    if (!empty($filters['visitor'])) {
      $a = explode(':', $filters['visitor'][0]);
      $visitor_type_default = $a[0];
      $visitor_value_default = $a[1];
      $dynamic_filters['visitor'] = "{$a[0]}={$a[1]}";
    }
    $form['filters']['visitor_type'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Visitor'),
      '#id' => 'visitor-type',
      '#options' => $visitor_options,
      '#default_value' => $visitor_type_default,
      '#prefix' => '<div class="filter-fieldset filter-fieldset-visitor">',
    );
    $form['filters']['visitor_value'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Visitor value'),
      '#id' => 'visitor-value',
      '#size' => 80,
      '#default_value' => $visitor_value_default,
      '#suffix' => '</div>',
    );

    $va_options = array(
      '' => Intel_Df::t('-'),
    );
    $va_options = array_merge($va_options, intel_get_field_visitor_attribute_allowed_values());
    $va_type_default = '';
    $va_value_default = '';
    if (!empty($filters['visitor-attr'])) {
      $a = explode(':', $filters['visitor-attr']);
      $va_type_default = $a[0];
      $va_value_default = $a[1];
      $dynamic_filters['visitor-attr'] = "{$a[0]}={$a[1]}";
    }
    $form['filters']['visitor_attr_type'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Visitor attribute'),
      '#id' => 'visitor-attr-type',
      '#options' => $va_options,
      '#default_value' => $va_type_default,
      '#prefix' => '<div class="filter-fieldset filter-fieldset-visitor-attr">',
    );
    $form['filters']['visitor_attr_value'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Visitor attribute value'),
      '#id' => 'visitor-attr-value',
      '#size' => 80,
      '#default_value' => $va_value_default,
      '#suffix' => '</div>',
    );
  }

  $form['filters']['apply'] = array(
    '#type' => 'markup',
    '#markup' => '<input type="button" id="apply-report-filter" value="Apply" class="form-submit">',
  );

  // do not display form if not in extended mode
  if (!intel_is_extended()) {
    unset($form['filters']);
  }

  $link_keys = array(
    'scorecard' => Intel_Df::t('scorecard'),
    'trafficsource' => Intel_Df::t('sources'),
    'content' => Intel_Df::t('content'),
    'visitor' => Intel_Df::t('visitors'),
  );
  $link_base_path = 'admin/reports/intel';

  if (!empty($vars['subsite'])) {
    $filter = explode(':', $vars['subsite']);
    $pa_info = intel_get_page_attribute_info($filter[0]);
    //$title = '<strong>' . Intel_Df::t('Page group') . '</strong>';
    $title = '<strong>' . Intel_Df::t('Subsite') . '</strong>';
    if (isset($pa_info['title'])) {
      $title .= ': ' . $pa_info['title'];
      $pa_option_info = intel_get_attribute_option_info('page', $filter[0], $filter[1]);
      $title .= ': ' . (!empty($pa_option_info['title']) ? $pa_option_info['title'] : $filter[1]);
    }
    else {
      $title .= ': ' . $filter[0] . ": " . $filter[1] . "<br />\n";
    }

    if (!empty($link_base_path)) {
      //$links[] = Intel_Df::l(t('x'), $link_base_path . '/' . $vars['report_args'][0];
      /*
      foreach ($link_keys AS $key => $t) {
        $args = array_slice($vars['report_args'], 1);
        $links[] = Intel_Df::l($t, $link_base_path . '/' . $key . '/-/-/-/-/-/'. $vars['report_args'][5]);
      }
      */
    }
    $title = '<div class="intel-static-subsite" style="font-size: 115%;">' . $title;

    if (!empty($links)) {
      $title .= ' [' . implode('|', $links) . ']';
    }
    $title .= '</div>';

    $form['subsite_display'] = array(
      '#type' => 'markup',
      '#markup' => $title,
    );
  }

  $filter_keys = array('f0', 'f1', 'f2', 'f3');
  $filter_type_titles = array(
    'trafficsource' => Intel_Df::t('Source'),
    'page' => Intel_Df::t('Page'),
    'page-attr' => Intel_Df::t('Page attribute'),
    'event' => Intel_Df::t('Event'),
    'visitor' => Intel_Df::t('Visitor'),
  );
  $filter_items = array();
  foreach ($filter_keys AS $filter_key) {
    if (!isset($vars['filter_defs'][$filter_key])) {
      break;
    }
    $fd = $vars['filter_defs'][$filter_key];
    $item = '<strong>' . $filter_type_titles[$fd['type']] . '</strong>';
    if ($fd['type'] == 'trafficsource') {
      $item .= ': ' . (isset($trafficsource_types[$fd['key']]) ? $trafficsource_types[$fd['key']] : $fd['key']) . ": " . $fd['value'];
    }
    elseif ($fd['type'] == 'page') {
      $item .= ': ' . $fd['value'];
    }
    elseif ($fd['type'] == 'page-attr') {
      $pa_info = intel_get_page_attribute_info($fd['key']);
      if (isset($pa_info['title'])) {
        $item .= ': ' . $pa_info['title'];
        $pa_option_info = intel_get_attribute_option_info('page', $fd['key'], $fd['value']);
        $item .= ': ' . (!empty($pa_option_info['title']) ? $pa_option_info['title'] : $fd['value']);
      }
      else {
        $item .= ': ' . $fd['key'] . ": " . $fd['value'];
      }
    }
    elseif ($fd['type'] == 'event') {
      $pa_info = intel_get_page_attribute_info($fd['key']);
      if (isset($pa_info['title'])) {
        $item .= ': ' . $pa_info['title'];
        $pa_option_info = intel_get_attribute_option_info('page', $fd['key'], $fd['value']);
        $item .= ': ' . (!empty($pa_option_info['title']) ? $pa_option_info['title'] : $fd['value']);
      }
      else {
        $item .= ': ' . str_replace('->', ' > ', $fd['value']);
      }
    }
    elseif ($fd['type'] == 'visitor') {
      if ($fd['key'] == 'vtk') {
        if (isset($vars['entities']['intel_visitor'][$fd['value']])) {
          $item .= ': ' . $vars['entities']['intel_visitor'][$fd['value']]->label();
        }
        else {
          $item .= ': ' . $fd['value'];
        }
      }
      else {
        $item .= ': ' . $fd['key'] . ": " . $fd['value'];
      }
    }
    else {
      $item .= ': ' . $fd['key'] . ": " . $fd['value'];
    }

    if (isset($link_keys[$vars['filter_type']])) {
      unset($link_keys[$vars['filter_type']]);
    }
    if ($vars['filter_type'] == 'page') {
      unset($link_keys['content']);
    }

    $links = array();
    if (!empty($link_base_path)) {
      // don't show clear link if reports shown on entity tab
      if (strpos($_GET['q'], 'admin/reports/intel/') === 0) {
        $params = $vars['report_params'];
        $params['_filter_del'] = $vars['report_params'][$filter_key];
        $links[] = Intel_Df::l(Intel_Df::t('x'), intel_build_report_path($vars['report_type'], $params, $vars['report_subtype']));
      }
      if (strpos($_GET['q'], 'admin/reports/intel/') !== 0) {
        foreach ($link_keys AS $key => $t) {
          $args = array_slice($vars['report_args'], 1);
          $links[] = Intel_Df::l($t, intel_build_report_path($key, $vars['report_params'], $vars['report_subtype']));
        }
      }
    }
    $item = '<div class="intel-static-filter">' . $item;

    if (!empty($links)) {
      $item .= ' [' . implode(' ', $links) . ']';
    }
    $item .= '</div>';
    $filter_items[] = $item;
  }

  if (!empty($filter_items)) {
    $form['static_filters_display'] = array(
      '#type' => 'markup',
      '#markup' => implode("\n", $filter_items),
    );
  }

  // create static filter header
  if (!empty($vars['filter_type'])) {
    $filter = explode(':', $vars['filter'], 2);
    $title = '';

    if ($vars['filter_type'] == 'page') {
      $title = '<strong>' . Intel_Df::t('Page') . '</strong>' . ': ' . $filter[1];
    }
    if ($vars['filter_type'] == 'page-attr') {
      $pa_info = intel_get_page_attribute_info($filter[0]);
      $title = '<strong>' . Intel_Df::t('Page attribute') . '</strong>';

    }
    if ($vars['filter_type'] == 'event') {
      $title = '<strong>' . Intel_Df::t('Event') . '</strong>' . ': ' . str_replace('->', ' > ', $filter[1]);
    }
    if ($vars['filter_type'] == 'trafficsource') {
      $title = '<strong>' . Intel_Df::t('Traffic source') . '</strong>' . ': ' . $filter[1] . " ({$filter[0]})";
    }
    if ($vars['filter_type'] == 'visitor') {
      $title = '<strong>' . Intel_Df::t('Visitor') . '</strong>' . ': ';
      $title .= (($vars['entity_type'] == 'intel_visitor' && !empty($vars['entity'])) ? $vars['entity']->label() : $filter[1]);
    }

    if ($title) {




    }
  }

  if (!empty($vars['related_reports'])) {
    $related = implode(', ', $vars['related_reports']);
    $form['related_reports'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="intel-related-links">' . Intel_Df::t('related') . ': ' . $related . '</div>',
    );
  }


  if (isset($filters_display) && count($filters_display)) {
    $form['filters_display'] = array(
      '#type' => 'markup',
      '#markup' => '<strong>' . Intel_Df::t('Filters') . '</strong>' . ': ' . implode(', ', $filters_display),
    );
  }



  return $form;
}

function intel_add_report_headers() {
  wp_enqueue_script( 'intel_google_jsapi_wp', 'https://www.google.com/jsapi');
  wp_enqueue_script( 'intel_reports', INTEL_URL . 'js/intel.report.js');
  wp_enqueue_script( 'intel_lib_charts', INTEL_URL . 'vendor/levelten/intel/charts/charts.js');
  wp_enqueue_style( 'intel_reports_css', INTEL_URL . 'css/intel.report.css');



  /*
  drupal_add_js('https://www.google.com/jsapi', array('type' => 'external', 'scope' => 'header'));
  drupal_add_js("google.load('visualization', '1', {packages: ['corechart']});google.load('visualization', '1', {packages: ['table']});", array('type' => 'inline', 'scope' => 'header'));
  drupal_add_js(libraries_get_path('LevelTen') . '/Intel/charts/charts.js');
  drupal_add_css(drupal_get_path('module', 'intel') . '/css/intel.report.css');
  drupal_add_js(drupal_get_path('module', 'intel') . '/js/intel.report.js');
  */
}



function intel_get_report_ajax_container($vars) {
  $gmap_apikey = get_option('intel_gmap_apikey', '');
  wp_enqueue_script( 'intel_googleapis_map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $gmap_apikey . '&callback=_intel_googleapi_map_init');

  $query = '';
  if (!empty($vars['query'])) {
    foreach ($vars['query'] as $k => $v) {
      if ($query) {
        $query .= '&';
      }
      $query .= $k . '=' . $v;
    }
  }

  $output = '<div id="intel-report-container" data-q="' . (!empty($vars['q']) ? $vars['q'] : $_GET['q']) . '" data-page="' . (!empty($vars['page']) ? $vars['page'] : $_GET['page']) . '" data-query="' . $query . '" data-current-path="' . (!empty($vars['current_path']) ? $vars['current_path'] : '') . '" data-dates="' . ((!empty($_GET['dates'])) ? $_GET['dates'] : '') . '" data-refresh="' . ((!empty($_GET['refresh'])) ? 'refresh' : '') . '"><div class="loader"><img src="' . INTEL_URL . 'images/ajax_loader_report.gif' . '" alt="loading Intelligence"><br>Gathering Intelligence...</div></div>';

  $output .= '<script>_intel_googleapi_map_init = function () {}</script>';

  return $output;
}

function intel_get_ga_property_slug() {
  $slug = get_option('intel_ga_property_slug', '');
  $slug = 'a5541069w10694645p75915226';
  //drupal_alter('intel_ga_property_slug', $slug);
  return $slug;
}

