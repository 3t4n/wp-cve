<?php
/**
 * @file
 * Generates scorecard reports
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

/**
 * @param string $context
 * @param string $filter_type -
 *  page-attr: page attribute
 * @param string $filter_element - key for attribute, e.g. 'a' = author
 * @param string $filter_value - value for attribute, e.g. 5 = nid 5
 *
 * @return string
 */
function intel_scorecard_report_page($report_subtype = '-', $report_subfilter = '-', $entity_type = '-', $entity = '-') {
  // TODO WP hack for debugging
  $_GET['return_type'] = 'nonajax';
//$args = func_get_args();
//dsm($args);
  require_once INTEL_DIR . "includes/intel.reports.php";
  require_once INTEL_DIR . "includes/intel.ga.php";
  $output = '';

  $vars = array();
  if (!empty($entity_type) && $entity_type != '-') {
    $vars['entity_type'] = $entity_type;
    $vars['entity'] = $entity;
  }
  $report_params = !empty($_GET['report_params']) ? $_GET['report_params'] : '-';
  $vars = intel_init_reports_vars('scorecard', 'scorecard', $report_params, $report_subtype, $report_subfilter, $vars);
  $vars['title'] = $title = Intel_Df::t('Scorecard');

  $output = intel_build_report($vars);

  return $output;
}

