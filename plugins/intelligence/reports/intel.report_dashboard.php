<?php
/**
 * @file
 * Generates dashboard report
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

//function intel_dashboard_report_page($report_subtype = '-', $context = '-', $timeframe = '-', $filter_type = '-', $filter_element = '-', $subsite = '-') {
function intel_dashboard_report_page($report_params = '-', $report_subtype = '-', $report_subfilter = '-', $entity_type = '-', $entity = '-', $vars = array()) {
  //$args = func_get_args();
  //dsm($args);

  require_once INTEL_DIR . "includes/intel.reports.php";
  require_once INTEL_DIR . "includes/intel.ga.php";
  $output = '';
  return 'TODO WP';

  //$vars = array();
  if (!empty($entity_type) && $entity_type != '-') {
    $vars['entity_type'] = $entity_type;
    $vars['entity'] = $entity;
  }
  $vars = intel_init_reports_vars('dashboard', 'dashboard', $report_params, $report_subtype, $report_subfilter, $vars);
  $vars['title'] = $title = t('Dashboard');

  $output = intel_build_report($vars);

  /*
  $vars = intel_init_reports_vars('dashboard', 'dashboard', $report_subtype, $context, $timeframe, $filter_type, $filter_element, $subsite);
  $vars['title'] = $title = t('Dashboard');
  $output = intel_build_report($vars);

  */
  return $output;
}
/*
function intel_dashboard_report_page($sub_index = '-', $filter_type = '', $filter_element = '', $filter_value = '') {
  $args = func_get_args();
  dsm($args);
  $output = '';
  $filters = array();
  $reports = intel_reports();
  $context = 'site';
  if ($filter_type) {
    if ($filter_type == 'node') {
      $filter_type = 'page';
      $filter_element = 'landingPagePath:' . url('node/' . $filter_element->nid);
    }
    if ($filter_value) {
      $filter_element .= ':' . $filter_value;
    }
    $filters[$filter_type] = urldecode($filter_element);
    $context = $filter_type;
  }
  require_once drupal_get_path('module', 'intel') . "/includes/intel.ga.inc";
  
  if (empty($_GET['return_type']) || ($_GET['return_type'] == 'nonajax')) {
    intel_add_report_headers();

    drupal_set_title(t('Dashboard'), PASS_THROUGH);  
  }
  
  if (empty($_GET['return_type'])) {
    $output .= intel_get_report_ajax_container();
  }
  elseif ($_GET['return_type'] == 'nonajax') {
    $output .= intel_dashboard_report($filters, $context, $sub_index);
  }
  elseif ($_GET['return_type'] == 'json') {
    $data = array(
      'report' => intel_dashboard_report($filters, $context, $sub_index),
    );
    drupal_json_output($data);
    return;    
  }  
   
  return $output;
}
*/