//function intel_scorecard_report($filters = array(), $context = '-', $sub_index = '-', $mode = '') {
function intel_scorecard_report($vars) {
  intel_include_library_file('ga/class.ga_model.php');
  require_once INTEL_DIR . "includes/intel.page_data.php";

  $filters = $vars['filters'];
  $context = $vars['context'];
  $context_mode = $vars['context_mode'];

  $cache_options = $vars['cache_options'];
  $row_count = 100;
  
  $output = '';

  $start_date = $vars['start_date'];
  $end_date = $vars['end_date'];
  $number_of_days = $vars['number_of_days'];
  //$timeops = $vars['timeframe'];
  //$timeops = 'yesterday';
  //list($start_date, $end_date, $number_of_days) = _intel_get_report_dates_from_ops($timeops, $cache_options);

  $ga_data = new LevelTen\Intel\GAModel();
//$ga_data->setDebug(0);
  $ga_data->setContext($vars['context']);
  $ga_data->setContextMode($vars['context_mode']);
  $ga_data->setAttributeInfoAll($vars['attribute_info']);
  $ga_data->buildFilters($filters, $vars['subsite']);
  $ga_data->setDateRange($start_date, $end_date);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $cache_options));
  $ga_data->setFeedRowsCallback('intel_get_ga_feed_rows_callback');

  $ga_data->setRequestSetting('indexBy', 'date');
  $ga_data->setRequestSetting('details', 0);
  // note: add +1 to number of days incase timeframe is not an exact day, e.g. l30dfn
  $ga_data->setRequestDefaultParam('max_results', ($number_of_days+1));

  //$ga_data->setDebug(1);
  $ga_data->loadFeedData('pageviews');
  //$ga_data->setDebug(0);

  //$ga_data->setDebug(1);
  $ga_data->loadFeedData('entrances');
  //$ga_data->setDebug(0);

  //$ga_data->loadFeedData('visitors');

  if ($context == 'page' || $context == 'page-attr' || $context_mode == 'subsite') {
    $ga_data->loadFeedData('sessions');
  }

  $ga_data->setRequestSetting('details', 1);
  $ga_data->setRequestDefaultParam('max_results', 10 *  ($number_of_days+1));
  $ga_data->setDebug(1);
  $ga_data->loadFeedData('pageviews_events_valued', 'date', 1);
  $ga_data->setDebug(0);

  //d($ga_data->data);
  //return;

  if ($context == 'page' || $context == 'page-attr') {
    $ga_data->loadFeedData('entrances_events_valued', 'date', 1);
  }

  //$ga_data->setRequestSetting('details', 1);
  $ga_data->setRequestDefaultParam('max_results', 5 *  ($number_of_days+1));
  $goals = array();
  //$submission_goals = get_option('intel_submission_goals', intel_get_submission_goals_default());
  //intel_d($submission_goals);

  $submission_goals = get_option('intel_goals', array());
  $i = 0;

  foreach ($submission_goals AS $key => $goal) {
    if ($i == 0) {
      $details = array();
    }
    $id = $submission_goals[$key]['ga_id'];
    $details[] = $id;      
    $goals["n$id"] = $submission_goals[$key]['title'];
    $i++;
    if ($i >= 5) {
      if ($context == 'page' || $context == 'page-attr') {
        $ga_data->loadFeedData('entrances_goals', 'date', $details);
      }
      else {
        $ga_data->loadFeedData('pageviews_goals', 'date', $details);
      }

      $i = 0;
    } 
  }
  if ($i > 0) {
    if ($context == 'page' || $context == 'page-attr') {
      $ga_data->loadFeedData('entrances_goals', 'date', $details);
    }
    else {
      $ga_data->loadFeedData('pageviews_goals', 'date', $details);
    }
  }

  if ($context == 'page-attr' || $context_mode == 'subsite') {
    $ga_data->loadFeedData('sessions_goals', 'date', 0);
  }

  // get top content data
  if ($context != 'page') {
    $ga_data->setRequestDefaultParam('max_results', 200);
    $request = $ga_data->loadFeedData('pageviews', 'content', 0);
    
    $ga_data->setRequestDefaultParam('max_results', 50); 
    $request = $ga_data->loadFeedData('entrances', 'content', 0);
    
    $ga_data->setRequestDefaultParam('max_results', 200);
    $request = $ga_data->loadFeedData('pageviews_events_valued', 'content', 0);

    $ga_data->setRequestDefaultParam('max_results', 200);
    $request = $ga_data->loadFeedData('entrances_events_valued', 'content', 0);
  }

  // get top traffic source data
  if ($context != 'trafficsource') {
    $ga_data->setRequestDefaultParam('max_results', 100);
    $request = $ga_data->loadFeedData('entrances', 'trafficsources');

    $request = $ga_data->loadFeedData('entrances_events_valued', 'trafficsources');
  }
  $d = $ga_data->data;

  if (!empty($path)) {
    $created = intel_get_node_created($path);
    $start = ($created > $start_date) ? $created : $start_date;
    $analysis_days = ($end_date - $start) / 86400;
  }
  else {
    $start = $created = $start_date;
    $analysis_days = ($end_date - $start) / 86400;
  }

  if (!empty($d['date'])) {
    foreach ($d['date'] AS $index => $de) {
      $score_components = array();
      $method = 'site';
      if ($context == 'page' || $context == 'page-attr') {
        $method = 'page';
      }
      $d['date'][$index]['score'] = intel_score_item($de, 1, $score_components, '', $method);
      $d['date'][$index]['score_components'] = $score_components;
    }
    $d['date']['_links'] = array(
      'events' => array(),
      'goals' => array(),
      'events_pageview' => array(),
      'goals_pageview' => array(),
    );
  }

  $more_link_options = array(
    'attributes' => array(
      'class' => array(
        'intel-more'
      ),
    ),
  );
  if ($vars['context'] == 'page' || $vars['context'] == 'page-attr') {
    $more_link_options['query'] = intel_build_report_query('event', $vars['report_params']);
    // TODO WP: reenable links after Events reports working again
    //$d['date']['_links']['events'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('event', $vars['report_params'], 'entrance_valued'), $more_link_options);
    //$d['date']['_links']['goals'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('event', $vars['report_params'], 'entrance_goal'), $more_link_options);
    //$d['date']['_links']['events_pageview'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('event', $vars['report_params'], 'pageview_valued'), $more_link_options);
  }
  else {
    $more_link_options['query'] = intel_build_report_query('event', $vars['report_params']);
    // TODO WP: reenable links after Events reports working again
    //$d['date']['_links']['events'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('event', $vars['report_params'], 'valued'), $more_link_options);
    //$d['date']['_links']['goals'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('event', $vars['report_params'], 'goal'), $more_link_options);
  }



  //$params = $vars['report_params'];
  //$params['_filter_add'] = 'pa-i:t';
  //$d['date']['_links']['i.t'][] = Intel_Df::l(t('more'), intel_build_report_path('content', $params));