//function intel_dashboard_report($filters = array(), $context = 'site', $sub_index = '-') {
function intel_dashboard_report($vars) {
  //dsm($vars);
  intel_include_library_file('ga/class.ga_model.php');
  require_once drupal_get_path('module', 'intel') . "/includes/intel.page_data.php";

  $filters = $vars['filters'];
  $context = $vars['context'];
  $context_mode = $vars['context_mode'];

  $report_mode = !empty($vars['report_info']['key']) ? $vars['report_info']['key'] : 'default.top.combined';
  $report_modes = explode('.', $report_mode);
  intel_include_library_file('ga/class.ga_model.php');
  
  $sub_index = !empty($vars['report_info']['key']) ? $vars['report_info']['key'] : '.default';

  $report_modes = explode('.', $sub_index);

  $cache_options = array();
  
  $output = '';    
  $filters += $_GET;
  
  if (!empty($filters['page'])) {
    $a = explode(":", $filters['page']);
    $path = $a[1];
  }
  
  $cur_month = 1;
  $report_time = REQUEST_TIME;
  if ($vars['timeframe'] != 'l30d') {
    $report_time = $vars['end_date'];
    $cur_month = 0;
  }
  $a = explode('-', $report_modes[0]);
  if ((count($a) == 2) ) {
    $target_yr = $a[0];
    $target_mo = $a[1];
    $cur_month = 0;
  }
  else {
    $target_yr = (int)date('Y', $report_time);
    if (!empty($a[0])) {
      $target_mo = $a[0];
    }
    else {
      $target_mo = (int)date('m', $report_time);
    }
  }

  $last_mo = $target_mo-1;
  $last_yr = $target_yr;
  if ($last_mo == 0) {
    $last_mo = 12;
    $last_yr--;
  }
  $next_mo = $target_mo+1;
  $next_yr = $target_yr;
  if ($next_mo == 13) {
    $next_mo = 1;
    $next_yr++;
  }
  list($lastmonth_start_date, $lastmonth_end_date, $lastmonth_number_of_days) = _intel_get_report_dates("$last_mo/1/$last_yr", "$target_mo/1/$target_yr - 1 second");

  list($start_date, $end_datemo, $number_of_days) = _intel_get_report_dates("$target_mo/1/$target_yr", "$next_mo/1/$next_yr - 1 second");
  // round end date to next hour to cache per hour
  if (!$cur_month) {
    $end_date = $end_datemo;
  }
  else {
    $end_date = (int)(3600 * round((time() + 1800) / 3600));
  }

  if (!empty($_GET['refresh'])) {
    $cache_options = array('refresh' => 1);
  }
  else {
    $cache_options = array('expire' => $end_date);
  }

  $filter_info = array();
  if (!empty($filters)) {
    if (isset($filters['page-attr'][0])) {
      $a = explode(':', $filters['page-attr'][0]);
      $filter_info['page-attr'] = intel_get_page_attribute_info($a[0]);
    }
  }
  
  // get last month's data  
  $last_month_data = new LevelTen\Intel\GAModel();
  $last_month_data->setContext($vars['context']);
  $last_month_data->setContextMode($vars['context_mode']);
  $last_month_data->setAttributeInfoAll($vars['attribute_info']);
  $last_month_data->buildFilters($filters, $vars['subsite']);
  $last_month_data->setDateRange($lastmonth_start_date, $lastmonth_end_date);
  $last_month_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $cache_options));
  
  $last_month_data->setRequestSetting('indexBy', 'date');
  $last_month_data->setRequestSetting('details', 0);
  $last_month_data->setRequestDefaultParam('max_results', $lastmonth_number_of_days);

  //$last_month_data->setDebug(1);
  $last_month_data->loadFeedData('entrances');

  $last_month_data->setRequestSetting('details', 1);
  $last_month_data->setRequestDefaultParam('max_results', 10 * $lastmonth_number_of_days);
  $last_month_data->loadFeedData('entrances_events_valued');
  //$last_month_data->setDebug(0);

  // get this month's data
  $ga_data = new LevelTen\Intel\GAModel();
  $ga_data->setContext($vars['context']);
  $ga_data->setContextMode($vars['context_mode']);
  $ga_data->setAttributeInfoAll($vars['attribute_info']);
  $ga_data->buildFilters($filters, $context);
  $ga_data->setDateRange($start_date, $end_date);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $cache_options));

  $ga_data->setRequestSetting('indexBy', 'date');
  $ga_data->setRequestSetting('details', 0);
  $ga_data->setRequestDefaultParam('max_results', $number_of_days);
//$last_month_data->setDebug(1);
  $ga_data->loadFeedData('entrances'); 
//$last_month_data->setDebug(0);  
  $ga_data->setRequestSetting('details', 1);
  $ga_data->setRequestDefaultParam('max_results', 10 * $number_of_days);
  $ga_data->loadFeedData('entrances_events_valued');
  
  $ga_data->setRequestDefaultParam('max_results', 5 * $number_of_days);
  $cg = get_option('intel_goals', array());
  $conversion_goals = array();
  foreach ($cg as $v) {
    if (!empty($cg['context']['submission']) || !empty($cg['context']['phonecall'])) {
      $conversion_goals[] = $v;
    }
  }


  $i = 0;
//$ga_data->setDebug(1);
  foreach ($conversion_goals AS $key => $goal) {
    if ($i == 0) {
      $details = array();
    }
    $id = $conversion_goals[$key]['ga_id'];
    $details[] = $id;      
    $goals["n$id"] = $conversion_goals[$key]['title'];
    $i++;
    if ($i >= 5) {
      $ga_data->loadFeedData('entrances_goals', 'date', $details); 
      $i = 0;
    } 
  }
  if ($i > 0) {
    $ga_data->loadFeedData('entrances_goals', 'date', $details);
  }
//$ga_data->setDebug(0);

  /*
  for ($b = 0; isset($submission_goals[$b]) && ($b < 20); $b += 5) {
    $details = array();
    for ($i = $b; isset($submission_goals[$i]); $i++) {
      $id = $submission_goals[$i]['ga_id'];
      $details[] = $id;      
      $goals["n$id"] = $submission_goals[$i]['name'];
    }
    $ga_data->loadFeedData('entrances_goals', 'date', $details);  
  }
  */
 
  $ga_data->setRequestSetting('details', 0);
  $ga_data->setRequestDefaultParam('max_results', 10*31);  
  $ga_data->setRequestSetting('subIndexBy', 'trafficcategory');
  $ga_data->loadFeedData('entrances');
  $ga_data->setRequestSetting('subIndexBy', '');

  $d = $ga_data->data;

  $d['lastmonth'] = isset($last_month_data->data['date']) ? $last_month_data->data : array();
  $d['lastmonth']['daterange'] = array(
    'start' => $lastmonth_start_date,
    'end' => $lastmonth_end_date,
    'days' => $lastmonth_number_of_days,
  );
  
  $request = array(
    //'dimensions' => array('ga:date', 'ga:customVarValue5', 'ga:medium', 'ga:socialNetwork'),
    'dimensions' => array('ga:date', 'ga:dimension5', 'ga:medium', 'ga:socialNetwork'),
    'metrics' => array('ga:entrances'),
    'sort_metric' => '',
    'start_date' => $start_date,
    'end_date' => $end_date,
    //'filters' => 'ga:dimension3=@&k&',
    'filters' => 'ga:dimension3=@&k&',
    'segment' => '',
    'max_results' => 1000,
  );

  $request = array(
    'dimensions' => array('ga:date', 'ga:dimension5', 'ga:medium', 'ga:socialNetwork'),
    'metrics' => array('ga:entrances'),
    'sort_metric' => '-ga:date',
    'start_date' => $start_date,
    'end_date' => $end_date,
    'filters' => '', //ga:dimension3=@&k&',
    //'segment' => 'dynamic::ga:eventCategory=~^Form,ga:dimension3=@&k&',
    'segment' => 'dynamic::ga:eventCategory=~^Form,ga:dimension3=@&k&',
    'max_results' => 1000,
  );  
  
  $data = intel_ga_api_data($request, $cache_options);
  $visitors = array();
  if (!empty($data->results)) {
    // check if v2 or v3 API format
    $rows = intel_get_ga_feed_rows($data);
    foreach ($rows AS $row) {
      $cat = $ga_data->initFeedIndex($row, 'trafficcategory'); 
      $visitors[$row['dimension5']] = $cat;
    }
  }

  $query_alter_vars = array(
    'table_alias' => array(
      'intel_visitor' => 'v',
      'node' => 'n',
    ),
    'report' => array(
      'filters' => $filters,
      'context' => $context,
      'sub_index' => $sub_index,
    )
  );
  
  $query = db_select('intel_visitor', 'v')
    ->fields('v', array('contact_created', 'vid'))
    ->condition('contact_created', $start_date, '>=');
  // enable other modules to alter this query
  $query->addTag('intel_report_dashboard_new_contacts');
  $context = 'report_dashboard_new_contacts';
  drupal_alter('intel_visitor_select_query', $query, $context, $query_alter_vars);

  $result = $query->execute();
  $unknowns = array();
  $d['date']['_all']['lead'] = array(
    'leads' => 0,
    'trafficcategory' => array(),
  );
  while ($row = $result->fetchObject()) {
    $index = date("Ymd", $row->contact_created);
    if (!isset($d['date'][$index]['lead'])) {
      $d['date'][$index]['lead'] = array(
        'leads' => 0,
        'trafficcategory' => array(),
      );
      $vtkids[$index] = array();
    }
    $d['date'][$index]['lead']['leads']++; 
    $d['date']['_all']['lead']['leads']++;
  }
  $query = db_select('intel_visitor', 'v')
    ->fields('v', array('contact_created', 'vid'))
    ->condition('contact_created', $lastmonth_start_date, '>=')
    ->condition('contact_created', $lastmonth_end_date, '<=');
  $query->addExpression('COUNT(v.vid)', 'leads_created');

  // enable other modules to alter this query
  $query->addTag('intel_report_dashboard_new_contacts_count_last_month');
  $context = 'report_dashboard_new_contacts_count_last_month';
  drupal_alter('intel_visitor_select_query', $query, $context, $query_alter_vars);

  $row = $query->execute()->fetchObject();
  $d['lastmonth']['date']['_all']['lead'] = array(
    'leads' => $row->leads_created,
  );
  
  // get nodes published this month
  $query = db_select('node', 'n')
    ->fields('n')
    ->condition('n.status', 1)
    ->condition('n.created', $start_date, '>=');

  // enable other modules to alter this query
  $query->addTag('intel_report_dashboard_new_contacts');
  $context = 'report_dashboard_new_nodes';
  drupal_alter('intel_node_select_query', $query, $context, $query_alter_vars);

  $result = $query->execute();

  $page_filter = '';
  $entrance_filter = '';
  $count = 0;
  while ($row = $result->fetchObject()) {
    $index = date("Ymd", $row->created);
    if (!isset($d['date'][$index]['post'])) {
      $d['date'][$index]['post'] = array();
    }

    $host = variable_get('intel_domain_name', '');
    if (!$host) {
      global $base_url;
      $a = explode('//', $base_url);
      $host = $a[1];
      $host = str_replace('www.', '', $host);
    }
    $path = url('node/' . $row->nid);
    $d['date'][$index]['post'][$row->nid] = array(
      'id' => $row->nid,
      'type' => str_replace('enterprise_', '', $row->type),
      'created' => $row->created,
      'title' => $row->title,
      'author' => $row->uid,
      'path' => $path,
      'host' => $host,
    );
    $page_filter .= (($page_filter) ? ',' : '') . $ga_data->formatPathFilter($path, 'pagePath', TRUE);
    $entrance_filter .= (($entrance_filter) ? ',' : '') . $ga_data->formatPathFilter($path, 'landingPagePath', TRUE);
    $count ++;
  }
  $d['date']['_all']['post'] = array(
    'published' => $count,
  );
  
  $query = db_select('node', 'n')
    ->fields('n')
    ->condition('n.status', 1)
    ->condition('n.created', $lastmonth_start_date, '>=')
    ->condition('n.created', $lastmonth_end_date, '<=');
  $query->addExpression('COUNT(n.nid)', 'published_nodes');

  // enable other modules to alter this query
  $query->addTag('intel_report_dashboard_new_nodes_count_last_month');
  $context = 'report_dashboard_new_nodes_count_last_month';
  drupal_alter('intel_node_select_query', $query, $context, $query_alter_vars);

  $row = $query->execute()->fetchObject();
  $d['lastmonth']['date']['_all']['post'] = array(
    'published' => $row->published_nodes,
  );
  //dsm($row);  
  
  $ga_data = new LevelTen\Intel\GAModel();
  $ga_data->buildFilters($filters, $context);
  $ga_data->setDateRange($start_date, $end_date);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $cache_options));
  $ga_data->setDataIndexCallback('category', '_intel_determine_category_index');
  
  // get recent post entrance stats
  //$regex = $ga_data->formatGtRegexFilter('ga:dimension1', $start_date, 'pt');
  $regex = 'ga:dimension1=@&pd=' . date('Ym', $start_date);