//dsm($d['date']['_links']);

  if (isset($d['content'])) {
    foreach ($d['content'] AS $index => $de) {
      $score_components = array();

      //$d['content'][$index]['score'] = intel_score_page_aggregation($de, 1, $score_components, '', array('method' => 'direct'));
      //$d['content'][$index]['score'] = intel_score_page_aggregation($de, 1, $score_components);
      $d['content'][$index]['score'] = intel_score_item($de, 1, $score_components, '', 'page');
      $d['content'][$index]['score_components'] = $score_components;
    }
    $d['content']['_links'] = array(
      'default' => array(),
      'i.t' => array(),
    );
    $more_link_options['query'] = intel_build_report_query('content', $vars['report_params']);
    $d['content']['_links']['default'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('content', $vars['report_params']), $more_link_options);
    $params = $vars['report_params'];
    $params['_filter_add'] = 'pa-i:t';
    $d['content']['_links']['i.t'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('content', $params), $more_link_options);
  }
  if (isset($d['trafficsources'])) {
    $sub_indexes = $ga_data->getTrafficsourcesSubIndexes();
    foreach ($sub_indexes AS $sub_index) {
      foreach ($d['trafficsources'][$sub_index] AS $index => $de) {
        $score_components = array();
        //$d['trafficsources'][$sub_index][$index]['score'] = intel_score_visit_aggregation($de, 1, $score_components);
        $d['trafficsources'][$sub_index][$index]['score'] = intel_score_item($de, 1, $score_components, '', 'entrance');
        $d['trafficsources'][$sub_index][$index]['score_components'] = $score_components;
      }
      $more_link_options['query'] = intel_build_report_query('trafficsource', $vars['report_params']);
      $d['trafficsources'][$sub_index]['_links'] = array();
      $d['trafficsources'][$sub_index]['_links'][] = Intel_Df::l(Intel_Df::t('more'), intel_build_report_path('trafficsource', $vars['report_params'], $sub_index), $more_link_options);
    }
  }
  // order date data cronologically
  if (!empty($d['date'])) {
    ksort($d['date']);
  }
  else {
    $d['_empty'] = 1;
    //return Intel_Df::t('No data found.');
  }

  /*
  $vars = array(
    'data' => $d,
    'row_count' => $row_count,
    'number_of_days' => $number_of_days,
    'start_date' => $start_date,
    'end_date' => $end_date,
    'goals' => $goals,
    'analysis_days' => $analysis_days,
    'context' => $context,
    'context_mode' => $context_mode,
    'report_modes' => array(),
    //'report_modes' => $report_modes,
  );
  */

  $vars['goals'] = $goals;
  $vars['data'] = $d;
  $output .= theme_intel_report($vars);

  return $output;

  /*
  $output .= theme_intel_content_scorecard($vars);


  $output .= Intel_Df::t("Timeframe: %start_date - %end_date %refresh", array(
    '%start_date' => date("Y-m-d H:i", $start_date),
    '%end_date' => date("Y-m-d H:i", $end_date),
    '%refresh' => (!empty($cache_options['refresh'])) ? '(refresh)' : '',
  ));

  if (!empty($cache_options['round_start']) || !empty($cache_options['round_end'])) {
    $cache_time = 900;

    $start = $vars['start_date'];
    if (!empty($cache_options['round_start'])) {
      $start = $cache_time * floor($start / $cache_time);
    }
    $end = $vars['end_date'];
    if (!empty($cache_options['round_end'])) {
      $end = $cache_time * floor($end / $cache_time);
    }

    $output .= Intel_Df::t("(cache: %start_date - %end_date)", array(
      '%start_date' => date("Y-m-d H:i", $start),
      '%end_date' => date("Y-m-d H:i", $end),
    ));
  }
  
  return $output;
  */
}

function theme_intel_content_scorecard($vars) {
  intel_include_library_file("reports/class.scorecard_report_view.php");

  $output = '';
  $report_view = new LevelTen\Intel\ScorecardReportView();
  $report_view->setData($vars['data']);
  $report_view->setTableRowCount($vars['row_count']);
  $report_view->setModes($vars['report_modes']);
  $report_view->setParam('context', $vars['context']);
  $report_view->setParam('context_mode', $vars['context_mode']);
  $report_view->setParam('analysis_days', $vars['analysis_days']);
  $report_view->setParam('ga_property_slug', intel_get_ga_property_slug());
  $report_view->setPageMetaCallback('intel_get_page_meta_callback');
  $report_view->setTargets(intel_get_targets());
  $report_view->setGoals($vars['goals']);
  $report_view->setDateRange($vars['start_date'], $vars['end_date']);
  \LevelTen\Intel\ReportPageHeader::setAddScriptCallback('intel_report_add_js_callback');
  $output .= $report_view->renderReport();

  return $output;
}