$ga_data->setDebug(0);
  $ga_data->setCustomFilter($regex . ';ga:entrances>0');
  $ga_data->setRequestDefaultParam('max_results', 10 * $count); 
  $request = $ga_data->loadFeedData('entrances', 'content');
$ga_data->setDebug(0);
  $ga_data->setCustomFilter($regex . ';ga:pagePath=@/');
  //$ga_data->setCustomFilter($page_filter);
  $ga_data->setRequestDefaultParam('max_results', 40 * $count); 
  $request = $ga_data->loadFeedData('entrances_events_valued', 'content');
$ga_data->setDebug(0);  
  //$ga_data->setCustomFilter('', 'segment');
  $ga_data->setCustomFilter($regex . ';ga:pagePath=@/');
  //$ga_data->setCustomFilter($page_filter);
  $ga_data->setRequestDefaultParam('max_results', 40 * $count); 
  $request = $ga_data->loadFeedData('pageviews_events_valued', 'content');
//dsm($ga_data->data['content']);   
  $d['content'] = !empty($ga_data->data['content']) ? $ga_data->data['content'] : array();
$ga_data->setDebug(0); 

  // testing
  /*
  $ga_data = new LevelTen\Intel\GAModel();
  $ga_data->buildFilters($filters, $context);
  $ga_data->setDateRange($start_date, $end_date);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $cache_options));
  $ga_data->setDataIndexCallback('category', '_intel_determine_category_index');
  
  $entrance_filter = 'ga:landingPagePath=~/blog/';
  $page_filter = 'ga:pagePath=~/blog/';
  
  $regex = $ga_data->formatGtRegexFilter('ga:dimension1', $start_date, 'pt');

  $ga_data->setCustomFilter($entrance_filter);
  $ga_data->setRequestDefaultParam('max_results', 10 * $count); 
  $request = $ga_data->loadFeedData('entrances', 'content');
 
  $ga_data->setCustomFilter($entrance_filter);
  $ga_data->setRequestDefaultParam('max_results', 40 * $count); 
  $request = $ga_data->loadFeedData('entrances_events_valued', 'content');
  
  $ga_data->setCustomFilter($page_filter);
  $ga_data->setRequestDefaultParam('max_results', 40 * $count); 
  $request = $ga_data->loadFeedData('pageviews_events_valued', 'content');
dsm($ga_data->data['content']);  
  
  //$d['content'] = $ga_data->data['content'];
  */


  
  if (!empty($path)) {
    $created = intel_get_node_created($path);
    $start = ($created > $start_date) ? $created : $start_date;
    $analysis_days = ($end_date - $start) / 86400;
  }
  else {
    $start = $created = $start_date;
    $analysis_days = ($end_date - $start) / 86400;
  }

  
  foreach ($d['date'] AS $index => $de) {
    $score_components = array();
    if ($context == 'page') {
      $d['date'][$index]['score'] = intel_score_page_aggregation($de, 1, $score_components);      
    } 
    else {
      $d['date'][$index]['score'] = intel_score_visit_aggregation($de, 1, $score_components);
    } 
    $d['date'][$index]['score_components'] = $score_components;  
  }

  if (isset($d['content'])) {
    foreach ($d['content'] AS $index => $de) {
      $score_components = array();
      $d['content'][$index]['score'] = intel_score_visit_aggregation($de, 1, $score_components);
      $d['content'][$index]['score_components'] = $score_components;  
    }    
  }
  /*
  if (isset($d['trafficsources'])) {
    $sub_indexes = _intel_get_trafficsource_sub_indexes();
    foreach ($sub_indexes AS $sub_index => $t) {
      foreach ($d['trafficsources'][$sub_index] AS $index => $de) {
        $score_components = array();
        $d['trafficsources'][$sub_index][$index]['score'] = intel_score_visit_aggregation($de, 1, $score_components);
        $d['trafficsources'][$sub_index][$index]['score_components'] = $score_components;  
      } 
    }   
  }
  */
  // order date data cronologically
  ksort($d['date']);

//dsm($d);

  $targets = intel_get_targets();
  if (isset($filters['page-attr'])) {
    $targets['entrances_per_month'] = 0;
    $targets['leads_per_month'] = 0;
    $targets['posts_per_month'] = 0;
  }

  $vars = array(
    'data' => $d,
    'number_of_days' => $number_of_days,
    'start_date' => $start_date,
    'end_date' => $end_datemo,
    'goals' => $goals,
    'targets' => $targets,
    'analysis_days' => $analysis_days,
    'context' => $context,
    'filters' => $filters,
  );
  $output .= theme_intel_dashboard_report($vars);

  $output .= t("Timeframe: %start_date - %end_date %refresh", array(
    '%start_date' => date("Y-m-d H:i", $start_date),
    '%end_date' => date("Y-m-d H:i", $end_date),
    '%refresh' => (!empty($cache_options['refresh'])) ? '(refresh)' : '',
  )); 

  $output .= ' (' . l(t('refresh data'), $_GET['q'], array('query' => array('refresh' => 1))) . ')';
  
  return $output;  
}

function theme_intel_dashboard_report($vars) {
  intel_include_library_file("reports/class.dashboard_report_view.php");
  $output = '';

  $report_view = new LevelTen\Intel\DashboardReportView();
  $report_view->setData($vars['data']);
  //$report_view->setTableRowCount($vars['row_count']);
  //$report_view->setModes($vars['report_modes']);
  //$report_view->setParam('indexBy', $vars['indexBy']);
  //$report_view->setParam('indexByLabel', $vars['indexByLabel']);
  //$report_view->setObjective('entrances_per_month', 3000);
  //$report_view->setObjective('leads_per_month', 30);
  //$report_view->setObjective('posts_per_month', 8);

  $report_view->setTargets($vars['targets']);
  $report_view->setGoals($vars['goals']);
  $report_view->setDateRange($vars['start_date'], $vars['end_date']);
  $report_view->setLibraryUri(libraries_get_path('LevelTen') . '/Intel');
  \LevelTen\Intel\ReportPageHeader::setAddScriptCallback('intel_report_add_js_callback');
  $output .= $report_view->renderReport();
  
  return $output; 
}